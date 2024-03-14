<form method="POST" action="">

	<input type="hidden" name="save_license_key" value="yes">
	<?php wp_nonce_field( 'save-license-key' ); ?>

	<div class="hpf-admin-settings-body wrap">

		<div class="hpf-admin-settings-license">

			<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

			<h1><?php echo __( 'License Information', 'houzezpropertyfeed' ); ?></h1>

			<div class="hpf-meta-box">

				<?php
					$button = __( 'Activate License Key', 'houzezpropertyfeed' );
					$hidden_field = 'activate';
					if ( isset($license_key_status['success']) && $license_key_status['success'] === true )
					{
						echo '<div class="notice notice-success inline"><p>' . __( 'License key active', 'houzezpropertyfeed' ) . '</p></div>';
						$button = __( 'Dectivate License Key', 'houzezpropertyfeed' );
						$hidden_field = 'deactivate';
					}
					else
					{
						if ( isset($license_key_status['success']) && $license_key_status['success'] !== true )
						{
							echo '<div class="notice notice-error inline"><p>' . $license_key_status['error'] . '</p></div>';
						}
					}
					echo '<input type="hidden" name="license_key_action" value="' . esc_attr($hidden_field) . '">';
					echo '<input type="hidden" name="current_license_key" value="' . ( isset($options['license_key']) ? esc_attr($options['license_key']) : '' ) . '">';
				?>

				<p>Enter your license key to unlock updates and benefit from additional functionality.</p>

				<table class="form-table">
					<tbody>
						<tr>
							<th><label for="license_key"><?php echo __( 'License Key', 'houzezpropertyfeed' ); ?></label></th>
							<td>
								<input type="text" name="license_key" id="license_key" value="<?php echo ( isset($options['license_key']) ? esc_attr($options['license_key']) : '' ); ?>"> 
								<p style="font-size:0.8em; margin-top:6px; color:#666">Your license key can be found and your subscription managed in the '<a href="http://houzezpropertyfeed.com/my-account" target="_blank">My Account</a>' section of the Houzez Property Feed website.</p>
							</td>
						</tr>
					</tbody>
				</table>

				<input type="submit" value="<?php echo esc_attr($button); ?>" class="button button-primary button-hero">

			</div>

		</div>

	</div>

</form>