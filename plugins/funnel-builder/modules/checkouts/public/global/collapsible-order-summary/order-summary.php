<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance     = wfacp_template();
$data         = $instance->get_checkout_fields();
$colspan_attr = '';
if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
	$colspan_attr1    = ' colspan="2"';
	$colspan_attr     = apply_filters( 'wfacp_order_summary_cols_span', $colspan_attr1 );
	$cellpadding_attr = ' cellpadding="20"';
}
$total_col              = 2;
$section_key            = '';
$cart_data              = [];
$selected_template_type = $instance->get_template_type();
if ( $selected_template_type != 'pre_built' ) {
	$rbox_mobile = 'none';
}
$is_disable_coupon_sidebar = apply_filters( 'wfacp_mini_cart_hide_coupon', true );
$coupon_class              = 'wfacp_active_coupon';
if ( false == wc_string_to_bool( $is_disable_coupon_sidebar ) ) {
	$coupon_class = 'wfacp_in_active_coupon';
}
?>
<div class="wfacp_form_cart <?php echo $rbox_mobile; ?> div_wrap_sec <?php echo $instance->get_field_label_position() ?>" <?php echo WFACP_Common::get_fragments_attr(); ?> >
    <div class="wfacp_order_sec wfacp_order_summary_layout_9 wfacp_order_summary_sec">
        <div class="<?php echo $coupon_class; ?>">
			<?php
			include __DIR__ . '/order-review.php';
			include __DIR__ . '/form-coupon.php';
			include __DIR__ . '/order-total.php';
			?>
        </div>
    </div>
</div>
