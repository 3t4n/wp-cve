<?php
/**
 * @package CTXFeed\V5\Helper
 */

namespace CTXFeed\V5\Helper;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Query\QueryFactory;
use CTXFeed\V5\Utility\Config;
use DateTime;
use Exception;
use TRP_Settings;
use TRP_Translation_Render;
use WC_Product;
use WC_Product_Composite;
use WC_Product_External;
use WC_Product_Grouped;
use WC_Product_Variable;
use WC_Product_Variation;
use WC_Product_Variation_Data_Store_CPT;
use WP_Term;

/**
 * This class contains product feed method
 */
class ProductHelper {

	/**
	 * Advance Custom Field (ACF) Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_ACF_FIELDS = 'acf_fields_';

	/**
	 * Post meta prefix for dropdown item
	 *
	 * @since 3.1.18
	 */
	const POST_META_PREFIX = 'wf_cattr_';

	/**
	 * Product Attribute (taxonomy & local) Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_ATTRIBUTE_PREFIX = 'wf_attr_';

	/**
	 * Product Taxonomy Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_TAXONOMY_PREFIX = 'wf_taxo_';

	/**
	 * Product Category Mapping Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_CATEGORY_MAPPING_PREFIX = 'wf_cmapping_';

	/**
	 * Product Dynamic Attribute Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX = 'wf_dattribute_';

	/**
	 * WordPress Option Prefix
	 *
	 * @since 3.1.18
	 */
	const WP_OPTION_PREFIX = 'wf_option_';

	/**
	 * Extra Attribute Prefix
	 *
	 * @since 3.2.20
	 */
	const PRODUCT_EXTRA_ATTRIBUTE_PREFIX = 'wf_extra_';

	/**
	 * Product Attribute Mappings Prefix
	 *
	 * @since 3.3.2*
	 */
	const PRODUCT_ATTRIBUTE_MAPPING_PREFIX = 'wp_attr_mapping_';

	/**
	 * Product Custom Field Prefix
	 *
	 * @since 3.1.18
	 */
	const PRODUCT_CUSTOM_IDENTIFIER = 'woo_feed_';

	/**
	 * Retrieves IDs based on feed configuration and query arguments.
	 *
	 * @param Config $config   Feed Configuration.
	 * @param array  $args     Query Arguments.
	 * @param mixed  $settings Additional settings, if any. Default is null.
	 *
	 * @return array        IDs retrieved based on the given configuration and arguments.
	 * @throws Exception   If any exceptions are thrown by QueryFactory::get_ids.
	 */
	public static function get_ids( Config $config, array $args = [], $settings = null ) {
		if ( $config === null ) {
			return array();
		}

		return QueryFactory::get_ids( $config, $args, $settings );
	}

	/**
	 * Retrieves the product gallery items (URLs) array for a given product.
	 * This can contain empty array values.
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product The product object.
	 *
	 * @return array<string> An array of attachment IDs.
	 * @since 3.2.6
	 */

	public static function get_product_gallery( $product ) {
		$img_urls       = [];
		$attachment_ids = [];

		if ( $product->is_type( 'variation' ) ) {
			if ( class_exists( 'Woo_Variation_Gallery' ) ) {
				/**
				 * Get Variation Additional Images for "Additional Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Additional Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-variation-gallery/
				 */
				$attachment_ids = \get_post_meta( $product->get_id(), 'woo_variation_gallery_images', true );
			} elseif ( \class_exists( 'WooProductVariationGallery' ) ) {
				/**
				 * Get Variation Additional Images for "Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-product-variation-gallery/
				 */
				$attachment_ids = \get_post_meta( $product->get_id(), 'rtwpvg_images', true );
			} elseif ( \class_exists( 'WC_Additional_Variation_Images' ) ) {
				/**
				 * Get Variation Additional Images for "WooCommerce Additional Variation Images"
				 *
				 * @plugin WooCommerce Additional Variation Images
				 * @link   https://woocommerce.com/products/woocommerce-additional-variation-images/
				 */
				$attachment_ids = \explode( ',', \get_post_meta( $product->get_id(), '_wc_additional_variation_images', true ) );
			} elseif ( \class_exists( 'WOODMART_Theme' ) ) {
				/**
				 * Get Variation Additional Images for "WOODMART Theme -> Variation Gallery Images Feature"
				 *
				 * @theme WOODMART
				 * @link  https://themeforest.net/item/woodmart-woocommerce-wordpress-theme/20264492
				 */
				$var_id    = $product->get_id();
				$parent_id = $product->get_parent_id();

				$variation_obj = \get_post_meta( $parent_id, 'woodmart_variation_gallery_data', true );
				if ( isset( $variation_obj, $variation_obj[ $var_id ] ) ) {
					$attachment_ids = \explode( ',', $variation_obj[ $var_id ] );
				} else {
					$attachment_ids = \explode( ',', \get_post_meta( $var_id, 'wd_additional_variation_images_data', true ) );
				}
			} else {
				/**
				 * If any Variation Gallery Image plugin not installed then get Variable Product Additional Image Ids .
				 */
				$attachment_ids = \wc_get_product( $product->get_parent_id() )->get_gallery_image_ids();
			}
		}

		/**
		 * Get Variable Product Gallery Image ids if Product is not a variation
		 * or variation does not have any gallery images
		 *
		 * Test case write is pending
		 */
		if ( empty( $attachment_ids ) ) {
			$attachment_ids = $product->get_gallery_image_ids();
		}

		if ( $attachment_ids && \is_array( $attachment_ids ) ) {
			$m_key = 1;
			foreach ( $attachment_ids as $attachment_id ) {
				$img_urls[ $m_key ] = Helper::woo_feed_get_formatted_url( \wp_get_attachment_url( $attachment_id ) );
				$m_key ++;
			}
		}

		return $img_urls;
	}

