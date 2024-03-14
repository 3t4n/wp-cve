<?php
/**
 * Plugin Name: PowerPack for LearnDash
 * Plugin URI: https://honorswp.com/
 * Description: PowerPack for LearnDash is the ultimate way to add functionality to your LearnDash powered website
 * Author: HonorsWP
 * Author URI: https://honorswp.com/
 * Version: 1.3.2
 * Requires PHP: 5.6
 * Requires at least: 5.9
 * Text Domain: learndash-powerpack
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package LearnDash PowerPack
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required minimums and constants
 */
define( 'LD_POWERPACK_VERSION', '1.3.2' );
define( 'LD_POWERPACK_MAIN_FILE', __FILE__ );
define( 'LD_POWERPACK_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'LD_POWERPACK_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Shows a message if the LearnDash plugin is not installed
 */
function learndash_powerpack_missing_learndash_notice() {
	echo '<div class="notice notice-error is-dismissible"><p><strong>' . sprintf(
		// translators: placeholder: Link to learndash.com.
		esc_html__( 'Learndash PowerPack requires LearnDash to be installed and active. You can download %s here.', 'learndash-powerpack' ),
		'<a href="https://www.learndash.com/" target="_blank">LearnDash</a>'
	) . '</strong></p></div>';
}

add_action( 'plugins_loaded', 'learndash_powerpack_init' );

/**
 * Initializing the plugin
 */
function learndash_powerpack_init() {
	load_plugin_textdomain( 'learndash-powerpack', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );

	if ( ! defined( 'LEARNDASH_VERSION' ) ) {
		add_action( 'admin_notices', 'learndash_powerpack_missing_learndash_notice' );
	}

	if ( ! class_exists( 'LearnDash_PowerPack' ) ) {
		/**
		 * Class LearnDash Powerpack
		 *
		 * @since 1.0.0
		 */
		class LearnDash_PowerPack {
			/**
			 * Instance of class.
			 *
			 * @var LearnDash_PowerPack
			 */
			public static $instance;

			/**
			 * Get class instance
			 *
			 * @return \LearnDash_PowerPack The *Singleton* instance.
			 */
			public static function get_instance() {
				if ( null === self::$instance ) {
					self::$instance = new self();
				}

				return self::$instance;
			}

			/**
			 * Protected constructor to prevent creating a new instance of the
			 * *Singleton* via the `new` operator from outside of this class.
			 */
			public function __construct() {
				add_action( 'admin_init', [ $this, 'install' ] );

				spl_autoload_register( [ $this, 'autoload' ] );

				$this->init();
			}

			/**
			 * Autoloader of all the files to be used in the plugin
			 *
			 * @since 1.0.0
			 *
			 * @param string $class  Class name.
			 * @param string $dir    Directory.
			 */
			public function autoload( $class, $dir = null ) {
				if ( is_null( $dir ) ) {
					$dir = LD_POWERPACK_PLUGIN_PATH . '/includes/ld_classes/';
				}
				$scanned_directory = array_diff( scandir( $dir ), [ '..', '.' ] );
				foreach ( $scanned_directory as $file ) {
					require_once $dir . $file;
				}
			}

			/**
			 * Include required core files used in admin and on the frontend.
			 *
			 * @version 1.2.1
			 * @since   1.0.0
			 */
			public function init() {
				require_once dirname( __FILE__ ) . '/includes/deprecated/deprecated-functions.php';
				require_once dirname( __FILE__ ) . '/includes/helper/helper-function.php';
				require_once dirname( __FILE__ ) . '/includes/setting_html/class-learndash-powerpack-build-setting-page-html.php';
				require_once dirname( __FILE__ ) . '/includes/available_classes/class-learndash-powerpack-all-classes.php';
				require_once dirname( __FILE__ ) . '/includes/admin_assets/class-learndash-powerpack-admin-assets.php';
				require_once dirname( __FILE__ ) . '/includes/class-learndash-powerpack-setting-page.php';
				require_once dirname( __FILE__ ) . '/includes/learndash_ajax/class-learndash-powerpack-ajax-call.php';
			}

			/**
			 * Updates the plugin version in db
			 *
			 * @since 3.1.0
			 * @version 4.0.0
			 */
			public function update_plugin_version() {
				update_option( 'ld_powerpack_version', LD_POWERPACK_VERSION );
			}

			/**
			 * Handles upgrade routines.
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 */
			public function install() {
				if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					return;
				}

				if ( LD_POWERPACK_VERSION !== get_option( 'ld_powerpack_version' ) ) {
					do_action( 'ld_powerpack_updated' );
					$this->update_plugin_version();
				}
			}
		}

		LearnDash_PowerPack::get_instance();
	}
}
