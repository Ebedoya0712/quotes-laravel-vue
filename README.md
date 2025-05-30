# Quotes Package

## Description

Laravel package to consume and display quotes from [dummyjson.com/quotes](https://dummyjson.com/quotes) with a Vue.js frontend, rate limiting, local cache, and tests.

---

## Installation

1. Copy the `packages/quotes` folder into your Laravel project.
2. Add the autoload to your main `composer.json`:
   ```json
   "autoload": {
       "psr-4": {
           "Quotes\\": "packages/quotes/src/"
       }
   }
   ```
   Then run:
   ```bash
   composer dump-autoload
   ```
3. Register the ServiceProvider in `config/app.php`:
   ```php
   'providers' => [
       // ...
       Quotes\QuotesServiceProvider::class,
   ],
   ```

---

## Configuration

- Publish the config file:
  ```bash
  php artisan vendor:publish --provider="Quotes\QuotesServiceProvider" --tag=quotes-config
  ```
- Edit `config/quotes.php` to customize the base URL, rate limit, and window duration.

---

## Build and publish the frontend

1. Install JS dependencies inside `packages/quotes`:
   ```bash
   cd packages/quotes
   npm install
   npm run build
   ```
2. Publish the compiled assets:
   ```bash
   php artisan vendor:publish --provider="Quotes\QuotesServiceProvider" --tag=quotes-assets
   ```
   This will copy the files to `public/vendor/quotes`.

---

## Access the UI

- Open: [http://localhost:8000/quotes-ui](http://localhost:8000/quotes-ui)

---

## API

- `GET /api/quotes` — All quotes
- `GET /api/quotes/random` — Random quote
- `GET /api/quotes/{id}` — Quote by ID

---

## Customizing the frontend

- Modify files in `packages/quotes/resources/js` and run:
  ```bash
  npm run build
  php artisan vendor:publish --provider="Quotes\QuotesServiceProvider" --tag=quotes-assets --force
  ```
- The published assets are in `public/vendor/quotes`.

---

## Testing

To run all tests from the root of your Laravel project:
```bash
./vendor/bin/pest
```

To run only the package tests:
```bash
./vendor/bin/pest packages/quotes/tests
```

You can also use PHPUnit if you prefer:
```bash
./vendor/bin/phpunit
```

---

## Features

- API client service with rate limiting and efficient local cache.
- API routes for frontend consumption.
- Vue.js interface with pagination, search by ID, and random quote.
- Asset and config publishing.
- Ready-to-use unit and feature tests.

---
