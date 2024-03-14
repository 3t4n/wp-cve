<?php

defined('ABSPATH') || exit;

global $wpdb;

return [
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_map ADD COLUMN message_version DATETIME NULL AFTER version;',
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_map ADD COLUMN image_version INT NULL AFTER version;'
];