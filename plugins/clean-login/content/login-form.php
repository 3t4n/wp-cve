<?php
	if ( ! defined( 'ABSPATH' ) ) exit; 
	$login_url = CleanLogin_Controller::get_login_url();
	$register_url = CleanLogin_Controller::get_register_url();
	$restore_url = CleanLogin_Controller::get_restore_password_url();
?>

<div class="cleanlogin-container">		

	<form class="cleanlogin-form" method="post" action="<?php echo $login_url;?>" onsubmit="submit.disabled = true; return true;">
			
		<fieldset>

			<?php do_action("cleanlogin_before_login_form"); ?>
			<div class="cleanlogin-field">
                <label for="log"><?php echo __( 'Username', 'clean-login' ); ?></label>
				<input class="cleanlogin-field-username" type="text" name="log" placeholder="<?php echo __( 'Username', 'clean-login' ); ?>" aria-label="<?php echo __( 'Username', 'clean-login' ); ?>">
			</div>
			
			<div class="cleanlogin-field">
                <label for="pwd"><?php echo __( 'Password', 'clean-login' ); ?></label>
				<input class="cleanlogin-field-password" type="password" id="pwd" name="pwd" placeholder="<?php echo __( 'Password', 'clean-login' ); ?>" aria-label="<?php echo __( 'Password', 'clean-login' ); ?>">
                <i class="bi bi-eye-slash" id="togglePassword"></i>
			</div>

			<?php if ( get_option( 'cl_gcaptcha' ) ) : ?>
				<?php CleanLogin_Frontend::gcaptcha_script(); ?>
				<div class="cleanlogin-field">
					<div class="g-recaptcha" data-sitekey="<?php echo get_option( 'cl_gcaptcha_sitekey' ) ?>"></div>
				</div>
			<?php endif; ?>
		
			<input type="hidden" name="clean_login_wpnonce" value="<?php echo wp_create_nonce( 'clean_login_wpnonce' ); ?>">
            <?php if( isset( $_GET ) && !empty( $_GET['url'] ) ): ?><input type="hidden" name="clean_login_redirect" value="<?php echo esc_attr( $_GET['url'] ); ?>"><?php endif; ?>

			<?php do_action("cleanlogin_after_login_form"); ?>
		</fieldset>
		
		<fieldset>
			<input class="cleanlogin-field" type="submit" value="<?php echo __( 'Log in', 'clean-login' ); ?>" name="submit">
			<input type="hidden" name="action" value="login">
			
			<div class="cleanlogin-field cleanlogin-field-remember">
				<input type="checkbox" id="rememberme" name="rememberme" value="forever">
				<label for="rememberme"><?php echo __( 'Remember?', 'clean-login' ); ?></label>
			</div>
		</fieldset>

		<?php echo do_shortcode( apply_filters( 'cl_login_form', '') ); ?>

		<div class="cleanlogin-form-bottom">
			
            <?php if ( $restore_url != '' )
				echo "<a href='$restore_url' class='cleanlogin-form-pwd-link'>". __( 'Lost password?', 'clean-login' ) ."</a>";
			?>

			<?php if ( $register_url != '' && get_option( 'users_can_register' ) )
				echo "<a href='$register_url' class='cleanlogin-form-register-link'>". __( 'Register', 'clean-login' ) ."</a>";
			?>
						
		</div>
		
	</form>

</div>

<script>
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#pwd');

togglePassword.addEventListener('click', function (e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('bi-eye');
});
</script>