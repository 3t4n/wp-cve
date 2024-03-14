<?php

/**
 * Forgot Password Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-forgot-password-form acadp-require-js" data-script="forgot-password">
	<form action="<?php echo esc_url( wp_lostpassword_url() ); ?>" id="acadp-forgot-password-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" role="form" data-js-enabled="false">
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

		<div class="acadp-alert acadp-alert-info" role="alert">
			<?php esc_html_e( "Enter your Username or E-mail Address. We'll send you a link you can use to pick a new password.", 'advanced-classifieds-and-directory-pro' );	?>
		</div>

		<div id="acadp-form-group-user_login" class="acadp-form-group">
			<label for="acadp-form-control-user_login" class="acadp-form-label">
				<?php esc_html_e( 'Username or E-mail', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="text" name="user_login" id="acadp-form-control-user_login" class="acadp-form-control acadp-form-input acadp-form-validate" required aria-describedby="acadp-form-error-user_login" />

			<div hidden id="acadp-form-error-user_login" class="acadp-form-error"></div>
		</div>

		<!-- Hook for developers to add new fields -->
		<?php do_action( 'acadp_forgot_password_form_fields' ); ?>

		<?php if ( $attributes['redirect'] ) : ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $attributes['redirect'] ); ?>" />
		<?php endif; ?>
				
		<button type="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
			<?php esc_attr_e( 'Reset Password', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
	</form>
</div>