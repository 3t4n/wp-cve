<?php
/**
 * Plugin Name: Countdown Timer for WordPress Block Editor
 * Plugin URI: https://flickdevs.com/gutenberg/
 * Description: Countdown Timer block for Wordpress Block Editor(Gutenberg).
 * Author: Flickdevs
 * Author URI: https://flickdevs.com/
 * Version: 1.0.5
 * License: GPL2+
 * Text Domain: fd-countdown-timer
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'FD_CTIMER_VERSION', '1.0.0' );
define( 'FD_CTIMER_BLOCKURL', plugins_url('/', __FILE__) );
define( 'FD_CTIMER_BASENAME', plugin_basename(__FILE__) );
define( 'FD_CDTIMER_LANG', 'fd-countdown-timer' );
/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'include/init.php';


