<?php
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;

/**
 * Class WC_BPost_Shipping_Json_Array_Controller
 */
class WC_BPost_Shipping_Json_Array_Controller extends WC_BPost_Shipping_Controller_Base {
	/** @var string[] ['BE' => 10, 'FR' => 50, 'NL' => 50] */
	private $free_shipping_map;
	/** @var string[] ['RU'] */
	private $allowed_countries;
	/** @var string */
	private $field;

	/**
	 * WC_BPost_Shipping_Free_Json_Array_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param string $field name
	 * @param string $free_shipping_json json decodable to string[] $free_shipping_map ['be' => 10, 'fr' => 50, 'nl' => 50]
	 * @param string[] $allowed_countries
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		$field,
		$free_shipping_json,
		$allowed_countries
	) {
		parent::__construct( $adapter );
		$this->free_shipping_map = json_decode( $free_shipping_json, true );

		//if invalid/empty json string create an empty array to avoid issue
		if ( ! $this->free_shipping_map ) {
			$this->free_shipping_map = array();
		}
		$this->allowed_countries = $allowed_countries;
		$this->field             = $field;
	}

	public function load_template() {
		list( $free_shipping, $no_free_countries ) = $this->get_free_and_not_free_countries();

		$this->get_template(
			'json-array.php',
			array(
				'field'              => $this->field,
				'no_free_countries'  => $no_free_countries,
				'free_shipping'      => $free_shipping,
				'allowed_countries'  => $this->allowed_countries,
				'prices_include_tax' => wc_prices_include_tax(),
			)
		);
	}

	/**
	 * @return array
	 */
	private function get_free_and_not_free_countries() {
		$free_shipping     = array();
		$no_free_countries = array();

		foreach ( $this->allowed_countries as $allowed_country => $allowed_country_name ) {
			if ( array_key_exists( $allowed_country, $this->free_shipping_map ) ) {
				$free_shipping[] = array(
					'country' => $allowed_country,
					'from'    => $this->free_shipping_map[ $allowed_country ],
				);
			} else {
				$no_free_countries[] = $allowed_country;
			}
		}

		return array( $free_shipping, $no_free_countries );
	}
}
