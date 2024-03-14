<?php

/**
 * Login Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-login-form acadp-require-js" data-script="login-form">
	<form action="<?php echo esc_url( wp_login_url() ); ?>" id="acadp-login-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" role="form" data-js-enabled="false">
		<!-- Show errors if there are any -->
		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<div class="acadp-alert acadp-alert-error" role="alert">
				<?php 
				foreach ( $attributes['errors'] as $error ) {
					printf( '<div class="acadp-error">%s</div>', wp_kses_post( $error ) );
				}
				?>
			</div>
		<?php endif; ?>

		<!-- Show logged out message if user just logged out -->
		<?php if ( $attributes['logged_out'] ) : ?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<?php esc_html_e( 'You have signed out. Would you like to login again?', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $attributes['registered'] ) : ?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<?php
					$message = sprintf(
						__( 'You have successfully registered to <strong>%s</strong>. We have emailed your account details to the email address you entered.', 'advanced-classifieds-and-directory-pro' ),
						get_bloginfo( 'name' )
					);

					echo wp_kses_post( $message );
				?>
			</div>
		<?php endif; ?>

		<?php if ( $attributes['lost_password_sent'] ) : ?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<?php esc_html_e( 'Check your email for a link to reset your password.', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $attributes['password_updated'] ) : ?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<?php esc_html_e( 'Your password has been changed. You can login now.', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		<?php endif; ?>

		<div id="acadp-form-group-login" class="acadp-form-group">
			<label for="acadp-form-control-login" class="acadp-form-label">
				<?php esc_html_e( 'Username or E-mail', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="text" name="log" id="acadp-form-control-login" class="acadp-form-control acadp-form-input acadp-form-validate" required aria-describedby="acadp-form-error-login" />

			<div hidden id="acadp-form-error-login" class="acadp-form-error"></div>
		</div>

		<div id="acadp-form-group-pass" class="acadp-form-group">
			<label for="acadp-form-control-pass" class="acadp-form-label">
				<?php esc_html_e( 'Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pwd" id="acadp-form-control-pass" class="acadp-form-control acadp-form-input acadp-form-validate" required aria-describedby="acadp-form-error-pass" />

			<div hidden id="acadp-form-error-pass" class="acadp-form-error"></div>
		</div>
	
		<div id="acadp-form-group-rememberme" class="acadp-form-group">
			<label class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="rememberme" class="acadp-form-control acadp-form-checkbox" value="forever">
				<?php esc_html_e( 'Remember Me', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>

		<!-- Hook for developers to add new fields -->
		<?php do_action( 'acadp_login_form_fields' ); ?>

		<?php if ( $attributes['redirect'] ) : ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $attributes['redirect'] ); ?>" />
		<?php endif; ?>
		
		<button type="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
			<?php esc_attr_e( 'Login', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
		
		<div class="acadp-flex acadp-flex-col acadp-gap-1">
			<div class="acadp-forgot-password">  
				<a href="<?php echo esc_url( $attributes['forgot_password_url'] ); ?>" class="acadp-underline">
					<?php esc_html_e( 'Forgot your password?', 'advanced-classifieds-and-directory-pro' ); ?>
				</a>
			</div>

			<?php if ( get_option( 'users_can_register' ) ) : ?>
				<div class="acadp-register-account">  
					<a href="<?php echo esc_url( $attributes['register_url'] ); ?>" class="acadp-underline">
						<?php esc_html_e( 'Create an account', 'advanced-classifieds-and-directory-pro' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>