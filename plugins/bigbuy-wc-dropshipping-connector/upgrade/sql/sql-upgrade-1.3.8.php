<?php

defined('ABSPATH') || exit;

global $wpdb;

return [
    'DROP TABLE IF EXISTS '.$wpdb->prefix.'tag_map;',
    'DROP TABLE IF EXISTS '.$wpdb->prefix.'attribute_group_map;',
    'DROP TABLE IF EXISTS '.$wpdb->prefix.'variation_map;',
    'DROP TABLE IF EXISTS '.$wpdb->prefix.'attribute_map;',
 ];