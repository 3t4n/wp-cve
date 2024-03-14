<?php
/**
 * Class BingStructure
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */
namespace CTXFeed\V5\Structure;

use CTXFeed\V5\Merchant\MerchantAttributeReplaceFactory;
use CTXFeed\V5\Shipping\ShippingFactory;
use CTXFeed\V5\Utility\Settings;

/**
 * Class representing the structure for Bing.
 * Implements the StructureInterface for Bing-related operations.
 */

class BingStructure implements StructureInterface {

	/**
	 * Configuration settings.
	 *
	 * @var mixed
	 */
	private $config;

	/**
	 * Constructor for BingStructure.
	 *
	 * @param mixed $config Configuration settings.
	 */

	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Retrieves grouped attributes for tax and shipping.
	 *
	 * @return array Array of grouped attributes categorized by type.
	 */
	public function get_grouped_attributes() {
		$group['tax']               = [
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship'
		];
		$group['shipping']          = [
			'shipping_country',
			'shipping_region',
			'shipping_service',
			'shipping_price',
		];

		return $group;
	}

	/**
	 * Retrieves the XML structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to XML format.
	 */
	public function get_xml_structure() {
		return $this->get_csv_structure();
	}

	/**
	 * Constructs a CSV structure based on the configuration settings and grouped attributes.
	 *
	 * @return array The constructed CSV data structure.
	 */
	public function get_csv_structure() {

		$group          = $this->get_grouped_attributes();
		$attributes  = $this->config->attributes;
		$mattributes = $this->config->mattributes;
		$static      = $this->config->default;
		$type        = $this->config->type;
		$data        = [];

		$shipping       = false;

		if ( !\in_array( "identifier_exists", $attributes ) ){
			\array_push( $attributes,'identifier_exists' );
			\array_push( $mattributes,'identifier_exists' );
			\array_push( $type,'attribute' );
		}

		foreach ( $mattributes as $key => $attribute ) {
			$attribute_value               = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];

			if ( \in_array( $attribute, $group['shipping'], true ) ) {
				$shipping = true;
			} elseif ( $attribute === 'shipping' ) {
				$shipping = true;
			}
            else {
	            $replaced_attribute            = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
	            $data[][ $replaced_attribute ] = $attribute_value;
            }

		}

		if ( \array_key_exists( 'shipping', $data ) && ! empty( $data['shipping'] ) ) {
			$attr            = 'shipping(' . \implode( ':', \array_keys( $data['shipping'] ) ) . ')';
			$data[][ $attr ] = \implode( ':', \array_values( $data['shipping'] ) );
			unset( $data['shipping'] );
		}

		if ( $shipping ) {
			$data = $this->get_shipping( $data );
		}

		return $data;
	}

	/**
	 * Retrieves shipping data based on specified criteria and settings.
	 *
	 * @param array $data The data to which shipping details will be appended.
	 * @return array Modified data array with shipping details.
	 */
	private function get_shipping( $data ){
		$methods                = ( ShippingFactory::get( [], $this->config ) )->get_shipping_info();
		$allow_all_shipping     = Settings::get( 'allow_all_shipping' );
		$local_pickup_shipping  = Settings::get('only_local_pickup_shipping');
		$country                = $this->config->get_shipping_country();
		$feed_country           = $this->config->get_feed_country();


		if ( ! empty( $methods ) ) {

			foreach ( $methods as $k=>$shipping ) {
				if ('local_pickup' == $shipping['method_id'] && $local_pickup_shipping=='yes') {
					unset($methods[$k]);
				}

				if($country!=""){
					if($country=='feed'){
						$allow_all_shipping='no';
					}
					if($country=='all'){
						$allow_all_shipping='yes';
					}
				}

				if ($feed_country !== $shipping['country'] && $allow_all_shipping=='no') {
					unset($methods[$k]);
				}
			}

			$i_max = \count( $methods );
			$group['shipping'] = array( "country", "region", "service", "price");
			for ( $i = 0; $i < $i_max; $i ++ ) {
				$data[][ 'shipping(' . \implode( ':', $group['shipping'] ) . ')' ]  = "csv_shipping_" . $i ;
			}
		}

		return $data;
	}

	/**
	 * Retrieves the TSV structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to TSV format.
	 */
	public function get_tsv_structure() {
		return $this->get_csv_structure();
	}

	/**
	 * Retrieves the TXT structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to TXT format.
	 */
	public function get_txt_structure() {
		return $this->get_csv_structure();
	}

	/**
	 * Retrieves the XLS structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to XLS format.
	 */
	public function get_xls_structure() {
		return $this->get_csv_structure();
	}

	/**
	 * Retrieves the JSON structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to JSON format.
	 */
	public function get_json_structure() {
		return $this->get_csv_structure();
	}
}
