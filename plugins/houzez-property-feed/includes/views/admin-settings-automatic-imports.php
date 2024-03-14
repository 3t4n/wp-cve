<div class="hpf-admin-settings-body wrap">

	<div class="hpf-admin-settings-automatic-imports">

		<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

		<h1><?php echo __( 'Automatic Imports', 'houzezpropertyfeed' ); ?></h1>

		<?php
			if ( $automatic_imports_table->has_items() )
			{
				echo '<div class="automatic-imports-table">';
					echo $automatic_imports_table->display();
				echo '</div>';

				if ( $run_now_button )
				{
					echo '<a href="' . admin_url('admin.php?page=houzez-property-feed-import&custom_property_import_cron=houzezpropertyfeedcronhook') . '" class="button">Manually Execute Import</a>';
				}
			}
			else
			{
		?>

		<div class="no-imports-exports">

			<h2><?php echo __( 'Your automatic imports will appear here', 'houzezpropertyfeed' ); ?></h2>

			<p>You don't have any imports running at the moment. Why not go ahead and try creating one now?</p>

			<p><a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import&action=addimport'); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-plus-alt2"></span> <?php echo __( 'Create New Import', 'houzezpropertyfeed' ); ?></a></p>

			<p><strong>Need help?</strong> Our <a href="https://houzezpropertyfeed.com/documentation/" target="_blank">in-depth documentation</a> will guide you through the process.</p>

		</div>

		<?php
			}
		?>

	</div>

</div>