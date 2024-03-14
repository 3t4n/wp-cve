<?php
/*
 * Settings page tab.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Settingstab for email.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_page_settingstab_email() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	} ?>

	<input type="hidden" id="cs_tab" name="cs_tab" value="cs_tab_email" />
	<?php
	settings_fields( 'chessgame_shizzle_options' );
	do_settings_sections( 'chessgame_shizzle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'chessgame_shizzle_page_settingstab_email' );
	echo '<input type="hidden" id="chessgame_shizzle_page_settingstab_email" name="chessgame_shizzle_page_settingstab_email" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<?php
		$user_ids = get_option('chessgame_shizzle-notifybymail' );
		if ( strlen($user_ids) > 0 ) {
			$user_ids = explode( ',', $user_ids );
			$user_ids = array_map( 'absint', array_unique( (array) $user_ids ) );
		} ?>

		<tr valign="top">
			<th scope="row"><label><?php esc_html_e('Subscription status', 'chessgame-shizzle'); ?></label></th>
			<td>
				<?php
				$my_user_id = (int) get_current_user_id();
				if ( is_array($user_ids) && in_array( $my_user_id, $user_ids, true) ) {
					esc_html_e('You are subscribed to email notifications.', 'chessgame-shizzle');
				} else {
					esc_html_e('You are not subscribed to email notifications.', 'chessgame-shizzle');
				} ?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cs_subscribe"><?php esc_html_e('Subscribe moderators', 'chessgame-shizzle'); ?></label></th>
			<td>
				<select name="cs_subscribe" id="cs_subscribe">
					<option value="0"><?php esc_html_e('Subscribe User', 'chessgame-shizzle'); ?></option>
					<?php
					$users = chessgame_shizzle_get_moderators();

					if ( is_array($users) && ! empty($users) ) {
						foreach ( $users as $user_info ) {

							// Test if already subscribed
							if ( is_array($user_ids) && ! empty($user_ids) ) {
								if ( in_array( $user_info->ID, $user_ids, true ) ) {
									continue;
								}
							}

							$username = esc_html( $user_info->first_name ) . ' ' . esc_html( $user_info->last_name ) . ' (' . esc_html( $user_info->user_email ) . ')';
							if ( $user_info->ID === get_current_user_id() ) {
								$username .= ' ' . esc_html__('You', 'chessgame-shizzle');
							}
							echo '<option value="' . (int) $user_info->ID . '">' . esc_html( $username ) . '</option>';
						}
					} ?>
				</select><br />
				<label for="cs_subscribe"><?php esc_html_e('You can subscribe a moderator to the notification emails.', 'chessgame-shizzle'); ?><br />
				<?php esc_html_e('Select a user that you want subscribed to the notification emails.', 'chessgame-shizzle'); ?>
				<?php esc_html_e("You will only see users with the roles of Administrator, Editor and Author, who have the capability 'publish_posts' .", 'chessgame-shizzle'); ?>
				</label>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cs_unsubscribe"><?php esc_html_e('Unsubscribe moderators', 'chessgame-shizzle'); ?></label></th>
			<td>
				<?php
				// Check if function mail() exists. If not, display a hint to the user.
				if ( ! function_exists('mail') ) {
					/* translators: 'mail()' is surrounded by code tags */
					echo '<p class="setting-description">' .
						sprintf( esc_html__( 'Sorry, but the function %1$smail()%2$s required to notify you by mail is not enabled in your PHP configuration. You might want to install a WordPress plugin that uses SMTP instead of %3$smail()%4$s. Or you can contact your hosting provider to change this.', 'chessgame-shizzle' ), '<code>', '</code>', '<code>', '</code>' )
						. '</p>';
				} ?>
				<select name="cs_unsubscribe" id="cs_unsubscribe">
					<option value="0"><?php esc_html_e('Unsubscribe User', 'chessgame-shizzle'); ?></option>
					<?php
					if ( is_array($user_ids) && ! empty($user_ids) ) {
						foreach ( $user_ids as $user_id ) {

							$user_info = get_userdata( (int) $user_id );
							if ($user_info === false) {
								// Invalid $user_id
								continue;
							}
							$username = esc_html( $user_info->first_name ) . ' ' . esc_html( $user_info->last_name ) . ' (' . esc_html( $user_info->user_email ) . ')';
							if ( $user_info->ID === get_current_user_id() ) {
								$username .= ' ' . esc_html__('You', 'chessgame-shizzle');
							}
							echo '<option value="' . (int) $user_id . '">' . esc_html( $username ) . '</option>';
						}
					} ?>
				</select><br />
				<label for="cs_unsubscribe"><?php esc_html_e('These users have subscribed to the notification emails.', 'chessgame-shizzle'); ?><br />
				<?php esc_html_e('Select a user if you want that user to unsubscribe from the notification emails.', 'chessgame-shizzle'); ?></label>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cs_admin_mail_from"><?php /* translators: Setting for SMTP mail from header */ esc_html_e('Send from address', 'chessgame-shizzle'); ?></label></th>
			<td>
				<input type="email" name="cs_admin_mail_from" id="cs_admin_mail_from" class="regular-text" value="<?php echo esc_attr( sanitize_text_field( get_option('chessgame_shizzle-mail-from', false) ) ); ?>" placeholder="info@example.com" />
				<br />
				<span class="setting-description">
					<?php
					esc_html_e('You can set the email address that is used for the From header of the mail that a notification subscriber gets on new entries.', 'chessgame-shizzle');
					echo '<br />';
					esc_html_e('By default the main admin address is used from General > Settings.', 'chessgame-shizzle');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="chessgame_shizzle_settings_admin" id="chessgame_shizzle_settings_admin" class="button-primary" value="<?php esc_attr_e('Save settings', 'chessgame-shizzle'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
