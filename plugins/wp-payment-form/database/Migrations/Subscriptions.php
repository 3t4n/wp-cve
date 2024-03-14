<?php

namespace WPPayForm\Database\Migrations;

class Subscriptions
{
    public static function migrate($forced = false)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wpf_subscriptions';

        $sql = "CREATE TABLE $table_name (
			id int(20) NOT NULL AUTO_INCREMENT,
			submission_id int(11),
			form_id int(11),
			payment_total int(11) DEFAULT 0,
			item_name varchar(255),
			plan_name varchar(255),
			parent_transaction_id int(11),
			billing_interval varchar (50),
			trial_days int(11),
			initial_amount int(11),
			quantity int(11) DEFAULT 1,
			recurring_amount int(11),
			bill_times int(11),
			bill_count int(11) DEFAULT 0,
			vendor_customer_id varchar(255),
			vendor_subscriptipn_id varchar(255),
			vendor_plan_id varchar(255),
			status varchar(255) DEFAULT 'pending',
			inital_tax_label varchar(255),
			inital_tax int(11),
			recurring_tax_label varchar(255),
			recurring_tax int(11),
			element_id varchar(255),
			note text,
			original_plan text,
			vendor_response longtext,
			expiration_at timestamp NULL,
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