	/**
	 * Determines if a sufficient number of identifier attributes exist for a product.
	 *
	 * @param mixed      $attribute Ignored parameter, remains for backward compatibility.
	 * @param WC_Product $product   The WooCommerce product object.
	 * @param mixed      $config    Configuration or context.
	 *
	 * @return string 'yes' if at least two identifiers are present, 'no' otherwise.
	 */
	public static function overwrite_identifier_exists( $attribute, $product, $config ) {
		$counter = 0;
		$counter += self::count_identifiers_in_attributes( $product, $config );
		$counter += self::count_identifiers_in_mattributes( $product, $config );

		return $counter >= 2 ? 'yes' : 'no';
	}

	private static function count_identifiers_in_attributes( $product, $config ) {
		$count       = 0;
		$feed_rules  = $config->get_feed_rules()['option_value']['feedrules'];
		$identifiers = [
			self::PRODUCT_CUSTOM_IDENTIFIER . 'identifier_gtin',
			self::PRODUCT_TAXONOMY_PREFIX . 'woo-feed-brand',
			self::PRODUCT_CUSTOM_IDENTIFIER . 'identifier_mpn',
		];

		foreach ( \array_intersect( $feed_rules['attributes'], $identifiers ) as $key => $result ) {
			if ( $feed_rules['type'][ $key ] === 'attribute' && self::get_custom_field( $result, $product, $config ) !== '' ) {
				$count ++;
			}
		}

		return $count;
	}

	/**
	 * Retrieves custom field values for a WooCommerce product.
	 *
	 * @param string     $field   The custom field key.
	 * @param WC_Product $product The WooCommerce product object.
	 * @param mixed      $config  Additional configuration or context.
	 *
	 * @return mixed The formatted value of the custom field.
	 */
	public static function get_custom_field( $field, $product, $config ) {
		// Adjust the meta key for variation products.
        $field = str_replace(AttributeValueByType::POST_META_PREFIX, "", $field );
        if ( strpos( $field, '_var') !== false ) {
            $meta_key = $product->is_type( 'variation' ) ? $field : str_replace("_var", "",$field );
        }else{
            $meta_key = $product->is_type( 'variation' ) ? $field . '_var' : $field;
        }

		// Allow filtering of the meta key.
		$meta_key = apply_filters( 'woo_feed_custom_field_meta', $meta_key, $product, $config );

		// Determine the new and old meta keys based on the presence of '_identifier'.
		if ( \strpos( $meta_key, '_identifier' ) !== false ) {
			$new_meta_key = \str_replace( '_identifier', '', $meta_key );
			$old_meta_key = $meta_key;
		} else {
			$new_meta_key = $meta_key;
			$old_meta_key = \str_replace( 'woo_feed_', 'woo_feed_identifier_', $meta_key );
		}

		// Retrieve the values for the new and old meta keys.
		$new_meta_value = self::get_product_meta( $new_meta_key, $product, $config );
		$old_meta_value = self::get_product_meta( $old_meta_key, $product, $config );

		// Return the formatted custom field value, preferring the new meta key.
		return empty( $new_meta_value ) ? self::format_custom_field_value( $old_meta_value, $meta_key )
			: self::format_custom_field_value( $new_meta_value, $meta_key );
	}

