<?php
/**
 * WC_Product_Interface interface file.
 *
 * @package WooCommerce Utils
 */

/**
 * Abstract Product Class
 *
 * The WooCommerce product class handles individual product data.
 */
interface WC_Product_Interface extends WC_Abstract_Legacy_Product_Interface {
    /**
	 * Get the product if ID is passed, otherwise the product is new and empty.
	 * This class should NOT be instantiated, but the wc_get_product() function
	 * should be used. It is possible, but the wc_get_product() is preferred.
	 *
	 * @param int|WC_Product|object $product Product to init.
	 */
	public function __construct( $product = 0 );

	/**
	 * Get internal type. Should return string and *should be overridden* by child classes.
	 *
	 * The product_type property is deprecated but is used here for BW compatibility with child classes which may be defining product_type and not have a get_type method.
	 *
	 * @since  3.0.0
	 * @return string
	 */
	public function get_type();

	/**
	 * Get product name.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_name( $context = 'view' );

	/**
	 * Get product slug.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_slug( $context = 'view' );

	/**
	 * Get product created date.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' );

	/**
	 * Get product modified date.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' );

	/**
	 * Get product status.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_status( $context = 'view' );

	/**
	 * If the product is featured.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return boolean
	 */
	public function get_featured( $context = 'view' );

	/**
	 * Get catalog visibility.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_catalog_visibility( $context = 'view' );

	/**
	 * Get product description.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_description( $context = 'view' );

	/**
	 * Get product short description.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_short_description( $context = 'view' );

	/**
	 * Get SKU (Stock-keeping unit) - product unique ID.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_sku( $context = 'view' );

	/**
	 * Returns the product's active price.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string price
	 */
	public function get_price( $context = 'view' );

	/**
	 * Returns the product's regular price.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string price
	 */
	public function get_regular_price( $context = 'view' );

	/**
	 * Returns the product's sale price.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string price
	 */
	public function get_sale_price( $context = 'view' );

	/**
	 * Get date on sale from.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_on_sale_from( $context = 'view' );

	/**
	 * Get date on sale to.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return WC_DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_on_sale_to( $context = 'view' );

	/**
	 * Get number total of sales.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_total_sales( $context = 'view' );

	/**
	 * Returns the tax status.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_tax_status( $context = 'view' );

	/**
	 * Returns the tax class.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_tax_class( $context = 'view' );

	/**
	 * Return if product manage stock.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return boolean
	 */
	public function get_manage_stock( $context = 'view' );

	/**
	 * Returns number of items available for sale.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int|null
	 */
	public function get_stock_quantity( $context = 'view' );

	/**
	 * Return the stock status.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @since  3.0.0
	 * @return string
	 */
	public function get_stock_status( $context = 'view' );

	/**
	 * Get backorders.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @since  3.0.0
	 * @return string yes no or notify
	 */
	public function get_backorders( $context = 'view' );

	/**
	 * Get low stock amount.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @since  3.5.0
	 * @return int|string Returns empty string if value not set
	 */
	public function get_low_stock_amount( $context = 'view' );

	/**
	 * Return if should be sold individually.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @since  3.0.0
	 * @return boolean
	 */
	public function get_sold_individually( $context = 'view' );

	/**
	 * Returns the product's weight.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_weight( $context = 'view' );

	/**
	 * Returns the product length.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_length( $context = 'view' );

	/**
	 * Returns the product width.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_width( $context = 'view' );

	/**
	 * Returns the product height.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_height( $context = 'view' );

	/**
	 * Returns formatted dimensions.
	 *
	 * @param  bool $formatted True by default for legacy support - will be false/not set in future versions to return the array only. Use wc_format_dimensions for formatted versions instead.
	 * @return string|array
	 */
	public function get_dimensions( $formatted = true );

	/**
	 * Get upsell IDs.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_upsell_ids( $context = 'view' );

	/**
	 * Get cross sell IDs.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_cross_sell_ids( $context = 'view' );

	/**
	 * Get parent ID.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_parent_id( $context = 'view' );

	/**
	 * Return if reviews is allowed.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function get_reviews_allowed( $context = 'view' );

	/**
	 * Get purchase note.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_purchase_note( $context = 'view' );

	/**
	 * Returns product attributes.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_attributes( $context = 'view' );

	/**
	 * Get default attributes.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_default_attributes( $context = 'view' );

	/**
	 * Get menu order.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_menu_order( $context = 'view' );

	/**
	 * Get post password.
	 *
	 * @since  3.6.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_post_password( $context = 'view' );

	/**
	 * Get category ids.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_category_ids( $context = 'view' );

	/**
	 * Get tag ids.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_tag_ids( $context = 'view' );

	/**
	 * Get virtual.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function get_virtual( $context = 'view' );

	/**
	 * Returns the gallery attachment ids.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_gallery_image_ids( $context = 'view' );

	/**
	 * Get shipping class ID.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_shipping_class_id( $context = 'view' );

	/**
	 * Get downloads.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array
	 */
	public function get_downloads( $context = 'view' );

