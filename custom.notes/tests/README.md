## Tests

Run PHPUnit tests:

```bash
composer install
composer require --dev phpunit/phpunit:^9
vendor/bin/phpunit local/modules/custom.notes/tests --testdox

## Результат
OK (4 tests, 6 assertions)

## Docker
# Внутри контейнера PHP:

docker compose exec php vendor/bin/phpunit local/modules/custom.notes/tests
