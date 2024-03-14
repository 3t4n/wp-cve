<?php


if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function glg_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'glgswitcher',
			__( GLG_ITEM_NAME, 'gallery-lightbox-slider' ),
			'glg_metabox_markup',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'glg_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function glg_metabox_markup( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'glg_save_meta_box_data', 'glg_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'glg_meta_options', true );

	echo '<label for="glg_activate_lightbox">';
	_e( 'Use Lightbox?', 'gallery-lightbox-slider' );
	echo '</label>'; ?>
	        <select name="glg_activate_lightbox" id="glg_activate_lightbox">
            <option value="yes" <?php selected( esc_attr( $value ), 'yes' ); ?>>Yes</option>
            <option value="no" <?php selected( esc_attr( $value ), 'no' ); ?>>No</option>
     <?php  echo '</select><p style="font-size: 13px;font-style: italic;color:#9d9d9d;font-weight: normal;margin: 5px 0 0 0;">'.__( 'If set to YES, all galleries on this page/post will be opened in the lightbox', 'gallery-lightbox-slider' ).'</p>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function glg_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['glg_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['glg_meta_box_nonce'], 'glg_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['glg_activate_lightbox'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['glg_activate_lightbox'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'glg_meta_options', $my_data );
}

add_action( 'save_post', 'glg_save_meta_box_data' );