	/**
	 * Get download expiry.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_download_expiry( $context = 'view' );

	/**
	 * Get downloadable.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function get_downloadable( $context = 'view' );

	/**
	 * Get download limit.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_download_limit( $context = 'view' );

	/**
	 * Get main image ID.
	 *
	 * @since  3.0.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_image_id( $context = 'view' );

	/**
	 * Get rating count.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array of counts
	 */
	public function get_rating_counts( $context = 'view' );

	/**
	 * Get average rating.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return float
	 */
	public function get_average_rating( $context = 'view' );

	/**
	 * Get review count.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_review_count( $context = 'view' );

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting product data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Set product name.
	 *
	 * @since 3.0.0
	 * @param string $name Product name.
	 */
	public function set_name( $name );

	/**
	 * Set product slug.
	 *
	 * @since 3.0.0
	 * @param string $slug Product slug.
	 */
	public function set_slug( $slug );

	/**
	 * Set product created date.
	 *
	 * @since 3.0.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null );

	/**
	 * Set product modified date.
	 *
	 * @since 3.0.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null );

	/**
	 * Set product status.
	 *
	 * @since 3.0.0
	 * @param string $status Product status.
	 */
	public function set_status( $status );

	/**
	 * Set if the product is featured.
	 *
	 * @since 3.0.0
	 * @param bool|string $featured Whether the product is featured or not.
	 */
	public function set_featured( $featured );

	/**
	 * Set catalog visibility.
	 *
	 * @since  3.0.0
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 * @param  string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
	 */
	public function set_catalog_visibility( $visibility );

	/**
	 * Set product description.
	 *
	 * @since 3.0.0
	 * @param string $description Product description.
	 */
	public function set_description( $description );

	/**
	 * Set product short description.
	 *
	 * @since 3.0.0
	 * @param string $short_description Product short description.
	 */
	public function set_short_description( $short_description );

	/**
	 * Set SKU.
	 *
	 * @since  3.0.0
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 * @param  string $sku Product SKU.
	 */
	public function set_sku( $sku );

	/**
	 * Set the product's active price.
	 *
	 * @param string $price Price.
	 */
	public function set_price( $price );

	/**
	 * Set the product's regular price.
	 *
	 * @since 3.0.0
	 * @param string $price Regular price.
	 */
	public function set_regular_price( $price );

	/**
	 * Set the product's sale price.
	 *
	 * @since 3.0.0
	 * @param string $price sale price.
	 */
	public function set_sale_price( $price );

	/**
	 * Set date on sale from.
	 *
	 * @since 3.0.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_on_sale_from( $date = null );

	/**
	 * Set date on sale to.
	 *
	 * @since 3.0.0
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_on_sale_to( $date = null );

	/**
	 * Set number total of sales.
	 *
	 * @since 3.0.0
	 * @param int $total Total of sales.
	 */
	public function set_total_sales( $total );

	/**
	 * Set the tax status.
	 *
	 * @since  3.0.0
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 * @param  string $status Tax status.
	 */
	public function set_tax_status( $status );

	/**
	 * Set the tax class.
	 *
	 * @since 3.0.0
	 * @param string $tax_class Tax class.
	 */
	public function set_tax_class( $tax_class );

	/**
	 * Set if product manage stock.
	 *
	 * @since 3.0.0
	 * @param bool $manage_stock Whether or not manage stock is enabled.
	 */
	public function set_manage_stock( $manage_stock );

	/**
	 * Set number of items available for sale.
	 *
	 * @since 3.0.0
	 * @param float|null $quantity Stock quantity.
	 */
	public function set_stock_quantity( $quantity );

	/**
	 * Set stock status.
	 *
	 * @param string $status New status.
	 */
	public function set_stock_status( $status = 'instock' );

	/**
	 * Set backorders.
	 *
	 * @since 3.0.0
	 * @param string $backorders Options: 'yes', 'no' or 'notify'.
	 */
	public function set_backorders( $backorders );

