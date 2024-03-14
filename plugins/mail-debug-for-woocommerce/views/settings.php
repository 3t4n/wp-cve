<div class="wrap mdwc-settings-page">
	<form method="post" action="options.php">

		<?php require MDWC_VIEWS_PATH . 'settings-start.php'; ?>

		<?php settings_fields( 'mdwc-settings' ); ?>
		<?php do_settings_sections( 'mdwc-settings' );

		$all_emails_to   = get_option( 'mdwc_all_emails_to', '' );
		$menu_in_wp_menu = 'yes' === get_option( 'mdwc_show_in_wp_menu', 'yes' );
		?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="mdwc_debug_enabled"><?php _e( 'Debug Enabled', 'mail-debug-for-woocommerce' ) ?></label>
				</th>
				<td>
					<input type="checkbox" id="mdwc_debug_enabled" name="mdwc_debug_enabled" value="yes" <?php checked( mdwc_is_debug_enabled() ) ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="mdwc_all_emails_to"><?php _e( 'Send all emails to', 'mail-debug-for-woocommerce' ) ?></label>
				</th>
				<td>
					<input type="text" id="mdwc_all_emails_to" name="mdwc_all_emails_to" value="<?php echo $all_emails_to ?>"/>
					<span class="description"><?php _e( 'Send all emails to the specified email address. Leave empty to disable', 'mail-debug-for-woocommerce' ) ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label><?php _e( 'Move plugin menus', 'mail-debug-for-woocommerce' ) ?></label>
				</th>
				<td>
					<?php
					$options = array(
						'show_in_wp_menu' => array(
							'label' => __( 'Move into the WordPress admin menu', 'mail-debug-for-woocommerce' ),
							'url'   => wp_nonce_url( add_query_arg( array( 'wcmd_show_in_wp_menu' => 'yes' ) ), 'wcmd_move_menu' ),
						),
						'show_in_tools'   => array(
							'label' => __( "Move <strong>Mail Debug</strong> into <strong>Tools</strong> and this settings page into <strong>Settings > Mail Debug</strong>", 'mail-debug-for-woocommerce' ),
							'url'   => wp_nonce_url( add_query_arg( array( 'wcmd_show_in_wp_menu' => 'no' ) ), 'wcmd_move_menu' ),
						),
					);
					$move    = ! $menu_in_wp_menu ? $options['show_in_wp_menu'] : $options['show_in_tools'];
					?>
					<a href="<?php echo $move['url']; ?>" class="button button-primary"><?php echo $move['label']; ?></a>

				</td>
			</tr>
		</table>


		<?php submit_button(); ?>
		<?php require MDWC_VIEWS_PATH . 'settings-end.php'; ?>
	</form>
</div>
