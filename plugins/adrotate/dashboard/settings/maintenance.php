<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */
?>

<h2><?php _e('Maintenance', 'adrotate'); ?></h2>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e('Check adverts', 'adrotate'); ?></th>
		<td>
			<a class="button" href="<?php echo wp_nonce_url('admin.php?page=adrotate-settings&tab=maintenance&action=check-ads', 'nonce', 'adrotate-nonce'); ?>"><?php _e('Check ads for errors', 'adrotate'); ?></a>

			<br /><br />
			<span class="description"><em><?php _e('Apply all evaluation rules to all adverts to see if any error slipped in. This may take a few seconds.', 'adrotate'); ?></em></span>
		</td>
	</tr>
</table>

<h3><?php _e('Status and Versions', 'adrotate'); ?></h3>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e('Current status of adverts', 'adrotate'); ?></th>
		<td colspan="3"><?php _e('Normal', 'adrotate'); ?>: <?php echo $advert_status['normal']; ?>, <?php _e('Error', 'adrotate'); ?>: <?php echo $advert_status['error']; ?>, <?php _e('Expired', 'adrotate'); ?>: <?php echo $advert_status['expired']; ?>, <?php _e('Expires Soon', 'adrotate'); ?>: <?php echo $advert_status['expiressoon']; ?>, <?php _e('Unknown', 'adrotate'); ?>: <?php echo $advert_status['unknown']; ?>.</td>
	</tr>
	<tr>
		<th width="15%"><?php _e('Banners/assets Folder', 'adrotate'); ?></th>
		<td colspan="3">
			<?php
			echo WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder'].'/ -> ';
			echo (is_writeable(WP_CONTENT_DIR.'/'.$adrotate_config['banner_folder']).'/') ? '<span style="color:#009900;">'.__('Exists and appears writable', 'adrotate').'</span>' : '<span style="color:#CC2900;">'.__('Not writable or does not exist', 'adrotate').'</span>';
			?>
		</td>
	</tr>
	<tr>
		<th width="15%"><?php _e('Reports Folder', 'adrotate'); ?></th>
		<td colspan="3">
			<?php
			echo WP_CONTENT_DIR.'/reports/'.' -> ';
			echo (is_writable(WP_CONTENT_DIR.'/reports/')) ? '<span style="color:#009900;">'.__('Exists and appears writable', 'adrotate').'</span>' : '<span style="color:#CC2900;">'.__('Not writable or does not exist', 'adrotate').'</span>';
			?>
		</td>
	</tr>
	<tr>
		<th width="15%"><?php _e('Advert evaluation', 'adrotate'); ?></th>
		<td><?php if(!$adevaluate) '<span style="color:#CC2900;">'._e('Not scheduled! Re-activate the plugin from the plugins page.', 'adrotate').'</span>'; else echo '<span style="color:#009900;">'.date_i18n(get_option('date_format')." H:i", $adevaluate).'</span>'; ?></td>
		<th width="15%"><?php _e('Clean Trackerdata', 'adrotate'); ?></th>
		<td><?php if(!$tracker) '<span style="color:#CC2900;">'._e('Not scheduled!', 'adrotate').'</span>'; else echo '<span style="color:#009900;">'.date_i18n(get_option('date_format')." H:i", $tracker).'</span>'; ?></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Background tasks', 'adrotate'); ?></th>
		<td colspan="3">
			<a class="button" href="<?php echo wp_nonce_url('admin.php?page=adrotate-settings&tab=maintenance&action=reset-tasks', 'nonce', 'adrotate-nonce'); ?>"><?php _e('Reset background tasks', 'adrotate'); ?></a>

			<br /><br />
			<span class="description"><em><?php _e('If automated tasks such as expiring adverts does not work reliable or one of the above schedules is missing use this button to reset the tasks.', 'adrotate'); ?></em></span>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Unsupported plugins', 'adrotate'); ?></th>
		<td colspan="3">
			<a class="button" href="<?php echo wp_nonce_url('admin.php?page=adrotate-settings&tab=maintenance&action=disable-3rdparty', 'nonce', 'adrotate-nonce'); ?>"><?php _e('Disable 3rd party plugins', 'adrotate'); ?></a><br /><br />
			<?php if(is_plugin_active('adrotate-extra-settings/adrotate-extra-settings.php') OR is_plugin_active('adrotate-email-add-on/adrotate-email-add-on.php') OR is_plugin_active('ad-builder-for-adrotate/ad-builder-for-adrotate.php') OR is_plugin_active('extended-adrotate-ad-placements/index.php')) { ?>
			<span style="color:#CC2900;"><?php _e('One or more unsupported 3rd party plugins detected.', 'adrotate'); ?></span><br />
			<?php } ?>
			<span class="description"><em><?php _e('These are plugins that alter functions of AdRotate or highjack parts of the dashboard which may affect security and/or stability.', 'adrotate'); ?></em></span>
		</td>
	</tr>
</table>

<h2><?php _e('Internal Versions', 'adrotate'); ?></h2>
<span class="description"><?php _e('Unless you experience database issues or a warning shows below, these numbers are not really relevant for troubleshooting. Support may ask for them to verify your database status.', 'adrotate'); ?></span>
<table class="form-table">			
	<tr>
		<th width="15%" valign="top"><?php _e('AdRotate version', 'adrotate'); ?></th>
		<td><?php _e('Current:', 'adrotate'); ?> <?php echo '<span style="color:#009900;">'.$adrotate_version['current'].'</span>'; ?> <?php if($adrotate_version['current'] != ADROTATE_VERSION) { echo '<span style="color:#CC2900;">'; _e('Should be:', 'adrotate'); echo ' '.ADROTATE_VERSION; echo '</span>'; } ?><br /><?php _e('Previous:', 'adrotate'); ?> <?php echo $adrotate_version['previous']; ?></td>
		<th width="15%" valign="top"><?php _e('Database version', 'adrotate'); ?></th>
		<td><?php _e('Current:', 'adrotate'); ?> <?php echo '<span style="color:#009900;">'.$adrotate_db_version['current'].'</span>'; ?> <?php if($adrotate_db_version['current'] != ADROTATE_DB_VERSION) { echo '<span style="color:#CC2900;">'; _e('Should be:', 'adrotate'); echo ' '.ADROTATE_DB_VERSION; echo '</span>'; } ?><br /><?php _e('Previous:', 'adrotate'); ?> <?php echo $adrotate_db_version['previous']; ?></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Manual upgrade', 'adrotate'); ?></th>
		<td colspan="3">
			<a class="button" href="<?php echo wp_nonce_url('admin.php?page=adrotate-settings&tab=maintenance&action=update-db', 'nonce', 'adrotate-nonce'); ?>" onclick="return confirm('<?php _e('YOU ARE ABOUT TO DO A MANUAL UPDATE FOR ADROTATE.', 'adrotate'); ?>\n<?php _e('Make sure you have a database backup!', 'adrotate'); ?>\n\n<?php _e('This might take a while and may slow down your site during this action!', 'adrotate'); ?>\n\n<?php _e('OK to continue, CANCEL to stop.', 'adrotate'); ?>')"><?php _e('Run updater', 'adrotate'); ?></a>
		</td>
	</tr>
</table>