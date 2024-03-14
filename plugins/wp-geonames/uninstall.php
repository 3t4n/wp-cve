<?php
/*
 * Clean-up when wp-geonames is uninstalled from a site.
*/
if(!defined('WP_UNINSTALL_PLUGIN')) die;
//
delete_option('wpGeonames_dataList');
delete_site_option('wpGeonames_dataList'); // multisite
//
global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->base_prefix.'geonames');
$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->base_prefix.'geonamesPostal');
?>
