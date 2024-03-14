<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( !$product ) {

    return;
}

$shop_catalog_mode = WModes_Pipeline_Shop_Catalog::get_instance();

$shop_catalog_mode->get_render_loop_add_to_cart( $product );
