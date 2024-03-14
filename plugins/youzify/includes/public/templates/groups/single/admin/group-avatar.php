<?php
/**
 * BuddyPress - Groups Admin - Group Avatar
 */

?>

<div class="youzify-group-settings-tab youzify-group-avatar-settings">

	<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

		<p><?php _e("Upload an image to use as a profile photo for this group. The image will be shown on the main group page, and in search results.", 'youzify' ); ?></p>

		<p class="youzify-group-avatar-form-basic">
			<label for="file" class="bp-screen-reader-text"><?php
				/* translators: accessibility text */
				_e( 'Select an image', 'youzify' );
			?></label>
			<input type="file" name="file" id="file" />
			<input type="submit" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'youzify' ); ?>" />
			<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
		</p>

		<?php if ( bp_get_group_has_avatar() ) : ?>

			<p><?php _e( "If you'd like to remove the existing group profile photo but not upload a new one, please use the delete group profile photo button.", 'youzify' ); ?></p>

			<?php bp_button( array( 'id' => 'delete_group_avatar', 'component' => 'groups', 'wrapper_id' => 'delete-group-avatar-button', 'link_class' => 'edit', 'link_href' => bp_get_group_avatar_delete_link(), 'link_text' => __( 'Delete Group Profile Photo', 'youzify' ) ) ); ?>

		<?php endif; ?>

		<?php
		/**
		 * Load the Avatar UI templates
		 *
		 * @since  2.3.0
		 */
		bp_avatar_get_templates(); ?>

		<?php wp_nonce_field( 'bp_avatar_upload' ); ?>

	<?php endif; ?>

	<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

		<h4><?php _e( 'Crop Profile Photo', 'youzify' ); ?></h4>

		<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php esc_attr_e( 'Profile photo to crop', 'youzify' ); ?>" />

		<div id="avatar-crop-pane">
			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Profile photo preview', 'youzify' ); ?>" />
		</div>

		<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'youzify' ); ?>" />

		<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />

		<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>

	<?php endif; ?>

</div>