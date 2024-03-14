<div id="password-reset-form" class="widecolumn">
	<?php if ( $attributes['show_title'] ) : ?>
		<h3><?php _e( 'Pick a New Password', 'DIRECTORYPRESS' ); ?></h3>
	<?php endif; ?>

	<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
		<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<?php foreach ( $attributes['errors'] as $error ) : ?>
				<p>
					<?php echo esc_html($error); ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>

		<p>
			<input type="password" name="pass1" id="pass1" class="input" size="20" value="" placeholder="<?php echo esc_html__('New password', 'DIRECTORYPRESS'); ?>" autocomplete="off" />
		</p>
		<p>
			<input type="password" name="pass2" id="pass2" class="input" size="20" value="" placeholder="<?php echo esc_html__('Repeat new password', 'DIRECTORYPRESS'); ?>" autocomplete="off" />
		</p>

		<p class="resetpass-submit">
			<input type="submit" name="submit" id="resetpass-button"
			       class="button" value="<?php _e( 'Reset Password', 'DIRECTORYPRESS' ); ?>" />
		</p>
	</form>
</div>