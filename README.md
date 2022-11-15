## Library Backend

This is the backend for the library application. It is a REST API written in PHP using the Laravel framework.

### How to run

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and fill in the database credentials   
4. Run `php artisan migrate`
5. Run `php artisan db:seed`
6. Run `php artisan serve`

### How to run tests
The tests are written using PEST. To run them, run `vendor/bin/phpunit`. or simply run `php artisan test`. in the root directory.

Also configure your test database by creating a  `.env.testing` file. Copy the contents of `.env` and change the database credentials to your test database.
 Then run `php artisan test --env=testing` to run the tests.



