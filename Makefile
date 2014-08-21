#
# This file is part of the segony package.
#
# For the full copyright and license information, please view the LICENSE.md
# file that was distributed with this source code.
#

.PHONY: install test

PHP55_EXECUTABLE = which php5x5

ifndef PHP
	ifdef PHP55_EXECUTABLE
		PHP = php55
	else
		PHP = php
	endif
endif

all: install

clean:
	rm -rf ./dist
	rm -rf ./vendor

test:
	@$(PHP) vendor/bin/phpunit

docs:
	@$(PHP) vendor/bin/phpdoc -d src/ -t dist/php-doc

install:
	@make segony
	@echo "Depending on the size of the update, this may take"
	@echo "several minutes."
	@echo ""
	@echo "+--------------------------------------------------+"
	@echo "| # NOTE ######################################### |"
	@echo "+--------------------------------------------------+"
	@echo "| Once the process is corrupted it is necessary to |"
	@echo "| run 'make clean'!                                |"
	@echo "+--------------------------------------------------+"
	@mkdir -p dist/
	@curl -sS https://getcomposer.org/installer | php > /dev/null 2>&1
	@echo ""
	@$(PHP) composer.phar install

help:
	@make segony
	@echo " Usage: make [options] [command]"
	@echo ""
	@echo " Commands:"
	@echo ""
	@echo "     install		Runs the installation"
	@echo "     clean 		Clean up"
	@echo "     docs		Generate the doc files"
	@echo "		test		Runs PHPUnit"
	@echo ""

segony:
	@echo ""
	@echo ""
	@echo "  ____     __     __     ___     ___   __  __    "
	@echo " /',__\\  /'__\`\\ /'_ \`\\  / __\`\\ /' _ \`\\/\\ \\/\\ \\   "
	@echo "/\\__, \`\\/\\  __//\\ \\L\\ \\/\\ \\L\\ \\/\\ \\/\\ \\ \\ \\_\\ \\  "
	@echo "\\/\\____/\\ \\____\\ \\____ \\ \\____/\\ \\_\\ \\_\\/\`____ \\ "
	@echo " \\/___/  \\/____/\\/___L\\ \\/___/  \\/_/\\/_/\`/___/> \\"
	@echo "                  /\\____/                  /\\___/"
	@echo "                  \\_/__/                   \\/__/ "
	@echo ""