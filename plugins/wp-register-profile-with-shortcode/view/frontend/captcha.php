<div class="reg-captcha">
<img src="<?php echo plugins_url( WPRPWS_DIR_NAME . '/captcha/captcha.php' ); ?>" id="captcha">
<br /><a href="javascript:refreshCaptcha();"><?php _e('Reload Image','wp-register-profile-with-shortcode');?></a>
</div>
<script type="application/javascript">
function refreshCaptcha(){ document.getElementById('captcha').src = '<?php echo plugins_url( WPRPWS_DIR_NAME . '/captcha/captcha.php' );?>?rand='+Math.random(); }
</script>