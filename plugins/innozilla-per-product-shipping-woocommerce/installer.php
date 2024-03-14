<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

$wpdb->hide_errors();

$collate = '';

if ( $wpdb->has_cap( 'collation' ) ) {
	if( ! empty($wpdb->charset ) ) {
		$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
	}
	if( ! empty($wpdb->collate ) ) {
		$collate .= " COLLATE $wpdb->collate";
	}
}

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// Table for storing rules for products.
$sql = "
CREATE TABLE {$wpdb->prefix}innozilla_per_product_shipping_rules_woo (
iz_rule_id bigint(20) NOT NULL auto_increment,
product_id bigint(20) NOT NULL,
iz_rule_country varchar(10) NOT NULL,
iz_rule_state varchar(10) NOT NULL,
iz_rule_postcode varchar(200) NOT NULL,
iz_rule_cost varchar(200) NOT NULL,
iz_rule_item_cost varchar(200) NOT NULL,
iz_rule_order bigint(20) NOT NULL,
PRIMARY KEY  (iz_rule_id)
) $collate;
";
dbDelta($sql);

// Upgrades
$old_data = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'per_product_shipping'" );

foreach ( $old_data as $data ) {
	if ( $data->meta_value ) {
	    $wpdb->insert(
			"{$wpdb->prefix}innozilla_per_product_shipping_rules_woo",
			array(
				'iz_rule_country' 		=> '',
				'iz_rule_state' 		=> '',
				'iz_rule_postcode' 	=> '',
				'iz_rule_cost' 		=> '',
				'iz_rule_item_cost' 	=> esc_attr( number_format( $data->meta_value, 2, '.', ',' ) ),
				'iz_rule_order'		=> 0,
				'product_id'		=> $data->post_id
			)
		);
		add_post_meta( $data->post_id, '_per_product_shipping', 'yes' );
	}
    add_post_meta( $data->post_id, 'old_per_product_shipping', $data->meta_value );
    delete_post_meta( $data->post_id, 'per_product_shipping' );
}

update_option( 'IPPSW_PER_PRODUCT_SHIPPING_VERSION', IPPSW_PER_PRODUCT_SHIPPING_VERSION );