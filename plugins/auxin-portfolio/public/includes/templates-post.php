<?php

/**
 * Retrieves the markup for related portfolios
 *
 * @param  array  $args The element setting
 * @return string       The final markup
 */
function auxpfo_get_portfolio_related_posts( $args = array() ){

    if( ! function_exists('auxin_get_the_related_posts') ){
        return __( 'Please activate "Auxin Elements" plugin.' );
    }

    global $post;

    // the recent posts args
    $defaults = array(
        'title'                       => auxin_get_option('portfolio_related_posts_label', __( 'Related Projects', 'auxin-portfolio' ) ),
        'post_type'                   => 'portfolio',
        'taxonomy_name'               => 'portfolio-cat', // the taxonomy that we intent to display in post info
        'taxonomy'                    => array( 'portfolio-cat', 'portfolio-tag' ),
        'desktop_cnum'                => auxin_get_option('portfolio_related_posts_column_number', 3 ),
        'tablet_cnum'                 => 2,
        'phone_cnum'                  => 1,
        'size'                        => '', // array or string. thumbnail, medium, medium_large, large, full
        'display_title'               => true,
        'show_info'                   => true,
        'show_date'                   => false,
        'author_or_readmore'          => 'none', // readmore, author, none
        'display_categories'          => auxin_get_option('portfolio_related_posts_display_taxonomies', true ),
        'max_taxonomy_num'            => 3,
        'wp_query_args'               => array(),
        'content_layout'              => 'default', // entry-boxed
        'post_info_position'          => 'after-title',
        'image_aspect_ratio'          => auxin_get_option('portfolio_related_image_aspect_ratio', 0.56 ),
        'preview_mode'                => auxin_get_option( 'portfolio_related_posts_preview_mode', 'grid' ),
        'grid_table_hover'            => 'bgimage-bgcolor',
        'ignore_media'                => false, // whether to ignore media for this
        'exclude'                     => '',
        'include'                     => '',
        'order_by'                    => 'rand',
        'order'                       => 'desc',
        'exclude_without_media'       => 0,
        'exclude_custom_post_formats' => 0,
        'exclude_quote_link'          => 0,
        'exclude_post_formats_in'     => array(), // the list od post formats to exclude
        'display_like'                => true,
        'extra_classes'               => auxin_get_option('portfolio_related_posts_align_center', false ) ? 'aux-text-align-center': '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'carousel_space'              => 30,
        'carousel_autoplay'           => false,
        'full_width'                  => false,
        'carousel_autoplay_delay'     => '2',
        'carousel_navigation'         => 'perpage',
        'carousel_navigation_control' => 'bullets',
        'carousel_loop'               => 1,
        'base_class'                  => 'aux-widget-recent-posts aux-widget-related-posts',
        'text_alignment'              => '',
        'container_start_tag'         => '<div class="aux-container aux-fold">',
        'container_end_tag'           => '</div>'
    );

    // get snap option
    if( 'default' == $snap_related_item = auxin_get_post_meta( $post, '_related_posts_snap_items', 'default' ) ) {
        $snap_related_item = auxin_get_option( 'portfolio_related_posts_snap_items', false );
    }
    $defaults['carousel_space'] = $snap_related_item = auxin_is_true( $snap_related_item )? 0: 30;

    // whether the wapper is full width or not
    // get full width option
    if( 'default' == $full_width = auxin_get_post_meta( $post, '_related_posts_full_width', 'default' ) ) {
        $full_width = auxin_get_option( 'portfolio_related_posts_full_width', false );
    }

    if( auxin_is_true( $full_width ) ){
         $defaults['container_start_tag']  = '<div class="aux-container">';
    }

    // whether to snap the items (0 space between the items)
    if( $defaults['carousel_space'] === 0 ){
        $defaults['extra_column_classes'] = 'aux-no-gutter';
    }


    $args = wp_parse_args( $args, $defaults );

    // ------------------------------------------------

    return auxin_get_the_related_posts( $args );
}
