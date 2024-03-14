<?php
/**
 * Plugin Name: WP Counter
 * Plugin URI: http://learn24bd.com/portfolio/wp-counter
 * Description: WP Counter is a simple visitor counter of your site. You can see your unique site visitor status in different date range (Today,Yesterday,Current Week,Current Month).
 * Author: Harun<harun.cox@gmail.com>
 * Author URI: https://learn24bd.com
 * Version: 1.2
 * License:GPL2
 * Requires at least: 5.3
 * Tested up to: 6.2
 * Text Domain: wpcounter
 */

use Haruncpi\WpCounter\Plugin;

require_once 'vendor/autoload.php';
require_once 'inc/date-helper.php';

define( 'WPCOUNTER_FILE', __FILE__ );
define( 'WPCOUNTER_DIR', plugin_dir_path( __FILE__ ) );

$plugin = new Plugin();
$plugin->init();

register_activation_hook( __FILE__, array( $plugin, 'on_plugin_active' ) );