	/**
	 * Set low stock amount.
	 *
	 * @param int|string $amount Empty string if value not set.
	 * @since 3.5.0
	 */
	public function set_low_stock_amount( $amount );

	/**
	 * Set if should be sold individually.
	 *
	 * @since 3.0.0
	 * @param bool $sold_individually Whether or not product is sold individually.
	 */
	public function set_sold_individually( $sold_individually );

	/**
	 * Set the product's weight.
	 *
	 * @since 3.0.0
	 * @param float|string $weight Total weight.
	 */
	public function set_weight( $weight );

	/**
	 * Set the product length.
	 *
	 * @since 3.0.0
	 * @param float|string $length Total length.
	 */
	public function set_length( $length );

	/**
	 * Set the product width.
	 *
	 * @since 3.0.0
	 * @param float|string $width Total width.
	 */
	public function set_width( $width );

	/**
	 * Set the product height.
	 *
	 * @since 3.0.0
	 * @param float|string $height Total height.
	 */
	public function set_height( $height );

	/**
	 * Set upsell IDs.
	 *
	 * @since 3.0.0
	 * @param array $upsell_ids IDs from the up-sell products.
	 */
	public function set_upsell_ids( $upsell_ids );

	/**
	 * Set crosssell IDs.
	 *
	 * @since 3.0.0
	 * @param array $cross_sell_ids IDs from the cross-sell products.
	 */
	public function set_cross_sell_ids( $cross_sell_ids );

	/**
	 * Set parent ID.
	 *
	 * @since 3.0.0
	 * @param int $parent_id Product parent ID.
	 */
	public function set_parent_id( $parent_id );

	/**
	 * Set if reviews is allowed.
	 *
	 * @since 3.0.0
	 * @param bool $reviews_allowed Reviews allowed or not.
	 */
	public function set_reviews_allowed( $reviews_allowed );

	/**
	 * Set purchase note.
	 *
	 * @since 3.0.0
	 * @param string $purchase_note Purchase note.
	 */
	public function set_purchase_note( $purchase_note );

	/**
	 * Set product attributes.
	 *
	 * Attributes are made up of:
	 *     id - 0 for product level attributes. ID for global attributes.
	 *     name - Attribute name.
	 *     options - attribute value or array of term ids/names.
	 *     position - integer sort order.
	 *     visible - If visible on frontend.
	 *     variation - If used for variations.
	 * Indexed by unqiue key to allow clearing old ones after a set.
	 *
	 * @since 3.0.0
	 * @param array $raw_attributes Array of WC_Product_Attribute objects.
	 */
	public function set_attributes( $raw_attributes );

	/**
	 * Set default attributes. These will be saved as strings and should map to attribute values.
	 *
	 * @since 3.0.0
	 * @param array $default_attributes List of default attributes.
	 */
	public function set_default_attributes( $default_attributes );

	/**
	 * Set menu order.
	 *
	 * @since 3.0.0
	 * @param int $menu_order Menu order.
	 */
	public function set_menu_order( $menu_order );

	/**
	 * Set post password.
	 *
	 * @since 3.6.0
	 * @param int $post_password Post password.
	 */
	public function set_post_password( $post_password );

	/**
	 * Set the product categories.
	 *
	 * @since 3.0.0
	 * @param array $term_ids List of terms IDs.
	 */
	public function set_category_ids( $term_ids );

	/**
	 * Set the product tags.
	 *
	 * @since 3.0.0
	 * @param array $term_ids List of terms IDs.
	 */
	public function set_tag_ids( $term_ids );

	/**
	 * Set if the product is virtual.
	 *
	 * @since 3.0.0
	 * @param bool|string $virtual Whether product is virtual or not.
	 */
	public function set_virtual( $virtual );

	/**
	 * Set shipping class ID.
	 *
	 * @since 3.0.0
	 * @param int $id Product shipping class id.
	 */
	public function set_shipping_class_id( $id );

	/**
	 * Set if the product is downloadable.
	 *
	 * @since 3.0.0
	 * @param bool|string $downloadable Whether product is downloadable or not.
	 */
	public function set_downloadable( $downloadable );

	/**
	 * Set downloads.
	 *
	 * @throws WC_Data_Exception If an error relating to one of the downloads is encountered.
	 *
	 * @param array $downloads_array Array of WC_Product_Download objects or arrays.
	 *
	 * @since 3.0.0
	 */
	public function set_downloads( $downloads_array );

