<?php
/**
 * Handles all admin ajax interactions for the Envira Gallery plugin.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team
 */

add_action( 'wp_ajax_envira_gallery_change_type', 'envira_gallery_ajax_change_type' );
/**
 * Changes the type of gallery to the user selection.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_change_type() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-change-type', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$post    = get_post( $post_id );
	$type    = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null;

	// Retrieve the data for the type selected.
	ob_start();
	$instance = Envira_Gallery_Metaboxes::get_instance();
	$instance->images_display( $type, $post );
	$html = ob_get_clean();

	// Send back the response.
	echo wp_json_encode(
		[
			'type' => $type,
			'html' => $html,
		]
	);
	die;
}

add_action( 'wp_ajax_envira_gallery_set_user_setting', 'envira_gallery_ajax_set_user_setting' );
/**
 * Stores a user setting for the logged in WordPress User
 *
 * @since 1.5.0
 */
function envira_gallery_ajax_set_user_setting() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-set-user-setting', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

	// Set user setting.
	set_user_setting( $name, $value );

	// Send back the response.
	wp_send_json_success();
	die();
}

add_action( 'wp_ajax_envira_gallery_load_image', 'envira_gallery_ajax_load_image' );
/**
 * Loads an image into a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_load_image() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-load-image', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$id      = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : null;
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	// Set post meta to show that this image is attached to one or more Envira galleries.
	$has_gallery = get_post_meta( $id, '_eg_has_gallery', true );
	if ( empty( $has_gallery ) ) {
		$has_gallery = [];
	}

	$has_gallery[] = $post_id;
	update_post_meta( $id, '_eg_has_gallery', $has_gallery );

	// Set post meta to show that this image is attached to a gallery on this page.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	$in_gallery[] = $id;
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Set data and update the meta information.
	$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $id );
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before building out the item.
	do_action( 'envira_gallery_ajax_load_image', $id, $post_id );

	// Build out the individual HTML output for the gallery image that has just been uploaded.
	$html = Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $gallery_data['gallery'][ $id ], $post_id );

	// Allow addons to filter the HTML output.
	$html = apply_filters( 'envira_gallery_ajax_get_gallery_item_html', $html, $gallery_data, $id, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( $html );
	die;
}

add_action( 'wp_ajax_envira_gallery_insert_images', 'envira_gallery_ajax_insert_images' );
/**
 * Inserts one or more images from the Media Library into a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_insert_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-insert-images', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$images = [];

	if ( isset( $_POST['images'] ) ) {
		$images = json_decode( sanitize_text_field( wp_unslash( $_POST['images'] ) ), true );
	}

	// Grab and update any gallery data if necessary.
	$in_gallery = get_post_meta( $post_id, '_eg_in_gallery', true );
	if ( empty( $in_gallery ) ) {
		$in_gallery = [];
	}

	// Set data and order of image in gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) ) {
		$gallery_data = [];
	}

	// If no gallery ID has been set, set it now.
	if ( empty( $gallery_data['id'] ) ) {
		$gallery_data['id'] = $post_id;
	}

	// Loop through the images and add them to the gallery.
	foreach ( (array) $images as $i => $image ) {

		// If the image is already in the gallery, lets skip it since we don't want to override the image metadata settings.
		if ( in_array( $image['id'], $in_gallery, true ) ) {
			continue;
		}

		// Update the attachment image post meta first.
		$has_gallery = get_post_meta( $image['id'], '_eg_has_gallery', true );
		if ( empty( $has_gallery ) ) {
			$has_gallery = [];
		}

		$has_gallery[] = $post_id;
		update_post_meta( $image['id'], '_eg_has_gallery', $has_gallery );

		// Now add the image to the gallery for this particular post.
		$in_gallery[] = $image['id'];
		$gallery_data = envira_gallery_ajax_prepare_gallery_data( $gallery_data, $image['id'], $image );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Run hook before finishing.
	do_action( 'envira_gallery_ajax_insert_images', $images, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Return a HTML string comprising of all gallery images, so the UI can be updated.
	$html = '';
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$html .= Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $data, $post_id );
	}

	// Output JSON and exit.
	echo wp_json_encode( [ 'success' => $html ] );
	die;
}

add_action( 'wp_ajax_envira_gallery_sort_images', 'envira_gallery_ajax_sort_images' );
/**
 * Sorts images based on user-dragged position in the gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_sort_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-sort', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$order        = isset( $_POST['order'] ) ? explode( ',', wp_unslash( $_POST['order'] ) ) : ''; // @codingStandardsIgnoreLine
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Copy the gallery config, removing the images
	// Stops config from getting lost when sorting + not clicking Publish/Update.
	$new_order = $gallery_data;
	unset( $new_order['gallery'] );
	$new_order['gallery'] = [];

	// Loop through the order and generate a new array based on order received.
	foreach ( $order as $id ) {
		$new_order['gallery'][ $id ] = $gallery_data['gallery'][ $id ];
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $new_order );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_gallery_remove_image', 'envira_gallery_ajax_remove_image' );
/**
 * Removes an image from a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_remove_image() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-remove-image', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$post_id      = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$attach_id    = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );
	$has_gallery  = get_post_meta( $attach_id, '_eg_has_gallery', true );

	// Unset the image from the gallery, in_gallery and has_gallery checkers.
	unset( $gallery_data['gallery'][ $attach_id ] );
	$key = array_search( $attach_id, (array) $in_gallery, true );
	if ( false !== $key ) {
		unset( $in_gallery[ $key ] );
	}
	$key = array_search( $post_id, (array) $has_gallery, true );

	if ( false !== $key ) {
		unset( $has_gallery[ $key ] );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );
	update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_image', $attach_id, $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_gallery_remove_images', 'envira_gallery_ajax_remove_images' );
/**
 * Removes multiple images from a gallery.
 *
 * @since 1.3.2.4
 */
