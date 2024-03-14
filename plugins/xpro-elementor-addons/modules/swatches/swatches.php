<?php

namespace XproElementorAddons\Modules\Swatches;

defined( 'ABSPATH' ) || exit;

class Swatches {

	private static $instance = null;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	const PA_COLOR = 'xpro_color';
	const PA_IMAGE = 'xpro_image';
	const PA_LABEL = 'xpro_label';

	private $attribute_types = array();


	public static function asset_source( $type = 'css', $directory = null ) {

		return XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/swatches/assets/' . $type . '/' . $directory;
	}

	public static function get_tax_attribute( $taxonomy ) {

		global $wpdb;

		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'woocommerce_attribute_taxonomies WHERE attribute_name = %s', $attr ) );

		return $attr;
	}


	public static function get_dummy() {

		return WC()->plugin_url() . '/assets/images/placeholder.png';
	}

	public function init() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'modules/swatches/loop-product-support/xpro-swatches.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'modules/swatches/admin-product.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'modules/swatches/attribute-hooks.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'modules/swatches/frontend.php';

		$this->set_attribute_types( self::PA_COLOR, esc_html__( 'Xpro Color', 'xpro-elementor-addons' ) );
		$this->set_attribute_types( self::PA_IMAGE, esc_html__( 'Xpro Image', 'xpro-elementor-addons' ) );
		$this->set_attribute_types( self::PA_LABEL, esc_html__( 'Xpro Label', 'xpro-elementor-addons' ) );

		add_filter( 'product_attributes_type_selector', array( $this, 'push_attribute_types' ) );

		if ( is_admin() ) {

			add_action( 'admin_init', array( $this, 'init_hooks' ) );
			add_action( 'admin_print_scripts', array( $this, 'enqueue' ) );
			add_action( 'admin_init', array( $this, 'includes_product' ) );
		}

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			add_action( 'init', array( $this, 'init_frontend_hook' ) );
		}

		/**
		 * add swatches to product loop
		 */
		Xpro_Swatches::instance();
	}


	public function push_attribute_types( $types ) {

		$types = array_merge( $types, $this->attribute_types );

		return $types;
	}


	private function set_attribute_types( $key, $title ) {

		$this->attribute_types[ $key ] = $title;

		return $this;
	}


	public function includes_product() {
		Admin_Product::instance();
	}


	public function init_hooks() {
		Attribute_Hooks::instance();
	}


	public function init_frontend_hook() {
		Frontend::instance();
	}


	public function enqueue() {

		$screen = get_current_screen();
		if ( empty( $screen ) ) {
			return;
		}

		if ( strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'xpro-css-admin', self::asset_source( 'css', 'admin.css' ), array( 'wp-color-picker' ), XPRO_ELEMENTOR_ADDONS_VERSION );
		wp_enqueue_script( 'xpro-js-admin', self::asset_source( 'js', 'admin.js' ), array( 'jquery', 'wp-color-picker', 'wp-util' ), XPRO_ELEMENTOR_ADDONS_VERSION, true );

		wp_localize_script(
			'xpro-js-admin',
			'swatch_conf',
			array(
				'i18n'  => array(
					'title'  => esc_html__( 'Choose an image', 'xpro-elementor-addons' ),
					'button' => esc_html__( 'Use image', 'xpro-elementor-addons' ),
				),
				'dummy' => self::get_dummy(),
			)
		);
	}


	public function get_available_types() {

		return $this->attribute_types;
	}

}

Swatches::instance();
