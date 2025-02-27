<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
/* delete after redirect */
if(isset($_GET['wt_pf_delete_log'])) 
{
	?>
	<script type="text/javascript">
		window.location.href='<?php echo admin_url('admin.php?page='.$this->module_id.'_log'); ?>';
	</script>
	<?php
}
?>
<div class="wt_pf_history_page">
	<h2 class="wp-heading-inline"><?php _e('Import Logs');?></h2>
	<p>
		<?php _e('Lists developer logs mostly required for debugging purposes. Options to view detailed logs are available along with delete and download(that can be shared with the support team in case of issues).');?>
	</p>

	<?php
	$log_path=Webtoffee_Product_Feed_Sync_Basic_Log::$log_dir;
	$log_files = glob($log_path.'/*'.'.log');
	if(is_array($log_files) && count($log_files)>0)
	{
            foreach ($log_files as $key => $value) {                  
                $date_time = str_replace('.log','',substr($value, strrpos($value, '_') + 1));
                $d = DateTime::createFromFormat('Y-m-d H i s A', $date_time);
                if ($d == false) {
                    $index = $date_time;
                } else {
                   $index = $d->getTimestamp();
                }
                $indexed_log_files[$index] = $value;                                
            }           
		krsort($indexed_log_files);
                $log_files = $indexed_log_files;

		?>
	<div class="wt_pf_bulk_action_box">
		<select class="wt_pf_bulk_action wt_pf_select">
			<option value=""><?php _e( 'Bulk Actions' ); ?></option>
			<option value="delete"><?php _e( 'Delete' ); ?></option>
		</select>
		<button class="button button-primary wt_pf_bulk_action_logs_btn" type="button" style="float:left;"><?php _e( 'Apply' ); ?></button>
	</div>
		<table class="wp-list-table widefat fixed striped history_list_tb log_list_tb">
		<thead>
			<tr>
				<th width="100">
					<input type="checkbox" name="" class="wt_pf_history_checkbox_main">
					<?php _e("No."); ?>
				</th>
				<th class="log_file_name_col"><?php _e("File"); ?></th>
				<th><?php _e("Actions"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach($log_files as $log_file)
		{
			$i++;
			$file_name=basename($log_file);
			?>
			<tr>
				<th>
					<input type="checkbox" value="<?php echo $file_name;?>" name="logfile_name[]" class="wt_pf_history_checkbox_sub">
					<?php echo $i;?>						
				</td>
				<td class="log_file_name_col"><a class="wt_pf_view_log_btn" data-log-file="<?php echo $file_name;?>"><?php echo $file_name; ?></a></td>
				<td>
					<a class="wt_pf_delete_log" data-href="<?php echo str_replace('_log_file_', $file_name, $delete_url);?>"><?php _e('Delete'); ?></a>
					| <a class="wt_pf_view_log_btn" data-log-file="<?php echo $file_name;?>"><?php _e("View");?></a>
					| <a class="wt_pf_download_log_btn" href="<?php echo str_replace('_log_file_', $file_name, $download_url);?>"><?php _e("Download");?></a>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<?php
	}else
	{
		?>
		<h4 class="wt_pf_history_no_records"><?php _e( "No logs found." ); ?>
			<?php if ( Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings( 'enable_import_log' ) == 0 ): ?>		
				<span> <?php _e( 'Please enable import log under' ); ?> <a target="_blank" href="<?php echo admin_url( 'admin.php?page=wt_import_export_for_woo_basic' ) ?>"><?php _e( 'settings' ); ?></a></span>		
			<?php endif; ?>
		</h4>
		<?php
	}
	?>
</div>