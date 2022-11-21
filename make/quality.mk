.PHONY: twc.stan twc.fixer twc.fix twc.rules twc.gitadd twc.fixdroits twc.test

twc.stan: ./quality/phpstan.neon
	vendor/bin/phpstan analyse -c ./quality/phpstan.neon

twc.fixer: ./quality/.php-cs-fixer.dist.php
	vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config=./quality/.php-cs-fixer.dist.php fix ./src

twc.fix:
	vendor/bin/phpcbf --standard=PSR12 ./src || true

twc.gitadd:
	git add .
	
twc.test: phpunit.xml.dist
	bin/phpunit

twc.fixdroits:
	chmod 777 .git/hooks/pre-commit
	chmod 777 .git/hooks/commit-msg
	chmod 777 quality/commit-rules.sh

twc.rules:
	vendor/bin/phpcs --standard=PSR12 ./src -n

twc.quality: twc.fixer twc.fix twc.rules twc.gitadd twc.stan twc.test