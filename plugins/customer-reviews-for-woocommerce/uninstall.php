<?php
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
require_once plugin_dir_path( __FILE__ ) . 'trustindex-woocommerce-plugin.class.php';
$trustindex_woocommerce = new TrustindexWoocommercePlugin('woocommerce', __FILE__, "3.2.1", "", "");
$trustindex_woocommerce->uninstall();
?>