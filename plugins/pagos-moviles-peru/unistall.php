<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit();
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS " . YAPEA1_DB_TABLE);
delete_option("yape_a1tiendas_version");
