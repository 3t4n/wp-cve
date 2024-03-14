<?php
/**
 * Class AttributeValueByType
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Product
 * @category   MyCategory
 */

namespace CTXFeed\V5\Product;

use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Output\AttributeMapping;
use CTXFeed\V5\Output\CategoryMapping;
use CTXFeed\V5\Output\DynamicAttributes;
use CTXFeed\V5\Output\FormatOutput;
use CTXFeed\V5\Output\OutputCommands;

/**
 * Class AttributeValueByType
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Product
 * @category   MyCategory
 */
class AttributeValueByType {

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
	 * Feed rules prefix
	 *
	 * @since 3.1.18
	 */
	const FEED_RULES_OPTION_PREFIX = 'wf_feed_';

	/**
	 * Feed temporary file body name prefix
	 *
	 * @since 3.1.18
	 */
	const FEED_TEMP_BODY_PREFIX = 'wf_store_feed_body_info_';

	/**
	 * Auto Feed temporary file body name prefix
	 *
	 * @since 3.1.18
	 */
	const AUTO_FEED_TEMP_BODY_PREFIX = 'wf_store_auto_feed_body_info_';

	/**
	 * Feed temporary file header name prefix
	 *
	 * @since 3.1.18
	 */
	const FEED_TEMP_HEADER_PREFIX = 'wf_store_feed_header_info_';

	/**
	 * Feed temporary file footer name prefix
	 *
	 * @since 3.1.18
	 */
	const FEED_TEMP_FOOTER_PREFIX = 'wf_store_feed_footer_info_';

	/**
	 * WP Option Name
	 *
	 * @since 6.1.1
	 */
	const WP_OPTION_NAME = 'wpfp_option';

	/**
	 * @var string $attribute Attribute name.
	 */
	private $attribute;

	/**
	 * @var string|null $merchant_attribute Merchant attribute name.
	 */
	private $merchant_attribute;

	/**
	 * @var \WC_Product $product Product Object.
	 */
	private $product;

	/**
	 * @var \CTXFeed\V5\Product\ProductInfo $productInfo Product Info Object.
	 */
	private $product_info;

	/**
	 * @var \CTXFeed\V5\Output\FormatOutput $format_output Format Output Object.
	 */
	private $format_output;

	/**
	 * @var \CTXFeed\V5\Output\OutputCommands $format_command Format Command Object.
	 */
	private $format_command;

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Config Object.
	 */
	private $config;

	/**
	 * @var \WC_Product|null $parent_product Parent Product Object.
	 */
	private $parent_product;

	/**
	 * AttributeValueByType constructor.
	 *
	 * Initializes the class with the provided parameters, setting up the necessary properties
	 * and instances based on the type of the product.
	 *
	 * @param string $attribute The attribute to be processed.
	 * @param \WC_Product $product The WooCommerce product object.
	 * @param \CTXFeed\V5\Utility\Config $config Configuration settings.
	 * @param string|null $merchant_attribute Optional. The merchant-specific attribute.
	 * @param \WC_Product|null $parent_product Optional. The parent product if the current product is a
	 *                                                       variation.
	 */
	public function __construct( $attribute, $product, $config, $merchant_attribute = null, $parent_product = null ) {
		$this->attribute          = $attribute;
		$this->merchant_attribute = $merchant_attribute;
		$this->product            = $product;
		$this->config             = $config;

		$this->parent_product = $parent_product;// phpcs:ignore
		$this->product_info   = new ProductInfo( $this->product, $this->config, $this->parent_product );
		$this->format_output  = new FormatOutput( $this->product, $this->config, $this->attribute, $this->parent_product );
		$this->format_command = new OutputCommands( $this->product, $this->config, $this->attribute, $this->parent_product );
	}

