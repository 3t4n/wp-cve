<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Quantity_Increment_Buttons_Init {

    function __construct() {
 
    	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    }

    public function enqueue_scripts() {

        $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    	wp_register_script( 'wcqib-js', plugins_url( 'assets/js/wc-quantity-increment-buttons' . $min . '.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ) );
    	wp_register_style( 'wcqib-css', plugins_url( 'assets/css/wc-quantity-increment-buttons.css', plugin_dir_path( __FILE__ ) ) );
    	wp_register_script( 'wcqib-number-polyfill', plugins_url( 'assets/js/lib/number-polyfill.min.js', plugin_dir_path( __FILE__ ) ) );

    	wp_enqueue_script( 'wcqib-js' );
    	wp_enqueue_style( 'wcqib-css' );
    }

}

new WC_Quantity_Increment_Buttons_Init;