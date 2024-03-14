<form method="post">
	<?php include('_inc/notices.php'); ?>

	<div id="fonts-com-setup-initial-container">

		<h3><?php _e('Initial Setup'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-authentication-key"><?php _e('Authentication Key'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="fonts-com-setup[authentication-key]" id="fonts-com-setup-authentication-key" value="<?php esc_attr_e($settings['authentication-key']); ?>" />
						<input type="button" class="button button-secondary" id="fonts-com-setup-authentication-key-validate" value="<?php _e('Validate and Save'); ?>" />
						<input type="button" class="button button-secondary <?php if(empty($settings['authentication-key'])) { ?>hide-if-js<?php } ?>" id="fonts-com-setup-authentication-key-clear" value="<?php _e('Clear Saved Key'); ?>" />
						
						<div><?php printf(__('Get your <a target="_blank" href="%s">Authentication Key</a>'), 'https://webfonts.fonts.com/en-US/Account/AccountInformation'); ?></div>
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2"><strong class="fonts-com-header"><?php _e('- OR -'); ?></strong></th>	
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-email"><?php _e('Email Address'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="fonts-com-setup[email]" id="fonts-com-setup-email" value="<?php esc_attr_e($settings['email']); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-password"><?php _e('Password'); ?></label></th>
					<td>
						<input autocomplete="off" type="password" class="code regular-text" name="fonts-com-setup[password]" id="fonts-com-setup-password" value="<?php esc_attr_e(''); ?>" />
						<input type="button" class="button button-secondary" id="fonts-com-setup-email-password-validate" value="<?php _e('Validate and Get Key'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2"><strong class="fonts-com-header"><?php _e('- OR -'); ?></strong></th>	
				</tr>
				<tr valign="top">
					<th colspan="2" style="vertical-align: middle;">
						<input type="button" class="button button-secondary" id="fonts-com-setup-create-account" value="<?php _e('Create Account'); ?>" /> &mdash;
						<?php _e("It's quick to do and free to try it out!"); ?>
					</th>
				</tr>
			</tbody>
		</table>
		
		<br />
		<h3><?php _e('Embedding Options'); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-embed-method"><?php _e('Embed Method'); ?></label></th>
					<td>
						<select name="fonts-com-setup[embed-method]" id="fonts-com-setup-embed-method">
							<option <?php selected($settings['embed-method'], 'javascript'); ?> value="javascript"><?php _e('JavaScript'); ?></option>
							<option <?php selected($settings['embed-method'], 'css'); ?> value="css"><?php _e('CSS'); ?></option>
						</select>
						<input type="button" class="button button-secondary" id="fonts-com-set-embed-method" value="<?php _e('Set'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		
	</div>

	<div id="fonts-com-setup-create-account-container" class="hide-if-js">

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th colspan="2"><strong class="fonts-com-header"><?php _e('New Account'); ?></strong></th>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-new-first-name"><?php _e('First Name'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="fonts-com-setup[new-first-name]" id="fonts-com-setup-new-first-name" value="<?php esc_attr_e($current_user->first_name); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-new-last-name"><?php _e('Last Name'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="fonts-com-setup[new-last-name]" id="fonts-com-setup-new-last-name" value="<?php esc_attr_e($current_user->last_name); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fonts-com-setup-new-email-address"><?php _e('Email Address'); ?></label></th>
					<td>
						<input type="text" class="code regular-text" name="fonts-com-setup[new-email-address]" id="fonts-com-setup-new-email-address" value="<?php esc_attr_e($current_user->user_email); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2">
						<input type="button" class="button button-primary" id="fonts-com-setup-new-sign-up" value="<?php _e('Sign Up'); ?>" />
						<input type="button" class="button button-secondary fonts-com-setup-new-cancel" id="fonts-com-setup-new-cancel" value="<?php _e('Cancel'); ?>" />
					</th>
				</tr>
			</tbody>
		</table>
	</div>

</form>