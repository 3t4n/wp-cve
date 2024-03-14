<?php

namespace CTXFeed\V5\Output;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Utility\Config;
use WC_Product;

/**
 * Class AttributeMapping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Output
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Output
 */
class AttributeMapping {

	/**
	 *  Get Attribute Mapping Value.
	 *
	 * @param            $attribute
	 * @param            $merchant_attribute
	 * @param WC_Product $product
	 * @param Config $config
	 *
	 * @return string
	 */
	public static function getMappingValue( $attribute, $merchant_attribute, $product, $config ) {

		$attributes = get_option( $attribute );
		$glue       = ! empty( $attributes['glue'] ) ? $attributes['glue'] : ' ';
		$output     = '';

		if ( isset( $attributes['mapping'] ) ) {
			foreach ( $attributes['mapping'] as $map ) {
				$get_attribute_value_by_type = new AttributeValueByType( $attribute, $product, $config, $merchant_attribute );
				$get_value               = $get_attribute_value_by_type->get_value( $map );
				if ( ! empty( $get_value ) ) {
					$output .= $glue . $get_value;
				}
			}
		}

		//trim extra glue
		$output = trim( $output, $glue );

		// remove extra whitespace
		$output = preg_replace( '!\s\s+!', ' ', $output );

		return apply_filters( 'woo_feed_filter_attribute_mapping', $output, $attribute, $product, $config );
	}

	/**
	 * Get Attribute Mapping.
	 *
	 * @param $attribute
	 *
	 * @return false|mixed|null
	 */
	public function getMapping( $attribute ) {
		if ( strpos( $attribute, AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) === false ) {
			$attribute = AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $attribute;
		}

		return get_option( $attribute );
	}


	public function getMappings() {
		$mappings = CommonHelper::get_options( AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX );
		$data     = array();
		if ( ! empty( $mappings ) ) {
			foreach ( $mappings as $mapping ) {
				$data[ $mapping->option_name ] = get_option( $mapping->option_name );
			}
			return $data;
		}

		return false;
	}

