<?php

declare (strict_types=1);
namespace WPPayVendor;

use WPPayVendor\Rector\Config\RectorConfig;
use WPPayVendor\Rector\Set\ValueObject\LevelSetList;
return static function (\WPPayVendor\Rector\Config\RectorConfig $rectorConfig) {
    $rectorConfig->paths([__DIR__ . '/src']);
    $rectorConfig->sets([\WPPayVendor\Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_74]);
};
