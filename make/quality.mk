.PHONY: twc.stan twc.fixer twc.fix twc.gitadd twc.fixdroits twc.test

twc.stan: ./quality/phpstan.neon
	vendor/bin/phpstan analyse -c ./quality/phpstan.neon --level=0 ./src

twc.fixer: ./quality/.php_cs.dist
	vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config=./quality/.php_cs.dist fix ./src

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

twc.quality: twc.fixer twc.fix twc.gitadd twc.stan twc.test