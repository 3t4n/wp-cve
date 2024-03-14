<?php

function testimonial_author_s_info_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function testimonial_author_s_info_add_meta_box() {
	add_meta_box(
		'testimonial_author_s_info-testimonial-author-s-info',
		__( 'Testimonial  Author\'s Info', 'testimonial_author_s_info' ),
		'testimonial_author_s_info_html',
		'post',
		'normal',
		'default'
	);
	add_meta_box(
		'testimonial_author_s_info-testimonial-author-s-info',
		__( 'Testimonial  Author\'s Info', 'testimonial_author_s_info' ),
		'testimonial_author_s_info_html',
		'tctestimonial',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'testimonial_author_s_info_add_meta_box' );

function testimonial_author_s_info_html( $post) {
	wp_nonce_field( '_testimonial_author_s_info_nonce', 'testimonial_author_s_info_nonce' ); ?>

	<p>
		<label for="testimonial_author_s_info_company_name"><?php _e( 'Company Name', 'testimonial_author_s_info' ); ?></label><br>
		<input type="text" name="testimonial_author_s_info_company_name" id="testimonial_author_s_info_company_name" value="<?php echo testimonial_author_s_info_get_meta( 'testimonial_author_s_info_company_name' ); ?>">
	</p>	<p>
		<label for="testimonial_author_s_info_designation"><?php _e( 'Designation', 'testimonial_author_s_info' ); ?></label><br>
		<input type="text" name="testimonial_author_s_info_designation" id="testimonial_author_s_info_designation" value="<?php echo testimonial_author_s_info_get_meta( 'testimonial_author_s_info_designation' ); ?>">
	</p>	<p>
		<label for="testimonial_author_s_info_location"><?php _e( 'Location', 'testimonial_author_s_info' ); ?></label><br>
		<input type="text" name="testimonial_author_s_info_location" id="testimonial_author_s_info_location" value="<?php echo testimonial_author_s_info_get_meta( 'testimonial_author_s_info_location' ); ?>">
	</p><?php
}

function testimonial_author_s_info_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['testimonial_author_s_info_nonce'] ) || ! wp_verify_nonce( $_POST['testimonial_author_s_info_nonce'], '_testimonial_author_s_info_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['testimonial_author_s_info_company_name'] ) )
		update_post_meta( $post_id, 'testimonial_author_s_info_company_name', esc_attr( $_POST['testimonial_author_s_info_company_name'] ) );
	if ( isset( $_POST['testimonial_author_s_info_designation'] ) )
		update_post_meta( $post_id, 'testimonial_author_s_info_designation', esc_attr( $_POST['testimonial_author_s_info_designation'] ) );
	if ( isset( $_POST['testimonial_author_s_info_location'] ) )
		update_post_meta( $post_id, 'testimonial_author_s_info_location', esc_attr( $_POST['testimonial_author_s_info_location'] ) );
}
add_action( 'save_post', 'testimonial_author_s_info_save' );

/*
	Usage: testimonial_author_s_info_get_meta( 'testimonial_author_s_info_company_name' )
	Usage: testimonial_author_s_info_get_meta( 'testimonial_author_s_info_designation' )
	Usage: testimonial_author_s_info_get_meta( 'testimonial_author_s_info_location' )
*/
