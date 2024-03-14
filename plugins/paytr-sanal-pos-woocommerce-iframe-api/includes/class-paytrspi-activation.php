<?php
if (!defined('ABSPATH')) {
    exit;
};

class PaytrCheckoutActivation
{
    public static function active()
    {
        global $wpdb, $table_prefix;

        $table_name = $table_prefix . 'paytr_iframe_transaction';
        $charset_collate = $wpdb->get_charset_collate();

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                paytr_id INT AUTO_INCREMENT,
                order_id INT NOT NULL,
                merchant_oid VARCHAR(64) NOT NULL,
                total DECIMAL(10,2),
                total_paid DECIMAL(10,2),
                status VARCHAR(64),
                status_message TEXT,
                is_completed INT,
                is_failed INT,
                is_order INT,
                is_refunded INT,
                refund_status TEXT,
                refund_amount DECIMAL(10,2),
                date_added DATETIME,
                date_updated DATETIME,
                PRIMARY KEY (paytr_id)
                ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}