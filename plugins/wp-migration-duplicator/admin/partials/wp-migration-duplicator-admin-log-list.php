<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

?>
  <style type="text/css">
.wt_mgdp_history_page{ padding:15px; }
.history_list_tb td, .history_list_tb th{ text-align:center; }
.history_list_tb tr th:first-child{ text-align:left; }
 .wt_mgdp_delete_log{ cursor:pointer; }
.wt_mgdp_history_no_records{float:left; width:100%; margin-bottom:55px; margin-top:20px; text-align:center; background:#fff; padding:15px 0px; border:solid 1px #ccd0d4;}
.wt_mgdp_view_log_btn{ cursor:pointer; }
.wt_mgdp_view_log{  }
.wt_mgdp_log_loader{ width:100%; height:200px; text-align:center; line-height:150px; font-size:14px; font-style:italic; }
.wt_mgdp_log_container{ padding:25px; }
.log_view_tb th, .log_view_tb td{ text-align:center; }
.log_list_tb .log_file_name_col{ text-align:left; }
.wt_iew_raw_log{ text-align:left; font-size:14px; }
</style>

<div class="wt_mgdp_history_page">
	<h2 class="wp-heading-inline"><?php _e('Logs');?></h2>
	<p>
		<?php _e('Lists the developer logs required for debugging purposes. You can share the downloaded log files with the support team in case of issues.');?>
	</p>

	<?php
	$log_path= Wp_Migration_Duplicator::$backup_dir . "/logs";
	$log_files = glob($log_path.'/*'.'.log');

	if(is_array($log_files) && count($log_files)>0)
	{
            foreach ($log_files as $key => $value) {      

                if(strstr($value,'FATAL_ERROR_LOG_')){
                    $date_time_arr = explode('FATAL_ERROR_LOG_', $value);
                    $date_time = str_replace('.log','',$date_time_arr[1]);
                    $date_time = str_replace('_','-',$date_time);
                    $date_time = $date_time.'_00_00_00';
                    $dt = DateTime::createFromFormat('d-M-Y_H_i_s', $date_time);
                    $fatel_time =$dt->getTimestamp();
                    $date_time = date('Y-m-d_H_i_s_A',$fatel_time);
                }else{
                    $value_arr = strstr($value,'Export_') ? explode('Export_', $value):explode('Import_', $value);  
                    $date_time = '';
                    if(isset($value_arr[1]) && !empty($value_arr[1])){
                        $date_time = str_replace('.log','',$value_arr[1]);
                    }
                }
                $d = DateTime::createFromFormat('Y-m-d_H_i_s_A', $date_time);
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
		<table class="wp-list-table widefat fixed striped history_list_tb log_list_tb">
		<thead>
			<tr>
				<th class="log_file_name_col"><?php _e("File"); ?></th>
				<th><?php _e("Actions"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($log_files as $log_file)
		{
			$file_name=basename($log_file);
			?>
			<tr>
				<td class="log_file_name_col"><a class="wt_mgdp_view_log_btn" data-log-file="<?php echo esc_attr($file_name);?>"><?php echo esc_attr($file_name); ?></a></td>
				<td>
					<a class="wt_mgdp_delete_log" href="<?php echo esc_url(str_replace('_log_file_', $file_name, $delete_url));?>"><?php _e('Delete'); ?></a>
					| <a class="wt_mgdp_view_log_btn" data-log-file="<?php echo esc_attr($file_name);?>"><?php _e("View");?></a>
					| <a class="wt_mgdp_download_log_btn" href="<?php echo esc_url(str_replace('_log_file_', $file_name, $download_url));?>"><?php _e("Download");?></a>
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
		<h4 class="wt_mgdp_history_no_records"><?php _e("No logs found."); ?></h4>
		<?php
	}
	?>
</div>