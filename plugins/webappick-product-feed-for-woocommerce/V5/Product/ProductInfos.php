<?php

namespace CTXFeed\V5\Product;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Price\PriceFactory;
use CTXFeed\V5\Tax\TaxFactory;
use CTXFeed\V5\Utility\Settings;
use RankMath\Helper;
use WC_DateTime;

/**
 * Class ProductInfos
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Product
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class ProductInfos {//phpcs:ignore

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
	 * @param \WC_Product                $product        Product object.
	 * @param \CTXFeed\V5\Utility\Config $config         Config object.
	 * @param \WC_Product_Variable       $parent_product Parent product object.
	 * @param array                      $children       Optional. Array of product variations.
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
	 * Get product parent id for translated products.
	 *
	 * @return int|null
	 * @since 8.0.0
	 */
	public function parent_id() {
		return apply_filters( 'woo_feed_original_post_id', $this->product->get_parent_id(), $this->product, $this->config );
	}

	/**
	 * Get product title.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function title() {
		$name = CommonHelper::clean_content( $this->product->get_name() );

		return apply_filters( 'woo_feed_filter_product_title', $name, $this->product, $this->config );
	}

	/**
	 * Get product parent title.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function parent_title() {
		$name = CommonHelper::clean_content( $this->product->get_title() );

		if ( $this->product->is_type( 'variation' ) ) {
			$name = CommonHelper::clean_content( $this->parent_product->get_title() );
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
		$description = CommonHelper::clean_content( $this->product->get_description() );

		// For variation product.
		if ( ! is_null( $this->parent_product ) && $this->product->is_type( 'variation' ) ) {
			$description = CommonHelper::clean_content( $this->parent_product->get_description() );
		}

		return apply_filters( 'woo_feed_filter_product_description', $description, $this->product, $this->config, $this->parent_product );
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
	 * Get product primary category name.
	 * If the category is "Clothing > Shirt > T-shirt", then it will return "Clothing".
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function primary_category() {
		$primary_category = '';

		$categories = $this->product->get_category_ids();

		if ( ! empty( $categories ) ) {
			$primary_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$primary_category = $primary_category->name;
		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$primary_category = $default_category->name;
		}

		return apply_filters( 'woo_feed_filter_product_primary_category', $primary_category, $this->product, $this->config );
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

		$categories = $this->product->get_category_ids();

		if ( ! empty( $categories ) ) {
			$categories     = array_reverse( $categories );
			$child_category = get_term_by( 'id', $categories[0], 'product_cat' );
			$child_category = $child_category->name;
		} else {
			// Get the default WooCommerce category
			$default_category = get_term_by( 'name', 'Uncategorized', 'product_cat' );
			$child_category   = $default_category->name;
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
		$categories = wp_strip_all_tags( wc_get_product_category_list( $id, ' > ' ) );

		return apply_filters( 'woo_feed_filter_product_categories', $categories, $this->product, $this->config );
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
		_deprecated_function( __FUNCTION__, '8.0.0', 'categories' );

		// Optionally, you can still call the new function from the old one
		return $this->categories();
	}

	/**
	 * Get product full category path.
	 *
	 * @return array
	 * @since 8.0.0
	 */
	public function full_category_path() {
		$full_category_path = '';

		$categories = $this->product->get_category_ids();

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$full_category_path .= get_term_by( 'id', $category, 'product_cat' )->name . ' > ';
			}

			$full_category_path = rtrim( $full_category_path, ' > ' );
		}

		return apply_filters( 'woo_feed_filter_product_full_category_path', $full_category_path, $this->product, $this->config );
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
	 * Get product parent permalink.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function parent_link() {
		$permalink = $this->product->get_permalink();

		if ( $this->product->is_type( 'variation' ) ) {
			$permalink = $this->parent_product->get_permalink();
		}

		// Add UTM parameter.
		if ( $this->config->get_campaign_parameters() ) {
			$permalink = CommonHelper::add_utm_parameter( $this->config->get_campaign_parameters(), $permalink );
		}

		return apply_filters( 'woo_feed_filter_product_parent_link', $permalink, $this->product, $this->config );
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
	public function image() {
		$image_link = wp_get_attachment_url( $this->product->get_image_id() );

		return apply_filters( 'woo_feed_filter_product_image', $image_link, $this->product, $this->config );
	}

	/**
	 * Get product featured image url.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function feature_image() {
		$image_link = wp_get_attachment_url( $this->product->get_image_id() );

		if ( empty( $image_link ) && $this->product->is_type( 'variation' ) ) {
			$image_link = wp_get_attachment_url( $this->parent_product->get_image_id() );
		}

		return apply_filters( 'woo_feed_filter_product_feature_image', $image_link, $this->product, $this->config );
	}

	/**
	 * Get product gallery image urls.
	 *
	 * @return string
	 * @since 8.0.0
	 */
	public function images() {
		$urls              = '';
		$gallery_image_ids = $this->product->get_gallery_image_ids();

		if ( $this->product->is_type( 'variation' ) ) {
			$gallery_image_ids = $this->parent_product->get_gallery_image_ids();
		}

		// Get product additional images as comma separated string.
		if ( ! empty( $gallery_image_ids ) ) {
			$urls_array        = array_map( 'wp_get_attachment_url', $gallery_image_ids );
			$additional_images = ProductHelper::get_product_gallery( $this->product );

			if ( ! empty( $additional_images ) ) {
				$urls_array = array_merge( $urls_array, $additional_images );
			}

			$urls = implode( ',', $urls_array );
		}

		return apply_filters( 'woo_feed_filter_product_images', $urls, $this->product, $this->config );
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

		if ( $this->product->is_type( 'variation' ) ) {
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
	 * Get product quantity.
	 *
	 * @return int
	 * @since      8.0.0
	 */
	/**
	 * Get product quantity.
	 *
	 * @return int
	 * @since      8.0.0
	 */
	public function quantity() {// phpcs:ignore
		$quantity = $this->product->get_stock_quantity();

		if ( $this->product->is_type( 'variation' ) ) {
			$quantity = $this->parent_product->get_stock_quantity();
		}

		if ( $quantity === null ) {
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
	 * Get Product Sale Price start date.
	 *
	 * @return string|null
	 */
	public function sale_price_sdate() {
		$sale_price_sdate = '';
		$start_date       = $this->product->get_date_on_sale_from();

		if ( $start_date instanceof WC_DateTime ) {
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

		if ( $end_date instanceof WC_DateTime ) {
			$sale_price_edate = $end_date->date_i18n();
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_edate', $sale_price_edate, $this->product, $this->config );
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
	 * Get Product Price.
	 *
	 * @return mixed
	 */
	public function current_price() {
		$price = PriceFactory::get( $this->product, $this->config )->price();

		return apply_filters( 'woo_feed_filter_product_price', $price, $this->product, $this->config, false, 'price' );
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
	 * Get Product Shipping Class
	 *
	 * @return mixed
	 * @since 3.2.0
	 */
	public function shipping_class() {
		return apply_filters( 'woo_feed_filter_product_shipping_class', $this->product->get_shipping_class(), $this->product, $this->config );
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
			$unit                     = ProductHelper::get_custom_field( 'woo_feed_unit', $this->product, $this->config );
			$unit_price_base_measure  = ProductHelper::get_custom_field( 'woo_feed_unit_pricing_base_measure', $this->product, $this->config );
			$unit_price_base_measure .= ' ' . $unit;
		}

		// For WooCommerce Germanized Plugin
		if ( empty( $unit_price_base_measure ) && class_exists( 'WooCommerce_Germanized' ) ) {
			$unit                     = ProductHelper::get_product_meta( '_unit', $this->product, $this->config );
			$unit_price_base_measure  = ProductHelper::get_product_meta( '_unit_base', $this->product, $this->config );
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

		if ( class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$wc_germanized_gtin = ProductHelper::get_product_meta( '_ts_gtin', $this->product, $this->config );
		}

		return apply_filters( 'woo_feed_filter_wc_germanized_gtin', $wc_germanized_gtin, $this->product, $this->config );
	}

	/**
	 * Get product MPN for WooCommerce_Germanized plugin.
	 *
	 * @return string
	 * @since      8.0.0
	 */
	public function wc_germanized_mpn() {
		$wc_germanized_mpn = '';

		if ( class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$wc_germanized_mpn = ProductHelper::get_product_meta( '_ts_mpn', $this->product, $this->config );
		}

		return apply_filters( 'woo_feed_filter_wc_germanized_mpn', $wc_germanized_mpn, $this->product, $this->config );
	}
	// SEO Plugins ############

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
				if ( empty( $description ) && $this->product->is_type( 'variation' ) ) {
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

}