function envira_gallery_ajax_remove_images() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-remove-image', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$attach_ids   = isset( $_POST['attachment_ids'] ) ? array_map( 'absint', wp_unslash( (array) $_POST['attachment_ids'] ) ) : [];
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	$in_gallery   = get_post_meta( $post_id, '_eg_in_gallery', true );

	foreach ( (array) $attach_ids as $attach_id ) {
		$has_gallery = get_post_meta( $attach_id, '_eg_has_gallery', true );

		// Unset the image from the gallery, in_gallery and has_gallery checkers.
		unset( $gallery_data['gallery'][ $attach_id ] );

		$key = array_search( $attach_id, (array) $in_gallery, true );
		if ( false !== $key ) {
			unset( $in_gallery[ $key ] );
		}

		$key = array_search( $post_id, (array) $has_gallery, true );
		if ( false !== $key ) {
			unset( $has_gallery[ $key ] );
		}

		// Update the attachment data.
		update_post_meta( $attach_id, '_eg_has_gallery', $has_gallery );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );
	update_post_meta( $post_id, '_eg_in_gallery', $in_gallery );

	// Run hook before finishing the reponse.
	do_action( 'envira_gallery_ajax_remove_images', $post_id );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_gallery_save_meta', 'envira_gallery_ajax_save_meta' );
/**
 * Saves the metadata for an image in a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_save_meta() {

	// Run a security check first.
	check_ajax_referer( 'envira-gallery-save-meta', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$attach_id    = isset( $_POST['attach_id'] ) ? absint( wp_unslash( $_POST['attach_id'] ) ) : null;
	$meta         = isset( $_POST['meta'] ) ? array_map( 'sanitize_text_field', wp_unslash( (array) $_POST['meta'] ) ) : [];
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// Prevent invalid data from being saved.
	if ( ! $post_id || ! $attach_id || empty( $meta['src'] ) ) {
		wp_send_json_error( 'Unable to save' );
	}

	$wp_kses_allowed_html = [
		'a'      => [
			'href'                => [],
			'target'              => [],
			'class'               => [],
			'title'               => [],
			'data-status'         => [],
			'data-envira-tooltip' => [],
			'data-id'             => [],
		],
		'br'     => [],
		'img'    => [
			'src'   => [],
			'class' => [],
			'alt'   => [],
		],
		'div'    => [
			'class' => [],
		],
		'li'     => [
			'id'                              => [],
			'class'                           => [],
			'data-envira-gallery-image'       => [],
			'data-envira-gallery-image-model' => [],
		],
		'em'     => [],
		'span'   => [
			'class' => [],
		],
		'strong' => [],
	];

	if ( isset( $meta['title'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['title'] = trim( wp_kses( $meta['title'], $wp_kses_allowed_html ) );
	}

	if ( isset( $meta['alt'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
	}

	if ( isset( $meta['link'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['link'] = esc_url( $meta['link'] );
	}

	if ( isset( $meta['link_new_window'] ) ) {
		$gallery_data['gallery'][ $attach_id ]['link_new_window'] = trim( $meta['link_new_window'] );
	}

	// Allow filtering of meta before saving.
	$gallery_data = apply_filters( 'envira_gallery_ajax_save_meta', $gallery_data, $meta, $attach_id, $post_id );

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success();
	die;
}

add_action( 'wp_ajax_envira_gallery_save_bulk_meta', 'envira_gallery_ajax_save_bulk_meta' );
/**
 * Saves the metadata for multiple images in a gallery (bulk edit).
 *
 * @since 1.4.2.2
 */
