<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPKOI_ELEMENTS_LITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPKOI_ELEMENTS_LITE_URL', plugins_url( '/', __FILE__ ) );
define( 'WPKOI_ELEMENTS_LITE_VERSION', '1.2.0' );

// Includes
require_once plugin_dir_path( __FILE__ ) . 'includes/elementor-helper.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpkoi-elements-integration.php';

// options for effects
$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );

if ( $wtfe_element_effects  != true ) {
	require_once plugin_dir_path( __FILE__ ) . 'elements/effects/effects.php';
}