	/**
	 * Get product attribute value by attribute type.
	 *
	 * Determines the attribute value based on its type and the associated product. The method
	 * leverages different helper classes and methods to compute the value, including custom
	 * and dynamic attributes.
	 *
	 * @param string $attr Optional. The attribute to get the value of.
	 *
	 * @return mixed The value of the attribute, processed and filtered.
	 * @throws \Exception If the attribute type is not recognized.
	 */
	public function get_value( $attr = '' ) {
		if ( ! empty( $attr ) ) {
			$this->attribute = $attr;
		}

		if ( $this->attribute === null ) {
			$this->attribute = '';
		}

		if ( method_exists( $this->product_info, $this->attribute ) ) {
			$output = $this->product_info->{$this->attribute}();
		} elseif ( strpos( $this->attribute, self::PRODUCT_EXTRA_ATTRIBUTE_PREFIX ) !== false ) {
			$attribute = str_replace( self::PRODUCT_EXTRA_ATTRIBUTE_PREFIX, '', $this->attribute );

			return apply_filters( "woo_feed_get_extra_{$attribute}_attribute", '', $this->product, $this->config );
		} elseif ( false !== strpos( $this->attribute, 'csv_tax_' ) ) {
			$key    = str_replace( 'csv_tax_', '', $this->attribute );
			$output = $this->product_info->tax( (string) $key );
		} elseif ( false !== strpos( $this->attribute, 'csv_shipping_' ) ) {
			$key    = str_replace( 'csv_shipping_', '', $this->attribute );
			$output = $this->product_info->shipping( (string) $key );
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_ACF_FIELDS ) ) {
			$output = ProductHelper::get_acf_field( $this->product, $this->attribute );
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) ) {
			//$output = ProductHelper::get_attribute_mapping( $this->product, $this->attribute, $this->merchant_attribute, $this->config );
			$output = AttributeMapping::getMappingValue( $this->attribute, $this->merchant_attribute, $this->product, $this->config );
			//die($output);
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX ) ) {
			//$output = ProductHelper::get_dynamic_attribute( $this->product, $this->attribute, $this->merchant_attribute, $this->config );
			$output = DynamicAttributes::getDynamicAttributeValue( $this->attribute, $this->merchant_attribute, $this->product, $this->config );
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_CUSTOM_IDENTIFIER ) || woo_feed_strpos_array( array(
				'_identifier_gtin',
				'_identifier_ean',
				'_identifier_mpn'
			), $this->attribute ) ) {
			$output = ProductHelper::get_custom_field( $this->attribute, $this->product, $this->config );
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_ATTRIBUTE_PREFIX ) ) {
			$this->attribute = str_replace( self::PRODUCT_ATTRIBUTE_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_attribute( $this->attribute, $this->product, $this->config );
		} elseif ( false !== strpos( $this->attribute, self::POST_META_PREFIX ) ) {
			$this->attribute = str_replace( self::POST_META_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_meta( $this->attribute, $this->product, $this->config );
			$this->attribute = self::POST_META_PREFIX . $this->attribute;
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_TAXONOMY_PREFIX ) ) {
			$this->attribute = str_replace( self::PRODUCT_TAXONOMY_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_taxonomy( $this->attribute, $this->product, $this->config );
			//[For getting exact attribute name need to added "PRODUCT_TAXONOMY_PREFIX" which is removed before cz in ProductHelper check  '$productAttribute !== $str_replace['subject']', 'Jira tkt: CTX-656']
			$this->attribute = self::PRODUCT_TAXONOMY_PREFIX . $this->attribute;
		} elseif ( false !== strpos( $this->attribute, self::PRODUCT_CATEGORY_MAPPING_PREFIX ) ) {
			$id = $this->product->is_type( 'variation' ) ? $this->product->get_parent_id() : $this->product->get_id();
			//$output = ProductHelper::get_category_mapping( $this->attribute, $id );
			$output = CategoryMapping::getCategoryMappingValue( $this->attribute, $id );
		} elseif ( false !== strpos( $this->attribute, self::WP_OPTION_PREFIX ) ) {
			$optionName = str_replace( self::WP_OPTION_PREFIX, '', $this->attribute );
			$output     = get_option( $optionName );
		} elseif ( strpos( $this->attribute, 'image_' ) === 0 ) {
			// For additional image method images() will be used with extra parameter - image number
			$imageKey = explode( '_', $this->attribute );
			if ( empty( $imageKey[1] ) || ! is_numeric( $imageKey[1] ) ) {
				$imageKey[1] = '';
			}
			$output = $this->product_info->images( $imageKey[1] );
		} elseif ( $this->attribute == 'identifier_exists' ) {
			$output = ProductHelper::overwrite_identifier_exists( $this->attribute, $this->product, $this->config );

		} else {
			$output = $this->get_attribute_value_by_type();
		}

		return $this->process_output( $output );
	}

	/**
	 * Process and format the output based on configuration settings.
	 *
	 * @param mixed $output The initial output to be processed.
	 *
	 * @return string Processed output.
	 */
	protected function process_output( $output ) {
		if ( is_array( $output ) ) {
			$output = wp_json_encode( $output );
		}

		if ( $this->config->get_string_replace() ) {
			$output = ProductHelper::str_replace( $output, $this->attribute, $this->config );
		}

		$output_types = $this->config->get_attribute_output_types( $this->attribute, $this->merchant_attribute );

		if ( ! empty( $output_types ) ) {
			$output = $this->format_output->get_output( $output, $output_types );
		}

		$output_commands = $this->config->get_attribute_commands( $this->attribute, $this->merchant_attribute );

		if ( ! empty( $output_commands ) ) {
			$output = $this->format_command->process_command( $output, $output_commands );
		}

		$output = ProductHelper::add_prefix_suffix( $output, $this->attribute, $this->config, $this->merchant_attribute );

		return $this->apply_filters_to_attribute_value( $output, $this->merchant_attribute );
	}

	/**
	 * Get the value of an attribute based on its type.
	 *
	 * This method handles various attribute types including custom fields, taxonomy,
	 * category mapping, and more.
	 *
	 * @return mixed The value of the attribute.
	 * @throws \Exception If the attribute type is not recognized.
	 */
	protected function get_attribute_value_by_type() {// phpcs:ignore
		if ( strpos( $this->attribute, 'csv_tax_' ) !== false ) {
			$key    = str_replace( 'csv_tax_', '', $this->attribute );
			$output = $this->product_info->tax( (string) $key );
		} elseif ( strpos( $this->attribute, 'csv_shipping_' ) !== false ) {
			$key    = str_replace( 'csv_shipping_', '', $this->attribute );
			$output = $this->product_info->shipping( (string) $key );
		} elseif ( strpos( $this->attribute, self::PRODUCT_ACF_FIELDS ) !== false ) {
			$output = ProductHelper::get_acf_field( $this->product, $this->attribute );
		} elseif ( strpos( $this->attribute, self::PRODUCT_ATTRIBUTE_MAPPING_PREFIX ) !== false ) {
			$output = AttributeMapping::getMappingValue( $this->attribute, $this->merchant_attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX ) !== false ) {
			$output = DynamicAttributes::getDynamicAttributeValue( $this->attribute, $this->merchant_attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::PRODUCT_CUSTOM_IDENTIFIER ) !== false || woo_feed_strpos_array(
				array( '_identifier_gtin', '_identifier_ean', '_identifier_mpn' ),
				$this->attribute
			) ) {
			$output = ProductHelper::get_custom_filed( $this->attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::PRODUCT_ATTRIBUTE_PREFIX ) !== false ) {
			$this->attribute = str_replace( self::PRODUCT_ATTRIBUTE_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_attribute( $this->attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::POST_META_PREFIX ) !== false ) {
			$this->attribute = str_replace( self::POST_META_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_meta( $this->attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::PRODUCT_TAXONOMY_PREFIX ) !== false ) {
			$this->attribute = str_replace( self::PRODUCT_TAXONOMY_PREFIX, '', $this->attribute );
			$output          = ProductHelper::get_product_taxonomy( $this->attribute, $this->product, $this->config );
		} elseif ( strpos( $this->attribute, self::PRODUCT_CATEGORY_MAPPING_PREFIX ) !== false ) {
			$id = $this->product->get_id();

			if ( $this->product->is_type( 'variation' ) ) {
				$id = $this->product->get_parent_id();
			}

			$output = CategoryMapping::getCategoryMappingValue( $this->attribute, $id );
		} elseif ( strpos( $this->attribute, self::WP_OPTION_PREFIX ) !== false ) {
			$option_name = str_replace( self::WP_OPTION_PREFIX, '', $this->attribute );
			$output      = get_option( $option_name );
		} elseif ( strpos( $this->attribute, 'image_' ) === 0 ) {
			$image_key = explode( '_', $this->attribute );

			if ( isset( $image_key[1] ) && is_numeric( $image_key[1] ) ) {
				$image_key[1] = (int) $image_key[1];
			} else {
				$image_key[1] = '';
			}

			$output = $this->product_info->images( $image_key[1] );
		} elseif ( $this->attribute === 'identifier_exists' ) {
			$output = ProductHelper::overwrite_identifier_exists( $this->attribute, $this->product, $this->config );
		} else {
			$output = $this->attribute;
		}

		return $output;
	}

	/**
	 *  Apply Filter to Attribute value
	 *
	 * @param string $output The output.
	 * @param string $merchant_attribute Merchant attribute.
	 *
	 * @return mixed|void
	 */
	protected function apply_filters_to_attribute_value( $output, $merchant_attribute = '' ) {
		$product_attribute = $this->attribute;
		/**
		 * Filter attribute value
		 *
		 * @param string $output the output
		 * @param \WC_Product $product Product Object.
		 * @param object $config feed config/rule
		 *
		 * @since 3.4.3
		 */
		$output = apply_filters( 'woo_feed_get_attribute', $output, $this->product, $this->config, $product_attribute, $merchant_attribute );

		/**
		 * Filter attribute value before return based on product attribute name
		 *
		 * @param string $output the output
		 * @param \WC_Product $product Product Object.
		 * @param array $config feed config/rule
		 *
		 * @since 3.3.5
		 */

		$output = apply_filters( "woo_feed_get_{$product_attribute}_attribute", $output, $this->product, $this->config, $product_attribute, $merchant_attribute );

		/**
		 * Filter attribute value before return based on merchant name
		 *
		 * @param string $output the output
		 * @param \WC_Product $product Product Object.
		 * @param array $config feed config/rule
		 *
		 * @since 3.3.5
		 */

		$output = apply_filters( "woo_feed_get_{$this->config->provider}_attribute", $output, $this->product, $this->config, $product_attribute, $merchant_attribute );

		/**
		 * Filter attribute value before return based on merchant and merchant attribute name
		 *
		 * @param string $output the output
		 * @param \WC_Product $product Product Object.
		 * @param array $config feed config/rule
		 *
		 * @since 3.3.7
		 */

		$merchant_attribute = ( $merchant_attribute === null ? '' : $merchant_attribute );

		// TODO:: Google Certification Attribute.
		if (
			$this->config->feed_info['option_value']['feedrules']['provider'] === 'google'
			&& $merchant_attribute === 'certification'
		) {
			$merchant_attribute = str_replace( array( ' ', 'g:' ), '', $merchant_attribute );

			if ( $this->config->feed_info['option_value']['feedrules']['feedType'] === 'xml' ) {

				$output = array(
					'g:certification_authority' => 'EC',
					'g:certification_name'      => 'EPREL',
					'g:certification_code'      => $output,
				);
			} else {
				$output = "EC:EPREL:$output";
			}
		}

		$template = $this->config->get_feed_template();

		return apply_filters( "woo_feed_get_{$template}_{$merchant_attribute}_attribute", $output, $this->product, $this->config, $product_attribute, $merchant_attribute );
	}

}
