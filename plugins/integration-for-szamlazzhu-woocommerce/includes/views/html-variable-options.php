<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div>
	<?php
	woocommerce_wp_text_input( array(
		'id' => 'wc_szamlazz_mennyisegi_egyseg[' . $loop . ']',
		'label' => esc_html__('Unit type', 'wc-szamlazz'),
		'placeholder' => esc_html__('pcs', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_mennyisegi_egyseg', true )),
		'description' => esc_html__('This is the unit type for the line item on the invoice. The default value is set in the plugin settings.', 'wc-szamlazz'),
		'wrapper_class' => 'form-row'
	));

	woocommerce_wp_text_input( array(
		'id' => 'wc_szamlazz_megjegyzes[' . $loop . ']',
		'label' => esc_html__('Line item comment', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_megjegyzes', true )),
		'description' => esc_html__('This note will be visible on the invoice line item.', 'wc-szamlazz'),
		'wrapper_class' => 'form-row'
	));

	woocommerce_wp_text_input( array(
		'id' => 'wc_szamlazz_tetel_nev[' . $loop . ']',
		'label' => esc_html__('Line item name', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_tetel_nev', true )),
		'description' => esc_html__('Enter a custom name that will appear on the invoice. Default is the name of the product.', 'wc-szamlazz'),
		'wrapper_class' => 'form-row'
	));

	woocommerce_wp_text_input(array(
		'id' => 'wc_szamlazz_custom_cost[' . $loop . ']',
		'label' => esc_html__('Cost on invoice', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_custom_cost', true )),
		'description' => esc_html__('You can overwrite the price of the product on the invoice with this option(enter a net price).', 'wc-szamlazz'),
		'wrapper_class' => 'form-row'
	));

	woocommerce_wp_checkbox(array(
		'id' => 'wc_szamlazz_disable_auto_invoice[' . $loop . ']',
		'label' => esc_html__('Turn off auto invoicing', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_disable_auto_invoice', true )),
		'description' => esc_html__('If checked, no invoice will be automatically issued for the order if this product is included in the order.', 'wc-szamlazz'),
		'wrapper_class' => 'wc-szamlazz-product-options-checkbox'
	));

	woocommerce_wp_checkbox(array(
		'id' => 'wc_szamlazz_hide_item[' . $loop . ']',
		'label' => esc_html__('Hide from invoice', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr(get_post_meta( $variation->ID, 'wc_szamlazz_hide_item', true )),
		'description' => esc_html__('If checked, this product will be hidden on the invoices.', 'wc-szamlazz'),
		'wrapper_class' => 'wc-szamlazz-product-options-checkbox'
	));
	?>
</div>
