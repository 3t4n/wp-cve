<?php

/*
* Plugin Name: VC Simple All Responsive
* Plugin URI: https://wordpress.org/plugins/vc-simple-all-responsive/
* Description: Makes it easier to develop responsive websites when using WPBakery Page Builder. Works on elements within columns.
* Version: 1.3
* Author: Obren Markov
* Author URI: http://markovobren.vojvodina.xyz/en/
* License: GPL-2.0+ 
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: vc-simple-all-responsive
* Domain Path: 
*/

// Before VC Init

add_action( 'vc_before_init', 'vc_before_init_actions' );

function vc_before_init_actions() {

// Require new custom Element

include( plugin_dir_path( __FILE__ ) . 'vc-sar-element.php');

}

// Link directory stylesheet

function simple_responsive_scripts() {
    wp_enqueue_style( 'simple_responsive_stylesheet',  plugin_dir_url( __FILE__ ) . 'assets/media.css' );
}
add_action( 'wp_enqueue_scripts', 'simple_responsive_scripts' );
