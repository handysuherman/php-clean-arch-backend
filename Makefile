.PHONY: test-src
test-src:
	vendor/bin/phpunit --testdox src/tests

.PHONY: install-yii
install-yii:
	composer create-project --prefer-dist yiisoft/yii2-app-basic myyiiapp