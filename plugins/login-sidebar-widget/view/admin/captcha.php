
<p><img src="<?php echo plugins_url( LSW_DIR_NAME.'/captcha/captcha.php' );?>" class="captcha" alt="code"></p>
<p>
    <label for="admin_captcha"><?php _e('Captcha','login-sidebar-widget');?></label><br>
    <?php Form_Class::form_input('text','admin_captcha','admin_captcha','','input','','','','20','',true,'','',true,'',apply_filters( 'lwws_admin_captcha_field', '' ));?>
</p>