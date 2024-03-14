<h1>BW Coming Soon Settings Page</h1>
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
	<?php
	wp_nonce_field( "bwcs_settings" );
	$bwcs_enable_plugin = get_option( 'bwcs_enable_plugin' );
	
	$plugin_enabled = ( $bwcs_enable_plugin != 'disabled' ) ? 'checked' : '';

	?>
	<input type="hidden" name="action" value="bwcs_admin_page">
	
	<table class="form-table" role="presentation">

		<tbody>

			<tr>
				<th scope="row">
					<label for="bwcs_enable_plugin"> <?php _e( 'Enable This Plugin', 'bwcs' ); ?> </label>
				</th>
				<td>
					<input name="bwcs_enable_plugin" type="checkbox" id="bwcs_enable_plugin" value="enabled" <?php echo $plugin_enabled; ?> />
					<label for="bwcs_enable_plugin"> Enable </label>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="bwcs_coming_soon_page"><?php _e( 'Select Coming Soon / Maintenance Page', 'bwcs' ); ?></label>
				</th>
				<td>
					<select name="bwcs_coming_soon_page">
						<?php bwcs_pages_as_dropdown(); ?>
					</select>
					
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label><?php _e( 'Who can see other pages', 'bwcs' ); ?></label>
				</th>
				<td>
					<?php bwcs_roles_as_checkbox() ?>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label><?php _e( 'Display pages along with coming soon page', 'bwcs' ); ?></label>
				</th>
				<td>
					<?php bwcs_pages_as_checkbox() ?>
				</td>
			</tr>
			
		</tbody>
	</table>

	<?php
	submit_button( 'Save Settings' );
	?>
</form>