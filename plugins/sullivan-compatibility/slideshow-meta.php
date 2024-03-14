<?php

/* ====================================================================
|  ADD OUR META BOXES
|  Add our custom meta boxes to the slideshow post edit page
'---------------------------------------------------------------------- */


/* Add our meta box -------------------------------------------- */

if ( ! function_exists( 'sullivan_add_meta_box' ) ) {
	function sullivan_add_meta_box( $post ){
		add_meta_box( 'sullivan_slide_data_meta_box', __( 'Slide details', 'sullivan-compatibility' ), 'sullivan_build_meta_box', 'sullivan_slideshow', 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'sullivan_add_meta_box' );


/* Add the fields to the meta box -------------------------------------------- */

if ( ! function_exists( 'sullivan_build_meta_box' ) ) {
	function sullivan_build_meta_box( $post ){

		// Make sure the form request comes from WordPress
		wp_nonce_field( basename( __FILE__ ), 'sullivan_slide_data_meta_box_nonce' );

		// Retrieve our current values
		$slide_title = get_post_meta( $post->ID, 'sullivan_slide_title', true );
		$slide_subtitle = get_post_meta( $post->ID, 'sullivan_slide_subtitle', true );
		$slide_button_text = get_post_meta( $post->ID, 'sullivan_slide_button_text', true );
		$slide_button_url = get_post_meta( $post->ID, 'sullivan_slide_button_url', true );
		?>

		<div class='inside'>

			<p>
				<label for="sullivan_slide_title"><?php _e( 'Title', 'sullivan-compatibility' ); ?></label>
				<input type="text" name="sullivan_slide_title" id="sullivan_slide_title" class="widefat" value="<?php echo wp_kses_post( $slide_title ); ?>" /> 
			</p>

			<p>
				<label for="sullivan_slide_subtitle"><?php _e( 'Subtitle', 'sullivan-compatibility' ); ?></label>
				<textarea name="sullivan_slide_subtitle" id="sullivan_slide_subtitle" class="widefat" rows="5"><?php echo wp_kses_post( $slide_subtitle ); ?></textarea>
			</p>

			<p>
				<label for="sullivan_slide_button_text"><?php _e( 'Button text', 'sullivan-compatibility' ); ?></label>
				<input type="text" name="sullivan_slide_button_text" id="sullivan_slide_button_text" class="widefat" value="<?php echo wp_kses_post( $slide_button_text ); ?>" /> 
			</p>

			<p>
				<label for="sullivan_slide_button_url"><?php _e( 'Button URL', 'sullivan-compatibility' ); ?></label>
				<input type="url" name="sullivan_slide_button_url" id="sullivan_slide_button_url" class="widefat" value="<?php echo esc_url( $slide_button_url ); ?>" /> 
			</p>

		</div>

		<?php
	}

}


/* Save the fields from the meta box -------------------------------------------- */

if ( ! function_exists( 'sullivan_save_meta_box_data' ) ) {
	function sullivan_save_meta_box_data( $post_id ){

		// Verify meta box nonce
		if ( ! isset( $_POST['sullivan_slide_data_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['sullivan_slide_data_meta_box_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		// Return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}

		// Check the user's permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}

		// Prepare an array specifying the fields we're saving
		$fields = array(
			array(
				'name'	=> 'sullivan_slide_title',
				'type'	=> 'text'
			),
			array(
				'name'	=> 'sullivan_slide_subtitle',
				'type'	=> 'textarea'
			),
			array(
				'name'	=> 'sullivan_slide_button_text',
				'type'	=> 'text'
			),
			array(
				'name'	=> 'sullivan_slide_button_url',
				'type'	=> 'url'
			)
		);

		// Loop through the fields, sanitize the data and save it
		foreach( $fields as $field ) {

			if ( isset( $_REQUEST[$field['name']] ) ) {

				// Sanitize based on the type
				if ( $field['type'] == 'text' ) {
					$field_value = sanitize_text_field( $_POST[$field['name']] );
				} elseif( $field['type'] == 'textarea' ) {
					$field_value = sanitize_textarea_field( $_POST[$field['name']] );
				} elseif( $field['type'] == 'url' ) {
					$field_value = esc_url( $_POST[$field['name']] );
				}

				update_post_meta( $post_id, $field['name'], $field_value );
			}

		}

	}
}
add_action( 'save_post_sullivan_slideshow', 'sullivan_save_meta_box_data' );