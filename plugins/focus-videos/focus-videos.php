<?php

/*
Plugin Name: Focus Video Plugin
Description: A plugin for setting the video in Focus theme.
Version: 1.1
Author: SiteOrigin
Author URI: http://siteorigin.com
License: GPL 3.0
License URI: license.txt
*/


/**
 * Enqueue the video scripts.
 *
 * @param $prefix
 */
function siteorigin_focus_admin_scripts( $prefix ) {
	$screen = get_current_screen();

	if( $prefix == 'post.php' || $prefix == 'post-new.php' && $screen->id == 'post' ){
		wp_enqueue_script( 'focus-admin-video', plugin_dir_url(__FILE__).'admin.video.js', array( 'jquery' ), '1.0' );
		wp_localize_script( 'focus-admin-video', 'focusVideoSettings', array(
			'button' => __( 'Set Video', 'focus' )
		));
	}
}
add_action( 'admin_enqueue_scripts', 'siteorigin_focus_admin_scripts' );

/**
 * Add the video metabox.
 */
function siteorigin_focus_add_metabox(){
	add_meta_box( 'focus-video', __( 'Video', 'focus' ), 'siteorigin_focus_video_metabox_render', array( 'post', 'page' ) );
}
add_action( 'add_meta_boxes', 'siteorigin_focus_add_metabox' );

/**
 * Render the video metabox.
 */
function siteorigin_focus_video_metabox_render(){
	siteorigin_focus_video_field( 'public', __( 'Public', 'focus' ) );
	siteorigin_focus_video_field( 'premium', __( 'Premium', 'focus' ) );
	do_action( 'focus_admin_after_video_metabox' );
	wp_nonce_field( 'save', '_focusnonce' );
}

/**
 * Render a single video field.
 *
 * @param $type
 * @param $title
 */
function siteorigin_focus_video_field( $type, $title ) {
	global $post;
	$video = get_post_meta( $post->ID, 'focus_video', true );
	$video = !empty( $video[$type] ) ? $video[$type] : array();
	$video = wp_parse_args( $video, array(
		'type' => '',
		'external' => '',
		'self' => '',
		'remote' => '',
		'custom' => '',
	) );
	$self = !empty( $video['self'] ) ? get_post( $video['self'] ) : null;

	?>
	<h3><?php printf( __( '%s Video', 'focus' ), $title ) ?></h3>
	<table class="form-table focus-video-table">
		<tbody>
		<tr>
			<th scope="row" valign="top"><?php _e( 'Video Type', 'focus' ) ?></th>
			<td>
				<select name="focus_video[<?php echo esc_attr( $type ) ?>][type]" class="focus-video-type-select">
					<option value="external" <?php selected( 'external', $video['type'] ) ?>><?php esc_html_e( 'External (YouTube, Vimeo, etc)', 'focus' ) ?></option>
					<option value="self" <?php selected( 'self', $video['type'] ) ?>><?php esc_html_e('Self Hosted', 'focus') ?></option>
					<option value="remote" <?php selected( 'remote', $video['type'] ) ?>><?php esc_html_e( 'Remote Video File', 'focus' ) ?></option>
					<option value="custom" <?php selected( 'custom', $video['type'] ) ?>><?php esc_html_e( 'Custom Embed Code', 'focus' ) ?></option>
				</select>
			</td>
		</tr>

		<tr class="field-<?php echo esc_attr( $type ) ?>-external field-external field">
			<th scope="row" valign="top"><?php _e( 'External Video URL', 'focus' ) ?></th>
			<td><input type="text" name="focus_video[<?php echo esc_attr( $type ) ?>][external]" class="regular-text" value="<?php echo esc_attr( $video['external'] ) ?>" /></td>
		</tr>
		<tr class="field-<?php echo esc_attr( $type ) ?>-self  field-self field">
			<th scope="row" valign="top"><?php _e( 'Self Hosted Video', 'focus' ) ?></th>
			<td class="wp-media-buttons">
				<strong style="margin-right: 10px" class="video-name"><?php if( !empty( $self ) ) echo $self->post_title ?></strong>

				<a href="#" class="button add_media focus-add-video" data-video-type="<?php echo esc_attr( $type ) ?>" title="Add Media" data-choose="<?php esc_attr_e( 'Select Video File', 'focus' ) ?>" data-update="<?php esc_attr_e( 'Set Video', 'focus' ) ?>">
					<span class="wp-media-buttons-icon"></span> <?php printf( __( 'Add %s Video', 'focus' ), $title) ?>
				</a>
				<a href="#" class="focus-remove-video"><?php printf( __( 'Remove %s Video', 'focus' ), $title) ?></a>

				<input type="hidden" name="focus_video[<?php echo esc_attr( $type ) ?>][self]" class="regular-text field-video-self" value="<?php if( !empty( $self ) ) echo $self->ID ?>" />
			</td>
		</tr>
		<tr class="field-<?php echo esc_attr( $type ) ?>-remote field-remote field">
			<th scope="row" valign="top"><?php _e( 'MP4 File URL', 'focus' ) ?></th>
			<td><input type="text" name="focus_video[<?php echo esc_attr( $type ) ?>][remote]" class="regular-text" value="<?php echo esc_attr( $video['remote'] ) ?>" /></td>
		</tr>
		<tr class="field-<?php echo esc_attr( $type ) ?>-custom field-custom field">
			<th scope="row" valign="top"><?php _e( 'Custom Embed Code', 'focus' ) ?></th>
			<td>
				<textarea name="focus_video[<?php echo esc_attr( $type ) ?>][custom]" class="widefat"><?php echo esc_textarea( $video['custom'] ) ?></textarea>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Save the post videos.
 *
 * @param $post_id
 */
function siteorigin_focus_video_save( $post_id ) {
	if( empty( $_POST['_focusnonce'] ) || !wp_verify_nonce( $_POST['_focusnonce'], 'save' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
	if( defined( 'DOING_AUTOSAVE' ) ) return;

	$video = array_map( 'stripslashes_deep', $_POST['focus_video'] );
	update_post_meta( $post_id, 'focus_video', $video );
}
add_action( 'save_post', 'siteorigin_focus_video_save' );
