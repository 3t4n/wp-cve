<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Plugin Activation
 * @since 1.5
 */
global $wpdb, $wp_version;

update_option('shop_ready_qs_version', SHOP_READY_VERSION);

if (class_exists('WooCommerce')) {

    $images = [
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-1.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-2.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-3.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-4.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-5.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-6.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-7.webp',
        SHOP_READY_PUBLIC_ROOT_IMG . '/product-demo/shop-ready-8.webp',
    ];

    $attachment_ids = [];
    foreach ($images as $key => $path) {

        $post_id = 0;
        $data = array(
            'file' => $path,
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'post_mime_type' => 'attachment',
            'post_title' => 'ShopReady-Demo-Image' . $key,
            'post_mime_type' => 'image/webp',
        );

        if (!$attachment_id = post_exists('ShopReady-Demo-Image' . $key)) {
            $attachment_id = wp_insert_post($data, false);
        }
        $attachment_ids[] = $attachment_id;
        update_post_meta($attachment_id, 'shop_ready_demo_content', 1);

    }

    update_option('shop_ready_demo_attachment_ids', $attachment_ids);




}