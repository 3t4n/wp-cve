<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<div class="acfw-order-preview-data wc-order-preview-addresses">
<?php do_action( 'acfw_before_order_preview_popup_summary', $order ); ?>

    <div class="acfw-order-used-coupons wc-order-preview-address" style="padding-top: 0;">
        <strong><?php esc_html_e( 'Coupons', 'advanced-coupons-for-woocommerce-free' ); ?></strong>
        <?php if ( $coupons_list ) : ?>
            <?php echo wp_kses_post( $coupons_list ); ?>
        <?php else : ?>
            <span class="no-coupons"><?php esc_html_e( 'No coupons used', 'advanced-coupons-for-woocommerce-free' ); ?></span>
        <?php endif; ?>
    </div>

    <?php do_action( 'acfw_after_order_preview_popup_summary', $order ); ?>
</div>
