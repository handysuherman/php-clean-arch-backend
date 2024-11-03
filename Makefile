.PHONY: test-src
test-src:
	vendor/bin/phpunit --testdox src/tests

.PHONY: install-yii
install-yii:
	composer create-project --prefer-dist yiisoft/yii2-app-basic myyiiapp

.PHONY: migrate-create
migrate-create:
	php vendor/bin/doctrine-migrations generate

.PHONY: migrateup
migrateup:
	./vendor/bin/doctrine-migrations migrate --all-or-nothing --no-interaction

.PHONY: migratedown
migratedown:
	./vendor/bin/doctrine-migrations migrate 0 --no-interaction

.PHONY: migratestatus
migratestatus:
	php vendor/bin/doctrine-migrations status --show-versions

.PHONY: migratetest
migratetest:
	./vendor/bin/doctrine-migrations migrate --dry-run

.PHONY: all-paseto
all-paseto: gen_priv_key gen_pub_key

.PHONY: gen_priv_key
gen_priv_key:
	openssl genpkey -algorithm ED25519 -out private.pem

.PHONY: gen_pub_key
gen_pub_key:
	openssl pkey -in private.pem -pubout -out public.pem