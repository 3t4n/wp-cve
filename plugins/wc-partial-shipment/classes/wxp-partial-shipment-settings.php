<?php
if (!defined('ABSPATH')) {
	exit;
	// Exit if accessed directly
}
class Wxp_Partial_Shipment_Settings{

	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_wxp_partial_shipping_settings', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_wxp_partial_shipping_settings', __CLASS__ . '::update_settings' );
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['wxp_partial_shipping_settings'] = __('Partial Shipment', 'wxp-partial-shipment' );
		return $settings_tabs;
	}

	public static function settings_tab(){
		woocommerce_admin_fields( self::get_settings() );
	}

	public static function update_settings(){
		woocommerce_update_options( self::get_settings() );
	}

	public static function get_settings() {

		$settings = array(
			'section_title' => array(
				'name'     => __('Woocommerce Partial Shipment Settings','wxp-partial-shipment'),
				'type'     => 'title',
				'id'       => 'wxp_partial_shipping_settings_section_title'
			),
			'partially_shipped' => array(
				'title'   => __('Add Status "Partially Shipped"','wxp-partial-shipment'),
				'desc'    => __('Add new order status called "Partially Shipped".','wxp-partial-shipment'),
				'id'      => 'partially_shipped_status',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			'auto_complete' => array(
				'title'   => __('Switch Status to "completed"','wxp-partial-shipment'),
				'desc'    => __('Auto Switch order status to "completed", if all products are shipped.','wxp-partial-shipment'),
				'id'      => 'partially_auto_complete',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			'partially_hide_status' => array(
				'title'   => __('Hide Status','wxp-partial-shipment'),
				'desc'    => __('Hide status label on order detail page until products are not partially shipped.','wxp-partial-shipment'),
				'id'      => 'partially_hide_status',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			'partially_enable_status_popup' => array(
				'title'   => __('Display status in order popup','wxp-partial-shipment'),
				'desc'    => __('Display status in order popup at order list page.','wxp-partial-shipment'),
				'id'      => 'partially_enable_status_popup',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id' => 'wxp_partial_shipping_settings_section_end'
			)
		);

		return apply_filters('wxp_partial_shipping_settings',$settings);
	}

}
?>