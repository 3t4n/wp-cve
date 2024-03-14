<?php

namespace WunderAuto\Types\Filters\Customer;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class IsFirstOrder
 */
class BaseOrderCount extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->operators = $this->numberOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'number';
    }

    /**
     * Return the number of orders this customer has.
     *
     * @param string             $email
     * @param array<int, string> $orderStatuses
     *
     * @return int
     */
    public function getOrderCount($email, $orderStatuses = [])
    {
        $wpdb = wa_get_wpdb();

        if (count($orderStatuses) == 0) {
            $orderStatuses = array_keys(wc_get_order_statuses());
        }

        $sql = "SELECT count(*) from wp_posts as p
                  LEFT OUTER JOIN wp_postmeta as m ON (p.ID = m.post_id AND m.meta_key='_billing_email')
                WHERE post_type = 'shop_order'
                AND p.post_status In([IN])
                AND m.meta_value = %s";
        $sql = $wpdb->prepare($sql, $email);
        if (!is_string($sql)) {
            return 0;
        }
        $sql   = str_replace('[IN]', $this->listForIn($orderStatuses), $sql);
        $count = $wpdb->get_var($sql);
        return absint($count);
    }

    /**
     * Converts an array of string to a comma separated list suitable
     * for using in sql in conditional
     *
     * @param array<int, string> $statuses
     *
     * @return string
     */
    private function listForIn($statuses)
    {
        $ret  = "'";
        $ret .= implode(
            "','",
            array_map(function ($el) {
                $str = esc_sql($el);
                return is_array($str) ? (string)$str[0] : $str;
            }, $statuses)
        );
        $ret .= "'";

        return $ret;
    }

    /**
     * Return the number of orders this customer has.
     *
     * @param string             $email
     * @param array<int, string> $orderStatuses
     *
     * @return float|int
     */
    public function getOrderTotalSum($email, $orderStatuses = [])
    {
        $wpdb = wa_get_wpdb();

        $totalSum = 0;

        if (count($orderStatuses) == 0) {
            $orderStatuses = array_keys(wc_get_order_statuses());
        }

        $sql = "SELECT ID from wp_posts as p
                  LEFT OUTER JOIN wp_postmeta as m ON (p.ID = m.post_id AND m.meta_key='_billing_email')
                WHERE post_type = 'shop_order'
                AND p.post_status In([IN])
                AND m.meta_value = %s";
        $sql = $wpdb->prepare($sql, $email);
        if (!is_string($sql)) {
            return 0;
        }
        $sql = str_replace('[IN]', $this->listForIn($orderStatuses), $sql);

        /** @var array<int, \stdClass> $orderRows */
        $orderRows = $wpdb->get_results($sql);
        foreach ($orderRows as $orderRow) {
            $order = wc_get_order($orderRow->ID);
            if ($order instanceof \WC_Order) {
                $totalSum += $order->get_total();
            }
        }

        return $totalSum;
    }
}