function envira_gallery_ajax_save_bulk_meta() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-save-meta', 'nonce' );

	// Get the Envira Gallery ID.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;

	if ( null === $post_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$image_ids = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : array(); // @codingStandardsIgnoreLine - Array
	$meta      = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : array(); // @codingStandardsIgnoreLine - Array

	// Check the required variables exist.
	if ( empty( $post_id ) ) {
		wp_send_json_error();
	}
	if ( empty( $image_ids ) || ! is_array( $image_ids ) ) {
		wp_send_json_error();
	}
	if ( empty( $meta ) || ! is_array( $meta ) ) {
		wp_send_json_error();
	}

	// Get gallery.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );
	if ( empty( $gallery_data ) || ! is_array( $gallery_data ) ) {
		wp_send_json_error();
	}

	// Iterate through gallery images, updating the metadata.
	foreach ( $image_ids as $image_id ) {
		// If the image isn't in the gallery, something went wrong - so skip this image.
		if ( ! isset( $gallery_data['gallery'][ $image_id ] ) ) {
			continue;
		}

		// Update image metadata.
		if ( isset( $meta['title'] ) && ! empty( $meta['title'] ) ) {
			$gallery_data['gallery'][ $image_id ]['title'] = trim( $meta['title'] );
		}

		if ( isset( $meta['alt'] ) && ! empty( $meta['alt'] ) ) {
			$gallery_data['gallery'][ $image_id ]['alt'] = trim( esc_html( $meta['alt'] ) );
		}

		if ( isset( $meta['link'] ) && ! empty( $meta['link'] ) ) {
			$gallery_data['gallery'][ $image_id ]['link'] = esc_url( $meta['link'] );
		}

		if ( isset( $meta['link_new_window'] ) && ! empty( $meta['link_new_window'] ) ) {
			$gallery_data['gallery'][ $image_id ]['link_new_window'] = trim( $meta['link_new_window'] );
		}

		if ( isset( $meta['caption'] ) && ! empty( $meta['caption'] ) ) {
			$gallery_data['gallery'][ $image_id ]['caption'] = trim( $meta['caption'] );
		}

		// Allow filtering of meta before saving.
		$gallery_data = apply_filters( 'envira_gallery_ajax_save_bulk_meta', $gallery_data, $meta, $image_id, $post_id );
	}

	// Update the gallery data.
	update_post_meta( $post_id, '_eg_gallery_data', $gallery_data );

	// Flush the gallery cache.
	Envira_Gallery_Common::get_instance()->flush_gallery_caches( $post_id );

	// Done.
	wp_send_json_success();
	die;
}

