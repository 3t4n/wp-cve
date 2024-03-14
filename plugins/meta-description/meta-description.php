<?php 
/*
Plugin Name: Meta Description
Plugin URI: http://shinraholdings.com.com/plugins/meta-description
Description: Adds a meta box for inputting post/page HTML meta descriptions.
Version: 1.0.6
Author: bitacre
Author URI: http://shinraholdings.com/
License: GPLv3 (http://gnu.org/licenses/gpl-3.0.txt)
Copyright 2018 Shinra Web Holdings (http://shinraholdings.com)
*/

// get saved meta description for post or page
function shinra_get_meta_description( $post_id ) {
	$output = get_post_meta( $post_id, 'meta_description', true ); // returns '' on empty
	return preg_replace( '/(^|[^\n\r])[\r\n](?![\n\r])/', '$1 ', $output ); // strip out line breaks since this is a meta tag value
}

// register the meta box
function shinra_add_meta_description_metaboxes() {
	add_meta_box( 'shinra-add-meta-description-metabox', 'Meta Description', 'shinra_draw_meta_description_metabox', 'post', 'normal', 'high' );
	add_meta_box( 'shinra-add-meta-description-metabox', 'Meta Description', 'shinra_draw_meta_description_metabox', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'shinra_add_meta_description_metaboxes', 10 );

// add meta box text to post meta
function shinra_save_meta_description( $post_id ) {
	
	// verify
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // only on user init saves
	if ( !wp_verify_nonce( $_POST['shinra_meta_description_nonce'], plugin_basename( __FILE__ ) ) ) return; // check nonce
	if ( $_POST['post_type'] == 'page' ) if ( !current_user_can( 'edit_page', $post_id ) ) return; // edit page capability?
	if ( $_POST['post_type'] == 'post' ) if( !current_user_can( 'edit_post', $post_id ) ) return; // edit post capability?

	// update
	update_post_meta( $post_id, 'meta_description', esc_attr( $_POST['shinra_meta_description_textarea'] ) );
}
add_action( 'save_post', 'shinra_save_meta_description', 10, 1 );

// admin page meta box inner HTML
function shinra_draw_meta_description_metabox( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'shinra_meta_description_nonce' ); ?>

<input id="shinra_meta_description_textarea" name="shinra_meta_description_textarea" style="width:98%;" value="<?php echo shinra_get_meta_description( $post->ID ); ?>">

<?php 
}

// function to insert HTML code
function shinra_meta_description_filter() { 
	global $post;
	if( is_single() || is_page() ) $meta_description = shinra_get_meta_description( $post->ID );
	else return; 
	if( empty( $meta_description ) ) return; ?>
<meta name="description" content="<?php echo $meta_description; ?>" /> 
<?php
}
add_action( 'wp_head', 'shinra_meta_description_filter', 1 );

?>
