<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

require_once( 'simplexml.php' );

if (!file_exists(wp_upload_dir()['basedir'] . '/skroutz')) {
    wp_mkdir_p(wp_upload_dir()['basedir'] . '/skroutz');
}

if (!file_exists(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml')) {
    touch(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml');
}

if (file_exists(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml')) {
    $xmlFile = wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml';
} else {
    echo "Could not create file.";
}

$xml = new feed_SimpleXMLExtended('<?xml version="1.0" encoding="utf-8"?><webstore/>');
$now = date('Y-n-j G:i');
$xml->addChild('created_at', "$now");
$products = $xml->addChild('products');
$xml_rows = generate_products_xml_data();
foreach($xml_rows as $prod_id=>$row){
    $product = $products->addChild('product');

    $product->mpn = NULL;
    $product->mpn->addCData(addslashes(trim($row['skus_ds']) == '' ? $prod_id : $row['skus_ds']));  
    
    if(isset($row['gtin'])) {
        $label = array_keys($row['gtin'])[0];
        $product->addChild($label)->addCData($row['gtin'][$label]);
    }

    $product->addChild('uid', $prod_id);
    $product->name = NULL;
    $product->name->addCData($row['title']);
    $product->link = NULL;
    $product->link->addCData($row['link']);

    $product->image = NULL;
    $product->image->addCData($row['image_big']);

    $product->category = NULL;
    $product->category->addCData($row['category_path']);
    if(isset($row['additional_image'])) {
        foreach($row['additional_image'] as $id) {
            $product->addChild('additional_image')->addCData($id);
            }
        }

    //$product->addChild('category_id', $cat_id);
    $product->addChild('price', $row['price']);


    if (strcmp($row['stockstatus'], "instock") == 0) {
        $product->addChild('instock', "Y");
        $product->addChild('availability', $row['availabilityST']);
    } else {
        
        if (strcmp($row['backorder'], "notify") == 0) {
            $product->addChild('instock', "N");
            $product->addChild('availability', __('Upon order', 'skroutz-woocommerce-feed'));
        } else if (strcmp($row['backorder'], "yes") == 0) {
            $product->addChild('instock', "Y");
            $product->addChild('availability', $row['availabilityST']);
        } else {
            $product->addChild('instock', "N");
        }
    }
    $product->addChild('size', $row['sizestring']);

    $product->manufacturer = NULL;
    $product->manufacturer->addCData($row['manufacturer']);

    $product->color = NULL;
    $product->color->addCData($row['colorstring']);
    $product->addChild('weight', floatval($row['_weight_ds']) > 0 ? round(floatval($row['_weight_ds']) * 1000) : 0);
}


 
echo '</br>' . __('SUCCESSFUL CREATION OF Skroutz XML', 'skroutz-woocommerce-feed') . '</br>';
$xml->saveXML($xmlFile);
echo __('The file is located at', 'skroutz-woocommerce-feed') .' <a href="' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml" target="_blank">' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml</a>';

// function format_number_skroutz($pa_size) {
//     return str_replace(',', '.', $pa_size);
// }

?>