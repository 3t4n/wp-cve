
<div class="login-wrap">

	<div id="login-form" class="login-form <?php echo $wid_id; ?>">

		<?php do_action('lwws_before_login_form_start');?>

		<?php $this->error_message();?>

		<form name="login" id="login" method="post" action="" autocomplete="off" <?php do_action('lwws_login_form_tag');?>>

		<?php if ($nonce_check) {?>
		<?php wp_nonce_field('login_widget_action', 'login_widget_field');?>
		<?php }?>

		<input type="hidden" name="option" value="ap_user_login" />
		<input type="hidden" name="redirect" value="<?php echo $this->gen_redirect_url(); ?>" />
		<div class="log-form-group">
			<label for="userusername"><?php _e('Username', 'login-sidebar-widget');?> </label>
			<input type="text" name="userusername" id="userusername" title="<?php _e('Please enter username', 'login-sidebar-widget');?>" <?php do_action('lwws_userusername_field');?> required/>
		</div>
		<div class="log-form-group">
			<label for="userpassword"><?php _e('Password', 'login-sidebar-widget');?> </label>
			<input type="password" name="userpassword" id="userpassword" title="<?php _e('Please enter password', 'login-sidebar-widget');?>" <?php do_action('lwws_userpassword_field');?> required/>
		</div>

		<?php do_action('login_ap_form');?>

		<?php do_action('login_form');?>

		<?php $this->add_remember_me();?>

		<div class="login-submit"><input name="login" type="submit" value="<?php _e('Login', 'login-sidebar-widget');?>" <?php do_action('lwws_login_form_submit_tag');?>/></div>

		<div class="log-form-group extra-links">
			<?php $this->add_extra_links();?>
		</div>

		</form>

		<?php do_action('lwws_after_login_form_end');?>

		</div>

</div>

