<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Components {

	protected static $components_dir;
	protected static $components = array();
	protected static $components_fields = array();
	private static $instance = null;
	public $order_data;
	public $view_data;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_components' ), 1 );
		add_action( 'wp', array( $this, 'load_components_on_custom_thank_you_page_and_set_page' ), 0 );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function retrieve_components_fields() {
		return apply_filters( 'XLWCTY_Component_fields', self::$components_fields );
	}

	/**
	 * @param bool $component
	 *
	 * @return XLWCTY_Component|XLWCTY_Components
	 */
	public static function get_components( $component = false ) {
		$components = self::retrieve_components();
		if ( false !== $component && array_key_exists( $component, $components ) ) {
			return $components[ $component ];
		}

		return new self;
	}

	/**
	 * @return XLWCTY_Component[]
	 */
	public static function retrieve_components() {
		return apply_filters( 'XLWCTY_Component', self::$components );
	}

	public function load_components() {
		/** Return if not component builder page */
		if ( ( ! isset( $_GET['page'] ) || 'xlwcty_builder' !== $_GET['page'] ) && ! isset( $_GET['key'] ) && ! isset( $_GET['order_id'] ) && ! isset( $_POST['action'] ) ) {
			return;
		}

		$this->load_all_components();
	}

	public function load_all_components() {
		self::$components_dir = XLWCTY_PLUGIN_DIR . '/components';
		$all_components       = array(
			'additional-information',
			'coupon-code',
			'crosssell-product',
			'customer-info',
			'html',
			'image-content',
			'join-us',
			'map',
			'order-acknowledge',
			'order-details',
			'recently-viewed-product',
			'related-product',
			'simple-text',
			'smart-bribe',
			'social-share',
			'specific-product',
			'upsell-product',
			'video',
		);
		foreach ( $all_components as $entry ) {
			$needed_file    = self::$components_dir . '/' . $entry . '/data.php';
			$component_data = include_once $needed_file;
			if ( is_array( $component_data ) && isset( $component_data['instance'] ) && is_object( $component_data['instance'] ) ) {
				$slug                             = $component_data['slug'];
				self::$components_fields[ $slug ] = $component_data['fields'];
				$component_data['instance']->set_slug( $slug );
				$component_data['instance']->set_component( $component_data );
				self::$components[ $slug ] = $component_data['instance'];
			}
		}

		do_action( 'xlwcty_after_components_loaded' );
	}

	public function load_components_on_custom_thank_you_page_and_set_page() {
		global $post;
		if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
			return;
		}

		$custom_pages = get_option( 'xlwcty_custom_thank_you_pages', array() );
		if ( empty( $custom_pages ) ) {
			return;
		}
		foreach ( $custom_pages as $key => $value ) {
			if ( $value == $post->ID ) {
				/** @var checking if the  $thankyou_post not exists than continue */
				$thankyou_post = get_post( $key );
				if ( ! $thankyou_post instanceof WP_POST ) {
					continue;
				}
				$this->load_all_components();
				XLWCTY_Core()->public->xlwcty_is_thankyou = true;
				XLWCTY_Core()->data->page_id              = $key;

				return;
			}
		}
	}

	public function __call( $name, $arguments ) {

	}

}

XLWCTY_Components::get_instance();
