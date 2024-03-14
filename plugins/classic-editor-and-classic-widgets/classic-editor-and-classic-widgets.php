<?php
/**
 * Plugin Name: Classic Editor and Classic Widgets
 * Plugin URI: https://wordpress.org/plugins/classic-editor-and-classic-widgets/
 * Description: Disables Gutenberg editor totally everywhere and enables Classic Editor & Classic Widgets.
 * Author: WPGrim
 * Author URI: https://wpgrim.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: classic-editor-and-classic-widgets
 * Version: 1.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CEW_VERSION', '1.3.6' );
define( 'CEW_FILE', __FILE__ );
define( 'CEW_PATH', dirname( CEW_FILE ) );
define( 'CEW_INCLUDES', CEW_PATH . '/includes' );
define( 'CEW_URL', plugin_dir_url( CEW_FILE ) );
define( 'CEW_BASENAME', plugin_basename( CEW_FILE ) );

require_once CEW_INCLUDES . '/autoload.php';
