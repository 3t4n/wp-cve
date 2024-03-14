<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' );

class Elex_WCCR_Settings extends WC_Settings_Page {

	public $id;

	public $user_adjustment_settings;

	public $restriction_table;
	
	public function __construct() {
		$this->init();
		$this->id = 'elex-wccr';
	}
	public function init() {
		$this->user_adjustment_settings = get_option( 'elex_wccr_checkout_restriction_settings', array() );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'elex_wccr_add_settings_tab' ), 50 );
		add_filter( 'woocommerce_sections_elex-wccr', array( $this, 'output_sections' ) );
	
		add_filter( 'woocommerce_settings_elex-wccr', array( $this, 'elex_wccr_output_settings' ) );
		
		add_action( 'woocommerce_update_options_elex-wccr', array( $this, 'elex_wccr_update_settings' ) );
		add_action( 'woocommerce_admin_field_checkoutrestrictiontable', array( $this, 'elex_wccr_admin_field_checkoutrestrictiontable' ) );
		
	}
	public function elex_wccr_add_settings_tab( $settings_tabs ) {
		$settings_tabs['elex-wccr'] = esc_html__( 'Minimum Order Amount', 'elex-wc-checkout-restriction' );
		return $settings_tabs;
	}
	public function get_sections() {
		$sections = array(
			''                           => __( 'Minimum Order Amount', 'elex-wc-checkout-restriction' ),
			'min-order-related-products' => __( '<li><strong><font color="red">Related Products!</font></strong></li>', 'elex-wc-checkout-restriction' ),
		);
		
		/**
		 * To woocommerce get sections.
		 *
		 * @since  1.0.0
		 */
		return apply_filters( 'woocommerce_get_sections_minimum_order_amount', $sections );
	}
	public function output_sections() {
		global $current_section;
		$sections = $this->get_sections();
		if ( empty( $sections ) || 1 === count( $sections ) ) {
			return;
		}
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );
		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . wp_kses_post( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
		}
		echo '</ul><br class="clear" />';
	}
	public function elex_wccr_output_settings() {
		$settings = $this->elex_wccr_get_settings();

		global $current_section;
		if ( '' === $current_section ) {
			WC_Admin_Settings::output_fields( $settings );

		}
		if ( 'min-order-related-products' === $current_section ) {
			wp_enqueue_style( 'bootstrap', plugins_url( '../assests/css/bootstrap.css', __FILE__ ), false, true );
			include_once 'market.php';
		}

	}

	
	public function elex_wccr_update_settings() {
		$options = $this->elex_wccr_get_settings();
		woocommerce_update_options( $options );
		$this->user_adjustment_settings = get_option( 'elex_wccr_checkout_restriction_settings', array() );
	}
	public function elex_wccr_admin_field_checkoutrestrictiontable( $settings ) {
		include( 'elex-wccr-restriction-table.php' );
	}

	public function elex_wccr_get_settings() {
		$settings = array(
			'elex_restricton_settings' => array(
				'type' => 'checkoutrestrictiontable',
				'id' => 'elex_wccr_checkout_restriction_settings',
				'value' => '',
			)
		);
		return $settings;
	}
}
new Elex_WCCR_Settings();
