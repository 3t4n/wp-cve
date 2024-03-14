<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

function pricelist_uninstall() {
    $pro = 'pricelist-for-woocommerce-pro';
    $free = 'pricelist-for-woocommerce';
    $isPro = $pro === basename(__DIR__);
    $other = $isPro ? $free : $pro;
    $other = __DIR__.'/../'.$other.'/'.$other.'.php';
    if (!file_exists($other)) {
        $prefix = 'pricelist_';
    } elseif ($isPro) {
        $prefix = 'pricelist_pro_';
    } else {
        return 'no';
    }
    $len = strlen($prefix);
    foreach (wp_load_alloptions() as $option => $value) {
        if (strncmp($option, $prefix, $len) === 0) {
            delete_option($option);
        }
    }
    return $prefix;
}
$pricelist_deleted = pricelist_uninstall();
?>