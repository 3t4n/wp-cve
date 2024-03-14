<?php
/**
 * WC_Product_Variable_Interface interface file.
 *
 * @package WooCommerce Utils
 */

/**
 * Variable product class.
 */
interface WC_Product_Variable_Interface extends WC_Product_Interface {
    /**
	 * Get internal type.
	 *
	 * @return string
	 */
	public function get_type();

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text();

	/**
	 * Get the add to cart button text description - used in aria tags.
	 *
	 * @since 3.3.0
	 * @return string
	 */
	public function add_to_cart_description();

	/**
	 * Get an array of all sale and regular prices from all variations. This is used for example when displaying the price range at variable product level or seeing if the variable product is on sale.
	 *
	 * @param  bool $for_display If true, prices will be adapted for display based on the `woocommerce_tax_display_shop` setting (including or excluding taxes).
	 * @return array Array of RAW prices, regular prices, and sale prices with keys set to variation ID.
	 */
	public function get_variation_prices( $for_display = false );

	/**
	 * Get the min or max variation regular price.
	 *
	 * @param  string  $min_or_max Min or max price.
	 * @param  boolean $for_display If true, prices will be adapted for display based on the `woocommerce_tax_display_shop` setting (including or excluding taxes).
	 * @return string
	 */
	public function get_variation_regular_price( $min_or_max = 'min', $for_display = false );

	/**
	 * Get the min or max variation sale price.
	 *
	 * @param  string  $min_or_max Min or max price.
	 * @param  boolean $for_display If true, prices will be adapted for display based on the `woocommerce_tax_display_shop` setting (including or excluding taxes).
	 * @return string
	 */
	public function get_variation_sale_price( $min_or_max = 'min', $for_display = false );

	/**
	 * Get the min or max variation (active) price.
	 *
	 * @param  string  $min_or_max Min or max price.
	 * @param  boolean $for_display If true, prices will be adapted for display based on the `woocommerce_tax_display_shop` setting (including or excluding taxes).
	 * @return string
	 */
	public function get_variation_price( $min_or_max = 'min', $for_display = false );

	/**
	 * Returns the price in html format.
	 *
	 * Note: Variable prices do not show suffixes like other product types. This
	 * is due to some things like tax classes being set at variation level which
	 * could differ from the parent price. The only way to show accurate prices
	 * would be to load the variation and get it's price, which adds extra
	 * overhead and still has edge cases where the values would be inaccurate.
	 *
	 * Additionally, ranges of prices no longer show 'striked out' sale prices
	 * due to the strings being very long and unclear/confusing. A single range
	 * is shown instead.
	 *
	 * @param string $price Price (default: '').
	 * @return string
	 */
	public function get_price_html( $price = '' );

	/**
	 * Get the suffix to display after prices > 0.
	 *
	 * This is skipped if the suffix
	 * has dynamic values such as {price_excluding_tax} for variable products.
	 *
	 * @see get_price_html for an explanation as to why.
	 * @param  string  $price Price to calculate, left blank to just use get_price().
	 * @param  integer $qty   Quantity passed on to get_price_including_tax() or get_price_excluding_tax().
	 * @return string
	 */
	public function get_price_suffix( $price = '', $qty = 1 );

	/**
	 * Return a products child ids.
	 *
	 * This is lazy loaded as it's not used often and does require several queries.
	 *
	 * @param bool|string $visible_only Visible only.
	 * @return array Children ids
	 */
	public function get_children( $visible_only = '' );

	/**
	 * Return a products child ids - visible only.
	 *
	 * This is lazy loaded as it's not used often and does require several queries.
	 *
	 * @since 3.0.0
	 * @return array Children ids
	 */
	public function get_visible_children();

	/**
	 * Return an array of attributes used for variations, as well as their possible values.
	 *
	 * This is lazy loaded as it's not used often and does require several queries.
	 *
	 * @return array Attributes and their available values
	 */
	public function get_variation_attributes();

	/**
	 * If set, get the default attributes for a variable product.
	 *
	 * @param string $attribute_name Attribute name.
	 * @return string
	 */
	public function get_variation_default_attribute( $attribute_name );

	/**
	 * Variable products themselves cannot be downloadable.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function get_downloadable( $context = 'view' );

	/**
	 * Variable products themselves cannot be virtual.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function get_virtual( $context = 'view' );

	/**
	 * Get an array of available variations for the current product.
	 *
	 * @param string $returns Optional. The format to return the results in. Can be 'array' to return an array of variation data or 'objects' for the product objects. Default 'array'.
	 *
	 * @return array[]|WC_Product_Variation[]
	 */
	public function get_available_variations( $returns = 'array' );

	/**
	 * Returns an array of data for a variation. Used in the add to cart form.
	 *
	 * @since  2.4.0
	 * @param  WC_Product $variation Variation product object or ID.
	 * @return array|bool
	 */
	public function get_available_variation( $variation );

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Sets an array of variation attributes.
	 *
	 * @since 3.0.0
	 * @param array $variation_attributes Attributes list.
	 */
	public function set_variation_attributes( $variation_attributes );

	/**
	 * Sets an array of children for the product.
	 *
	 * @since 3.0.0
	 * @param array $children Children products.
	 */
	public function set_children( $children );

	/**
	 * Sets an array of visible children only.
	 *
	 * @since 3.0.0
	 * @param array $visible_children List of visible children products.
	 */
	public function set_visible_children( $visible_children );

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Ensure properties are set correctly before save.
	 *
	 * @since 3.0.0
	 */
	public function validate_props();

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	*/

	/**
	 * Returns whether or not the product is on sale.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit. What the value is for. Valid values are view and edit.
	 * @return bool
	 */
	public function is_on_sale( $context = 'view' );

	/**
	 * Is a child in stock?
	 *
	 * @return boolean
	 */
	public function child_is_in_stock();

	/**
	 * Is a child on backorder?
	 *
	 * @since 3.3.0
	 * @return boolean
	 */
	public function child_is_on_backorder();

	/**
	 * Does a child have a weight set?
	 *
	 * @return boolean
	 */
	public function child_has_weight();

	/**
	 * Does a child have dimensions set?
	 *
	 * @return boolean
	 */
	public function child_has_dimensions();

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
	 * Returns whether or not the product has additional options that need
	 * selecting before adding to cart.
	 *
	 * @since  3.0.0
	 * @return boolean
	 */
	public function has_options();


	/*
	|--------------------------------------------------------------------------
	| Sync with child variations.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Sync a variable product with it's children. These sync functions sync
	 * upwards (from child to parent) when the variation is saved.
	 *
	 * @param WC_Product|int $product Product object or ID for which you wish to sync.
	 * @param bool           $save If true, the product object will be saved to the DB before returning it.
	 * @return WC_Product Synced product object.
	 */
	public static function sync( $product, $save = true );

	/**
	 * Sync parent stock status with the status of all children and save.
	 *
	 * @param WC_Product|int $product Product object or ID for which you wish to sync.
	 * @param bool           $save If true, the product object will be saved to the DB before returning it.
	 * @return WC_Product Synced product object.
	 */
	public static function sync_stock_status( $product, $save = true );
}
