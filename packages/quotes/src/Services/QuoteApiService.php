<?php

namespace Quotes\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuoteApiService
{
    protected string $baseUrl;
    protected int $rateLimit;
    protected int $rateLimitWindow;
    protected array $cache = [];
    protected array $requestTimestamps = [];

    public function __construct()
    {
        $this->baseUrl = config('quotes.base_url');
        $this->rateLimit = config('quotes.rate_limit');
        $this->rateLimitWindow = config('quotes.rate_limit_window');
        $this->cache = Cache::get('quotes_cache', []);
        $this->requestTimestamps = Cache::get('quotes_requests', []);
    }

    protected function throttle()
    {
        $now = time();
        // Elimina timestamps fuera de la ventana
        $this->requestTimestamps = array_filter(
            $this->requestTimestamps,
            fn($timestamp) => $timestamp > ($now - $this->rateLimitWindow)
        );
        if (count($this->requestTimestamps) >= $this->rateLimit) {
            $wait = $this->rateLimitWindow - ($now - min($this->requestTimestamps));
            sleep(max(1, $wait));
            $this->throttle();
        }
        $this->requestTimestamps[] = $now;
        Cache::put('quotes_requests', $this->requestTimestamps, $this->rateLimitWindow);
    }

    public function getAllQuotes()
    {
        $this->throttle();
        $response = Http::get("{$this->baseUrl}/quotes");
        $quotes = $response->json('quotes', []);
        foreach ($quotes as $quote) {
            $this->storeInCache($quote);
        }
        return $quotes;
    }

    public function getRandomQuote()
    {
        $this->throttle();
        $response = Http::get("{$this->baseUrl}/quotes/random");
        $quote = $response->json();
        $this->storeInCache($quote);
        return $quote;
    }

    public function getQuote(int $id)
    {
        $quote = $this->binarySearchCache($id);
        if ($quote) {
            return $quote;
        }
        $this->throttle();
        $response = Http::get("{$this->baseUrl}/quotes/{$id}");
        $quote = $response->json();
        $this->storeInCache($quote);
        return $quote;
    }

    protected function storeInCache(array $quote)
    {
        if (!isset($quote['id'])) return;
        // Insertar ordenado por id
        $this->cache[] = $quote;
        usort($this->cache, fn($a, $b) => $a['id'] <=> $b['id']);
        Cache::put('quotes_cache', $this->cache, 3600);
    }

    protected function binarySearchCache(int $id)
    {
        $low = 0;
        $high = count($this->cache) - 1;
        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $midId = $this->cache[$mid]['id'] ?? null;
            if ($midId === $id) {
                return $this->cache[$mid];
            } elseif ($midId < $id) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        return null;
    }
}
