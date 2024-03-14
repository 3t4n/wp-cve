<div class="forgot-pass-form">
        
<?php do_action('lwws_before_forgot_password_form_start');?>

<?php $this->error_message();?>

<form name="forgot" id="forgot" method="post" action="" autocomplete="off" <?php do_action( 'lwws_forgot_password_form_tag' );?>>
<?php wp_nonce_field( 'login_widget_action', 'login_widget_field' ); ?>
<input type="hidden" name="option" value="ap_forgot_pass" />
	<div class="forgot-pass-form-group">
	<label for="userusername"><?php _e('Email','login-sidebar-widget');?> </label>
	<input type="email" name="userusername" id="userusername" title="<?php _e('Please enter correct email','login-sidebar-widget');?>" <?php do_action( 'lwws_fp_userusername_field' );?> required/>
	</div>
	
	<div class="forgot-pass-form-group"><input name="forgot" type="submit" value="<?php _e('Submit','login-sidebar-widget');?>" <?php do_action( 'lwws_forgot_password_form_submit_tag' );?>/></div>
	
	<div class="forgot-pass-form-group">
		<div class="forgot-text">
			<?php _e('Please enter your email. The password reset link will be provided in your email.','login-sidebar-widget');?>
		</div>
	</div>
</form>

<?php do_action('lwws_after_forgot_password_form_end');?>

</div>