	/**
	 * Save Attribute mapping.
	 *
	 * @param array $mappingConfig
	 *
	 * @return bool
	 */
	public function saveMapping( $mappingConfig ) {

		$data = array();

		$data['name'] = '';
		if ( isset( $mappingConfig['mapping_name'] ) ) {
			$data['name'] = sanitize_text_field( $mappingConfig['mapping_name'] );
		}

		// Set Multiple Attributes or texts.
		if ( isset( $mappingConfig['value'] ) ) {
			foreach ( $mappingConfig['value'] as $item ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( ' ' === $item ) {
					$data['mapping'][] = $item;
				} elseif ( '' !== $item ) {
					$data['mapping'][] = sanitize_text_field( $item );
				}
			}
			$data['mapping'] = array_filter( $data['mapping'] );
		}

		// Set Glue.
		if ( isset( $mappingConfig['mapping_glue'] ) ) {
			$data['glue'] = $mappingConfig['mapping_glue'];
		} else {
			$data['glue'] = '';
		}

		// Set Option Name.
		if ( isset( $mappingConfig['option_name'] ) &&
		     ! empty( $mappingConfig['option_name'] ) &&
		     false !== strpos( $mappingConfig['option_name'], AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			$option = sanitize_text_field( $mappingConfig['option_name'] );
		} else {
			// generate unique one.
			$option = CommonHelper::unique_option_name( $data['name'] );
		}
		$option = Helper::extract_option_name( $option, AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX );
		$option = AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $option;


		return update_option( $option, $data );
	}

	/**
	 * Save Attribute mapping.
	 *
	 * @param array $mappingConfig
	 *
	 * @return bool
	 */
	public function updateMapping( $mappingConfig ) {

		$data = array();

		$data['name'] = '';
		if ( isset( $mappingConfig['mapping_name'] ) ) {
			$data['name'] = sanitize_text_field( $mappingConfig['mapping_name'] );
		}

		// Set Multiple Attributes or texts.
		if ( isset( $mappingConfig['value'] ) ) {
			foreach ( $mappingConfig['value'] as $item ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( ' ' === $item ) {
					$data['mapping'][] = $item;
				} elseif ( '' !== $item ) {
					$data['mapping'][] = sanitize_text_field( $item );
				}
			}
			$data['mapping'] = array_filter( $data['mapping'] );
		}

		// Set Glue.
		if ( isset( $mappingConfig['mapping_glue'] ) ) {
			$data['glue'] = $mappingConfig['mapping_glue'];
		} else {
			$data['glue'] = '';
		}

		// Set Option Name.
		if ( isset( $mappingConfig['option_name'] ) &&
		     ! empty( $mappingConfig['option_name'] ) &&
		     false !== strpos( $mappingConfig['option_name'], AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			$option = sanitize_text_field( $mappingConfig['option_name'] );
		} else {
			// generate unique one.
			$option = CommonHelper::unique_option_name( AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $data['name'] );
		}

		return update_option( $option, $data );
	}

	/**
	 * Delete Attribute Mapping.
	 *
	 * @param $attribute
	 *
	 * @return bool
	 */
	public function deleteMapping( $attribute ) {
		if ( strpos( $attribute, AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) === false ) {
			$attribute = AttributeValueByType::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . $attribute;
		}

		return delete_option( $attribute );
	}


	/**
	 * Get a random product data for view attribute mapping data
	 *
	 * @param $attribute_mappings
	 *
	 * @return array
	 */
	public static function get_attributes_preview_data( $attribute_mappings ) {

		$preview_data = [];
		if ( count( $attribute_mappings ) ) {
			$config = new Config([]);
			foreach ($attribute_mappings as $attribute_mapping_key => $attribute_mapping) {
				$saperator = $attribute_mapping['glue'] ? $attribute_mapping['glue'] . ' ' : ' ';
				$preview_data[ $attribute_mapping_key ] = trim( 'No preview' . $saperator);
			}
		}

		return $preview_data; // TODO [Attributes Mapping page Loading issue, in future needed to remove this code and fix the issue]
		$products = wc_get_products( [
			'limit'   => 1,
			'orderby' => 'rand',
		] );
		$product  = $products[0];

		$preview_data = [];
		if ( count( $attribute_mappings ) ) {
			$config = new Config( [] );
			foreach ( $attribute_mappings as $attribute_mapping_key => $attribute_mapping ) {
				$saperator = $attribute_mapping['glue'] ? $attribute_mapping['glue'] . ' ' : ' ';
				if ( $attribute_mapping && isset( $attribute_mapping['mapping'] ) && count( $attribute_mapping['mapping'] ) ) {
					$current_preview_data = '';
					foreach ( $attribute_mapping['mapping'] as  $attribute ) {
						//dynamic attribute value
						if ( strpos( $attribute, 'wf_dattribute_' ) !== false ) {
							$dynamic_attribute       = new DynamicAttributes();
							$dynamic_attribute_value = $dynamic_attribute->getDynamicAttributeValue( $attribute, '', $product, $config );
							$value                   = $dynamic_attribute_value ? $dynamic_attribute_value : 'No data';
							$current_preview_data    .= $value . $saperator;
							continue;
						}
						if(!self::exclude_attributes_preview_data($attribute )) {
						$attribute_value      = new AttributeValueByType( $attribute, $product, $config );
							$value                = $attribute_value->get_value() ? $attribute_value->get_value() : 'No data';
							$current_preview_data .= $value . $saperator;
						}else{
							$current_preview_data .= 'No data' . $saperator;
						}
					}

					$preview_data[ $attribute_mapping_key ] = trim( $current_preview_data, $saperator );
				}
			}
		}


		return $preview_data;
	}

	/**
	 * Exclude preview data
	 *
	 * @param $attribute
	 *
	 * @return boolean
	 */
	public static function exclude_attributes_preview_data( $attribute ) {
		$attribute_array = array( 'shipping', 'tax' );
		return in_array( $attribute, $attribute_array );
	}
}
