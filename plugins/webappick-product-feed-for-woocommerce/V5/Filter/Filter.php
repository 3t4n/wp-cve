<?php /** @noinspection ALL */

namespace CTXFeed\V5\Filter;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Utility\Config;
use WC_Product;
use CTXFeed\V5\Product\AttributeValueByType;

class Filter {
	/**
	 * @var WC_Product $product
	 */
	private $product;
	/**
	 * @var Config $config
	 */
	private $config;

	/**
	 * @var array|bool
	 */
	private static $products_to_include;

	/**
	 * @var array|bool
	 */
	private static $products_to_exclude;

	/**
	 * @var array|bool
	 */
	private static $categories_to_include;

	/**
	 * @var array|bool
	 */
	private static $categories_to_exclude;

	/**
	 * @var array|bool
	 */
	private static $authors_to_include;

	/**
	 * @param $product
	 * @param Config $config
	 */
	public function __construct( $product, $config ) {
		$this->product = $product;
		$this->config  = $config;

		self::$products_to_include   = $this->config->get_products_to_include();
		self::$products_to_exclude   = $this->config->get_products_to_exclude();
		self::$categories_to_include = $this->config->get_categories_to_include();
		self::$categories_to_exclude = $this->config->get_categories_to_exclude();
		self::$authors_to_include    = $this->config->get_vendors_to_include();
	}

	/**
	 * @return bool
	 */
	public function exclude() {
		$exclude = false;

		$filters = [
			'exclude_variable_product',
			'exclude_empty_title_products',
			'exclude_hidden_products'
		];

		if ( Helper::is_pro() ) { // These filters only applied for pro version.
			$pro_filters = [
				'exclude_empty_description_products',
				'exclude_empty_image_products',
				'exclude_empty_price_products',
				'exclude_out_of_stock_products',
				'exclude_back_order_products',
				'exclude_variation_parent_draft_products',
				'exclude_override_out_of_stock_isibility'
			];

			$filters = array_merge( $filters, $pro_filters );
			if ( self::$authors_to_include ) {
				array_push( $filters, 'include_vendors' );
			}
			if ( self::$products_to_include ) {
				array_push( $filters, 'include_products' );// Only add products which are set to include.
			}
			if ( self::$products_to_exclude ) {
				array_push( $filters, 'exclude_products' );// Remove products which are set to exclude.
			}

			if ( self::$categories_to_exclude ) {
				array_push( $filters, 'exclude_categories' );// Remove categories which are set to exclude.
			}

			if ( self::$categories_to_include ) {
				array_push( $filters, 'include_categories' );// Only add categories which are set to include.
			}
		}


		foreach ( $filters as $filter ) {
			if ( $this->$filter() ) {
				$exclude = true;
				break;
			}
		}


		return apply_filters( 'ctx_feed_filter_product', $exclude, $this->product, $this->config );
	}


