<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="woocommerce-order">

    <?php if ($order) :
        do_action( 'woocommerce_before_thankyou', $order->get_id() );
        ?>

        <?php if ($order->has_status('failed')) : ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?></p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php _e('Pay', 'woocommerce') ?></a>
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php _e('My account', 'woocommerce'); ?></a>
                <?php endif; ?>
            </p>

        <?php else : ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), $order); ?></p>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    <?php _e('Order number:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php _e('Date:', 'woocommerce'); ?>
                    <strong><?php echo wc_format_datetime($order->get_date_created()); ?></strong>
                </li>

                <li class="woocommerce-order-overview__total total">
                    <?php _e('Total:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>

                <?php if ($order->get_payment_method_title()) : ?>

                    <li class="woocommerce-order-overview__payment-method method">
                        <?php _e('Payment method:', 'woocommerce'); ?>
                        <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                    </li>

                <?php endif; ?>

            </ul>
            
            <?php //if (WC()->session->__isset('pdf_url') && ($order->get_payment_method() == 'openpay_stores' || $order->get_payment_method() == 'openpay_spei')): ?>                            
            <?php if(strlen($order->get_meta('_pdf_url')) && ($order->get_payment_method() == 'openpay_stores' || $order->get_payment_method() == 'openpay_spei')): ?>
                <h2 class="woocommerce-order-details__title">Recibo de pago</h2>
                <iframe id="pdf" src="<?php echo $order->get_meta('_pdf_url'); //echo WC()->session->get('pdf_url') ?>" style="width:100%; height:950px; visibility: visible !important; opacity: 1 !important;" frameborder="0"></iframe>
            <?php endif; ?>    
                
            <div class="clear"></div>
            
            <?php if( $order->get_meta('_country') == 'MX'): ?>
                <div style="margin: 20px 0px; text-align: center;">
                    <h2 class="woocommerce-order-details__title" style="font-weight:600;">Localiza tu tienda más cercana dando click
                        <a target="_blank" href="https://www.paynet.com.mx/mapa-tiendas/index.html" style="font-weight:600;">aqui</a>
                    </h2>
                </div>    
            <?php endif; ?>    

        <?php endif; ?>

        <?php do_action('woocommerce_thankyou_'.$order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), null); ?></p>

    <?php endif; ?>

</div>