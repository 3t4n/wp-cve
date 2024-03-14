<?php
/**
 * @wordpress-plugin 
 * Plugin Name: chat-me-now 
 * Plugin URI: 		https://boliviahub.com/chat-me-now 
 * Description: 	Floating button to the webwhats chat on the customer browser.
 * Version: 		1.0.2
 * Author: 			Frank Ortiz
 * Author URI: 		https://dfortiz.github.io
 * Text Domain: 	chat-me-now
 * Tags: 		message, web-whatsapp, comments, support, whatsapp, chat, floating, support
 * Requires at least: 4.7
 * Donate link: https://dfortiz.github.io
 * Domain Path: / 
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl.html
 * 
 *  PHP version 5.3.0
 *
 * @category    Wordpress_Plugin
 * @package     BH_Plugin
 * @author      Frank Ortiz <dfortiz@gmail.com>
 * @copyright   2021 Boliviahub
 * @license     GNU Public License
 * @version     1.0.2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/* Defines plugin's root folder. */
define( 'BH_PLGN_CMN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BH_PLGN_CMN_URL', plugins_url('/', __FILE__ ) );
define( 'BH_PLGN_CMN__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( "BH_PLGN_CMN_LICENSE", true );

/* General. */
require_once('inc/BH_PLGN_CMN-init.php');

new bhpcmn_main();

?>