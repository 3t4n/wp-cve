<?php
/**
 * Drop tables from db.
 */
function bm_call_uninstall()
{
    global $wpdb;

    if($wpdb->get_var('show tables like "' . BM_TABLE_BANNERS . '"') == BM_TABLE_BANNERS) {
        $sql = 'DROP TABLE ' . BM_TABLE_BANNERS;
        $wpdb->query($sql);
    }

    if($wpdb->get_var('show tables like "' . BM_TABLE_GROUPS . '"') == BM_TABLE_GROUPS) {
        $sql = 'DROP TABLE ' . BM_TABLE_GROUPS;
        $wpdb->query($sql);
    }

    if($wpdb->get_var('show tables like "' . BM_TABLE_STATS . '"') == BM_TABLE_STATS) {
        $sql = 'DROP TABLE ' . BM_TABLE_STATS;
        $wpdb->query($sql);
    }
}

// taulak ezabatu
register_uninstall_hook( BM_PLUGIN_FILE, 'bm_call_uninstall');
