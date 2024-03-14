<?php

declare(strict_types=1);

use CKPL\Pay\Autoload;

require __DIR__.'/definitions.php';
require __DIR__.'/functions.php';
require __DIR__.'/../src/Autoload.php';

spl_autoload_register(
    Autoload::class.'::resolve'
);
