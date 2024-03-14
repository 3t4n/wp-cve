<div class="hpf-admin-settings-body wrap">

	<div class="hpf-admin-settings-automatic-imports">

		<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

		<h1><?php echo __( 'Automatic Exports', 'houzezpropertyfeed' ); ?></h1>

		<?php
			if ( $automatic_exports_table->has_items() )
			{
				echo '<div class="automatic-exports-table">';
					echo $automatic_exports_table->display();
				echo '</div>';

				if ( $run_now_button )
				{
					echo '<a href="' . admin_url('admin.php?page=houzez-property-feed-export&custom_property_export_cron=houzezpropertyfeedcronhook') . '" class="button">Manually Execute Export</a>';
				}
			}
			else
			{
		?>

		<div class="no-imports-exports">

			<h2><?php echo __( 'Your automatic exports will appear here', 'houzezpropertyfeed' ); ?></h2>

			<p>You don't have any exports running at the moment. Why not go ahead and try creating one now?</p>

			<p><a href="<?php echo admin_url('admin.php?page=houzez-property-feed-export&action=addexport'); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-plus-alt2"></span> <?php echo __( 'Create New Export', 'houzezpropertyfeed' ); ?></a></p>

			<p><strong>Need help?</strong> Our <a href="https://houzezpropertyfeed.com/documentation/" target="_blank">in-depth documentation</a> will guide you through the process.</p>

		</div>

		<?php
			}
		?>

	</div>

</div>