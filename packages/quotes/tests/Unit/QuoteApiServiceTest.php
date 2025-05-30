<?php

uses(\Tests\TestCase::class);

use Quotes\Services\QuoteApiService;

it('puede obtener todas las citas', function () {
    $service = app(QuoteApiService::class);
    $quotes = $service->getAllQuotes();
    expect($quotes)->toBeArray();
    expect($quotes)->not->toBeEmpty();
});

it('puede obtener una cita aleatoria', function () {
    $service = app(QuoteApiService::class);
    $quote = $service->getRandomQuote();
    expect($quote)->toBeArray();
    expect($quote)->toHaveKey('id');
    expect($quote)->toHaveKey('quote');
});

it('puede obtener una cita por ID', function () {
    $service = app(QuoteApiService::class);
    $quote = $service->getQuote(1);
    expect($quote)->toBeArray();
    expect($quote['id'])->toBe(1);
});
