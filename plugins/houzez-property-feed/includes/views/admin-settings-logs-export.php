<div class="hpf-admin-settings-body wrap">

	<div class="hpf-admin-settings-logs">

		<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

		<h1><?php echo __( 'Export Logs', 'houzezpropertyfeed' ); ?></h1>

		<?php 
			echo '<div class="logs-table">';
				echo $logs_table->display(); 
			echo '</div>';
		?>

	</div>

</div>