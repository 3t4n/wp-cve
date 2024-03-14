<?php
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
$trustindex_pm_ebay = new TrustindexPlugin_ebay("ebay", __FILE__, "11.6", "Widgets for Ebay Reviews", "Ebay");
$trustindex_pm_ebay->uninstall();
?>