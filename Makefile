#
# This file is part of the segony package.
#
# For the full copyright and license information, please view the LICENSE.md
# file that was distributed with this source code.
#
all: install

clean:
	rm -rf ./dist
	rm -rf ./vendor

install:
	mkdir -p dist/
	curl -sS https://getcomposer.org/installer | php
	php composer.phar install

test:
	php vendor/bin/phpunit

docs:
	php vendor/bin/phpdoc -d src/ -t dist/php-doc