add_action( 'wp_ajax_envira_gallery_refresh', 'envira_gallery_ajax_refresh' );
/**
 * Refreshes the DOM view for a gallery.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_refresh() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-refresh', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables.
	$post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null;
	$gallery = '';

	// Grab all gallery data.
	$gallery_data = get_post_meta( $post_id, '_eg_gallery_data', true );

	// If there are no gallery items, don't do anything.
	if ( empty( $gallery_data ) || empty( $gallery_data['gallery'] ) ) {
		echo wp_json_encode( [ 'error' => true ] );
		die;
	}

	// Loop through the data and build out the gallery view.
	foreach ( (array) $gallery_data['gallery'] as $id => $data ) {
		$gallery .= Envira_Gallery_Metaboxes::get_instance()->get_gallery_item( $id, $data, $post_id );
	}

	echo wp_json_encode( [ 'success' => $gallery ] );
	die;
}

add_action( 'wp_ajax_envira_gallery_load_gallery_data', 'envira_gallery_ajax_load_gallery_data' );

/**
 * Retrieves and return gallery data for the specified ID.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_load_gallery_data() {

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Prepare variables and grab the gallery data.
	$gallery_id   = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : null; // @codingStandardsIgnoreLine
	$gallery_data = get_post_meta( $gallery_id, '_eg_gallery_data', true );

	// Send back the gallery data.
	echo wp_json_encode( $gallery_data );
	die;
}

add_action( 'wp_ajax_envira_gallery_install_addon', 'envira_gallery_ajax_install_addon' );
/**
 * Installs an Envira addon.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_install_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-install', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = esc_url_raw( wp_unslash( $_POST['plugin'] ) );
		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once plugin_dir_path( Envira_Gallery::get_instance()->file ) . 'includes/admin/skin.php';

		// Create the plugin upgrader with our custom skin.
		$skin      = new Envira_Gallery_Skin();
		$installer = new Plugin_Upgrader( $skin );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo wp_json_encode( [ 'plugin' => $plugin_basename ] );
			die;
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_gallery_activate_addon', 'envira_gallery_ajax_activate_addon' );
/**
 * Activates an Envira addon.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_activate_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-activate', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to activate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Activate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$activate = activate_plugin( wp_unslash( $_POST['plugin'] ) );  // @codingStandardsIgnoreLine

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
			die;
		}
	}

	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_gallery_deactivate_addon', 'envira_gallery_ajax_deactivate_addon' );
/**
 * Deactivates an Envira addon.
 *
 * @since 1.0.0
 */
function envira_gallery_ajax_deactivate_addon() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-deactivate', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to deactivate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$deactivate = deactivate_plugins( wp_unslash( $_POST['plugin'] ) );  // @codingStandardsIgnoreLine
	}

	echo wp_json_encode( true );
	die;
}
/**
 * Helper function to prepare the metadata for an image in a gallery.
 *
 * @since 1.0.0
 *
 * @param array $gallery_data   Array of data for the gallery.
 * @param int   $id             The attachment ID to prepare data for.
 * @param array $image          Attachment image. Populated if inserting from the Media Library.
 * @return array $gallery_data Amended gallery data with updated image metadata.
 */
function envira_gallery_ajax_prepare_gallery_data( $gallery_data, $id, $image = false ) {
	// Get attachment.
	$attachment   = get_post( $id );
	$url          = wp_get_attachment_image_src( $id, 'full' );
	$url_from_src = isset( $url[0] ) ? esc_url( $url[0] ) : '';

	// Depending on whether we're inserting from the Media Library or not, prepare the image array.
	if ( ! $image ) {
		$alt_text  = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$new_image = [
			'status'  => 'active',
			'src'     => $url_from_src,
			'title'   => get_the_title( $id ),
			'link'    => $url_from_src,
			'alt'     => ! empty( $alt_text ) ? $alt_text : '',
			'caption' => ! empty( $attachment->post_excerpt ) ? $attachment->post_excerpt : '',
			'thumb'   => '',
		];
	} else {
		$src       = isset( $image['src'] ) ? $image['src'] : ( ! empty( $image['url'] ) ? $image['url'] : $url_from_src );
		$link      = isset( $image['link'] ) && wp_http_validate_url( $image['link'] ) ? $image['link'] : $src;
		$new_image = [
			'status'  => 'active',
			'src'     => $src,
			'title'   => $image['title'],
			'link'    => $link,
			'alt'     => $image['alt'],
			'caption' => $image['caption'],
			'thumb'   => '',
		];
	}

	// Allow Addons to possibly add metadata now.
	$image = apply_filters( 'envira_gallery_ajax_prepare_gallery_data_item', $new_image, $image, $id, $gallery_data );

	// If gallery data is not an array (i.e. we have no images), just add the image to the array.
	if ( ! isset( $gallery_data['gallery'] ) || ! is_array( $gallery_data['gallery'] ) ) {
		$gallery_data['gallery']        = [];
		$gallery_data['gallery'][ $id ] = $image;
	} else {

		// Add image, this will default to the end of the array.
		$gallery_data['gallery'][ $id ] = $image;

	}

	// Filter and return.
	$gallery_data = apply_filters( 'envira_gallery_ajax_item_data', $gallery_data, $attachment, $id, $image );

	return $gallery_data;
}

