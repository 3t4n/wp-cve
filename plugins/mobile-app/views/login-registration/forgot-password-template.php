<?php
$input_valid_class = empty( $error_message ) ? '' : ' error';
$login_url         = Canvas::get_option( 'login_url', '/canvas-api/login' );

$back_to_login_txt = Canvas::get_option( 'lr_register_back_to_login_text', 'Login' );
$back_to_login_txt = apply_filters( 'canvas_form_back_to_login_text', $back_to_login_txt );
?>
<form name="canvas-fp-form" class="canvas-form" id="canvas-fp-form" method="post">

	<div class="canvas-login-email canvas-form-group">
		<p><label for="user_login">Email:</label></p>
		<input type="text" name="user_login" id="user_login"
			   class="input<?php echo $input_valid_class; ?>" value="" required>
	</div>

	<div class="canvas-fp-submit canvas-form-group" id="submit-container">
		<input type="hidden" name="canvas_fp_submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Submit">
		<div class="spinner-loading hide">
			<?php require CANVAS_DIR . 'views/login-registration/parts/loading-icon.php'; ?>
		</div>
	</div>

	<div class="canvas-custom-action">
		<p><a id="login-link" href="<?php echo $login_url; ?>?app=true"><?php echo $back_to_login_txt; ?></a></p>
	</div>

</form>
