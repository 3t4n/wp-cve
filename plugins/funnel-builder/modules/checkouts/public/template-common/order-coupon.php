<?php

if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}
$settings          = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
$is_disable_coupon = ( isset( $settings['disable_coupon'] ) && 'true' == $settings['disable_coupon'] );
if ( $is_disable_coupon ) {
	return;
}
$instance       = wfacp_template();
$checkout_field = $instance->get_checkout_fields();
if ( ! isset( $checkout_field['advanced']['order_coupon'] ) ) {
	return '';
}
$coupon_cls = 'wfacp-col-left-half';
if ( $instance->get_template_type() == 'embed_form' ) {
	$coupon_cls = 'wfacp-col-full';
}
$is_collapsible = $checkout_field['advanced']['order_coupon']['coupon_style'];
if ( ! empty( $field ) ) {
	$args = WC()->session->get( 'order_coupon_' . WFACP_Common::get_id(), $field );
}

$classes = isset( $args['cssready'] ) ? implode( ' ', $args['cssready'] ) : '';

$apply_coupon_button_text = apply_filters( 'wfacp_form_apply_coupon_button_text', __( 'Apply', 'woocommerce' ) );
?>

<div class="wfacp_woocommerce_form_coupon wfacp-form-control-wrapper <?php echo $classes; ?>" id="order_coupon_field">
    <div class="wfacp-coupon-section wfacp_custom_row_wrap clearfix">
        <div class="wfacp-coupon-page">
			<?php
			if ( wc_string_to_bool( $is_collapsible ) ) {
				?>
                <div class="woocommerce-form-coupon-toggle">
					<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', ' <a  class="wfacp_showcoupon">' . __( 'Have a coupon?', 'woocommerce' ) . ' ' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' ), 'notice' ); ?>
                </div>
				<?php
			}

			?>
            <div class="wfacp-row wfacp_coupon_field_box" style="<?php echo ( wc_string_to_bool( $is_collapsible ) && count( WC()->cart->applied_coupons ) == 0 ) ? 'display:none' : ''; ?>">
                <p class="form-row form-row-first wfacp-form-control-wrapper wfacp-col-left-half wfacp-input-form">
                    <label for="coupon_code" class="wfacp-form-control-label"><?php echo isset( $args['label'] ) ? $args['label'] : __( 'Enter the coupon code below', 'woofunnels-aero-checkout' ); ?></label>
                    <input type="text" name="wfacp_coupon_field" id='wfacp_coupon_code_field' class="input-text wfacp-form-control wfacp_coupon_code" placeholder="<?php echo $args['label']; ?>" value=""/>
                </p>
                <p class="form-row form-row-last <?php echo $coupon_cls; ?> wfacp_coupon_btn_wrap">
                    <label class="wfacp-form-control-label">&nbsp;</label>
                    <button type="button" class="button wfacp-coupon-field-btn" name="apply_coupon" value="<?php echo $apply_coupon_button_text; ?>" disabled="disabled"><?php echo $apply_coupon_button_text; ?></button>
                </p>
                <div class="clear"></div>
            </div>
            <div class="wfacp-row ">
                <div class="wfacp_coupon_field_msg">
					<?php
					$success_message = $checkout_field['advanced']['order_coupon']['coupon_success_message_heading'];
					foreach ( WC()->cart->get_coupons() as $code => $coupon ) {

						$parse_message = WFACP_Product_Switcher_Merge_Tags::parse_coupon_merge_tag( $success_message, $coupon );
						$remove_link   = sprintf( "<a href='javascript:void(0)' class='wfacp_remove_coupon' data-coupon='%s'>%s</a>", $code, __( 'Remove', 'funnel-builder' ) );

						$parse_message = sprintf( '<div class="wfacp_single_coupon_msg">%s %s</div>', $parse_message, $remove_link );

						echo sprintf( '<div class="wfacp_single_coupon_msg">%s</div>', $parse_message );
					}
					?>
                </div>
                <div class="wfacp_coupon_error_msg"></div>
                <div class="wfacp_coupon_remove_msg"></div>
            </div>
        </div>
    </div>
</div>
