<?php 
    /*
    Plugin Name: Display Category Post Count
    Description: This plugin useful for displaying post count for any post/product category (simply use shortcode [get-post-count-wpcpc category="your category name" post_type="post/product/etc"])
    Author: Amit Maskare
    Version: 1.1
    Author URI: #
    */
class WPCPC{
    public function getPostCount($atts){
    global $wpdb;

    extract( shortcode_atts( array (
        'cat_name'  => $atts['category'],
        'post_type' => $atts['post_type'],
    ), $atts ) );

$get_query = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}terms terms INNER JOIN {$wpdb->prefix}term_relationships terms_rel ON terms.term_id = terms_rel.term_taxonomy_id INNER JOIN {$wpdb->prefix}posts post ON post.ID = terms_rel.object_id WHERE terms.name = '".$cat_name."' AND post.post_status = 'publish' AND post.post_type = '".$post_type."'");

$rowcount = $wpdb->num_rows;
return $rowcount;
}
}

function get_post_count_wpcpc($atts){
$WPCPC = new WPCPC;
return $WPCPC->getPostCount($atts);
}

add_shortcode( 'get-post-count-wpcpc' , 'get_post_count_wpcpc' );