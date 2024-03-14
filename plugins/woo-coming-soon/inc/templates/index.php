<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

require( './wp-load.php' );


$woo_cs_function_frontend = (function_exists('woo_cs_function_frontend') && woo_cs_function_frontend()!='' && !is_user_logged_in());

if($woo_cs_function_frontend){ 
	
	echo woo_cs_function_frontend(); 

}else{
	
	define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
	require __DIR__ . '/wp-blog-header.php';
}