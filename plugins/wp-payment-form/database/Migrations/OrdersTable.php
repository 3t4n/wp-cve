<?php

namespace WPPayForm\Database\Migrations;

class OrdersTable
{
    public static function migrate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wpf_order_items';
        $sql = "CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			form_id int(11) NOT NULL,
			submission_id int(11) NOT NULL,
			type varchar(255) DEFAULT 'single',
			parent_holder varchar(255),
			billing_interval varchar(255),
			item_name varchar(255),
			quantity int(11) DEFAULT 1,
			item_price int(11),
			line_total int(11),
			created_at timestamp NULL,
			updated_at timestamp NULL,
			PRIMARY  KEY  (id)
		) $charset_collate;";

        dbDelta($sql);
    }
}
