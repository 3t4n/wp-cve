<div class="wrap formstack">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'formstack_plugin' );

		do_settings_sections( 'formstack_plugin_do_options' );

		submit_button( esc_attr__( 'Save Changes', 'formstack' ) ); ?>
	</form>

	<?php

	$settings      = get_option( 'formstack_settings', '' );
	$client_id     = ( isset( $settings['client_id'] ) ) ? $settings['client_id'] : '';
	$client_secret = ( isset( $settings['client_secret'] ) ) ? $settings['client_secret'] : '';
	$oauth_code    = get_option( 'formstack_oauth2_code', '' );

	if ( $client_id && $client_secret ) {
		?>

		<h3 class="formstack-status">
			<?php esc_html_e( 'Status', 'formstack' ); ?>
		</h3>
		<?php
			$formstack_api = new Formstack_API_V2(
				array(
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
			        'redirect_uri'  => admin_url( 'admin.php?page=Formstack' ),
			        'code'          => $oauth_code
				)
			);
			// Only try to fetch tokens if we have an auth code set.
			if ( $oauth_code ) {
				// Sets up our tokens. Should not do much else if we already have some.
				$formstack_api->get_fresh_token();
			}

			// Let's display only if we need to.
			if ( empty( $oauth_code ) || ! $formstack_api->has_tokens() ) {
				echo $formstack_api->get_authentication_button();
			}

			// Needs to be up here so that any errors can be properly set in time.
			$form_count = $formstack_api->get_form_count();

			$formstack_api->display_errors();

			if ( $oauth_code ) {
				?>
				<ul>
					<?php
					#$status       = $formstack_api->is_token_expired();
					/*$refresh_link = '';
					if ( '' === $status ) {
						$status = esc_html__( 'none set', 'formstack' );
					} else {
						$status = ( $status ) ? esc_html__( 'Expired', 'formstack' ) : esc_html__( 'Valid', 'formstack' );
					}*/

					/*printf(
						sprintf(
							'<li>%s</li>',
							sprintf(
								__( 'Token status: %s', 'formstack' ),
								sprintf(
									'<strong>%s</strong>',
									$status
								)
							)
						)
					);*/
					printf(
						sprintf(
							'<li>%s</li>',
							sprintf(
								__( 'Available Forms: %s', 'formstack' ),
								sprintf(
									'<strong>%d</strong>',
									esc_html( $form_count )
								)
							)
						)
					);
					printf(
						'<li>%s</li>',
						sprintf(
							__( 'PHP Version: %s', 'formstack' ),
							sprintf(
								'<strong>%s</strong>',
								esc_html( PHP_VERSION )
							)
						)
					);
					?>
				</ul>
				<p><a href="<?php
					echo
					esc_url( add_query_arg(
						array(
							'clear_formstack_cache' => 'true',
						),
						admin_url( 'admin.php?page=Formstack' )
					) ); ?>">
						<?php esc_html_e( 'Refresh Formstack form cache', 'formstack' ); ?>
					</a></p>
				<p><a href="<?php
					echo
					esc_url( add_query_arg(
						array(
							'clear_formstack_tokens' => 'true',
						),
						admin_url( 'admin.php?page=Formstack' )
					) ); ?>">
						<?php esc_html_e( 'Clear Formstack tokens', 'formstack' ); ?>
					</a></p>
				<?php
			}
	} // End if().
	?>
</div>
