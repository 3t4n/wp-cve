<?php
/**
 * WC_Abstract_Legacy_Product_Interface interface file.
 *
 * @package WooCommerce Utils
 */

/**
 * Legacy Abstract Product
 *
 * Legacy and deprecated functions are here to keep the WC_Abstract_Product
 * clean.
 * This class will be removed in future versions.
 */
interface WC_Abstract_Legacy_Product_Interface extends WC_Data_Interface {
    /**
	 * Magic __isset method for backwards compatibility. Legacy properties which could be accessed directly in the past.
	 *
	 * @param  string $key Key name.
	 * @return bool
	 */
	public function __isset( $key );

	/**
	 * Magic __get method for backwards compatibility. Maps legacy vars to new getters.
	 *
	 * @param  string $key Key name.
	 * @return mixed
	 */
	public function __get( $key );

	/**
	 * If set, get the default attributes for a variable product.
	 *
	 * @deprecated 3.0.0
	 * @return array
	 */
	public function get_variation_default_attributes();

	/**
	 * Returns the gallery attachment ids.
	 *
	 * @deprecated 3.0.0
	 * @return array
	 */
	public function get_gallery_attachment_ids();

	/**
	 * Set stock level of the product.
	 *
	 * @deprecated 3.0.0
	 *
	 * @param int    $amount Amount to set stock to. Default: null.
	 * @param string $mode   Mode to set the stock. Options: set, increase, decrease. Default: set.
	 *
	 * @return int
	 */
	public function set_stock( $amount = null, $mode = 'set' );

	/**
	 * Reduce stock level of the product.
	 *
	 * @deprecated 3.0.0
	 * @param int $amount Amount to reduce by. Default: 1.
	 * @return int new stock level
	 */
	public function reduce_stock( $amount = 1 );

	/**
	 * Increase stock level of the product.
	 *
	 * @deprecated 3.0.0
	 * @param int $amount Amount to increase by. Default 1.
	 * @return int new stock level
	 */
	public function increase_stock( $amount = 1 );

	/**
	 * Check if the stock status needs changing.
	 *
	 * @deprecated 3.0.0 Sync is done automatically on read/save, so calling this should not be needed any more.
	 */
	public function check_stock_status();

	/**
	 * Get and return related products.
     *
	 * @deprecated 3.0.0 Use wc_get_related_products instead.
	 *
	 * @param int $limit (default: 5).
	 *
	 * @return array
	 */
	public function get_related( $limit = 5 );

	/**
	 * Returns the child product.
     *
	 * @deprecated 3.0.0 Use wc_get_product instead.
	 * @param  mixed $child_id Child ID.
	 * @return WC_Product|WC_Product|WC_Product_variation
	 */
	public function get_child( $child_id );

	/**
	 * Functions for getting parts of a price, in html, used by get_price_html.
	 *
	 * @deprecated 3.0.0
	 * @return string
	 */
	public function get_price_html_from_text();

	/**
	 * Functions for getting parts of a price, in html, used by get_price_html.
	 *
	 * @deprecated 3.0.0 Use wc_format_sale_price instead.
	 * @param  string $from String or float to wrap with 'from' text.
	 * @param  mixed  $to String or float to wrap with 'to' text.
	 * @return string
	 */
	public function get_price_html_from_to( $from, $to );

	/**
	 * Lists a table of attributes for the product page.
     *
	 * @deprecated 3.0.0 Use wc_display_product_attributes instead.
	 */
	public function list_attributes();

	/**
	 * Returns the price (including tax). Uses customer tax rates. Can work for a specific $qty for more accurate taxes.
	 *
	 * @deprecated 3.0.0 Use wc_get_price_including_tax instead.
	 * @param  int    $qty   (default: 1).
	 * @param  string $price to calculate, left blank to just use get_price().
	 * @return string
	 */
	public function get_price_including_tax( $qty = 1, $price = '' );

	/**
	 * Returns the price including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
	 *
	 * @deprecated 3.0.0 Use wc_get_price_to_display instead.
	 * @param  string  $price to calculate, left blank to just use get_price().
	 * @param  integer $qty   passed on to get_price_including_tax() or get_price_excluding_tax().
	 * @return string
	 */
	public function get_display_price( $price = '', $qty = 1 );

	/**
	 * Returns the price (excluding tax) - ignores tax_class filters since the price may *include* tax and thus needs subtracting.
	 * Uses store base tax rates. Can work for a specific $qty for more accurate taxes.
	 *
	 * @deprecated 3.0.0 Use wc_get_price_excluding_tax instead.
	 * @param  int    $qty  (default: 1).
	 * @param  string $price to calculate, left blank to just use get_price().
	 * @return string
	 */
	public function get_price_excluding_tax( $qty = 1, $price = '' );

