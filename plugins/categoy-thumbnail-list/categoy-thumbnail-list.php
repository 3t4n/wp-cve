<?php
/**
* Plugin Name: Category Thumbnail List
* Plugin URI: https://plugins.followmedarling.se/category-thumbnail-list/
* Description: Creates a list of thumbnail images, using the_post_thumbnail() in WordPress 2.9 and up.
* Version: 2.03
* Author: Jonk @ Follow me Darling
* Author URI: https://plugins.followmedarling.se/
* Domain Path: /languages
* Text Domain: categoy-thumbnail-list
**/

$categoryThumbnailList_Order = stripslashes( get_option( 'category-thumbnail-list_order' ) );
if ( $categoryThumbnailList_Order == '' ) {
	$categoryThumbnailList_Order = 'date';
}
$categoryThumbnailList_OrderType = stripslashes( get_option( 'category-thumbnail-list_ordertype' ) );
if ( $categoryThumbnailList_OrderType == '' ) {
	$categoryThumbnailList_OrderType = 'DESC';
}
    
$categoryThumbnailList_Path = get_option('siteurl')."/wp-content/plugins/categoy-thumbnail-list/";

define( "categoryThumbnailList_REGEXP", "/\[categorythumbnaillist ([[:print:]]+)\]/" );

define( "categoryThumbnailList_TARGET", "###CATTHMBLST###" );

function categoryThumbnailList_callback($listCatId) {
	global $post;
	global $categoryThumbnailList_Order;
	global $categoryThumbnailList_OrderType;
	$tmp_post = $post; 
	$myposts_arr = array(
        'numberposts'      => -1,
        'category'         => $listCatId[1],
        'orderby'          => $categoryThumbnailList_OrderType,
        'order'            => $categoryThumbnailList_Order,
    );
	$myposts = get_posts( $myposts_arr );
	$output = '<div class="category-thumbnail-list">';
	foreach($myposts as $post) :
		setup_postdata($post);
		if ( has_post_thumbnail() ) {
		$link = get_permalink($post->ID);
		$thmb = get_the_post_thumbnail($post->ID,'thumbnail');
		$title = get_the_title();
		$output .= '<div class="category-thumbnail-list-item">';
			$output .= '<a href="' . $link . '" title="' . esc_attr( $title ) . '">' . $thmb;
			$output .= $title . '</a>';
		$output .= '</div>';
		}
	endforeach;
	$output .= '</div>';
	$post = $tmp_post;
	wp_reset_postdata();
	return ($output);
	$output = '';
}

add_filter( 'the_content', 'categoryThumbnailList' ,1 );
function categoryThumbnailList( $content ) {
	return ( preg_replace_callback( categoryThumbnailList_REGEXP, 'categoryThumbnailList_callback', $content ) );
}

add_action( 'wp_enqueue_scripts', 'category_thumbnail_list_css', 11 );
function category_thumbnail_list_css() {
	wp_register_style( 'categoy-thumbnail-list', plugins_url( '/categoy-thumbnail-list.css', __FILE__ ) );
	wp_enqueue_style( 'categoy-thumbnail-list' );
}
?>
<?php
add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
  add_options_page( 'Category Thumbnail List Options', 'Category Thumbnail List', 'manage_options', 'category-thumbnail-list', 'my_plugin_options' );
}

function my_plugin_options() {
	global $categoryThumbnailList_Order;
	global $categoryThumbnailList_OrderType;

	if ( isset( $_POST['save_category-thumbnail-list_settings'] ) ) {
        // update order type
        if( !$_POST['category-thumbnail-list_ordertype'] ) {
            $_POST['category-thumbnail-list_ordertype'] = 'date';
        }
        update_option('category-thumbnail-list_ordertype', $_POST['category-thumbnail-list_ordertype'] );
        
        // update order
        if( !$_POST['category-thumbnail-list_order'] ) {
            $_POST['category-thumbnail-list_order'] = 'DESC';
        }
        update_option( 'category-thumbnail-list_order', $_POST['category-thumbnail-list_order'] );
        
        $categoryThumbnailList_Order = stripslashes( get_option( 'category-thumbnail-list_order' ) );
	$categoryThumbnailList_OrderType = stripslashes( get_option( 'category-thumbnail-list_ordertype' ) );
	
	echo "<div id=\"message\" class=\"updated fade\"><p>Your settings are now updated</p></div>\n";
		
	}	
	?>
  <div class="wrap">
	<h2>Category Thumbnail List Settings</h2>
	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Order by</th>
				<td>
					<select name="category-thumbnail-list_ordertype" id="category-thumbnail-list_ordertype">
							<option <?php if ($categoryThumbnailList_OrderType == 'date') { echo 'selected="selected"'; } ?> value="date">Date</option>
							<option <?php if ($categoryThumbnailList_OrderType == 'title') { echo 'selected="selected"'; } ?> value="title">Title</option>
					</select>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row">Display order</th>
				<td>
					<select name="category-thumbnail-list_order" id="category-thumbnail-list_order">
							<option <?php if ($categoryThumbnailList_Order == 'DESC') { echo 'selected="selected"'; } ?> value="DESC">Descending (z-a/9-1/2010-2001)</option>
							<option <?php if ($categoryThumbnailList_Order == 'ASC') { echo 'selected="selected"'; } ?> value="ASC">Ascending (a-z/1-9/2001-2010)</option>
					</select>
				</td> 
			</tr>
		</table>

		<div class="submit">
			<!--<input type="submit" name="reset_category-thumbnail-list_settings" value="<?php _e('Reset') ?>" />-->
			<input type="submit" name="save_category-thumbnail-list_settings" value="<?php _e('Save Settings') ?>" class="button-primary" />
		</div>
		<div>
			<a href="options-media.php">Update the thumbnail sizes here</a>
		</div>
		<div>
			<a href="plugin-editor.php?file=categoy-thumbnail-list/categoy-thumbnail-list.css&plugin=categoy-thumbnail-list/categoy-thumbnail-list.php">You may need to update your css when changing the thumbnail size</a>
		</div>
		
	</form>
  </div>
<?php
}
?>
