<?php

/**
 * User Account Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-user-account-form acadp-require-js" data-script="user-account">
	<form action="<?php echo esc_url( acadp_get_user_account_page_link() ); ?>" id="acadp-user-account-form" class="acadp-flex acadp-flex-col acadp-gap-6" method="post" role="form" data-js-enabled="false">
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
		
		<?php if ( $attributes['account_updated'] ) : ?>
			<div class="acadp-alert acadp-alert-info" role="alert">
				<?php esc_html_e( 'Your account has been updated!', 'advanced-classifieds-and-directory-pro' ); ?>
			</div>
		<?php endif; ?>

		<div id="acadp-form-group-username" class="acadp-form-group">
			<dt class="acadp-m-0 acadp-p-0">
				<?php esc_html_e( 'Username', 'advanced-classifieds-and-directory-pro' ); ?>
			</dt>

			<dd class="acadp-field-value acadp-m-0 acadp-p-0">
				<?php echo esc_html( $attributes['username'] ); ?>
			</dd>
		</div>

		<div id="acadp-form-group-first_name" class="acadp-form-group">
			<label for="acadp-form-control-first_name" class="acadp-form-label">
				<?php esc_html_e( 'First Name', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>

			<input type="text" name="first_name" id="acadp-form-control-first_name" class="acadp-form-control acadp-form-input" value="<?php echo esc_attr( $attributes['first_name'] ); ?>" />
		</div>

		<div id="acadp-form-group-last_name" class="acadp-form-group">
			<label for="acadp-form-control-last_name" class="acadp-form-label">
				<?php esc_html_e( 'Last Name', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>

			<input type="text" name="last_name" id="acadp-form-control-last_name" class="acadp-form-control acadp-form-input" value="<?php echo esc_attr( $attributes['last_name'] ); ?>" />
		</div>

		<div id="acadp-form-group-email" class="acadp-form-group">
			<label for="acadp-form-control-email" class="acadp-form-label">
				<?php esc_html_e( 'E-mail Address', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="email" name="email" id="acadp-form-control-email" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php echo esc_attr( $attributes['email'] ); ?>" required aria-describedby="acadp-form-error-email" />
			
			<div hidden id="acadp-form-error-email" class="acadp-form-error"></div>
		</div>
		
		<div id="acadp-form-group-change_password" class="acadp-form-group">
			<label class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="change_password" id="acadp-form-control-change_password" class="acadp-form-control acadp-form-checkbox" value="1">
				<?php esc_html_e( 'Change Password', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>

		<div id="acadp-form-group-pass1" class="acadp-form-group acadp-form-group-password" hidden>
			<label for="acadp-form-control-pass1" class="acadp-form-label">
				<?php esc_html_e( 'New Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass1" id="acadp-form-control-pass1" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required disabled aria-describedby="acadp-form-error-pass1" />
			
			<div hidden id="acadp-form-error-pass1" class="acadp-form-error"></div>
		</div>

		<div id="acadp-form-group-pass2" class="acadp-form-group acadp-form-group-password" hidden>
			<label for="acadp-form-control-pass2" class="acadp-form-label">
				<?php esc_html_e( 'Confirm Password', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-form-required" aria-hidden="true">*</span>
			</label>
			
			<input type="password" name="pass2" id="acadp-form-control-pass2" class="acadp-form-control acadp-form-input acadp-form-validate acadp-form-validate-password" autocomplete="off" required disabled aria-describedby="acadp-form-error-pass2" />
			
			<div hidden id="acadp-form-error-pass2" class="acadp-form-error"></div>
		</div>

		<?php wp_nonce_field( 'acadp_update_user_account', 'acadp_user_account_nonce' ); ?>
		
		<button type="submit" name="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
			<?php esc_attr_e( 'Update Account', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
	</form>
</div>