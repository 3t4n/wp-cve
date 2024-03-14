<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="cleanlogin-container cleanlogin-full-width">
	<form class="cleanlogin-form" method="post" action="#">
		<fieldset>

			<?php do_action("cleanlogin_before_register_form"); ?>
			<?php if ( get_option( 'cl_nameandsurname' ) ) : ?>
				<div class="cleanlogin-field">
                    <label for="first_name"><?php echo __( 'First name', 'clean-login' ); ?></label>
					<input class="cleanlogin-field-name" type="text" name="first_name" value="" placeholder="<?php echo __( 'First name', 'clean-login' ); ?>">
				</div>
				<div class="cleanlogin-field">
                    <label for="last_name"><?php echo __( 'Last name', 'clean-login' ); ?></label>
					<input class="cleanlogin-field-surname" type="text" name="last_name" value="" placeholder="<?php echo __( 'Last name', 'clean-login' ); ?>">
				</div>
			<?php endif; ?>
			
			<?php if ( !get_option( 'cl_email_username' ) ) : ?>
				<div class="cleanlogin-field">
                    <label for="username"><?php echo __( 'Username', 'clean-login' ); ?></label>
					<input class="cleanlogin-field-username" type="text" name="username" value="" placeholder="<?php echo __( 'Username', 'clean-login' ); ?>" aria-label="<?php echo __( 'Username', 'clean-login' ); ?>">
				</div>
			<?php endif; ?>
			
			<div class="cleanlogin-field">
                <label for="email"><?php echo __( 'Email', 'clean-login' ); ?></label>
				<input class="cleanlogin-field-email" type="email" name="email" value="" placeholder="<?php echo __( 'Email', 'clean-login' ); ?>" aria-label="<?php echo __( 'Email', 'clean-login' ); ?>">
			</div>

			<div class="cleanlogin-field-website">
				<label for='website'>Website</label>
				<input type='text' name='website' value=".">
			</div>

			<div class="cleanlogin-field">
                <label for="pass1"><?php echo __( 'New password', 'clean-login' ); ?></label>
				<input class="cleanlogin-field-password" type="password" name="pass1" value="" autocomplete="off" placeholder="<?php echo __( 'New password', 'clean-login' ); ?>" aria-label="<?php echo __( 'New password', 'clean-login' ); ?>">
			</div>

			<?php if ( !get_option( 'cl_single_password' ) ) : ?>
				<div class="cleanlogin-field">
                    <label for="pass2"><?php echo __( 'Confirm password', 'clean-login' ); ?></label>
					<input class="cleanlogin-field-password" type="password" name="pass2" value="" autocomplete="off" placeholder="<?php echo __( 'Confirm password', 'clean-login' ); ?>" aria-label="<?php echo __( 'Confirm password', 'clean-login' ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( get_option( 'cl_antispam' ) ) : ?>
				<div class="cleanlogin-field">
					<img src="<?php echo CLEAN_LOGIN_CAPTCHA_URL; ?>"/>
					<input class="cleanlogin-field-spam" type="text" name="captcha" value="" autocomplete="off" placeholder="<?php echo __( 'Type the text above', 'clean-login' ); ?>" aria-label="<?php echo __( 'Type the text above', 'clean-login' ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( get_option( 'cl_gcaptcha' ) ) : ?>
				<?php CleanLogin_Frontend::gcaptcha_script(); ?>
				<div class="cleanlogin-field">
					<div class="g-recaptcha" data-sitekey="<?php echo get_option( 'cl_gcaptcha_sitekey' ) ?>"></div>
				</div>
			<?php endif; ?>
            
			<?php if ( get_option( 'cl_chooserole' ) ) : ?>
				<?php if ($param['role']) : ?>
				<input type="text" name="role" value="<?php echo $param['role']; ?>" hidden >
				<?php else : ?>
				<div class="cleanlogin-field cleanlogin-field-role" <?php if ( get_option( 'cl_antispam' ) || get_option( 'cl_gcaptcha' ) ) echo 'style="margin-top: 46px;"'; ?> >
					<span><?php echo __( 'Choose your role:', 'clean-login' ); ?></span>
					<select name="role" id="role">
						<?php
						$newuserroles = get_option ( 'cl_newuserroles' );
						global $wp_roles;
						foreach($newuserroles as $role){
							echo '<option value="'.$role.'">'. translate_user_role( $wp_roles->roles[ $role ]['name'] ) .'</option>';
						}
						?>
					</select>
				</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( get_option( 'cl_termsconditions' ) ) : ?>
				<div class="cleanlogin-field">
					<label class="cleanlogin-terms">
						<input name="termsconditions" type="checkbox" id="termsconditions">
						<a href="<?php echo CleanLogin_Controller::get_translated_option_page( 'cl_termsconditionsURL' ); ?>" target="_blank"><?php echo get_option( 'cl_termsconditionsMSG' ); ?></a>
					</label>
				</div>
			<?php endif; ?>

			<input type="hidden" name="clean_login_wpnonce" value="<?php echo wp_create_nonce( 'clean_login_wpnonce' ); ?>">

			<?php do_action("cleanlogin_after_register_form"); ?>
		</fieldset>

		<div>
			<input type="submit" value="<?php echo __( 'Register', 'clean-login' ); ?>" name="btn-submit" onclick="this.form.submit(); this.disabled = true;">
			<input type="hidden" name="action" value="register">
		</div>

	</form>
</div>