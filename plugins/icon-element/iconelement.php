<?php
/**
 * Plugin Name: Icon Element
 * Plugin URI:  https://webangon.com/icon-element/
 * Description: Various icon font for Elementor page builder
 * Version:     2.0.5
 * Author:      Webangon
 * Author URI:  http://webangon.com/
 * Text Domain: iconelement
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /languages
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! class_exists( 'Icon_Element_Icons' ) ) {

	class Icon_Element_Icons {

		private static $instance = null;

		private $version = '1.0.0';

		private $plugin_url = null;

		private $plugin_path = null;

		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );

			// Init required modules.
			add_action( 'init', array( $this, 'init' ), -999 );

			add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this,'my_plugin_action_links') );

			add_action('admin_init', [$this, 'installed_active_elementor'], 10);

		}
 
		public function installed_active_elementor()
        {
            if (is_admin() && current_user_can('activate_plugins') && !did_action('elementor/loaded')) {
                add_action('admin_notices', [$this, 'elementor_inactive_not_present'], 10);

            }
        }

		public function elementor_inactive_not_present()
        {
            $class = 'notice notice-error';
            $plugin = 'elementor/elementor.php';
            $message = sprintf(__('%1$sIcon Element%2$s plugin requires %1$sElementor%2$s plugin installed & activated.', 'thepack'), '<strong>', '</strong>');

            if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
                $action_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
                $button_label = __('Activate Elementor', 'thepack');
            } else {
                $action_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
                $button_label = __('Install Elementor', 'thepack');
            }

            $button = '<p><a href="' . esc_url($action_url) . '" class="button-primary">' . esc_html($button_label) . '</a></p><p></p>';

            printf('<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr($class), wp_kses_post($message), wp_kses_post($button));
        }

		public function get_version() {
			return $this->version;
		}

		public function init() {
			if ( ! $this->has_elementor() ) {
				return;
			}

			Icon_Element_Icons_Integration::get_instance();
		}

		public function has_elementor() {
			return did_action( 'elementor/loaded' );
		}

		public function my_plugin_action_links( $links ) {

			$links = array_merge( $links, array(
				'<a class="elementor-plugins-gopro" href="https://webangon.com/icon-element/">' . __( 'Get Pro', 'icon-element' ) . '</a>',
				'<a href="' . esc_url( admin_url( '/themes.php?page=iconelement' ) ) . '">' . __( 'Settings', 'icon-element' ) . '</a>'
			) );
		
			return $links;
		
		}

		public function elementor() {
			return \Elementor\Plugin::instance();
		}

		public static function is_iconelement_pro(){

			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			return is_plugin_active( 'iconelement-pro/iconelement.php' ) ? true : false;
		}

		public function lang() {
			load_plugin_textdomain( 'icon-element', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
				self::$instance->icon_element_constant();
			}
			return self::$instance;
		}

        private function icon_element_constant() {

            // Plugin Folder Path
            if (!defined('ICON_ELEM_DIR')) {
                define('ICON_ELEM_DIR', plugin_dir_path(__FILE__));
            }

            // Plugin Folder URL
            if (!defined('ICON_ELEM_URL')) {
                define('ICON_ELEM_URL', plugin_dir_url(__FILE__));
            }

			define( 'ICONELEMENT_ROOT_FILE__', __FILE__ );

            require_once ICON_ELEM_DIR . 'admin/options.php';
            require_once ICON_ELEM_DIR . 'admin/inc/sunrise.php';
            require_once ICON_ELEM_DIR . 'includes/integration.php';
			require_once ICON_ELEM_DIR . 'includes/optin.php';
        }
	}
}

if ( ! function_exists( 'Icon_Element_Icons' ) ) {

	/**
	 * Returns instance of the plugin class.
	 *
	 * @since  1.0.0
	 * @return Icon_Element_Icons
	 */
	function Icon_Element_Icons() {
		return Icon_Element_Icons::get_instance();
	}
}

Icon_Element_Icons();
