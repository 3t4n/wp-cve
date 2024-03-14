<?php // phpcs:ignore WordPress.NamingConventions
/**
 * GENERAL ARRAY OPTIONS
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogether
 */

$general = array(

	'general' => array(

		array(
			'title' => __( 'General Options', 'yith-woocommerce-frequently-bought-together' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'yith-wcfbt-general-options',
		),

		array(
			'id'        => 'yith-wfbt-form-title',
			'name'      => __( 'Box title', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'Title shown on "Frequently Bought Together" box.', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Frequently Bought Together', 'yith-woocommerce-frequently-bought-together' ),
		),

		array(
			'id'        => 'yith-wfbt-total-label',
			'name'      => __( 'Total label', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'This is the label shown for total price label.', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Price for all', 'yith-woocommerce-frequently-bought-together' ),
		),

		array(
			'id'        => 'yith-wfbt-button-label',
			'name'      => __( 'Button label', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'This is the label shown for "Add to cart" button.', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Add all to Cart', 'yith-woocommerce-frequently-bought-together' ),
		),

		array(
			'id'        => 'yith-wfbt-button-color',
			'name'      => __( 'Button Color', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'Select button background color', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'colorpicker',
			'default'   => '#222222',
		),

		array(
			'id'        => 'yith-wfbt-button-color-hover',
			'name'      => __( 'Button Hover Color', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'Select button background hover color', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'colorpicker',
			'default'   => '#777777',
		),

		array(
			'id'        => 'yith-wfbt-button-text-color',
			'name'      => __( 'Button Text Color', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'Select button text color', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'colorpicker',
			'default'   => '#ffffff',
		),

		array(
			'id'        => 'yith-wfbt-button-text-color-hover',
			'name'      => __( 'Button Text Hover Color', 'yith-woocommerce-frequently-bought-together' ),
			'desc'      => __( 'Select button text hover color', 'yith-woocommerce-frequently-bought-together' ),
			'type'      => 'yith-field',
			'yith-type' => 'colorpicker',
			'default'   => '#ffffff',
		),

		array(
			'type' => 'sectionend',
			'id'   => 'yith-wcfbt-general-options',
		),
	),
);

return apply_filters( 'yith_wcfbt_panel_general_options', $general );
