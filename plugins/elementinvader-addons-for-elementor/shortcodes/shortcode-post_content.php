<?php

/*
* Widget [eli-show_post], show latest listings in list view
* atts list:
*
* post_id (int) - id of post or WDK Listing Preview

*/

add_shortcode('eli-show_post', 'eli_shortcode_post_content');

function eli_shortcode_post_content($atts) {
    // Attributes
    $atts = shortcode_atts(array(
        'post_id'=>NULL,
    ), $atts);

    // Get post content based on the provided post ID
    if(empty($atts['post_id']))
        return false;
    
    $post_id = absint($atts['post_id']);
    $post_data = get_post($post_id);

    // Check if the post exists
    if ($post_data) {
        // Return the post content
        $content = '';
        if($post_data){
            if($post_data->post_type == 'page' || $post_data->post_type == 'elementor_library') {
                $listing_page_id = get_option('wdk_listing_page');

                $elementor_instance = \Elementor\Plugin::instance();
                $content = $elementor_instance->frontend->get_builder_content_for_display($post_id);
                if(empty($content )) {
                    $content = $post_data->post_content;
                } 


            } elseif($post_data->post_type == 'wdk-listing') {
                global $wdk_listing_id;

                $wdk_listing_id_bac = $wdk_listing_id;
                $wdk_listing_id = $post_id;

                $wdk_listing_page_id = get_option('wdk_listing_page');

                $elementor_instance = Elementor\Plugin::instance();
                $content = $elementor_instance->frontend->get_builder_content_for_display($wdk_listing_page_id);

                $wdk_listing_id = $wdk_listing_id_bac;

                if(empty($content )) {
                    $content = $post_data->post_content;
                } 
            } else {
                $content = $post_data->post_content;
            }

            return $content;
        }
    }
    
    return false;
}

add_shortcode('show_post_content', 'show_post_content_shortcode');



?>