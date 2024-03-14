<?php
/**
 * Fields
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta;

use NovaPoshta\Main;
use WC_Shipping_Rate;
use NovaPoshta\Api\Api;
use NovaPoshta\Language;
use NovaPoshta\Settings\Settings;

/**
 * Class User
 *
 * @package NovaPoshta
 */
class Fields {

	/**
	 * API for Nova Poshta
	 *
	 * @var API
	 */
	private $api;

	/**
	 * Settings
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Plugin language
	 *
	 * @var Language
	 */
	private $language;

	/**
	 * User constructor.
	 *
	 * @param API      $api      API for Nova Poshta.
	 * @param Settings $settings Settings.
	 * @param Language $language Plugin language.
	 */
	public function __construct( API $api, Settings $settings, Language $language ) {

		$this->api      = $api;
		$this->settings = $settings;
		$this->language = $language;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'wp_ajax_shipping_nova_poshta_for_woocommerce_city', [ $this, 'cities' ] );
		add_action( 'wp_ajax_nopriv_shipping_nova_poshta_for_woocommerce_city', [ $this, 'cities' ] );
		add_action( 'wp_ajax_shipping_nova_poshta_for_woocommerce_warehouse', [ $this, 'warehouses' ] );
		add_action( 'wp_ajax_nopriv_shipping_nova_poshta_for_woocommerce_warehouse', [ $this, 'warehouses' ] );

