<?php
/**
 * @var $sign_in_link string
 * @var $data array
 * @var $options array
 */
?>

<div class="wrap getsitecontrol" data-auth="">
	<div class="block-login-form">
		<section class="sign-up-form">
			<h1>Create account</h1>
			<div class="form-social-footer">
				<div class="social-login">
					<a href="<?php echo esc_url( $options['google_social_link'] ); ?>" class=" social-login-button social-login-google">
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/google_icon_back.svg'; ?>">
						<span>Sign up with Google →</span>
					</a>
				</div>
			</div>
			<p class="form-connect-with">OR</p>
			<div class="form-contents">
				<form action="<?php echo esc_url( $options['api_url'] ); ?>" method="post" data-type="signin" novalidate="" data-form-validate="">
					<fieldset class="form-group">
						<div class="form-wrapper">
							<input class="form-control disabled" hidden required=""
									   maxlength="200" name="site" type="url"
									   value="<?php echo ! empty( $data['site'] ) ? esc_attr( $data['site'] ) : ''; ?>">
						</div>
						<div class="form-wrapper">
							<input class="form-control" title="Enter your email" required="" maxlength="200" placeholder="Email" type="email" name="email" value="<?php echo ! empty( $data['email'] ) ? esc_attr( $data['email'] ) : ''; ?>">
							<span class="form-validation-message"></span>
						</div>
						<div class="form-wrapper">
							<input class="form-control" title="Password" required="" maxlength="200" placeholder="Password" type="password" name="password">
							<span class="form-validation-message"></span>
						</div>
					</fieldset>
					<button class="button-submit" data-sending-text="Signing up..."
					data-text="Sign up with email →"  type="submit">Sign up with email →</button>
				</form>
				<div class="form-validation-general form-validation-message"></div>
			</div>
			<div class="form-legal">
				By signing up, you agree to the<br/>
				<a target="_blank" href="https://getsitecontrol.com/terms/">Terms of service</a> and <a target="_blank" href="https://getsitecontrol.com/privacy/">Privacy policy</a>
				<br><br>
				Already have an account?&nbsp; <a tabindex="7" href="<?php echo esc_url( $sign_in_link ); ?>">Sign in</a>
			</div>

		</section>
	</div>
</div>


<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
