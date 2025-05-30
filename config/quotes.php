<?php

return [
    'base_url' => env('QUOTES_API_BASE_URL', 'https://dummyjson.com'),
    'rate_limit' => env('QUOTES_API_RATE_LIMIT', 10), // Máximo de solicitudes por ventana
    'rate_limit_window' => env('QUOTES_API_RATE_LIMIT_WINDOW', 60), // Ventana en segundos
];
