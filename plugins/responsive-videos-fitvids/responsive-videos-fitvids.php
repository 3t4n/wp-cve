<?php
/*
 * Plugin Name: Responsive Videos - FitVids
 * Description: Makes emebedded videos responsive
 * Plugin URI:  	  https://wordpress.org/plugins/responsive-videos-fitvids
 * Version: 		  3.0.1
 * Requires at least: 5.3
 * Requires PHP:      7.2
 * Author: 	 	      Sibin Grasic
 * Author URI:        https://sgi.io
 * Text Domain:       responsive-videos-fitvids
 */

 namespace SGI\Fitvids;

// Prevent direct access
!defined('WPINC') && die;

!defined(__NAMESPACE__ . '\FILE')     && define(__NAMESPACE__ . '\FILE', __FILE__);                        // Define Main plugin file
!defined(__NAMESPACE__ . '\BASENAME') && define(__NAMESPACE__ . '\BASENAME', plugin_basename(FILE));       //Define Basename
!defined(__NAMESPACE__ . '\PATH')     && define(__NAMESPACE__ . '\PATH', plugin_dir_path( FILE ));         //Define internal path
!defined(__NAMESPACE__ . '\VERSION')  && define (__NAMESPACE__ . '\VERSION', '3.0.1');                       // Define internal version
!defined(__NAMESPACE__ . '\DOMAIN')   && define (__NAMESPACE__ . '\DOMAIN', 'responsive-videos-fitvids');  // Define Text domain

// Bootstrap the plugin
require (PATH . '/vendor/autoload.php');

// Run the plugin
function runFitvids()
{

    global $wp_version;

    if (version_compare( PHP_VERSION, '7.2.0', '<' ))
        throw new \Exception(__('Letter Avatars plugin requires PHP 7.2 or greater.', 'responsive-videos-fitvids'));

    if (version_compare($wp_version, '5.3', '<'))
        throw new \Exception(__('Letter Avatars plugin requires WordPress 5.1.0.', 'responsive-videos-fitvids'));

    RSFitvids();

}

// And awaaaaay we goooo
try {

    runFitvids();

} catch (\Exception $e) {

    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    deactivate_plugins( __FILE__ );
    wp_die($e->getMessage());

}