<?php
/**
 * The core plugin class.
 *
 * It is used to define startup settings and requirements
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WQM_Common' ) ) {

	class WQM_Common {

		/**
		 * Plugin common system name
		 */
		const PLUGIN_SYSTEM_NAME = 'wp-qrcode-me-v-card';

		/**
		 * Human readable plugin name for front end
		 */
		const PLUGIN_HUMAN_NAME = 'QR code MeCard/vCard generator';

		/**
		 * @var string Path to plugin root directory
		 */
		public $plugin_base_path = '';

		/**
		 * @var string Url path to plugin root directory
		 */
		public $plugin_base_url = '';

		/**
		 * WQM_Common constructor.
		 */
		public function __construct() {
			$this->plugin_base_path = self::get_plugin_root_path();
			$this->plugin_base_url  = self::get_plugin_root_path( 'url' );
			$this->load_dependencies();
			$this->set_locale();
		}

		/**
		 * Print error
		 *
		 * @param Exception|WP_Error $exception
		 */
		public static function print_error( $exception ) {
			$error_string = $exception->get_error_message();
			echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}

		/**
		 * Module entry point
		 */
		public function run() {
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Use for register module options
		 */
		public static function activate() {
		}

		/**
		 * Do all jobs when module deactivated
		 */
		public static function deactivate() {
		}

		/**
		 * Use for unregister module options registered before in self::activate()
		 */
		public static function uninstall() {
		}

		/**
		 * Add localization support
		 */
		private function set_locale() {
			add_action( 'plugins_loaded', function () {
				load_plugin_textdomain( 'wp-qrcode-me-v-card', false, plugin_basename( dirname( __DIR__ ) ) . '/languages' );
				add_image_size( 'qr-code-photo', 300, 300 );
			} );
		}

		/**
		 * Add actions and work for admin part of plugin
		 */
		private function define_admin_hooks() {
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'wqm_register_admin_scripts' ) );
				add_action( 'wp_ajax_wqm_make_permanent', array( WQM_QR_Code_Type::class, 'wqm_make_url_permanent' ) );

				WQM_QR_Code_Type::run();
			}
		}

		/**
		 * Register all plugin admin part styles and JS
		 */
		public function wqm_register_admin_scripts() {
			wp_enqueue_style( 'wqm-styles', $this->plugin_base_url . 'static/css/styles.css' );

			if ( ! wp_script_is( 'select2', 'registered' ) ) {
				wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false, '1.0', 'all' );
				wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '1.0', true );
			}

			wp_enqueue_style( 'wp-color-picker' );
			wp_register_script( 'wqm-color-picker-alpha', $this->plugin_base_url . 'static/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '3.0.2', true );
			wp_add_inline_script(
				'wqm-color-picker-alpha',
				'jQuery( function() { jQuery( ".wqm-color-picker" ).wpColorPicker(); } );'
			);
			wp_enqueue_script( 'wqm-color-picker-alpha' );
		}

		/**
		 * Add actions and work for public part of plugin
		 */
		private function define_public_hooks() {
			add_shortcode( WQM_Shortcode::SHORTCODE_NAME, array( 'WQM_Shortcode', 'add_shortcode' ) );
			add_action( 'widgets_init', array( 'WQM_Widget', 'load_widget' ) );
			add_action( 'wp', array( $this, 'load_vcard_on_page_open' ) );
		}

		/**
		 * Load plugin files
		 */
		private function load_dependencies() {
			require_once $this->plugin_base_path . 'vendor/autoload.php';
			require_once $this->plugin_base_path . 'controller/class-wqm-shortcode.php';
			require_once $this->plugin_base_path . 'controller/class-wqm-widget.php';
			require_once $this->plugin_base_path . 'controller/class-wqm-qr-code-type.php';
			require_once $this->plugin_base_path . 'model/class-wqm-qr-code.php';
			require_once $this->plugin_base_path . 'model/class-wqm-cards.php';
		}

		/**
		 * Get plugin base path or url for plugin common usage
		 *
		 * @param string $type
		 *
		 * @return string
		 */
		public static function get_plugin_root_path( $type = 'path' ) {
			if ( 'url' == $type ) {
				return plugin_dir_url( dirname( __FILE__ ) );
			}

			return plugin_dir_path( dirname( __FILE__ ) );
		}

		/**
		 * Render a template
		 *
		 * Allows parent/child themes to override the markup by placing the a file named basename( $default_template_path ) in their root folder,
		 * and also allows plugins or themes to override the markup by a filter. Themes might prefer that method if they place their templates
		 * in sub-directories to avoid cluttering the root folder. In both cases, the theme/plugin will have access to the variables so they can
		 * fully customize the output.
		 *
		 * @mvc @model
		 *
		 * @param string|bool $template The path to the template, relative to the plugin's `views` folder
		 * @param array $variables An array of variables to pass into the template's scope, indexed with the variable name so that it can be extract()-ed
		 *
		 * @return string
		 */
		public static function render( $template = '', $variables = array() ) {
			do_action( 'wqm_pre_render_template', $template, $variables );

			$template_path = locate_template( basename( $template ) );
			if ( ! $template_path ) {
				$template_path = self::get_plugin_root_path() . 'views/' . $template;
			}
			$template_path = apply_filters( 'wqm_template_path', $template_path );

			if ( is_file( $template_path ) ) {
				extract( $variables );
				ob_start();

				include $template_path;

				$template_content = apply_filters( 'wqm_template_content', ob_get_clean(), $template, $template_path, $variables );
			} else {
				$template_content = '';
			}

			do_action( 'wqm_post_render_template', $template, $variables, $template_path, $template_content );

			return $template_content;
		}

		/**
		 * Sanitize string to integer value
		 *
		 * @param $text
		 *
		 * @return int
		 */
		public static function clear_digits( $text ): int {
			return intval( preg_replace( '@[^\d]+@si', '', $text ) );
		}

		public static function is_url_exists( $url ) {
			$headers = @get_headers( $url );

			return is_array( $headers ) ? preg_match( '/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $headers[0] ) : false;
		}

		public function load_vcard_on_page_open() {
			$code = isset( $_GET['qr-code'] ) ? WQM_Common::clear_digits( $_GET['qr-code'] ) : false;
			if ( $code ) {
				$params = array_merge(
					WQM_QR_Code_Type::get_card_metas( $code ),
					WQM_QR_Code_Type::get_qr_code_settings_metas( $code )
				);
				$text   = ( new WQM_Qr_Code_Generator( $params ) )->build( $just_code = true );

				$name = $this->prepare_file_name( $params ) . '.vcf';

				header( 'Content-Type: text/x-vcard;charset=utf-8;' );
				header( "Pragma: no-cache" );
				header( "Content-Disposition: inline; filename=\"{$name}\";" );
				die( $text );
			}
		}

		private function prepare_file_name( array $fields ): string {
			$filename = $fields['wqm_filename'];
			if ( empty( $filename ) ) {
				$filename = $fields['wqm_type'] == 'mecard' ? 'meCard' : 'vCard';
			}
			$filename = str_replace( '%_name_%', $fields['wqm_n'], $filename );
			$filename = str_replace( '%_nickname_%', $fields['wqm_nickname'], $filename );
			$filename = str_replace( '%_title_%', $fields['wqm_title'], $filename );
			$filename = str_replace( '%_organization_%', $fields['wqm_org'], $filename );

			return sanitize_file_name( $filename );
		}
	}
}

/**
 * @param $item
 * @param $array
 *
 * @return string
 */
function wqm_selected( $item, $array ) {
	if ( in_array( (string) $item, $array ) ) {
		$result = " selected='selected'";
	} else {
		$result = '';
	}

	echo $result;

	return $result;
}
