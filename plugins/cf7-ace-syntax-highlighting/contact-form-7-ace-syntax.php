<?php
/*
Plugin Name: Contact Form 7 Syntax Highlighting
Description: Ace syntax highlighting makes the Contact Form 7 backend easy to use for complex form editing.
Author: Joris van Montfort
Version: 0.2.4
Author URI: http://www.jorisvm.nl
*/

// Add some extra script
add_action( 'admin_enqueue_scripts', 'add_cf7_ace_editor_script' );

function add_cf7_ace_editor_script( $hook ) {    
    if (isset( $_GET['page'] )) {
    	if ($_GET['page'] == 'wpcf7' || $_GET['page'] == 'wpcf7-new') {    
	        // needs jquery
	        wp_enqueue_script( 'jquery' );
	        wp_enqueue_script( 'cf7_ace', plugin_dir_url( __FILE__ ) . 'js/ace/ace.js' );                 
	        wp_enqueue_script( 'cf7_ace_init', plugin_dir_url( __FILE__ ) . 'js/cf7_ace_init.js', array( 'cf7_ace' ) );
		}            
    }
}