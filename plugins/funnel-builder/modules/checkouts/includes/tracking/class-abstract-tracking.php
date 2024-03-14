<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]

 abstract class WFACP_Analytics {
	protected $slug = '';

	protected $checkout_data = [];
	protected $add_to_cart_data = [];
	protected $id = [];
	protected static $available_services = [];
	protected static $global_settings = [];
	protected static $page_settings = [];
	protected static $tag_manager_enqueued = [];

	protected $variable_as_simple = false;
	protected $id_prefix = '';
	protected $id_suffix = '';
	protected $exclude_tax = false;
	protected $content_id_type = '';

	protected function __construct() {
		$this->admin_general_settings = BWF_Admin_General_Settings::get_instance();
		if ( wp_doing_ajax() && isset( $_REQUEST['wc-ajax'] ) ) {
			$this->prepare_data();
		} else {
			add_action( 'wfacp_after_checkout_page_found', [ $this, 'prepare_data' ] );
			add_action( 'wfacp_after_native_checkout_page_found', [ $this, 'prepare_data' ] );
		}


	}

	final public function prepare_data() {
		if ( true !== $this->enable_tracking() ) {
			return;
		}

		self::$page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		$this->content_id_type    = $this->admin_general_settings->get_option( $this->slug . '_content_id_type' );
		$this->variable_as_simple = $this->admin_general_settings->get_option( $this->slug . '_variable_as_simple' );
		$this->id_prefix          = $this->admin_general_settings->get_option( $this->slug . '_content_id_prefix' );
		$this->id_suffix          = $this->admin_general_settings->get_option( $this->slug . '_content_id_suffix' );
		if ( empty( $this->variable_as_simple ) ) {
			$this->variable_as_simple = false;
		}
		$exclude_from_total = false;
		if ( $this->slug === 'pixel' ) {
			$exclude_from_total = $this->admin_general_settings->get_option( 'exclude_from_total' );
		} elseif ( $this->slug === 'google_ua' ) {
			$exclude_from_total = $this->admin_general_settings->get_option( 'ga_exclude_from_total' );
		} elseif ( $this->slug === 'google_ads' ) {
			$exclude_from_total = $this->admin_general_settings->get_option( 'gad_exclude_from_total' );
		} elseif ( $this->slug === 'pint' ) {
			$exclude_from_total = $this->admin_general_settings->get_option( 'pint_exclude_from_total' );
		}

		if ( is_array( $exclude_from_total ) && count( $exclude_from_total ) > 0 && in_array( 'is_disable_taxes', $exclude_from_total, true ) ) {
			$this->exclude_tax = true;
		}
		$this->get_prepare_data();

		self::$available_services[ $this->slug ] = $this;
	}

	final public function number_format( $value, $format_count = 2 ) {

		$output = number_format( floatval( $value ), wc_get_price_decimals(), '.', '' );

		return apply_filters( 'wfacp_analytics_number_format', $output, $value, $format_count, $this );
	}

	protected function enable_tracking() {
		return apply_filters( 'wfacp_enable_tracking_' . $this->slug, true );
	}

	public function get_checkout_data() {
		return $this->checkout_data;
	}

	public function get_add_to_cart_data() {
		return $this->add_to_cart_data;
	}

	public function is_fb_enable_content_on() {
		$is_fb_enable_content_on = $this->admin_general_settings->get_option( 'is_fb_enable_content' );
		if ( is_array( $is_fb_enable_content_on ) && count( $is_fb_enable_content_on ) > 0 && 'yes' === $is_fb_enable_content_on[0] ) {
			return true;
		}
	}

	public function get_product_content_id( $product_id ) {

		if ( $this->content_id_type == 'product_sku' ) {
			$content_id = get_post_meta( $product_id, '_sku', true );
			if ( empty( $content_id ) ) {
				$content_id = $product_id;
			}
		} else {
			$content_id = $product_id;
		}
		$value = $this->id_prefix . $content_id . $this->id_suffix;

		return $value;
	}

	public function get_cart_item_id( $item ) {
		$product_id = $item['product_id'];

		if ( false == wc_string_to_bool( $this->variable_as_simple ) && isset( $item['variation_id'] ) && $item['variation_id'] !== 0 ) {

			$product_id = $item['variation_id'];
		}

		return $product_id;
	}


	public function get_options() {

		$page_settings = self::$page_settings;

		$pixel_id = $this->get_key();

		$override_global_track_event = wc_string_to_bool( isset( $page_settings['override_global_track_event'] ) ? $page_settings['override_global_track_event'] : false );
		$locals                      = [];
		$pixel_id                    = apply_filters( 'wfacp_' . $this->slug . '_id', $pixel_id );

		if ( '' === $pixel_id ) {
			return $locals;
		}

		$add_to_cart  = false === $this->is_global_add_to_cart_enabled() ? $this->admin_general_settings->get_option( $this->slug . '_add_to_cart_event' ) ? $this->admin_general_settings->get_option( $this->slug . '_add_to_cart_event' ) : 'false' : 'true';
		$checkout_ev  = $this->admin_general_settings->get_option( $this->slug . '_initiate_checkout_event' ) ? $this->admin_general_settings->get_option( $this->slug . '_initiate_checkout_event' ) : 'false';
		$page_view    = false === $this->is_global_pageview_enabled() ? $this->admin_general_settings->get_option( $this->slug . '_is_page_view' ) ? $this->admin_general_settings->get_option( $this->slug . '_is_page_view' ) : 'false' : 'true';
		$payment_info = $this->admin_general_settings->get_option( $this->slug . '_add_payment_info_event' ) ? $this->admin_general_settings->get_option( $this->slug . '_add_payment_info_event' ) : 'false';

		$custom_event         = $this->enable_custom_event();
		$add_to_cart_position = 'load';
		$checkout_ev_position = 'load';
		if ( true == $override_global_track_event ) {
			$add_to_cart  = isset( $page_settings[ $this->slug . '_add_to_cart_event' ] ) ? $page_settings[ $this->slug . '_add_to_cart_event' ] : false;
			$checkout_ev  = isset( $page_settings[ $this->slug . '_initiate_checkout_event' ] ) ? $page_settings[ $this->slug . '_initiate_checkout_event' ] : false;
			$payment_info = isset( $page_settings[ $this->slug . '_add_payment_info_event' ] ) ? $page_settings[ $this->slug . '_add_payment_info_event' ] : false;
			$page_view    = isset( $page_settings[ $this->slug . '_is_page_view' ] ) ? $page_settings[ $this->slug . '_is_page_view' ] : false;

			if ( wc_string_to_bool( $add_to_cart ) ) {
				$add_to_cart_position = isset( $page_settings[ $this->slug . '_add_to_cart_event_position' ] ) ? $page_settings[ $this->slug . '_add_to_cart_event_position' ] : $add_to_cart_position;
			}

			if ( wc_string_to_bool( $checkout_ev ) ) {
				$checkout_ev_position = isset( $page_settings[ $this->slug . '_initiate_checkout_event_position' ] ) ? $page_settings[ $this->slug . '_initiate_checkout_event_position' ] : $checkout_ev_position;
			}
		}

		$locals = [
			'id'        => $pixel_id,
			'positions' => [
				'add_to_cart' => $add_to_cart_position,
				'checkout'    => $checkout_ev_position,
			],
			'settings'  => [
				'add_to_cart' => wc_string_to_bool( $add_to_cart ) ? 'true' : 'false',
				'page_view'   => wc_string_to_bool( $page_view ) ? 'true' : 'false',
				'checkout'    => wc_string_to_bool( $checkout_ev ) ? 'true' : 'false',
				'payment'     => wc_string_to_bool( $payment_info ) ? 'true' : 'false',
				'custom'      => wc_string_to_bool( $custom_event ) ? 'true' : 'false',
			]
		];

		return apply_filters( 'wfacp_tracking_options_data', $locals, $this );
	}

	public function enable_custom_event() {
		return false;
	}

	/**
	 * @param $product_obj WC_Product
	 * @param $cart_item
	 *
	 * @return array
	 */
	public function get_item( $product_obj, $cart_item ) {
		return [];
	}

	public function get_prepare_data() {
		$options = $this->get_options();
		if ( ! isset( $options['id'] ) || empty( $options['id'] ) ) {
			return $options;
		}

		if ( wc_string_to_bool( $options['settings']['add_to_cart'] ) ) {

			$this->add_to_cart_data = $this->get_add_to_cart_data();
			$options['add_to_cart'] = $this->add_to_cart_data;
		}
		if ( wc_string_to_bool( $options['settings']['checkout'] ) ) {
			$this->checkout_data = $this->get_checkout_data();
			$options['checkout'] = $this->checkout_data;
		}


		return $options;
	}

	final public static function get_available_service() {
		return self::$available_services;
	}

	/**
	 * @param string $taxonomy Taxonomy name
	 *
	 * @return array Array of object term names
	 */
	public function get_object_terms( $taxonomy, $post_id ) {

		$terms   = get_the_terms( $post_id, $taxonomy );
		$results = array();

		if ( is_wp_error( $terms ) || empty ( $terms ) ) {
			return array();
		}

		// decode special chars
		foreach ( $terms as $term ) {
			$results[] = html_entity_decode( $term->name );
		}

		return $results;

	}

	function getWooCartTotal() {


		if ( wc_string_to_bool( $this->exclude_tax ) ) {
			$total = WC()->cart->cart_contents_total;

		} else {
			$total = WC()->cart->cart_contents_total + WC()->cart->tax_total;
		}

		return $total;

	}

	public function get_key() {
		return '';
	}


	public function get_conversion_api_access_token() {

		$get_conversion_api_access_token = apply_filters( 'wfacp_conversion_api_access_token', $this->admin_general_settings->get_option( 'conversion_api_access_token' ) );

		return empty( $get_conversion_api_access_token ) ? '' : $get_conversion_api_access_token;
	}

	public function get_conversion_api_test_event_code() {

		$get_conversion_api_test_event_code = apply_filters( 'wfacp_conversion_api_test_event_code', $this->admin_general_settings->get_option( 'conversion_api_test_event_code' ) );

		return empty( $get_conversion_api_test_event_code ) ? '' : $get_conversion_api_test_event_code;
	}


	protected static function print_google_tag_manager_js( $pixel_id ) {

		if ( empty( $pixel_id ) || true == self::$tag_manager_enqueued ) {
			return;
		}
		self::$tag_manager_enqueued = true;
		$ga_ids                     = explode( ',', $pixel_id );
		if ( is_array( $ga_ids ) && count( $ga_ids ) > 0 ) {
			echo sprintf( "<script defer src='https://www.googletagmanager.com/gtag/js?id=%s'></script>", $ga_ids[0] );

		}
	}

	/**
	 * @param $product \WC_Product
	 *
	 * @return array
	 */
	public function get_product_item( $product ) {
		return [];
	}

	public function is_global_pageview_enabled() {
		return false;
	}

	public function is_global_add_to_cart_enabled() {
		return false;
	}

	public function getEventRequestUri() {
		$request_uri = "";
		if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //phpcs:ignore
		}

		return $request_uri;
	}

}