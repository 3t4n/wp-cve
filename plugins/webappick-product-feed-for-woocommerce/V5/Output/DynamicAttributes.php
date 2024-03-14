<?php

namespace CTXFeed\V5\Output;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Utility\Config;
use WC_Product;

/**
 * Class DynamicAttributes
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Output
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class DynamicAttributes {


	/**
	 * Get the value of a dynamic attribute
	 *
	 * @param            $attribute
	 * @param            $merchant_attribute
	 * @param WC_Product $product
	 * @param Config     $config
	 *
	 * @return string
	 */
	public static function getDynamicAttributeValue( $attribute, $merchant_attribute, $product, $config ) {

		//$get_attribute_value_by_type = new AttributeValueByType( $attribute, $merchant_attribute, $product, $config );
		$getValue         = maybe_unserialize( get_option( $attribute ) );
		$wfDAttributeCode = isset( $getValue['wfDAttributeCode'] ) ? $getValue['wfDAttributeCode'] : '';
		$attribute        = isset( $getValue['attribute'] ) ? (array) $getValue['attribute'] : array();
		$condition        = isset( $getValue['condition'] ) ? (array) $getValue['condition'] : array();
		$compare          = isset( $getValue['compare'] ) ? (array) $getValue['compare'] : array();
		$type             = isset( $getValue['type'] ) ? (array) $getValue['type'] : array();

		$logical_condition = isset( $getValue['logical_condition'] ) ? (array) $getValue['logical_condition'] : array();

		$prefix = isset( $getValue['prefix'] ) ? (array) $getValue['prefix'] : array();
		$suffix = isset( $getValue['suffix'] ) ? (array) $getValue['suffix'] : array();

		$value_attribute = isset( $getValue['value_attribute'] ) ? (array) $getValue['value_attribute'] : array();
		$value_pattern   = isset( $getValue['value_pattern'] ) ? (array) $getValue['value_pattern'] : array();

		$default_type            = isset( $getValue['default_type'] ) ? $getValue['default_type'] : 'attribute';
		$default_value_attribute = isset( $getValue['default_value_attribute'] ) ? $getValue['default_value_attribute'] : '';
		$default_value_pattern   = isset( $getValue['default_value_pattern'] ) ? $getValue['default_value_pattern'] : '';

		$result       = '';
		$result_array = array();

		// Check If Attribute Code exist
		if ( $wfDAttributeCode && count( $attribute ) ) {
			foreach ( $attribute as $key => $name ) {
				if ( ! empty( $name ) ) {

					if ( ! empty( $logical_condition ) || in_array( '&&', $logical_condition ) ) {
						$result = '';
					}

					$conditionName = ( new AttributeValueByType( $attribute, $product, $config ) )->get_value( $name );

					if ( 'weight' === $name ) {
						$unit = ' ' . get_option( 'woocommerce_weight_unit' );
						if ( ! empty( $unit ) ) {
							$conditionName = (float) str_replace( $unit, '', $conditionName );
						}
					}

					$conditionCompare  = $compare[ $key ];
					$conditionOperator = $condition[ $key ];

					if ( ! empty( $conditionCompare ) ) {
						$conditionCompare = trim( $conditionCompare );
					}
					$conditionValue = '';
					if ( 'pattern' === $type[ $key ] ) {
						$conditionValue = $value_pattern[ $key ];
					} elseif ( 'attribute' === $type[ $key ] ) {
						$conditionValue = ( new AttributeValueByType( $attribute, $product, $config ) )->get_value( $value_attribute[ $key ] );
					} elseif ( 'remove' === $type[ $key ] ) {
						$conditionValue = '';
					}

					switch ( $conditionOperator ) {
						case '==':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName == $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '!=':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName != $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '>=':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName >= $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}

							break;
						case '<=':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName <= $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '>':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName > $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '<':
							if ( ProductHelper::validate_date( $conditionName ) && ProductHelper::validate_date( $conditionCompare ) ) {
								$conditionName    = strtotime( $conditionName );
								$conditionCompare = strtotime( $conditionCompare );
							}
							if ( $conditionName < $conditionCompare ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'contains':
							if ( false !== stripos( $conditionName, $conditionCompare ) ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'nContains':
							if ( stripos( $conditionName, $conditionCompare ) === false ) {
								$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'between':
							$compare_items = explode( ',', $conditionCompare );

							if ( isset( $compare_items[1] ) && is_numeric( $compare_items[0] ) && is_numeric( $compare_items[1] ) ) {
								if ( $conditionName >= $compare_items[0] && $conditionName <= $compare_items[1] ) {
									$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
									if ( '' !== $result ) {
										$result = $prefix[ $key ] . $result . $suffix[ $key ];
									}
								}
							} elseif ( isset( $compare_items[1] ) && ProductHelper::validate_date( $compare_items[0] ) && ProductHelper::validate_date( $compare_items[1] ) ) {
								if ( $conditionName >= $compare_items[0] && $conditionName <= $compare_items[1] ) {
									$result = ProductHelper::price_format( $name, $conditionName, $conditionValue );
									if ( '' != $result ) {
										$result = $prefix[ $key ] . $result . $suffix[ $key ];
									}
								}
							} else {
								$result = '';
							}
							break;
						default:
							break;
					}
				}
				$result_array[ $key ] = array(
					'conditionName'     => $conditionName,
					'result'            => $result,
					'condition'         => (isset($logical_condition[ $key ])) ? $logical_condition[ $key ] : '',
					'name'              => $name,
					'conditionOperator' => $conditionOperator,
					'conditionCompare'  => $conditionCompare,
				);

			}
		}

		if ( empty( $logical_condition ) || ! in_array( '&&', $logical_condition ) ) {
			$result = $result;

		} else {
			$new_logical_condition = $logical_condition;
			array_shift( $new_logical_condition );
			if ( ! in_array( '||', $new_logical_condition ) ) {
				foreach ( $result_array as $key => $value ) {
					if ( $value['result'] === '' ) {
						$result = '';
						break;
					} else {
						$result = $value['result'];
					}
				}
			} else {
				foreach ( $result_array as $key => $value ) {
					if ( $key == 0 ) {
						continue;
					} elseif ( $value['condition'] == '&&' ) {
						if ( $value['result'] !== '' && $result_array[ $key - 1 ]['result'] !== '' ) {
							$result = $value['result'];
						} else {
							$result = '';
						}
					} elseif ( $value['condition'] == '||' ) {
						if ( $value['result'] !== '' ) {
							$result = $value['result'];
						}
					}
				}
			}
		}



		if ( '' === $result ) {
			if ( 'pattern' === $default_type ) {
				$result = $default_value_pattern;
			} elseif ( 'attribute' === $default_type ) {
				if ( ! empty( $default_value_attribute ) ) {
					$result = ( new AttributeValueByType( $attribute, $product, $config ) )->get_value( $default_value_attribute );
				}
			} elseif ( 'remove' === $default_type ) {
				$result = '';
			}
		}
		//return $result;
		return apply_filters( 'woo_feed_after_dynamic_attribute_value', $result, $product, $attribute, $merchant_attribute, $config );

	}


	/**
	 * Save Dynamic Attribute.
	 *
	 * @param array $dynamicAttributes
	 *
	 * @return bool
	 */
	public function saveDynamicAttribute( $dynamicAttributes ) {

		$condition             = '';
		$_data                 = array();
		$wf_attribute_opt_name = '';

		if ( count( $dynamicAttributes ) && isset( $dynamicAttributes['wfDAttributeCode'] ) ) {
			$condition             = sanitize_text_field( $dynamicAttributes['wfDAttributeCode'] );
			$_data                 = woo_feed_sanitize_form_fields( $dynamicAttributes );
			$wf_attribute_opt_name = AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX . $condition;
		}

		if ( false !== get_option( $wf_attribute_opt_name, false ) ) {
			$option = CommonHelper::unique_option_name( $wf_attribute_opt_name );
		} else {
			$option = $wf_attribute_opt_name;
		}


		return update_option( $option, $_data );
	}

	/**
	 * Update Dynamic Attribute.
	 *
	 * @param array $dynamicAttributes
	 *
	 * @return bool
	 */
	public function updateDynamicAttribute( $dynamicAttributes ) {

		$_data  = array();
		$option = '';

		if ( count( $dynamicAttributes ) && isset( $dynamicAttributes['wfDAttributeCode'] ) ) {
			$condition             = sanitize_text_field( $dynamicAttributes['wfDAttributeCode'] );
			$_data                 = woo_feed_sanitize_form_fields( $dynamicAttributes );
			$wf_attribute_opt_name = AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX . $condition;
			$option                = $wf_attribute_opt_name;
		}

		return update_option( $option, $_data );

	}

	/**
	 * Get Dynamic Attribute.
	 *
	 * @param $attribute
	 *
	 * @return false|mixed|null
	 */
	public function getDynamicAttribute( $attribute ) {
		if ( strpos( $attribute, AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX ) === false ) {
			$attribute = AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX . $attribute;
		}

		return get_option( $attribute );
	}

	public function getDynamicAttributes() {

		$dynamic_attributes = CommonHelper::get_options( AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX );
		$data               = array();
		if ( ! empty( $dynamic_attributes ) ) {
			foreach ( $dynamic_attributes as $attributes ) {
				$data[ $attributes->option_name ] = get_option( $attributes->option_name );
			}

			return $data;
		}

		return false;
	}

	/**
	 * Delete Dynamic Attribute.
	 *
	 * @param $attribute
	 *
	 * @return bool
	 */
	public function deleteDynamicAttribute( $attribute ) {
		if ( strpos( $attribute, AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX ) === false ) {
			$attribute = AttributeValueByType::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX . $attribute;
		}

		return delete_option( $attribute );
	}

}
