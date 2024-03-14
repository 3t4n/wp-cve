<?php
namespace WC_BPost_Shipping\Adapter;

use WC_BPost_Shipping\Container\WC_BPost_Shipping_Container_Postalcode;

/**
 * Class WC_BPost_Shipping_Adapter_Shm_Callback
 * @package WC_BPost_Shipping\Adapter
 */
class WC_BPost_Shipping_Adapter_Shm_Callback {
	/** @var array */
	private $posted;
	/** @var WC_BPost_Shipping_Container_Postalcode */
	private $postal_code;

	/**
	 * WC_BPost_Shipping_Adapter_Shm_Callback constructor.
	 *
	 * @param WC_BPost_Shipping_Container_Postalcode $postal_code
	 * @param array $posted
	 */
	public function __construct( WC_BPost_Shipping_Container_Postalcode $postal_code, array $posted ) {
		$this->postal_code = $postal_code;
		$this->posted      = $posted;
	}

	/**
	 * @param string $key
	 * @param int $filter constants FILTER_SANITIZE_*
	 * @param mixed $filter_options
	 *
	 * @return mixed
	 */
	private function get_value( $key, $filter, $filter_options = null ) {
		if ( ! array_key_exists( $key, $this->posted ) ) {
			return null;
		}

		return filter_var( $this->posted[ $key ], $filter, $filter_options );
	}

	/**
	 * @return string
	 */
	public function get_city() {
		return $this->get_value( 'customerCity', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_company() {
		return $this->get_value( 'customerCompany', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_country() {
		return $this->get_value(
			'customerCountry',
			FILTER_VALIDATE_REGEXP,
			array(
				'options' => array(
					'regexp' => '#^[A-Z]{2}$#', //ISO 3166-1 alpha-2
				),
			)
		);
	}

	/**
	 * @return string
	 */
	public function get_email() {
		return $this->get_value( 'customerEmail', FILTER_VALIDATE_EMAIL );
	}

	/**
	 * @return string
	 */
	public function get_first_name() {
		return $this->get_value( 'customerFirstName', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_last_name() {
		return $this->get_value( 'customerLastName', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_phone_number() {
		return $this->get_value( 'customerPhoneNumber', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_postal_code() {
		$regexp = $this->postal_code->get_regex_for( $this->get_country() );

		if ( ! $regexp ) {
			return $this->get_value( 'customerPostalCode', FILTER_SANITIZE_STRING );
		}

		return $this->get_value(
			'customerPostalCode',
			FILTER_VALIDATE_REGEXP,
			array(
				'options' => array(
					'regexp' => $regexp,
				),
			)
		);
	}

	/**
	 * @return string
	 */
	public function get_postal_location() {
		return $this->get_value( 'customerPostalLocation', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_street() {
		return $this->get_value( 'customerStreet', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_street_number() {
		return $this->get_value( 'customerStreetNumber', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return string
	 */
	public function get_street_box() {
		return $this->get_value( 'customerBox', FILTER_VALIDATE_INT );
	}

	/**
	 * @return string
	 */
	public function get_delivery_method() {
		return $this->get_value( 'deliveryMethod', FILTER_SANITIZE_STRING );
	}

	/**
	 * @return float
	 */
	public function get_delivery_price() {
		if ( ! array_key_exists( 'deliveryMethodPriceTotal', $this->posted ) ) {
			return null;
		}

		return filter_var(
			str_replace( ',', '.', $this->posted['deliveryMethodPriceTotal'] ),
			FILTER_VALIDATE_FLOAT
		) / 100;
	}

	/**
	 * @return string
	 */
	public function get_delivery_date() {
		return $this->get_value(
			'deliveryDate',
			FILTER_VALIDATE_REGEXP,
			array(
				'options' => array(
					'regexp' => '#^\d{4}-\d{2}-\d{2}$#',
				),
			)
		);
	}

	/**
	 * @return string
	 */
	public function get_order_reference() {
		return $this->get_value( 'orderReference', FILTER_SANITIZE_STRING );
	}

	public function get_delivery_post_point_id() {
		return $this->get_value( 'pugoKeepMeInformedViaPickupPunt', FILTER_SANITIZE_NUMBER_INT );
	}

	/**
	 * @return array
	 */
	public function get_extra() {
		$extra = $this->get_value(
			'extra',
			FILTER_CALLBACK,
			array( 'options' => array( $this, 'json_decode_assoc' ) )
		);

		if ( ! $extra ) {
			return array();
		}

		return $extra;
	}

	/**
	 * @param $encoded_json
	 *
	 * @return array|bool
	 * @see get_extra (Fitler callback)
	 */
	private function json_decode_assoc( $encoded_json ) {
		return json_decode( $encoded_json, true );
	}

	/**
	 * @return string
	 */
	public function get_state() {
		$extra = $this->get_extra();
		if ( isset( $extra['customerState'] ) ) {
			return (string) $extra['customerState'];
		}

		return '';
	}

}
