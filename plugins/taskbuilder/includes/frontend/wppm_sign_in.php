<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="col-sm-12" style="margin-bottom:20px;">
	<?php 
	do_action('wppm_before_signin_module');
	?>
	<span class="wppm-form-signin-heading"><?php echo esc_html_e('Please sign in','taskbuilder')?></span>		
	<form id="frm_wppm_sign_in" action="javascript:wppm_sign_in_frm();" method="post" style="margin-bottom:5px;">
		<p id="wppm_message_login" class="bg-success" style="display:none;"></p>
		<label class="sr-only"><?php echo esc_html_e('Username or email','taskbuilder')?></label>
		<input id="wppm-inputEmail" name="username" class="form-control" placeholder="<?php echo esc_attr__('Username or email','taskbuilder')?>" required="" autofocus="" autocomplete="off" type="text" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : '';?>">
		<label for="wppm-inputPassword" class="sr-only"><?php echo esc_html_e('Password','taskbuilder')?></label>
		<input id="wppm-inputPassword" name="password" class="form-control" placeholder="<?php echo esc_attr__('Password','taskbuilder')?>" required="" autocomplete="off" type="password">
		<div class="checkbox">
			<label>
				<input name="remember" value="remember-me" type="checkbox"><span id="wppm_remember"><?php echo esc_html_e('Remember me','taskbuilder')?></span>
			</label>
		</div>
		<input type="hidden" name="action" value="wppm_set_user_login" />
		<input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce())?>" />
		<button id="wppm_sign_in_btn" class="btn btn-lg btn-block" type="submit"><?php echo esc_html_e('Sign In','taskbuilder')?></button>
	</form>
</div>