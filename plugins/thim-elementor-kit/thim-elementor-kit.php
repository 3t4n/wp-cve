<?php
/**
 * Plugin Name: Thim Elementor Kit
 * Description: It is page builder for the Elementor page builder.
 * Author: ThimPress
 * Version: 1.1.8
 * Author URI: http://thimpress.com
 * Requires at least: 6.3
 * Tested up to: 6.4.2
 * Requires PHP: 7.0
 * Text Domain: thim-elementor-kit
 * Domain Path: /languages/
 * Elementor tested up to: 3.19.0
 */

use Elementor\Core\Files\Manager as Files_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

define( 'THIM_EKIT_VERSION', '1.1.8' );
const THIM_EKIT_PLUGIN_FILE = __FILE__;
define( 'THIM_EKIT_PLUGIN_PATH', plugin_dir_path( THIM_EKIT_PLUGIN_FILE ) );
define( 'THIM_EKIT_PLUGIN_URL', plugin_dir_url( THIM_EKIT_PLUGIN_FILE ) );
define( 'THIM_EKIT_PLUGIN_BASE', plugin_basename( THIM_EKIT_PLUGIN_FILE ) );
define( 'THIM_EKIT_DEV', false );

/**
 * Class Thim Elementor Kits Plugin
 *
 * @author Nhamdv from ThimPress <daonham95@gmail.com>
 */
if ( ! class_exists( 'Thim_EL_Kit' ) ) {
	final class Thim_EL_Kit {
		protected static $instance = null;

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 99 );

			if ( ! $this->elementor_is_active() ) {
				add_action( 'admin_notices', array( $this, 'required_plugins_notice' ) );
				return;
			}

			if ( defined( 'THIM_EKIT_PRO_VERSION' ) ) {
				add_action( 'admin_notices', array( $this, 'notice_thim_elementor_kit_pro' ) );
			}

			$this->includes();

			do_action( 'thim_ekit_loaded' );
		}

		protected function includes() {
			// Utilities
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/singleton-trait.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/class-response.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/class-elementor.php';
			// Group Add Control
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/group-control-trait.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/login-register-trait.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/utilities/widget-loop-trait.php';

			// Include
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-dashboard.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-settings.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-post-type.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-enqueue-scripts.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-rest-api.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-shortcode.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-structured-data.php';
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/class-functions.php';

			// Elementor
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/class-elementor.php';

			// Modules
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/modules/class-init.php';

			// Upgrade.
			require_once THIM_EKIT_PLUGIN_PATH . 'inc/upgrade/class-init.php';
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'thim-elementor-kit', false, basename( THIM_EKIT_PLUGIN_PATH ) . '/languages' );
		}

		public function register_activation_hook() {
			if ( $this->elementor_is_active() ) {
				if ( Plugin::$instance->files_manager instanceof Files_Manager ) {
					Plugin::$instance->files_manager->clear_cache();
				}
			}
		}

		public function elementor_is_active() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		public function required_plugins_notice() {
			$screen = get_current_screen();

			if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
				return;
			}

			$plugin = 'elementor/elementor.php';

			$installed_plugins      = get_plugins();
			$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

			if ( $is_elementor_installed ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

				$message  = sprintf( '<p>%s</p>', esc_html__( 'Thim Elementor Kit requires Elementor to be activated.', 'thim-elementor-kit' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $activation_url, esc_html__( 'Activate Elementor Now', 'thim-elementor-kit' ) );
			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

				$message  = sprintf( '<p>%s</p>', esc_html__( 'Thim Elementor Kit requires Elementor to be installed.', 'thim-elementor-kit' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, esc_html__( 'Install Elementor Now', 'thim-elementor-kit' ) );
			}

			printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
		}

		public function notice_thim_elementor_kit_pro() {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			// Deactive Thim Elementor Kit Pro.
			deactivate_plugins( 'thim-elementor-kit-pro/thim-elementor-kit-pro.php' );

			$deactivate_url = wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=thim-elementor-kit-pro/thim-elementor-kit-pro.php' ), 'deactivate-plugin_thim-elementor-kit-pro/thim-elementor-kit-pro.php' );
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'Thim Elementor Kit Pro is merged into Thim Elementor Kit. Please deactivate Thim Elementor Kit Pro to avoid conflicts.', 'thim-elementor-kit' ); ?></p>
				<p><a href="<?php echo esc_url( $deactivate_url ); ?>" class="button-primary"><?php esc_html_e( 'Deactivate Thim Elementor Kit Pro', 'thim-elementor-kit' ); ?></a></p>
			</div>
			<?php
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'thim-elementor-kit' ), '1.0' );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'thim-elementor-kit' ), '1.0' );
		}
	}
}

// Update CSS Print Method in Elementor.
register_activation_hook(
	__FILE__,
	function() {
		Thim_EL_Kit::instance()->register_activation_hook();
	}
);

// If Multilsite.
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	add_action(
		'plugins_loaded',
		function() {
			Thim_EL_Kit::instance();
		},
		90
	);
} else {
	Thim_EL_Kit::instance();
}
