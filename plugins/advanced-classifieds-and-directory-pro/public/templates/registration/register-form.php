<?php

/**
 * Register Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-register-form acadp-require-js" data-script="register-form">
	<form action="<?php echo esc_url( wp_registration_url() ); ?>" id="acadp-register-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" role="form" data-js-enabled="false">
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

		<div id="acadp-form-group-username" class="acadp-form-group">
			<label for="acadp-form-control-username" class="acadp-form-label">
				<?php esc_html_e( 'Username', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="text" name="username" id="acadp-form-control-username" class="acadp-form-control acadp-form-input acadp-form-validate" required aria-describedby="acadp-form-error-username" />
			
			<div class="acadp-form-description acadp-text-muted acadp-text-sm">
				<?php esc_html_e( 'Usernames cannot be changed.', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>

			<div hidden id="acadp-form-error-username" class="acadp-form-error"></div>
		</div>

		<div id="acadp-form-group-first_name" class="acadp-form-group">
			<label for="acadp-form-control-first_name" class="acadp-form-label">
				<?php esc_html_e( 'First Name', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>

			<input type="text" name="first_name" id="acadp-form-control-first_name" class="acadp-form-control acadp-form-input" />
		</div>

		<div id="acadp-form-group-last_name" class="acadp-form-group">
			<label for="acadp-form-control-last_name" class="acadp-form-label">
				<?php esc_html_e( 'Last Name', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>

			<input type="text" name="last_name" id="acadp-form-control-last_name" class="acadp-form-control acadp-form-input" />
		</div>

		<div id="acadp-form-group-email" class="acadp-form-group">
			<label for="acadp-form-control-email" class="acadp-form-label">
				<?php esc_html_e( 'E-mail Address', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="email" name="email" id="acadp-form-control-email" class="acadp-form-control acadp-form-input acadp-form-validate" required aria-describedby="acadp-form-error-email" />
			
			<div hidden id="acadp-form-error-email" class="acadp-form-error"></div>
		</div>
		
		<div id="acadp-form-group-pass1" class="acadp-form-group acadp-form-group-password">
			<label for="acadp-form-control-pass1" class="acadp-form-label">
				<?php esc_html_e( 'Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass1" id="acadp-form-control-pass1" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required aria-describedby="acadp-form-error-pass1" />
			
			<div hidden id="acadp-form-error-pass1" class="acadp-form-error"></div>
		</div>

		<div id="acadp-form-group-pass2" class="acadp-form-group acadp-form-group-password">
			<label for="acadp-form-control-pass2" class="acadp-form-label">
				<?php esc_html_e( 'Confirm Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass2" id="acadp-form-control-pass2" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required aria-describedby="acadp-form-error-pass2" />
			
			<div hidden id="acadp-form-error-pass2" class="acadp-form-error"></div>
		</div>

		<!-- Hook for developers to add new fields -->
		<?php do_action( 'acadp_register_form_fields' ); ?>

		<?php
		// Terms and Conditions
		include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/terms-and-conditions.php' );
		?>

		<?php 
		// Privacy Policy
		include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/privacy-policy.php' );
		?>	

		<div class="acadp-recaptcha">
			<div id="acadp-form-control-recaptcha"></div>
			<div hidden id="acadp-form-error-recaptcha" class="acadp-form-error"></div>
		</div>

		<?php if ( $attributes['redirect'] ) : ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $attributes['redirect'] ); ?>" />
		<?php endif; ?>
		
		<button type="submit" name="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
			<?php esc_attr_e( 'Register', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
	</form>
</div>
