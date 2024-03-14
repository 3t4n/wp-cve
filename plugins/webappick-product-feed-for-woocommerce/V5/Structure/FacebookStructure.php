<?php

/**
 * Class FacebookStructure
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */

namespace CTXFeed\V5\Structure;
use CTXFeed\V5\Merchant\MerchantAttributeReplaceFactory;

/**
 * Class representing the structure for Facebook.
 * Implements the StructureInterface for Facebook-related operations.
 */

class FacebookStructure implements StructureInterface {

	/**
	 * Configuration settings.
	 *
	 * @var \Config $config
	 */
	private $config;

	/**
	 * Constructor for FacebookStructure.
	 *
	 * @param mixed $config Configuration settings.
	 */
	public function __construct( $config ) {
		$this->config = $config;
		$this->config->itemWrapper  = 'item';
		$this->config->itemsWrapper = 'items';
	}

	/**
	 * Retrieves grouped attributes for tax and shipping.
	 *
	 * @return array Array of grouped attributes categorized by type.
	 */
	public function get_grouped_attributes() {
		$group['additional_variant']       = [
			'additional_variant_label',
			'additional_variant_value'
		];

		$group['tax']               = [
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship'
		];
		$group['shipping']          = [
			'location_id',
			'location_group_name',
			'min_handling_time',
			'max_handling_time',
			'min_transit_time',
			'max_transit_time'
		];

		return $group;
	}

	/**
	 * Retrieves the XML structure.
	 *
	 * @return array The constructed XML data structure.
	 */
	public function get_xml_structure() {
		$additional_variant     = [];
		$group                  = $this->get_grouped_attributes();
		$attributes             = $this->config->attributes;
		$mattributes            = $this->config->mattributes;
		$static                 = $this->config->default;
		$type                   = $this->config->type;
		$wrapper                = $this->config->itemWrapper;
		$data                   = [];

		if ( !\in_array( "identifier_exists", $attributes ) ){
			\array_push( $attributes,'identifier_exists' );
			\array_push( $mattributes,'identifier_exists' );
			\array_push( $type,'attribute' );
		}

		foreach ( $mattributes as $key => $attribute ) {
			$attribute_value   = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			$additional_variant_sub  = \str_replace( "additional_variant_", "", $attribute );
			$replaced_attribute = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
			// Installment Attribute
			if ( \in_array( $attribute, $group['additional_variant'], true ) && \count( $additional_variant ) < 1 ) {
				$additional_variant[ $additional_variant_sub ] = $attribute_value;
			}elseif ( \in_array( $attribute, $group['additional_variant'], true ) ) {
				$additional_variant[ $additional_variant_sub ] = $attribute_value;
				$data[ $wrapper ][]['additional_variant_attribute'] = $additional_variant;
				$additional_variant                     = [];
			}elseif ( \strpos( $attribute, 'images_' ) !== false ) {
				$data[ $wrapper ][][ $replaced_attribute ] = $attribute_value;
			}else {
				$data[ $wrapper ][ $replaced_attribute ] = $attribute_value;
			}
		}

		return $data;
	}

	/**
	 * Constructs a CSV structure based on the configuration settings and grouped attributes.
	 *
	 * @return array The constructed CSV data structure.
	 */
	public function get_csv_structure() {
		$group          = $this->get_grouped_attributes();
		$attributes     = $this->config->attributes;
		$mattributes    = $this->config->mattributes;
		$static         = $this->config->default;
		$type           = $this->config->type;
		$data           = [];

		if ( !\in_array( "identifier_exists", $attributes ) ){
			\array_push( $attributes,'identifier_exists' );
			\array_push( $mattributes,'identifier_exists' );
			\array_push( $type,'attribute' );
		}

		foreach ( $mattributes as $key => $attribute ) {
			$additional_variant_sub  = \str_replace( "additional_variant_", "", $attribute );
			$attribute_value   = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];

			if ( \in_array( $attribute, $group['additional_variant'], true ) && \count( $additional_variant ) < 1 ) {
				$additional_variant[ $additional_variant_sub ] = $attribute_value;
			}elseif ( \in_array( $attribute, $group['additional_variant'], true ) ) {
				$additional_variant[ $additional_variant_sub ] = $attribute_value;
				$data[ 'additional_variant_attribute' ][] = $additional_variant;
				$additional_variant                     = [];
			} elseif ( \strpos( $attribute, 'images_' ) !== false ) {
				$replaced_attribute = MerchantAttributeReplaceFactory::replace_attribute( 'additional_image_link', $this->config );
				$data[][$replaced_attribute] = $attribute_value;
			}  else {
				$replaced_attribute = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
				$data[][ $replaced_attribute ] = $attribute_value;
			}
		}

		if ( \array_key_exists( 'shipping', $data ) && ! empty( $data['shipping'] ) ) {
			$attr            = 'shipping(' . \implode( ':', \array_keys( $data['shipping'] ) ) . ')';
			$data[][ $attr ] = \implode( ':', \array_values( $data['shipping'] ) );
			unset( $data['shipping'] );
		}

		if ( \array_key_exists( 'additional_variant_attribute', $data ) && ! empty( $data['additional_variant_attribute'] ) ) {
			foreach ( $data['additional_variant_attribute'] as $detail ) {
				$additional_variant[] = \implode( ':', \array_values( $detail ) );
			}
			$data[]['additional_variant_attribute'] = \implode( ',', \array_values( $additional_variant ) );
			unset( $data['additional_variant_attribute'] );
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

