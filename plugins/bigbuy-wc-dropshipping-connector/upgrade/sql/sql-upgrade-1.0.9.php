<?php

defined('ABSPATH') || exit;

global $wpdb;

return [
    'INSERT INTO '.$wpdb->prefix.'options (`option_name`, `option_value`) VALUES ("WC_MIPCONNECTOR_BIGBUY_API_KEY", "0");',
    'INSERT INTO '.$wpdb->prefix.'options (`option_name`, `option_value`) VALUES ("WC_MIPCONNECTOR_ENABLE_UPDATE_PRODUCT_URL", 0);'
];