	/**
	 * Retrieves a specific meta value for a WooCommerce product. Supports handling variations and RankMath integration.
	 *
	 * @param string     $meta    The meta key to retrieve.
	 * @param WC_Product $product The WooCommerce product object.
	 * @param mixed      $config  Additional configuration or context.
	 *
	 * @return mixed The value of the specified meta key. Filters the value through 'woo_feed_filter_product_meta'.
	 */
	public static function get_product_meta( $meta, $product, $config ) {
		$product_id = $product->get_id();
		$value      = \get_post_meta( $product_id, $meta, true );

		// Attempt to retrieve the meta value from the parent product if it's a variation and the meta value is empty.
		if ( empty( $value ) && $product->is_type( 'variation' ) ) {
			$parent_id = $product->get_parent_id();
			$value     = \get_post_meta( $parent_id, $meta, true );
		}

		// Handling for RankMath integration.
		if ( self::is_rank_math_active() && $meta === 'rank_math_primary_product_cat' && \is_numeric( $value ) ) {
			$term  = \get_term( $value );
			$value = $term instanceof WP_Term ? $term->name : $value;
		}

		// Handle taxonomy-related meta keys.
		if ( \strpos( $meta, self::PRODUCT_TAXONOMY_PREFIX ) !== false ) {
			$meta_key = \str_replace( self::PRODUCT_TAXONOMY_PREFIX, '', $meta );
			$value    = self::get_product_taxonomy( $meta_key, $product, $config );
		}

		return apply_filters( 'woo_feed_filter_product_meta', $value, $product, $config );
	}

	/**
	 * Checks if Rank Math SEO plugin is active.
	 *
	 * @return bool True if Rank Math or Rank Math Pro is active, false otherwise.
	 */
	private static function is_rank_math_active() {
		return \class_exists( 'RankMath' ) || \class_exists( 'RankMathPro' );
	}

	/**
	 * Retrieves the taxonomy terms associated with a product.
	 *
	 * @param string     $taxonomy The taxonomy for which to retrieve terms.
	 * @param WC_Product $product  The WooCommerce product object.
	 * @param Config     $config   Additional configuration or context.
	 *
	 * @return string A string containing the taxonomy terms separated by the specified separator.
	 *
	 * Note: Test case writing is pending for this function.
	 */
	public static function get_product_taxonomy( $taxonomy, $product, $config ) {
		$id        = CommonHelper::parent_product_id( $product );
		$separator = apply_filters( 'woo_feed_product_taxonomy_term_list_separator', ',', $config, $product );
		$term_list = \get_the_term_list( $id, $taxonomy, '', $separator, '' );

		if ( \is_object( $term_list ) && \get_class( $term_list ) === 'WP_Error' ) {
			$term_list = '';
		}

		$getTaxonomy = CommonHelper::strip_all_tags( $term_list );

		return apply_filters( 'woo_feed_filter_product_taxonomy', $getTaxonomy, $product, $config );
	}

	/**
	 * Formats the value of a custom field based on its metadata key.
	 *
	 * @param mixed  $value    The value of the custom field.
	 * @param string $meta_key The metadata key used to determine the formatting.
	 *
	 * @return mixed Formatted value if specific formatting is required, otherwise returns the original value.
	 */
	private static function format_custom_field_value( $value, $meta_key ) {
		if ( \strpos( $meta_key, 'availability_date' ) !== false ) {
			$formatted_date = \strtotime( $value );

			if ( $formatted_date === false ) {
				// Handle invalid date
				return $value;
			}

			return \date( 'c', $formatted_date );
		}

		return $value;
	}

	private static function count_identifiers_in_mattributes( $product, $config ) {
		$count           = 0;
		$feed_rules      = $config->get_feed_rules()['option_value']['feedrules'];
		$identifier_keys = [ 'brand', 'upc', 'sku', 'mpn', 'gtin' ];

		foreach ( \array_intersect( $feed_rules['mattributes'], $identifier_keys ) as $key => $result ) {
			$count += self::evaluate_mattribute( $feed_rules, $key, $product, $config );
		}

		return $count;
	}

	private static function evaluate_mattribute( $feed_rules, $key, $product, $config ) {
		$attribute_key = $feed_rules['attributes'][ $key ];

		if ( $feed_rules['type'][ $key ] === 'pattern' && $feed_rules['default'][ $key ] !== '' ) {
			return 1;
		}

		if ( $feed_rules['type'][ $key ] === 'attribute' ) {
			if ( $attribute_key === 'sku' && $product->get_sku() !== '' ) {
				return 1;
			}

			if ( $attribute_key !== '' && \strpos( $attribute_key, self::PRODUCT_ATTRIBUTE_PREFIX ) !== false ) {
				$attribute = \str_replace( self::PRODUCT_ATTRIBUTE_PREFIX, '', $attribute_key );

				return self::get_product_attribute( $attribute, $product, $config ) !== '' ? 1 : 0;
			}

			if ( $attribute_key !== '' && \strpos( $attribute_key, self::PRODUCT_TAXONOMY_PREFIX ) !== false ) {
				return self::get_product_meta( $attribute_key, $product, $config ) !== '' ? 1 : 0;
			}
		}

		return 0;
	}

