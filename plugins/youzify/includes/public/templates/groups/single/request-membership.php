<?php
/**
 * BuddyPress - Groups Request Membership
 */

/**
 * Fires before the display of the group membership request form.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_request_membership_content' ); ?>

<div class="youzify-group-settings-tab youzify-group-avatar-settings">

<?php if ( ! bp_group_has_requested_membership() ) : ?>

	<p class="description"><?php printf( __( "You are requesting to become a member of the group '%s'.", 'youzify' ), bp_get_group_name( false ) ); ?></p>

	<form action="<?php bp_group_form_action( 'request-membership' ); ?>" method="post" name="request-membership-form" id="request-membership-form" class="standard-form">

		<div class="youzify-group-field-item">
			<label for="group-request-membership-comments"><?php _e( 'Comments (optional)', 'youzify' ); ?></label>
			<textarea name="group-request-membership-comments" id="group-request-membership-comments"></textarea>
		</div>

		<?php

		/**
		 * Fires after the textarea for the group membership request form.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_group_request_membership_content' ); ?>

		<div class="youzify-group-submit-form">
			<input type="submit" name="group-request-send" id="group-request-send" value="<?php esc_attr_e( 'Send Request', 'youzify' ); ?>" />
		</div>

		<?php wp_nonce_field( 'groups_request_membership' ); ?>
	</form><!-- #request-membership-form -->
<?php endif; ?>

<?php

/**
 * Fires after the display of the group membership request form.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_request_membership_content' ); ?>

</div>