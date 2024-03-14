<?php
class BeRocket_watermarks_ajax_generate_button {
    function __construct() {
        add_action( "wp_ajax_berocket_get_watermark_images", array ( $this, 'get_all_images' ) );
    }
    function get_products_inverted_args($args = array()) {
        $args = array_merge(array(
            'posts_per_page'   => -1,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'include'          => '',
            'exclude'          => '',
            'post_type'        => 'product',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'           => '',
            'post_status'      => 'any',
            'fields'           => 'ids',
            'suppress_filters' => false 
        ), $args);
        $posts_array = new WP_Query($args);
        $posts_array = $posts_array->posts;
        $args = array(
            'posts_per_page'   => -1,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'include'          => '',
            'exclude'          => '',
            'post_type'        => 'product_variation',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'           => '',
            'post_status'      => 'any',
            'fields'           => 'ids',
            'suppress_filters' => false 
        );
        $posts_array2 = new WP_Query($args);
        $posts_array2 = $posts_array2->posts;
        $posts_array = array_merge($posts_array, $posts_array2);
        return $posts_array;
    }
    function get_not_watermarked_attachments() {
        global $wpdb;
        $products_lists = $wpdb->get_col( "SELECT meta_value FROM {$wpdb->postmeta} 
        JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
        WHERE {$wpdb->posts}.post_type IN ('product', 'product_variation')
        AND {$wpdb->postmeta}.meta_key IN ('_thumbnail_id', '_product_image_gallery')
        AND {$wpdb->postmeta}.meta_value != ''
        AND {$wpdb->postmeta}.meta_value != '0'", 0);
        $products_lists2 = array();
        foreach($products_lists as $images) {
            $products_lists2 = array_merge($products_lists2, explode(',', $images));
        }
        unset($products_lists);
        $images_list = array();
        for($i = 0; $i < count($products_lists2); $i += 500) {
            $search_list = array_slice($products_lists2, $i, 500);
            $images_list_found = $wpdb->get_col( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts}
            WHERE {$wpdb->posts}.ID NOT IN (SELECT {$wpdb->posts}.ID FROM {$wpdb->posts}
            JOIN {$wpdb->postmeta} 
            ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
            AND {$wpdb->postmeta}.meta_key = 'br_watermark' 
            AND {$wpdb->postmeta}.meta_value = '2')
            AND {$wpdb->posts}.ID IN ('".implode("','", $search_list)."')", 0);
            $images_list = array_merge($images_list, $images_list_found);
        }
        $images_list = array_unique($images_list);
        return $images_list;
    }
    function get_watermarked_attachments() {
        $args = array(
            'posts_per_page'   => -1,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => 'br_watermark',
            'meta_value'       => '2',
            'post_type'        => 'attachment',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'           => '',
            'post_status'      => 'any',
            'fields'           => 'ids',
            'suppress_filters' => false 
        );
        $posts_array = new WP_Query($args);
        return $posts_array->posts;
    }
    function get_all_images() {
        $attachments_id = array();
        $status = $_GET['generation'];
        $status = $_GET['generation'] != 'restore';
        if( $status ) {
            $attachments_id = $this->get_not_watermarked_attachments();
        } else {
            $attachments_id = $this->get_watermarked_attachments();
        }
        echo json_encode($attachments_id);
        wp_die();
    }
    function get_image_array_for_watermark($images, $status = true) {
        $images_list = array();
        if( ! empty($images) ) {
            $images = explode(',', $images);
            if(is_array($images) && count($images) ) {
                foreach($images as $image) {
                    $br_watermark = get_post_meta($image, 'br_watermark', true);
                    if( ($br_watermark != '2') == $status ) {
                        $images_list[] = $image;
                        update_post_meta($image, 'br_watermark', '1');
                    }
                }
            }
        }
        return $images_list;
    }
}
new BeRocket_watermarks_ajax_generate_button();
