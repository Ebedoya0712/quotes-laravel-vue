<?php

uses(\Tests\TestCase::class);

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    // Finge respuestas de la API externa si lo deseas
    Http::fake([
        'https://dummyjson.com/quotes' => Http::response([
            'quotes' => [
                ['id' => 1, 'quote' => 'Test quote', 'author' => 'Test Author'],
            ]
        ], 200),
        'https://dummyjson.com/quotes/random' => Http::response([
            'id' => 1, 'quote' => 'Test quote', 'author' => 'Test Author'
        ], 200),
        'https://dummyjson.com/quotes/1' => Http::response([
            'id' => 1, 'quote' => 'Test quote', 'author' => 'Test Author'
        ], 200),
    ]);
});

it('devuelve todas las citas por la ruta API', function () {
    $response = $this->get('/api/quotes');
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => 1]);
});

it('devuelve una cita aleatoria por la ruta API', function () {
    $response = $this->get('/api/quotes/random');
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => 1]);
});

it('devuelve una cita específica por ID por la ruta API', function () {
    $response = $this->get('/api/quotes/1');
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => 1]);
});
