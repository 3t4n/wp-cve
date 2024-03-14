<?php /* Loops through all portfolio, taxes, .. and display posts */


// the page number
$paged            = max( 1, get_query_var('paged'), get_query_var('page') );
// get portfolio template type id
$portfolio_taxonomy_template = auxin_get_option( 'portfolio_taxonomy_template_type', 'grid-1' );

// @TODO: we should use portfolio_archive_items_perpage for portfolio per page in both portfolio archive and taxonomy
// get template type
$template_type = strstr( $portfolio_taxonomy_template, '-', true );

// posts perpage
$per_page         = get_option( 'posts_per_page' );
$portfolio_per_page = auxin_get_option( 'portfolio_archive_items_perpage', 12 );

// if template type is masonry
if( in_array( $template_type, array('grid', 'masonry', 'tiles') ) ){

    $queried_object = get_queried_object();
    $args = array(
        'posts_per_page'              => $portfolio_per_page,
        'paged'                       => $paged,
        'order_by'                    => 'menu_order date',
        'order'                       => 'desc',
        'display_like'                => auxin_get_option( 'show_portfolio_taxonomy_like_button' ),
        'deeplink'                    => false,
        'item_style'                  => auxin_get_option( 'portfolio_taxonomy_grid_item_type' ),
        'paginate'                    => 1,
        'perpage'                     => $portfolio_per_page,
        'show_filters'                => false,
        'show_title'                  => true,
        'show_info'                   => true,
        'filter_by'                   => $queried_object->taxonomy,
        'term'                        => '',
        'image_aspect_ratio'          => auxin_get_option( 'portfolio_taxonomy_image_aspect_ratio'),
        'space'                       => auxin_get_option( 'portfolio_taxonomy_grid_space') ,
        'desktop_cnum'                => auxin_get_option( 'portfolio_taxonomy_column_number' ),
        'tablet_cnum'                 => auxin_get_option( 'portfolio_taxonomy_column_number_tablet' ),
        'phone_cnum'                  => auxin_get_option( 'portfolio_taxonomy_column_number_mobile' ),
        'layout'                      => $template_type,
        'extra_classes'               => '',
        'custom_el_id'                => '',
        'reset_query'                 => false,
        'use_wp_query'                => true, // true to use the global wp_query, false to use internal custom query
        'base_class'                  => 'aux-widget-recent-portfolios',
        'called_from'                 => 'taxonomy',
        'num'                         => $portfolio_per_page,
        'override_global_query'       => true
    );

    if ( $queried_object->taxonomy == 'portfolio-cat' ) {
        $args['cat'] = $queried_object->term_id;
    } elseif ( $queried_object->taxonomy == 'portfolio-tag' ) {
        $args['tag'] = $queried_object->term_id;
    } elseif ( $queried_object->taxonomy == 'portfolio-filter' ) {
        $args['filter'] = $queried_object->term_id;
    }

    if( 'masonry' == $template_type ){
        unset( $args['image_aspect_ratio'] );

    } elseif( 'tiles' == $template_type ){
        unset( $args['image_aspect_ratio'] );
        unset( $args['space'] );
        unset( $args['desktop_cnum'] );
        unset( $args['tablet_cnum' ] );
        unset( $args['phone_cnum'  ] );
    }

    if( function_exists( 'auxin_widget_recent_portfolios_grid_callback' ) ){
        // get the shortcode base portfolio taxonomy page
        $result = auxin_widget_recent_portfolios_grid_callback( $args );
    } else {
        $result = __('To enable this feature, please install "Auxin Portfolio" plugin.', 'auxin-portfolio' );
    }

// if template type is default means land
} else {
    global $query_string;
    $q_args = '&paged='. $paged. '&posts_per_page='. get_option( 'posts_per_page' );

    // query the posts
    query_posts( $query_string . $q_args ); 
    // does this query has result?
    $result = have_posts();
}


// if it is not a shortcode base blog page
if( true === $result ){

    while ( have_posts() ) : the_post();
        auxpfo_get_template_part( 'theme-parts/entry/portfolio', 'land' );
    endwhile; // end of the loop.

// if it is a shortcode base blog page
} elseif( ! empty( $result ) ){
    echo $result;

// if result not found
} else {
    auxpfo_get_template_part( 'theme-parts/content', 'none' );
}

auxin_the_paginate_nav(
    array( 'css_class' => auxin_get_option('archive_pagination_skin') )
);



