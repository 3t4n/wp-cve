<?php

require __DIR__ . DIRECTORY_SEPARATOR
        . 'classes' . DIRECTORY_SEPARATOR
        . 'core' . DIRECTORY_SEPARATOR
        . 'class-wc-bpost-shipping-core-autoload.php';

// Plugin autoloader
spl_autoload_register( array( 'WC_BPost_Shipping\Core\WC_BPost_Shipping_Core_Autoload', 'load' ) );

// Composer autoloader
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
