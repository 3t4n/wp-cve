<?php
/**
 * Afterpay Checkout Instalments Display
 * @var WC_Gateway_Afterpay $this
 */

    function afterpay_display_payment_schedule($test_mode, $mpid, $order_total, $currency, $page_type = 'checkout') {
?>
    <?php if ($test_mode != 'production') { ?>
        <p class="afterpay-test-mode-warning-text"><?php _e( 'TEST MODE ENABLED', 'woo_afterpay' ); ?></p>
    <?php } ?>
    <div
        id="afterpay-widget-container"
        data-mpid="<?php echo esc_attr($mpid); ?>"
        data-page-type="<?php echo esc_attr($page_type); ?>"
        data-amount="<?php echo esc_attr($order_total); ?>"
        data-currency="<?php echo esc_attr($currency); ?>"></div>
<?php
    }
