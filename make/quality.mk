TWC_INSIGHTS_CODE?=90
TWC_INSIGHTS_COMPLEXITY?=80
TWC_INSIGHTS_ARCHITECTURE?=80
TWC_INSIGHTS_STYLE?=95

.PHONY: twc.stan twc.insights twc.insights-ci twc.fixer twc.fix twc.gitadd twc.fixdroits twc.test

twc.stan: ./quality/phpstan.neon
	vendor/bin/phpstan analyse -c ./quality/phpstan.neon --level=0 ./src

twc.insights: ./quality/phpinsights.php
	vendor/bin/phpinsights --config-path=./quality/phpinsights.php

twc.insights.ci: ./quality/phpinsights.php
	vendor/bin/phpinsights --config-path=./quality/phpinsights.php --no-interaction --min-quality=$(TWC_INSIGHTS_CODE) --min-complexity=$(TWC_INSIGHTS_COMPLEXITY) --min-architecture=$(TWC_INSIGHTS_ARCHITECTURE) --min-style=$(TWC_INSIGHTS_STYLE)

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

twc.quality: twc.fixer twc.fix twc.gitadd twc.stan twc.insights.ci twc.test