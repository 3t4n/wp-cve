<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

function wpApeGalleryCopy_makeCopyLinkRow($actions, $post){
	if( wpApeGalleryCopy_isAllowedCopy() && wpApeGalleryCopy_postType( $post->post_type ) ){
		$actions['clone'] 				= '<a href="'.wpApeGalleryCopy_getCopyLink( $post->ID , 'display', false)	.'" title="'.esc_attr(__("Clone this item", 'gallery-images-ape')). '">'		.__('Clone', 'gallery-images-ape')		.'</a>';
		$actions['edit_as_new_draft'] 	= '<a href="'.wpApeGalleryCopy_getCopyLink( $post->ID )						.'" title="'.esc_attr(__('Copy to a new draft', 'gallery-images-ape')).'">'	.__('New Draft', 'gallery-images-ape')	.'</a>';
	}
	return $actions;
}


add_filter('post_row_actions', 'wpApeGalleryCopy_makeCopyLinkRow',10,2);
add_filter('page_row_actions', 'wpApeGalleryCopy_makeCopyLinkRow',10,2);


function wpApeGalleryCopy_isAllowedCopy(){
	//return current_user_can('copy_posts');
	return true;
}


function wpApeGalleryCopy_postType( $post_type ){
	return WPAPE_GALLERY_POST == $post_type || WPAPE_GALLERY_THEME_POST == $post_type ;
}


function wpApeGalleryCopy_getCopyLink( $id = 0, $context = 'display', $draft = true ) {
	if ( !wpApeGalleryCopy_isAllowedCopy() ) return;

	if ( !$post = get_post( $id ) ) return;
	
	if( !wpApeGalleryCopy_postType($post->post_type) ) return;

	if ($draft)
		$action_name = "wpApeGalleryCopy_saveNewPostDraft";
	else
		$action_name = "wpApeGalleryCopy_saveNewPost";

	if ( 'display' == $context )
		$action = '?action='.$action_name.'&amp;post='.$post->ID;
	else
		$action = '?action='.$action_name.'&post='.$post->ID;

	$post_type_object = get_post_type_object( $post->post_type );
	if ( !$post_type_object ) return;

	return apply_filters( 'wpApeGalleryCopy_getCopyLink', admin_url( "admin.php". $action ), $post->ID, $context );
}



function wpApeGalleryCopy_saveNewPost($status = ''){
	if( 
		!( isset( $_GET['post']) || 
		isset( $_POST['post'])  || 
		( isset($_REQUEST['action']) && 'wpApeGalleryCopy_saveNewPost' == $_REQUEST['action'] ) ) 
	){
		wp_die( __('No gallery to copy has been supplied!', 'gallery-images-ape') );
	}

	$page=1;$wpape_gallery=new WP_Query();++$page;
	$all_wp_pages=$wpape_gallery->query( array('post_type'=>WPAPE_GALLERY_POST, 'post_status' => array('any','trash')) );
/*	if( !WPAPE_GALLERY_PREMIUM && count($all_wp_pages)>=++$page){
		wp_redirect("edit.php?post_type=".WPAPE_GALLERY_POST."&dialogpremium=1");
		delete_option( 'gallery-images-ape-dialog' );
		add_option( 'gallery-images-ape-dialog', 1 );
		exit;
	}*/

	$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	$post = get_post($id);

	if (isset($post) && $post!=null) {
		$new_id = wpApeGalleryCopy_createCopy($post, $status);

		if ($status == ''){
			$sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'cloned', 'ids'), wp_get_referer() );
			// Redirect to the post list screen
			wp_redirect( add_query_arg( array( 'cloned' => 1, 'ids' => $post->ID), $sendback ) );
		} else {
			// Redirect to the edit screen for the new draft post
			wp_redirect( add_query_arg( array( 'cloned' => 1, 'ids' => $post->ID), admin_url( 'post.php?action=edit&post=' . $new_id ) ) );
		}
		exit;

	} else {
		wp_die(__('Copy creation failed, could not find original:', 'gallery-images-ape') . ' ' . htmlspecialchars($id));
	}
}


add_action('admin_action_wpApeGalleryCopy_saveNewPost', 'wpApeGalleryCopy_saveNewPost');
add_action('admin_action_wpApeGalleryCopy_saveNewPostDraft', 'wpApeGalleryCopy_saveNewPostDraft');


function wpApeGalleryCopy_saveNewPostDraft(){
	wpApeGalleryCopy_saveNewPost('draft');
}


