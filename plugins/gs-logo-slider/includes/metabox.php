<?php
namespace GSLOGO;

if ( ! defined( 'ABSPATH' ) ) exit;

class Metabox {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'gs_logo_slider_add_meta_box' ] );
		add_action( 'save_post', [ $this, 'gs_logo_slider_save_meta_box_data' ] );
	}

	/**
	 * Adds a box to the main column on the Post and Page edit screens.
	 */
	public function gs_logo_slider_add_meta_box() {

		add_meta_box(
			'gs_logo_slider_sectionid',
			__( "Client's URL" , 'gslogo' ),
			[ $this, 'gs_logo_slider_meta_box_callback' ],
			'gs-logo-slider'
		);
	}

	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function gs_logo_slider_meta_box_callback( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'gs_logo_slider_meta_box', 'gs_logo_slider_meta_box_nonce' );

		/*
		* Use get_post_meta() to retrieve an existing value
		* from the database and use the value for the form.
		*/
		$value = get_post_meta( $post->ID, 'client_url', true );

		echo '<label for="gs_logo_slider_url_field">';
		_e( 'Enter Site URL', 'gslogo' );
		echo '</label> ';
		echo '<input type="text" id="gs_logo_slider_url_field" name="gs_logo_slider_url_field" value="' . esc_attr( $value ) . '" size="25" />';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function gs_logo_slider_save_meta_box_data( $post_id ) {

		/*
		* We need to verify this came from our screen and with proper authorization,
		* because the save_post action can be triggered at other times.
		*/

		// Check if our nonce is set.
		if ( ! isset( $_POST['gs_logo_slider_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['gs_logo_slider_meta_box_nonce'], 'gs_logo_slider_meta_box' ) ) {
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
		if ( ! isset( $_POST['gs_logo_slider_url_field'] ) ) {
			return;
		}

		// Sanitize user input.
		$gs_logo = sanitize_url( $_POST['gs_logo_slider_url_field'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'client_url', $gs_logo );
	}

}
