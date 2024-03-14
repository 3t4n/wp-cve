<?php
/**
 * BuddyPress - Members Single Messages Compose
 *
 */
?>
<form action="<?php bp_messages_form_action('compose' ); ?>" method="post" id="send_message_form" class="standard-form" enctype="multipart/form-data">

	<?php

	/**
	 * Fires before the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_messages_compose_content' ); ?>

	<div class="youzify-msg-form-item">
		<label for="send-to-input"><?php _e( "Send To ( Username or Friend's Name )", 'youzify' ); ?></label>
		<ul class="first acfb-holder">
			<li class="youzify-compose-input-item">
				<?php bp_message_get_recipient_tabs(); ?>
				<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" />
			</li>
		</ul>
	</div>

	<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<div class="youzify-msg-form-item">
			<label class="youzify-cs-checkbox-field" for="send-notice">
				<input type="checkbox" id="send-notice" name="send-notice" value="1" />
				<div class="youzify_field_indication"></div>
				<?php _e( "This is a notice to all users.", 'youzify' ); ?>
			</label>
		</div>
	<?php endif; ?>

	<div class="youzify-msg-form-item">
		<label for="subject"><?php _e( 'Subject', 'youzify' ); ?></label>
		<input type="text" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />
	</div>

	<div class="youzify-msg-form-item">
		<label for="message_content"><?php _e( 'Message', 'youzify' ); ?></label>
		<div class="youzify-compose-message-textarea">
		<textarea name="content" id="message_content" rows="15" cols="40"><?php bp_messages_content_value(); ?></textarea>

		<div class="youzify-message-form-tools">
			<?php if ( 'on' == youzify_option( 'youzify_enable_messages_emoji', 'on' ) ) : ?><div class="youzify-load-emojis youzify-load-messages-emojis"><i class="far fa-smile"></i></div><?php endif; ?>
		</div>
		</div>

	</div>

	<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />

	<?php

	/**
	 * Fires after the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_messages_compose_content' ); ?>

	<div class="submit">
		<input type="submit" value="<?php esc_attr_e( 'Send Message', 'youzify' ); ?>" name="send" id="send" />
	</div>

	<div class="youzify-wall-attachments youzify-attachments">
		<input hidden="true" class="youzify-upload-attachments" type="file" name="attachments[]" multiple>
		<div class="youzify-form-attachments"></div>
	</div>

	<?php wp_nonce_field( 'messages_send_message' ); ?>
</form>

<script type="text/javascript">
	document.getElementById("send-to-input").focus();
</script>