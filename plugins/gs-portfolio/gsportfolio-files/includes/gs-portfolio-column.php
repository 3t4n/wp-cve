<?php 
// ============== Displaying Additional Columns ===============

add_filter( 'manage_edit-gs-portfolio_columns', 'gs_portfolio_screen_columns' );

function gs_portfolio_screen_columns( $columns ) {
	unset( $columns['date'] );
    $columns['gs_p_featured_image'] = 'Portfolio';
    $columns['gs_portfolio_url_field'] = 'URL';
    $columns['date'] = 'Date';
    return $columns;
}

// GET FEATURED IMAGE
function gs_portfolio_featured_image($post_ID) {
    $gs_p_post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($gs_p_post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($gs_p_post_thumbnail_id);
        return $post_thumbnail_img[0];
    }
}

add_action('manage_posts_custom_column', 'gs_portfolio_columns_content', 10, 2);
// SHOW THE FEATURED IMAGE
function gs_portfolio_columns_content($gs_p_column_name, $post_ID) {
    if ($gs_p_column_name == 'gs_p_featured_image') {
        $gs_p_post_featured_image = gs_portfolio_featured_image($post_ID);
        if ($gs_p_post_featured_image) {
            echo '<img src="' . $gs_p_post_featured_image . '" width="34"/>';
        }
    }
}

//Populating the Columns

add_action( 'manage_posts_custom_column', 'gs_portfolio_populate_columns' );

function gs_portfolio_populate_columns( $column ) {

    if ( 'gs_portfolio_url_field' == $column ) {
        $client_url = get_post_meta( get_the_ID(), 'client_url', true );
        echo $client_url;
    }
}


// Columns as Sortable
add_filter( 'manage_edit-gs-logo-slider_sortable_columns', 'gs_portfolio_sort' );

function gs_portfolio_sort( $columns ) {
    $columns['gs_portfolio_url_field'] = 'gs_portfolio_url_field';
 
    return $columns;
}