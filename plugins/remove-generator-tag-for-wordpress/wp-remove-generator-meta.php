<?php
/*
Plugin Name: WP Remove Generator Meta Tag
Plugin URI: http://thisismyurl.com/downloads/remove-generator-meta-tag/
Description: This plugin is designed to insert a piece of code into your WordPress website, which will automatically remove the WordPress version from your header.
Author: Christopher Ross
Author URI: http://thisismyurl.com/
Tags: meta, generator, security, remove generator, wordpress version
Version: 15.01
*/

/**
 * WP Remove Generator Meta Tag
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/remove-generator-meta-tag/
 *
 * @package 	WP Remove Generator Meta Tag
 * @copyright	Copyright ( c ) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 ( or newer )
 *
 * @since 		WP Remove Generator Meta Tag 1.0
 */



/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_WPRGMT_NAME', 'Remove Generator Meta Tag' );
define( 'THISISMYURL_WPRGMT_SHORTNAME', 'Remove Generator' );

define( 'THISISMYURL_WPRGMT_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_WPRGMT_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_WPRGMT_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_WPRGMT_NAMESPACE', basename( THISISMYURL_WPRGMT_FILENAME, '.php' ) );
define( 'THISISMYURL_WPRGMT_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_WPRGMT_NAMESPACE ) );

define( 'THISISMYURL_WPRGMT_VERSION', '15.01' );

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
if( ! class_exists( 'thissimyurl_RemoveGeneratorTag' ) ) {
class thissimyurl_RemoveGeneratorTag extends thisismyurl_Common_WPRGMT {

	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/remove_action
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		remove_action( 'wp_head' , 'wp_generator' );
	}
	
	
}
}

$thissimyurl_RemoveGeneratorTag = new thissimyurl_RemoveGeneratorTag;

$thissimyurl_RemoveGeneratorTag->run();