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
						<li class="active"><a href="#emailreports"><span class="dashicons dashicons-email"></span> <?php echo __( 'Email Reports', 'houzezpropertyfeed' ); ?></a></li>
						<li><a href="#offmarket"><span class="dashicons dashicons-trash"></span> <?php echo __( 'Removing Properties', 'houzezpropertyfeed' ); ?></a></li>
						<li><a href="#media"><span class="dashicons dashicons-admin-media"></span> <?php echo __( 'Media Processing', 'houzezpropertyfeed' ); ?></a></li>
					</ul>
				</div>

				<div class="right-settings">

					<div class="buttons">

						<input type="submit" value="<?php echo __( 'Save changes', 'houzezpropertyfeed' ); ?>" class="button button-primary">

					</div>

					<div class="settings-panels">

						<div class="settings-panel" id="emailreports">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-import-email-reports.php' ); ?>
						</div>

						<div class="settings-panel" id="offmarket" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-import-off-market.php' ); ?>
						</div>

						<div class="settings-panel" id="media" style="display:none">
							<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-import-media.php' ); ?>
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