@ECHO OFF
codecept run unit && codecept run wpunit && vendor/bin/phpstan analyse && vendor/bin/phpcs -p -s --standard=phpcs.xml src
