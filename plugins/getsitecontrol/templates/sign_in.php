<?php
/**
 * @var $sign_up_link string
 * @var $data array
 * @var $options array
 */
?>
<div class="wrap getsitecontrol" data-auth="">
	<div class="block-login-form">
		<section class="sign-up-form">
			<h1>Log in</h1>
			<div class="form-social-footer">
				<div class="social-login">
					<a href="<?php echo esc_url( $options['google_social_link'] ); ?>" class=" social-login-button social-login-google">
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/google_icon_back.svg'; ?>">
						<span>Log in with Google →</span>
					</a>
				</div>
			</div>
			<p class="form-connect-with">OR</p>
			<div class="form-contents">
				<form action="<?php echo esc_url( $options['api_url'] ); ?>" method="post" data-type="signin" novalidate="" data-form-validate="">
					<fieldset class="form-group">
						<div class="form-wrapper">
							<input class="form-control" title="Enter your email" required="" maxlength="200" placeholder="Email" type="email" name="email" value="<?php echo ! empty( $data['email'] ) ? esc_attr( $data['email'] ) : ''; ?>">
							<span class="form-validation-message"></span>
						</div>
						<div class="form-wrapper">
							<input class="form-control" title="Enter your password" required="" maxlength="200" placeholder="Password" type="password" name="password">
							<span class="form-validation-message"></span>
						</div>
					</fieldset>
					<button class="button-submit"
					data-sending-text="Logging in..."
					data-text="Log in with email →" type="submit">Log in with email →</button>
				</form>
			<div class="form-validation-message form-validation-general"></div>
			</div>

			<div class="form-legal">
			    Forgot password? <a href="https://getsitecontrol.com/reset/">Reset password</a><br>
			    <br>
                New user? <a href="<?php echo esc_url( $sign_up_link ); ?>" tabindex="6">Create an account</a>
			</div>

		</section>
	</div>
</div>


<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
