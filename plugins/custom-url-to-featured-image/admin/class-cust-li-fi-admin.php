<?php
/**
 * Admin option for post pages
 *
 * @package Add Featured Image Custom Link
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	/**
	 * Add Custom Fields in meta box
	 *
	 * Handles to add custom field
	 * with custom html in meta box
	 *
	 * @package Custom Url to Featured Image
	 * @since 1.1.0
	 * @param WP_Post $post The post object.
	 */
function cust_li_fi_meta_box( $post ) {

	$screens = get_post_types();

	foreach ( $screens as $screen ) {
		add_meta_box(
			'custom_url_image',
			__( 'Custom Url to Featured Image', 'custom-url-to-featured-image' ),
			'cust_li_fi_meta_box_callback', // $callback
			$screen,
			'side', // $context
			'low'// $priority
		);
	}
}
	/**
	 * Add uro Fields in meta box
	 *
	 * Handles to add custom field
	 * with custom html in meta box
	 *
	 * @package Custom Url to Featured Image
	 * @since 1.1.0
	 * @param WP_Post $post The post object.
	 */
function cust_li_fi_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'custom_url_image_nonce', 'custom_url_image_nonce' );

	$cust_li_fi_value = get_post_meta( $post->ID, '_custom_url_image', true );

	echo '<input type="url" name="custom_url_image" id="custom_url_image"
	placeholder="https://example.com" size="35" value="' . esc_attr( $cust_li_fi_value ) . '">';
}
	/**
	 * Save Custom Meta
	 *
	 * Handles to save custom meta
	 *
	 * @package Custom Url to Featured Image
	 * @since 1.1.0
	 * @param int $post_id The ID of the post.
	 */
function save_cust_li_fi_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['custom_url_image_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['custom_url_image_nonce'], 'custom_url_image_nonce' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {

			return;
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['custom_url_image'] ) ) {
		return;
	}
	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['custom_url_image'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_custom_url_image', $my_data );
}

/**
 * Custom Thumbnail Fallback with Custom URL.
 *
 * Modifies the thumbnail HTML to include a custom URL link.
 *
 * @param string       $html             The post thumbnail HTML.
 * @param int          $post_id          The post ID.
 * @param int          $post_thumbnail_id The post thumbnail ID.
 * @param string|array $size             The post thumbnail size.
 * @param string|array $attr             Query string or array of attributes.
 *
 * @return string Modified post thumbnail HTML.
 */
function cust_li_fi_thumbnail_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	$clink = get_post_meta( get_the_ID(), '_custom_url_image', true );

	if ( empty( $html ) || ! empty( $clink ) && is_singular() ) {

			$html = '<a href="' . $clink . '" title="' . esc_attr( get_the_title( $post_id ) ) . '" 
			target="_blank" class="ex-link">' . $html . '</a>';
	}
		return $html;
}
	// add action to add meta box for custom url in single post | page.
	add_action( 'add_meta_boxes', 'cust_li_fi_meta_box' );
	// Update meta box for custom url in single post | page in the database.
	add_action( 'save_post', 'save_cust_li_fi_meta_box_data' );
	// add custom link in href to featured image in front of page | post.
	add_action( 'post_thumbnail_html', 'cust_li_fi_thumbnail_fallback', 20, 5 );