/**
 * Called whenever a notice is dismissed in Envira Gallery or its Addons.
 *
 * Updates a key's value in the options table to mark the notice as dismissed,
 * preventing it from displaying again
 *
 * @since 1.3.5
 */
function envira_gallery_ajax_dismiss_notice() {

	// Run a security check first.
	check_admin_referer( 'envira-gallery-dismiss-notice', 'nonce' );

	if ( ! current_user_can( 'edit_dashboard' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to dismiss notices.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the notice.
	if ( isset( $_POST['notice'] ) ) {
		// Init the notice class and mark notice as deactivated.
		$notice = Envira_Gallery_Notice_Admin::get_instance();
		$notice->dismiss( sanitize_text_field( wp_unslash( $_POST['notice'] ) ) );

		echo wp_json_encode( true );
		die;
	}

	// If here, an error occured.
	echo wp_json_encode( false );
	die;
}
add_action( 'wp_ajax_envira_gallery_ajax_dismiss_notice', 'envira_gallery_ajax_dismiss_notice' );


add_action( 'wp_ajax_envira_gallery_ajax_dismiss_topbar', 'envira_gallery_ajax_dismiss_topbar' );

/**
 * Dismiss top bar
 *
 * @return void
 */
function envira_gallery_ajax_dismiss_topbar() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-dismiss-topbar', 'nonce' );

	if ( ! current_user_can( 'edit_dashboard' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to dismiss notices.', 'envira-gallery-lite' ) ] );
	}

	update_option( 'envira_pro_upgrade_header_dismissed', true );

	wp_send_json_success();
}

add_action( 'wp_ajax_envira_gallery_get_attachment_links', 'envira_gallery_get_attachment_links' );

/**
 * Returns the media link (direct image URL) for the given attachment ID
 *
 * @since 1.4.1.4
 */
function envira_gallery_get_attachment_links() {

	// Check nonce.
	check_ajax_referer( 'envira-gallery-save-meta', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}


	// Get required inputs.
	$attachment_id = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : null;

	// Return the attachment's links.
	wp_send_json_success(
		[
			'media_link'      => wp_get_attachment_url( $attachment_id ),
			'attachment_page' => get_attachment_link( $attachment_id ),
		]
	);
}

add_action( 'wp_ajax_envira_gallery_editor_get_galleries', 'envira_gallery_editor_get_galleries' );

/**
 * Returns Galleries, with an optional search term.
 *
 * @since 1.5.0
 */
function envira_gallery_editor_get_galleries() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-editor-get-galleries', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}


	// Get POSTed fields.
	$search       = isset( $_POST['search'] ) ? (bool) wp_unslash( $_POST['search'] ) : false; // @codingStandardsIgnoreLine
	$search_terms = isset( $_POST['search_terms'] ) ? sanitize_text_field( wp_unslash( $_POST['search_terms'] ) ) : ''; // @codingStandardsIgnoreLine
	$prepend_ids  = isset( $_POST['prepend_ids'] ) ? stripslashes_deep( wp_unslash( $_POST['prepend_ids'] ) ) : array(); // @codingStandardsIgnoreLine
	$results      = [];

	// Get galleries.
	$instance  = Envira_Gallery_Lite::get_instance();
	$galleries = $instance->get_galleries( false, true, ( $search ? $search_terms : '' ) );

	// Build array of just the data we need.
	foreach ( (array) $galleries as $gallery ) {
		// Get the thumbnail of the first image.
		if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
			// Get the first image.
			reset( $gallery['gallery'] );
			$key       = key( $gallery['gallery'] );
			$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
		}

		if ( ! empty( $gallery['config']['title'] ) ) {
			$gallery_title = $gallery['config']['title'];
		} else {
			$gallery_title = false;
		}

		// Check to make sure variables are there.
		$gallery_id          = false;
		$gallery_config_slug = false;

		if ( isset( $gallery['id'] ) ) {
			$gallery_id = $gallery['id'];
		}
		if ( isset( $gallery['config']['slug'] ) ) {
			$gallery_config_slug = $gallery['config']['slug'];
		}

		// Add gallery to results.
		$results[] = [
			'id'        => $gallery_id,
			'slug'      => $gallery_config_slug,
			'title'     => $gallery_title,
			'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
			'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
		];
	}

	// If any prepended Gallery IDs were specified, get them now.
	// These will typically be a Defaults Gallery, which wouldn't be included in the above get_galleries() call.
	if ( is_array( $prepend_ids ) && count( $prepend_ids ) > 0 ) {
		$prepend_results = [];

		// Get each Gallery.
		foreach ( $prepend_ids as $gallery_id ) {
			// Get gallery.
			$gallery = get_post_meta( $gallery_id, '_eg_gallery_data', true );

			// Get gallery first image.
			if ( isset( $gallery['gallery'] ) && ! empty( $gallery['gallery'] ) ) {
				// Get the first image.
				reset( $gallery['gallery'] );
				$key       = key( $gallery['gallery'] );
				$thumbnail = wp_get_attachment_image_src( $key, 'thumbnail' );
			}

			// Add gallery to results.
			$prepend_results[] = [
				'id'        => $gallery['id'],
				'slug'      => $gallery['config']['slug'],
				'title'     => $gallery['config']['title'],
				'thumbnail' => ( ( isset( $thumbnail ) && is_array( $thumbnail ) ) ? $thumbnail[0] : '' ),
				'action'    => 'gallery', // Tells the editor modal whether this is a Gallery or Album for the shortcode output.
			];
		}

		// Add to results.
		if ( is_array( $prepend_results ) && count( $prepend_results ) > 0 ) {
			$results = array_merge( $prepend_results, $results );
		}
	}

	// Return galleries.
	wp_send_json_success( $results );
}

