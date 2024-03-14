<?php namespace Premmerce\WoocommerceMulticurrency\Users;

use \WC_Customer;

/**
 * Class UserTotalSpentAmont
 */
class UserTotalSpentAmount
{
    /**
     * UserTotalSpentAmount constructor.
     */
    public function __construct()
    {
        add_filter('woocommerce_customer_get_total_spent_query', array($this, 'extendUserSpentQuery'), 10, 2);
    }

    /**
     * Extend WC user spent query so it considers orders currencies
     *
     * @param string $query
     * @param WC_Customer $customer
     *
     * @return string
     */
    public function extendUserSpentQuery($query, WC_Customer $customer)
    {
        global $wpdb;
        $statuses = array_map('esc_sql', wc_get_is_paid_statuses());

        $query = "SELECT SUM(order_amount.meta_value *  IFNULL(currencies.rate, 1))
					FROM {$wpdb->posts} as orders
         LEFT JOIN {$wpdb->postmeta} as order_customer
                   ON order_customer.post_id = orders.ID && order_customer.meta_key = '_customer_user'
         LEFT JOIN {$wpdb->postmeta} as order_amount
                   ON order_amount.post_id = orders.ID && order_amount.meta_key = '_order_total'
         LEFT JOIN {$wpdb->postmeta} as order_currency
                   ON order_currency.post_id = orders.ID &&
                      order_currency.meta_key = '_premmerce_multicurrency_order_currency_id'
         LEFT JOIN {$wpdb->prefix}premmerce_currencies as currencies ON currencies.id = order_currency.meta_value
     WHERE orders.post_type = 'shop_order'
    AND order_customer.meta_value = '" . esc_sql($customer->get_id()) . "'
        AND orders.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )";

        return $query;
    }
}
