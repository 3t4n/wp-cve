<form id="sakolawp_registration_form" class="sakolawp_user_form sakolawp_form" action="" method="POST">
	<fieldset class="skwp-form-inner">
		<?php if ( ! empty( $logo_image ) ) { ?>
			<img src="<?php echo esc_url( $logo_image[0] ); ?>" alt="<?php esc_html_e( 'logo', 'sakolawp' ); ?>" />
		<?php } ?>
		<h4 class="sakolawp_header"><?php esc_html_e('Register New Account', 'sakolawp'); ?></h4>
		<?php sakolawp_show_error_messages(); ?>
		<p>
			<label for="sakolawp_user_Login"><?php esc_html_e('Username', 'sakolawp'); ?></label>
			<input name="sakolawp_user_login" id="sakolawp_user_login" class="required skwp-form-control" type="text"/>
		</p>
		<p>
			<label for="sakolawp_user_email"><?php esc_html_e('Email', 'sakolawp'); ?></label>
			<input name="sakolawp_user_email" id="sakolawp_user_email" class="required skwp-form-control" type="email"/>
		</p>
		<p>
			<label for="sakolawp_user_first"><?php esc_html_e('First Name', 'sakolawp'); ?></label>
			<input name="sakolawp_user_first" id="sakolawp_user_first" class="skwp-form-control" type="text"/>
		</p>
		<p>
			<label for="sakolawp_user_last"><?php esc_html_e('Last Name', 'sakolawp'); ?></label>
			<input name="sakolawp_user_last" id="sakolawp_user_last" class="skwp-form-control" type="text"/>
		</p>
		<p>
			<label for="password"><?php esc_html_e('Password', 'sakolawp'); ?></label>
			<input name="sakolawp_user_pass" id="password" class="required skwp-form-control" type="password"/>
		</p>
		<p>
			<label for="password_again"><?php esc_html_e('Password Again', 'sakolawp'); ?></label>
			<input name="sakolawp_user_pass_confirm" id="password_again" class="required skwp-form-control" type="password"/>
		</p>
		<p>
			<label for="roles"><?php esc_html_e('Select Your Roles', 'sakolawp'); ?></label>
			<select name="sakolawp_user_roles" id="user_roles" class="skwp-form-control required" required="">
				<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
				<option value="teacher"><?php esc_html_e('Teacher', 'sakolawp'); ?></option>
				<option value="student"><?php esc_html_e('Student', 'sakolawp'); ?></option>
			</select>
		</p>
		<p>
			<input type="hidden" name="sakolawp_register_nonce" value="<?php echo wp_create_nonce('sakolawp-register-nonce'); ?>"/>
			<input id="sakolawp_login_submit" type="submit" value="<?php esc_html_e('Register Your Account', 'sakolawp'); ?>"/>
		</p>
	</fieldset>
</form>