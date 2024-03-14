<?php
/**
 * Plugin Name: SoftTemplates for Elementor
 * Plugin URI:  https://softhopper.net/plugins/
 * Description: Most powerful plugin created header, footer, single post, archive page template with elementor
 * Version:     1.0.8
 * Author:      SoftHopper
 * Author URI:  https://softhopper.net
 * Text Domain: soft-template-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Soft_template_Core` doesn't exists yet.
if ( ! class_exists( 'Soft_template_Core' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Soft_template_Core {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private $core = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.0.0';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin base name
		 *
		 * @var string
		 */
		public $plugin_name = null;

		/**
		 * Components
		 */
		public $framework;
		public $assets;
		public $settings;
		public $dashboard;
		public $templates;
		public $templates_manager;
		public $config;
		public $locations;
		public $structures;
		public $conditions;
		public $api;
		public $compatibility;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			$this->plugin_name = plugin_basename( __FILE__ );

			// Load framework
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Load framework modules
		 *
		 * @return [type] [description]
		 */
		public function framework_loader() {

			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Soft_template_Core_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
				)
			);

			add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->config            = new Soft_template_Core_Config();
			$this->assets            = new Soft_template_Core_Assets();
			$this->settings          = new Soft_template_Core_Settings();
			$this->templates         = new Soft_template_Core_Templates_Post_Type();
			$this->locations         = new Soft_template_Core_Locations();
			$this->structures        = new Soft_template_Core_Structures();
			$this->conditions        = new Soft_template_Core_Conditions_Manager();
			$this->override     	 = new Soft_template_Default_Compat();
			$this->compatibility     = new Soft_template_Core_Compatibility();

			new Soft_template_Core_Elementor_Integration();

			if ( is_admin() ) {

				$this->dashboard         = new Soft_template_Core_Dashboard();
				$this->templates_manager = new Soft_template_Core_Templates_Manager();

				new Soft_template_Core_Ajax_Handlers();
			}

			// call widget 
			soft_template_widget_integration()->init();

			do_action( 'soft-template-core/init', $this );

			// WooCommerce Minicart
			if ( function_exists( 'WC' ) ) {
				add_filter('woocommerce_add_to_cart_fragments', array($this, 'add_to_cart_fragment'));
				add_filter('woocommerce_add_to_cart_fragments', array($this, 'add_to_cart_price_fragment'));
				add_filter('woocommerce_add_to_cart_fragments', array($this, 'add_to_cart_fragment_popup'));
			}

		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {

			// Global
			require $this->plugin_path( 'includes/assets.php' );
			require $this->plugin_path( 'includes/settings.php' );
			require $this->plugin_path( 'includes/config.php' );
			require $this->plugin_path( 'includes/ajax-handlers.php' );
			require $this->plugin_path( 'includes/elementor-integration.php' );
			require $this->plugin_path( 'includes/utils.php' );
			require $this->plugin_path( 'includes/locations.php' );
			require $this->plugin_path( 'includes/compatibility.php' );

			// Dashboard
			require $this->plugin_path( 'includes/dashboard/manager.php' );

			// Templates
			require $this->plugin_path( 'includes/templates/post-type.php' );
			require $this->plugin_path( 'includes/templates/manager.php' );

			// Structures
			require $this->plugin_path( 'includes/structures/manager.php' );

			// Conditions
			require $this->plugin_path( 'includes/conditions/manager.php' );

			// Override Template
			require $this->plugin_path( 'overrides/default-compat.php' );

			// Add Menu Nav walker
			require $this->plugin_path( 'includes/nav-walker/class-menu-walker.php' );

			// Add Widgets
			require $this->plugin_path( 'includes/class-blocks-integration.php' );
		}

		public function has_yoast_seo() {
			include_once(ABSPATH.'wp-admin/includes/plugin.php');

			if (is_plugin_active('wordpress-seo/wp-seo.php')) {
				return true;
			} else {
				return false;
			}
		}		
		
		public function has_rank_math() {
			if (function_exists('rank_math_the_breadcrumbs')) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor_pro() {
			return defined( 'ELEMENTOR_PRO_VERSION' );
		}

		/**
		 * Elementor instance
		 *
		 * @return object
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}		
		/**
		 * Elementor front instance
		 *
		 * @return object
		 */
		public function elementor_front() {
			return \Elementor\Plugin::$instance->frontend;
		}		
		
		public function elementor_editor_preview() {
			return \Elementor\Plugin::$instance->editor->is_edit_mode();
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'soft-template-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'soft-template-core/template-path', 'soft-template-core/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Ajax Mini Cart
		 * 
		 * Mini cart will update in 
		 * the same page, without 
		 * reloading the current state.
		 */
		public function add_to_cart_fragment($fragments) {
			ob_start(); ?>
				<span class="soft-template-cart-count">
					<?php echo sprintf('%d', WC()->cart->cart_contents_count); ?>
				</span>
			<?php
			$fragments['.soft-template-cart-count'] = ob_get_clean();
			return $fragments;
		}			
		
		public function add_to_cart_fragment_popup($fragments) {
			ob_start(); ?>
				<span class="soft-template-cart-popup-count">
					<?php echo sprintf('%d', WC()->cart->cart_contents_count); ?>
				</span>
			<?php
			$fragments['.soft-template-cart-popup-count'] = ob_get_clean();
			return $fragments;
		}		
		
		public function add_to_cart_price_fragment($fragments) {
			ob_start(); ?>
				<div class="soft-template-cart-total">
					<?php echo wc_price(sprintf('%d', WC()->cart->total)); ?>
				</div>
			<?php
			$fragments['.soft-template-cart-total'] = ob_get_clean();
			return $fragments;
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'soft_template_core' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function soft_template_core() {
		return Soft_template_Core::get_instance();
	}
}

soft_template_core();