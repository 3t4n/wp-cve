<?php

class pisol_corw_new_order_status{
    function __construct(){
        /**
         * The order status filtering shown on top of the order list page
         */
        add_action('init', array($this, 'cancelOrderRequestFilter'));

        add_filter('wc_order_statuses', array($this, 'listOfOrderStatus'));
    }

    function cancelOrderRequestFilter(){
        register_post_status(
            'wc-cancel-request', 
            array('label' => __('Cancel Request', 'cancel-order-request-woocommerce'), 
            'public' => true, 
            'exclude_from_search' => false, 
            'show_in_admin_all_list' => true, 
            'show_in_admin_status_list' => true, 
            'label_count' => _n_noop('Cancel Request <span class="count">(%s)</span>', 'Cancel Requests <span class="count">(%s)</span>', 'cancel-order-request-woocommerce')
            )
        );
    }

    function listOfOrderStatus($order_statuses)    {
        $order_statuses['wc-cancel-request'] = __('Cancel Request','cancel-order-request-woocommerce');

        return $order_statuses;
    }
}

new pisol_corw_new_order_status();