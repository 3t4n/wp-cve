<?php

	/**
	 * Single Product Meta | tags | Category | Sku
	 *
	 * @version 3.0.0
	 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	$id = get_the_id();

if ( shop_ready_is_elementor_mode() ) {

	if ( is_numeric( $settings['wready_product_id'] ) ) {
		$id = $settings['wready_product_id'];
	}
}

	global $product;
	$product = is_null( $product ) ? wc_get_product( $id ) : $product;
if ( ! is_object( $product ) ) {
	return;
}
if ( ! method_exists( $product, 'get_sku' ) ) {
	return;
}

	$seperator = $settings['separator'];

?>
<div class="product_meta">

    <?php do_action( 'woocommerce_product_meta_start' ); ?>
    <!-- Sku -->
    <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

    <?php
		if ( $settings['show_sku'] == 'yes' && method_exists( $product, 'get_sku' ) ) :
				 $sku = $product->get_sku() != '' ? $product->get_sku() : esc_html__( 'N/A', 'shopready-elementor-addon' );
			?>
    <span class="sku_wrapper">
        <?php echo esc_html( $settings['show_sku_label'] == 'yes' ? esc_html( $settings['show_sku_label_text'] ) : '' ); ?>
        <span class="sku"><?php echo wp_kses_post( $sku ); ?></span>
    </span>
    <?php endif; ?>

    <?php endif; ?>
    <!-- Category -->
    <?php if ( $settings['show_cat'] == 'yes' ) : ?>

    <?php if ( $settings['show_cat_label'] == 'yes' ) : ?>

    <?php echo wp_kses_post( wc_get_product_category_list( $product->get_id(), $seperator, '<span class="posted_in">' . _n( 'Product Category', 'Product Categories', count( $product->get_category_ids() ), 'shopready-elementor-addon' ) . ' ', '</span>' ) ); ?>

    <?php else : ?>

    <?php echo wp_kses_post( wc_get_product_category_list( $product->get_id(), $seperator ) ); ?>

    <?php endif; ?>

    <?php endif; ?>
    <!-- Tags -->
    <?php if ( $settings['show_tags'] == 'yes' ) : ?>
    <?php if ( $settings['show_tags_label'] == 'yes' ) : ?>
    <?php echo wp_kses_post( wc_get_product_tag_list( $product->get_id(), $seperator, '<span class="tagged_as">' . _n( 'Product Tag', 'Product Tags', count( $product->get_tag_ids() ), 'shopready-elementor-addon' ) . ' ', '</span>' ) ); ?>
    <?php else : ?>
    <?php echo wp_kses_post(wc_get_product_tag_list( $product->get_id(), $seperator )); ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>