<?php

$meta_query_args = array(
    'post_type'     => 'reuseb_template',
    'meta_query'    => array(
        'relation' => 'AND',
        array(
            'key'     => 'reuseb_template_post_select',
            'value'   => get_post_type(),
            'compare' => '='
        ),
        array(
            'key'     => 'reuseb_template_select_type',
            'value'   => 'single',
            'compare' => '='
        ),
    ),
);
$meta_query = get_posts( $meta_query_args );

// if template found load it
if( $meta_query ) {

    $template = get_post( $meta_query[0]->ID );
    echo do_shortcode( $template->post_content );
}
