<form method="POST" action="">

	<input type="hidden" name="save_hpf_settings" value="yes">
	<?php wp_nonce_field( 'save-hpf-settings' ); ?>

	<div class="hpf-admin-settings-body wrap">

		<div class="hpf-admin-settings-import-settings">

			<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

			<h1><?php echo __( 'Settings', 'houzezpropertyfeed' ); ?></h1>

			<div class="settings-area">

				<div class="left-tabs">
					<ul>
						<li class="active"><a href="#departmentstatuses"><span class="dashicons dashicons-post-status"></span> <?php echo __( 'Departments', 'houzezpropertyfeed' ); ?></a></li>
						<li><a href="#propertyselection"><span class="dashicons dashicons-yes"></span> <?php echo __( 'Property Selection', 'houzezpropertyfeed' ); ?></a></li>
					</ul>
				</div>

				<div class="right-settings">

					<div class="buttons">

						<input type="submit" value="<?php echo __( 'Save changes', 'houzezpropertyfeed' ); ?>" class="button button-primary">

					</div>

					<div class="settings-panels">

						<div class="settings-panel" id="departmentstatuses">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-export-department-statuses.php' ); ?>
						</div>

						<div class="settings-panel" id="propertyselection" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-export-property-selection.php' ); ?>
						</div>
						
					</div>

					<div class="buttons bottom">

						<input type="submit" value="<?php echo __( 'Save changes', 'houzezpropertyfeed' ); ?>" class="button button-primary">

					</div>

				</div>

			</div>

		</div>

	</div>

</form>