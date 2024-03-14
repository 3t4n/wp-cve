<?php
/*
Plugin Name: Stop Pinging Yourself for WordPress
Plugin URI: http://thisismyurl.com/downloads/stop-pinging-yourself-for-wordpress/
Description: Stops a WordPress blog from pinging itself with pingbacks
Author: christopherross
Version: 15.01.01
Author URI: http://thisismyurl.com/
*/


/**
 * Stop Pinging Yourself for WordPress core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/stop-pinging-yourself-for-wordpress/
 *
 * @package 	Stop Pinging Yourself for WordPress
 * @copyright	Copyright ( c ) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 ( or newer )
 *
 * @since 		Stop Pinging Yourself for WordPress 1.0
 */





/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_SPYFW_NAME', 'Stop Pinging Yourself for WordPress' );
define( 'THISISMYURL_SPYFW_SHORTNAME', 'Stop Pinging' );

define( 'THISISMYURL_SPYFW_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_SPYFW_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_SPYFW_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_SPYFW_NAMESPACE', basename( THISISMYURL_SPYFW_FILENAME, '.php' ) );
define( 'THISISMYURL_SPYFW_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_SPYFW_NAMESPACE ) );

define( 'THISISMYURL_SPYFW_VERSION', '15.01' );

include_once( 'thisismyurl-common.php' );



/**
 * Creates the class required for the plugin
 *
 * @author     Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @see        wp_enqueue_scripts()
 * @since      Class available since Release 15.01
 *
 */
if( ! class_exists( 'thissimyurl_StopPingingYourself' ) ) {
class thissimyurl_StopPingingYourself extends thisismyurl_Common_SPYFW {

	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_action
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		add_action( 'pre_ping' , 'no_self_ping' );
	}
	
	
	function no_self_ping( $links ) {
	
		foreach ( $links as $link_count => $link ) {
	
			if ( 0 === strpos( $link, get_option( 'home' ) ) )
				unset( $links[$link_count] );
			
		}
	
	}
	
}
}

$thissimyurl_StopPingingYourself = new thissimyurl_StopPingingYourself;

$thissimyurl_StopPingingYourself->run();
