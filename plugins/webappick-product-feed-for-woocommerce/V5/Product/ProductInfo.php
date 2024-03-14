<?php

namespace CTXFeed\V5\Product;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Price\PriceFactory;
use CTXFeed\V5\Shipping\ShippingFactory;
use CTXFeed\V5\Tax\TaxFactory;
use CTXFeed\V5\Utility\Settings;
use Exception;
use RankMath\Helper;
use WPSEO_Meta;
use WPSEO_Option_Titles;
use WPSEO_Primary_Term;
use CTXFeed\V5\Common\Helper as CTX_Helper;

class ProductInfo {

	/**
	 * @var \WC_Product|\WC_Product_Variation|\WC_Product_Simple|\WC_Product_Grouped|\WC_Product_External|\WC_Product_Variable
	 */
	private $product;

	/**
	 * @var \WC_Product_Variable
	 */
	private $parent_product;

	/**
	 * @var \CTXFeed\V5\Utility\Config
	 */
	private $config;

	/**
	 * ProductInfos constructor.
	 *
	 * @param \WC_Product $product Product object.
	 * @param \CTXFeed\V5\Utility\Config $config Config object.
	 * @param \WC_Product_Variable $parent_product Parent product object.
	 * @param array $children Optional. Array of product variations.
	 */
	public function __construct( $product, $config, $parent_product = null, $children = array() ) {
		$this->product        = $product;
		$this->parent_product = $parent_product;
		$this->config         = $config;
	}

	/**
	 * Get product id.
	 *
	 * @return int|null
	 * @since 8.0.0
	 */
	public function id() {
		return apply_filters( 'woo_feed_filter_product_id', $this->product->get_id(), $this->product, $this->config );
	}

	/**
	 * Get original product id for translated products, when WPML is active.
	 * If WPML is not installed, then return the same id.
	 * If WPML is installed, then return the original product id.
	 *
	 * This attribute is only applicable for WPML.
	 *
	 * @return int|null
	 * @since 8.0.0
	 */
	public function parent_id() {
		return apply_filters( 'woo_feed_original_post_id', $this->product->get_id(), $this->product, $this->config );
	}

	/**
	 * Get product title.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function title() {
		$title = CommonHelper::clean_content( $this->product->get_name() );

		// Add all available variation attributes to variation title.
		if ( $this->product->is_type( 'variation' ) && ! empty( $this->product->get_attributes() ) && $this->parent_product ) {
			$title = $this->parent_product->get_title();
			/**
			 * Translate press plugin support.
			 */
			$attributes = [];
			foreach ( $this->product->get_attributes() as $slug => $value ) {
				$attribute = $this->product->get_attribute( $slug );
				if ( ! empty( $attribute ) ) {
					$attributes[ $slug ] = $attribute;
				}
			}
			// set variation attributes with separator.
			$separator            = apply_filters( 'woo_feed_attribute_separator', ' , ', $this->config, $this->product );
			$variation_attributes = implode( $separator, $attributes );

			// get product title with variation attributes merger.
			$product_title_and_attribute_merger = apply_filters( "woo_feed_product_title_and_attributes_merger", " - ", $this->product, $this->config );

