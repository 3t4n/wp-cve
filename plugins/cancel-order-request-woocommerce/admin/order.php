<?php

class pisol_corw_cancellation_reason{

    function __construct(){
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'cancellationReason'), 10, 1 );
    }

    function cancellationReason($order){
        $order_id = version_compare( WC_VERSION, '3.0.0', '<' ) ? $order->id : $order->get_id();

        $reason = $order->get_meta( 'order_cancel_reason', true);
        $predefined_reason = $order->get_meta( 'predefined_reason', true);

        if($order->get_status() == 'cancel-request' || !empty($reason)){

            ?>
            <div class="order_data_column" style="width:100%;">
            <?php if(!empty($predefined_reason)):?>
            <h3><?php _e('Selected reason for cancellation: ','cancel-order-request-woocommerce'); ?></h3>
            <?php echo $predefined_reason; ?>
            <?php endif; ?>

            <h3><?php _e('Cancellation reason: ','cancel-order-request-woocommerce'); ?></h3>
            <?php
                echo !empty($reason) ? esc_html($reason) : '-';
            ?>
            </div>
            <?php
        }
    }
}

new pisol_corw_cancellation_reason();