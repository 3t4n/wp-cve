<?php 
	namespace MasterAccordion;

	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

	if( !class_exists('Master_Accordion_Elementor') ){

		final class Master_Accordion_Elementor {

			const MINIMUM_PHP_VERSION = '5.4';

			const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

			private $_localize_settings = [];

			private static $plugin_path;
			private static $plugin_url;
			private static $plugin_slug;
			public static $plugin_dir_url;
			public static $plugin_name;

			private static $instance = null;

			public $pro_enabled;

			public static $jltmaf_default_widgets;
			public static $jltmaf_extensions;


			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;
				}

				return self::$instance;
			}


			public function __construct() {

				self::$plugin_slug = 'master-accordion';
				self::$plugin_path = untrailingslashit( plugin_dir_path( '/', __FILE__ ) );
				self::$plugin_url  = untrailingslashit( plugins_url( '/', __FILE__ ) );

				// Initialize Plugin
				add_action('plugins_loaded', [$this, 'jltmaf_plugins_loaded']);

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'jltmaf_plugin_actions_links' ] );

				// Add Elementor Widgets
				add_action( 'elementor/widgets/widgets_registered', [ $this, 'jltmaf_init_widgets' ] );

				//Body Class
				add_filter( 'body_class', [ $this, 'jltmaf_ea_body_class' ] );
				
				
			}


			// Initialize
			public function jltmaf_plugins_loaded(){

				// Check if Elementor installed and activated
				if ( ! did_action( 'elementor/loaded' ) ) {
					add_action( 'admin_notices', array( $this, 'jltmaf_admin_notice_missing_main_plugin' ) );
					return;
				}

				// Check for required Elementor version
				if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
					add_action( 'admin_notices', array( $this, 'jltmaf_admin_notice_minimum_elementor_version' ) );
					return;
				}

				// Check for required PHP version
				if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
					add_action( 'admin_notices', array( $this, 'jltmaf_admin_notice_minimum_php_version' ) );
					return;
				}

			}


			public function jltmaf_init_widgets() {

				//Master Table for Elementor 
				require_once MAF_ADDON . 'master-accordion.php';

				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Addon\Master_Accordion() );	

			}


			public function is_elementor_activated( $plugin_path = 'elementor/elementor.php' ) {
				$installed_plugins_list = get_plugins();

				return isset( $installed_plugins_list[ $plugin_path ] );
			}



			// Plugin URL
			public static function jltmaf_plugin_url() {

				if ( self::$plugin_url ) {
					return self::$plugin_url;
				}
				return self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
			}


			// Plugin Path
			public static function jltmaf_plugin_path() {
				if ( self::$plugin_path ) {
					return self::$plugin_path;
				}

				return self::$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			// Plugin Dir Path
			public static function jltmaf_plugin_dir_url() {

				if ( self::$plugin_dir_url ) {
					return self::$plugin_dir_url;
				}

				return self::$plugin_dir_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
			}


			public function jltmaf_plugin_actions_links( $links ) {
				if ( is_admin() ) {					
					$links[] = '<a href="' . esc_url_raw('https://master-addons.com/contact-us') .'" target="_blank">' . esc_html__( 'Support', MAF_TD ) . '</a>';
					$links[] = '<a href="'. esc_url_raw('https://master-addons.com/docs/').'" target="_blank">' . esc_html__( 'Documentation',
							MAF_TD ) . '</a>';
				}
				return $links;
			}



			public function jltmaf_ea_body_class($classes){
				if ( !\Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					$classes[] = 'master-accordion';
				}
				return $classes;
			}


			public function get_localize_settings() {
				return $this->_localize_settings;
			}

			public function add_localize_settings( $setting_key, $setting_value = null ) {
				if ( is_array( $setting_key ) ) {
					$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

					return;
				}

				if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
					$this->_localize_settings[ $setting_key ] = $setting_value;

					return;
				}

				$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
			}


			public function jltmaf_admin_notice_missing_main_plugin() {
				$plugin = 'elementor/elementor.php';

				if ( $this->is_elementor_activated() ) {
					if ( ! current_user_can( 'activate_plugins' ) ) {
						return;
					}
					$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
					$message = sprintf( 'Master Addons requires <b>Elementor</b> plugin to be active. Please activate Elementor to continue.', MAF_TD );
					$button_text = esc_html__( 'Activate Elementor', MAF_TD );

				} else {
					if ( ! current_user_can( 'install_plugins' ) ) {
						return;
					}

					$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
					$message = sprintf( esc_html__( 'Master Addons requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', MAF_TD ), '<strong>', '</strong>' );
					$button_text = esc_html__( 'Install Elementor', MAF_TD );
				}




				$button = '<p><a href="' . esc_url_raw( $activation_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a></p>';

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message , $button );

			}

			public function jltmaf_admin_notice_minimum_elementor_version() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MAF_TD ),
					'<strong>' . esc_html__( 'Master Addons for Elementor', MAF_TD ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', MAF_TD ) . '</strong>',
					self::MINIMUM_ELEMENTOR_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}

			public function jltmaf_admin_notice_minimum_php_version() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MAF_TD ),
					'<strong>' . esc_html__( 'Master Addons for Elementor', MAF_TD ) . '</strong>',
					'<strong>' . esc_html__( 'PHP', MAF_TD ) . '</strong>',
					self::MINIMUM_PHP_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}




		}


		Master_Accordion_Elementor::get_instance();

	}