add_action( 'wp_ajax_envira_gallery_move_media', 'envira_gallery_move_media' );

/**
 * Moves media (images) from one Gallery to another
 *
 * @since 1.5.0.3
 */
function envira_gallery_move_media() {

	// Check nonce.
	check_admin_referer( 'envira-gallery-move-media', 'nonce' );

	// Get the Envira Gallery ID.
	$from_gallery_id = isset( $_POST['from_gallery_id'] ) ? absint( wp_unslash( $_POST['from_gallery_id'] ) ) : null;

	if ( null === $from_gallery_id ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Invalid Post ID.', 'envira-gallery-lite' ) ] );
	}

	if ( ! current_user_can( 'edit_posts', $from_gallery_id ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to edit galleries.', 'envira-gallery-lite' ) ] );
	}

	// Get POSTed fields.
	$to_gallery_id   = isset( $_POST['to_gallery_id'] ) ? absint( $_POST['to_gallery_id'] ) : null;
	$image_ids       = isset( $_POST['image_ids'] ) ? wp_unslash( $_POST['image_ids'] ) : array(); // @codingStandardsIgnoreLine

	if ( ! $from_gallery_id ) {
		wp_send_json_error( __( 'The From Gallery ID has not been specified.', 'envira-gallery-lite' ) );
	}
	if ( ! $to_gallery_id ) {
		wp_send_json_error( __( 'The From Gallery ID has not been specified.', 'envira-gallery-lite' ) );
	}
	if ( count( $image_ids ) === 0 ) {
		wp_send_json_error( __( 'No images were selected to be moved between Galleries.', 'envira-gallery-lite' ) );
	}

	// Get from and to Galleries.
	$from_gallery = Envira_Gallery::get_instance()->get_gallery( $from_gallery_id );
	$to_gallery   = Envira_Gallery::get_instance()->get_gallery( $to_gallery_id );

	// Iterate through each image ID, adding the image to $to_gallery, then removing from $from_gallery.
	foreach ( $image_ids as $image_id ) {
		// Check the image exists in $from_gallery.
		// If not, skip this image.
		if ( ! isset( $from_gallery['gallery'][ $image_id ] ) ) {
			continue;
		}

		// Copy the image to $to_gallery.
		$to_gallery['gallery'][ $image_id ] = $from_gallery['gallery'][ $image_id ];

		// Remove the image from $from_gallery.
		unset( $from_gallery['gallery'][ $image_id ] );
	}

	// Save both Galleries.
	update_post_meta( $from_gallery_id, '_eg_gallery_data', $from_gallery );
	update_post_meta( $to_gallery_id, '_eg_gallery_data', $to_gallery );

	// Return success.
	wp_send_json_success();
}
add_action( 'wp_ajax_envira_activate_partner', 'envira_activate_partner' );

/**
 * Helper method to activate partner
 *
 * @since 1.90
 *
 * @return void
 */
function envira_activate_partner() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-activate-partner', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to activate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Activate the addon.
	if ( isset( $_POST['basename'] ) ) {
		$activate = activate_plugin( sanitize_text_field( wp_unslash( $_POST['basename'] ) ) );

		if ( is_wp_error( $activate ) ) {
			echo wp_json_encode( [ 'error' => $activate->get_error_message() ] );
			die;
		}
	}

	echo wp_json_encode( true );
	die;
}

