<?php
/**
 * RDV Category Image: Frontend display
 **/

/*
* Category Image direct print shortcode Function
* @since 1.0.0
*/
add_shortcode('rdv_category_image', 'rdv_category_thumbnail_shortcode');
function rdv_category_thumbnail_shortcode($atts) {
    ob_start();
    $atts = shortcode_atts(
            array(
                'term_id' => '',
                'size' => '',
            ), $atts, 'rdv_category_thumbnail' );
    
    $image_placeholder = plugin_dir_url( __FILE__ ).'images/rdv-placeholder.png';
    
    $category_id = $atts['term_id'];
    $category_thumbnail = $atts['size'];
    
    if($category_thumbnail == '') {
        $size = 'full';
    }
    else {
        $size = $atts['size'];
    }
    
    if(is_archive()){
        $term_object = get_queried_object();
        $term_name = $term_object->name;
        $get_term_id = get_queried_object_id();
        if($category_id == 'current-page'){
            $term_id = $term_object->term_id;
        }
        elseif(!empty($category_id)) {
            $term_id = $category_id;
        }
        else {
            $term_id = $term_object->term_id;
        }
    }
    else {
        if(!empty($category_id)) {
            $term_id = $category_id;
        }
        else {
            $term_id = '';
        }
    }
    $image_id = get_term_meta ( $term_id, 'rdv_category_image_id', true ); 
    if ( !empty($image_id) ) {
        $taxonomy_image = wp_get_attachment_image($image_id, $size);
    }
    else {
        // If ID is not set with other parameters then to avoid Notice: Undefined variable: taxonomy_image
        $taxonomy_image = '';
    }
    return $taxonomy_image;
    return ob_get_clean();
}

/*
* Category Image direct print Function
* @since 1.0.0
*/
 function rdv_category_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
     if (!$term_id) {
        if (is_category()) {
            $term_id = get_query_var('cat');
        }
        elseif (is_tag()) {
            $term_id = get_query_var('tag_id');
        }
        elseif (is_tax()) {
            $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            $term_id = $current_term->term_id;
        }
    }
    
    $image_id = get_term_meta ( $term_id, 'rdv_category_image_id', true ); 
    if ( !empty($image_id) ) {
        $taxonomy_image = wp_get_attachment_image($image_id, $size, FALSE, $attr);
    }
    else {
        // If ID is not set with other parameters then to avoid Notice: Undefined variable: taxonomy_image
        $taxonomy_image = '';
    }
     
    if ($echo) {
        echo wp_get_attachment_image($image_id, $size, FALSE, $attr);
    }
    else {
        return $taxonomy_image;
    }
 }

/*
* Category Image URL Function
* @since 1.0.0
*/
 function rdv_category_image_url($term_id = NULL, $size = 'full') {
     if (!$term_id) {
        if (is_category()) {
            $term_id = get_query_var('cat');
        }
        elseif (is_tag()) {
            $term_id = get_query_var('tag_id');
        }
        elseif (is_tax()) {
            $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            $term_id = $current_term->term_id;
        }
    }
    
    $image_id = get_term_meta ( $term_id, 'rdv_category_image_id', true ); 
    $image_url = wp_get_attachment_image_url($image_id, $size, 'single-post-thumbnail');
    if ( !empty($image_id) ) {
        $taxonomy_image_url = $image_url;
    }
    else {
        // If ID is not set with other parameters then to avoid Notice: Undefined variable: taxonomy_image
        $taxonomy_image_url = '';
    }
    return $taxonomy_image_url; 
 }