	/**
	 * Exclude Variable products if only variations to include.
	 * @return bool
	 */
	public function exclude_variable_product() {
		//TODO::Remove the second condition ==> [$this->config->is_variations == 1]
		if ( $this->product->is_type( 'variable' ) && ( $this->config->is_variations == 1 || $this->config->is_variations === 'y' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove out of stock products.
	 *
	 * @return bool
	 */
	public function exclude_out_of_stock_products() {
		if ( ! $this->config->remove_outofstock_product() || ( $this->product->get_stock_status() !== 'outofstock' && $this->product->get_stock_quantity() !== 0 ) || $this->product->get_stock_status() === 'onbackorder' ) {
			return false;
		}

		return true;
	}

	/**
	 * Remove back order products.
	 *
	 * @return bool
	 */
	public function exclude_back_order_products() {
		if ( $this->config->remove_backorder_product() && $this->product->get_stock_status() === 'onbackorder' ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove empty title products.
	 *
	 * @return bool
	 */
	public function exclude_empty_title_products() {
		if ( $this->config->remove_empty_title() && empty( $this->product->get_name() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove hidden products.
	 *
	 * @return bool
	 */
	public function exclude_hidden_products() {
		$remove_hidden_products = ! $this->config->remove_hidden_products();
		if ( $remove_hidden_products && ( $this->product->get_catalog_visibility() === 'hidden' || ! $this->product->is_visible() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove hidden variation products whose parent status is draft.
	 *
	 * @return bool
	 */
	public function exclude_variation_parent_draft_products() {
		if( $this->product->is_type('variation') ){
			$parent_id = $this->product->get_parent_id();
			if( $this->config->remove_hidden_products() && get_post_status( $parent_id ) === 'draft' ){
				return true;
			}
		}
		return false;
	}
	/**
	 * Remove hidden variation products whose parent status is draft.
	 *
	 * @return bool
	 */
	public function exclude_override_out_of_stock_isibility() {
		if( !$this->config->get_outofstock_visibility() && $this->product->get_stock_status() ==='outofstock' && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items') ){
			return true;
		}
		return false;
	}

	/**
	 * Remove empty description products.
	 *
	 * @return bool
	 */
	public function exclude_empty_description_products() {

		if ( $this->config->remove_empty_description() ) {
			if ( $this->product->is_type( 'variation' ) == 1 && empty( $this->product->get_description() ) ) {
				$parent_product = wc_get_product( $this->product->get_parent_id() );

				if ( $parent_product && empty( $parent_product->get_description() ) ) {
					return true;
				}
			} else if ( empty( $this->product->get_description() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove empty image products.
	 *
	 * @return bool
	 */
	public function exclude_empty_image_products() {
		if ( $this->config->remove_empty_image() && empty( $this->product->get_image( 'woocommerce_thumbnail', [], false ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Return Empty Price products.
	 *
	 * @return bool
	 */
	public function exclude_empty_price_products() {
		if ( $this->config->remove_empty_price() && empty( $this->product->get_price() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Exclude Variations.
	 *
	 * @param $exclude
	 *
	 * @return bool|mixed
	 */
	public function exclude_variation( $exclude, $is_variation = true ) {


		// TODO should check, whether this method is written properly.
		$filters = [
			'exclude_products',// Remove products which are set to exclude.
			'include_products',// Only add products which are set to include.
			'exclude_categories',// Remove categories which are set to exclude.
			'include_categories',// Only add categories which are set to include.
			'include_vendors',// Only add product status which are set to include.
//			'exclude_variation_stock_status',// Only add product stock status which are set to include.
//			'include_variation_author',// Only add product authors (For multivendor plugin) which are set to include.
		];

		foreach ( $filters as $filter ) {
			if ( $this->$filter() ) {
				$exclude = true;
				break;
			}
		}


		return $exclude;
	}

	public function exclude_products() {
		if ( self::$products_to_exclude && in_array( $this->product->get_id(), self::$products_to_exclude ) ) {
			return true;
		}

		return false;
	}

	public function include_products() {
		if ( self::$products_to_include && ! in_array( $this->product->get_id(), self::$products_to_include ) ) {
			return true;
		}

		return false;
	}

	public function exclude_categories() {
		$id = ( $this->product->is_type( 'variation' ) ) ? $this->product->get_parent_id() : $this->product->get_id();
		if ( self::$categories_to_exclude && has_term( self::$categories_to_exclude, 'product_cat', $id ) ) {
			return true;
		}

		return false;
	}

	public function include_categories() {
		if ( self::$products_to_include && in_array( $this->product->get_id(), self::$products_to_include ) ) {
			return false;
		}

		if ( ! apply_filters( 'ctx_filter_by_category__should_include_sub_categories', false ) ) {
			$id = ( $this->product->is_type( 'variation' ) ) ? $this->product->get_parent_id() : $this->product->get_id();
			if ( self::$categories_to_include && ! has_term( self::$categories_to_include, 'product_cat', $id ) ) {
				return true;
			}
		}

		return false;
	}

	public function include_vendors() {

		if ( self::$authors_to_include ) {
			// Find Author id
			$authorId = get_post( $this->product->get_id() )->post_author;
			if ( $this->product->is_type( 'variation' ) ) {
				$authorId = get_post( $this->product->get_parent_id() )->post_author;
			}

			if ( ! in_array( $authorId, self::$authors_to_include ) ) {
				return true;
			}
		}

		return false;
	}


}

