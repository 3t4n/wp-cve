<?php
/*
Plugin Name: Custom ShortCode Creator
Plugin URI: http://www.odrasoft.com/
Description: Declares a plugin that will create a custom post type displaying Short Codes.
Version: 2.0
Author: swadeshswain
Author URI: http://www.odrasoft.com/
License: GPLv2
*/
?>
<?php  
add_action( 'init', 'odras_create_custom_shortcode' );
function odras_create_custom_shortcode() {
    register_post_type( 'odras_myshortcodes',
        array(
            'labels' => array(
                'name' => 'Short Codes',
                'singular_name' => 'Short Code',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Short Code',
                'edit' => 'Edit',
                'edit_item' => 'Edit Short Code',
                'new_item' => 'New Short Code',
                'view' => 'View',
                'view_item' => 'View Short Code',
                'search_items' => 'Search Short Codes',
                'not_found' => 'No Short Codes found',
                'not_found_in_trash' => 'No Short Codes found in Trash',
                'parent' => 'Parent Short Code'
            ),
            'public' => true,
            'menu_position' => 15,
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true
        )
    );
}
?>
<?php 
add_action("manage_posts_custom_column",  "odras_myshortcodes_custom_columns");
add_filter("manage_edit-odras_myshortcodes_columns", "odras_myshortcodes_edit_columns");
function odras_myshortcodes_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "ShortCodes Title",
    "id" => "Shortcode",
	"author" => "Author",
	"date" => "Date",
  );
  return $columns;
}
function odras_myshortcodes_custom_columns($column){
  global $post;
  switch ($column) {
    case "id":
 echo '[shortcode id="';echo get_the_ID(); echo '"]';
      break;
  }
}
?>
<?php 
function odras_content_func($atts){
	extract( shortcode_atts( array(
		'id' => null,
	), $atts ) );
$post = get_post($id);
$content = $post->post_content;
if (strpos($content, '<' . '?') !== false) {
        ob_start();
        eval('?' . '>' . $content);
        $content = ob_get_clean();
    }
return $content;
}
add_shortcode('shortcode','odras_content_func');
 ?>