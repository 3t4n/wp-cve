<?php

/**
 * Password Reset Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-password-reset-form acadp-require-js" data-script="password-reset">
	<form action="<?php echo esc_url( site_url( 'wp-login.php?action=resetpass' ) ); ?>" id="acadp-password-reset-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" autocomplete="off" role="form" data-js-enabled="false">
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
			<?php echo wp_get_password_hint(); ?>
		</div>

		<input type="hidden" id="acadp-user-login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
		
		<div id="acadp-form-group-pass1" class="acadp-form-group acadp-form-group-password">
			<label for="acadp-form-control-pass1" class="acadp-form-label">
				<?php esc_html_e( 'New Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass1" id="acadp-form-control-pass1" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required aria-describedby="acadp-form-error-pass1" />
			
			<div hidden id="acadp-form-error-pass1" class="acadp-form-error"></div>
		</div>

		<div id="acadp-form-group-pass2" class="acadp-form-group acadp-form-group-password">
			<label for="acadp-form-control-pass2" class="acadp-form-label">
				<?php esc_html_e( 'Repeat New Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass2" id="acadp-form-control-pass2" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required aria-describedby="acadp-form-error-pass2" />
			
			<div hidden id="acadp-form-error-pass2" class="acadp-form-error"></div>
		</div>

		<?php if ( $attributes['redirect'] ) : ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $attributes['redirect'] ); ?>" />
		<?php endif; ?>
				
		<button type="submit" name="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
			<?php esc_attr_e( 'Reset Password', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
	</form>
</div>