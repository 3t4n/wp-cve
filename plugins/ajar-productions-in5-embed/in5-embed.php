<?php
/*
* In this file, we initialize everything that we need.
* This includes: constants, assets, call on files necessary for plugin to work, etc.
*/
define( 'IN5_PLUGIN_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'IN5_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'gutenberg/src/init.php';

require_once IN5_PLUGIN_PATH . 'includes/assets.php';
require_once IN5_PLUGIN_PATH . 'includes/init.php';