<?php
/**
 * Plugin Name:  WP Snow - Best Snow Effect Plugin
 * Plugin URI: https://ironikus.com/downloads/wp-snow/
 * Description: The best (snow)flakes animation for WordPress.
 * Version: 1.0.3
 * Author: Ironikus
 * Author URI: https://ironikus.com/
 * License: GPL2
 *
 * You should have received a copy of the GNU General Public License
 * along with TMG User Filter. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// Plugin name.
define( 'WPSNOW_NAME',           'WP Snow' );

// Plugin version.
define( 'WPSNOW_VERSION',        '1.0.3' );

// Plugin Root File.
define( 'WPSNOW_PLUGIN_FILE',    __FILE__ );

// Plugin base.
define( 'WPSNOW_PLUGIN_BASE',    plugin_basename( WPSNOW_PLUGIN_FILE ) );

// Plugin Folder Path.
define( 'WPSNOW_PLUGIN_DIR',     plugin_dir_path( WPSNOW_PLUGIN_FILE ) );

// Plugin Folder URL.
define( 'WPSNOW_PLUGIN_URL',     plugin_dir_url( WPSNOW_PLUGIN_FILE ) );

// Plugin Root File.
define( 'WPSNOW_TEXTDOMAIN',     'wp-snow' );

/**
 * Load the main class for our plugin
 */
require_once WPSNOW_PLUGIN_DIR . 'core/class-wp-snow.php';

new WP_Snow();