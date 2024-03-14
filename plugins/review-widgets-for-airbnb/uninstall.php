<?php
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
$trustindex_pm_airbnb = new TrustindexPlugin_airbnb("airbnb", __FILE__, "11.6", "Widgets for Airbnb Reviews", "Airbnb");
$trustindex_pm_airbnb->uninstall();
?>