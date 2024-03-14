<?php 
/*Create table for checkpincodein woocommerce*/
function CPIW_create_table() {    
    global $table_prefix, $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $tablename = $table_prefix.'cpiw_pincode';

    $sql = "CREATE TABLE $tablename (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        pincode TEXT NOT NULL,
        city TEXT NOT NULL,
        state TEXT NOT NULL,
        ddate TEXT NOT NULL,
        ship_amount TEXT NOT NULL,
        caseondilvery TEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( CPIW_PLUGIN_FILE, 'CPIW_create_table');