<?php /* Loops through all portfolio posts */


// the page number
$paged            = max( 1, get_query_var('paged'), get_query_var('page') );
// get template type id
$template_type_id = auxin_get_option( 'portfolio_index_template_type', 'grid-1' );
// get template type
$template_type = strstr( $template_type_id, '-', true );
// posts perpage
$per_page         = get_option( 'posts_per_page' );

if( in_array( $template_type, array('grid', 'masonry', 'tiles') ) ){

    $args = array(
        'posts_per_page'              => -1,
        'paged'                       => $paged,
        'order_by'                    => 'menu_order date',
        'order'                       => 'desc',
        'num'                         => auxin_get_option( 'portfolio_archive_items_perpage' ),
        'display_like'                => auxin_get_option( 'show_portfolio_archive_like_button' ),
        'deeplink'                    => false,
        'item_style'                  => auxin_get_option( 'portfolio_archive_grid_item_type' ),
        'paginate'                    => false,
        'perpage'                     => false,
        'show_filters'                => false,
        'show_title'                  => true,
        'show_info'                   => true,
        'image_aspect_ratio'          => auxin_get_option( 'portfolio_image_aspect_ratio'),
        'space'                       => auxin_get_option( 'portfolio_archive_grid_space'),
        'desktop_cnum'                => auxin_get_option( 'portfolio_archive_column_number' ),
        'tablet_cnum'                 => auxin_get_option( 'portfolio_archive_column_number_tablet' ),
        'phone_cnum'                  => auxin_get_option( 'portfolio_archive_column_number_mobile' ),
        'layout'                      => $template_type,
        'tag'                         => '',
        'extra_classes'               => '',
        'custom_el_id'                => '',
        'reset_query'                 => false,
        'use_wp_query'                => true, // true to use the global wp_query, false to use internal custom query
        'base_class'                  => 'aux-widget-recent-portfolios',
        'called_from'                 => 'archive',
        'term'                        => '',
        'filter_by'                   => '',
        'cat'                         => '',
    );

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
        // get the shortcode base portfolio page
        $result = auxin_widget_recent_portfolios_grid_callback( $args );
    } else {
        $result = __('To enable this feature, please install "Auxin Portfolio" plugin.', 'auxin-portfolio' );
    }

// if it is normal portfolio archive loop
} else {
    global $query_string;

    $pere_page = auxin_get_option( 'portfolio_archive_items_perpage', 12 );
    $q_args = '&paged='. $paged. '&posts_per_page='. $pere_page;
    // query the posts
    query_posts( $query_string . $q_args );
    // does this query has result?
    $result = have_posts();
}


// if it is not a shortcode base portfolio page
if( true === $result ){

    while ( have_posts() ) : the_post();

        include 'entry/portfolio-land.php';

    endwhile; // end of the loop.
// if it is a shortcode base portfolio page
} elseif( ! empty( $result ) ){
    echo $result;

// if result not found
} else {
    auxpfo_get_template_part( 'theme-parts/content', 'none' );
}


auxin_the_paginate_nav(
    array( 'css_class' => auxin_get_option('archive_pagination_skin') )
);
