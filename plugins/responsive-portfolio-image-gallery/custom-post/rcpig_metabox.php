<?php
/*
 *  Responsive Portfolio Image Gallery 1.2
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
add_action( 'add_meta_boxes', 'rcpig_add_meta_box' );
add_action( 'wp_insert_post', 'rcpig_insert_postdata' );
add_action( 'save_post', 'rcpig_insert_postdata' );
add_action( 'add_meta_boxes', 'rcpig_add_meta_button' );
add_action( 'save_post', 'rcpig_save_meta_boxes_button' );

function rcpig_add_meta_button(){
	$rcpig_post_type = 'rcpig';
	add_meta_box(
		'rcpig_portfolio_button',
		esc_html__( 'Buttons For this Portfolio', 'rcpig' ),
		'rcpig_render_buttons_meta_box',
		$rcpig_post_type,
		'normal',
		'default'
	);
}
function rcpig_render_buttons_meta_box() {
	global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'rcpig_meta_box_nonce' );
	$rcpig_button_text = get_post_meta( $post->ID, '_first_button', true );
	$rcpig_button_link = get_post_meta( $post->ID, '_first_button_link', true );
	$rcpig_button_tab = get_post_meta( $post->ID, '_first_button_tab', true ); ?>
	<div class='inside advance-input'>
		<label class="input-title"><?php _e( 'Portfolio Button Text', 'rcpig' ); ?></label>
		<input type="text" name="first_button" class="medium" id="first_button" value="<?php echo $rcpig_button_text; ?>" placeholder="<?php _e('e.g. Demo', 'rcpig'); ?>" />
		<label class="input-title"><?php _e('Portfolio Button Link', 'rcpig'); ?></label>
		<input type="text" name="first_button_link" class="medium" id="first_button_link" value="<?php echo $rcpig_button_link; ?>" placeholder="<?php _e('e.g. http://example.com', 'rcpig'); ?>" />
		<label class="input-title"><?php _e('Open Link in New Tab', 'rcpig'); ?>:</label>
		<select name="first_button_tab" id="first_button_tab" class="port-dir">
			<?php if($rcpig_button_tab == 'true') { ?>
			<option value="true" selected="selected"><?php _e('Yes', 'rcpig'); ?></option>
			<option value="false"><?php _e('No', 'rcpig'); ?></option>
			<?php } else { ?>
			<option value="true"><?php _e('Yes', 'rcpig'); ?></option>
			<option value="false" selected="selected"><?php _e('No', 'rcpig'); ?></option>
			<?php } ?>
		</select>
	</div><?php
}
function rcpig_save_meta_boxes_button( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['rcpig_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['rcpig_meta_box_nonce'], plugin_basename( __FILE__ ) ) ) { return; }
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	// store custom fields values
	if ( isset( $_REQUEST['first_button'] ) ) {
		update_post_meta( $post_id, '_first_button', sanitize_text_field( $_POST['first_button'] ) );
	}
	if ( isset( $_REQUEST['first_button_link'] ) ) {
		update_post_meta( $post_id, '_first_button_link', sanitize_text_field( $_POST['first_button_link'] ) );
	}
	if ( isset( $_REQUEST['first_button_tab'] ) ) {
		update_post_meta( $post_id, '_first_button_tab', sanitize_text_field( $_POST['first_button_tab'] ) );
	}
}

function rcpig_add_meta_box() {
	$rcpig_post_type_select = 'rcpig';
	add_meta_box(
		'rcpig_portfolio_meta',      // Unique ID
		esc_html__( 'Images For this Portfolio', 'rcpig' ),    // Title
		'rcpig_render_images_meta_box',   // Callback function
		$rcpig_post_type_select, // Admin page (or post type)
		'side',          // Context
		'default'         // Priority
	);
}
/* Prints the box content */
function rcpig_render_images_meta_box() {
	global $wpdb;
	global $post;
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'rcpig_noncename' );
	// The actual fields for data entry
	// Use get_post_meta to retrieve an existing value from the database and use the value for the form
	$value = get_post_meta($post->ID, '_multi_img_array', true);
	$temp = explode(",", $value);
	if ($temp) {
		foreach ( $temp as $t_val ) {
			$image_attributes = wp_get_attachment_image_src( $t_val , array(63,63) );
			echo '<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" data-id="'.$t_val.'">';
		}
	} else {
		$image_attributes = wp_get_attachment_image_src( $value , array(63,63) );
		echo '<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" data-id="'.$value.'">';
	}
	echo "<input type='hidden' name='image_upload_val' id='image_upload_val' value='".$value."' />";
	echo "<div class='rcpig_upload_media' id='rcpig_image_upload'>" . __('Upload Images', 'rcpig') . "</div>";
	echo "<span class='remove_image'>" . __('Click on image to remove it', 'rcpig') . "</span>";
}
/* When the post is saved, saves our custom data */
function rcpig_insert_postdata( $post_id ) {
	global $wpdb;
	// First we need to check if the current user is authorised to do this action. 
	if ( ! current_user_can( 'edit_page', $post_id ) )
		return;

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

	// Secondly we need to check if the user intended to change this value.
	if ( ! isset( $_POST['rcpig_noncename'] ) || ! wp_verify_nonce( $_POST['rcpig_noncename'], plugin_basename( __FILE__ ) ) )
		return;

	// Thirdly we can save the value to the database
	//if saving in a custom table, get post_ID
	$post_ID = $_POST['post_ID'];
	//sanitize user input
	// $mydata = sanitize_text_field( $_POST['myplugin_priceCode'] );
	$mydata = $_POST['image_upload_val'];
	// Do something with $mydata 
	// either using 
	if($mydata) {
		$cur_data = get_post_meta($post_ID, '_multi_img_array', true);
		if(!(empty($cur_data))) {
			// $cur_data .=",".$mydata;
			update_post_meta($post_ID, '_multi_img_array', $mydata);
		} else {
			add_post_meta($post_id, '_multi_img_array', $mydata, true);
		}
	}
}
?>