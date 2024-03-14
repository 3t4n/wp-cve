<?php
/**
 * Return product category array.
 */
if (!function_exists('rpmw_post_categories')) {

    function rpmw_post_categories() {

        $orderby = 'name';
        $order = 'asc';
        $hide_empty = true ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        
        $product_categories = get_terms( 'product_cat', $cat_args );
        
        $product_cat = array();
        if( !empty($product_categories) && !is_wp_error($product_categories) ){
            foreach ($product_categories as $category) {
                $product_cat[$category->term_id] = $category->name;
            }
        }
    return $product_cat;
    }
}
/**
 * Return product tags array.
 */
if (!function_exists('rpmw_post_tag')) {

    function rpmw_post_tag() {

        $orderby = 'name';
        $order = 'asc';
        $hide_empty = true ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        
        $product_categories = get_terms( 'product_tag', $cat_args );
        
        $product_cat = array();
        if( !empty($product_categories) && !is_wp_error($product_categories) ){
            foreach ($product_categories as $category) {
                $product_cat[$category->term_id] = $category->name;
            }          
        }
        return $product_cat;

    }
}