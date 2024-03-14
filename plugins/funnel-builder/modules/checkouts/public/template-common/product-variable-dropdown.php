<?php


if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}

/**
 * @var $product WC_Product_Variable
 */
global $product;
$get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
$available_variations = $get_variations ? $product->get_available_variations() : false;
$attributes           = $product->get_variation_attributes();
$selected_attributes  = $product->get_default_attributes();
$attribute_keys       = array_keys( $attributes ); ?>
<div class='wfacp_variable_product_gallery' style="float: left;width:48%">
	<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
</div>
<div class='wfacp_variable_product_options' style="float: left;width:48%">
    <form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ); // WPCS: XSS ok. ?>">
		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
		<?php else : ?>
            <table class="variations" cellspacing="0">
                <tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr>
                        <td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label>
                        </td>
                        <td class="value">
							<?php
							WFACP_Common::wc_dropdown_variation_attribute_options( array(
								'options'   => $options,
								'attribute' => $attribute_name,
								'product'   => $product,
							) );
							echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>

		<?php endif; ?>
        <div class="single_variation_wrap">
            <div class="woocommerce-variation single_variation"></div>
        </div>
    </form>
</div>
<?php
unset( $get_variations, $available_variations, $attributes, $selected_attributes );
?>
