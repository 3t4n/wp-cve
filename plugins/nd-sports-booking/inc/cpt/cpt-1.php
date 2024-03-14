<?php

///////////////////////////////////////////////////CPT1///////////////////////////////////////////////////////////////
function nd_spt_create_post_type_1() {

    register_post_type('nd_spt_cpt_1',
        array(
            'labels' => array(
                'name' => __('Sports', 'nd-sports-booking'),
                'singular_name' => __('sports', 'nd-sports-booking')
            ),
            'public' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'rewrite' => array('slug' => 'sports' ),
            'menu_icon'   => 'dashicons-awards',
            'supports' => array('title', 'editor', 'thumbnail','excerpt' )
        )
    );
}
add_action('init', 'nd_spt_create_post_type_1');