	/**
	 * Adjust a products price dynamically.
	 *
	 * @deprecated 3.0.0
	 * @param mixed $price Price to adjust.
	 */
	public function adjust_price( $price );

	/**
	 * Returns the product categories.
	 *
	 * @deprecated 3.0.0
	 * @param string $sep (default: ', ').
	 * @param string $before (default: '').
	 * @param string $after (default: '').
	 * @return string
	 */
	public function get_categories( $sep = ', ', $before = '', $after = '' );

	/**
	 * Returns the product tags.
	 *
	 * @deprecated 3.0.0
	 * @param string $sep (default: ', ').
	 * @param string $before (default: '').
	 * @param string $after (default: '').
	 * @return array
	 */
	public function get_tags( $sep = ', ', $before = '', $after = '' );

	/**
	 * Get the product's post data.
	 *
	 * @deprecated 3.0.0
	 * @return WP_Post
	 */
	public function get_post_data();

	/**
	 * Get the parent of the post.
	 *
	 * @deprecated 3.0.0
	 * @return int
	 */
	public function get_parent();

	/**
	 * Returns the upsell product ids.
	 *
	 * @deprecated 3.0.0
	 * @return array
	 */
	public function get_upsells();

	/**
	 * Returns the cross sell product ids.
	 *
	 * @deprecated 3.0.0
	 * @return array
	 */
	public function get_cross_sells();

	/**
	 * Check if variable product has default attributes set.
	 *
	 * @deprecated 3.0.0
	 * @return bool
	 */
	public function has_default_attributes();

	/**
	 * Get variation ID.
	 *
	 * @deprecated 3.0.0
	 * @return int
	 */
	public function get_variation_id();

	/**
	 * Get product variation description.
	 *
	 * @deprecated 3.0.0
	 * @return string
	 */
	public function get_variation_description();

	/**
	 * Check if all variation's attributes are set.
	 *
	 * @deprecated 3.0.0
	 * @return boolean
	 */
	public function has_all_attributes_set();

	/**
	 * Returns whether or not the variations parent is visible.
	 *
	 * @deprecated 3.0.0
	 * @return bool
	 */
	public function parent_is_visible();

	/**
	 * Get total stock - This is the stock of parent and children combined.
	 *
	 * @deprecated 3.0.0
	 * @return int
	 */
	public function get_total_stock();

	/**
	 * Get formatted variation data with WC < 2.4 back compat and proper formatting of text-based attribute names.
	 *
	 * @deprecated 3.0.0
	 *
	 * @param bool $flat (default: false).
	 *
	 * @return string
	 */
	public function get_formatted_variation_attributes( $flat = false );

	/**
	 * Sync variable product prices with the children lowest/highest prices.
	 *
	 * @deprecated 3.0.0 not used in core.
	 *
	 * @param int $product_id (default: 0).
     */
	public function variable_product_sync( $product_id = 0 );

	/**
	 * Sync the variable product's attributes with the variations.
	 *
	 * @param WC_product $product  Product object.
	 * @param bool       $children Children to sync.
	 */
	public static function sync_attributes( $product, $children = false );

	/**
	 * Match a variation to a given set of attributes using a WP_Query.
     *
	 * @deprecated 3.0.0 in favour of Product data store's find_matching_product_variation.
	 *
	 * @param array $match_attributes (default: array()).
	 */
	public function get_matching_variation( $match_attributes = array() );

	/**
	 * Returns whether or not we are showing dimensions on the product page.
     *
	 * @deprecated 3.0.0 Unused.
	 * @return bool
	 */
	public function enable_dimensions_display();

	/**
	 * Returns the product rating in html format.
	 *
	 * @deprecated 3.0.0
	 * @param  string $rating (default: '').
	 * @return string
	 */
	public function get_rating_html( $rating = null );

	/**
	 * Sync product rating. Can be called statically.
	 *
	 * @deprecated 3.0.0
	 * @param  int $post_id Post ID.
	 */
	public static function sync_average_rating( $post_id );

	/**
	 * Sync product rating count. Can be called statically.
	 *
	 * @deprecated 3.0.0
	 * @param  int $post_id Post ID.
	 */
	public static function sync_rating_count( $post_id );

	/**
	 * Same as get_downloads in CRUD.
	 *
	 * @deprecated 3.0.0
	 * @return array
	 */
	public function get_files();

	/**
     * Sync grouped product children.
     *
	 * @deprecated 3.0.0 Sync is taken care of during save - no need to call this directly.
	 */
	public function grouped_product_sync();
}
