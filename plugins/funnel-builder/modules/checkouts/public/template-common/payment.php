<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance                    = wfacp_template();
$selected_template_slug      = $instance->get_template_slug();
$payment_des                 = $instance->get_payment_desc();
$border_cls                  = $instance->get_heading_title_class();
$payment_methods_heading     = $instance->payment_heading();
$payment_methods_sub_heading = $instance->payment_sub_heading();
$current_step                = $instance->get_current_step();
$current_open                = $instance->get_current_open_step();
$hide_payment_cls            = '';

$temp_open_checking = false;
if ( 'single_step' !== $current_open ) {
	$temp_open_checking = true;
}

if ( false == $temp_open_checking && 'single_step' !== $current_step ) {
	$hide_payment_cls = 'wfacp_hide_payment_part';
}

if ( WFACP_Core()->public->is_paypal_express_active_session ) {
	$hide_payment_cls = '';
}
if ( WFACP_Core()->public->is_amazon_express_active_session ) {
	$hide_payment_cls = '';
}
$payment_des_class = '';
if ( ! empty( $payment_des ) ) {
	$payment_des_class = 'wfacp-payment-dec-active';
}
?>
<div class="wfacp-section wfacp_payment <?php echo $hide_payment_cls; ?>  <?php echo $payment_des_class; ?> form_section_your_order_0_<?php echo $selected_template_slug; ?> wfacp-section-title wfacp-hg-by-box">
    <div style="clear: both;"></div>
    <div class="wfacp-comm-title <?php echo $border_cls; ?>">
        <h2 class="wfacp_section_heading wfacp_section_title <?php echo $instance->get_heading_class() ?> "><?php echo $payment_methods_heading; ?></h2>
        <h4 class="<?php echo $instance->get_sub_heading_class(); ?>"><?php echo $payment_methods_sub_heading; ?></h4>
    </div>
	<?php do_action( 'wfacp_checkout_before_order_review' ); ?>
    <div class="woocommerce-checkout-review-order wfacp-oder-detail clearfix">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		<?php
		if ( $payment_des != '' ) {
			?>
            <div class="wfacp-payment-dec">
				<?php echo $payment_des; ?>
            </div>
			<?php
		}
		?>
    </div>
	<?php do_action( 'wfacp_checkout_after_order_review' ); ?>
</div>
