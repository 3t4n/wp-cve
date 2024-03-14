<?php
/**
 * Admin class
 *
 * @class   YITH_Popup_Admin
 * @package YITH WooCommerce Popup
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YPOP_INIT' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YITH_Popup_Admin' ) ) {
	/**
	 * YITH_Popup_Admin class
	 *
	 * @since 1.0.0
	 */
	class YITH_Popup_Admin {
		/**
		 * Single instance of the class
		 *
		 * @var \YITH_Popup_Admin
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 *  Premium tab template file name
		 *
		 * @var string
		 */
		protected $premium = 'premium.php';

		/**
		 * Panel
		 *
		 * @var Panel Object
		 */
		protected $panel;

		/**
		 * Premium landing URL
		 *
		 * @var string
		 */
		protected $premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-popup/';

		/**
		 * Panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_woocommerce_popup';

		/**
		 * The name for the plugin options
		 *
		 * @access public
		 * @var string
		 * @since 1.0.0
		 */
		public $plugin_options = 'yit_ypop_options';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_Popup_Admin
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @return \YITH_Popup_Admin
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->create_menu_items();

			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_YPOP_DIR . '/' . basename( YITH_YPOP_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// custom styles and javascript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 20 );

			add_filter( 'yit_fw_metaboxes_type_args', array( $this, 'add_custom_metaboxes' ) );

			add_action( 'wp_ajax_ypop_change_status', array( $this, 'change_status' ) );
			add_action( 'wp_ajax_nopriv_ypop_change_status', array( $this, 'change_status' ) );

			add_filter( 'yit_fw_metaboxes_type_args', array( $this, 'textarea_metabox' ) );
			add_filter( 'yith_plugin_fw_metabox_class', array( $this, 'add_custom_metabox_class' ), 10, 2 );

		}


		/**
		 * Change value in a metabox
		 * Modify the metabox value in a textarea-editor when the value is empty.
		 *
		 * @since  1.0
		 * @param array $args .
		 *
		 * @return mixed
		 */
		public function textarea_metabox( $args ) {
			if ( ! isset( $_REQUEST['post'] ) ) { //phpcs:ignore
				return $args;
			}
			$post_id = sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ); //phpcs:ignore

			if ( 'textarea-editor' === $args['type'] ) {
				$meta_value                    = YITH_Popup()->get_meta( $args['args']['args']['id'], $post_id );
				$args['args']['args']['value'] = $meta_value;
			}

			return $args;
		}

		/**
		 * Create Menu Items
		 *
		 * Print admin menu items
		 *
		 * @since  1.0
		 */
		private function create_menu_items() {
			// Add a panel under YITH Plugins tab.
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			add_action( 'yith_ypop_premium_tab', array( $this, 'premium_tab' ) );
		}

		/**
		 * Action Links
		 * Add the action links to plugin admin page.
		 *
		 * @param array $links Links plugin.
		 *
		 * @return   mixed Array
		 * @use      plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, false );
			return $links;
		}

		/**
		 * Enqueue styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 */
		public function enqueue_styles_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_register_script( 'yith_ypop_admin', YITH_YPOP_ASSETS_URL . '/js/backend' . $suffix . '.js', array( 'jquery', 'yith-plugin-fw-fields' ), YITH_YPOP_VERSION, true );
			wp_register_style( 'yith_ypop_backend', YITH_YPOP_ASSETS_URL . '/css/backend.css', array( 'yith-plugin-fw-fields' ), YITH_YPOP_VERSION );

			wp_register_style(
				'select2',
				str_replace(
					array(
						'http:',
						'https:',
					),
					'',
					WC()->plugin_url()
				) . '/assets/css/select2.css',
				false,
				YITH_YPOP_VERSION
			);

			wp_localize_script( 'yith_ypop_admin', 'ypop_backend', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			if ( 'edit-yith_popup' === $screen_id || yith_popup_check_valid_admin_page( 'yith_popup' ) || isset( $_GET['page'] ) && 'yith_woocommerce_popup' === $_GET['page'] ) { //phpcs:ignore
				wp_enqueue_script( 'yith_ypop_admin' );
				wp_enqueue_style( 'yith_ypop_admin' );
				wp_enqueue_style( 'select2' );
				if ( ! wp_script_is( 'selectWoo' ) ) {
					wp_enqueue_script( 'selectWoo' );
					wp_enqueue_script( 'wc-enhanced-select' );
				}
			}

		}


		/**
		 * Add the action links to plugin admin page.
		 *
		 * @param array  $new_row_meta_args Plugin Meta New args.
		 * @param string $plugin_meta Plugin Meta.
		 * @param string $plugin_file Plugin file.
		 * @param array  $plugin_data Plugin data.
		 * @param string $status Status.
		 * @param string $init_file Init file.
		 *
		 * @return array
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, string $init_file = 'YITH_YPOP_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_YPOP_SLUG;
			}

			return $new_row_meta_args;
		}

		/**
		 * Get the premium landing uri
		 *
		 * @return  string The premium landing link
		 * @since   1.0.0
		 */
		public function get_premium_landing_uri() {

			return apply_filters( 'yith_plugin_fw_premium_landing_uri', $this->premium_landing, YITH_YPOP_SLUG );
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'popups'   => __( 'Popups', 'yith-woocommerce-popup' ),
				'settings' => __( 'Settings', 'yith-woocommerce-popup' ),
			);

			if ( defined( 'YITH_YPOP_FREE_INIT' ) ) {
				$admin_tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-popup' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'plugin_slug'      => YITH_YPOP_SLUG,
				'page_title'       => 'YITH WooCommerce Popup',
				'menu_title'       => 'Popup',
				'capability'       => 'manage_options',
				'parent'           => 'ypop',
				'parent_page'      => 'yith_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => $admin_tabs,
				'class'            => yith_set_wrapper_class(),
				'options-path'     => YITH_YPOP_DIR . '/plugin-options',
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel' ) ) {
				require_once YITH_YPOP_DIR . '/plugin-fw/lib/yit-plugin-panel.php';
			}

			$this->panel = new YIT_Plugin_Panel( $args );
		}


		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @return   void
		 * @since    1.0
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_YPOP_TEMPLATE_PATH . '/admin/' . $this->premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}
		}


		/**
		 * Enable custom metabox type
		 *
		 * @param array $args Arguments.
		 * @use yit_fw_metaboxes_type_args
		 * @return mixed
		 */
		public function add_custom_metaboxes( $args ) {

			if ( 'iconlist' === $args['type'] ) {
				$args['basename'] = YITH_YPOP_DIR;
				$args['path']     = 'metaboxes/types/';
			}

			return $args;
		}

		/**
		 * Change status
		 *
		 * @return false
		 */
		public function change_status() {
			check_ajax_referer( 'popup-toggle-enabled', 'security' );

			if ( ! isset( $_REQUEST['post_id'] ) ) { //phpcs:ignore
				return false;
			}

			$post_id = sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ); //phpcs:ignore
			if ( 'enable' === sanitize_text_field( wp_unslash( $_REQUEST['status'] ) ) ) { //phpcs:ignore
				$updated = update_post_meta( $post_id, '_enable_popup', 1 );
			} else {
				$updated = update_post_meta( $post_id, '_enable_popup', 0 );
			}

			echo $updated; //phpcs:ignore

			die();

		}

		/**
		 * Add new plugin-fw style.
		 *
		 * @param string  $class .
		 * @param WP_Post $post .
		 *
		 * @return string
		 */
		public function add_custom_metabox_class( $class, $post ) {

			$allow_post_types = array( 'yith_popup' );
			if ( in_array( $post->post_type, $allow_post_types, true ) ) {
				$class .= ' ' . yith_set_wrapper_class();
			}
			return $class;
		}


	}

	/**
	 * Unique access to instance of YITH_Popup_Admin class
	 *
	 * @return \YITH_Popup_Admin
	 */
	function YITH_Popup_Admin() { //phpcs:ignore
		return YITH_Popup_Admin::get_instance();
	}
}
