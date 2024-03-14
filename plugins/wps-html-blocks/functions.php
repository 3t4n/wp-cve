<?php

// Hook into the 'init' action
add_action( 'init', 'wpsbx_register_blocks', 1);

function wpsbx_register_blocks() {

    $labels = array(
        'name'                => esc_html__( 'WPS HTML Blocks', 'wpsuites' ),
        'singular_name'       => esc_html__( 'WPS HTML Block', 'wpsuites' ),
        'menu_name'           => esc_html__( 'WPS HTML Blocks', 'wpsuites' ),
        'parent_item_colon'   => esc_html__( 'Parent Item:', 'wpsuites' ),
        'all_items'           => esc_html__( 'All Items', 'wpsuites' ),
        'view_item'           => esc_html__( 'View Item', 'wpsuites' ),
        'add_new_item'        => esc_html__( 'Add New Item', 'wpsuites' ),
        'add_new'             => esc_html__( 'Add New', 'wpsuites' ),
        'edit_item'           => esc_html__( 'Edit Item', 'wpsuites' ),
        'update_item'         => esc_html__( 'Update Item', 'wpsuites' ),
        'search_items'        => esc_html__( 'Search Item', 'wpsuites' ),
        'not_found'           => esc_html__( 'Not found', 'wpsuites' ),
        'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'wpsuites' ),
    );

    $args = array(
        'label'               => esc_html__( 'wpsbx_block', 'wpsuites' ),
        'description'         => esc_html__( 'Custom HTML blocks to place in your pages with shortcode', 'wpsuites' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-media-code',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'rewrite'             => false,
        'capability_type'     => 'page',
    );

    register_post_type('wpsbx_block', $args);

}

function wpsbx_edit_html_blocks_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => esc_html__( 'Title', 'wpsuites' ),
        'shortcode' => esc_html__( 'Shortcode', 'wpsuites' ),
        'date' => esc_html__( 'Date', 'wpsuites' ),
    );

    return $columns;
}

function wpsbx_manage_html_blocks_columns($column, $post_id) {
    switch( $column ) {
        case 'shortcode' :
            echo '<input size="40" type="text" readonly onfocus="this.select()" value="[wpsbx_html_block id='.$post_id.']"></input>';
            break;
    }
}


function wpsbx_register_shortcodes() {
    add_shortcode( 'wpsbx_html_block', 'register_wpsbx_shortcode' );
}

add_action( 'init', 'wpsbx_register_shortcodes' );


function register_wpsbx_shortcode($atts) {
    extract(shortcode_atts(array(
        'id' => 0
    ), $atts));

    return wpsbx_get_html_block($id);
}

function wpsbx_get_html_block($id) {
    $post = get_post( $id );
    if ( ! $post || $post->post_type != 'wpsbx_block' ) return;
    $content = do_shortcode( $post->post_content );

    return $content;

}

    function wpsbx_getallheaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

add_filter( 'manage_edit-wpsbx_block_columns', 'wpsbx_edit_html_blocks_columns');
add_action( 'manage_wpsbx_block_posts_custom_column', 'wpsbx_manage_html_blocks_columns', 10, 2);

// Widget shortcode enable
add_filter('widget_text', 'do_shortcode');