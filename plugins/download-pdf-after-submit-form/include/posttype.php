<?php
//<-- custom post --> 
add_action( 'init', 'dpbsf_download_posttype' );
function dpbsf_download_posttype() {
 $labels = array(
  'name'               => _x( 'Download PDF After Submit Form', 'post type general name', 'dpbsf' ),
  'singular_name'      => _x( 'Download PDF After Submit Form', 'post type singular name', 'dpbsf' ),
  'menu_name'          => _x( 'Download PDF After Submit F.', 'admin menu', 'dpbsf' ),
  'name_admin_bar'     => _x( 'Download PDF After Submit Form', 'add new on admin bar', 'dpbsf' ),
  'add_new'            => _x( 'Add New', 'Download PDF After Submit Form', 'dpbsf' ),
  'add_new_item'       => __( 'Add New Download PDF After Submit Form', 'dpbsf' ),
  'new_item'           => __( 'New Download PDF After Submit Form', 'dpbsf' ),
  'edit_item'          => __( 'Edit Download PDF After Submit Form', 'dpbsf' ),
  'view_item'          => __( 'View Download PDF After Submit Form', 'dpbsf' ),
  'all_items'          => __( 'View Entries', 'dpbsf' ),
  'search_items'       => __( 'Search Download PDF After Submit Form', 'dpbsf' ),
  'parent_item_colon'  => __( 'Parent Download PDF After Submit Form:', 'dpbsf' ),
  'not_found'          => __( 'No Download PDF After Submit Form found.', 'dpbsf' ),
  'not_found_in_trash' => __( 'No Download PDF After Submit Form found in Trash.', 'dpbsf' )
 );
 $args = array(
  'labels'             => $labels,
  'description'        => __( 'Description.', 'dpbsf' ),
  'public'             => true,
  'publicly_queryable' => false,
  'show_ui'            => true,
  'show_in_menu'       => true,
  'query_var'          => true,
  'rewrite'            => array( 'slug' => 'infomat' ),
  'capability_type'    => 'post', 
  'has_archive'        => true,
  'hierarchical'       => false,
  'menu_position'      => null,
  'menu_icon'          => 'dashicons-format-aside',
  'supports'           => array( 'none'),
  
 );
 register_post_type( 'infomat', $args );
}  
//remove add new button
function dpbsf_hide_infomat_add_new_button() {
    global $post_type;
    if ($post_type === 'infomat') {
        echo '<style>#menu-posts-infomat .wp-submenu li:nth-child(3), .post-type-infomat a.page-title-action { display: none !important; }</style>';
    }
    global $current_screen;
    if ($current_screen && $current_screen->post_type === 'infomat') {
        echo '<style>#wp-admin-bar-new-infomat { display: none !important; }</style>';
    }
}
add_action('admin_head', 'dpbsf_hide_infomat_add_new_button');

//show editor post
function dpbsf_custom_metabox_content() {
    global $post;
    $post_content = $post->post_content;
    $post_title = $post->post_title;
    ?>
    <div class="custom-title-metabox">
        <label for="custom_post_title">Title: <b><?php echo esc_attr($post_title); ?></b></label>
    </div>
    <div class="custom-content-metabox">
        <?php echo wpautop($post_content); ?>
    </div>
    <?php
}
function dpbsf_add_custom_metabox() {
    add_meta_box('dpbsf_custom_content_metabox', 'View Entrie', 'dpbsf_custom_metabox_content', 'infomat', 'normal'); // Replace 'post' with your custom post type if needed
}
add_action('add_meta_boxes', 'dpbsf_add_custom_metabox');
//change edit to view
function dpbsf_change_edit_text_to_view($translated_text, $untranslated_text, $domain) {
    global $post_type;
    if ($post_type === 'infomat') {
        // Change "Edit" to "View"
        if ($untranslated_text === 'Edit') {
            return 'View';
        }
    }
    // Return the original translation for other texts
    return $translated_text;
}
add_filter('gettext', 'dpbsf_change_edit_text_to_view', 10, 3);
?>