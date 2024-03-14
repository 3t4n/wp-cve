<?php

defined('ABSPATH') || exit;

global $wpdb;

return [
    'CREATE INDEX idx_wc_product_meta_lookup_sku ON '.$wpdb->prefix.'wc_product_meta_lookup (sku);'
];