	/**
	 * Set download limit.
	 *
	 * @since 3.0.0
	 * @param int|string $download_limit Product download limit.
	 */
	public function set_download_limit( $download_limit );

	/**
	 * Set download expiry.
	 *
	 * @since 3.0.0
	 * @param int|string $download_expiry Product download expiry.
	 */
	public function set_download_expiry( $download_expiry );

	/**
	 * Set gallery attachment ids.
	 *
	 * @since 3.0.0
	 * @param array $image_ids List of image ids.
	 */
	public function set_gallery_image_ids( $image_ids );

	/**
	 * Set main image ID.
	 *
	 * @since 3.0.0
	 * @param int|string $image_id Product image id.
	 */
	public function set_image_id( $image_id = '' );

	/**
	 * Set rating counts. Read only.
	 *
	 * @param array $counts Product rating counts.
	 */
	public function set_rating_counts( $counts );

	/**
	 * Set average rating. Read only.
	 *
	 * @param float $average Product average rating.
	 */
	public function set_average_rating( $average );

	/**
	 * Set review count. Read only.
	 *
	 * @param int $count Product review count.
	 */
	public function set_review_count( $count );

	/*
	|--------------------------------------------------------------------------
	| Other Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Ensure properties are set correctly before save.
	 *
	 * @since 3.0.0
	 */
	public function validate_props();

	/**
	 * Save data (either create or update depending on if we are working on an existing product).
	 *
	 * @since  3.0.0
	 * @return int
	 */
	public function save();

	/**
	 * Delete the product, set its ID to 0, and return result.
	 *
	 * @param  bool $force_delete Should the product be deleted permanently.
	 * @return bool result
	 */
	public function delete( $force_delete = false );

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check if a product supports a given feature.
	 *
	 * Product classes should override this to declare support (or lack of support) for a feature.
	 *
	 * @param  string $feature string The name of a feature to test support for.
	 * @return bool True if the product supports the feature, false otherwise.
	 * @since  2.5.0
	 */
	public function supports( $feature );

	/**
	 * Returns whether or not the product post exists.
	 *
	 * @return bool
	 */
	public function exists();

	/**
	 * Checks the product type.
	 *
	 * Backwards compatibility with downloadable/virtual.
	 *
	 * @param  string|array $type Array or string of types.
	 * @return bool
	 */
	public function is_type( $type );

	/**
	 * Checks if a product is downloadable.
	 *
	 * @return bool
	 */
	public function is_downloadable();

	/**
	 * Checks if a product is virtual (has no shipping).
	 *
	 * @return bool
	 */
	public function is_virtual();

	/**
	 * Returns whether or not the product is featured.
	 *
	 * @return bool
	 */
	public function is_featured();

	/**
	 * Check if a product is sold individually (no quantities).
	 *
	 * @return bool
	 */
	public function is_sold_individually();

	/**
	 * Returns whether or not the product is visible in the catalog.
	 *
	 * @return bool
	 */
	public function is_visible();

	/**
	 * Returns false if the product cannot be bought.
	 *
	 * @return bool
	 */
	public function is_purchasable();

	/**
	 * Returns whether or not the product is on sale.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function is_on_sale( $context = 'view' );

	/**
	 * Returns whether or not the product has dimensions set.
	 *
	 * @return bool
	 */
	public function has_dimensions();

	/**
	 * Returns whether or not the product has weight set.
	 *
	 * @return bool
	 */
	public function has_weight();

	/**
	 * Returns whether or not the product can be purchased.
	 * This returns true for 'instock' and 'onbackorder' stock statuses.
	 *
	 * @return bool
	 */
	public function is_in_stock();

	/**
	 * Checks if a product needs shipping.
	 *
	 * @return bool
	 */
	public function needs_shipping();

	/**
	 * Returns whether or not the product is taxable.
	 *
	 * @return bool
	 */
	public function is_taxable();

	/**
	 * Returns whether or not the product shipping is taxable.
	 *
	 * @return bool
	 */
	public function is_shipping_taxable();

	/**
	 * Returns whether or not the product is stock managed.
	 *
	 * @return bool
	 */
	public function managing_stock();

	/**
	 * Returns whether or not the product can be backordered.
	 *
	 * @return bool
	 */
	public function backorders_allowed();

	/**
	 * Returns whether or not the product needs to notify the customer on backorder.
	 *
	 * @return bool
	 */
	public function backorders_require_notification();

