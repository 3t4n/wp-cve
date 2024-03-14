<div class="wrap hizzle-recaptcha-settings">

	<?php

		// Display the title.
		printf(
			'<h1>%s</h1>',
			esc_html( get_admin_page_title() )
		);

		// Fire a hook before printing the settings page.
		do_action( 'hizzle_recaptcha_settings_page_top' );

		if ( false === $saved_settings ) {
			printf(
				'<div class="error is-dismissible hizzle-recaptcha-notice"><p>%s</p></div>',
				esc_html__( 'Could not save your settings. Please try again.', 'hizzle-recaptcha' )
			);
		}

		if ( true === $saved_settings ) {
			printf(
				'<div class="notice notice-success is-dismissible hizzle-recaptcha-notice"><p>%s</p></div>',
				esc_html__( 'Your settings have been saved.', 'hizzle-recaptcha' )
			);
		}

	?>

	<style>
		.hizzle-recaptcha-field-wrapper {
			display: flex;
			margin-top: 16px;
			margin-bottom: 32px;
			text-align: left;
		}
		.hizzle-recaptcha-label {
			max-width: 200px;
			flex: 1 0 200px;
			font-weight: 600;
		}
		.hizzle-recaptcha_settings-wrapper {
			flex: 1 0 0;
		}
		.notice:not(.hizzle-recaptcha-notice),
		div.error:not(.hizzle-recaptcha-notice),
		div.updated:not(.hizzle-recaptcha-notice) {
			display: none!important;
		}
	</style>
	<form method="POST" class="hizzle-recaptcha-main-settings-form">
		<?php wp_nonce_field( 'hizzle-recaptcha', 'hizzle-recaptcha' ); ?>
		<p>
			<?php esc_html_e( 'Currently, we only support site keys created with version 2 of Google reCAPTCHA.', 'hizzle-recaptcha' ); ?>
			<a target="_blank" href="http://www.google.com/recaptcha/admin"><?php esc_html_e( 'Get your site key here.', 'hizzle-recaptcha' ); ?></a>
		</p>
		<?php foreach ( $settings as $setting_id => $args ) : ?>

			<label class="hizzle-recaptcha-field-wrapper">
				<span class="hizzle-recaptcha-label"><?php echo esc_html( $args['label'] ); ?></span>
				<div class="hizzle-recaptcha_settings-wrapper">
					<?php if ( 'text' === $args['type'] ) : ?>
						<input
							type="text"
							class="regular-text"
							name="hizzle_recaptcha[<?php echo esc_attr( $setting_id ); ?>]"
							value="<?php echo esc_attr( hizzle_recaptcha_get_option( $setting_id, $args['default'] ) ); ?>"
						>
					<?php endif; ?>
					<?php if ( 'checkbox' === $args['type'] ) : ?>
						<input
							type="checkbox"
							name="hizzle_recaptcha[<?php echo esc_attr( $setting_id ); ?>]"
							<?php checked( null !== hizzle_recaptcha_get_option( $setting_id, null ) ); ?>
							value="1"
						>&nbsp;<span><?php echo wp_kses_post( $args['label2'] ); ?></span>
					<?php endif; ?>
					<?php if ( 'select' === $args['type'] ) : ?>
						<select name="hizzle_recaptcha[<?php echo esc_attr( $setting_id ); ?>]">
							<?php foreach ( $args['options'] as $option_value => $option_label ) : ?>
								<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $option_value, hizzle_recaptcha_get_option( $setting_id, $args['default'] ) ); ?>><?php echo esc_html( $option_label ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
					<?php do_action( 'hizzle_recaptcha_settings_display_' . $args['type'], $args, $setting_id ); ?>
					<?php
						if ( ! empty( $args['desc'] ) ) {
							printf(
								'<p class="description">%s</p>',
								wp_kses_post( $args['desc'] )
							);
						}
					?>
				</div>
			</label>

		<?php endforeach; ?>

		<div class="hizzle-recaptcha-field-wrapper">
			<span class="hizzle-recaptcha-label"><?php esc_html_e( 'Show reCAPTCHA on:', 'hizzle-recaptcha' ); ?></span>
			<div class="hizzle-recaptcha_settings-wrapper">
				<?php foreach ( $available_integrations as $integration_id => $integration ) : ?>
				<?php
					printf(
						'<label style="margin-bottom: 10px; display: block;"><input type="checkbox" name="hizzle_recaptcha[enabled_integrations][]" value="%s" %s> <span>%s</span></label>',
						esc_attr( $integration_id ),
						checked( in_array( $integration_id, $enabled_integrations, true ), true, false ),
						esc_html( $integration )
					);
				?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php submit_button(); ?>

	</form>
	<?php do_action( 'hizzle_recaptcha_settings_page_bottom' ); ?>
</div>