	/**
	 * Retrieves a specified attribute from a WooCommerce product.
	 *
	 * @param string     $attr    The attribute slug to retrieve.
	 * @param WC_Product $product The WooCommerce product object.
	 * @param Config     $config  Additional configuration or context.
	 *
	 * @return string The value of the specified attribute.
	 * @since 2.2.3
	 */
	public static function get_product_attribute( $attr, $product, Config $config ) {
		// Normalize attribute slug for WooCommerce versions 3.6 and above.
		if ( \woo_feed_wc_version_check( 3.6 ) ) {
			$attr = \str_replace( 'pa_', '', $attr );
		}

		$value = self::fetch_product_attribute( $attr, $product );

		// Retrieve attribute value from the parent product if it's a variation and the attribute value is empty.
		if ( '' === $value && $product->is_type( 'variation' ) ) {
			$parent_product = \wc_get_product( $product->get_parent_id() );
			if ( $parent_product ) {
				$value = self::fetch_product_attribute( $attr, $parent_product );
			}
		}

		return apply_filters( 'woo_feed_filter_product_attribute', $value, $attr, $product, $config );
	}

	/**
	 * Fetches the attribute value from a product.
	 *
	 * @param string     $attr    The attribute slug.
	 * @param WC_Product $product The WooCommerce product object.
	 *
	 * @return string The attribute value.
	 */
	private static function fetch_product_attribute( $attr, WC_Product $product ) {
		if ( \woo_feed_wc_version_check( 3.2 ) ) {
			return $product->get_attribute( $attr );
		}

		// Fallback for WooCommerce versions below 3.2.
		return \implode( ',', \wc_get_product_terms( $product->get_id(), $attr, array( 'fields' => 'names' ) ) );
	}

	/**
	 * Retrieves the value of an Advanced Custom Fields (ACF) field for a given WooCommerce product.
	 *
	 * @param WC_Product $product   The WooCommerce product object.
	 * @param string     $field_key The ACF field key, with the prefix "acf_fields_".
	 *
	 * @return mixed|string The value of the ACF field, or an empty string if ACF is not available.
	 *
	 * Note: Test case writing is pending for this function.
	 */
	public static function get_acf_field( $product, $field_key ) {
		// Remove the prefix to get the actual ACF field key.
		$acf_field_key = \str_replace( 'acf_fields_', '', $field_key );

		// Check if ACF is installed and active.
		if ( \class_exists( 'ACF' ) ) {
			// Retrieve and return the ACF field value.
			return \get_field( $acf_field_key, $product->get_id() );
		}

		// Return an empty string if ACF is not available.
		return '';
	}

	/**
	 * Returns category mapping values by product ID, considering the parent product for variations.
	 *
	 * @param string $mapping_name Category Mapping Name
	 * @param int    $product_id   Product ID / Parent Product ID for variation product
	 *
	 * @return mixed
	 *
	 * This function already exists in CTXFeed\V5\Output\CategoryMapping.
	 * Test case is available in CTXFeed\tests\wpunit\Output\CategoryMappingTest.
	 */
	public static function get_category_mapping( $mapping_name, $product_id ) {
		$mapping_settings = \maybe_unserialize( \get_option( $mapping_name ) );

		if ( ! isset( $mapping_settings['cmapping'], $mapping_settings['gcl-cmapping'] ) ) {
			return '';
		}

		// Define suggestive category list merchants.
		$suggestive_category_list_merchants = [
			'google',
			'facebook',
			'pinterest',
			'bing',
			'bing_local_inventory',
			'snapchat'
		];

		// Determine the appropriate mapping array.
		$cmapping = self::determine_mapping_array( $mapping_settings, $suggestive_category_list_merchants );

		// Retrieve product categories and process them.
		$categories = \get_the_terms( $product_id, 'product_cat' );
		if ( \is_array( $categories ) ) {
			foreach ( \array_reverse( $categories ) as $category ) {
				if ( ! empty( $cmapping[ $category->term_id ] ) ) {
					return $cmapping[ $category->term_id ];
				}
			}
		}

		return '';
	}

	/**
	 * Determines the appropriate category mapping array.
	 *
	 * @param array $mapping_settings Configuration settings for mapping.
	 * @param array $merchants        List of suggestive category list merchants.
	 *
	 * @return array The determined mapping array.
	 */
	private static function determine_mapping_array( $mapping_settings, $merchants ) {
		if ( isset( $mapping_settings['gcl-cmapping'] ) && \in_array( $mapping_settings['mappingprovider'], $merchants, true ) ) {
			return \is_array( $mapping_settings['gcl-cmapping'] ) ? \array_reverse( $mapping_settings['gcl-cmapping'], true ) : $mapping_settings['gcl-cmapping'];
		}

		return \is_array( $mapping_settings['cmapping'] ) ? \array_reverse( $mapping_settings['cmapping'], true ) : $mapping_settings['cmapping'];
	}

