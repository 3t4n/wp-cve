<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div style="background: white;border:1px solid #E1E3E6;padding: 16px;">
<div style = "display: flex">
	<img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))).'/admin/images/google-drive.svg'); ?>" style="max-width:20px;"/>&nbsp&nbsp<h3><?php _e('Google Drive','wp-migration-duplicator');?></h3> &nbsp&nbsp&nbsp&nbsp <?php if( $authenticated === true ):?> <h4 style="color: green;"><?php _e('Connected','wp-migration-duplicator');?> </h4> <?php endif; ?>
</div>
<div class="wt_info_box" style="margin-bottom:35px;">
<ul style="list-style:disc; margin-left:20px;">
		<li><?php echo sprintf(wp_kses(__('Obtain client ID and client secret from the Google developer console to get connected to Google Drive. Refer Google developer <a href="%s" target="_blank">documentation</a>', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://developers.google.com/drive/api/v3/about-auth')); ?></li>
		<li><?php _e('To update the credentials: disconnect, update and authenticate.','wp-migration-duplicator'); ?></li>
	</ul>
</div>

<form method="post"  action="<?php echo esc_url($_SERVER["REQUEST_URI"]);?>" id="wt_mgdp_googledrive">
	<?php wp_nonce_field('wp_migration_duplicator_googledrive','_google_drive_auth'); ?>
	<input type="hidden" name="wt_authenticate_google_form">
	<table class="form-table wf-form-table">
		<tr>
			<th><label><?php _e("Client ID",'wp-migration-duplicator'); ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The Client ID is a publicly exposed string that is used by the service API to identify the application, and is also used to build authorization URLs that are presented to users.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></label></th>
			<td>
				<input type="text" name="wt_google_client_id" value="<?php echo esc_attr($client_id); ?>">
			</td>
			<td></td>
		</tr>

		<tr>
		
			<th><label><?php _e("Client Secret",'wp-migration-duplicator'); ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The Client Secret is used to authenticate the identity of the application to the service API when the application requests to access a user’s account.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></label></th>
			<td>
                            <input type="password" name="wt_google_client_secret" value="<?php echo esc_attr($client_secret); ?>" style="width:100%;border: 1px solid #ced4da;">
			</td>
			<td></td>
		</tr>
	</table>
	<div style="clear: both;"></div>
	<?php if( $authenticated === false ):?> 
		<div class="wt-mgdp-plugin-toolbar wt-migrator-action-bar bottom wt-migrator-authenticate-bar">
			<div class="left">
			</div>
			<div class="right">
				<span class="wt-migrator-notice wt-migrator-notice-inline" style=" margin-top: 10px; display: inline-block; margin-right: -20px;"></span>
				<input type="submit" name="wt_authenticate_google" value="<?php _e('Authenticate', 'wp-migration-duplicator'); ?>" class="button button-primary" style="float:right;" />
				<span class="spinner" style="margin-top:11px"></span>
			</div>
		</div>
		<?php else :?>
			<div class="wt-mgdp-plugin-toolbar bottom wt-migrator-action-bar wt-migrator-disconnect-bar">
			<div class="left">
			</div>
			<div class="right">
				<span class="wt-migrator-notice wt-migrator-notice-inline" style=" margin-top: 10px; display: inline-block; margin-right: -20px;"></span>
				<button type="submit" id="wt_disconnect_googledrive" name="wt_disconnect_googledrive" class="button button-primary" style="float:right;"><?php _e('Disconnect', 'wp-migration-duplicator'); ?></button>
				<span class="spinner" style="margin-top:11px"></span>
			</div>
		</div>
	<?php endif; ?>
</form>
</div>
<?php include WT_MGDP_PLUGIN_PATH . '/admin/partials/wt_migrator_upgrade_to_pro.php'; ?>