		if ( 'billing' === $this->settings->place_for_fields() ) {
			add_filter( 'woocommerce_billing_fields', [ $this, 'billing_fields' ] );
			add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'fragments' ] );
			add_action( 'woocommerce_checkout_update_order_review', [ $this, 'reload_after_country_change' ] );

			return;
		}

		add_action( 'woocommerce_after_shipping_rate', [ $this, 'shipping_method_fields' ] );
	}

	/**
	 * Reload page after a country change.
	 *
	 * @param array $post_data Post data.
	 */
	public function reload_after_country_change( $post_data ) {

		$post_data = wp_parse_args( $post_data );
		$country   = ! empty( $post_data['shipping_country'] ) ? $post_data['shipping_country'] : '';
		$country   = empty( $country ) && ! empty( $post_data['billing_country'] ) ? $post_data['billing_country'] : '';

		if ( 'UA' !== $country ) {
			return;
		}

		if ( WC()->customer->get_shipping_country() === 'UA' ) {
			return;
		}

		WC()->session->reload_checkout = true;
	}

	/**
	 * Add fragments.
	 *
	 * @param array $fragments List of fragments for refreshing.
	 *
	 * @return mixed
	 */
	public function fragments( array $fragments ): array {

		foreach ( $this->fields() as $key => $field ) {
			if ( in_array( NovaPoshta::ID, wc_get_chosen_shipping_method_ids(), true ) ) {
				$fragments[ '#' . $key . '_field' ] = $this->get_field_markup( $key, $field );

				continue;
			}

			if ( ! isset( $fragments[ '#' . $key . '_field' ] ) ) {
				$fragments[ '#' . $key . '_field' ] = $this->get_field_wrapper_markup( $key );
			}
		}

		return $fragments;
	}

	/**
	 * Get field markup.
	 *
	 * @param string $field_name Field name.
	 * @param array  $settings   Field settings.
	 *
	 * @return string
	 */
	private function get_field_markup( string $field_name, array $settings ): string {

		ob_start();
		woocommerce_form_field( $field_name, $settings );

		return ob_get_clean();
	}

	/**
	 * Hidden field wrapper for field.
	 *
	 * @param string $field_name Field name.
	 *
	 * @return string
	 */
	private function get_field_wrapper_markup( string $field_name ): string {

		return sprintf(
			'<p id="%s_field" style="display: none"></p>',
			esc_attr( $field_name )
		);
	}

	/**
	 * List of fields.
	 *
	 * @return array
	 */
	private function fields(): array {

		$city_id      = $this->get_current_city_id();
		$city         = ! empty( $city_id ) ? $this->api->city( $city_id ) : '';
		$warehouse_id = $this->get_current_warehouse_id();
		$warehouses   = ! empty( $city_id ) ? $this->api->warehouses( $city_id ) : [ '' => '' ];
		$required     = in_array( NovaPoshta::ID, wc_get_chosen_shipping_method_ids(), true );
		$required     = $required ? apply_filters( 'shipping_nova_poshta_for_woocommerce_is_required_field', true, NovaPoshta::ID ) : false;

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$fields = [
			'shipping_nova_poshta_for_woocommerce_nonce'     => [
				'type'    => 'hidden',
				'default' => wp_create_nonce( Main::PLUGIN_SLUG . '-shipping' ),
			],
			'shipping_nova_poshta_for_woocommerce_city'      => [
				'type'        => 'select',
				'label'       => esc_html__( 'Select delivery city', 'shipping-nova-poshta-for-woocommerce' ),
				'required'    => $required,
				'class'       => [ 'form-row-wide' ],
				'options'     => [ $city_id => $city ],
				'default'     => $city_id,
				'placeholder' => esc_html__( 'Select delivery city', 'shipping-nova-poshta-for-woocommerce' ),
				'priority'    => 120,
			],
			'shipping_nova_poshta_for_woocommerce_warehouse' => [
				'type'        => 'select',
				'label'       => esc_html__( 'Choose branch', 'shipping-nova-poshta-for-woocommerce' ),
				'required'    => $required,
				'class'       => [ 'form-row-wide' ],
				'options'     => $warehouses,
				'default'     => $warehouse_id,
				'priority'    => 130,
				'placeholder' => esc_html__( 'Choose branch', 'shipping-nova-poshta-for-woocommerce' ),
			],
		];
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		// Compatibility with WooCommerce Checkout Manager plugin.
		if ( ! empty( $city_id ) ) {
			unset( $fields['shipping_nova_poshta_for_woocommerce_city']['placeholder'], $fields['shipping_nova_poshta_for_woocommerce_warehouse']['placeholder'] );
		}

		return $fields;
	}

	/**
	 * Billing fields.
	 *
	 * @param array $fields List of fields.
	 *
	 * @return array
	 */
	public function billing_fields( array $fields ): array {

		return array_merge( $fields, $this->fields() );
	}

	/**
	 * Fields for nova poshta
	 *
	 * @param WC_Shipping_Rate $shipping_rate Shipping rate.
	 */
	public function shipping_method_fields( WC_Shipping_Rate $shipping_rate ) {

		// The same hook work on cart page.
		if ( ! is_checkout() ) {
			return;
		}

		if ( 'shipping_method' !== $this->settings->place_for_fields() ) {
			return;
		}

		$shipping_methods = wc_get_chosen_shipping_method_ids();
		if ( ! in_array( NovaPoshta::ID, $shipping_methods, true ) || NovaPoshta::ID !== $shipping_rate->get_method_id() ) {
			return;
		}

		foreach ( $this->fields() as $key => $field ) {
			do_action( 'shipping_nova_poshta_for_woocommerce_before_field', $key, $field );
			woocommerce_form_field( $key, $field );
			do_action( 'shipping_nova_poshta_for_woocommerce_after_field', $key, $field );
		}
	}

	/**
	 * Get current city ID.
	 *
	 * @return string
	 */
	private function get_current_city_id(): string { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ) {
			return sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$post_data = ! empty( $_POST['post_data'] ) ? wp_parse_args( wp_unslash( $_POST['post_data'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( ! empty( $post_data['shipping_nova_poshta_for_woocommerce_city'] ) ) {
			return (string) $post_data['shipping_nova_poshta_for_woocommerce_city'];
		}

		$user_id = get_current_user_id();
		$city_id = apply_filters( 'shipping_nova_poshta_for_woocommerce_default_city_id', '', $user_id );

		if ( $city_id ) {
			return $city_id;
		}

		$city = apply_filters(
			'shipping_nova_poshta_for_woocommerce_default_city',
			'',
			$user_id,
			$this->language->get_current_language()
		);

		if ( empty( $city ) ) {
			return '';
		}

		$cities = $this->api->cities( $city, 1 );

		if ( empty( $cities ) ) {
			return '';
		}

		$cities = array_keys( $cities );

		return array_pop( $cities );
	}

	/**
	 * Get current warehouse ID.
	 *
	 * @return string
	 */
	private function get_current_warehouse_id(): string {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ) {
			return sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return '';
	}

	/**
	 * List of the cities by search field
	 */
	public function cities() {

		check_ajax_referer( Main::PLUGIN_SLUG, 'nonce' );
		$search = ! empty( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		$cities = $this->api->cities( $search );
		foreach ( $cities as $key => $city ) {
			$cities[ $key ] = [
				'id'   => $key,
				'text' => $city,
			];
		}
		wp_send_json( array_values( $cities ) );
	}

	/**
	 * List of warehouses by city
	 */
	public function warehouses() {

		check_ajax_referer( Main::PLUGIN_SLUG, 'nonce' );
		$city       = ! empty( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
		$warehouses = $this->api->warehouses( $city );
		foreach ( $warehouses as $key => $warehouse ) {
			$warehouses[ $key ] = [
				'id'   => $key,
				'text' => $warehouse,
			];
		}
		wp_send_json( array_values( $warehouses ) );
	}
}
