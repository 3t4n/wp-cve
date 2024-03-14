<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance    = wfacp_template();
$next_action = 'two_step';
$data_text   = 'step-2';
if ( $current_action === 'two_step' ) {
	$next_action = 'third_step';
	$data_text   = 'step-3';
}


$change_next_btn = apply_filters( 'wfacp_change_next_btn_' . $current_action, 'Next', $current_action );

$btnClass       = [ 'wfacp-two-step-erap', 'wfacp-next-btn-wrap' ];
$alignmentclass = '';
if ( isset( $formData['form_data']['btn_details']['talign'] ) ) {
	$alignmentclass = $formData['form_data']['btn_details']['talign'];
	$btnClass[]     = $alignmentclass;
}

$btnWidth = '';
if ( isset( $formData['form_data']['btn_details']['width'] ) ) {
	$btnWidth   = $formData['form_data']['btn_details']['width'];
	$btnClass[] = $btnWidth;
}


?>
<div class="<?php echo implode( ' ', $btnClass ); ?>">

	<?php
	$is_global_checkout = WFACP_Core()->public->is_checkout_override();


	do_action( 'wfacp_before_next_button', $current_action );
	?>
    <button type="button" class="button button-primary wfacp_next_page_button" data-next-step="<?php echo $next_action; ?>" data-current-step='<?php echo $current_action; ?>' value="<?php _e( 'Next Step', 'woofunnels-aero-checkout' ); ?>" data-text="<?php echo $data_text ?>">
		<?php _e( $change_next_btn, 'woofunnels-aero-checkout' ); ?>
    </button>
	<?php
	if ( isset( $formData['form_data']['breadcrumb_before']['enable_cart'] ) && true === $formData['form_data']['breadcrumb_before']['enable_cart'] ) {

		if ( apply_filters( 'wfacp_native_checkout_cart', true ) && $current_action == 'single_step' && $is_global_checkout === true ) {
			$cartURL = wc_get_cart_url();

			$cart_name = __( '« Return to Cart', 'woofunnels-aero-checkout' );
			if ( isset( $formData['form_data']['breadcrumb_before']['enable_cart_text'] ) && $formData['form_data']['breadcrumb_before']['enable_cart_text'] != '' ) {
				$enable_cart_text = esc_html( $formData['form_data']['breadcrumb_before']['enable_cart_text'] );
				if ( "Cart" === $enable_cart_text ) {
					$cart_name = __( '« Return to ' . $enable_cart_text, 'woofunnels-aero-checkout' );
				} else {
					$cart_name = "<span>«</span> " . esc_html( $formData['form_data']['breadcrumb_before']['enable_cart_text'] );
				}
			}

			$cartName = apply_filters( 'wfacp_back_cart', $cart_name );

			?>

            <div class="btm_btn_sec wfacp_back_cart_link">
                <div class="wfacp-back-btn-wrap">
                    <a href="<?php echo $cartURL; ?>"><?php echo( $cartName ); ?></a>
                </div>
            </div>

			<?php
		}
	}

	?>
</div>
