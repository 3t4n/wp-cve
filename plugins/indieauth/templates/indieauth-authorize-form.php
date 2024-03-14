<?php
$login_errors = new WP_Error();
login_header(
	/* translators: 1. Client Name */
	sprintf( __( 'Authorize %1$s', 'indieauth' ), empty( $client_name ) ? $client_id : $client_name ),
	'',
	$login_errors
);
?>
<form method="post" action="<?php echo esc_url( $url ); ?>">
	<div class="client-info">
		<?php if ( ! empty( $client_icon ) ) { ?>
			<img src="<?php echo esc_url( $client_icon ); ?>" />
		<?php } ?>
		<strong>
		<?php
			echo wp_kses(
				/* translators: 1. Client */
				sprintf( __( '%1$s wants to access your site.', 'indieauth' ), $client ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			);
			?>
		</strong>
		</div>
		<div class="user-info">
		<?php

			echo get_avatar( $current_user->ID, '48' );
			printf(
				/* translators: 1. User Display Name 2. User Nice Name */
				esc_html__( 'The app will use credentials of %1$s (%2$s). You can revoke access at any time.', 'indieauth' ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
				esc_html( $current_user->user_nicename )
			);
			?>

	</div>

	<?php require plugin_dir_path( __FILE__ ) . 'indieauth-notices.php'; ?>
	<div class="scope-info">
		<?php esc_html_e( 'Below select the privileges you would like to grant the application.', 'indieauth' ); ?>
		<ul>
		<?php self::scope_list( $scopes ); ?>
		</ul>
	</div>

	<div class="notice notice-info message expiration">
	<?php
		$expiration = (int) get_option( 'indieauth_expires_in' );
	if ( 0 !== $expiration ) {
		printf(
			/* translators: 1. human time difference */
			'⌛ ' . esc_html__( 'The client will have access for %1$s.', 'indieauth' ),
			esc_html( human_time_diff( time(), time() + $expiration ) )
		);
	}
	?>
	</div>
	<p class="submit">
	<?php
		// Hook to allow adding to form
		do_action( 'indieauth_authorization_form', $current_user->user_id, $client_id );
	?>
		<input type="hidden" name="client_id" value="<?php echo esc_url( $client_id ); ?>" />
		<input type="hidden" name="redirect_uri" value="<?php echo esc_url( $redirect_uri ); ?>" />
		<input type="hidden" name="state" value="<?php echo esc_attr( $state ); ?>" />
		<input type="hidden" name="me" value="<?php echo esc_url( $me ); ?>" />
		<input type="hidden" name="response_type" value="<?php echo esc_attr( $response_type ); ?>" />
		<input type="hidden" name="code_challenge" value="<?php echo esc_attr( $code_challenge ); ?>" />
		<input type="hidden" name="code_challenge_method" value="<?php echo esc_attr( $code_challenge_method ); ?>" />

		<?php if ( ! is_null( $code_challenge ) ) { ?>
			<input type="hidden" name="code_challenge" value="<?php echo esc_attr( $code_challenge ); ?>" />
			<input type="hidden" name="code_challenge_method" value="<?php echo esc_attr( $code_challenge_method ); ?>" />
		<?php } ?>
		<button name="wp-submit" value="authorize" class="button button-primary button-large"><?php esc_html_e( 'Approve', 'indieauth' ); ?></button>
		<a name="wp-submit" value="cancel" class="button button-large" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Cancel', 'indieauth' ); ?></a>
	</p>
</form>
<?php /* translators: 1. Redirect URI */ ?>
<div class="message redirect-info"><?php printf( esc_html__( 'You will be redirected to %1$s after approving this application.', 'indieauth' ), '<code>' . esc_url( $redirect_uri ) . '</code>' ); ?></div>
