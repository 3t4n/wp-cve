<div class="hpf-admin-settings-body wrap">

	<div class="hpf-admin-settings-logs">

		<?php include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-notice.php' ); ?>

		<h1><?php echo __( 'Import Logs', 'houzezpropertyfeed' ); ?></h1>

		<div class="log-buttons log-buttons-top">
			<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import&tab=logs'); ?>" class="button">Back To Logs</a>
		
			<?php
				if ( $previous_instance !== false )
				{
					echo ' <a href="' . admin_url( 'admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . (int)$previous_instance . ( isset($_GET['import_id']) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '" class="button">Previous Log</a> ';
				}
				if ( $next_instance !== false )
				{
					echo ' <a href="' . admin_url( 'admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . (int)$next_instance . ( isset($_GET['import_id']) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '" class="button">Next Log</a> ';
				}
			?>
		</div>

		<?php 
			echo '<div class="logs-table">';
				echo $logs_view_table->display(); 
			echo '</div>';
		?>

		<div class="log-buttons log-buttons-bottom">
			<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import&tab=logs'); ?>" class="button">Back To Logs</a>
		
			<?php
				if ( $previous_instance !== false )
				{
					echo ' <a href="' . admin_url( 'admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . (int)$previous_instance . ( isset($_GET['import_id']) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '" class="button">Previous Log</a> ';
				}
				if ( $next_instance !== false )
				{
					echo ' <a href="' . admin_url( 'admin.php?page=houzez-property-feed-import&tab=logs&action=view&log_id=' . (int)$next_instance . ( isset($_GET['import_id']) ? '&import_id=' . (int)$_GET['import_id'] : '' ) ) . '" class="button">Next Log</a> ';
				}
			?>
		</div>

	</div>

</div>