<?php


if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

if ( apply_filters( 'wfacp_show_form_coupon', $this->have_coupon_field ) ) {

	return;
}

$coupon_cls      = 'wfacp-col-left-half';
$coupon_left_cls = 'wfacp-col-left-half';


if ( $this->template_type == 'embed_form' ) {
	$coupon_cls      = 'wfacp-col-full';
	$coupon_left_cls = 'wfacp-col-full';

}

$wfacp_default_coupon_text = apply_filters( 'wfacp_default_coupon_text', __( 'Coupon code', 'woocommerce' ) );
$apply_coupon_button_text = apply_filters( 'wfacp_apply_coupon_button_text', __( 'Apply', 'woocommerce' ) )

?>
<div class="wfacp-coupon-section wfacp_custom_row_wrap clearfix">
    <div class="wfacp-coupon-page">
        <div class="woocommerce-form-coupon-toggle wfacp-woocom-coupon">
			<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', ' <a href="#" class="showcoupon">' . __( 'Have a coupon?', 'woocommerce' ) . ' ' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' ), 'notice' ); ?>
        </div>

        <div class="wfacp-row">
            <div class="wfacp_coupon_msg"></div>
        </div>

        <form class="checkout_coupon woocommerce-form-coupon " method="post" style="display:none">

            <div class="wfacp-row">
                <p class="wfacp-coupon-code-title wfacp-col-full "><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></p>

                <p class="form-row form-row-first wfacp-form-control-wrapper <?php echo $coupon_left_cls; ?> wfacp-input-form wfacp-coupon-code-input-wrap">
                    <label for="coupon_code" class="wfacp-form-control-label "><?php echo $wfacp_default_coupon_text; ?></label>
                    <input type="text" name="coupon_code" class="input-text wfacp-form-control" placeholder="<?php echo $wfacp_default_coupon_text; ?>" id="coupon_code" value=""/>
                </p>
                <p class="form-row form-row-last <?php echo $coupon_cls; ?> wfacp-coupon-code-btn-wrap">
                    <button type="submit" class="button wfacp-coupon-btn" name="apply_coupon" value="<?php echo $apply_coupon_button_text; ?>">
						<?php echo $apply_coupon_button_text; ?></button>
                </p>
                <div class="clear"></div>
            </div>

        </form>
    </div>
</div>
