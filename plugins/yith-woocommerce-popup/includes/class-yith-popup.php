<?php
/**
 * Main class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Popup
 * @version 1.0.0
 */

if ( ! defined( 'YITH_YPOP_INIT' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! class_exists( 'YITH_Popup' ) ) {
	/**
	 * YITH WooCommerce Popup main class
	 *
	 * @since 1.0.0
	 */
	class YITH_Popup {
		/**
		 * Single instance of the class
		 *
		 * @var \YITH WooCommerce Popup
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Array with accessible variables
		 *
		 * @var array
		 */
		protected $_data = array(); //phpcs:ignore

		/**
		 * Post type name
		 *
		 * @var string
		 */
		public $post_type_name = 'yith_popup';

		/**
		 * Template list
		 *
		 * @var array
		 */
		public $template_list = array();

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
		 * @return \YITH WooCommerce Popup
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
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->set_templates();

			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'init', array( $this, 'create_post_type' ), 0 );
			add_action( 'admin_init', array( $this, 'add_metabox' ), 1 );

			add_filter( 'manage_edit-' . $this->post_type_name . '_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_' . $this->post_type_name . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );

			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

		}

		/**
		 * Set Template
		 */
		public function set_templates() {
			$this->template_list = array(
				'theme1' => __( 'Theme 1', 'yith-woocommerce-popup' ),
			);

			$_data['template_list'] = $this->template_list;
		}


		/**
		 * Register Custom Post Type
		 */
		public function create_post_type() {

			$labels = array(
				'name'               => esc_html_x( 'Yith Popup', 'Post Type General Name', 'yith-woocommerce-popup' ),
				'singular_name'      => esc_html_x( 'Yith Popup', 'Post Type Singular Name', 'yith-woocommerce-popup' ),
				'menu_name'          => esc_html__( 'Popup', 'yith-woocommerce-popup' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'yith-woocommerce-popup' ),
				'all_items'          => esc_html__( 'All Popups', 'yith-woocommerce-popup' ),
				'view_item'          => esc_html__( 'View Popup', 'yith-woocommerce-popup' ),
				'add_new_item'       => esc_html__( 'Add New Popup', 'yith-woocommerce-popup' ),
				'add_new'            => esc_html__( 'Add New Popup', 'yith-woocommerce-popup' ),
				'edit_item'          => esc_html__( 'Edit Popup', 'yith-woocommerce-popup' ),
				'update_item'        => esc_html__( 'Update Popup', 'yith-woocommerce-popup' ),
				'search_items'       => esc_html__( 'Search Popup', 'yith-woocommerce-popup' ),
				'not_found'          => esc_html__( 'Not found', 'yith-woocommerce-popup' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'yith-woocommerce-popup' ),
			);
			$args   = array(
				'label'               => esc_html__( 'yith_popup', 'yith-woocommerce-popup' ),
				'description'         => esc_html__( 'Yith Popup Description', 'yith-woocommerce-popup' ),
				'labels'              => $labels,
				'supports'            => array( 'title' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => null,
				'can_export'          => true,
				'has_archive'         => true,
				'menu_icon'           => 'dashicons-feedback',
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
			);

			register_post_type( $this->post_type_name, $args );

		}

		/**
		 * Return a $property defined in this class
		 *
		 * @param mixed $property .
		 * @since   1.0.0
		 * @return  mixed
		 */
		public function __get( $property ) {
			if ( isset( $this->_data[ $property ] ) ) {
				return $this->_data[ $property ];
			}
		}

		/**
		 * Load YIT Plugin Framework
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}


		/**
		 * Get options from db
		 *
		 * @access public
		 * @since 1.0.0
		 * @param string $option .
		 * @return mixed
		 */
		public function get_option( $option ) {
			// get all options.
			$options = get_option( $this->plugin_options );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return false;
		}

		/**
		 * Add metabox in popup page
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function add_metabox() {

			if ( ! function_exists( 'YIT_Metabox' ) ) {
				require_once 'plugin-fw/yit-plugin.php';
			}

			$args             = require_once YITH_YPOP_DIR . '/plugin-options/metabox/ypop-template.php';
			$metabox_template = YIT_Metabox( 'yit-pop' );
			$metabox_template->init( $args );

			$args    = require_once YITH_YPOP_DIR . '/plugin-options/metabox/ypop-metabox.php';
			$metabox = YIT_Metabox( 'yit-pop-info' );
			$metabox->init( $args );

			$args    = require_once YITH_YPOP_DIR . '/plugin-options/metabox/ypop-cpt-metabox.php';
			$metabox = YIT_Metabox( 'yit-cpt-info' );
			$metabox->init( $args );

		}

		/**
		 * Get meta from Metabox Panel
		 *
		 * @param string $meta .
		 * @param int    $post_id .
		 *
		 * @return mixed
		 * @since    1.0
		 */
		public function get_meta( $meta, $post_id ) {
			$meta_value = get_post_meta( $post_id, $meta, true );

			if ( isset( $meta_value ) ) {
				return $meta_value;
			} else {
				return '';
			}
		}

		/**
		 * Get list of popups
		 *
		 * @return array
		 */
		public function get_popups_list() {
			$popups = get_posts( 'post_type=' . $this->post_type_name . '&posts_per_page=-1' );

			$array = array();
			if ( ! empty( $popups ) ) {
				foreach ( $popups as $popup ) {
					$array[ $popup->ID ] = $popup->post_title;
				}
			}

			return $array;
		}


		/**
		 * Edit columns
		 *
		 * @param array $columns .
		 * @return array
		 */
		public function edit_columns( $columns ) {
			$columns = array(
				'cb'       => '<input type="checkbox" />',
				'title'    => __( 'Title', 'yith-woocommerce-popup' ),
				'template' => __( 'Template', 'yith-woocommerce-popup' ),
				'content'  => __( 'Content Type', 'yith-woocommerce-popup' ),
				'active'   => __( 'Active', 'yith-woocommerce-popup' ),
			);

			return $columns;
		}

		/**
		 * Custom columns.
		 *
		 * @param string $column .
		 * @param int    $post_id .
		 */
		public function custom_columns( $column, $post_id ) {
			$template = get_post_meta( $post_id, '_template_name', true );
			$enabled  = (int) get_post_meta( $post_id, '_enable_popup', true );
			$enabled  = 1 === $enabled ? 'yes' : 'no';

			switch ( $column ) {
				case 'template':
					echo esc_html( $template );
					break;
				case 'content':
					$content = get_post_meta( $post_id, '_' . $template . '_content_type', true );
					if ( is_string( $content ) ) {
						echo wp_kses_post( $content );
					}
					break;
				case 'active':
					yith_plugin_fw_get_field(
						array(
							'type'  => 'onoff',
							'class' => 'ypop-popup-toggle-enabled',
							'value' => $enabled,
							'data'  => array(
								'id'       => $post_id,
								'security' => wp_create_nonce( 'popup-toggle-enabled' ),
							),
						),
						true
					);
					break;
			}
		}

		/***
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_YPOP_FREE_INIT, true );
			}
		}

	}

	/**
	 * Unique access to instance of YITH_Popup class
	 *
	 * @return \YITH_Popup
	 */
	function YITH_Popup() { //phpcs:ignore
		return YITH_Popup::get_instance();
	}
}

