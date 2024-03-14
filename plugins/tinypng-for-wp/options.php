<?php

/**
 * Plugin Name: TinyPNG for Wordpress
 * Plugin URI: http://techjunkie.in/tinypng-for-wordpress/
 * Description: A plugin that connects with the TinyPNG API and optimizes/reduces the size of your PNG images on the go
 * Version: 0.2
 * Author: Prateek Kathal
 * Author URI: http://techjunkie.in/
 * License: GPL3
 
Copyright © 2007 Free Software Foundation, Inc. <http://fsf.org/>
Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.

**/

add_action('admin_menu', '__tinypngforwp_create_menu');	// TinyPNG Settings (Menu)
function __tinypngforwp_create_menu() {

	add_menu_page('TinyPNG', 'TinyPNG', 'administrator',__FILE__, '__tinypngforwp_settings_page',plugins_url('tinypngicon.png', __FILE__)); 	//Main Options (Top-Level Menu)

	add_action( 'admin_init', 'register_tinypngforwpsettings' );			//Register Settings function
	
}

require_once('functions.php');

function __tinypngforwp_styles() {
       wp_enqueue_style( 'tinypngforwp-style', plugins_url('stylesheet.css', __FILE__) );	       /* Enqueue stylesheet */
}
add_action( 'admin_enqueue_scripts', '__tinypngforwp_styles' );

function register_tinypngforwpsettings() {						//Register settings for Form

	register_setting( 'tinypng-settings-group', 'tinypng-email-id' );
	register_setting( 'tinypng-settings-group', 'tinypng-api' );
}

function __tinypngforwp_settings_page() {
?>
<h2>TinyPNG for WP</h2>
<br/>
<?php 
__tinypngforwp_maintable();
__tinypngforwp_sidetable(); 
} 

?>