	/**
	 * Retrieves the mapped value for a specified attribute, considering the merchant attribute and configuration.
	 *
	 * @param WC_Product $product            The product object.
	 * @param string     $attribute          The attribute to map.
	 * @param string     $merchant_attribute The merchant attribute.
	 * @param mixed      $config             Additional configuration or context.
	 *
	 * @return string The concatenated attribute values, separated by the defined glue or a space.
	 *
	 * This function already exists in CTXFeed\V5\Output\AttributesMapping.
	 * Test case is available in CTXFeed\tests\wpunit\Output\AttributesMappingTest.
	 */
	public static function get_attribute_mapping( $product, $attribute, $merchant_attribute, $config ) {
		$attributes = \get_option( $attribute );
		$glue       = ! empty( $attributes['glue'] ) ? $attributes['glue'] : ' ';

		if ( ! isset( $attributes['mapping'] ) || ! \is_array( $attributes['mapping'] ) ) {
			return '';
		}

		$get_attribute_value_by_type = new AttributeValueByType( $attribute, $merchant_attribute, $product, $config );
		$output                      = self::build_attribute_output( $attributes['mapping'], $get_attribute_value_by_type, $glue );

		// remove extra whitespace
		$output = \preg_replace( '!\s\s+!', ' ', $output );

		return apply_filters( 'woo_feed_filter_attribute_mapping', $output, $attribute, $product, $config );
	}

	/**
	 * Builds the concatenated output for the attribute mapping.
	 *
	 * @param array                $mapping                     The attribute mapping array.
	 * @param AttributeValueByType $get_attribute_value_by_type The object to retrieve attribute values.
	 * @param string               $glue                        The glue used for concatenation.
	 *
	 * @return string The concatenated attribute values.
	 */
	private static function build_attribute_output( $mapping, $get_attribute_value_by_type, $glue ) {
		$output = [];

		foreach ( $mapping as $map ) {
			$value = $get_attribute_value_by_type->get_value( $map );
			if ( ! empty( $value ) ) {
				$output[] = $value;
			}
		}

		return implode( $glue, $output );
	}

