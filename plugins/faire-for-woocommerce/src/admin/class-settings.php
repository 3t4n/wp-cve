<?php
/**
 * Plugin settings retrieving methods.
 *
 * @package  Faire/Admin
 */

namespace Faire\Wc\Admin;

use Faire\Wc\Country;
use WC_Settings_API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin Main class.
 */
class Settings extends WC_Settings_API {

	/**
	 * Maps time literals to seconds.
	 *
	 * @var array
	 */
	const TIME_LITERAL_TO_SECONDS = array(
		'none'    => 0,
		'minutes' => MINUTE_IN_SECONDS,
		'hours'   => HOUR_IN_SECONDS,
		'daily'   => DAY_IN_SECONDS,
	);

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->id = 'faire_wc_integration';
	}

	/**
	 * Converts a named time unit to seconds.
	 *
	 * @param string $time_unit Name of time unit (none, minutes, hours, daily).
	 *
	 * @return int Time in seconds.
	 */
	public function time_to_seconds( string $time_unit ): int {
		if ( ! isset( self::TIME_LITERAL_TO_SECONDS[ $time_unit ] ) ) {
			return 0;
		}
		return self::TIME_LITERAL_TO_SECONDS[ $time_unit ];
	}

	/**
	 * Retrieves the Faire integration API key.
	 *
	 * @return string
	 *   The API key.
	 */
	public function get_api_key(): string {
		$key        = $this->plugin_id . $this->id . '_api_key';
		$post_value = ! empty( $_POST[ $key ] ) ? $_POST[ $key ] : false;

		if ( $post_value ) {
			return $post_value;
		}

		return $this->get_option( 'api_key', $post_value );
	}

	/**
	 * Retrieves the Faire integration API environment.
	 *
	 * @return string
	 *   The debug log setting.
	 */
	public function get_api_mode(): string {
		$key        = $this->plugin_id . $this->id . '_api_mode';
		$post_value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : false;

		if ( $post_value && ( 'production' === $post_value || 'staging' === $post_value ) ) {
			return $post_value;
		}

		$default_value = 'staging' === $post_value ? 'staging' : 'production';

		return $this->get_option( 'api_mode', $default_value );
	}

	/**
	 * Retrieves the debug log setting.
	 *
	 * @return bool
	 *   The debug log setting.
	 */
	public function get_debug_log(): bool {
		return $this->get_option( 'debug', 'yes' ) === 'yes';
	}

	/**
	 * Retrieves the intial setup date.
	 *
	 * @return string
	 *   The brand id.
	 */
	public function get_initial_setup_date(): string {
		return (string) $this->get_option( 'initial_setup_date', '' );
	}

	/**
	 * Retrieves if initial setup found existing products
	 *
	 * @return bool
	 *   The brand id.
	 */
	public function get_initial_setup_products_exist(): bool {
		return $this->get_option( 'initial_setup_products_exist', false );
	}

	/**
	 * Retrieves the brand id.
	 *
	 * @return string
	 *   The brand id.
	 */
	public function get_brand_id(): string {
		return (string) $this->get_option( 'brand_id', '' );
	}

	/**
	 * Retrieves the brand name.
	 *
	 * @return string
	 *   The brand name.
	 */
	public function get_brand_name(): string {
		return (string) $this->get_option( 'brand_name', '' );
	}

	/**
	 * Retrieves the name of the Faire product ID meta field.
	 *
	 * @return string
	 */
	public function get_meta_faire_product_id(): string {
		return sprintf( '_faire_%s_product_id', $this->get_brand_id() );
	}

	/**
	 * Retrieves the name of the Faire variant ID meta field.
	 *
	 * @return string
	 */
	public function get_meta_faire_variant_id(): string {
		return sprintf( '_faire_%s_variant_id', $this->get_brand_id() );
	}

	/**
	 * Retrieves the brand currency.
	 *
	 * @return string
	 *   The brand currency.
	 */
	public function get_brand_currency(): string {
		return (string) $this->get_option( 'brand_currency', '' );
	}

	/**
	 * Retrieves the brand locale.
	 *
	 * @return string
	 *   The brand locale.
	 */
	public function get_brand_locale(): string {
		return (string) $this->get_option( 'brand_locale', '' );
	}

	/**
	 * Retrieves the product sync mode.
	 *
	 * @return string
	 *   The product sync mode.
	 */
	public function get_product_sync_mode(): string {
		return $this->get_option( 'product_sync_mode', 'do_not_sync' );
	}

	/**
	 * Retrieves the product sync schedule number.
	 *
	 * @return float
	 *   The product sync schedule number.
	 */
	public function get_product_sync_schedule_num(): float {
		return (float) $this->get_option( 'product_sync_schedule_num', 0 );
	}

	/**
	 * Retrieves the literal for the product sync schedule time.
	 *
	 * @return string
	 *   The product sync schedule time.
	 */
	public function get_product_sync_schedule_time(): string {
		return $this->get_option( 'product_sync_schedule_time', 'none' );
	}

	/**
	 * Returns the product sync schedule period in seconds.
	 *
	 * @return float
	 */
	public function get_product_sync_schedule_period(): float {
		return $this->get_product_sync_schedule_num() *
			self::TIME_LITERAL_TO_SECONDS[ $this->get_product_sync_schedule_time() ];
	}

	/**
	 * Retrieves product pricing policy.
	 *
	 * @return string
	 */
	public function get_product_pricing_policy(): string {
		return $this->get_option( 'product_pricing_policy', 'wholesale_percentage' );
	}

	/**
	 * Retrieves product wholesale percentage.
	 *
	 * @return int
	 */
	public function get_product_wholesale_percentage(): int {
		return (int) $this->get_option( 'product_wholesale_percentage', 80 );
	}

	/**
	 * Retrieves product wholesale multiplier.
	 *
	 * @return float
	 */
	public function get_product_wholesale_multiplier(): float {
		return (float) $this->get_option( 'product_wholesale_multiplier', 1.25 );
	}

	/**
	 * Retrieves the product image size.
	 *
	 * @return string
	 *   The product image size.
	 */
	public function get_product_image_size(): string {
		return 'original';
	}

	/**
	 * Retrieves the product sync excluded fields.
	 *
	 * @return array
	 *   The fields to exclude from sync.
	 */
	public function get_product_sync_exclude_fields(): array {
		return (array) $this->get_option(
			'product_sync_exclude_fields',
			array(
				'product.allow_sales_when_out_of_stock',
				'product.preorder_fields',
				'variant.sku',
				'variant.tariff_code',
			)
		);
	}

	/**
	 * Retrieves the order sync schedule number.
	 *
	 * @return float
	 *   The order sync schedule number.
	 */
	public function get_order_sync_schedule_num(): float {
		return (float) $this->get_option( 'order_sync_schedule_num', 0 );
	}

	/**
	 * Retrieves the literal for the order sync schedule time.
	 *
	 * @return string
	 *   The order sync schedule time.
	 */
	public function get_order_sync_schedule_time(): string {
		return $this->get_option( 'order_sync_schedule_time', 'none' );
	}

	/**
	 * Returns the order sync schedule period in seconds.
	 *
	 * @return float
	 */
	public function get_order_sync_schedule_period(): float {
		return $this->get_order_sync_schedule_num() *
			self::TIME_LITERAL_TO_SECONDS[ $this->get_order_sync_schedule_time() ];
	}

	/**
	 * Retrieves the setting to skip orders creation on orders syncing.
	 *
	 * @return bool True if new orders should not be created,
	 */
	public function get_order_sync_skip_orders_create(): bool {
		if ( true === $this->get_suppress_currency_matching() ) {
			return true;
		}
		return $this->get_option( 'order_sync_skip_orders_create', 'no' ) === 'no';
	}

	/**
	 * Retrieves the inventory sync on change setting.
	 *
	 * @return bool
	 *   The inventory sync on change setting.
	 */
	public function get_inventory_sync_on_change(): bool {
		return $this->get_option( 'inventory_sync_on_change', 'no' ) === 'yes';
	}

	/**
	 * Retrieves the inventory sync on add_to_cart setting.
	 *
	 * @return bool
	 *   The inventory sync on add_to_cart setting.
	 */
	public function get_inventory_sync_on_add_to_cart(): bool {
		return $this->get_option( 'inventory_sync_on_add_to_cart', 'no' ) === 'yes';
	}

	/**
	 * Retrieves the orders sync mode.
	 *
	 * @return string The orders sync mode.
	 */
	public function get_order_sync_mode(): string {
		return $this->get_option( 'order_sync_mode', 'do_not_sync' );
	}

	/**
	 * Retrieves the timestamp of the last products syncing.
	 *
	 * If no timestamp is retrieved, we assume it should be "now".
	 *
	 * @return string Timestamp of last orders syncing (ISO 8601).
	 */
	public function get_products_last_sync_date(): string {
		return $this->get_option( 'products_last_sync_date', '' );
	}

	/**
	 * Saves the ISO 8601 timestamp of the last products syncing.
	 *
	 * If no timestamp is given, we assume it should be "now".
	 *
	 * @param string $date ISO 8601 timestamp.
	 */
	public function save_products_last_sync_date( string $date = '' ) {
		$this->update_option( 'products_last_sync_date', $date ? $date : gmdate( 'c' ) );
	}

	/**
	 * Saves the results of an products sync as a string.
	 *
	 * @param array $results List of results of the products sync.
	 */
	public function save_product_sync_results( array $results, bool $append = false ) {
		$results_text = '';
		foreach ( $results as $result ) {
			$results_text .= sprintf(
				'%s%s',
				$result['info'],
				PHP_EOL
			);
			/*
			$results_text .= sprintf(
				'[%s] %s%s',
				ucfirst( $result['status'] ),
				$result['info'],
				PHP_EOL
			);
			*/
		}

		if ( true === $append ) {
			$existing_results = $this->get_option( 'product_sync_results', '' );
			$results_text     = $existing_results . $results_text;
			$this->update_option( 'product_sync_results', $results_text );
		}
		$this->update_option( 'product_sync_results', $results_text );
	}

	/**
	 * Retrieves the results of last product sync.
	 *
	 * @return string Results of the product sync.
	 */
	public function get_product_sync_results(): string {
		return $this->get_option( 'product_sync_results', '' );
	}


	/**
	 * Saves the initial setup date
	 *
	 * @param string $date Date string.
	 */
	public function save_initial_setup_date( string $date = '' ) {
		$this->update_option(
			'initial_setup_date',
			$date
		);
	}

	/**
	 * Saves the initial setup found existing products
	 *
	 * @param boolean $exist If products exist.
	 */
	public function save_initial_setup_products_exist( bool $exist = false ) {
		$this->update_option(
			'initial_setup_products_exist',
			$exist
		);
	}

	/**
	 * Saves the brand id
	 *
	 * @param string $brand_id The brand id.
	 */
	public function save_brand_id( string $brand_id = '' ) {
		$this->update_option(
			'brand_id',
			$brand_id
		);
	}

	/**
	 * Saves the brand name
	 *
	 * @param string $name The brand name.
	 */
	public function save_brand_name( string $name = '' ) {
		$this->update_option(
			'brand_name',
			$name
		);
	}

	/**
	 * Saves the brand currency
	 *
	 * @param string $currency The brand currency.
	 */
	public function save_brand_currency( string $currency = '' ) {
		$this->update_option(
			'brand_currency',
			$currency
		);
	}

	/**
	 * Saves the brand locale
	 *
	 * @param string $locale The brand locale.
	 */
	public function save_brand_locale( string $locale = '' ) {
		$this->update_option(
			'brand_locale',
			$locale
		);
	}

	/**
	 * Gets sync enabled
	 */
	public function is_sync_enabled(): bool {

		static $result = null;

		if ( null !== $result ) {
			return $result;
		}

		// Add error if locale and currency do not match.
		if ( ! ( $this->get_brand_locale() && $this->get_brand_currency() && $this->validate_locale_currency() ) ) {
			$result = false;
		} else {
			$result = true;
		}

		return $result;
	}

	/**
	 * Validate if brand locale and currency matches wc
	 *
	 * @return boolean
	 */
	public function validate_locale_currency(): bool {
		global $sitepress;

		if ( $sitepress instanceof \SitePress ) {
			$wp_locale = $sitepress->get_locale_from_language_code( apply_filters( 'wpml_default_language', null ) );
		} else {
			$wp_locale = str_replace( '-', '_', get_locale() );
		}
		$brand_locale = str_replace( '-', '_', $this->get_brand_locale() );
		// phpcs:ignore WordPress.PHP.YodaConditions.NotYoda
		$locale_matches = explode( '_', $wp_locale )[0] === explode( '_', $brand_locale )[0];
		if ( true === $this->get_suppress_currency_matching() ) {
			return $locale_matches;
		} else {
			return $locale_matches && ( $this->get_brand_currency() === get_woocommerce_currency() );
		}
	}

	/**
	 * Get the 3 letter ISO country code for current WC shop base country code (2 letter ISO)
	 *
	 * @return string
	 */
	public function get_shop_iso3_country_code(): string {
		$country_iso2 = WC()->countries->get_base_country();
		return Country::convert_country_iso2_to_iso3( $country_iso2 );
	}

	/**
	 * Determines if the WC shop base country is in EU
	 *
	 * @return boolean
	 */
	public function get_shop_in_eu(): bool {
		$country_iso2 = WC()->countries->get_base_country();
		$eu_countries = WC()->countries->get_european_union_countries();
		return in_array( $country_iso2, $eu_countries ) ? true : false;
	}

	/**
	 * Retrieves the create new variations when linking setting.
	 *
	 * @return bool
	 *   The boolean setting.
	 */
	public function get_create_new_variations_when_linking(): bool {
		return $this->get_option( 'create_new_variations_when_linking', 'no' ) === 'yes';
	}

	/**
	 * Retrieves the create new products when linking setting.
	 *
	 * @return bool
	 *    The boolean setting.
	 */
	public function get_create_new_products_when_linking(): bool {
		return $this->get_option( 'create_new_products_when_linking', 'no' ) === 'yes';
	}

	/**
	 * Retrieves the product linking pending create array
	 *
	 * @return array
	 *   Array of faire products
	 */
	public function get_product_linking_create_products_csv(): array {
		return get_option( 'faire_product_linking_create_products_csv', [] );
	}

	/**
	 * Retrieves the variation linking pending create array
	 *
	 * @return array
	 *   Array of faire product variations
	 */
	public function get_product_linking_create_variations_csv(): array {
		return get_option( 'faire_product_linking_create_variations_csv', [] );
	}

	/**
	 * Determine faire geo constraint country or country_group based on WC base country
	 *
	 * @return array
	 */
	public function get_faire_geo_constraint() {
		$country_iso3   = $this->get_shop_iso3_country_code();
		$currency       = get_woocommerce_currency();
		$geo_constraint = array();

		if ( in_array( $country_iso3, array( 'USA', 'CAN', 'AUS', 'GBR' ) ) ) {
			$geo_constraint = array(
				'country'  => $country_iso3,
			);
		} elseif ( 'EUR' === $currency ) {
			$geo_constraint = array(
				'country_group' => 'EUROPEAN_UNION',
			);
		} elseif ( true === $this->get_shop_in_eu() && 'EUR' !== $currency ) {
			$geo_constraint = array(
				'country' => 'USA',
			);
		} else {
			$geo_constraint = array(
				'country'  => $country_iso3,
			);
		}
		return $geo_constraint;
	}

	/**
	 * Optional override to suppress currency matching WC and Faire
	 *
	 * @return bool
	 */
	public function get_suppress_currency_matching() {
		return apply_filters( 'faire_wc_suppress_currency_matching', false );
	}

}
