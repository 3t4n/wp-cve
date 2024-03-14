<p>
    <label for="captcha"><?php _e('Captcha','wp-register-profile-with-shortcode');?>
    <img src="<?php echo plugins_url( WPRPWS_DIR_NAME . '/captcha/captcha_admin.php' );?>" id="captcha" style="float:right;"><br>
    <input type="text" name="admin_captcha" class="input" required size="20" autocomplete="off" <?php do_action( 'wprp_admin_captcha_field' );?>/>
    </label>
</p>