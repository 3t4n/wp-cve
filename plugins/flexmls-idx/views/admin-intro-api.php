<?php

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

$fmc_settings = get_option( 'fmc_settings' );
$SparkAPI = new \SparkAPI\Core();
$auth_token = $SparkAPI->generate_auth_token();

updateUserOptions($auth_token);

?>
<h3>Activate Your Key & Secret<?php if( $auth_token ): ?> <span class="fmc-admin-badge fmc-admin-badge-success">Connected</span><?php endif; ?></h3>
<p>Enter your Flexmls&reg; Key & Secret credentials below to connect your website, then click Save Credentials. If entered correctly, you will see a green button above that says Connected:</p>
<form action="<?php echo admin_url( 'admin.php?page=fmc_admin_intro&tab=api' ); ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="api_key">Key</label>
				</th>
				<td>
					<input type="text" class="regular-text" name="fmc_settings[api_key]" id="api_key" value="<?php echo $fmc_settings[ 'api_key' ]; ?>" required>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="api_secret">Secret</label>
				</th>
				<td>
					<input type="<?php if( $auth_token ): ?>password<?php else: ?>text<?php endif; ?>" class="regular-text" name="fmc_settings[api_secret]" id="api_secret" value="<?php echo $fmc_settings[ 'api_secret' ]; ?>" required>
				</td>
			</tr>
		</tbody>
	</table>
	<p><?php wp_nonce_field( 'update_api_credentials_action', 'update_api_credentials_nonce' ); ?><button type="submit" class="button-primary">Save Credentials</button></p>
</form>
<hr />
<div class="key-content">
	<h3>Don't have a Key & Secret?</h3>
	<p>Fill out this <a href="https://zfrmz.com/04GYW4vcWPQa5mXnyYVk" target="_blank">quick form</a> or call 866-320-9977 to talk with an IDX Specialist.</p>
	<div class="license-section">
		<h3 class="bg-blue-head">License Information</h3>
		<p>
			<?php
				global $wp_version;
				$options = get_option( 'fmc_settings' );

				$active_theme = wp_get_theme();
				$active_plugins = get_plugins();

				$known_plugin_conflicts = array(
					'screencastcom-video-embedder/screencast.php', // Screencast Video Embedder, JS syntax errors in 0.4.4 breaks all pages
				);

				$known_plugin_conflicts_tag = ' &ndash; <span class="flexmls-known-plugin-conflict-tag">Known issues</span>';

				$system = new \SparkAPI\System();
				$api_system_info = $system->get_system_info();

				$license_info = array();
				if( $api_system_info ){
					$license_info[] = '<strong>Licensed to:</strong> ' . $api_system_info[ 'Name' ];
					$license_info[] = '<strong>Member of:</strong> ' . $api_system_info[ 'Mls' ];
					if( $system->is_not_blank_or_restricted( $api_system_info[ 'Office' ] ) ){
						$license_info[] = '<strong>Office:</strong> ' . $api_system_info[ 'Office' ];
					}
				} else {
					$license_info[] = '<strong>Licensed to:</strong> Unlicensed/Unknown (Not connected)';
				}
				$license_info[] = '<strong>API Key:</strong> ' . ( !empty( $options[ 'api_key' ] ) ? '<code>' . $options[ 'api_key' ] . '</code>' : 'Not Set' );
				$license_info[] = '<strong>OAuth Client ID:</strong> ' . ( !empty( $options[ 'oauth_key' ] ) ? '<code>' . $options[ 'oauth_key' ] . '</code>' : 'Not Set' );
				echo implode( '<br />', $license_info );
			?>
		</p>
	</div>
</div>
