<?php

namespace Quotes\Controllers;

use Illuminate\Http\Request;
use Quotes\Services\QuoteApiService;

class QuoteController
{
    protected $service;

    public function __construct(QuoteApiService $service)
    {
        $this->service = $service;
    }

    public function all()
    {
        return response()->json($this->service->getAllQuotes());
    }

    public function random()
    {
        return response()->json($this->service->getRandomQuote());
    }

    public function show($id)
    {
        return response()->json($this->service->getQuote((int)$id));
    }
}
