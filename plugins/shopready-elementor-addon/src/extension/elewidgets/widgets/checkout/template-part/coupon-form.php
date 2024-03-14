<?php
if(!defined('ABSPATH')){
    exit;
  }
/**
 * Coupon Form for checkout 
 * @since 1.0
 * 
 */
	use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

	$css_style = 'style="display:none"';

	if( $settings['wready_coupon_collapsible'] != 'yes' ){
		$css_style = 'inherit';	
	}
	
	$apply_code_label    = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_code_label','Apply Code');
	$apply_coupon_label  = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_coupon_label','Apply Coupon');
	$coupon_label        = WReady_Helper::get_global_setting('woo_ready_widget_cart_apply_coupon_label','Coupon:');
  	
?>

<?php if( $settings['wready_coupon_collapsible'] =='yes' ): ?>
<div class="woocommerce-form-coupon-toggle">
    <?php wp_kses_post(wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', '<span>'.esc_html( $settings['coupon_heading_colapse_msg_text'] ).'</span>' . ' <a href="#" class="showcoupon">' . esc_html($settings['coupon_heading_colapse_text']) . '</a>' ), 'notice' )); ?>
</div>
<?php endif; ?>

<form class="checkout_coupon woocommerce-form-coupon" method="post" <?php echo wp_kses_post($css_style); ?>>
    <div class="wready-coupon-row-wrapper">

        <?php if( $settings[ 'show_coupon_heading' ] == 'yes' ): ?>
        <p class="wready-coupon-heading-col"><?php echo esc_html( $settings[ 'coupon_heading_text' ] ); ?></p>
        <?php endif; ?>

        <p class="woo-ready-coupon-col">
            <input type="text" name="coupon_code" class="input-text"
                placeholder="<?php echo esc_attr($apply_code_label); ?>" id="coupon_code" value="" />
        </p>

        <p class="wready-coup-btn-col">
            <button type="submit" name="apply_coupon"
                value="<?php echo esc_attr($apply_coupon_label); ?>"><?php echo esc_html($apply_coupon_label); ?></button>
        </p>
    </div>
</form>