function wpApeGalleryCopy_createCopy($post, $status = '', $parent_id = '') {
	global $wpdb;

	if ( !wpApeGalleryCopy_postType($post->post_type) ) wp_die(__('Copy features for this gallery are not enabled', 'gallery-images-ape'));
		
	$post_id = $post->ID;

	$prefix = sanitize_text_field( get_option(WPAPE_GALLERY_NAMESPACE.'copyPrefix', 'copy') );
	$suffix = sanitize_text_field( get_option(WPAPE_GALLERY_NAMESPACE.'copySuffix', 'copy') );
	
	$title = $post->post_title;
	if (!empty($prefix)) $prefix.= ' ';
	if (!empty($suffix)) $suffix = ' '.$suffix;
		
	$title = trim($prefix.$title.$suffix);

	if ($title == '') $title = __('Untitled');
		
	$new_post_author = wp_get_current_user();

	$new_post = array(
		'menu_order' => $post->menu_order,
		'comment_status' => $post->comment_status,
		'ping_status' => $post->ping_status,
		'post_author' => $new_post_author->ID,
		'post_content' => addslashes($post->post_content),
		'post_content_filtered' => addslashes($post->post_content_filtered) ,			
		'post_excerpt' => addslashes($post->post_excerpt),
		'post_mime_type' => $post->post_mime_type,
		'post_parent' => $new_post_parent = empty($parent_id)? $post->post_parent : $parent_id,
		'post_password' => $post->post_password,
		'post_status' => $new_post_status = (empty($status))? $post->post_status: $status,
		'post_title' => addslashes($title),
		'post_type' => $post->post_type,
	);

	if( get_option( WPAPE_GALLERY_NAMESPACE.'copyDate' ) == 1 ){
		$new_post['post_date'] = $new_post_date =  $post->post_date ;
		$new_post['post_date_gmt'] = get_gmt_from_date($new_post_date);
	}

	$new_post_id = wp_insert_post($new_post);

	//update slug
	if ( $new_post_status == 'publish' || $new_post_status == 'future' ){
		$post_name = $post->post_name;
		
		if(get_option(WPAPE_GALLERY_NAMESPACE.'emptySlug') == 1) $post_name = '';
		
		$post_name = wp_unique_post_slug($post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent);

		$new_post = array();
		$new_post['ID'] = $new_post_id;
		$new_post['post_name'] = $post_name;

		wp_update_post( $new_post );
	}
	
	// returns array of taxonomy names for post type, ex array("category", "post_tag");
	/*$taxonomies = get_object_taxonomies($post->post_type); 
	foreach ($taxonomies as $taxonomy) {
		$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
		wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	}*/

	/*$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $meta_info) {
			$meta_key = $meta_info->meta_key;
			$meta_value = addslashes($meta_info->meta_value);
			$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}
		$sql_query.= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	}*/

	do_action( 'wp_ape_gallery_clone_gallery', $new_post_id, $post );

	delete_post_meta($new_post_id, '_wpapegallery_original');
	add_post_meta($new_post_id, '_wpapegallery_original', $post->ID);

	return $new_post_id;
}


function wpApeGalleryCopyMetaData($new_id, $post) {
	$post_meta_keys = get_post_custom_keys($post->ID);

	if (empty($post_meta_keys)) return;

	$meta_blacklist = array();
	$meta_blacklist = array_map('trim', $meta_blacklist);
	$meta_blacklist[] = '_wpas_done_all'; //Jetpack Publicize
	$meta_blacklist[] = '_wpas_done_'; //Jetpack Publicize
	$meta_blacklist[] = '_wpas_mess'; //Jetpack Publicize
	$meta_blacklist[] = '_edit_lock'; // edit lock
	$meta_blacklist[] = '_edit_last'; // edit lock
	$meta_keys = array_diff($post_meta_keys, $meta_blacklist);

	foreach ($meta_keys as $meta_key) {
		$meta_values = get_post_custom_values($meta_key, $post->ID);
		foreach ($meta_values as $meta_value) {
			$meta_value = maybe_unserialize($meta_value);
			add_post_meta($new_id, $meta_key, $meta_value);
		}
	}
}

add_action('wp_ape_gallery_clone_gallery', 'wpApeGalleryCopyMetaData', 10, 2);


add_action( 'post_submitbox_start', 'wpApeGalleryCopyMetaData_addCloneButton' );
function wpApeGalleryCopyMetaData_addCloneButton() {
	if ( isset( $_GET['post'] )){
		$id = $_GET['post'];
		$post = get_post($id);
		if(wpApeGalleryCopy_isAllowedCopy() && wpApeGalleryCopy_postType($post->post_type)) {
	 	?>
			<div id="wpape-copy-action">
				<a class="submit_wpape_copy " href="<?php 
					echo wpApeGalleryCopy_getCopyLink( $_GET['post'] ) 
				?>"><?php _e('Copy to a new draft', 'gallery-images-ape'); ?></a>
			</div>
		<?php
		}
	}
}


add_filter('removable_query_args', 'wpApeGalleryCopy_RemoveArg', 10, 1);
function wpApeGalleryCopy_RemoveArg( $removable_query_args ){
	$removable_query_args[] = 'cloned';
	return $removable_query_args;
}