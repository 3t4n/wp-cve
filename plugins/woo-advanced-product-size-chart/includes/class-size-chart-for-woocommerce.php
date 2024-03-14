<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SCFW_Size_Chart_For_Woocommerce
 * @subpackage SCFW_Size_Chart_For_Woocommerce/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class SCFW_Size_Chart_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SCFW_Size_Chart_For_Woocommerce_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The Size chart post type.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $post_type_name The string used to uniquely identify this plugin.
	 */
	protected $post_type_name;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * SCFW_Size_Chart_For_Woocommerce constructor.
	 *
	 * @param $name
	 * @param $version
	 * @param $post_type_name
	 *
	 * @since    1.0.0
	 */
	public function __construct( $name, $version, $post_type_name ) {

		$this->plugin_name    = $name;
		$this->version        = $version;
		$this->post_type_name = $post_type_name;

		$this->scfw_load_dependencies();
		$this->scfw_define_admin_hooks();
		$this->scfw_define_public_hooks();

		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter( "{$prefix}plugin_action_links_" . plugin_dir_path( plugin_basename( dirname( __FILE__ ) ) ) . 'size-chart-for-woocommerce.php', array(
			$this,
			'scfw_plugin_action_links_callback'
		), 10, 4 );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SCFW_Size_Chart_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - SCFW_Size_Chart_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - SCFW_Size_Chart_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function scfw_load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-size-chart-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-size-chart-for-woocommerce-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-size-chart-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-size-chart-for-woocommerce-public.php';

		$this->loader = new SCFW_Size_Chart_For_Woocommerce_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function scfw_define_admin_hooks() {
		$plugin_admin = new SCFW_Size_Chart_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_post_type_name() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'scfw_enqueue_styles_scripts_callback' );
		$this->loader->add_action( 'init', $plugin_admin, 'scfw_size_chart_register_post_type_chart_callback' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'scfw_size_chart_welcome_screen_and_default_posts_callback' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'scfw_size_chart_welcome_page_screen_and_menu_callback' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'scfw_welcome_screen_remove_menus_callback' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'scfw_size_chart_custom_styles_and_scripts' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'scfw_size_chart_preview_dialog_box_callback' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'scfw_size_chart_add_meta_box_callback' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'scfw_size_chart_product_and_size_chart_save_callback' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'scfw_post_updated_messages' );

		$this->loader->add_action( 'admin_action_size_chart_duplicate_post', $plugin_admin, 'scfw_size_chart_duplicate_post_callback' );
		$this->loader->add_action( 'admin_action_size_chart_preview_post', $plugin_admin, 'scfw_size_chart_preview_post_callback' );
		
		$this->loader->add_action( 'wp_ajax_size_chart_preview_post', $plugin_admin, 'scfw_size_chart_preview_post_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_search_chart', $plugin_admin, 'scfw_size_chart_search_chart_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_product_assign', $plugin_admin, 'scfw_size_chart_product_assign_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_quick_search_products', $plugin_admin, 'scfw_size_chart_quick_search_products_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_unassign_product', $plugin_admin, 'scfw_size_chart_unassign_product_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_export_data', $plugin_admin, 'scfw_size_chart_export_data_callback' );
		$this->loader->add_action( 'wp_ajax_size_chart_import_data', $plugin_admin, 'scfw_size_chart_import_data_callback' );
		$this->loader->add_action( 'wp_ajax_scfw_export_settings_action', $plugin_admin, 'scfw_export_settings_action__premium_only' );
		$this->loader->add_action( 'wp_ajax_scfw_import_settings_action', $plugin_admin, 'scfw_import_settings_action__premium_only' );
		$this->loader->add_filter( 'manage_edit-size-chart_columns', $plugin_admin, 'scfw_size_chart_column_callback' );
		$this->loader->add_filter( 'manage_size-chart_posts_custom_column', $plugin_admin, 'scfw_size_chart_manage_column_callback' );
		$this->loader->add_filter( 'views_edit-size-chart', $plugin_admin, 'scfw_size_chart_view_edit_callback' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'scfw_size_chart_remove_row_actions_callback', apply_filters( 'size_chart_post_row_actions_priority', 99 ), 2 );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'scfw_size_chart_filter_default_template_callback' );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'scfw_size_chart_filter_default_template_query_callback' );
		$this->loader->add_action( 'trashed_post', $plugin_admin, 'scfw_size_chart_selected_chart_delete_callback' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'scfw_size_chart_admin_notice_review_callback' );
		$this->loader->add_action( 'wp_ajax_scfw_plugin_setup_wizard_submit', $plugin_admin, 'scfw_plugin_setup_wizard_submit' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'scfw_send_wizard_data_after_plugin_activation' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function scfw_define_public_hooks() {
		$plugin_public = new SCFW_Size_Chart_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version(), $this->get_post_type_name() );
	
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'scfw_enqueue_styles_scripts_callback' );
		$this->loader->add_filter( 'woocommerce_product_tabs', $plugin_public, 'scfw_size_chart_custom_product_tab_callback' );
		$this->loader->add_action( 'woocommerce_before_single_product', $plugin_public, 'scfw_size_chart_popup_button_position_callback' );
	}

	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @param array $actions associative array of action names to anchor tags
	 *
	 * @return array associative array of plugin action links
	 * @since 1.0.0
	 */
	public function scfw_plugin_action_links_callback( $actions ) {
		$custom_actions = array(
			'configure' => sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'edit.php?post_type=size-chart&page=size-chart-setting-page' ) ),
				__( 'Settings', 'size-chart-for-woocommerce' )
			),
			'docs'      => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'help Doc/Size Chart For WooCommerce plugin - help document.pdf' ),
				__( 'Docs', 'size-chart-for-woocommerce' )
			),
			'support'   => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( 'https://www.thedotstore.com/support/' ),
				__( 'Support', 'size-chart-for-woocommerce' ) )
		);

		// add the links to the front of the actions list
		return array_merge( $custom_actions, $actions );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    SCFW_Size_Chart_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_post_type_name() {
		return $this->post_type_name;
	}

}