	/**
	 * Check if a product is on backorder.
	 *
	 * @param  int $qty_in_cart (default: 0).
	 * @return bool
	 */
	public function is_on_backorder( $qty_in_cart = 0 );

	/**
	 * Returns whether or not the product has enough stock for the order.
	 *
	 * @param  mixed $quantity Quantity of a product added to an order.
	 * @return bool
	 */
	public function has_enough_stock( $quantity );

	/**
	 * Returns whether or not the product has any visible attributes.
	 *
	 * @return boolean
	 */
	public function has_attributes();

	/**
	 * Returns whether or not the product has any child product.
	 *
	 * @return bool
	 */
	public function has_child();

	/**
	 * Does a child have dimensions?
	 *
	 * @since  3.0.0
	 * @return bool
	 */
	public function child_has_dimensions();

	/**
	 * Does a child have a weight?
	 *
	 * @since  3.0.0
	 * @return boolean
	 */
	public function child_has_weight();

	/**
	 * Check if downloadable product has a file attached.
	 *
	 * @since 1.6.2
	 *
	 * @param  string $download_id file identifier.
	 * @return bool Whether downloadable product has a file attached.
	 */
	public function has_file( $download_id = '' );

	/**
	 * Returns whether or not the product has additional options that need
	 * selecting before adding to cart.
	 *
	 * @since  3.0.0
	 * @return boolean
	 */
	public function has_options();

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the product's title. For products this is the product name.
	 *
	 * @return string
	 */
	public function get_title();

	/**
	 * Product permalink.
	 *
	 * @return string
	 */
	public function get_permalink();

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children();

	/**
	 * If the stock level comes from another product ID, this should be modified.
	 *
	 * @since  3.0.0
	 * @return int
	 */
	public function get_stock_managed_by_id();

	/**
	 * Returns the price in html format.
	 *
	 * @param string $deprecated Deprecated param.
	 *
	 * @return string
	 */
	public function get_price_html( $deprecated = '' );

	/**
	 * Get product name with SKU or ID. Used within admin.
	 *
	 * @return string Formatted product name
	 */
	public function get_formatted_name();

	/**
	 * Get min quantity which can be purchased at once.
	 *
	 * @since  3.0.0
	 * @return int
	 */
	public function get_min_purchase_quantity();

	/**
	 * Get max quantity which can be purchased at once.
	 *
	 * @since  3.0.0
	 * @return int Quantity or -1 if unlimited.
	 */
	public function get_max_purchase_quantity();

	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @return string
	 */
	public function add_to_cart_url();

	/**
	 * Get the add to cart button text for the single page.
	 *
	 * @return string
	 */
	public function single_add_to_cart_text();

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text();

	/**
	 * Get the add to cart button text description - used in aria tags.
	 *
	 * @since  3.3.0
	 * @return string
	 */
	public function add_to_cart_description();

	/**
	 * Returns the main product image.
	 *
	 * @param  string $size (default: 'woocommerce_thumbnail').
	 * @param  array  $attr Image attributes.
	 * @param  bool   $placeholder True to return $placeholder if no image is found, or false to return an empty string.
	 * @return string
	 */
	public function get_image( $size = 'woocommerce_thumbnail', $attr = array(), $placeholder = true );

	/**
	 * Returns the product shipping class SLUG.
	 *
	 * @return string
	 */
	public function get_shipping_class();

	/**
	 * Returns a single product attribute as a string.
	 *
	 * @param  string $attribute to get.
	 * @return string
	 */
	public function get_attribute( $attribute );

	/**
	 * Get the total amount (COUNT) of ratings, or just the count for one rating e.g. number of 5 star ratings.
	 *
	 * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
	 * @return int
	 */
	public function get_rating_count( $value = null );

	/**
	 * Get a file by $download_id.
	 *
	 * @param  string $download_id file identifier.
	 * @return array|false if not found
	 */
	public function get_file( $download_id = '' );

	/**
	 * Get file download path identified by $download_id.
	 *
	 * @param  string $download_id file identifier.
	 * @return string
	 */
	public function get_file_download_path( $download_id );

	/**
	 * Get the suffix to display after prices > 0.
	 *
	 * @param  string  $price to calculate, left blank to just use get_price().
	 * @param  integer $qty   passed on to get_price_including_tax() or get_price_excluding_tax().
	 * @return string
	 */
	public function get_price_suffix( $price = '', $qty = 1 );

	/**
	 * Returns the availability of the product.
	 *
	 * @return string[]
	 */
	public function get_availability();
}
