<?php
/**
 * The plugin core class definition
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite' ) ) {
	class Widget_Box_Lite {

		/**
		 * @since    1.0.0
		 */
		protected $loader;

		/**
		 * @since    1.0.0
		 */
		protected $plugin_name;

		/**
		 * @since    1.0.0
		 */
		protected $version;

		/**
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'WIDGET_BOX_LITE_VERSION' ) ) {
				$this->version = WIDGET_BOX_LITE_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'widget-box-lite';

			$this->load_dependencies();
			$this->load_widgets();
			$this->set_locale();
			$this->define_admin_hooks();

		}

		/**
		 * @since    1.0.0
		 */
		private function load_dependencies() {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-widget-box-loader.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-widget-box-i18n.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/admin/class-widget-box-admin.php';

			$this->loader = new Widget_Box_Lite_Loader();

		}

		/**
		 * @since    1.0.0
		 */
		function load_widgets() {

			if ( ! Widget_Box_Lite_Admin::is_theme4press_theme() ) {
				return;
			}

			// Load Banner Ads Widget
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/banner-ads.php';

			// Load Contact Info Widget
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/contact-info.php';

			// Posts Slider Widget
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/posts-slider.php';

			// Recent Posts Widget
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/recent-posts.php';

			// Load Social Media Links Widget
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/social-media-links.php';

		}

		/**
		 * @since    1.0.0
		 */
		private function set_locale() {

			$plugin_i18n = new Widget_Box_Lite_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * @since    1.0.0
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Widget_Box_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		}

		/**
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * @since     1.0.0
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * @since     1.0.0
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * @since     1.0.0
		 */
		public function get_version() {
			return $this->version;
		}
	}
}