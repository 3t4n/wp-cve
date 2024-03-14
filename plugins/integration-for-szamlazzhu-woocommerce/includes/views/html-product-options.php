<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="options_group hide_if_variable hide_if_grouped">
	<h4 style="padding-left:12px;"><?php esc_html_e('Számlázz.hu invoice settings', 'wc-szamlazz'); ?></h4>
	<?php
	woocommerce_wp_text_input(array(
		'id' => 'wc_szamlazz_mennyisegi_egyseg',
		'label' => esc_html__('Unit type', 'wc-szamlazz'),
		'placeholder' => esc_html__('pcs', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr( $post->wc_szamlazz_mennyisegi_egyseg ),
		'description' => esc_html__('This is the unit type for the line item on the invoice. The default value is set in the plugin settings.', 'wc-szamlazz'),
	));
	?>
	<?php
	woocommerce_wp_text_input(array(
		'id' => 'wc_szamlazz_megjegyzes',
		'label' => esc_html__('Line item comment', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => get_post_meta($post->ID, 'wc_szamlazz_megjegyzes', true),
		'description' => esc_html__('This note will be visible on the invoice line item.', 'wc-szamlazz'),
	));
	?>
	<?php
	woocommerce_wp_text_input(array(
		'id' => 'wc_szamlazz_tetel_nev',
		'label' => esc_html__('Line item name', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr( $post->wc_szamlazz_tetel_nev ),
		'description' => esc_html__('Enter a custom name that will appear on the invoice. Default is the name of the product.', 'wc-szamlazz'),
	));
	?>
	<?php
	woocommerce_wp_checkbox(array(
		'id' => 'wc_szamlazz_disable_auto_invoice',
		'label' => esc_html__('Turn off auto invoicing', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr( $post->wc_szamlazz_disable_auto_invoice ),
		'description' => esc_html__('If checked, no invoice will be automatically issued for the order if this product is included in the order.', 'wc-szamlazz')
	));
	?>
	<?php
	woocommerce_wp_checkbox(array(
		'id' => 'wc_szamlazz_hide_item',
		'label' => esc_html__('Hide from invoice', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr( $post->wc_szamlazz_hide_item ),
		'description' => esc_html__('If checked, this product will be hidden on the invoices.', 'wc-szamlazz')
	));
	?>
	<?php
	woocommerce_wp_text_input(array(
		'id' => 'wc_szamlazz_custom_cost',
		'label' => esc_html__('Cost on invoice', 'wc-szamlazz'),
		'desc_tip' => true,
		'value' => esc_attr( $post->wc_szamlazz_custom_cost ),
		'description' => esc_html__('You can overwrite the price of the product on the invoice with this option(enter a net price).', 'wc-szamlazz'),
	));
	?>
</div>
