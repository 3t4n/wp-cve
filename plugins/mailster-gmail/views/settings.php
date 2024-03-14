<table class="form-table">
	<?php if ( ! $verified ) : ?>
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td><p class="description"><?php echo sprintf( __( 'Please follow our guide %s and enter Client ID and Client Secret to continue.', 'mailster-gmail' ), '<a href="https://kb.mailster.co/send-your-newsletters-via-gmail/" class="external">' . __( 'here', 'mailster-gmail' ) . '</a>' ); ?></p>
		</td>
	</tr>
	<?php endif; ?>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Gmail Client ID', 'mailster-gmail' ); ?></th>
		<td><input type="text" name="mailster_options[gmail_client_id]" value="<?php echo esc_attr( mailster_option( 'gmail_client_id' ) ); ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Gmail Client Secret', 'mailster-gmail' ); ?></th>
		<td><input type="password" name="mailster_options[gmail_client_secret]" value="<?php echo esc_attr( mailster_option( 'gmail_client_secret' ) ); ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Authorized redirect URI', 'mailster-gmail' ); ?></th>
		<td><code id="copy-redirect_url"><?php echo $this->get_redirect_url(); ?></code><br><a class="clipboard" data-clipboard-target="#copy-redirect_url"><?php esc_html_e( 'copy URL', 'mailster-gmail' ); ?></a></td>
	</tr>

	<?php if ( mailster_option( 'gmail_client_id' ) && mailster_option( 'gmail_client_secret' ) ) : ?>

		<?php if ( $verified ) : ?>

			<tr valign="top">
				<th scope="row">&nbsp;</th>
				<td>
					<span style="color:#3AB61B">&#10004;</span> <?php esc_html_e( 'Your are authorized!', 'mailster-gmail' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><p><input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Remove connection', 'mailster-gmail' ); ?>" onclick="return confirm('<?php esc_attr_e( 'Do you really like to remove the connection?', 'mailster-gmail' ); ?>') && jQuery('#gmail_token').val('');"> <?php esc_html_e( 'or check given', 'mailster-gmail' ); ?> <a href="https://myaccount.google.com/permissions" class="external"><?php esc_html_e( 'Permissions', 'mailster-gmail' ); ?></a>.</p></td>
			</tr>

		<?php else : ?>

			<?php
			$authurl = add_query_arg(
				array(
					'response_type'          => 'code',
					'access_type'            => 'offline',
					'client_id'              => mailster_option( 'gmail_client_id' ),
					'redirect_uri'           => rawurlencode( $this->get_redirect_url() ),
					'state'                  => '',
					'scope'                  => rawurlencode( 'https://mail.google.com/' ),
					'approval_prompt'        => 'force',
					'include_granted_scopes' => 'true',
				),
				'https://accounts.google.com/o/oauth2/auth'
			)
			?>
		<tr valign="top">
			<th scope="row"></th>
			<td><a class="button button-hero button-primary" href="<?php echo esc_url( $authurl ); ?>"><?php esc_html_e( 'Authorize', 'mailster-gmail' ); ?></a></td>
		</tr>

		<?php endif; ?>
	<?php endif; ?>

	<input type="hidden" id="gmail_token" name="mailster_options[gmail_token]" value="<?php echo esc_attr( mailster_option( 'gmail_token' ) ); ?>">

</table>
