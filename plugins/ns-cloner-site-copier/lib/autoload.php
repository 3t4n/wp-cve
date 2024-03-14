<?php

if ( ! defined( 'NS_CLONER_V4_PLUGIN_DIR' ) ) {
    exit; // Exit if accessed directly.
}

// Load external libraries.
require_once NS_CLONER_V4_PLUGIN_DIR . 'lib/vendor/autoload.php';

// Load Background processor.
require_once NS_CLONER_V4_PLUGIN_DIR . 'lib/wp-background-processing/wp-background-processing.php';
