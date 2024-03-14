<?php

/**
 * Calls the class on the post edit screen.
 */
function regUserOnly_call_Class() {
    new regUserOnly();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'regUserOnly_call_Class' );
    add_action( 'load-post-new.php', 'regUserOnly_call_Class' );
}

/** 
 * The Class.
 */
class regUserOnly {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'regUserOnly_meta_box'
			,__( 'Visibility', 'regUserOnly' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'side'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['regUserOnly_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['regUserOnly_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'regUserOnly_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['regUserOnly'] );

		// Update the meta field.
		update_post_meta( $post_id, '_regUserOnly', $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'regUserOnly_inner_custom_box', 'regUserOnly_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_regUserOnly', true );
?>		<input type="radio" id="regUserOnly-yes" name="regUserOnly" value="1" <?php if ($value == "1") {echo " checked ";} ?> /> <label for="regUserOnly-yes"><?php _e('Registered Users','regUserOnly'); ?></label><br>
		<input type="radio" id="regUserOnly-no" name="regUserOnly" value="0" <?php if (empty($value)) {echo " checked ";} ?> /> <label for="regUserOnly-no"><?php _e('ALL','regUserOnly'); ?></label>
	<?php
	}
}
?>