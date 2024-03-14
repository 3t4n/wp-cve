<?php
define('REVI_API_URL', 'https://api.revi.io/');
define('REVI_WIDGETS_URL', 'https://widgets.revi.io/');
define('REVI_DIR', plugin_dir_path(__FILE__));


if (function_exists('icl_object_id')) {
    define('REVI_LANGUAGE_PLUGIN', 'wpml');
} else if (function_exists('pll_count_posts')) {
    define('REVI_LANGUAGE_PLUGIN', 'polylang');
} else {
    define('REVI_LANGUAGE_PLUGIN', '');
}

const PRODUCT_EAN = array(
    'EAN-13',
    'EAN13',
    'EAN',
    'ean',
    'EANCode',
    '_amazon_product_id',
    '_wpm_gtin_code',
    '_alg_ean',
);

const PRODUCT_BRAND = array(
    'brand',
    'BRAND',
    'manufacturer',
    'Fabricante',
    'MARCA',
    'MARCAS',
    'marca',
    'pwb-brand',
    'gla_attributes[brand]',
);


if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    define('WOOCOMMERCE_ACTIVE', true);
} else {
    define('WOOCOMMERCE_ACTIVE', false);
}
