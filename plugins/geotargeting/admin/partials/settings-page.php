<?php
/**
 * Settings page template
 * @since  1.0.0
 */
 if (  isset( $_POST['geot_nonce'] ) && wp_verify_nonce( $_POST['geot_nonce'], 'geot_save_settings' ) ) {

     $settings = esc_sql( $_POST['geot_settings'] );

	 update_option( 'geot_settings' ,  $settings);

 }

 $opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );

 if( empty( $opts['debug_mode'] ) ) {
	 $opts['debug_mode'] = '0';
 }


?>
<div class="wrap geot-settings">
	<h2>GeoTargeting <?php echo $this->version;?></h2>
	<form name="geot-settings" method="post" enctype="multipart/form-data">
		<table class="form-table">
			<?php do_action( 'geot/settings_page/before' ); ?>
			<tr valign="top" class="">
				<th><h3><?php _e( 'Main settings:', $this->GeoTarget ); ?></h3></th>
			</tr>

			<tr valign="top" class="">
				<th><label for="maxm_id"><?php _e( 'Debug Mode', $this->GeoTarget ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="maxm_id" name="geot_settings[debug_mode]" value="1" <?php checked($opts['debug_mode'] , '1');?> />
						<p class="help"><?php _e( 'If you want to calculate user data on every page load and print in the footer debug info with check this.', $this->GeoTarget ); ?></p>
				</td>
			</tr>
			<tr><td><input type="submit" class="button-primary" value="<?php _e( 'Save settings', $this->GeoTarget );?>"/></td>
				<?php wp_nonce_field('geot_save_settings','geot_nonce'); ?>
			</tr>
            <tr>
                <td colspan="2"><h2>This plugin won't work if you have any page cache in your site/server. Only country shortcodes are available.</h2>
                <p>If you need a complete geolocation tool with cache support check <a href="https://geotargetingwp.com/?utm_source=plugin-settings&utm_medium=link&utm_campaign=geotargeting-pro">GeotargetingWP plugin</a></p></td>
            </tr>
		</table>
	</form>
</div>
