
<div class="wrap" id="seo-backlink-monitor">
	<h1 class="wp-heading-inline"><?php echo __( 'SEO Backlink Monitor', 'seo-backlink-monitor' ); ?></h1>
	<?php if (!$editLink): ?>
		<a href="#" class="page-title-action" data-toggle="#add-link-form">+ <?php echo __( 'Add Link', 'seo-backlink-monitor' ); ?></a>
	<?php endif; ?>
	<a href="#" class="page-title-action" data-toggle="#settings-form"><?php echo __( 'Settings', 'seo-backlink-monitor' ); ?></a>

	<hr class="wp-header-end">

	<?php if ( isset( $_REQUEST['msg'] ) ) {
		switch ($_REQUEST['msg']) {
			case 'success':
				$msg = __( 'Link added.', 'seo-backlink-monitor' );
				$type = 'success';
				break;
			case 'error':
				$msg = __( 'Please enter valid URLs for Link To and Link From.', 'seo-backlink-monitor' );
				$type = 'error';
				break;
			case 'duplicate-warning':
				$msg = __( 'Duplicate Link: Link not added. Please check the Link.', 'seo-backlink-monitor' );
				$type = 'warning';
				break;
			case 'edit-success':
				$msg = __( 'Link updated.', 'seo-backlink-monitor' );
				$type = 'success';
				break;
			case 'multi-success':
				$msg = __( 'All Links added.', 'seo-backlink-monitor' );
				$type = 'success';
				break;
			case 'multi-success-with-errors':
				$msg = __( 'Some Links added. Please check remaining entered Links (one link-combination per line, two valid URLs separated by ";").', 'seo-backlink-monitor' );
				$type = 'warning';
				break;
			case 'multi-info':
				$msg = __( 'Nothing added.', 'seo-backlink-monitor' );
				$type = 'info';
				break;
			case 'multi-info-with-errors':
				$msg = __( 'Nothing added. Please check entered Links (one link-combination per line, two valid URLs separated by ";").', 'seo-backlink-monitor' );
				$type = 'warning';
				break;
			case 'multi-warning':
				$msg = __( 'Nothing added.', 'seo-backlink-monitor' );
				$type = 'warning';
				break;
			case 'refresh-success':
				$msg = __( 'All Links checked.', 'seo-backlink-monitor' );
				$type = 'success';
				break;
			case 'refresh-warning':
				$msg = __( 'All Links checked, but there was an error sending the email.', 'seo-backlink-monitor' );
				$type = 'warning';
				break;
			case 'settings-success':
				$msg = __( 'Settings saved.', 'seo-backlink-monitor' );
				$type = 'success';
				break;
			case 'settings-email-error':
				$msg = __( 'Settings saved, but the email address you entered was incorrect and was therefore not saved.', 'seo-backlink-monitor' );
				$type = 'error';
				break;
			default:
				$msg = false;
				$type = false;
				break;
		}
		if ($msg) {
		?>
		<div class="notice notice-<?php echo $type; ?> is-dismissible" id="seo-blm-admin-notice">
			<p><?php echo $msg; ?></p>
		</div>
	<?php
		}
	}
	?>

	<div class="card" id="settings-form">
		<form id="settings" action="<?php echo admin_url('admin-post.php'); ?>" method="post">
			<?php wp_nonce_field('seo-backlink-monitor-save-settings', 'seo-backlink-monitor-save-settings-nonce'); ?>
			<input type="hidden" name="action" value="seo_backlink_monitor_save_settings">
			<h2><?php echo __( 'Settings' , 'seo-backlink-monitor' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="cron-frequency"><?php echo __( 'Automatically Check Links', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<select name="cron-frequency" id="cron-frequency">
								<option value="" <?php echo $settings['cronFrequency'] === '' ? 'selected' : '' ?>><?php echo __( 'Deactivated', 'seo-backlink-monitor' ); ?></option>
								<option value="daily" <?php echo $settings['cronFrequency'] === 'daily' ? 'selected' : '' ?>><?php echo __( 'Daily', 'seo-backlink-monitor' ); ?></option>
								<option value="twicedaily" <?php echo $settings['cronFrequency'] === 'twicedaily' ? 'selected' : '' ?>><?php echo __( 'Twice Daily', 'seo-backlink-monitor' ); ?></option>
								<option value="hourly" <?php echo $settings['cronFrequency'] === 'hourly' ? 'selected' : '' ?>><?php echo __( 'Hourly', 'seo-backlink-monitor' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="notify-email"><?php echo __( 'Send Email on changed link status', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<input name="notify-email" type="text" id="notify-email" class="regular-text" placeholder="<?php echo __( 'Your Email', 'seo-backlink-monitor' ); ?>" value="<?php echo esc_attr($settings['notifyEmail']); ?>" />
							<p><em><?php echo __( 'Leave empty to disable. Sends email, when "Automatically Check Links" is enabled.', 'seo-backlink-monitor' ); ?></em></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="date-format"><?php echo __( 'Overwrite Date Format', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<input name="date-format" type="text" id="date-format" class="" placeholder="<?php echo get_option( 'date_format' ); ?>" value="<?php echo esc_attr($settings['dateFormat']); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="display-notes"><?php echo __( 'Display Notes', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<select name="display-notes" id="display-notes">
								<option value="" <?php echo $settings['displayNotes'] === '' ? 'selected' : '' ?>><?php echo __( 'Yes', 'seo-backlink-monitor' ); ?></option>
								<option value="0" <?php echo $settings['displayNotes'] === '0' ? 'selected' : '' ?>><?php echo __( 'No', 'seo-backlink-monitor' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="result-items-per-page"><?php echo __( 'Backlinks Per Page', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<select name="result-items-per-page" id="result-items-per-page">
								<option value="5" <?php echo $settings['resultItemsPerPage'] === '5' ? 'selected' : '' ?>>5</option>
								<option value="10" <?php echo $settings['resultItemsPerPage'] === '10' ? 'selected' : '' ?>>10</option>
								<option value="20" <?php echo $settings['resultItemsPerPage'] === '20' ? 'selected' : '' ?>>20</option>
								<option value="50" <?php echo $settings['resultItemsPerPage'] === '50' ? 'selected' : '' ?>>50</option>
								<option value="100" <?php echo $settings['resultItemsPerPage'] === '100' ? 'selected' : '' ?>>100</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="button" class="button" data-toggle="#settings-form" value="<?php echo __( 'Cancel', 'seo-backlink-monitor' ); ?>">
				<input type="submit" name="submit" id="submit" class="button button-primary settings-btn" value="<?php echo __( 'Save Settings', 'seo-backlink-monitor' ); ?>">
			</p>
			<p><span class="dashicons dashicons-heart"></span> <em><?php echo __( 'Thank you for installing our plugin. Have fun and success with it. We would be very happy about a small backlink on your frontend.', 'seo-backlink-monitor' ); ?></em></p>
			<p><code>&lt;a href="https://www.active-websight.de"&gt;Active Websight&lt;/a&gt;</code></p>
		</form>
	</div>

	<div class="card <?php echo $editLink || $addLinkHasError ? 'show' : '' ?>" id="add-link-form">
		<form id="add-valid-link" action="<?php echo admin_url('admin-post.php'); ?>" method="post" novalidate>
			<?php wp_nonce_field('seo-backlink-monitor-'.$mode.'-link', 'seo-backlink-monitor-'.$mode.'-link-nonce'); ?>
			<input type="hidden" name="action" value="seo_backlink_monitor_<?php echo $mode; ?>_link">
			<img src="<?php echo admin_url( '/images/wpspin_light.gif' )?>" id="img_loading" style=" display: none;" />
			<h2><?php echo $editLink ? __( 'Edit Link' , 'seo-backlink-monitor' ) : __( 'Add Link' , 'seo-backlink-monitor' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="link-to"><?php echo __( 'Link To', 'seo-backlink-monitor' ); ?> *</label>
						</th>
						<td>
							<input name="link-to" type="url" id="link-to" class="regular-text" required="true" value="<?php echo isset($linkData['linkTo']) ? esc_attr(htmlentities($linkData['linkTo'])) : '' ?>" placeholder="<?php echo __( 'Please enter a valid URL.', 'seo-backlink-monitor' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="link-from"><?php echo __( 'Link From', 'seo-backlink-monitor' ); ?> *</label>
						</th>
						<td>
							<input name="link-from" type="url" id="link-from" class="regular-text" required="true" value="<?php echo isset($linkData['linkFrom']) ? esc_attr(htmlentities($linkData['linkFrom'])) : '' ?>" placeholder="<?php echo __( 'Please enter a valid URL.', 'seo-backlink-monitor' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="notes"><?php echo __( 'Notes', 'seo-backlink-monitor' ); ?></label>
						</th>
						<td>
							<textarea name="notes" id="notes" type="textarea" class="regular-text" cols="" rows="<?php echo $editLink ? '6' : '3' ?>" placeholder="<?php echo __( 'Optionally add notes.', 'seo-backlink-monitor' ); ?>"><?php echo isset($linkData['notes']) ? stripslashes($linkData['notes']) : '' ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<?php if (!$editLink): ?>
					<input type="button" class="button" data-toggle="#add-link-form" value="<?php echo __( 'Cancel', 'seo-backlink-monitor' ); ?>">
				<?php else: ?>
					<a class="button" href="<?php echo admin_url('admin.php?page='.SEO_BLM_PLUGIN); ?>"><?php echo __( 'Cancel', 'seo-backlink-monitor' ); ?></a>
					<input type="hidden" name="edit_id" value="<?php echo esc_attr($linkData['id']); ?>">
				<?php endif; ?>
				<input type="submit" name="submit" id="submit" class="button button-primary add-link-btn" value="<?php echo $editLink ? __( 'Edit Link', 'seo-backlink-monitor' ) : __( 'Add Link' , 'seo-backlink-monitor' ); ?>">
			</p>
		</form>

		<hr>

		<form id="add-multiple-valid-links" action="<?php echo admin_url('admin-post.php'); ?>" method="post" novalidate>
			<?php wp_nonce_field('seo-backlink-monitor-add-multiple-links', 'seo-backlink-monitor-add-multiple-links-nonce'); ?>
			<input type="hidden" name="action" value="seo_backlink_monitor_add_multiple_links">
			<h2><?php echo __( 'Add Multiple Links' , 'seo-backlink-monitor' ); ?></h2>
			<table class="form-table">
				<thead>
					<tr>
						<th scope="row">
							<label for="multiple-links"><?php echo __( 'Links', 'seo-backlink-monitor' ); ?> *</label>
						</th>
					</tr>
				</thead>
				<tbody>
						<td>
							<textarea name="multiple-links" id="multiple-links" type="textarea" class="regular-text" required="true" cols="" rows="10" placeholder="<?php echo __( 'http://link-to.tld;http://link-from.tld', 'seo-backlink-monitor' ); ?>"><?php echo isset($multipleLinks['links']) ? stripslashes($multipleLinks['links']) : '' ?></textarea>
							<p><em><?php echo __( 'Valid URL of "Link To" followed by valid URL of "Link From", separated by a semicolon (";"). One link-combination per line. No notes. Existing link-combinations will not be inserted.', 'seo-backlink-monitor' ); ?></em></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="button" class="button" data-toggle="#add-link-form" value="<?php echo __( 'Cancel', 'seo-backlink-monitor' ); ?>">

				<input type="submit" name="submit" id="submit" class="button button-primary add-multiple-links-btn" value="<?php echo __( 'Add Multiple Links' , 'seo-backlink-monitor' ); ?>">
			</p>
		</form>
	</div>

	<div id="seo-backlink-monitor-links" class="<?php echo $settings['displayNotes'] === '0' ? 'hide-notes' : '' ?>">
		<?php $linksListTable = new SEO_Backlink_Monitor_Child_WP_List_Table(); ?>
		<h2>
			<?php echo __( 'List of Backlinks: ', 'seo-backlink-monitor' ); ?>
			<a class="button button-primary" id="refresh-all-btn" href="<?php echo wp_nonce_url(admin_url('admin.php?page='.SEO_BLM_PLUGIN.'') . (isset($_GET['with']) && $_GET['with'] === 'mail' ? '&with=mail': ''), 'seo-backlink-monitor-refresh-all-nonce', 'seo-backlink-monitor-refresh-all'); ?>"><span class="dashicons dashicons-update"></span> <?php echo __( 'Check All Links', 'seo-backlink-monitor' ); ?></a>
		</h2>

		<div class="search-help" id="search-help-status">
			<ul class="subsubsub">
				<li><span><span class="dashicons dashicons-filter"></span></span></li>
				<li><a href="#" data-search-val="1" title="1"><span class="dashicons dashicons-admin-site"></span> <?php _e('Link Found', 'seo-backlink-monitor'); ?></a></li>
				<li><a href="#" data-search-val="0" title="0"><span class="dashicons dashicons-editor-unlink"></span> <?php _e('Link Not Found', 'seo-backlink-monitor'); ?></a></li>
				<li><a href="#" data-search-val="2" title="2"><span class="dashicons dashicons-admin-site dashicons-warning down"></span> <?php _e('Server Down', 'seo-backlink-monitor'); ?></a></li>
			</ul>
			<br class="clear" />
		</div>
		<div class="search-help" id="search-help-follow">
			<ul class="subsubsub">
				<li><span><span class="dashicons dashicons-filter"></span></span></li>
				<li><a href="#" data-search-val="1" title="1"><span class="dashicons dashicons-desktop dashicons-desktop-yes"></span> <?php _e('Yes'); ?></a></li>
				<li><a href="#" data-search-val="0" title="0"><span class="dashicons dashicons-desktop dashicons-desktop-no"></span> <?php _e('No'); ?></a></li>
			</ul>
			<br class="clear" />
		</div>

		<?php $linksListTable->prepare_items(); ?>
		<input type="hidden" name="page" value="<?php echo absint($_REQUEST['page']); ?>" />
		<?php $linksListTable->display(); ?>
	</div>
</div>
