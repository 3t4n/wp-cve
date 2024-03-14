<?php

/**
 * The file that defines the merchants attributes
 *
 * A class definition that includes attributes and functions used across the
 * admin area.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class Woo_Feed_Product_Attributes {
	/**
	 * Hold Product Attributes.
	 *
	 * @var $attributes
	 */
	public $attributes;

	/**
	 * Get Product Attributes
	 * @return string
	 */
	public function getAttributes( $selected = '' ) {
		$attributeDropdown = $this->get_cached_dropdown( 'woo_feed_product_attribute_dropdown', $selected );
		if ( false === $attributeDropdown ) {
			return $this->cache_dropdown( 'woo_feed_product_attribute_dropdown', $this->setAttributes(), $selected, __( 'Select Attributes', 'woo-feed' ) );
		}

		return $attributeDropdown;
	}

	/**
	 * Get Cached Dropdown Entries
	 *
	 * @param string $key cache key
	 * @param string $selected selected option
	 *
	 * @return string|false
	 */
	protected function get_cached_dropdown( $key, $selected = '' ) {
		$options = woo_feed_get_cached_data( $key );
		if ( $selected !== '' ) {
			$selected = esc_attr( $selected );
			$options  = str_replace( "value=\"{$selected}\"", "value=\"{$selected}\" selected", $options );
		}

		return empty( $options ) ? false : $options;
	}

	/**
	 * Create dropdown options and cache for next use
	 *
	 * @param string $cache_key cache key
	 * @param array $items dropdown items
	 * @param string $selected selected option
	 * @param string $default default option
	 *
	 * @return string
	 */
	protected function cache_dropdown( $cache_key, $items, $selected = '', $default = '' ) {

		if ( empty( $items ) || ! is_array( $items ) ) {
			return '';
		}

		if ( ! empty( $default ) ) {
			$options = '<option value="" class="disabled" selected>' . esc_html( $default ) . '</option>';
		} else {
			$options = '<option></option>';
		}

		foreach ( $items as $item ) {
			if ( ! empty( $item['options'] ) ) {
				$options .= "<optgroup label=\"{$item['optionGroup']}\">";
				foreach ( $item['options'] as $option_key => $option ) {
					$options .= sprintf( '<option value="%s">%s</option>', $option_key, $option );
				}
			}
			$options .= '</optgroup>';
		}

		woo_feed_set_cache_data( $cache_key, $options );

		if ( $selected !== '' ) {
			$selected = esc_attr( $selected );
			$options  = str_replace( "value=\"{$selected}\"", "value=\"{$selected}\" selected", $options );
		}

		return $options;
	}

	protected function setAttributes() {
		$attributes        = [];
		$primaryAttributes = [
			'optionGroup' => esc_html__( 'Primary Attributes', 'woo-feed' ),
			'options'     => [
				'id'                    => esc_html__( 'Product Id', 'woo-feed' ),
				'title'                 => esc_html__( 'Product Title', 'woo-feed' ),
				'parent_title'          => esc_html__( 'Parent Title', 'woo-feed' ),
				'description'           => esc_html__( 'Product Description', 'woo-feed' ),
				'description_with_html' => esc_html__( 'Product Description (with HTML)', 'woo-feed' ),
				'short_description'     => esc_html__( 'Product Short Description', 'woo-feed' ),
				'primary_category'      => esc_html__( 'Parent Category', 'woo-feed' ),
				'primary_category_id'   => esc_html__( 'Parent Category ID', 'woo-feed' ),
				'child_category'        => esc_html__( 'Child Category', 'woo-feed' ),
				'child_category_id'     => esc_html__( 'Child Category ID', 'woo-feed' ),
				'product_type'          => esc_html__( 'Product Category [Category Path]', 'woo-feed' ),
				'product_full_cat'      => esc_html__( 'Product Full Category [Category Full Path]', 'woo-feed' ),
				'link'                  => esc_html__( 'Product URL', 'woo-feed' ),
				'parent_link'           => esc_html__( 'Parent URL', 'woo-feed' ),
				'canonical_link'        => esc_html__( 'Canonical URL', 'woo-feed' ),
				'ex_link'               => esc_html__( 'External Product URL', 'woo-feed' ),
				'add_to_cart_link'      => esc_html__( 'Add to Cart URL', 'woo-feed' ),
				'item_group_id'         => esc_html__( 'Parent Id [Group Id]', 'woo-feed' ),
				'sku'                   => esc_html__( 'SKU', 'woo-feed' ),
				'sku_id'                => esc_html__( 'SKU_ID', 'woo-feed' ),
				'parent_sku'            => esc_html__( 'Parent SKU', 'woo-feed' ),
				'availability'          => esc_html__( 'Availability', 'woo-feed' ),
				'availability_date'     => esc_html__( 'Availability Date', 'woo-feed' ),
				'quantity'              => esc_html__( 'Quantity', 'woo-feed' ),
				'reviewer_name'         => esc_html__( 'Reviewer Name', 'woo-feed' ),
				'weight'                => esc_html__( 'Weight', 'woo-feed' ),
				'weight_unit'           => esc_html__( 'Weight Unit', 'woo-feed' ),
				'width'                 => esc_html__( 'Width', 'woo-feed' ),
				'height'                => esc_html__( 'Height', 'woo-feed' ),
				'length'                => esc_html__( 'Length', 'woo-feed' ),
				'type'                  => esc_html__( 'Product Type', 'woo-feed' ),
				'visibility'            => esc_html__( 'Visibility', 'woo-feed' ),
				'rating_total'          => esc_html__( 'Total Rating', 'woo-feed' ),
				'rating_average'        => esc_html__( 'Average Rating', 'woo-feed' ),
				'tags'                  => esc_html__( 'Tags', 'woo-feed' ),
				'is_bundle'             => esc_html__( 'Is Bundle', 'woo-feed' ),
				'author_name'           => esc_html__( 'Author Name', 'woo-feed' ),
				'author_email'          => esc_html__( 'Author Email', 'woo-feed' ),
				'date_created'          => esc_html__( 'Date Created', 'woo-feed' ),
				'date_updated'          => esc_html__( 'Date Updated', 'woo-feed' ),
				'product_status'        => esc_html__( 'Product Status', 'woo-feed' ),
				'featured_status'        => esc_html__( 'Featured Status', 'woo-feed' ),
			]
		];

		$attributes [] = $primaryAttributes;


		$imageAttributes = [
			'optionGroup' => esc_html__( 'Images', 'woo-feed' ),
			'options'     => [
				'image'         => esc_html__( 'Main Image', 'woo-feed' ),
				'feature_image' => esc_html__( 'Featured Image', 'woo-feed' ),
				'images'        => esc_html__( 'Images [Comma Separated]', 'woo-feed' ),
				'image_1'       => esc_html__( 'Additional Image 1', 'woo-feed' ),
				'image_2'       => esc_html__( 'Additional Image 2', 'woo-feed' ),
				'image_3'       => esc_html__( 'Additional Image 3', 'woo-feed' ),
				'image_4'       => esc_html__( 'Additional Image 4', 'woo-feed' ),
				'image_5'       => esc_html__( 'Additional Image 5', 'woo-feed' ),
				'image_6'       => esc_html__( 'Additional Image 6', 'woo-feed' ),
				'image_7'       => esc_html__( 'Additional Image 7', 'woo-feed' ),
				'image_8'       => esc_html__( 'Additional Image 8', 'woo-feed' ),
				'image_9'       => esc_html__( 'Additional Image 9', 'woo-feed' ),
				'image_10'      => esc_html__( 'Additional Image 10', 'woo-feed' ),
			],
		];

		$attributes [] = $imageAttributes;

		$priceAttributes = [
			'optionGroup' => esc_html__( 'Price', 'woo-feed' ),
			'options'     => [
				'currency'                  => esc_html__( 'Currency', 'woo-feed' ),
				'price'                     => esc_html__( 'Regular Price', 'woo-feed' ),
				'current_price'             => esc_html__( 'Price', 'woo-feed' ),
				'sale_price'                => esc_html__( 'Sale Price', 'woo-feed' ),
				'price_with_tax'            => esc_html__( 'Regular Price With Tax', 'woo-feed' ),
				'current_price_with_tax'    => esc_html__( 'Price With Tax', 'woo-feed' ),
				'sale_price_with_tax'       => esc_html__( 'Sale Price With Tax', 'woo-feed' ),
				'sale_price_sdate'          => esc_html__( 'Sale Start Date', 'woo-feed' ),
				'sale_price_edate'          => esc_html__( 'Sale End Date', 'woo-feed' ),
				'sale_price_effective_date' => esc_html__( 'Sale Price Effective Date', 'woo-feed' ),
			],
		];

		$attributes [] = $priceAttributes;

		$shippingAttributes = [
			'optionGroup' => esc_html__( 'Shipping', 'woo-feed' ),
			'options'     => [
				'shipping'             => esc_html__( 'Shipping (Google Format)', 'woo-feed' ),
				'shipping_class'       => esc_html__( 'Shipping Class', 'woo-feed' ),
//				'shipping_zone_name'   => esc_html__( 'Shipping Zone Name', 'woo-feed' ),
//				'shipping_country'     => esc_html__( 'Shipping Country', 'woo-feed' ),
//				'shipping_region'      => esc_html__( 'Shipping Regions', 'woo-feed' ),
//				'shipping_postcode' => esc_html__( 'Shipping Postcodes', 'woo-feed' ),
//				'shipping_service'     => esc_html__( 'Shipping Method Name', 'woo-feed' ),
//				'shipping_price'       => esc_html__( 'Shipping Cost', 'woo-feed' ),
			],
		];

		$attributes [] = $shippingAttributes;

		$taxAttributes = [
			'optionGroup' => esc_html__( 'Tax', 'woo-feed' ),
			'options'     => [
				'tax'           => esc_html__( 'Tax (Google Format)', 'woo-feed' ),
				'tax_class'     => esc_html__( 'Tax Class', 'woo-feed' ),
//				'tax_status'    => esc_html__( 'Tax Status', 'woo-feed' ),
//				'tax_country'   => esc_html__( 'Tax Country', 'woo-feed' ),
//				'tax_state'     => esc_html__( 'Tax State', 'woo-feed' ),
//				'tax_postcode' => esc_html__( 'Tax Postcodes', 'woo-feed' ),
//				'tax_city'      => esc_html__( 'Tax City', 'woo-feed' ),
//				'tax_rate'      => esc_html__( 'Tax Rate', 'woo-feed' ),
//				'tax_label'     => esc_html__( 'Tax Name', 'woo-feed' ),
			],
		];

		$attributes [] = $taxAttributes;

		$subscriptionAttributes = [
			'optionGroup' => esc_html__( 'Subscription & Installment', 'woo-feed' ),
			'options'     => [
				'subscription_period'          => esc_html__( 'Subscription Period', 'woo-feed' ),
				'subscription_period_interval' => esc_html__( 'Subscription Period Length', 'woo-feed' ),
				'subscription_amount'          => esc_html__( 'Subscription Amount', 'woo-feed' ),
				'installment_months'           => esc_html__( 'Installment Months', 'woo-feed' ),
				'installment_amount'           => esc_html__( 'Installment Amount', 'woo-feed' ),
			],
		];

		$customXMLAttributes = [
			'optionGroup' => esc_html__( 'Custom Template 2 (XML)', 'woo-feed' ),
			'options'     => [
				'custom_xml_variations' => esc_html__( 'Product Variations', 'woo-feed' ),
				'custom_xml_images'     => esc_html__( 'Product Gallery Images', 'woo-feed' ),
				'custom_xml_categories' => esc_html__( 'Product Categories', 'woo-feed' ),
			]
		];

		/**
		 * Add subscription attributes if WooCommerce Subscription plugin installed.
		 * @link https://woocommerce.com/products/woocommerce-subscriptions/
		 */
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$attributes[] = $subscriptionAttributes;
		}

		$attributes[] = $this->getPluginsCustomFields();
		$attributes[] = $this->getUnitPriceAttributes();
		$attributes[] = $this->getSeoPluginAttributes();
		$attributes[] = $this->getGlobalAttributes();
		$attributes[] = $this->getCustomAttributes();
		$attributes[] = $this->getAllTaxonomy();
		$attributes[] = $this->getAllOptions();
		// Category Mapping
		$attributes[] = $this->getCategoryMappedAttributes();
		// ACF Plugin custom fields getACFAttributes
		$attributes[] = $this->getACFAttributes();
		// Custom Fields & Post Metas
		$attributes[] = $this->getProductMetaKeyAttributes();

		if(class_exists('Woo_Feed_Products_v3_Pro')){
			$attributes [] = $this->getMultiLanguageAttributes();
			$attributes [] = $this->getMultiVendorAttributes();
			// Dynamic Attributes
			$attributes[] = $this->getDynamicAttributes();
			// Attribute Mappings
			$attributes[] = $this->getCustomMappedAttributes();
			// Custom Template 2 Attributes
			$attributes [] = $customXMLAttributes;
		}

		return apply_filters( 'woo_feed_product_attribute_dropdown', $attributes );
	}

	/**
	 * Get CTX Feed plugins Custom Fields.
	 *
	 * @return array
	 */
	protected function getPluginsCustomFields() {

		$custom_fields            = woo_feed_product_custom_fields();
		$custom_identifier_filter = new Woo_Feed_Custom_Identifier_Filter( $custom_fields );
		$custom_identifier        = iterator_to_array( $custom_identifier_filter );
		$activeAttributes         = [ 'optionGroup' => esc_html__( 'Custom Fields by CTX Feed', 'woo-feed' ), ];
		if ( ! empty( $custom_identifier ) ) {
			foreach ( $custom_identifier as $key => $value ) {
				$activeAttributes['options'][ 'woo_feed_identifier_' . sanitize_text_field( wp_unslash( $key ) ) ] = sanitize_text_field( $value[0] );
			}
		}

		return ! empty( $activeAttributes['options'] ) ? $activeAttributes : [];
	}

	/**
	 * Get Unit Price Attributes.
	 *
	 * @return array
	 */
	protected function getUnitPriceAttributes() {

		$unitPriceAttributes = [
			'optionGroup' => esc_html__( 'Unit Price (CTX Feed)', 'woo-feed' ),
			'options'     => [
				'unit_price_unit'         => esc_html__( 'Unit', 'woo-feed' ),
			],
		];

		/**
		 * Get Germanized for WooCommerce plugins unit attributes.
		 * @link https://wordpress.org/plugins/woocommerce-germanized/
		 */
		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			$wcUnitPriceAttributes = [
				'optionGroup' => esc_html__( 'Unit Price (WooCommerce Germanized)', 'woo-feed' ),
				'options'     => [
					'wc_germanized_unit_price_measure'      => esc_html__( 'Unit Price Measure', 'woo-feed' ),
					'wc_germanized_unit_price_base_measure' => esc_html__( 'Unit Price Base Measure', 'woo-feed' ),
					'wc_germanized_gtin'                    => esc_html__( 'GTIN', 'woo-feed' ),
					'wc_germanized_mpn'                     => esc_html__( 'MPN', 'woo-feed' ),
				],
			];
			$unitPriceAttributes   += $wcUnitPriceAttributes;
		}

		return $unitPriceAttributes;
	}

	/**
	 * Get installed SEO plugin attributes.
	 *
	 * @return array
	 */
	protected function getSeoPluginAttributes() {

		$seoAttributes = [];
		/**
		 * Get Yoast SEO Plugin Attributes.
		 * @link https://wordpress.org/plugins/wordpress-seo/
		 */
		if ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'WPSEO_Premium' ) ) {

			$seoAttributes = [
				'optionGroup' => esc_html__( 'Yoast SEO', 'woo-feed' ),
				'options'     => [
					'yoast_wpseo_title'    => esc_html__( 'Title [Yoast SEO]', 'woo-feed' ),
					'yoast_wpseo_metadesc' => esc_html__( 'Description [Yoast SEO]', 'woo-feed' ),
					'yoast_canonical_url'  => esc_html__( 'Canonical URL [Yoast SEO]', 'woo-feed' ),
				],
			];

			/**
			 * Get Yoast WooCommerce SEO plugins Identifier Attributes.
			 * @link https://yoast.com/wordpress/plugins/yoast-woocommerce-seo/
			 */
			if ( class_exists( 'Yoast_WooCommerce_SEO' ) ) {
				$seoAttributes['options'] += [
					'yoast_gtin8'  => esc_html__( 'GTIN8 [Yoast SEO]', 'woo-feed' ),
					'yoast_gtin12' => esc_html__( 'GTIN12 / UPC [Yoast SEO]', 'woo-feed' ),
					'yoast_gtin13' => esc_html__( 'GTIN13 / EAN [Yoast SEO]', 'woo-feed' ),
					'yoast_gtin14' => esc_html__( 'GTIN14 / ITF-14 [Yoast SEO]', 'woo-feed' ),
					'yoast_isbn'   => esc_html__( 'ISBN [Yoast SEO]', 'woo-feed' ),
					'yoast_mpn'    => esc_html__( 'MPN [Yoast SEO]', 'woo-feed' ),
				];
			}
		}

		if ( class_exists( 'RankMath' ) || class_exists( 'RankMathPro' ) ) {
			$seoAttributes = [
				'optionGroup' => esc_html__( 'RANK MATH SEO', 'woo-feed' ),
				'options'     => [
					'rank_math_title'         => esc_html__( 'Title [RankMath SEO]', 'woo-feed' ),
					'rank_math_description'   => esc_html__( 'Description [RankMath SEO]', 'woo-feed' ),
					'rank_math_canonical_url' => esc_html__( 'Canonical URL [RankMath SEO]', 'woo-feed' )
				],
			];

			if ( class_exists( 'RankMathPro' ) ) {
				$seoAttributes['options'] += [ 'rank_math_gtin' => esc_html__( 'GTIN [RankMath Pro SEO]', 'woo-feed' ) ];
			}
		}

		if ( class_exists( 'AIOSEO\Plugin\AIOSEO' ) ) {
			$seoAttributes = [
				'optionGroup' => esc_html__( 'ALL IN ONE SEO', 'woo-feed' ),
				'options'     => [
					'_aioseop_title'         => esc_html__( 'Title [All in One SEO]', 'woo-feed' ),
					'_aioseop_description'   => esc_html__( 'Description [All in One SEO]', 'woo-feed' ),
					'_aioseop_canonical_url' => esc_html__( 'Canonical URL [All in One SEO]', 'woo-feed' ),
				],
			];
		}

		return $seoAttributes;
	}

	/**
	 * Get Product Global Attributes.
	 * @retun array
	 */
	protected function getGlobalAttributes() {
		$taxonomies = woo_feed_get_cached_data( 'getAttributeTaxonomies' );
		if ( false === $taxonomies ) {
			// Load the main attributes
			$globalAttributes = wc_get_attribute_taxonomy_labels();
			if ( count( $globalAttributes ) ) {
				foreach ( $globalAttributes as $key => $value ) {
					$taxonomies[ Woo_Feed_Products_v3::PRODUCT_ATTRIBUTE_PREFIX . 'pa_' . $key ] = $value;
				}
			}
			woo_feed_set_cache_data( 'getAttributeTaxonomies', $taxonomies );
		}

		return [
			'optionGroup' => esc_html__( 'Product Attributes', 'woo-feed' ),
			'options'     => $taxonomies,
		];
	}

	/**
	 * Get Product Custom Attributes.
	 * @retun array
	 */
	protected function getCustomAttributes() {
		$attributes = woo_feed_get_cached_data( 'woo_feed_dropdown_product_custom_attributes' );
		if ( false === $attributes ) {
			// Get Variation Attributes
			$attributes = $this->queryVariationsAttributes();
			// Get Product Custom Attributes
			$attributes += $this->queryCustomAttributes();

			woo_feed_set_cache_data( 'woo_feed_dropdown_product_custom_attributes', $attributes );
		}

		return [
			'optionGroup' => esc_html__( 'Product Custom Attributes', 'woo-feed' ),
			'options'     => $attributes,
		];
	}

	/**
	 * Get Variation Attributes
	 * Local attributes will be found on variation product meta only with attribute_ suffix
	 */
	protected function queryVariationsAttributes() {
		// Get Variation Attributes
		global $wpdb;
		$attributes = array();
		$sql        = "SELECT DISTINCT( meta_key ) FROM $wpdb->postmeta
			WHERE post_id IN (
			    SELECT ID FROM $wpdb->posts WHERE post_type = 'product_variation' -- local attributes will be found on variation product meta only with attribute_ suffix
			) AND (
			    meta_key LIKE 'attribute_%' -- include only product attributes from meta list
			    AND meta_key NOT LIKE 'attribute_pa_%'
			)";
		// sanitization ok
		$localAttributes = $wpdb->get_col( $sql ); // phpcs:ignore
		foreach ( $localAttributes as $localAttribute ) {
			$localAttribute                                                                 = str_replace( 'attribute_', '', $localAttribute );
			$attributes[ Woo_Feed_Products_v3::PRODUCT_ATTRIBUTE_PREFIX . $localAttribute ] = ucwords( str_replace( '-', ' ', $localAttribute ) );
		}

		return $attributes;
	}

	/**
	 * Get Product Custom Attributes
	 */
	protected function queryCustomAttributes() {
		global $wpdb;
		$attributes       = array();
		$sql              = 'SELECT meta.meta_id, meta.meta_key as name, meta.meta_value as type FROM ' . $wpdb->postmeta . ' AS meta, ' . $wpdb->posts . " AS posts WHERE meta.post_id = posts.id AND posts.post_type LIKE '%product%' AND meta.meta_key='_product_attributes';";
		$customAttributes = $wpdb->get_results( $sql ); // phpcs:ignore
		if ( ! empty( $customAttributes ) ) {
			foreach ( $customAttributes as $value ) {
				$product_attr = maybe_unserialize( $value->type );
				if ( is_array( $product_attr ) ) {
					foreach ( $product_attr as $key => $arr_value ) {
						if ( strpos( $key, 'pa_' ) === false ) {
							$attributes[ Woo_Feed_Products_v3::PRODUCT_ATTRIBUTE_PREFIX . $key ] = ucwords( str_replace( '-', ' ', $arr_value['name'] ) );
						}
					}
				}
			}
		}

		return $attributes;
	}

	/**
	 * Get All Taxonomy
	 *
	 * @return array
	 */
	protected function getAllTaxonomy() {
		$info = woo_feed_get_cached_data( 'woo_feed_dropdown_product_taxonomy' );
		if ( false === $info ) {
			$info = array();
			global $wp_taxonomies;
			$default_excludes = array(
				'product_type',
				'product_visibility',
				'product_cat',
				'product_tag',
				'product_shipping_class',
				'translation_priority',
			);

			/**
			 * Exclude Taxonomy from dropdown
			 *
			 * @param array $user_excludes
			 * @param array $default_excludes
			 */

			$user_excludes    = apply_filters( 'woo_feed_dropdown_exclude_taxonomy', null, $default_excludes );
			$default_excludes = ! empty( $user_excludes ) ? array_merge( $default_excludes, $user_excludes ) : $default_excludes;

			foreach ( get_object_taxonomies( 'product' ) as $value ) {
				$value = ! empty( $value ) ? trim( $value ) : $value;
				if ( in_array( $value, $default_excludes, true ) || strpos( $value, 'pa_' ) !== false ) {
					continue;
				}
				$label                                                          = isset( $wp_taxonomies[ $value ] ) ? $wp_taxonomies[ $value ]->label . " [$value]" : $value;
				$info[ Woo_Feed_Products_v3::PRODUCT_TAXONOMY_PREFIX . $value ] = $label;
			}

			woo_feed_set_cache_data( 'woo_feed_dropdown_product_taxonomy', $info );
		}

		return [
			'optionGroup' => esc_html__( 'Product Taxonomies', 'woo-feed' ),
			'options'     => $info,
		];
	}

	/**
	 * Get All Options
	 *
	 * @return array
	 */
	protected function getAllOptions() {
		$_wp_options     = wp_list_pluck( get_option( 'wpfp_option', array() ), 'option_name' );
		$_wp_options_val = str_replace( 'wf_option_', '', $_wp_options );
		$_wp_options     = array_combine( $_wp_options, $_wp_options_val );

		return [
			'optionGroup' => esc_html__( 'Options', 'woo-feed' ),
			'options'     => $_wp_options,
		];
	}

	/**
	 * Get Category Mappings
	 * @return array
	 */
	protected function getCategoryMappedAttributes() {
		global $wpdb;
		// Load Custom Category Mapped Attributes
		$info = array();
		// query cached and escaped
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", Woo_Feed_Products_v3::PRODUCT_CATEGORY_MAPPING_PREFIX . '%' ) );  // phpcs:ignore
		if ( count( $data ) ) {
			foreach ( $data as $value ) {
				$opts                        = maybe_unserialize( $value->option_value );
				$opts                        = maybe_unserialize( $opts );
				$info[ $value->option_name ] = is_array( $opts ) && isset( $opts['mappingname'] ) ? $opts['mappingname'] : str_replace( 'wf_cmapping_',
					'',
					$value->option_name );
			}
		}

		return [
			'optionGroup' => esc_html__( 'Category Mapping', 'woo-feed' ),
			'options'     => $info,
		];
	}

	/**
	 * Get Advance Custom Field (ACF) field list
	 *
	 *
	 * @return Array
	 */
	protected function getACFAttributes() {
		$options = [];
		if ( class_exists( 'ACF' ) ) {
			$acf_fields = woo_feed_get_cached_data( 'acf_field_list' );
			if ( false === $acf_fields && function_exists( 'acf_get_field_groups' ) ) {
				$field_groups = acf_get_field_groups();
				foreach ( $field_groups as $group ) {
					// DO NOT USE here: $fields = acf_get_fields($group['key']);
					// because it causes repeater field bugs and returns "trashed" fields
					$fields = get_posts( array(
						'posts_per_page'         => - 1,
						'post_type'              => 'acf-field',
						'orderby'                => 'menu_order',
						'order'                  => 'ASC',
						'suppress_filters'       => true, // DO NOT allow WPML to modify the query
						'post_parent'            => $group['ID'],
						'post_status'            => 'any',
						'update_post_meta_cache' => false,
					) );
					foreach ( $fields as $field ) {
						$options[ 'acf_fields_' . $field->post_name ] = $field->post_title;
					}
				}

				woo_feed_set_cache_data( 'acf_field_list', $options );
			}
		}

		return [
			'optionGroup' => esc_html__( 'Advance Custom Fields (ACF)', 'woo-feed' ),
			'options'     => $options,
		];
	}

	/**
	 * Get All Custom Attributes
	 *
	 * @return array
	 */
	protected function getProductMetaKeyAttributes() {
		$info = woo_feed_get_cached_data( 'woo_feed_dropdown_meta_keys' );
		if ( false === $info ) {
			global $wpdb;
			$info = [];
			// Load the main attributes.

			$default_exclude_keys = [
				// WP internals.
				'_edit_lock',
				'_wp_old_slug',
				'_edit_last',
				'_wp_old_date',
				// WC internals.
				'_downloadable_files',
				'_sku',
				'_weight',
				'_width',
				'_height',
				'_length',
				'_file_path',
				'_file_paths',
				'_default_attributes',
				'_product_attributes',
				'_children',
				'_variation_description',
				// ignore variation description, engine will get child product description from WC CRUD WC_Product::get_description().
				// Plugin Data.
				'_wpcom_is_markdown',
				// JetPack Meta.
				'_yith_wcpb_bundle_data',
				// Yith product bundle data.
				'_et_builder_version',
				// Divi builder data.
				'_vc_post_settings',
				// Visual Composer (WP Bakery) data.
				'_enable_sidebar',
				'frs_woo_product_tabs',
				// WooCommerce Custom Product Tabs http://www.skyverge.com/.
			];

			/**
			 * Exclude meta keys from dropdown
			 *
			 * @param array $exclude meta keys to exclude.
			 * @param array $default_exclude_keys Exclude keys by default.
			 */
			$user_exclude = apply_filters( 'woo_feed_dropdown_exclude_meta_keys', null, $default_exclude_keys );

			if ( is_array( $user_exclude ) && ! empty( $user_exclude ) ) {
				$user_exclude         = esc_sql( $user_exclude );
				$default_exclude_keys = array_merge( $default_exclude_keys, $user_exclude );
			}

			$default_exclude_keys = array_map( 'esc_sql', $default_exclude_keys );
			$exclude_keys         = '\'' . implode( '\', \'', $default_exclude_keys ) . '\'';

			$default_exclude_key_patterns = [
				'%_et_pb_%', // Divi builder data
				'attribute_%', // Exclude product attributes from meta list
				'_yoast_wpseo_%', // Yoast SEO Data
				'_acf-%', // ACF duplicate fields
				'_aioseop_%', // All In One SEO Pack Data
				'_oembed%', // exclude oEmbed cache meta
				'_wpml_%', // wpml metas
				'_oh_add_script_%', // SOGO Add Script to Individual Pages Header Footer.
			];

			/**
			 * Exclude meta key patterns from dropdown
			 *
			 * @param array $exclude meta keys to exclude.
			 * @param array $default_exclude_key_patterns Exclude keys by default.
			 */
			$user_exclude_patterns = apply_filters( 'woo_feed_dropdown_exclude_meta_keys_pattern', null, $default_exclude_key_patterns );
			if ( is_array( $user_exclude_patterns ) && ! empty( $user_exclude_patterns ) ) {
				$default_exclude_key_patterns = array_merge( $default_exclude_key_patterns, $user_exclude_patterns );
			}
			$exclude_key_patterns = '';
			foreach ( $default_exclude_key_patterns as $pattern ) {
				$exclude_key_patterns .= $wpdb->prepare( ' AND meta_key NOT LIKE %s', $pattern );
			}

			$sql = "SELECT DISTINCT( meta_key ) FROM $wpdb->postmeta WHERE 1=1 AND post_id IN ( SELECT ID FROM $wpdb->posts WHERE post_type = 'product' OR post_type = 'product_variation' ) AND ( meta_key NOT IN ( $exclude_keys ) $exclude_key_patterns )";

			// sql escaped, cached
			$data = $wpdb->get_results( $sql ); // phpcs:ignore

			if ( count( $data ) ) {
				foreach ( $data as $value ) {
					//TODO Remove ACF Fields
					$info[ Woo_Feed_Products_v3::POST_META_PREFIX . $value->meta_key ] = $value->meta_key;
				}
			}
			woo_feed_set_cache_data( 'woo_feed_dropdown_meta_keys', $info );
		}

		return [
			'optionGroup' => esc_html__( 'Custom Fields & Post Metas', 'woo-feed' ),
			'options'     => $info,
		];
	}

	/**
	 * Get Dynamic Attribute List
	 *
	 * @return array
	 */
	protected function getDynamicAttributes() {
		global $wpdb;

		// Load Custom Category Mapped Attributes
		$info = array();
		// query escaped and cached
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", Woo_Feed_Products_v3_Pro::PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX . '%' ) ); // phpcs:ignore
		if ( count( $data ) ) {
			foreach ( $data as $key => $value ) {
				$opts                        = maybe_unserialize( $value->option_value );
				$opts                        = maybe_unserialize( $opts );
				$info[ $value->option_name ] = is_array( $opts ) && isset( $opts['wfDAttributeName'] ) ? $opts['wfDAttributeName'] : str_replace(
					'wf_dattribute_',
					'',
					$value->option_name
				);
			}
		}

		return [
			'optionGroup' => esc_html__( 'Dynamic Attributes', 'woo-feed' ),
			'options'     => $info,
		];
	}

	/**
	 * Get Attribute Mappings
	 *
	 * @return array
	 */
	protected function getCustomMappedAttributes() {
		global $wpdb;
		// Load Custom Category Mapped Attributes
		$info = [];
		// query cached and escaped
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", Woo_Feed_Products_v3_Pro::PRODUCT_ATTRIBUTE_MAPPING_PREFIX . '%' ) );  // phpcs:ignore
		if ( count( $data ) ) {
			foreach ( $data as $value ) {
				$opts                        = maybe_unserialize( $value->option_value );
				$info[ $value->option_name ] = is_array( $opts ) && isset( $opts['name'] ) ? $opts['name'] : str_replace( Woo_Feed_Products_v3_Pro::PRODUCT_ATTRIBUTE_MAPPING_PREFIX, '', $value->option_name );
			}
		}

		return [
			'optionGroup' => esc_html__( 'Attribute Mappings', 'woo-feed' ),
			'options'     => $info,
		];
	}

	/**
	 * @return array
	 */
	protected function getMultiLanguageAttributes() {
		$attributes = [];
		if ( class_exists( 'SitePress' ) ) {
			$attributes = [
				'optionGroup' => esc_html__( 'WPML Attributes', 'woo-feed' ),
				'options'     => [
					'parent_id' => esc_html__( 'Parent Product ID', 'woo-feed' )
				]
			];
		}

		return $attributes;
	}

	/**
	 * @return array
	 */
	protected function getMultiVendorAttributes() {
		$attributes = [];
		if ( function_exists('woo_feed_is_multi_vendor') && woo_feed_is_multi_vendor() ) {
			$attributes = [
				'optionGroup' => esc_html__( 'Multi Vendor Attributes', 'woo-feed' ),
				'options'     => [
					'vendor_store_name' => esc_html__( 'Vendor Store Name', 'woo-feed' )
				]
			];
		}

		return $attributes;
	}
}
