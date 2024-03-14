<?php
/**
 * Product quantity inputs
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

	$on_change_qty  = WReady_Helper::get_global_setting('shop_ready_pro_cart_update_on_change_qty','no');
	if(shop_ready_is_elementor_mode()){
		$item_key = '';
	}else{

		if(is_product()){
			$item_key = '';
		}else{
			preg_match("/\[(.*?)\]/", $input_name, $matches);
			$item_key = isset($matches[1]) ? $matches[1] : '';
		}
	}

	if ( $max_value && $min_value === $max_value ) {
		?>
<div class="quantity hidden">
    <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty"
        name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
</div>
<?php
	} else {
		/* translators: %s: Quantity. */
		$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'shopready-elementor-addon' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'shopready-elementor-addon' );
		?>
<div class="wooready_product_quantity tpl-gl-qty-input">
    <div class="product-quantity">
        <label class="screen-reader-text"
            for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>
        <button type="button"
            class="woo-ready-qty-sub <?php echo esc_attr($on_change_qty == 'yes'?'woo-ready-qty-sub-js':''); ?>">-</button>
        <input type="number" id="<?php echo esc_attr( $input_id ); ?>"
            class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>" step="<?php echo esc_attr( $step ); ?>"
            min="<?php echo esc_attr( $min_value ); ?>"
            max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
            name="<?php echo esc_attr( $input_name ); ?>" item_key="<?php echo esc_attr( $item_key ); ?>"
            value="<?php echo esc_attr( $input_value ); ?>"
            title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'shopready-elementor-addon' ); ?>"
            size="4" placeholder="<?php echo esc_attr( $placeholder ); ?>"
            inputmode="<?php echo esc_attr( $inputmode ); ?>" />
        <button type="button"
            class="woo-ready-qty-add <?php echo esc_attr($on_change_qty == 'yes'?'woo-ready-qty-add-js':''); ?>">+</button>
        <?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
    </div>
</div>
<?php
	}