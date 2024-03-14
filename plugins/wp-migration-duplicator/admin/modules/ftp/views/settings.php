<?php
if (!defined('WPINC')) {
	die;
}

?>
<style type="text/css">
	.wt_mgdp_ftp_loader {
		width: 100%;
		height: 200px;
		text-align: center;
		line-height: 150px;
		font-size: 14px;
		font-style: italic;
	}
</style>
<ul class="wf_sub_tab">
	<li style="border-left:none; padding-left: 0px;" data-target="ftp-profiles"><a><?php _e('FTP profiles', 'wp-migration-duplicator'); ?></a></li>
	<li data-target="add-new-ftp"><a><?php _e('Add new', 'wp-migration-duplicator'); ?></a></li>
</ul>
<div class="wf_sub_tab_container" style="  background: #ffffff; padding: 8px 20px 15px 20px;width:96.5%;border:none !important;box-shadow: 0px 2px 16px rgba(0, 0, 0, 0.1) !important;">
	<div class="wf_sub_tab_content" data-id="add-new-ftp" style="display:block;  padding: 12px;" id="wp_mgdp_form_wrapper">
		<h3 class="wt_mgdp_form_title" id="wt_mgdp_form_title"> <?php _e("Add new FTP profile", 'wp-migration-duplicator'); ?></h3>

		<form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>" id="wt_mgdp_ftp_form">
			<input type="hidden" value="0" name="wt_mgdp_ftp_id" />
			<input type="hidden" value="wp_mgdp_ftp_ajax" name="action" />
			<input type="hidden" value="save_ftp" name="wt_mgdp_update_action" class="wt_mgdp_update_action" />
			<?php
			// Set nonce:
			if (function_exists('wp_nonce_field')) {
				wp_nonce_field(Wp_Migration_Profile::$module_id);
			}
			?>
			<table class="form-table wf-form-table">
				<tr>
					<th>
						<label>
							<?php _e("Profile name", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('You should begin by creating a Profile for each FTP server that you will be working with. The profile contains the necessary information to connect to the server.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
						</label>
					</th>
					<td>
						<input type="text" name="wt_mgdp_profilename" placeholder="e.g. Demo FTP">
					</td>
					<td></td>
				</tr>
				<tr>
					<th><label>
							<?php _e("FTP Server Host/IP", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The host server is the FTP host to which the users want to connect using their respective login credentials.To get FTP host Login to the control panel of your website, the FTP address should be listed in the FTP account section of the control panel.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
						</label>
					</th>
					<td>
						<input type="text" name="wt_mgdp_hostname" placeholder="e.g. ftp.hostname.com">
					</td>
					<td></td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e("FTP User Name", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The username created on activating FTP account', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
							
						</label>
					</th>
					<td>
						<input type="text" name="wt_mgdp_ftpuser" placeholder="e.g. John">
					</td>
					<td></td>
				</tr>
				<tr>
					<th><label><?php _e("FTP Password", 'wp-migration-duplicator'); ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The password you set up when you activated your FTP account.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></label></th>
					<td>
						<input type="password" name="wt_mgdp_ftppassword" autocomplete="off">
					</td>
					<td></td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e("FTP Port", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Port number to establish FTP connection. Default port number: 21', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
							
						</label>
					</th>
					<td>
						<input type="number" step="1" value="21" name="wt_mgdp_ftpport">
					</td>
					<td></td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e("Default export path", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Specify a server directory to store the backup files. The specified directory must exist on the server.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
						</label>
					</th>
					<td>
						<input type="text" name="wt_mgdp_ftpexport_path" placeholder="e.g. /backups">
						<span style="font-style: italic;font-size:12px;"><?php _e('Needs to already exist on the server','wp-migration-duplicator'); ?></span>
					</td>
					<td></td>
				</tr>
				<tr>
					<th>
						<label>
							<?php _e("Default import path", 'wp-migration-duplicator'); ?>
							<span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Specify a server directory from where you want to import the backup. The specified directory must exist on the server.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
						</label>
					</th>
					<td>
						<input type="text" step="1" name="wt_mgdp_ftpimport_path" value="" placeholder="e.g. /backups">
						<span style="font-style: italic;font-size:12px;"><?php _e('Needs to already exist on the server','wp-migration-duplicator'); ?></span>
					</td>
					<td></td>
				</tr>
                                
                                
                                <tr>
					<th><label><?php _e("Use FTPS"); ?></label></th>
					<td>
						<input type="radio" name="wt_mgdp_useftps" class="" value="1"/> Yes &nbsp;&nbsp;
                                                <input type="radio" name="wt_mgdp_useftps" class="" value="0" checked="checked"/> No &nbsp;&nbsp;<br/>
                                                <span style="font-style: italic;font-size:12px;"><?php _e('Enable this send data over a network with SSL encryption','wp-migration-duplicator'); ?></span>

        	        </td>
					<td></td>
				</tr>
				<tr>
					<th><label><?php _e("Enable Passive mode"); ?></label></th>
					<td>
						<input type="radio" name="wt_mgdp_passivemode" class="" value="1"/> Yes &nbsp;&nbsp;
                                                <input type="radio" name="wt_mgdp_passivemode" class="" value="0" checked="checked"/> No &nbsp;&nbsp;<br>
                                                <span style="font-style: italic;font-size:12px;"><?php _e('Enable this to turns passive mode on or off','wp-migration-duplicator'); ?></span>
        	        </td>
					<td></td>
				</tr>
				<tr>
					<th><label><?php _e("Is SFTP"); ?></label></th>
					<td>
						<input type="radio" name="wt_mgdp_is_sftp" class="" value="1"> Yes &nbsp;&nbsp;
						<input type="radio" name="wt_mgdp_is_sftp" class="" value="0" checked="checked"> No &nbsp;&nbsp;
        	        </td>
					<td></td>
				</tr>



			</table>
			<?php
			$settings_button_title = __('Save settings');
			include plugin_dir_path(WT_MGDP_PLUGIN_FILENAME) . "admin/views/admin-settings-save-button.php";
			?>
		</form>
	</div>
	<div class="wf_sub_tab_content" data-id="ftp-profiles">
		<?php
		$checked = (filter_var($is_enabled, FILTER_VALIDATE_BOOLEAN)) ? 'checked=checked' : '';
		?>
		<h3><?php _e("FTP/SFTP settings", 'wp-migration-duplicator'); ?></h3>
		<p><?php _e('Create and maintain multiple FTP/SFTP profiles that can be used for the import-export operations of your site backups.','wp-migration-duplicator');?></p>
		<div class="wt_mgdp_ftp_list">
			<div class="wp_migration_duplicator_ftp_list"></div>
		</div>
	</div>
</div>
  <?php include WT_MGDP_PLUGIN_PATH . '/admin/partials/wt_migrator_upgrade_to_pro.php'; ?>