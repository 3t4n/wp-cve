<?php
/**
 * Product metabox html
 *
 * @var WC_Product $product Current product.
 *
 * @package NovaPosta\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>
	</div>
	<div class="options_group">
<?php
woocommerce_wp_text_input(
	[
		'id'                => 'weight_formula',
		'label'             => sprintf(
			'%s<span class="shipping-nova-poshta-for-woocommerce-pro"></span>',
			esc_html__( 'Formula for weight calculate', 'shipping-nova-poshta-for-woocommerce' )
		),
		'placeholder'       => '[qty] * 0.5',
		'desc_tip'          => true,
		'description'       => esc_html__( 'Formula cost calculation. The numbers are indicated in kilograms. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ),
		'custom_attributes' => [
			'disabled' => true,
		],
	]
);

woocommerce_wp_text_input(
	[
		'id'                => 'width_formula',
		'label'             => sprintf(
			'%s<span class="shipping-nova-poshta-for-woocommerce-pro"></span>',
			esc_html__( 'Formula for width calculate', 'shipping-nova-poshta-for-woocommerce' )
		),
		'placeholder'       => '[qty] * 0.26',
		'desc_tip'          => true,
		'description'       => esc_html__( 'Formula cost calculation. The numbers are indicated in meters. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ),
		'custom_attributes' => [
			'disabled' => true,
		],
	]
);

woocommerce_wp_text_input(
	[
		'id'                => 'length_formula',
		'label'             => sprintf(
			'%s<span class="shipping-nova-poshta-for-woocommerce-pro"></span>',
			esc_html__( 'Formula for length calculate', 'shipping-nova-poshta-for-woocommerce' )
		),
		'placeholder'       => '[qty] * 0.145',
		'desc_tip'          => true,
		'description'       => esc_html__( 'Formula cost calculation. The numbers are indicated in meters. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ),
		'custom_attributes' => [
			'disabled' => true,
		],
	]
);

woocommerce_wp_text_input(
	[
		'id'                => 'height_formula',
		'label'             => sprintf(
			'%s<span class="shipping-nova-poshta-for-woocommerce-pro"></span>',
			esc_html__( 'Formula for height calculate', 'shipping-nova-poshta-for-woocommerce' )
		),
		'placeholder'       => '[qty] * 0.1',
		'desc_tip'          => true,
		'description'       => esc_html__( 'Formula cost calculation. The numbers are indicated in meters. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ),
		'custom_attributes' => [
			'disabled' => true,
		],
	]
);
