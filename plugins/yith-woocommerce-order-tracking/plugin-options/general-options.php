<?php
/**
 * General options tab
 *
 * @package YITH\OrderTracking\PluginOptions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$general_options = array(
	'general' => array(
		'admin_options_section'        => array(
			'name' => __( 'Admin options', 'yith-woocommerce-order-tracking' ),
			'type' => 'title',
			'id'   => 'ywot_admin_options_section',
		),
		'carrier_default_name'         => array(
			'name'      => __( 'Default carrier', 'yith-woocommerce-order-tracking' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'id'        => 'ywot_carrier_default_name',
			'desc'      => __( 'Enter the carrier to show by default in the Order Details page.', 'yith-woocommerce-order-tracking' ),
		),
		'admin_options_section_end'    => array(
			'type' => 'sectionend',
			'id'   => 'ywot_admin_options_section_end',
		),
		'user_options_section'         => array(
			'name' => __( 'User options', 'yith-woocommerce-order-tracking' ),
			'type' => 'title',
			'id'   => 'ywot_user_options_section',
		),
		'order_tracking_text'          => array(
			'name'          => __( 'Text to show in the Order Details page', 'yith-woocommerce-order-tracking' ),
			'type'          => 'yith-field',
			'yith-type'     => 'textarea-editor',
			'id'            => 'ywot_order_tracking_text',
			'default'       => __( 'Your order has been picked up by <b>[carrier_name]</b> on <b>[pickup_date]</b>. Your tracking code is <b>[track_code]</b>. Live tracking on [carrier_link]', 'yith-woocommerce-order-tracking' ),
			'desc'          => __( 'Set the text to show in the Order Details page. Customize the text using these placeholders to provide the real shipping information: <br><b>[carrier_name]</b>, <b>[pickup_date]</b>, <b>[track_code]</b>, <b>[carrier_link]</b>', 'yith-woocommerce-order-tracking' ),
			'wpautop'       => false,
			'media_buttons' => false,
			'textarea_rows' => 4,
		),
		'order_tracking_text_position' => array(
			'name'      => __( 'Position of the text in the Order Details page', 'yith-woocommerce-order-tracking' ),
			'type'      => 'yith-field',
			'yith-type' => 'radio',
			'id'        => 'ywot_order_tracking_text_position',
			'desc'      => __( 'Choose the position of the text in the Order Details page.', 'yith-woocommerce-order-tracking' ),
			'options'   => array(
				'1' => __( 'Top - Before products list', 'yith-woocommerce-order-tracking' ),
				'2' => __( 'Bottom - After products list', 'yith-woocommerce-order-tracking' ),
			),
			'default'   => '1',
		),
		'user_options_section_end'     => array(
			'type' => 'sectionend',
			'id'   => 'ywot_user_options_section_end',
		),
	),
);

return apply_filters( 'yith_ywot_general_options', $general_options );
