<?php
	if ( ! defined( 'ABSPATH' ) ) exit; 
	$current_user = wp_get_current_user();
?>

<?php do_action("cleanlogin_before_login_edit_form_container"); ?>

<div class="cleanlogin-container cleanlogin-full-width">
	<form class="cleanlogin-form" method="post" action="#" onsubmit="submit.disabled = true; return true;">

		<h4><?php echo __( 'General information', 'clean-login' ); ?></h4>

		<fieldset>
			<?php do_action("cleanlogin_before_login_edit_form"); ?>
			<div class="cleanlogin-field">
				<label for="first_name"><?php echo __( 'First name', 'clean-login' ); ?></label>
				<input type="text" id="first_name" name="first_name" value="<?php echo $current_user->user_firstname; ?>">
			</div>
			
			<div class="cleanlogin-field">
				<label for="last_name"><?php echo __( 'Last name', 'clean-login' ); ?></label>
				<input type="text" id="last_name" name="last_name" value="<?php echo $current_user->user_lastname; ?>">
			</div>
			
			<?php if( $param['show_email'] == "true" ): ?>
			<div class="cleanlogin-field">
				<label for="email"><?php echo __( 'E-mail', 'clean-login' ); ?></label>
				<input type="text" id="email" name="email" value="<?php echo $current_user->user_email; ?>">
			</div>
			<?php else: ?>
				<input type="hidden" name="email" value="<?php echo $current_user->user_email; ?>">
			<?php endif; ?>
			
			<input type="hidden" name="clean_login_wpnonce" value="<?php echo wp_create_nonce( 'clean_login_wpnonce' ); ?>">

			<?php do_action("cleanlogin_after_login_edit_form"); ?>
		</fieldset>

		<h4><?php echo __( 'Change password', 'clean-login' ); ?></h4>
		
		<p class="cleanlogin-form-description"><?php echo __( "If you would like to change the password type a new one. Otherwise leave this blank.", 'clean-login' ); ?></p>
		
		<fieldset>
		
			<div class="cleanlogin-field">
				<label for="pass1"><?php echo __( 'New password', 'clean-login' ); ?></label>
				<input type="password" id="pass1" name="pass1" value="" autocomplete="off">
				<i class="bi bi-eye-slash" id="togglePassword"></i>
			</div>
			
			<div class="cleanlogin-field">
				<label for="pass2"><?php echo __( 'Confirm password', 'clean-login' ); ?></label>
				<input type="password" id="pass2" name="pass2" value="" autocomplete="off">
				<i class="bi bi-eye-slash" id="togglePassword2"></i>
			</div>
		
		</fieldset>
		
		<div>	
			<input type="submit" value="<?php echo __( 'Update profile', 'clean-login' ); ?>" name="submit">
			<input type="hidden" name="action" value="edit">		
		</div>

	</form>
</div>

<script>
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#pass1');
const togglePassword2 = document.querySelector('#togglePassword2');
const password2 = document.querySelector('#pass2');

togglePassword.addEventListener('click', function (e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('bi-eye');
});

togglePassword2.addEventListener('click', function (e) {
    const type2 = password2.getAttribute('type') === 'password' ? 'text' : 'password';
    password2.setAttribute('type', type2);
    this.classList.toggle('bi-eye');
});
</script>