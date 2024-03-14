<?php

if( !defined('ABSPATH') ) die('No Access to this page');

include_once("includes/BingMapPro_OptionsManager.php");

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$bmp_options = new BingMapPro_OptionsManager\BingMapPro_OptionsManager();
$bmp_options->deleteOption('bmp_api_key');
$bmp_options->deleteOption('bmp_cnb');
$bmp_options->deleteOption('bmp_dz');
$bmp_options->deleteOption('bmp_mr');
$bmp_options->deleteOption('bmp_dsom');
$bmp_options->deleteOption('_version');
$bmp_options->deleteOption('_installed');
$bmp_options->deleteOption('editor_cap');
$bmp_options->deleteOption('author_cap');
$bmp_options->deleteOption('contributor_cap');
$bmp_options->deleteOption('hide_api_key');

$bmp_options->deleteOption('bmp_pin_desktop_height');
$bmp_options->deleteOption('bmp_pin_desktop_width');
$bmp_options->deleteOption('bmp_pin_mobile_height');
$bmp_options->deleteOption('bmp_pin_mobile_width');
$bmp_options->deleteOption('bmp_pin_tablet_width');
$bmp_options->deleteOption('bmp_pin_tablet_height');
$bmp_options->deleteOption('bmp_woo_autosuggest_enabled');


global $wpdb;
$bmp_table_maps         = $wpdb->prefix . 'bingmappro_maps';
$bmp_table_pins         = $wpdb->prefix . 'bingmappro_pins';
$bmp_table_map_pins     = $wpdb->prefix . 'bingmappro_map_pins';
$bmp_table_shapes       = $wpdb->prefix . 'bingmappro_shapes';
$bmp_table_map_shapes   = $wpdb->prefix . 'bingmappro_map_shapes';
$bmp_table_map_shortcodes = $wpdb->prefix . 'bingmappro_map_shortcodes';

$bmp_query_map_pins     = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_map_pins}; ");
$bmp_query_maps         = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_maps}; ");
$bmp_query_pins         = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_pins}; ");
$bmp_query_shapes       = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_shapes}; ");
$bmp_query_map_shapes   = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_map_shapes}; ");
$bmp_query_map_shortcodes = $wpdb->query("DROP TABLE IF EXISTS {$bmp_table_map_shortcodes}; ");