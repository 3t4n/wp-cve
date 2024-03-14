<?php

function mpsp_custom_post_type(){
  $labels = array(
    'name' => _x('Posts Slider','post type general name'),
    'singular_name' => _x('Posts Slider','post type singular name'),
    'add_new' => _x('Add New','Posts Slider'),
    'add_new_item' => __('Add new Posts Slider'),
    'edit_item' => __('Edit Posts Slider'),
    'new_item' => __('New Posts Slider'),
    'all_items' => __('All Posts Sliders'),
    'view_itme' => __('View Posts Slider'),
    'search_items' => __('Search Posts Slider'),
    'not_found' => __('No Posts Slider found'),
    'not_found_in_trash' => __('No Posts Slider found in trash'),
    'parent_item_colon' => "",
    'menu_name' => 'Posts Slider',

    );
  $args = array(
    'labels' => $labels,
    'description' => 'Create Posts Slider',
    'public' => true,
    'supports' => array('title','custom_fields'),
    'has_archive' => true,
    'capability_type' => 'post',
    'query_var' => 'mpsp_slider',
    'menu_icon' => 'dashicons-welcome-add-page',
    'show_in_menu' => true,
    );


  register_post_type('mpsp_slider',$args);
}

add_action('init','mpsp_custom_post_type');



function mpsp_custom_posts_column($defaults) {
	unset($defaults['date']);
    $defaults['mpsp_shortocode']  = 'Shortocode';
    return $defaults;
}
function mpsp_display_custom_column_data($column_name, $post_ID) {
    if ($column_name == 'mpsp_shortocode') {
        echo "<div style='padding: 7px 10px 8px 31px;background: #fff;border: 1px solid #D2D2D2;border-radius: 3px;width: 20%; min-width:200px;font-weight: bold;' >[mpsp_posts_slider id='$post_ID']</div>";
    }
}

add_filter('manage_mpsp_slider_posts_columns', 'mpsp_custom_posts_column');
add_action('manage_mpsp_slider_posts_custom_column','mpsp_display_custom_column_data',10, 2);




 ?>