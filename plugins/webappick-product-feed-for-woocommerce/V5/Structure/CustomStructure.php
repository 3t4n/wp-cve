<?php
/**
 * Class CustomStructure
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */
namespace CTXFeed\V5\Structure;

use CTXFeed\V5\Merchant\MerchantAttributeReplaceFactory;

/**
 * Class representing the structure for Custom2.
 * Implements the StructureInterface for Custom-related operations.
 */

class CustomStructure implements StructureInterface,StructureXLSXInterface {

	/**
	 * Configuration settings.
	 *
	 * @var \Config $config
	 */
	private $config;

	/**
	 * Constructor for Custom2Structure.
	 *
	 * @param mixed $config Configuration settings.
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Retrieves the XML structure.
	 *
	 * @return array The constructed XML data structure.
	 */
	public function get_xml_structure() {
		$attributes  = $this->config->attributes;
		$mattributes = $this->config->mattributes;
		$static      = $this->config->default;
		$type        = $this->config->type;

		$wrapper     = \str_replace( " ", "_", $this->config->itemWrapper );;
		$wrapper     = apply_filters('woo_feed_product_item_wrapper', $wrapper, $this->config );

		$data = [];
		foreach ( $mattributes as $key => $attribute ) {

			$attribute_value                           = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			$replaced_attribute                        = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
			$replaced_attribute                        = \str_replace( " ", "_", $replaced_attribute );
			$data[ $wrapper ][][ $replaced_attribute ] = $attribute_value;
		}

		return $data;
	}

	public function get_csv_structure() {
		$attributes  = $this->config->attributes;
		$mattributes = $this->config->mattributes;
		$static      = $this->config->default;
		$type        = $this->config->type;
		$data        = [];
		foreach ( $mattributes as $key => $attribute ) {
			$attribute_value               = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			$replaced_attribute            = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
			$data[][ $replaced_attribute ] = $attribute_value;
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
	 * Retrieves the XLSX structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to XLSX format.
	 */
	public function get_xlsx_structure() {
		return $this->get_csv_structure();
	}

	/**
	 * Retrieves the JSON structure.
	 * Currently, this method serves as a wrapper for the get_csv_structure method.
	 *
	 * @return mixed The CSV structure converted to JSON format.
	 */
	public function get_json_structure() {
		$attributes  = $this->config->attributes;
		$mattributes = $this->config->mattributes;
		$static      = $this->config->default;
		$type        = $this->config->type;
		$data        = [];
		foreach ( $mattributes as $key => $attribute ) {
			$attribute_value             = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			$replaced_attribute          = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
			$data[ $replaced_attribute ] = $attribute_value;
		}

		return $data;
	}
}
