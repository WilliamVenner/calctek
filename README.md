# calctek

Take-home exercise to produce an advanced calculator.

## Technologies used

* PHP 8.0 - backend language
* Vue 3 - JavaScript framework
* Laravel 9 - PHP framework
* Inertia - Vue adapter for Laravel
* Tailwind CSS - CSS framework
* Composer - PHP package manager
* PHPUnit - backend testing
* Cypress - frontend testing

## Relevant files

Below are the relevant files and folders for the exercise:

* [`routes/web.php`](routes/web.php)
* [`app/Http/Controllers/CalcController`](app/Http/Controllers/CalcController)
* [`app/Http/Controllers/CalcController/Lexer`](app/Http/Controllers/CalcController/Lexer)
* [`app/Http/Controllers/CalcController/Parser`](app/Http/Controllers/CalcController/Parser)
* [`app/Http/Controllers/CalcController/Evaluator`](app/Http/Controllers/CalcController/Evaluator)
* [`app/Models/CalcHistoryEntry.php`](app/Models/CalcHistoryEntry.php)
* [`resources/js/Pages/Calculator.vue`](resources/js/Pages/Calculator.vue)
* [`resources/js/Components/Calculator/HistorySidebar.vue`](resources/js/Components/Calculator/HistorySidebar.vue)
* [`database/migrations/2024_06_10_144556_create_calc_history_entries_table.php`](database/migrations/2024_06_10_144556_create_calc_history_entries_table.php)
* [`tests/cypress/e2e/calculator.cy.js`](tests/cypress/e2e/calculator.cy.js)
* [`tests/Feature/CalcBasicTest.php`](tests/Feature/CalcBasicTest.php)
* [`tests/Feature/CalcEvalFunctionTest.php`](tests/Feature/CalcEvalFunctionTest.php)
* [`tests/Feature/CalcEvalOperatorTest.php`](tests/Feature/CalcEvalOperatorTest.php)
* [`tests/Feature/CalcEvalRandTest.php`](tests/Feature/CalcEvalRandTest.php)
* [`tests/Feature/CalcEvalTest.php`](tests/Feature/CalcEvalTest.php)
* [`tests/Feature/CalcHistoryTest.php`](tests/Feature/CalcHistoryTest.php)

## Development

```bash
npm install
composer install
mv .env.example .env
php artisan key:generate --no-interaction --force
php artisan migrate --seed --no-interaction --force
npm run dev
```

## Testing

```bash
npm run test:backend
npm run test:frontend
```

or

```bash
npm run test
```

## Media

### General Usage

https://github.com/WilliamVenner/calctek/assets/14863743/d48586ea-3687-40d3-82dc-56b3b83a382b

### Responsiveness

https://github.com/WilliamVenner/calctek/assets/14863743/c57186bb-d911-4dcc-b50e-31fc452bbf0a
