<?php
require_once __DIR__ .'/../libraries/XmlExportWooCommerceOrder.php';


function pmwoe_pmxe_init_addons() {

    if(!\XmlExportEngine::$woo_export) {
        \XmlExportEngine::$woo_export = new XmlExportWooCommerce();
    }

    if(!\XmlExportEngine::$woo_order_export) {
        \XmlExportEngine::$woo_order_export = new XmlExportWooCommerceOrder();
    }

}