			/**
			 * Translate press plugin support.
			 *
			 * @since 8.0.0
			 * @package CTXFeed
			 * @subpackage CTXFeed/V5/Product
			 * @see https://webappick.atlassian.net/browse/CBT-324
			 * @see https://webappick.atlassian.net/browse/CBT-304
			 */
			if ( class_exists( 'TRP_Translate_Press' ) ) {
				$title = apply_filters( 'woo_feed_filter_product_title', $title, $this->product, $this->config );
				// Merge product title with variation attributes.
				if ( ! empty( $variation_attributes ) ) {
					$title .= $product_title_and_attribute_merger . $variation_attributes;
				}

				return $title;
			} else {
				// Merge product title with variation attributes.
				if ( ! empty( $variation_attributes ) ) {
					$title .= $product_title_and_attribute_merger . $variation_attributes;
				}
			}
		}

		return apply_filters( 'woo_feed_filter_product_title', $title, $this->product, $this->config );

	}

	/**
	 * Get product parent title.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function parent_title() {
		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$name = CommonHelper::clean_content( $this->parent_product->get_name() );
		} else {
			$name = $this->title();
		}

		return apply_filters( 'woo_feed_filter_product_parent_title', $name, $this->product, $this->config );
	}

	/**
	 * Get product description.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function description() {

		/**
		 * Translate press plugin support.
		 *
		 * @since 8.0.0
		 * @package CTXFeed
		 * @subpackage CTXFeed/V5/Product
		 * @see https://webappick.atlassian.net/browse/CBT-304
		 */
		if ( class_exists( 'TRP_Translate_Press' ) ) {
			$description = $this->product->get_description();
			// For variation product.
			if ( ! is_null( $this->parent_product ) && $this->product->is_type( 'variation' ) && empty( $description ) ) {
				$description = $this->parent_product->get_description();
			}
		} else {
			$description = CommonHelper::clean_content( $this->product->get_description() );
			// For variation product.
			if ( ! is_null( $this->parent_product ) && $this->product->is_type( 'variation' ) && empty( $description ) ) {
				$description = CommonHelper::clean_content( $this->parent_product->get_description() );
			}
		}

		return apply_filters( 'woo_feed_filter_product_description', $description, $this->product, $this->config, $this->parent_product );
	}

	/**
	 * Get product description with HTML.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function description_with_html() {
		$description = $this->product->get_description();

		if ( empty( $description ) ) {
			$description = $this->product->get_short_description();
		}

		// For variation product.
		if ( ! is_null( $this->parent_product ) && $this->product->is_type( 'variation' ) ) {
			$description = $this->parent_product->get_description();

			if ( empty( $description ) ) {
				$description = $this->parent_product->get_short_description();
			}
		}

		// Remove spacial characters.
		$description = wp_check_invalid_utf8( wp_specialchars_decode( $description ), true );

		return apply_filters( 'woo_feed_filter_product_description_with_html', $description, $this->product, $this->config );
	}

	/**
	 * Get product short description.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function short_description() {
		$description = CommonHelper::clean_content( $this->product->get_short_description() );


		// For variation product.
		if ( empty( $description ) && ! is_null( $this->parent_product ) && $this->product->is_type( 'variation' ) ) {
			$description = CommonHelper::clean_content( $this->parent_product->get_short_description() );
		}

		return apply_filters( 'woo_feed_filter_product_short_description', $description, $this->product, $this->config );
	}

	/**
	 * Get product primary category name.
	 * If the category is "Clothing > Shirt > T-shirt", then it will return "Clothing".
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function primary_category() {
		$primary_category = '';
		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$categories = $this->parent_product->get_category_ids();
		} else {
			$categories = $this->product->get_category_ids();
		}

		if ( is_array( $categories ) && ! empty( $categories ) ) {
//			$categories     = array_reverse( $categories );
			sort( $categories );
			$primary_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$primary_category = ( $primary_category ) ? $primary_category->name : $primary_category;
			//$primary_category = $primary_category->name;

		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$primary_category = ( $default_category ) ? $default_category->name : $primary_category;
			//$primary_category = $default_category->name;
		}

		return apply_filters( 'woo_feed_filter_product_primary_category', $primary_category, $this->product, $this->config );
	}

	/**
	 * Get product categories.
	 *
	 * @return array
	 * @since      1.0.0
	 * @deprecated 8.0.0
	 */
	public function product_type() {
		// Notify that this function is deprecated
		// This message is generating huge amount of error log, so we are commenting it out.
		// _deprecated_function( __FUNCTION__, '8.0.0', 'categories' );

		// Optionally, you can still call the new function from the old one
		return $this->categories();
	}

	/**
	 * Get product categories.
	 *
	 * @return array
	 * @since 8.0.0
	 */
	public function categories() {

		$id = $this->product->get_id();

		if ( $this->product->is_type( 'variation' ) ) {
			$id = $this->product->get_parent_id();
		}

		// Get child categories of the current parent ID
		//$categories = wp_strip_all_tags( wc_get_product_category_list( $id, ' > ' ) );

		$categories = '';
		$term_list  = get_the_terms( $id, 'product_cat' );

		$separator = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $this->product );

		if ( is_array( $term_list ) ) {
			$col = array_column( $term_list, "parent" );
			array_multisort( $col, SORT_ASC, $term_list );
			$term_list  = array_column( $term_list, "name" );
			$categories = implode( $separator, $term_list );

		}

		return apply_filters( 'woo_feed_filter_product_categories', $categories, $this->product, $this->config );
	}

	/**
	 * Format term ids to names.
	 *
	 * @param array $term_ids Term IDs to format.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return string
	 * @since 3.1.0
	 */
	public function format_term_ids( $term_ids, $taxonomy, $separator ) {
		$term_ids = wp_parse_id_list( $term_ids );


		if ( ! count( $term_ids ) ) {
			return '';
		}

		$formatted_terms = array();

		if ( is_taxonomy_hierarchical( $taxonomy ) ) {
			foreach ( $term_ids as $term_id ) {
				$formatted_term = array();
				$ancestor_ids   = array_reverse( get_ancestors( $term_id, $taxonomy ) );
				foreach ( $ancestor_ids as $ancestor_id ) {
					$term = get_term( $ancestor_id, $taxonomy );
					if ( $term && ! is_wp_error( $term ) ) {
						$formatted_term[] = $term->name;
					}
				}

				$term = get_term( $term_id, $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$formatted_term[] = $term->name;
				}

				$formatted_terms[] = implode( $separator, $formatted_term );
			}
		} else {
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id, $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$formatted_terms[] = $term->name;
				}
			}
		}

		$formatted_value = '';

		if ( count( $formatted_terms ) == 1 ) {
			$formatted_value = $formatted_terms[0];
		} else {
			foreach ( $formatted_terms as $terms ) {
				// Ensure that the item is a string
				if ( is_string( $terms ) && strlen( $terms ) > strlen( $formatted_value ) ) {
					$formatted_value = $terms;
				}
			}
		}

		return $formatted_value;

	}

	/**
	 * Get product primary category id.
	 * If the category is "Clothing > Shirt > T-shirt", then it will return term_id of "Clothing".
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function primary_category_id() {
		$primary_category = '';

		$categories = $this->product->get_category_ids();

		if ( ! empty( $categories ) ) {
			$primary_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$primary_category = $primary_category->term_id;
		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$primary_category = $default_category->term_id;
		}

		return apply_filters( 'woo_feed_filter_product_primary_category_id', $primary_category, $this->product, $this->config );
	}

	/**
	 * Get product child category name.
	 * If the category is "Clothing > Shirt > T-shirt", then it will return "T-shirt".
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function child_category() {
		$child_category = '';

		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$categories = $this->parent_product->get_category_ids();
		} else {
			$categories = $this->product->get_category_ids();
		}


		if ( ! empty( $categories ) ) {
			sort( $categories );
			$categories     = array_reverse( $categories );
			$child_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$child_category = ( $child_category ) ? $child_category->name : $child_category;
			//$child_category = $child_category->name;
		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$child_category = ( $default_category ) ? $default_category->name : $child_category;
			//$child_category   = $default_category->name;
		}

		return apply_filters( 'woo_feed_filter_product_child_category', $child_category, $this->product, $this->config );
	}

	/**
	 * Get product child category id.
	 * If the category is "Clothing > Shirt > T-shirt", then it will return term_id of "T-shirt".
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function child_category_id() {
		$child_category = '';

		$categories = $this->product->get_category_ids();

		if ( ! empty( $categories ) ) {
			$categories     = array_reverse( $categories );
			$child_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$child_category = $child_category->term_id;
		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$child_category   = $default_category->term_id;
		}

		return apply_filters( 'woo_feed_filter_product_child_category_id', $child_category, $this->product, $this->config );
	}

	/**
	 * Get product status.
	 *
	 * @return array
	 * @since      8.0.0
	 */
	public function product_status() {
		$product_status = $this->product->get_status();

		return apply_filters( 'woo_feed_filter_product_status', $product_status, $this->product, $this->config );
	}

	/**
	 * Get product featured status.
	 *
	 * @return array
	 * @since      8.0.0
	 */
	public function featured_status() {
		$featured_status = 'no';

		if ( $this->product->is_featured() ) {
			$featured_status = 'yes';
		}

		return apply_filters( 'woo_feed_filter_featured_status', $featured_status, $this->product, $this->config );
	}

	/**
	 * Get product full category.
	 *
	 * @return mixed|void
	 */
	public function product_full_cat() {

//		$id = $this->product->get_id();
//		if ( $this->product->is_type( 'variation' ) ) {
//			$id = $this->product->get_parent_id();
//		}
//
//		$separator = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $this->product );
//
//		$product_type = wp_strip_all_tags( wc_get_product_category_list( $id, $separator ) );

		$term_ids = $this->product->get_category_ids();

		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$term_ids = $this->parent_product->get_category_ids();
		}

		$separator = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $this->product );

		$product_type = $this->format_term_ids( $term_ids, 'product_cat', $separator );

		return apply_filters( 'woo_feed_filter_product_local_category', htmlspecialchars_decode( $product_type ), $this->product, $this->config );
	}

	/**
	 * Get product canonical permalink.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function canonical_link() {
		$permalink = $this->parent_link();

		return apply_filters( 'woo_feed_filter_product_canonical_link', $permalink, $this->product, $this->config );
	}

	/**
	 * Get product parent permalink.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function parent_link() {
		$permalink = $this->product->get_permalink();

		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$permalink = $this->parent_product->get_permalink();
		}

		// Add UTM parameter.
		if ( $this->config->get_campaign_parameters() ) {
			$permalink = CommonHelper::add_utm_parameter( $this->config->get_campaign_parameters(), $permalink );
		}

		return apply_filters( 'woo_feed_filter_product_parent_link', $permalink, $this->product, $this->config );
	}

	/**
	 * Get external product URL.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function ex_link() {
		$external_product_url = '';

		if ( $this->product->is_type( 'external' ) ) {
			$external_product_url = $this->product->get_product_url();
		}

		return apply_filters( 'woo_feed_filter_product_ex_link', $external_product_url, $this->product, $this->config );
	}

	/**
	 * Get product main image url.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	/*public function image() {
		$image_link = wp_get_attachment_url( $this->product->get_image_id() );
		$image_link = CTX_Helper::woo_feed_get_formatted_url( $image_link );

		error_log( print_r( ['$image_link'=>$image_link], true ) );
		return apply_filters( 'woo_feed_filter_product_image', $image_link, $this->product, $this->config );
	}*/
	public function image() {
		$image = '';
		if ( $this->product->is_type( 'variation' ) ) {
			// Variation product type
			if ( has_post_thumbnail( $this->product->get_id() ) ) {
				$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $this->product->get_id() ), 'single-post-thumbnail' );
				$image    = CTX_Helper::woo_feed_get_formatted_url( $getImage[0] );
			} elseif ( has_post_thumbnail( $this->product->get_parent_id() ) ) {
				$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $this->product->get_parent_id() ), 'single-post-thumbnail' );
				$image    = CTX_Helper::woo_feed_get_formatted_url( $getImage[0] );
			}
		} elseif ( has_post_thumbnail( $this->product->get_id() ) ) { // All product type except variation
			$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $this->product->get_id() ), 'single-post-thumbnail' );
			$image    = isset( $getImage[0] ) ? CTX_Helper::woo_feed_get_formatted_url( $getImage[0] ) : '';
		}

		return apply_filters( 'woo_feed_filter_product_image', $image, $this->product, $this->config );
	}

	/**
	 * Get product featured image url.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	/*public function feature_image() {
		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$image_link = wp_get_attachment_url( $this->parent_product->get_image_id() );
			if ( empty( $image_link ) ) {
				$image_link = wp_get_attachment_url( $this->product->get_image_id() );
			}
		} else {
			$image_link = wp_get_attachment_url( $this->product->get_image_id() );
		}

		return apply_filters( 'woo_feed_filter_product_feature_image', $image_link, $this->product, $this->config );
	}*/
	public function feature_image() {
		$id = $this->product->get_id();
		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$id = $this->product->get_parent_id();
		}

		$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
		$image    = isset( $getImage[0] ) ? CTX_Helper::woo_feed_get_formatted_url( $getImage[0] ) : '';

		return apply_filters( 'woo_feed_filter_product_feature_image', $image, $this->product, $this->config );
	}

	/**
	 * Get product condition.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function condition() {
		return apply_filters( 'woo_feed_product_condition', 'new', $this->product, $this->config );
	}

	/**
	 * Get a product type.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function type() {
		return apply_filters( 'woo_feed_filter_product_type', $this->product->get_type(), $this->product, $this->config );
	}

	/**
	 * Get product is a bundle.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function is_bundle() {
		$is_bundle = 'no';
		$type      = $this->product->get_type();

		if ( in_array( $type, array( 'bundle', 'bundled', 'yith_bundle', 'woosb' ), true ) ) {
			$is_bundle = 'yes';
		}

		return apply_filters( 'woo_feed_filter_product_is_bundle', $is_bundle, $this->product, $this->config );
	}

	/**
	 * Get product is a multipack.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function multipack() {
		$is_multipack = '';

		if ( $this->product->is_type( 'grouped' ) && ! empty( $this->product->get_children() ) ) {
			$is_multipack = count( $this->product->get_children() );
		}

		return apply_filters( 'woo_feed_filter_product_is_multipack', $is_multipack, $this->product, $this->config );
	}

	/**
	 * Get product visibility.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function visibility() {
		return apply_filters( 'woo_feed_filter_product_visibility', $this->product->get_catalog_visibility(), $this->product, $this->config );
	}

	/**
	 * Get product rating total.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function rating_total() {
		return apply_filters( 'woo_feed_filter_product_rating_total', $this->product->get_rating_count(), $this->product, $this->config );
	}

	/**
	 * Get product rating average.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function rating_average() {
		return apply_filters( 'woo_feed_filter_product_rating_average', $this->product->get_average_rating(), $this->product, $this->config );
	}

	/**
	 * Get product total sold.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function total_sold() {
		return apply_filters( 'woo_feed_filter_product_total_sold', $this->product->get_total_sales(), $this->product, $this->config );
	}

	/**
	 * Get comma separated product tags.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function tags() {
		$tags = '';

		if ( $this->product->is_type( 'variation' ) ) {
			$tags = wp_strip_all_tags( wc_get_product_tag_list( $this->product->get_parent_id(), ',', '', '' ) );
		} else {
			$tags = wp_strip_all_tags( wc_get_product_tag_list( $this->product->get_id(), ',', '', '' ) );
		}

		return apply_filters( 'woo_feed_filter_product_tags', $tags, $this->product, $this->config );
	}

	/**
	 * Get product group id.
	 *
	 * @return int
	 * @since      8.0.0
	 */
	public function item_group_id() {
		$id = $this->product->get_id();

		if ( $this->product->is_type( 'variation' ) ) {
			$id = $this->product->get_parent_id();
		}

		return apply_filters( 'woo_feed_filter_product_item_group_id', $id, $this->product, $this->config );
	}

	/**
	 * Get product SKU.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function sku() {
		return apply_filters( 'woo_feed_filter_product_sku', $this->product->get_sku(), $this->product, $this->config );
	}

	/**
	 * Get product SKU_ID.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function sku_id() {
		$sku    = $this->product->get_sku();
		$id     = $this->product->get_id();
		$sku_id = $id;

		if ( ! empty( $sku ) ) {
			$sku_id = $sku . '_' . $id;
		}

		return apply_filters( 'woo_feed_filter_product_sku_id', $sku_id, $this->product, $this->config );
	}

	/**
	 * Get product parent SKU.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function parent_sku() {
		$parent_sku = $this->product->get_sku();

		if ( $this->product->is_type( 'variation' ) && $this->parent_product ) {
			$parent_sku = $this->parent_product->get_sku();
		}

		return apply_filters( 'woo_feed_filter_product_parent_sku', $parent_sku, $this->product, $this->config );
	}

	/**
	 * Get product availability status.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function availability() {
		$status = $this->product->get_stock_status();

		if ( 'instock' === $status ) {
			$status = 'in stock';
		} elseif ( 'outofstock' === $status ) {
			$status = 'out of stock';
		} elseif ( 'onbackorder' === $status ) {
			$status = 'backorder';
		}

		return apply_filters( 'woo_feed_filter_product_availability', $status, $this->product, $this->config );
	}

	/**
	 * Get product availability date.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function availability_date() {
		$availability_date_settings = Settings::get( 'woo_feed_identifier' );
		$availability_date_status   = $availability_date_settings['availability_date'];

		if ( $availability_date_status === 'disable' || $this->product->get_stock_status() !== 'onbackorder' ) {
			return '';
		}

		$meta_field_name = 'woo_feed_availability_date';

		if ( $this->product->is_type( 'variation' ) ) {
			$meta_field_name .= '_var';
		}

		$availability_date = get_post_meta( $this->product->get_id(), $meta_field_name, true );

		$availability_date = gmdate( 'c', strtotime( $availability_date ) );

		return apply_filters( 'woo_feed_filter_product_availability_date', $availability_date, $this->product, $this->config );
	}

	/**
	 * Get product availability date.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function add_to_cart_link() {
		$cart_parameters  = array( 'add-to-cart' => $this->product->get_id() );
		$add_to_cart_link = $this->product->add_to_cart_url();
		$add_to_cart_link = add_query_arg( $cart_parameters, $add_to_cart_link );
		$utm_parameters   = $this->config->get_campaign_parameters();
		$add_to_cart_link = CommonHelper::add_utm_parameter( $utm_parameters, $add_to_cart_link );

		return apply_filters( 'woo_feed_filter_product_add_to_cart_link', $add_to_cart_link, $this->product, $this->config );
	}

	/**
	 * Get a permalink with UTM parameter.
	 *
	 * @return array
	 * @since      8.0.0
	 */
	public function link() {
		$permalink = $this->product->get_permalink();

		// Add UTM parameter.
		if ( $this->config->get_campaign_parameters() ) {
			$permalink = CommonHelper::add_utm_parameter( $this->config->get_campaign_parameters(), $permalink );
		}

		return apply_filters( 'woo_feed_filter_product_link', $permalink, $this->product, $this->config );
	}

	/**
	 * Get product quantity.
	 *
	 * @return int
	 * @since      8.0.0
	 */
	public function quantity() {// phpcs:ignore
		$quantity = $this->product->get_stock_quantity();
		$status   = $this->product->get_stock_status();

		if ( 'outofstock' === $status && $quantity === null ) {
			$quantity = 0;
		}

		if ( $this->product->is_type( 'variable' ) ) {

			$variable_qty_type = $this->config->variable_quantity;

			// Get the IDs of the product variations
			$variation_ids = $this->product->get_visible_children();

			// Use array_map to get the quantities of each variation

			$variations_quantities = array_map(
				static function ( $variation_id ) {
					$stock = get_post_meta( $variation_id, '_stock', true );

					if ( $stock === '' ) {
						$stock = 0;
					}

					return $stock;
				},
				$variation_ids
			);

			if ( empty( $variations_quantities ) ) {
				$quantity = 0;
			} elseif ( $variable_qty_type === 'min' ) {
				$quantity = min( $variations_quantities );
			} elseif ( $variable_qty_type === 'max' ) {
				$quantity = max( $variations_quantities );
			} elseif ( $variable_qty_type === 'first' ) {

				$quantity = $variations_quantities[0];
			} else {
				$quantity = array_sum( $variations_quantities );
			}
		}

		return apply_filters( 'woo_feed_filter_product_quantity', $quantity, $this->product, $this->config );
	}

	/**
	 * Get Store Currency.
	 *
	 * @return string
	 */
	public function currency() {
		$store_currency = get_option( 'woocommerce_currency' );

		return apply_filters( 'woo_feed_filter_product_currency', $store_currency, $this->product, $this->config );
	}

	/**
	 * Get Product Price.
	 *
	 * @return mixed
	 */
	public function current_price() {
		$price = PriceFactory::get( $this->product, $this->config )->price();

		return apply_filters( 'woo_feed_filter_product_price', $price, $this->product, $this->config, false, 'price' );
	}

	/**
	 * Get Product Regular Price.
	 *
	 * @return mixed
	 */
	public function price() {
		$regular_price = PriceFactory::get( $this->product, $this->config )->regular_price();

		return apply_filters( 'woo_feed_filter_product_regular_price', $regular_price, $this->product, $this->config, false, 'regular_price' );

	}

	/**
	 * Get Product Regular Price with Tax.
	 *
	 * @return mixed
	 */
	public function price_with_tax() {
		$regular_price = PriceFactory::get( $this->product, $this->config )->regular_price( true );

		return apply_filters( 'woo_feed_filter_product_regular_price_with_tax', $regular_price, $this->product, $this->config, true, 'regular_price' );
	}

	/**
	 * Get Product Price with Tax.
	 *
	 * @return mixed
	 */
	public function current_price_with_tax() {
		$price = PriceFactory::get( $this->product, $this->config )->price( true );

		return apply_filters( 'woo_feed_filter_product_price_with_tax', $price, $this->product, $this->config, true, 'price' );
	}

	/**
	 * Get Product Sale Price with Tax.
	 *
	 * @return mixed
	 */
	public function sale_price_with_tax() {
		$sale_price = PriceFactory::get( $this->product, $this->config )->sale_price( true );

		return apply_filters( 'woo_feed_filter_product_sale_price_with_tax', $sale_price, $this->product, $this->config, true, 'sale_price' );
	}

	/**
	 * Get Product Sale Price.
	 *
	 * @return mixed
	 */
	public function sale_price() {
		$sale_price = PriceFactory::get( $this->product, $this->config )->sale_price();

		return apply_filters( 'woo_feed_filter_product_sale_price', $sale_price, $this->product, $this->config, false, 'sale_price' );
	}

	/**
	 * Get Product Weight.
	 *
	 * @return string
	 */
	public function weight() {
		$weight      = '';
		$weight_unit = get_option( 'woocommerce_weight_unit' );

		if ( $this->product->get_weight() ) {
			$weight = $this->product->get_weight() . ' ' . $weight_unit;
		}

		return apply_filters( 'woo_feed_filter_product_weight', $weight, $this->product, $this->config );
	}

	/**
	 * Get Weight Unit.
	 *
	 * @return string
	 */
	public function weight_unit() {
		return apply_filters( 'woo_feed_filter_product_weight_unit', get_option( 'woocommerce_weight_unit' ), $this->product, $this->config );
	}

	/**
	 * Get Product Width.
	 *
	 * @return string
	 */
	public function width() {
		$width          = '';
		$dimension_unit = get_option( 'woocommerce_dimension_unit' );

		if ( $this->product->get_width() ) {
			$width = $this->product->get_width() . " $dimension_unit";
		}

		return apply_filters( 'woo_feed_filter_product_width', $width, $this->product, $this->config );
	}

	/**
	 * Get Product Height.
	 *
	 * @return string
	 */
	public function height() {
		$height = '';

		if ( $this->product->get_height() ) {
			$dimension_unit = get_option( 'woocommerce_dimension_unit' );
			$height         = $this->product->get_height() . " $dimension_unit";
		}

		return apply_filters( 'woo_feed_filter_product_height', $height, $this->product, $this->config );
	}

	/**
	 * Get Product Length.
	 *
	 * @return string
	 */
	public function length() {
		$length         = '';
		$dimension_unit = get_option( 'woocommerce_dimension_unit' );

		if ( $this->product->get_length() ) {
			$length = $this->product->get_length() . " $dimension_unit";
		}

		return apply_filters( 'woo_feed_filter_product_length', $length, $this->product, $this->config );
	}

	/**
	 * Get Product checkout template URL.
	 *
	 * @return mixed|void
	 */
	public function checkout_link_template() {
		if ( $this->config->get_feed_file_type() === 'xml' ) {
			$checkout_link_url = wc_get_checkout_url() . $this->product->get_id();
		} else {
			$checkout_link_url = wc_get_page_permalink( 'cart' ) . "?productId=" . $this->product->get_id();
		}

		return apply_filters( 'woo_feed_filter_product_checkout_link_url', $checkout_link_url, $this->product, $this->config );
	}

	/** Google Formatted Shipping info
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function shipping( $key = '' ) {
		try {
			return ( ShippingFactory::get( $this->product, $this->config ) )->get_shipping( $key );
//			return apply_filters( 'woo_feed_filter_product_shipping', $shipping, $this->product, $this->config );
		} catch ( Exception $e ) {

		}
	}

	/**
	 * Get Shipping Cost.
	 *
	 * @throws \Exception
	 */
	public function shipping_cost() {
		// Get config to which shipping price to return (first, highest or lowest)
		$shipping = ( ShippingFactory::get( $this->product, $this->config ) )->get_shipping_info();

		$price = "0";
		if ( ! empty( $shipping ) ) {
			if ( isset( $this->config->shipping_price ) ) {
				if ( 'highest' === $this->config->shipping_price ) {
					$price = max( wp_list_pluck( $shipping, 'price' ) );
				} elseif ( 'lowest' === $this->config->shipping_price ) {
					$price = min( wp_list_pluck( $shipping, 'price' ) );
				} else {
					$shipping_prices = wp_list_pluck( $shipping, 'price' );
					$price           = reset( $shipping_prices );
				}
			} else {
				$shipping_prices = wp_list_pluck( $shipping, 'price' );
				$price           = reset( $shipping_prices );
			}
		}

		return apply_filters( 'woo_feed_filter_product_shipping_cost', $price, $this->product, $this->config );
	}

	/**
	 * Get Product Shipping Class
	 *
	 * @return mixed
	 * @since 3.2.0
	 */
	public function shipping_class() {
		return apply_filters( 'woo_feed_filter_product_shipping_class', $this->product->get_shipping_class(), $this->product, $this->config );
	}

	/**
	 * Get author name.
	 *
	 * @return string
	 */
	public function author_name() {
		$post = get_post( $this->product->get_id() );

		return get_the_author_meta( 'user_login', $post->post_author );
	}

	/**
	 * Get Author Email.
	 *
	 * @return string
	 */
	public function author_email() {
		$post = get_post( $this->product->get_id() );

		return get_the_author_meta( 'user_email', $post->post_author );
	}

	/**
	 * Get Date Created.
	 *
	 * @return mixed|void
	 */
	public function date_created() {
		$date_created = gmdate( 'Y-m-d', strtotime( $this->product->get_date_created() ) );

		return apply_filters( 'woo_feed_filter_product_date_created', $date_created, $this->product, $this->config );
	}

	/**
	 * Get Date updated.
	 *
	 * @return mixed|void
	 */
	public function date_updated() {
		$date_updated = gmdate( 'Y-m-d', strtotime( $this->product->get_date_modified() ) );

		return apply_filters( 'woo_feed_filter_product_date_updated', $date_updated, $this->product, $this->config );
	}

	/** Get Google Sale Price effective date.
	 *
	 * @return string
	 */
	public function sale_price_effective_date() {
		$effective_date = '';
		$from           = $this->sale_price_sdate();
		$to             = $this->sale_price_edate();

		if ( ! empty( $from ) && ! empty( $to ) ) {
			$from = gmdate( 'c', strtotime( $from ) );
			$to   = gmdate( 'c', strtotime( $to ) );

			$effective_date = $from . '/' . $to;
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_effective_date', $effective_date, $this->product, $this->config );
	}

	/**
	 * Get Product Sale Price start date.
	 *
	 * @return string|null
	 */
	public function sale_price_sdate() {
		$sale_price_sdate = '';
		$start_date       = $this->product->get_date_on_sale_from();

		if ( $start_date instanceof \WC_DateTime ) {
			$sale_price_sdate = $start_date->date_i18n();
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_sdate', $sale_price_sdate, $this->product, $this->config );
	}

	/**
	 * Get Product Sale Price End Date.
	 *
	 * @return mixed|void
	 */
	public function sale_price_edate() {
		$sale_price_edate = '';
		$end_date         = $this->product->get_date_on_sale_to();

		if ( $end_date instanceof \WC_DateTime ) {
			$sale_price_edate = $end_date->date_i18n();
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_edate', $sale_price_edate, $this->product, $this->config );
	}

	/**
	 * Get product subscription period.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function subscription_period() {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return ProductHelper::get_product_meta( '_subscription_period', $this->product, $this->config );
		}

		return '';
	}

	/**
	 * Get product subscription period interval.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function subscription_period_interval() {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return ProductHelper::get_product_meta( '_subscription_period_interval', $this->product, $this->config );
		}

		return '';
	}

	/**
	 * Get product subscription amount.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function subscription_amount() {
		return $this->product->get_price();
	}

	/**
	 * Get product installment amount.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function installment_amount() {
		return $this->product->get_price();
	}

	/**
	 * Get product installment period.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function installment_months() {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return ProductHelper::get_product_meta( '_subscription_length', $this->product, $this->config );
		}

		return '';
	}

	/**
	 * Get product unit price measure.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function unit_price_measure() {
		$unit_price_measure = '';
		$identifiers        = Settings::get( 'woo_feed_identifier' );

		if ( 'enable' === $identifiers['unit_pricing_base_measure']
		     && 'enable' === $identifiers['unit_pricing_measure']
		     && 'enable' === $identifiers['unit']
		) {
			$unit               = ProductHelper::get_custom_field( 'woo_feed_unit', $this->product, $this->config );
			$unit_price_measure = ProductHelper::get_custom_field( 'woo_feed_unit_pricing_measure', $this->product, $this->config );

			if ( ! empty( $unit_price_measure ) ) {
				$unit_price_measure .= ' ' . $unit;
			}
		}

		// For WooCommerce Germanized Plugin
		// TODO:: Move to compatibility class
		if ( empty( $unit_price_measure ) && class_exists( 'WooCommerce_Germanized' ) ) {
			$unit               = ProductHelper::get_product_meta( '_unit', $this->product, $this->config );
			$unit_price_measure = ProductHelper::get_product_meta( '_unit_product', $this->product, $this->config );

			$unit_price_measure .= ' ' . $unit;
		}

		return apply_filters( 'woo_feed_filter_unit_price_measure', $unit_price_measure, $this->product, $this->config );
	}

	/**
	 * Get product unit price base measure.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function unit_price_base_measure() {
		$unit_price_base_measure = '';
		$identifiers             = Settings::get( 'woo_feed_identifier' );

		if ( 'enable' === $identifiers['unit_pricing_base_measure']
		     && 'enable' === $identifiers['unit_pricing_measure']
		     && 'enable' === $identifiers['unit']
		) {

			$unit                    = ProductHelper::get_custom_filed( 'woo_feed_unit', $this->product, $this->config );
			$unit_price_base_measure = ProductHelper::get_custom_filed( 'woo_feed_unit_pricing_base_measure', $this->product, $this->config );
			$unit_price_base_measure .= ' ' . $unit;
		}

		// For WooCommerce Germanized Plugin
		// TODO:: Move to compatibility class
		if ( empty( $unit_price_base_measure ) && class_exists( 'WooCommerce_Germanized' ) ) {
			$unit                    = ProductHelper::get_product_meta( '_unit', $this->product, $this->config );
			$unit_price_base_measure = ProductHelper::get_product_meta( '_unit_base', $this->product, $this->config );
			$unit_price_base_measure .= ' ' . $unit;
		}

		return apply_filters( 'woo_feed_filter_unit_price_base_measure', $unit_price_base_measure, $this->product, $this->config );
	}

	/**
	 * Get product GTIN for WooCommerce_Germanized plugin.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function wc_germanized_gtin() {
		$wc_germanized_gtin = '';
		$wc_germanized_gtin = ProductHelper::get_product_meta( '_ts_gtin', $this->product, $this->config );

		return apply_filters( 'woo_feed_filter_wc_germanized_gtin', $wc_germanized_gtin, $this->product, $this->config );
	}

	/**
	 * Get product unit price measure for WooCommerce_Germanized plugin.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function wc_germanized_unit_price_measure() {
		$wc_germanized_unit_price_measure = '';
		$wc_germanized_unit_price_measure = ProductHelper::get_product_meta( '_unit_product', $this->product, $this->config );

		return apply_filters( 'woo_feed_filter_wc_germanized_unit_price_measure', $wc_germanized_unit_price_measure, $this->product, $this->config );
	}

	/**
	 * Get product unit price base measure for WooCommerce_Germanized plugin.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function wc_germanized_unit_price_base_measure() {
		$wc_germanized_unit_price_base_measure = '';
		$wc_germanized_unit_price_base_measure = ProductHelper::get_product_meta( '_unit_base', $this->product, $this->config );

		return apply_filters( 'woo_feed_filter_wc_germanized_unit_price_base_measure', $wc_germanized_unit_price_base_measure, $this->product, $this->config );
	}

	/**
	 * Get product MPN for WooCommerce_Germanized plugin.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function wc_germanized_mpn() {
		$wc_germanized_mpn = '';
		$wc_germanized_mpn = ProductHelper::get_product_meta( '_ts_mpn', $this->product, $this->config );

		return apply_filters( 'woo_feed_filter_wc_germanized_mpn', $wc_germanized_mpn, $this->product, $this->config );
	}

	public function yoast_primary_category() {
		$primary_category = '';
		$product_id       = CommonHelper::parent_product_id( $this->product );
		$primary_term_id  = yoast_get_primary_term_id( 'product_cat', $product_id );
		$term             = get_term( $primary_term_id );
		if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
			$primary_category = $term->name;
		}

		return apply_filters( 'woo_feed_filter_product_yoast_primary_category', $primary_category, $this->product, $this->config );
	}

	# SEO Plugins

	/**
	 * Get product Yoast WP SEO title.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_wpseo_title() {

		$product_id = $this->product->get_id();

		if ( $this->product->is_type( 'variation' ) ) {
			$product_id = $this->product->get_parent_id();
		}

		$yoast_title = $this->title();

		if ( class_exists( 'WPSEO_Frontend' ) ) {
			$yoast_title = get_post_meta( $product_id, '_yoast_wpseo_title', true );

			// Get an instance of WPSEO_Replace_Vars
			$replace_vars = new \WPSEO_Replace_Vars;

			// Replace variables in the title
			$yoast_title = $replace_vars->replace( $yoast_title, get_post( $product_id ) );
		}

		return apply_filters( 'woo_feed_filter_product_yoast_wpseo_title', $yoast_title, $this->product, $this->config );
	}

	/**
	 * Get product Yoast WP SEO description.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_wpseo_metadesc() {

		$product_id = $this->product->get_id();

		if ( $this->product->is_type( 'variation' ) ) {
			$product_id = $this->product->get_parent_id();
		}

		$meta_description = $this->description();

		if ( class_exists( 'WPSEO_Frontend' ) ) {
			$meta_description = get_post_meta( $product_id, '_yoast_wpseo_metadesc', true );

			// Get an instance of WPSEO_Replace_Vars
			$replace_vars = new \WPSEO_Replace_Vars;

			// Replace variables in the title
			$meta_description = $replace_vars->replace( $meta_description, get_post( $product_id ) );
		}

		return apply_filters( 'woo_feed_filter_product_yoast_wpseo_metadesc', $meta_description, $this->product, $this->config );
	}

	# SEO Plugins

	/**
	 * Get product Yoast WP SEO canonical URL.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_canonical_url() {

		$product_id = $this->product->get_id();

		if ( $this->product->is_type( 'variation' ) ) {
			$product_id = $this->product->get_parent_id();
		}

		$yoast_canonical_url = get_post_meta( $product_id, '_yoast_wpseo_canonical', true );

		return apply_filters( 'woo_feed_filter_product_yoast_canonical_url', $yoast_canonical_url, $this->product, $this->config );
	}

	/**
	 * Get product Yoast WP SEO GTIN8.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_gtin8() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'gtin8', $this->product );

		return apply_filters( 'yoast_gtin8_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Yoast WP SEO GTIN12.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_gtin12() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'gtin12', $this->product );

		return apply_filters( 'yoast_gtin12_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Yoast WP SEO GTIN13.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_gtin13() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'gtin13', $this->product );

		return apply_filters( 'yoast_gtin13_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Yoast WP SEO GTIN14.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_gtin14() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'gtin14', $this->product );

		return apply_filters( 'yoast_gtin14_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Yoast WP SEO ISBN.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_isbn() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'isbn', $this->product );

		return apply_filters( 'yoast_isbn_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Yoast WP SEO MPN.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function yoast_mpn() {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'mpn', $this->product );

		return apply_filters( 'yoast_mpn_attribute_value', $yoast_gtin8_value, $this->product );
	}

	/**
	 * Get product Rank Math Title.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function rank_math_title() {
		$rank_title = '';
		if ( class_exists( 'RankMath' ) ) {
			$title = get_post_meta( $this->product->get_id(), 'rank_math_title', true );
			if ( empty( $title ) ) {
				$title_format = Helper::get_settings( "titles.pt_product_title" );
				$title_format = $title_format ? $title_format : '%title%';
				$sep          = Helper::get_settings( 'titles.title_separator' );

				$rank_title = str_replace( '%title%', $this->product->get_title(), $title_format );
				$rank_title = str_replace( '%sep%', $sep, $rank_title );
				$rank_title = str_replace( '%page%', '', $rank_title );
				$rank_title = str_replace( '%sitename%', get_bloginfo( 'name' ), $rank_title );
			} else {
				$rank_title = $title;
			}
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_title', $rank_title, $this->product, $this->config );
	}

	/**
	 * Get product Rank Math Description.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function rank_math_description() {
		$description = '';
		if ( class_exists( 'RankMath' ) ) {
			$description = get_post_meta( $this->product->get_id(), 'rank_math_description' );
			$desc_format = Helper::get_settings( "titles.pt_post_description" );

			if ( empty( $description ) ) {
				if ( ! empty( $desc_format ) && strpos( (string) $desc_format, 'excerpt' ) !== false ) {
					$description = str_replace( '%excerpt%', get_the_excerpt( $this->product->get_id() ), $desc_format );
				}

				// Get Variation Description
				if ( empty( $description ) && $this->product->is_type( 'variation' ) && $this->parent_product ) {
					$description = $this->parent_product->get_description();
				}
			}

			if ( is_array( $description ) ) {
				$description = reset( $description );
			}

			$description = CommonHelper::remove_shortcodes( $description );

			//strip tags and spacial characters
			$strip_description = CommonHelper::strip_all_tags( wp_specialchars_decode( $description ) );

			$description = ! empty( strlen( $strip_description ) ) && 0 < strlen( $strip_description ) ? $strip_description : $description;
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_description', $description, $this->product, $this->config );
	}

	/**
	 * Get product Rank Math Canonical URL.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function rank_math_canonical_url() {
		$canonical_url = '';

		if ( class_exists( 'RankMath' ) ) {
			$post_canonical_url = get_post_meta( $this->product->get_id(), 'rank_math_canonical_url' );

			if ( empty( $post_canonical_url ) ) {
				$canonical_url = get_the_permalink( $this->product->get_id() );
			} else {
				$canonical_url = $post_canonical_url;
			}

			if ( is_array( $canonical_url ) ) {
				$canonical_url = reset( $canonical_url );
			}
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_canonical_url', $canonical_url, $this->product, $this->config );
	}

	/**
	 * Get product Rank Math GTIN.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function rank_math_gtin() {
		$product_id          = CommonHelper::parent_product_id( $this->product );
		$rankmath_gtin_value = get_post_meta( $product_id, '_rank_math_gtin_code' );
		$rankmath_gtin_value = ! empty( $rankmath_gtin_value ) && is_array( $rankmath_gtin_value ) ? $rankmath_gtin_value[0] : '';

		return apply_filters( 'rankmath_gtin_attribute_value', $rankmath_gtin_value, $this->product, $this->config );
	}

	public function _aioseop_title() {
		$title = '';
		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {

			$post  = \AIOSEO\Plugin\Common\Models\Post::getPost( $this->product->get_id() );
			$title = ! empty( $post->title ) ? $post->title : aioseo()->meta->title->getPostTypeTitle( 'product' );
		}

		$title = ! empty( $title ) ? $title : $this->title();

		return apply_filters( 'woo_feed_filter_product_aioseop_title', $title, $this->product, $this->config );
	}

	public function _aioseop_description() {
		$description = '';

		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {

			$post        = \AIOSEO\Plugin\Common\Models\Post::getPost( $this->product->get_id() );
			$description = ! empty( $post->description ) ? $post->description : aioseo()->meta->description->getPostTypeDescription( 'product' );
		}

		if ( empty( $description ) ) {
			$description = $this->description();
		}

		return apply_filters( 'woo_feed_filter_product_aioseop_description', $description, $this->product, $this->config );
	}

	public function _aioseop_canonical_url() {
		$aioseop_canonical_url = '';
		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {
			$post                  = \AIOSEO\Plugin\Common\Models\Post::getPost( $this->product->get_id() );
			$aioseop_canonical_url = $post->canonical_url;
		}

		return apply_filters( 'woo_feed_filter_product_aioseop_canonical_url', $aioseop_canonical_url, $this->product, $this->config );
	}

	public function tax( $key = '' ) {


		$taxes = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$tax   = TaxFactory::get( $this->product, $this->config )->merchant_formatted_tax( $key );

		// GoogleTax and CustomTax class is available.
		// For others merchant use filter hook to modify value.
		return apply_filters( 'woo_feed_filter_product_tax', $tax, $this->product, $this->config, $taxes );
	}

	############# TAX #############

	public function tax_class() {
		return apply_filters( 'woo_feed_filter_product_tax_class', $this->product->get_tax_class(), $this->product, $this->config );
	}

	public function tax_status() {
		return apply_filters( 'woo_feed_filter_product_tax_status', $this->product->get_tax_status(), $this->product, $this->config );
	}

	public function tax_country() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$country  = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates   = array_values( $taxes[ $taxClass ] );
			$country = $rates[0]['country'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_country', $country, $this->product, $this->config, $taxes );
	}

	public function tax_state() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$state    = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates = array_values( $taxes[ $taxClass ] );
			$state = $rates[0]['state'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_state', $state, $this->product, $this->config, $taxes );
	}

	public function tax_postcode() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$postcode = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates    = array_values( $taxes[ $taxClass ] );
			$postcode = $rates[0]['postcode'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_postcode', $postcode, $this->product, $this->config, $taxes );
	}

	public function tax_city() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$city     = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates = array_values( $taxes[ $taxClass ] );
			$city  = $rates[0]['city'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_city', $city, $this->product, $this->config, $taxes );
	}

	public function tax_rate() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$rate     = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates = array_values( $taxes[ $taxClass ] );
			$rate  = $rates[0]['rate'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_rate', $rate, $this->product, $this->config, $taxes );
	}

	public function tax_label() {
		$taxes    = TaxFactory::get( $this->product, $this->config )->get_taxes();
		$taxClass = empty( $this->product->get_tax_class() ) ? 'standard-rate' : $this->product->get_tax_class();
		$label    = "";
		if ( isset( $taxes[ $taxClass ] ) && ! empty( $taxes[ $taxClass ] ) ) {
			$rates = array_values( $taxes[ $taxClass ] );
			$label = $rates[0]['label'];
		}

		return apply_filters( 'woo_feed_filter_product_tax_label', $label, $this->product, $this->config, $taxes );
	}

	/**
	 * Custom Template 2 images loop
	 *
	 * @return array
	 */
	public function custom_xml_images() {
		$separator = apply_filters( 'woo_feed_filter_category_separator', ' > ', $this->product, $this->config );
		$images    = $this->images( '', $separator );

		return $images;
	}

	# Custom XML Template

	/**
	 * Get product gallery image urls.
	 *
	 * @return string Comma separated image urls.
	 * @since 8.0.0
	 */

	public function images( $additional_image = '', $separator = ' , ' ) {
		$img_urls  = ProductHelper::get_product_gallery( $this->product );
		$separator = apply_filters( 'woo_feed_filter_category_separator', $separator, $this->product, $this->config );

		// Return Specific Additional Image URL
		if ( '' !== $additional_image ) {
			if ( array_key_exists( $additional_image, $img_urls ) ) {
				$images = $img_urls[ $additional_image ];
			} else {
				$images = '';
			}
		} else {
			if ( "idealo" === $this->config->get_feed_template() ) {
				$separator = ';';
			}

			$images = implode( $separator, array_filter( $img_urls ) );
		}

		return apply_filters( 'woo_feed_filter_product_images', $images, $this->product, $this->config );
	}

	/**
	 * Custom Template 2 attributes loop
	 *
	 * @return array
	 */
	public function custom_xml_attributes() {
		$getAttributes = $this->product->get_attributes();
		$attributes    = [];
		if ( ! empty( $getAttributes ) ) {
			foreach ( $getAttributes as $key => $attribute ) {
				$attributes[ $key ]['name']  = wc_attribute_label( $key );
				$attributes[ $key ]['value'] = $this->product->get_attribute( wc_attribute_label( $key ) );
			}
		}

		return $attributes;
	}

	public function custom_xml_shipping() {
	}

	public function custom_xml_tax() {
	}

	public function custom_xml_categories() {
		$output   = []; // Initialising
		$taxonomy = 'product_cat'; // Taxonomy for product category

		// Get the product categories terms ids in the product:
		$terms_ids = wp_get_post_terms( $this->product->get_id(), $taxonomy, array( 'fields' => 'ids' ) );

		// Loop though terms ids (product categories)
		foreach ( $terms_ids as $term_id ) {
			$term_names = []; // Initialising category array

			// Loop through product category ancestors
			foreach ( get_ancestors( $term_id, $taxonomy ) as $ancestor_id ) {
				// Add the ancestor's term names to the category array
				$term_names[] = get_term( $ancestor_id, $taxonomy )->name;
			}
			// Add the product category term name to the category array
			$term_names[] = get_term( $term_id, $taxonomy )->name;

			// Get category separator
			$separator = apply_filters( 'woo_feed_filter_category_separator', ' > ', $this->product, $this->config );

			// Add the formatted ancestors with the product category to main array
			$output[] = implode( $separator, $term_names );
		}

		return $output;
	}


}
