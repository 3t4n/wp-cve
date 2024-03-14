<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Dashboard' ) ) {

	/**
	 * Define Soft_template_Core_Dashboard class
	 */
	class Soft_template_Core_Dashboard {

		/**
		 * Dashboard page slug
		 *
		 * @var string
		 */
		public static $main_slug = 'soft-template-core';

		/**
		 * Dashboard pages
		 *
		 * @var array
		 */
		private $_pages = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_main_menu_page' ), 10 );
			add_action( 'init', array( $this, 'do_dashboard_actions' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'dashboard_assets' ), 0 );

			$this->register_dashboard_pages();

		}

		/**
		 * Enqueue dashboard assets
		 *
		 * @return void
		 */
		public function dashboard_assets() {

			if ( ! isset( $_GET['page'] ) || $this->slug() !== sanitize_key( $_GET['page'] ) ) {
				return;
			}

			wp_enqueue_style(
				'soft-template-core-dashboard',
				soft_template_core()->plugin_url( 'assets/css/dashboard.css' ),
				array(),
				soft_template_core()->get_version()
			);

		}

		/**
		 * Get dashboard page object by ID.
		 *
		 * @param  string $page [description]
		 * @return [type]       [description]
		 */
		public function get( $page = '' ) {
			return isset( $this->_pages[ $page ] ) ? $this->_pages[ $page ] : false;
		}

		/**
		 * Run dashboard actions
		 *
		 * @return [type] [description]
		 */
		public function do_dashboard_actions() {

			if ( ! isset( $_GET['softtemplate_action'] ) ) {
				return;
			}

			$action = sanitize_key( $_GET['softtemplate_action'] );
			
			if ( ! array_key_exists( $action, $this->_pages ) ) {
				return;
			}

			/**
			 * Run page specific actions
			 */
			do_action( 'soft-template-core/dashboard/actions/' . $action );

		}

		/**
		 * Register dashboard pages
		 *
		 * @return void
		 */
		public function register_dashboard_pages() {

			$base_path = soft_template_core()->plugin_path( 'includes/dashboard/' );

			require $base_path . 'base.php';
			
			$default = array();

			$default['Soft_template_Core_Dashboard_Settings'] = $base_path . 'page-settings.php';

			foreach ( $default as $class => $file ) {
				require $file;
				$this->register_dashboard_page( $class );
			}

			/**
			 * You could register custom conditions on this hook.
			 * Note - each condition should be presented like instance of class 'Soft_template_Core_Conditions_Base'
			 */
			do_action( 'soft-template-core/dashboard/pages/register', $this );

		}

		/**
		 * Register new dashboard page
		 *
		 * @return [type] [description]
		 */
		public function register_dashboard_page( $class ) {
			$page = new $class( $this );
			$this->_pages[ $page->get_slug() ] = $page;
		}

		/**
		 * Register menu page
		 *
		 * @return void
		 */
		public function register_main_menu_page() {

			$menu_icon = soft_template_core()->config->get( 'menu_icon' );

			if ( ! $menu_icon ) {
				$menu_icon = 'dashicons-admin-generic';
			}

			add_menu_page(
				soft_template_core()->config->get( 'dashboard_page_name' ),
				soft_template_core()->config->get( 'dashboard_page_name' ),
				'manage_options',
				$this->slug(),
				array( $this, 'render_dashboard' ),
				$menu_icon
			);

		}

		/**
		 * Render Admin page
		 * @return
		 */
		public function render_dashboard() {

			$pages        = $this->_pages;
			$current_page = $this->get_current_page();

			if ( ! $current_page ) {
				return;
			}

			echo '<div class="wrap softtemplate-core-dashboard">';
				echo '<div class="cx-ui-kit cx-component cx-tab cx-tab--vertical">';
					echo '<div class="cx-tab__body">';
						include soft_template_core()->get_template( 'dashboard/tabs.php' );
						echo '<div class="cx-ui-kit__content cx-component__content cx-tab__content tab-' . $current_page->get_slug() . '">';
							$current_page->render_page();
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		}

		/**
		 * Returns URL to passed page.
		 *
		 * @param  [type] $page [description]
		 * @return [type]       [description]
		 */
		public function get_page_link( $page ) {

			if ( is_string( $page ) ) {
				$page = isset( $this->_pages[ $page ] ) ? $this->_pages[ $page ] : false;
			}

			if ( ! $page ) {
				return false;
			}

			return add_query_arg(
				array(
					'page' => $this->slug(),
					'tab'  => $page->get_slug(),
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * Get current page object
		 *
		 * @return object
		 */
		public function get_current_page() {

			$pages        = $this->_pages;
			$current_page = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';

			if ( ! $current_page ) {
				$tabs         = array_keys( $pages );
				$current_page = $tabs[0];
			}

			return isset( $pages[ $current_page ] ) ? $pages[ $current_page ] : false;

		}

		/**
		 * Returns slug
		 *
		 * @return staing
		 */
		public function slug() {
			return self::$main_slug;
		}

	}

}