	/**
	 * Retrieves the value of a dynamic attribute for a product.
	 *
	 * @param WC_Product $product
	 * @param string     $attribute_name
	 * @param string     $merchant_attribute
	 * @param mixed      $config
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 * This function already exists in CTXFeed\V5\Output\DynamicAttributes.
	 * Test case is available in CTXFeed\tests\wpunit\Output\DynamicAttributesTest.
	 */
	public static function get_dynamic_attribute( $product, $attribute_name, $merchant_attribute, $config ) {
		$get_attribute_value_by_type = new AttributeValueByType( $attribute_name, $merchant_attribute, $product, $config );
		$get_value                   = \maybe_unserialize( \get_option( $attribute_name ) );
		$wf_dattribute_code          = $get_value['wfDAttributeCode'] ?? '';
		$attribute                   = isset( $get_value['attribute'] ) ? (array) $get_value['attribute'] : array();
		$condition                   = isset( $get_value['condition'] ) ? (array) $get_value['condition'] : array();
		$compare                     = isset( $get_value['compare'] ) ? (array) $get_value['compare'] : array();
		$type                        = isset( $get_value['type'] )
			? (array) $get_value['type']
			: array();

		$prefix = isset( $get_value['prefix'] )
			? (array) $get_value['prefix']
			: array();
		$suffix = isset( $get_value['suffix'] )
			? (array) $get_value['suffix']
			: array();

		$value_attribute = isset( $get_value['value_attribute'] )
			? (array) $get_value['value_attribute']
			: array();
		$value_pattern   = isset( $get_value['value_pattern'] )
			? (array) $get_value['value_pattern']
			: array();

		$default_type            = $get_value['default_type'] ?? 'attribute';
		$default_value_attribute = $get_value['default_value_attribute'] ?? '';
		$default_value_pattern   = $get_value['default_value_pattern'] ?? '';

		$result = '';

		// Check If Attribute Code exist
		if ( $wf_dattribute_code && count( $attribute ) ) {
			foreach ( $attribute as $key => $name ) {
				if ( empty( $name ) ) {
					continue;
				}

				$condition_name = $get_attribute_value_by_type->get_value( $name );

				if ( 'weight' === $name ) {
					$unit = ' ' . \get_option( 'woocommerce_weight_unit' );

					if ( ! empty( $unit ) ) {
						$condition_name = (float) \str_replace( $unit, '', $condition_name );
					}
				}

				$condition_compare  = $compare[ $key ];
				$condition_operator = $condition[ $key ];

				if ( ! empty( $condition_compare ) ) {
					$condition_compare = \trim( $condition_compare );
				}

				$condition_value = '';

				if ( 'pattern' === $type[ $key ] ) {
					$condition_value = $value_pattern[ $key ];
				} elseif ( 'attribute' === $type[ $key ] ) {
					$condition_value = $get_attribute_value_by_type->get_value( $value_attribute[ $key ] );
				} elseif ( 'remove' === $type[ $key ] ) {
					$condition_value = '';
				}

				switch ( $condition_operator ) {
					case '==':
						if ( $condition_name === $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case '!=':
						if ( $condition_name !== $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case '>=':
						if ( $condition_name >= $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case '<=':
						if ( $condition_name <= $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case '>':
						if ( $condition_name > $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case '<':
						if ( $condition_name < $condition_compare ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case 'contains':
						if ( false !== stripos( $condition_name, $condition_compare ) ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case 'nContains':
						if ( stripos( $condition_name, $condition_compare ) === false ) {
							$result = self::price_format( $name, $condition_name, $condition_value );

							if ( '' !== $result ) {
								$result = $prefix[ $key ] . $result . $suffix[ $key ];
							}
						}

						break;

					case 'between':
						$compare_items = explode( ',', $condition_compare );

						if (
							isset( $compare_items[1] )
							&& \is_numeric( $compare_items[0] )
							&& \is_numeric( $compare_items[1] )
						) {
							if ( $condition_name >= $compare_items[0] && $condition_name <= $compare_items[1] ) {
								$result = self::price_format( $name, $condition_name, $condition_value );

								if ( '' !== $result ) {
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
		}

		if ( '' === $result ) {
			if ( 'pattern' === $default_type ) {
				$result = $default_value_pattern;
			} elseif ( 'attribute' === $default_type ) {
				if ( ! empty( $default_value_attribute ) ) {
					$result = $get_attribute_value_by_type->get_value( $default_value_attribute );
				}
			} elseif ( 'remove' === $default_type ) {
				$result = '';
			}
		}

		return apply_filters( 'woo_feed_after_dynamic_attribute_value', $result, $product, $attribute_name, $merchant_attribute, $config );
	}

	/**
	 * Formats a price or weight value based on the specified operation.
	 *
	 * @param string $name          Attribute Name indicating whether it's a price or weight.
	 * @param float  $conditionName The initial value to be formatted.
	 * @param string $result        The operation and value to apply to the initial value.
	 *
	 * @return float|int|string Formatted result after applying the operation.
	 * @since 3.2.0
	 */
	public static function price_format( $name, $condition_name, $result ) {
		// calc and return the output.
		if ( false !== \strpos( $name, 'price' ) || false !== \strpos( $name, 'weight' ) ) {
			if ( false !== \strpos( $result, '+' ) && false !== \strpos( $result, '%' ) ) {
				$result = \str_replace_trim( '+', '', $result );
				$result = \str_replace_trim( '%', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name + ( $condition_name * $result / 100 );
				}
			} elseif ( false !== \strpos( $result, '-' ) && false !== \strpos( $result, '%' ) ) {
				$result = \str_replace_trim( '-', '', $result );
				$result = \str_replace_trim( '%', '', $result );

				if ( \is_numeric( $result ) ) {
					// $result = ( ( $conditionName * $result ) / 100 ) - $conditionName;
					$result = $condition_name - ( $condition_name * $result / 100 );
				}
			} elseif ( false !== \strpos( $result, '*' ) && false !== \strpos( $result, '%' ) ) {
				$result = \str_replace_trim( '*', '', $result );
				$result = \str_replace_trim( '%', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name * $result / 100;
				}
			} elseif ( false !== \strpos( $result, '+' ) ) {
				$result = \str_replace_trim( '+', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name + $result;
				}
			} elseif ( false !== \strpos( $result, '-' ) ) {
				$result = \str_replace_trim( '-', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name - $result;
				}
			} elseif ( false !== \strpos( $result, '*' ) ) {
				$result = \str_replace_trim( '*', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name * $result;
				}
			} elseif ( false !== \strpos( $result, '/' ) ) {
				$result = \str_replace_trim( '/', '', $result );

				if ( \is_numeric( $result ) ) {
					$result = $condition_name / $result;
				}
			}
		}

		return $result;
	}

	/**
	 * Validates a given date string against a specified format.
	 *
	 * @param string $date   The date string to validate.
	 * @param string $format The date format to validate against. Defaults to 'Y-m-d'.
	 *
	 * @return bool True if the date is valid and matches the format, false otherwise.
	 */
	public static function validate_date( $date, $format = 'Y-m-d' ) {
		if ( ! \is_string( $date ) ) {
			// Optional: Add error logging or handling if $date is not a string
			return false;
		}

		$dateTime = DateTime::createFromFormat( $format, $date );

		return $dateTime && $dateTime->format( $format ) === $date;
	}

	/**
	 * Retrieves a product attribute value based on its type.
	 *
	 * @param string                     $attribute          The name of the product attribute.
	 * @param \WC_Product                $product            The product object, representing the context of the attribute.
	 * @param \CTXFeed\V5\Utility\Config $config             Configuration settings, affecting how attribute values are processed.
	 * @param string|null                $merchant_attribute Optional merchant-specific attribute, altering the return value based on merchant requirements.
	 * @param \WC_Product                $parent_product     The product object.
	 *
	 * @return mixed The value of the attribute, which varies depending on the attribute type and configuration settings.
	 */
	public static function get_attribute_value_by_type( $attribute, $product, $config, $merchant_attribute = null, $parent_product = null ) {
		// Error handling: validate inputs
		if ( ! $product || ! $config ) {
			// Handle error or invalid input
			return null;
		}

		// Efficient handling of AttributeValueByType instance creation could be considered here
		$attribute_value = new AttributeValueByType( $attribute, $product, $config, $merchant_attribute, $parent_product );

		return $attribute_value->get_value();
	}

	/**
	 * Replaces specific strings in a product attribute based on configuration rules.
	 *
	 * @param string $output           The initial string to be modified.
	 * @param string $productAttribute The product attribute to be checked for replacements.
	 * @param Config $config           Configuration containing the replacement rules.
	 *
	 * @return string The modified string after applying the replacement rules.
	 *
	 * @todo Write test cases for this method.
	 */
	public static function str_replace( $output, $product_attribute, $config ) {

		// str_replace array can contain duplicate subjects, so better loop through...
		foreach ( $config->get_string_replace() as $str_replace ) {

			if ( ! empty( $str_replace['subject'] ) && ( $product_attribute == $str_replace['subject'] || self::PRODUCT_ATTRIBUTE_PREFIX . $product_attribute == $str_replace['subject'] ) ) {

				if ( \strpos( $str_replace['search'], '/' ) === false ) {
					$output = \preg_replace( \stripslashes( '/' . $str_replace['search'] . '/mi' ), $str_replace['replace'], $output );
				} else {
					$output = \str_replace( $str_replace['search'], $str_replace['replace'], $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Adds a prefix and/or suffix to a given output string based on attribute configurations.
	 *
	 * @param string                     $output             The string to which the prefix and suffix will be added.
	 * @param string                     $attribute          The product attribute to which the prefix and suffix apply.
	 * @param \CTXFeed\V5\Utility\Config $config             Configuration settings for determining prefix and suffix.
	 * @param string|null                $merchant_attribute Optional merchant-specific attribute.
	 *
	 * @return string The modified output string with the appropriate prefix and/or suffix added.
	 */
	public static function add_prefix_suffix( $output, $attribute, $config, $merchant_attribute ) {
		if ( $output === '' ) {
			return $output;
		}

		if ( ! $config || ! \method_exists( $config, 'get_prefix_suffix' ) ) {
			// Handle error or invalid configuration object
			return $output;
		}

		$prefix_suffix = $config->get_prefix_suffix( $attribute, $merchant_attribute );

		$output = ( empty( $prefix_suffix['prefix'] ) ? '' : $prefix_suffix['prefix'] ) . $output;
		if ( ! empty( $prefix_suffix['suffix'] ) ) {
			$output .= ( \preg_match( '/^\s/', $prefix_suffix['suffix'] ) ? '' : ' ' ) . $prefix_suffix['suffix'];
		}

		if ( self::should_strip_prefix_suffix( $attribute ) ) {
			$output = \str_replace( ' ', '', $output );
		}

		return $output;
	}

	/**
	 * Determines if a prefix or suffix should be stripped from a given attribute.
	 *
	 * @param string $attribute The attribute name to check.
	 *
	 * @return bool True if the prefix and suffix should be stripped for the given attribute, false otherwise.
	 */
	public static function should_strip_prefix_suffix( $attribute ) {
		// Validate attribute
		if ( ! \is_string( $attribute ) ) {
			// Handle error or invalid input
			return false;
		}

		// Consider defining these as a class constant or static variable if reused
		$attributes_to_strip = [
			'link',
			'canonical_link',
			'mobile_link',
			'image',
			'images',
			'images_1',
			'images_2',
			'images_3',
			'images_4',
			'images_5',
			'images_6',
			'images_7',
			'images_8',
			'images_9',
			'images_10',
			'image_1',
			'image_2',
			'image_3',
			'image_4',
			'image_5',
			'image_6',
			'image_7',
			'image_8',
			'image_9',
			'image_10'
		];

		return \in_array( $attribute, $attributes_to_strip );
	}

	/**
	 * Translates an attribute using the TranslatePress plugin.
	 *
	 * @param string     $attribute       Name of the product attribute.
	 * @param mixed      $attribute_value Value of the product attribute.
	 * @param WC_Product $product         Product object.
	 * @param mixed      $config          Feed configuration settings.
	 *
	 * @return mixed Translated attribute value if TranslatePress is active and configured, else returns original value.
	 * @since 5.2.12
	 */
	public static function get_tp_translate( $attribute, $attribute_value, $product, $config ) {
		if ( \is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
			$target_language = $config->get_feed_language( $attribute );

			if ( ! empty( $target_language ) ) {
				if ( \class_exists( 'TRP_Settings' ) && \class_exists( 'TRP_Translation_Render' ) ) {
					$settings   = ( new TRP_Settings() )->get_settings();
					$trp_render = new TRP_Translation_Render( $settings );
					global $TRP_LANGUAGE;
					$default_language = $TRP_LANGUAGE;
					$TRP_LANGUAGE     = $target_language;
					$attribute_value  = $trp_render->translate_page( $attribute_value );

					// Resetting the global language to default
					$TRP_LANGUAGE = $default_language;
				}
			}
		}

		return $attribute_value;
	}

	/**
	 * Retrieves a WooCommerce product object based on the product ID and configuration settings.
	 * For variable products, it returns a specific variation based on the configured variation type.
	 *
	 * @param int    $product_id Product ID.
	 * @param Config $config     Configuration settings for handling variable products.
	 *
	 * @return mixed                The product object, which may be a variation for variable products.
	 * @throws Exception                    If the product is not found or an error occurs.
	 */
	public static function get_product_object( $product_id, $config ) {
		$product = \wc_get_product( $product_id );
		if ( ! $product ) {
			throw new Exception( 'Product not found.' );
		}

		$variable_config = $config->get_variable_config();
		$variation_type  = $variable_config['is_variations'];

		if ( $product->is_type( 'variable' ) && \in_array( $variation_type, [
				'default',
				'cheap',
				'first',
				'last',
				'expensive',
				'n'
			], true ) ) {
			$id = self::determine_variable_product( $product, $variation_type );

			return $id ? \wc_get_product( $id ) : $product;
		}

		if ( $config->get_categories_to_include() && $product->is_type( 'variable' ) ) {
			$products = [];
			if ( $variation_type == 'both' ) {
				array_push( $products, $product );
			}
			$variations = $product->get_visible_children();
			foreach ( $variations as $variation_id ) {
				$variation_product = wc_get_product( $variation_id );
				array_push( $products, $variation_product );
			}

			return $products;
		}


		return $product;
	}

	/**
	 * Determines the ID of the variable product based on the variation type.
	 *
	 * @param WC_Product $product       The variable product.
	 * @param string     $variationType The type of variation to retrieve.
	 *
	 * @return int|null The ID of the determined product variation, or null if not found.
	 */
	private static function determine_variable_product( $product, $variation_type ) {
		$variations       = $product->get_visible_children();
		$variations_price = $product->get_variation_prices();
		switch ( $variation_type ) {
			case 'default':
				return self::get_default_product_variation( $product );

			case 'first':
				return ! empty( $variations ) ? \reset( $variations ) : null;

			case 'last':
				return ! empty( $variations ) ? \end( $variations ) : null;

			case 'cheap':
				return isset( $variations_price['price'] ) ? \array_keys( $variations_price['price'], \min( $variations_price['price'] ) )[0] : null;

			case 'expensive':
				return isset( $variations_price['price'] ) ? \array_keys( $variations_price['price'], \max( $variations_price['price'] ) )[0] : null;
		}

		return null;
	}

	/**
	 * Finds the default variation ID for a variable product.
	 *
	 * @param WC_Product $product The variable product.
	 *
	 * @return int|false The ID of the default variation, or false if the product is not a variable product.
	 * @throws Exception If the product is not valid.
	 */
	public static function get_default_product_variation( $product ) {
		if ( $product->is_type( 'variable' ) ) {
			$attributes = $product->get_default_attributes();

			foreach ( $attributes as $key => $value ) {
				if ( \strpos( $key, 'attribute_' ) === 0 ) {
					continue;
				}

				unset( $attributes[ $key ] );
				$attributes[ \sprintf( 'attribute_%s', $key ) ] = $value;
			}

			return ( new WC_Product_Variation_Data_Store_CPT )->find_matching_product_variation( $product, $attributes );
		}

		return false;
	}

	/**
	 * Retrieves the product price including tax.
	 *
	 * @param float|string $price   The base price of the product.
	 * @param WC_Product   $product The WooCommerce product object.
	 *
	 * @return float The product price including tax.
	 */
	public static function get_price_with_tax( $price, $product ) {
		if ( CommonHelper::wc_version_check( 3.0 ) ) {
			return \wc_get_price_including_tax( $product, array( 'price' => $price ) );
		}

		return $product->get_price_including_tax( 1, $price );

	}

}

