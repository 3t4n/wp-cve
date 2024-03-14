<?php
/**
 * Plugin Name: LB Back To Top
 * Plugin URI: http://lbideias.com.br
 * Description: Including button that takes the user to the top of the page.
 * Author: leobaiano
 * Author URI: http://lbideias.com.br/
 * Version: 2.0
 * License: GPLv2 or later
 */
	
	add_action( 'wp_enqueue_scripts', 'lbbtt_load_scripts' );
	add_action( 'wp_footer', 'lbbtt_display_button', 9999 );

	function lbbtt_load_scripts(){ 
	    wp_enqueue_script( 'lb-back-to-top', plugins_url( 'js/main.js', __FILE__ ), array( 'jquery' ), null, true );
	    wp_enqueue_style( 'lb-back-to-top', plugins_url( 'css/main.css', __FILE__ ), array(), null, 'all' );
	}
	function lbbtt_display_button(){
		echo '<a href="javascript:;" class="lb-back-to-top">' . __( "Topo", "lbbtt" ) . '</a>';
	}
?>