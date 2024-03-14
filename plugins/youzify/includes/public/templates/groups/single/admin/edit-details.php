<?php
/**
 * BuddyPress - Groups Admin - Edit Details
 */

?>

<div class="youzify-group-settings-tab">

	<?php

	/**
	 * Fires before the display of group admin details.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_details_admin' ); ?>

	<div class="youzify-group-field-item">
		<label for="group-name"><?php _e( 'Group Name (required)', 'youzify' ); ?></label>
		<input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" />
	</div>

	<div class="youzify-group-field-item">
		<label for="group-desc"><?php _e( 'Group Description (required)', 'youzify' ); ?></label>
		<textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>
	</div>

	<?php

	/**
	 * Fires after the group description admin details.
	 *
	 * @since 1.0.0
	 */
	do_action( 'groups_custom_group_fields_editable' ); ?>

	<div class="youzify-group-field-item">
		<label for="group-notify-members">
			<input type="checkbox" name="group-notify-members" id="group-notify-members" value="1" /> <?php _e( 'Notify group members of these changes via email', 'youzify' ); ?>
		</label>
	</div>

	<?php

	/**
	 * Fires after the display of group admin details.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_details_admin' ); ?>
	<div class="youzify-group-submit-form">
		<input type="submit" value="<?php esc_attr_e( 'Save Changes', 'youzify' ); ?>" id="save" name="save" />
		<?php wp_nonce_field( 'groups_edit_group_details' ); ?>
	</div>
</div>
