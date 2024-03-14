<table class="form-table">
	<?php if ( ! $verified ) : ?>
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td><p class="description"><?php echo sprintf( __( 'You need a %s account to use this service!', 'mailster-mailgun' ), '<a href="https://www.mailgun.com/" class="external">Mailgun</a>' ); ?></p>
		</td>
	</tr>
	<?php endif; ?>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Mailgun API key', 'mailster-mailgun' ); ?></th>
		<td><input type="password" name="mailster_options[mailgun_apikey]" value="<?php echo esc_attr( mailster_option( 'mailgun_apikey' ) ); ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td>
			<?php if ( $verified ) : ?>
			<span style="color:#3AB61B">&#10004;</span> <?php esc_html_e( 'Your API Key is ok!', 'mailster-mailgun' ); ?>
			<?php else : ?>
			<span style="color:#D54E21">&#10006;</span> <?php esc_html_e( 'Your API Key is WRONG!', 'mailster-mailgun' ); ?>
			<?php endif; ?>

			<input type="hidden" name="mailster_options[mailgun_verified]" value="<?php echo $verified; ?>">
		</td>
	</tr>
</table>
<?php if ( 'mailgun' == mailster_option( 'deliverymethod' ) ) : ?>
<div class="<?php echo ( ! $verified ) ? 'hidden' : ''; ?>">
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Sending Domain', 'mailster-mailgun' ); ?></th>
		<td>
		<?php $domains = $verified ? $this->get_sending_domains() : array(); ?>
		<?php if ( is_wp_error( $domains ) ) : ?>
			<div class="error inline"><p><strong><?php esc_html_e( 'Not able to get Sub Accounts. Make sure your API Key is allowed to read them! Mailster will use your Master Account.', 'mailster-mailgun' ); ?></strong></p></div>
		<?php else : ?>
		<p class="howto"><?php esc_html_e( 'Send From Following Domain', 'mailster-mailgun' ); ?></p>
		<select name="mailster_options[mailgun_domain]">
			<?php foreach ( $domains as $domain ) : ?>
			<option value="<?php echo esc_attr( $domain->name ); ?>" <?php selected( mailster_option( 'mailgun_domain' ), $domain->name ); ?>><?php echo esc_html( $domain->name . ' (' . $domain->state . ')' ); ?></option>
		<?php endforeach; ?>
		</select> <a href="https://app.mailgun.com/app/domains" class="external"><?php esc_html_e( 'Manage your Domains', 'mailster-mailgun' ); ?></a>
		<?php endif; ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Send Emails with', 'mailster-mailgun' ); ?></th>
		<td>
		<select name="mailster_options[mailgun_api]">
			<option value="web" <?php selected( mailster_option( 'mailgun_api' ), 'web' ); ?>>WEB API</option>
			<option value="smtp" <?php selected( mailster_option( 'mailgun_api' ), 'smtp' ); ?>>SMTP API</option>
		</select>
		<span class="description"><?php esc_html_e( 'Use the WEB API as it\'s most likly faster.', 'mailster-mailgun' ); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Endpoint', 'mailster-mailgun' ); ?></th>
		<td>
		<select name="mailster_options[mailgun_endpoint]">
			<option value="0" <?php selected( ! mailster_option( 'mailgun_endpoint' ) ); ?>><?php esc_html_e( 'Default', 'mailster-mailgun' ); ?></option>
			<option value="eu" <?php selected( mailster_option( 'mailgun_endpoint' ), 'eu' ); ?>><?php esc_html_e( 'EU', 'mailster-mailgun' ); ?></option>
		</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'SMTP Port', 'mailster-mailgun' ); ?></th>
		<td>
		<select name="mailster_options[mailgun_smtp_port]">
			<option value="25" <?php selected( mailster_option( 'mailgun_smtp_port' ), '25' ); ?>>25</option>
			<option value="465" <?php selected( mailster_option( 'mailgun_smtp_port' ), '465' ); ?>>465</option>
			<option value="587" <?php selected( mailster_option( 'mailgun_smtp_port' ), '587' ); ?>>587</option>
		</select>
		<span class="description"><?php esc_html_e( 'Only in use for SMTP API', 'mailster-mailgun' ); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'SMTP Login', 'mailster-mailgun' ); ?></th>
		<td><input type="text" name="mailster_options[mailgun_smtp_login]" value="<?php echo esc_attr( mailster_option( 'mailgun_smtp_login' ) ); ?>" class="regular-text"><span class="description">@<?php echo mailster_option( 'mailgun_domain' ); ?></span></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'SMTP Password', 'mailster-mailgun' ); ?></th>
		<td><input type="password" name="mailster_options[mailgun_smtp_password]" value="<?php echo esc_attr( mailster_option( 'mailgun_smtp_password' ) ); ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Track in Mailgun', 'mailster-mailgun' ); ?></th>
		<td>
		<select name="mailster_options[mailgun_track]">
			<option value="0"<?php selected( mailster_option( 'mailgun_track' ), 0 ); ?>><?php esc_html_e( 'Account defaults', 'mailster-mailgun' ); ?></option>
			<option value="none"<?php selected( mailster_option( 'mailgun_track' ), 'none' ); ?>><?php esc_html_e( 'none', 'mailster-mailgun' ); ?></option>
			<option value="opens"<?php selected( mailster_option( 'mailgun_track' ), 'opens' ); ?>><?php esc_html_e( 'opens', 'mailster-mailgun' ); ?></option>
			<option value="clicks"<?php selected( mailster_option( 'mailgun_track' ), 'clicks' ); ?>><?php esc_html_e( 'clicks', 'mailster-mailgun' ); ?></option>
			<option value="opens,clicks"<?php selected( mailster_option( 'mailgun_track' ), 'opens,clicks' ); ?>><?php esc_html_e( 'opens and clicks', 'mailster-mailgun' ); ?></option>
		</select> <span class="description"><?php esc_html_e( 'Track opens and clicks in Mailgun as well', 'mailster-mailgun' ); ?></span></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Tags', 'mailster-mailgun' ); ?></th>
		<td><input type="text" name="mailster_options[mailgun_tags]" value="<?php echo esc_attr( mailster_option( 'mailgun_tags' ) ); ?>" class="large-text">
		<p class="howto"><?php esc_html_e( 'Define your tags separated with commas which get send via the Mailgun API', 'mailster-mailgun' ); ?></p>
	</tr>
</table>
</div>
<?php else : ?>
<input type="hidden" name="mailster_options[mailgun_domain]" value="<?php echo esc_attr( mailster_option( 'mailgun_domain' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_api]" value="<?php echo esc_attr( mailster_option( 'mailgun_api' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_endpoint]" value="<?php echo esc_attr( mailster_option( 'mailgun_endpoint' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_smtp_port]" value="<?php echo esc_attr( mailster_option( 'mailgun_smtp_port' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_smtp_login]" value="<?php echo esc_attr( mailster_option( 'mailgun_smtp_login' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_smtp_password]" value="<?php echo esc_attr( mailster_option( 'mailgun_smtp_password' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_track]" value="<?php echo esc_attr( mailster_option( 'mailgun_track' ) ); ?>">
<input type="hidden" name="mailster_options[mailgun_tags]" value="<?php echo esc_attr( mailster_option( 'mailgun_tags' ) ); ?>">
	<?php if ( $verified ) : ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">&nbsp;</th>
			<td><div class="notice notice-warning inline"><p><strong><?php esc_html_e( 'Please save your settings to access further delivery options!', 'mailster-mailgun' ); ?></strong></p></div></td>
		</tr>
	</table>
	<?php endif; ?>
<?php endif; ?>
