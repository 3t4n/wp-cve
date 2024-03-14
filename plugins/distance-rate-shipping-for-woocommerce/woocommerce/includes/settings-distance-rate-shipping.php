<?php
/**
 * Settings for flat rate shipping.
 *
 * @package WooCommerce\Classes\Shipping
 */

defined( 'ABSPATH' ) || exit;
global $woocommerce;
$text_domain = $this->plugin_config->get_text_domain();
$cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', $text_domain );

$settings = array(
    'title' => array(
        'title'             => __('Title', $text_domain),
        'type'              => 'text',
        'description'       => __('Title', $text_domain),
        'placeholder'       => __( 'N/A', $text_domain ),
        'default'           => $this->method_title,
        'sanitize_callback' => array( $this, 'sanitize_string_field' ),
        'desc_tip'          => true,
    ),
    'min_shipping_distance' => array(
        'title'             => __('Minimum Shipping Distance', $text_domain),
        'type'              => 'text',
        'description'       => __('Minimum Shipping Distance', $text_domain),
        'placeholder'       => __( 'N/A', $text_domain ),
        'default'           => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip'          => true,
    ),
    'max_shipping_distance' => array(
        'title'             => __('Maximum Shipping Distance', $text_domain),
        'type'              => 'text',
        'description'       => __('Maximum Shipping Distance', $text_domain),
        'placeholder'       => __( 'N/A', $text_domain ),
        'default'           => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip'          => true,
    ),
    'min_order_amount' => array(
        'title'             => __('Minimum Order Amount', $text_domain),
        'type'              => 'text',
        'description'       => __('Minimum Order Amount', $text_domain),
        'placeholder'       => __( 'N/A', $text_domain ),        
        'default'           => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip'          => true,
    ),
    'max_order_amount' => array(
        'title'             => __('Maximum Order Amount', $text_domain),
        'description'       => __('Maximum Order Amount', $text_domain),
        'placeholder'       => __( 'N/A', $text_domain ),
        'type'              => 'text',
        'default'           => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip'          => true,
    ),
    'min_order_qty' => array(
        'title'             => __('Minimum Order Qty', $text_domain),                
        'description'       => __('Minimum Order Qty', $text_domain),
        'type'              => 'text',
        'placeholder'       => __( 'N/A', $text_domain ),
        'default'           => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip'          => true,
    ),
    'max_order_qty' => array(
        'title' => __('Maximum Order Qty', $text_domain),
        'description' => __('Maximum Order Qty', $text_domain),
        'type' => 'text',
        'placeholder'       => __( 'N/A', $text_domain ),                
        'default' => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip' => true,
    ),
    'price_per_distance' => array(
        'title' => __('Price Per Distance', $text_domain),
        'description' => __('Price Per Distance', $text_domain),
        'type' => 'text',
        'placeholder'       => __( 'N/A', $text_domain ),
        'default' => '0',
        'sanitize_callback' => array( $this, 'sanitize_number_field' ),
        'desc_tip' => true,
    ),
    'tax_status' => array(
        'title'   => __( 'Tax status', $text_domain  ),
        'type'    => 'select',
        'class'   => 'wc-enhanced-select',
        'default' => 'taxable',
        'options' => array(
            'taxable' => __( 'Taxable', $text_domain  ),
            'none'    => _x( 'None', 'Tax status', $text_domain  ),
        ),
    ),
);
$shipping_classes = WC()->shipping()->get_shipping_classes();

if ( ! empty( $shipping_classes ) ) {
	$settings['class_costs'] = array(
		'title'       => __( 'Shipping class costs', $text_domain ),
		'type'        => 'title',
		'default'     => '',
		/* translators: %s: URL for link. */
		'description' => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', $text_domain  ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) ) {
			continue;
		}
		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			/* translators: %s: shipping class name */
			'title'             => sprintf( __( '"%s" shipping class cost', $text_domain  ), esc_html( $shipping_class->name ) ),
			'type'              => 'text',
			'placeholder'       => __( 'N/A', $text_domain  ),
			'description'       => $cost_desc,
			'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names.
			'desc_tip'          => true,
			'sanitize_callback' => array( $this, 'sanitize_cost' ),
		);
	}

	$settings['no_class_cost'] = array(
		'title'             => __( 'No shipping class cost', $text_domain ),
		'type'              => 'text',
		'placeholder'       => __( 'N/A', $text_domain ),
		'description'       => $cost_desc,
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_cost' ),
	);

	$settings['type'] = array(
		'title'   => __( 'Calculation type', $text_domain ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'class',
		'options' => array(
			'class' => __( 'Per class: Charge shipping for each shipping class individually', $text_domain ),
			'order' => __( 'Per order: Charge shipping for the most expensive shipping class', $text_domain ),
		),
	);
}

return $settings;