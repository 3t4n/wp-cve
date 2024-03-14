<?php
/**
 * Masteriyo WooCommerce integration setting class.
 *
 * @since 1.8.1
 * @package Masteriyo\Addons\WcIntegration
 */

namespace Masteriyo\Addons\WcIntegration;

use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo WooCommerce integration setting class.
 *
 * @class Masteriyo\Addons\WcIntegration\Setting
 */

class Setting {
	/**
	 * Setting option name.
	 *
	 * @var string
	 */
	private $name = 'masteriyo_wc_integration_setting';

	/**
	 * Setting data.
	 *
	 * @since 1.8.1
	 *
	 * @var array
	 */
	private $data = array(
		'unenrollment_status' => array( OrderStatus::CANCELLED, OrderStatus::FAILED, OrderStatus::REFUNDED ),
	);

	/**
	 * Initialize.
	 *
	 * @since 1.8.1
	 */
	public function init() {
		add_filter( 'masteriyo_rest_pre_insert_setting_object', array( $this, 'save' ), 10, 3 );
		add_filter( 'masteriyo_rest_prepare_setting_object', array( $this, 'append_setting_in_response' ), 10, 3 );
	}

	/**
	 * Append WooCommerce integration setting to the global settings.
	 *
	 * @since 1.8.1
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param Model            $object   Object data.
	 * @param WP_REST_Request  $request  Request object.
	 * @return WP_REST_Response
	 */
	public function append_setting_in_response( $response, $object, $request ) {
		$data                                = $response->get_data();
		$data['integrations']['woocommerce'] = $this->get();

		$response->set_data( $data );

		return $response;
	}

	/**
	 * Store woocommerce integration settings.
	 *
	 * @since 1.8.1
	 *
	 * @param Model         $setting  Object object.
	 * @param WP_REST_Request $request  Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return Masteriyo\Models\Setting
	 */
	public function save( $setting, $request, $creating ) {
		$setting_in_db = get_option( $this->name, $this->data );
		$post_data     = masteriyo_array_get( $request, 'integrations.woocommerce', array() );
		$setting_arr   = wp_parse_args( $post_data, $setting_in_db );

		update_option( $this->name, $setting_arr );

		return $setting;
	}

	/**
	 * Return setting value.
	 *
	 * @since 1.8.1
	 * @param string $key Setting key.
	 * @param mixed $default Setting default.
	 * @return mixed
	 */
	public function get( $key = null, $default = null ) {
		$setting = get_option( $this->name, array() );
		$setting = wp_parse_args( $setting, $this->data );

		$value = $key ? masteriyo_array_get( $setting, $key, $default ) : $setting;

		return $value;
	}

	/**
	 * Save setting value.
	 *
	 * @since 1.8.1
	 *
	 * @param string $key Setting key.
	 * @param mixed $default Setting default.
	 */
	public function set( $key, $value ) {
		masteriyo_array_set( $this->data, $key, $value );
		update_option( $this->name, $this->data );
	}
}
