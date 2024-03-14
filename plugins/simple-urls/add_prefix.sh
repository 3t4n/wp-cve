rm -rf vendor
rm -rf vendor-bin
rm -rf vendor-prefix
rm -rf composer.lock
composer install

composer require --dev bamarni/composer-bin-plugin
composer bin php-scoper config minimum-stability dev
composer bin php-scoper config prefer-stable true 
composer bin php-scoper require --dev humbug/php-scoper

vendor/bin/php-scoper add-prefix --output-dir vendor-prefix --force
composer dump-autoload --working-dir vendor-prefix
# composer dump-autoload --working-dir vendor-prefix --classmap-authoritatives

php vendor-prefix-fixer.php

composer remove --dev bamarni/composer-bin-plugin
