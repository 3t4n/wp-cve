<?php

/**
 * Pagination.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$numpages = '';

if ( isset( $acadp_query ) ) {
    $numpages = $acadp_query->max_num_pages;
}

if ( '' == $numpages ) {
    global $wp_query;
    	
    $numpages = $wp_query->max_num_pages;
    if ( ! $numpages ) {
        $numpages = 1;
    }
}

$pagerange = 2;

if ( empty( $paged ) ) {
    $paged = acadp_get_page_number();
}

// Construct the pagination arguments to enter into our paginate_links function
$arr_params = array();

parse_str( $_SERVER['QUERY_STRING'], $queries );
if ( ! empty( $queries ) ) {
    $arr_params = array_keys( $queries );
}
 
$base = acadp_remove_query_arg( $arr_params, get_pagenum_link( 1 ) );

if ( ! get_option('permalink_structure') || isset( $_GET['q'] ) ) {
    $prefix = strpos( $base, '?' ) ? '&' : '?';
    $format = $prefix . 'paged=%#%';
} else {
    $prefix = ( '/' == substr( $base, -1 ) ) ? '' : '/';
    $format = $prefix . 'page/%#%';
} 

$pagination_args = array(
    'base'         => $base . '%_%',
    'format'       => $format,
    'total'        => $numpages,
    'current'      => $paged,
    'show_all'     => false,
    'end_size'     => 1,
    'mid_size'     => $pagerange,
    'prev_next'    => true,
    'prev_text'    => __( '&laquo;' ),
    'next_text'    => __( '&raquo;' ),
    'type'         => 'array',
    'add_args'     => false,
    'add_fragment' => ''
);

$paginate_links = paginate_links( $pagination_args );

if ( ! $paginate_links ) {
    return false;
}
?>

<div class="acadp-pagination acadp-flex acadp-flex-col acadp-gap-1 acadp-items-center">
    <ul class="acadp-flex acadp-gap-1 acadp-items-center acadp-m-0 acadp-p-0 acadp-list-none">
        <?php
        foreach ( $paginate_links as $key => $page_link ) {		
            echo sprintf( 
                '<li class="acadp-m-0 acadp-p-0 acadp-leading-none">%s</li>', 
                $page_link 
            );
        }
        ?>
    </ul>

    <div class="acadp-text-muted acadp-text-sm">
        <?php 
        printf( 
            __( 'Page %d of %d', 'advanced-classifieds-and-directory-pro' ), 
            $paged, 
            $numpages 
        ); 
        ?>
    </div>
</div>