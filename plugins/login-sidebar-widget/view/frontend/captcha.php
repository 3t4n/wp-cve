<div class="log-form-group">
	<label for="user_captcha"><?php _e('Captcha','login-sidebar-widget');?> </label>
	<img src="<?php echo plugins_url( LSW_DIR_NAME.'/captcha/captcha.php');?>" alt="code" class="captcha">
	<?php Form_Class::form_input('text','user_captcha','user_captcha','','','','','','','',true,'','',true,__('Please enter captcha','login-sidebar-widget'),apply_filters( 'lwws_user_captcha_field', '' ));?>
</div>