add_action( 'wp_ajax_envira_deactivate_partner', 'envira_deactivate_partner' );

/**
 * Helper method to deactivate partner
 *
 * @since 1.90
 *
 * @return void
 */
function envira_deactivate_partner() {
	// Run a security check first.
	check_admin_referer( 'envira-gallery-deactivate-partner', 'nonce' );

	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to deactivate plugins.', 'envira-gallery-lite' ) ] );
	}

	// Deactivate the addon.
	if ( isset( $_POST['basename'] ) ) {
		$deactivate = deactivate_plugins( wp_unslash( $_POST['basename'] ) );  // @codingStandardsIgnoreLine
	}

	echo wp_json_encode( true );
	die;
}
add_action( 'wp_ajax_envira_install_partner', 'envira_install_partner' );

/**
 * Helper method to install partner
 *
 * @since 1.90
 *
 * @return void
 */
function envira_install_partner() {

	check_admin_referer( 'envira-gallery-install-partner', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	// Install the addon.
	if ( isset( $_POST['download_url'] ) ) {

		$download_url = esc_url_raw( wp_unslash( $_POST['download_url'] ) );
		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		$method = '';
		$url    = esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-lite-about-us' ) );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		$creds = request_filesystem_credentials( $url, $method, false, false, null );
		if ( false === $creds ) {
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo wp_json_encode( [ 'form' => $form ] );
			die;
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once plugin_dir_path( ENVIRA_LITE_FILE ) . 'includes/global/Installer_Skin.php';

		// Create the plugin upgrader with our custom skin.
		$skin      = new Envira_Lite_Installer_Skin();
		$installer = new Plugin_Upgrader( $skin );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();

			$active = activate_plugin( $plugin_basename, false, false, true );

			wp_send_json_success( [ 'plugin' => $plugin_basename ] );

			die();
		}
	}

	// Send back a response.
	echo wp_json_encode( true );
	die;
}


add_action( 'wp_ajax_envira_connect', 'envira_connect' );

/**
 * Connects To Envira Gallery Pro
 *
 * @since 1.8.7
 *
 * @return void
 */
function envira_connect() {
	// Run a security check.
	check_ajax_referer( 'envira_gallery_connect' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'You are not allowed to install plugins.', 'envira-gallery-lite' ) ] );
	}

	$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	if ( empty( $key ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'Please enter your license key to connect.', 'envira-gallery-lite' ) ] );
	}

	$valid_key = envira_api_remote_request( 'verify-key', [ 'tgm-lite-key' => $key ] );

	// If it returns false, send back a generic error message and return.
	if ( ! $valid_key ) {
		wp_send_json_error( [ 'message' => esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'envira-gallery-lite' ) ] );
	}

	// If an error is returned, set the error and return.
	if ( ! empty( $valid_key->error ) ) {
		wp_send_json_error( [ 'message' => wp_kses_post( $valid_key->error ) ] );
	}

	$option                = get_option( 'envira_gallery' );
	$option['key']         = $_POST['envira-license-key']; // @codingStandardsIgnoreLine
	$option['type']        = isset( $valid_key->type ) ? $valid_key->type : $option['type'];
	$option['is_expired']  = false;
	$option['is_disabled'] = false;
	$option['is_invalid']  = false;

	update_option( 'envira_gallery', $option );

	$pro_path = trailingslashit( WP_PLUGIN_DIR ) . '/envira-gallery/envira-gallery.php';

	if ( file_exists( $pro_path ) ) {

		$active = activate_plugin( 'envira-gallery/envira-gallery.php', false, false, true );

		// Deactivate Lite.
		$plugin = plugin_basename( ENVIRA_LITE_FILE );

		deactivate_plugins( $plugin );

		do_action( 'envira_lite_plugin_deactivated', $plugin );

		wp_send_json_success(
			[
				'message' => esc_html__( 'Envira Gallery Pro is installed but not activated.', 'envira-gallery-lite' ),
				'reload'  => true,
			]
		);
	}

	if ( ! isset( $valid_key->download_url ) ) {
		wp_send_json_error( [ 'message' => esc_html__( 'There was an error connecting to the remote key API. Please try again later.', 'envira-gallery-lite' ) ] );
	}

	$download_url = esc_url_raw( $valid_key->download_url );

	// Start output bufferring to catch the filesystem form if credentials are needed.
	ob_start();

	$method = '';
	$url    = esc_url( admin_url( 'edit.php?post_type=envira&page=envira-gallery-lite-about-us' ) );

	$creds = request_filesystem_credentials( $url, $method, false, false, null );
	if ( false === $creds ) {
		$form = ob_get_clean();
		echo wp_json_encode( [ 'form' => $form ] );
		die;
	}
	// If we are not authenticated, make it happen now.
	if ( ! WP_Filesystem( $creds ) ) {
		ob_start();
		request_filesystem_credentials( $url, $method, true, false, null );
		$form = ob_get_clean();
		echo wp_json_encode( [ 'form' => $form ] );
		die;
	}
	// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once plugin_dir_path( ENVIRA_LITE_FILE ) . 'includes/global/Installer_Skin.php';

	// Create the plugin upgrader with our custom skin.
	$skin      = new Envira_Lite_Installer_Skin();
	$installer = new Plugin_Upgrader( $skin );
	$installer->install( $download_url );

	// Flush the cache and return the newly installed plugin basename.
	wp_cache_flush();

	if ( $installer->plugin_info() ) {

		$plugin_basename = $installer->plugin_info();

		$active = activate_plugin( $plugin_basename, false, false, true );

		// Deactivate Lite.
		$plugin = plugin_basename( ENVIRA_LITE_FILE );

		deactivate_plugins( $plugin );

		do_action( 'envira_lite_plugin_deactivated', $plugin );

		wp_send_json_success(
			[
				'message' => esc_html__( 'Envira Gallery Pro is installed but not activated.', 'envira-gallery-lite' ),
				'reload'  => true,
			]
		);
	}
}

