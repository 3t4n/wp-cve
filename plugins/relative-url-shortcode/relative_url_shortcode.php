<?php 
    /*
    Plugin Name: Relative URL ShortCode
    Plugin URI: https://wordpress.org/plugins/relative-url-shortcode/
    Description: Now its easy to change domains and keep links and images fine you just need to use this shortcode [base_url] as your base url for links and images.  
    Author: Muhammad Sufian
    Version: 1.1.0
    Author URI: http://technologicx.com/
    */

if ( ! defined( 'ABSPATH' ) ) exit;
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

function rurls_convert_base_url() {
	return site_url();
}
add_shortcode('base_url', 'rurls_convert_base_url');

?>