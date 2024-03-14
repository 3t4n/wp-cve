<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $widget_id
 */

$instance = wfacp_template();

if ( apply_filters( 'wfacp_disable_mini_cart', false, $instance, $widget_id ) ) {
	do_action( 'wfacp_disable_mini_cart_placeholder' );
	return;
}

$settings           = WFACP_Common::get_session( $widget_id );
$enable_delete_item = wc_string_to_bool( $settings['enable_delete_item'] );
add_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
?>
    <div class="wfacp_wrapper_start wfacp_mini_cart_start_h">
		<?php
		if ( '' !== $settings['mini_cart_heading'] ) {
			?>
            <div class="wfacp-order-summary-label"><?php echo $settings['mini_cart_heading']; ?></div>
			<?php
		}
		?>
        <div class="wfacp_order_summary_container wfacp_min_cart_widget wfacp_mini_cart_elementor" data-delete-enabled="<?php echo $enable_delete_item ?>">
			<?php
			include __DIR__ . '/mini-cart-items.php';
			if ( 'yes' == $settings['enable_coupon'] ) {
				$instance->get_mini_cart_coupon( $widget_id );
			}
			include __DIR__ . '/mini-cart-review-totals.php';
			?>
        </div>
    </div>
<?php

remove_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