/**
 * Queries the remote URL via wp_remote_post and returns a json decoded response.
 *
 * @since 1.8.7
 *
 * @param string $action        The name of the $_POST action var.
 * @param array  $body           The content to retrieve from the remote URL.
 * @param array  $headers        The headers to send to the remote URL.
 * @param string $return_format The format for returning content from the remote URL.
 * @return string|bool          Json decoded response on success, false on failure.
 */
function envira_api_remote_request( $action, $body = [], $headers = [], $return_format = 'json' ) {

	// Build the body of the request.
	$body = wp_parse_args(
		$body,
		[
			'tgm-updater-action'     => $action,
			'tgm-plugin-key'         => '',
			'tgm-updater-wp-version' => get_bloginfo( 'version' ),
			'tgm-updater-referer'    => site_url(),
		]
	);
	$body = http_build_query( $body, '', '&' );
	// Build the headers of the request.
	$headers = wp_parse_args(
		$headers,
		[
			'Content-Type'   => 'application/x-www-form-urlencoded',
			'Content-Length' => strlen( $body ),
		]
	);
	// Setup variable for wp_remote_post.
	$post = [
		'headers' => $headers,
		'body'    => $body,
	];
	// Allow us to define the api url for development.
	$api_url = defined( 'ENVIRA_API_URL' ) ? ENVIRA_API_URL : 'https://enviragallery.com';
	// Perform the query and retrieve the response.
	$response      = wp_remote_post( $api_url, $post );
	$response_code = wp_remote_retrieve_response_code( $response ); /* log this for API issues */
	$response_body = wp_remote_retrieve_body( $response );

	// Bail out early if there are any errors.
	if ( 200 !== $response_code || is_wp_error( $response_body ) ) {
		return false;
	}
	// Return the json decoded content.
	return json_decode( $response_body );
}
