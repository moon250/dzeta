.PHONY: fix
fix:
	vendor/bin/pint

.PHONY: migrate
migrate:
	php artisan migrate:fresh

.PHONY: lint
lint:
	vendor/bin/phpstan analyse --memory-limit=2G

.PHONY: tests
tests:
	php artisan test

.PHONY: tt
tt:
	phpunit-watcher watch
