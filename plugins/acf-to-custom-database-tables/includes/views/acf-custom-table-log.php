<?php
    if(!isset($_GET['acf_ct_post_id']) || empty($_GET['acf_ct_post_id'])){
		echo esc_html('Invalid Request');
		return;
    }

	$acf_ct_post_id = intval($_GET['acf_ct_post_id']);
	$logs = Acf_ct_database_manager::get_logs($acf_ct_post_id);
	$table_name = Acfct_utils::get_custom_table_name($acf_ct_post_id, true);

    Acfct_utils::set_page_title('Logs');
?>
<div class="metabox-holder acf-ct-columns-2">
	<div class="postbox acf-ct-column-1">
		<div>
			<?php
			if(empty($logs)){
				echo '<div class="pad-10">No Logs found for table <b>' . esc_html($table_name) . '</b></div>';
			}else {
				?>
				<table class="wp-list-table widefat fixed striped tags acf-ct-table acf-ct-label-list">
					<thead>
					<tr>
						<th width="50%">Logs of <?php echo esc_html($table_name); ?></th>
						<th>User</th>
						<th>Time</th>
					</tr>
					</thead>
					<tbody id="the-list" data-wp-lists="list:tag">
					<?php
					foreach ($logs as $log) {
						foreach ($log['log'] as $col_log){
							echo '<tr>';
							echo '<td>'. esc_html($col_log) .'</td>';
							echo '<td>'. esc_html($log['user']) .'</td>';
							echo '<td>'. esc_html(date('j F Y, h:i:s a',$log['time'])) .'</td>';
							echo '</tr>';
						}
					}
					?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
	<div class="acf-ct-column-2">
		<?php include_once 'partials/right-sidebar.php';?>
	</div>
</div>
