<form method="POST" action="">

	<input type="hidden" name="import_id" value="<?php echo ( $import_id !== FALSE ? $import_id : '' ); ?>">
	<input type="hidden" name="save_import_settings" value="yes">
	<?php wp_nonce_field( 'save-import-settings' ); ?>

	<div class="hpf-admin-settings-body wrap">

		<div class="hpf-admin-settings-import-settings">

			<h1><?php echo ( $import_id !== false ) ? __( 'Edit Import', 'houzezpropertyfeed' ) : __( 'Create Import', 'houzezpropertyfeed' ); ?></h1>

			<div class="settings-area">

				<div class="left-tabs">
					<ul>
						<li id="import_setting_tab_format" class="active"><a href="#format"><span class="dashicons dashicons-editor-code"></span> <?php echo __( 'Import Format', 'houzezpropertyfeed' ); ?></a></li>
						<li id="import_setting_tab_frequency"><a href="#frequency"><span class="dashicons dashicons-clock"></span> <?php echo __( 'Frequency', 'houzezpropertyfeed' ); ?></a></li>
						<li id="import_setting_tab_taxonomies"><a href="#taxonomies"><span class="dashicons dashicons-tag"></span> <?php echo __( 'Taxonomies', 'houzezpropertyfeed' ); ?></a></li>
						<li id="import_setting_tab_contactinfo"><a href="#contactinfo"><span class="dashicons dashicons-admin-users"></span> <?php echo __( 'Contact Information', 'houzezpropertyfeed' ); ?></a></li>
						<li id="import_setting_tab_fieldmapping"><a href="#fieldmapping"><span class="dashicons dashicons-admin-settings"></span></span> <?php echo __( 'Field Mapping', 'houzezpropertyfeed' ); ?><span id="field_mapping_warning" style="color:#999; display:none">&nbsp;&nbsp;<span class="dashicons dashicons-warning"></span></span></a></li>
						<li id="import_setting_tab_media"><a href="#media"><span class="dashicons dashicons-admin-media"></span> <?php echo __( 'Media', 'houzezpropertyfeed' ); ?></a></li>
						<li id="import_setting_tab_enquiries"><a href="#enquiries"><span class="dashicons dashicons-email"></span> <?php echo __( 'Export Enquiries', 'houzezpropertyfeed' ); ?></a></li>
					</ul>
				</div>

				<div class="right-settings">

					<div class="buttons">

						<div class="running-status-toggle">
							
							Import Running

							<label class="hpf-switch">
							  	<input type="checkbox" name="running" value="yes"<?php if ( isset($import_settings['running']) && $import_settings['running'] === true ) { echo ' checked'; } ?>>
							  	<span class="hpf-slider"></span>
							</label>

						</div>

						<input type="submit" value="<?php echo __( 'Save changes', 'houzezpropertyfeed' ); ?>" class="button button-primary">&nbsp;
						<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import'); ?>" class="button">Cancel</a>

					</div>

					<div class="settings-panels">

						<div class="settings-panel" id="format">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-format.php' ); ?>
						</div>

						<div class="settings-panel" id="frequency" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-frequency.php' ); ?>
						</div>

						<div class="settings-panel" id="taxonomies" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-taxonomies.php' ); ?>
						</div>

						<div class="settings-panel" id="contactinfo" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-contact-information.php' ); ?>
						</div>

						<div class="settings-panel" id="fieldmapping" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-field-mapping.php' ); ?>
						</div>

						<div class="settings-panel" id="media" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-media.php' ); ?>
						</div>

						<div class="settings-panel" id="enquiries" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings-enquiries.php' ); ?>
						</div>

					</div>

					<div class="buttons bottom">

						<input type="submit" value="<?php echo __( 'Save changes', 'houzezpropertyfeed' ); ?>" class="button button-primary">&nbsp;
						<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import'); ?>" class="button">Cancel</a>

					</div>

				</div>

			</div>

		</div>

	</div>

</form>