<?php

namespace WPPayForm\Database\Migrations;

class TransactionsTable
{
    public static function migrate($forced = true)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wpf_order_transactions';

        $sql = "CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			form_id int(11) NOT NULL,
			user_id int(11) DEFAULT NULL,
			submission_id int(11) NULL,
			subscription_id int(11) NULL,
			transaction_type varchar(255) DEFAULT 'one_time',
			payment_method varchar(255),
			card_last_4 int(4),
			card_brand varchar(255),
			charge_id varchar(255),
			payment_total int(11) DEFAULT 1,
			status varchar(255),
			currency varchar(255),
			payment_mode varchar(255),
			payment_note longtext,
			created_at timestamp NULL,
			updated_at timestamp NULL,
			PRIMARY  KEY  (id)
		) $charset_collate;";

        if ($forced) {
            return MigrateHelper::runForceSQL($sql, $table_name);
        }

        return MigrateHelper::runSQL($sql, $table_name);
    }
}
