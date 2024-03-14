<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
global $wpdb;

$page_url = admin_url('admin.php?page=myworks-wc-xero-sync-log');

$table = $MWXS_L->gdtn('log');

#Delete
$del_log_id = (int) $MWXS_L->var_g('del_log',0);
if($del_log_id > 0){
	$wpdb->query($wpdb->prepare("DELETE FROM `".$table."` WHERE `id` = %d",$del_log_id));
	$MWXS_L->redirect($page_url);
}

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();
 
$MWXS_L->set_and_get('log_search');
$log_search = $MWXS_L->get_session_val('log_search');

$log_search = $MWXS_L->sanitize($log_search);

$whr = '';
if(!empty($log_search)){
	$whr.=$wpdb->prepare(" AND (`details` LIKE '%%%s%%' OR `log_type` LIKE '%%%s%%' OR `log_title` LIKE '%%%s%%' ) ",$log_search,$log_search,$log_search);
}

$total_records = $wpdb->get_var("SELECT COUNT(*) FROM `".$table."` WHERE `id` >0 {$whr} ");

$page = $MWXS_L->get_page_var();
$offset = ( $page * $items_per_page ) - $items_per_page;

$log_q = "SELECT * FROM `".$table."` WHERE `id` >0 $whr ORDER BY `id` DESC LIMIT {$offset} , {$items_per_page}";
$log_data = $MWXS_L->get_data($log_q);
?>

<br><br>
<div class="container log-outr-sec mq_lp_cont">
	<div class="page_title"><h4><?php _e( 'Sync Log', 'myworks-sync-for-xero' );?></h4></div>
	<div class="mw_wc_filter">
		<input placeholder="Search Log" type="text" id="log_search" value="<?php echo esc_attr($log_search);?>">
		
		<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
	</div>
	
	<br>
	
	<div class="mq_lp_cdt">
		<?php _e( 'Current Datetime', 'myworks-sync-for-xero' );?>: <?php echo $MWXS_L->escape($MWXS_L->now('Y-m-d '));?> 
		<b><?php echo $MWXS_L->escape($MWXS_L->now('h:i:s A'));?></b>
	</div>
	
	<br>
	
	<div class="myworks-wc-qbo-sync-table-responsive">
		<table class="wp-list-table widefat fixed striped posts  menu-blue-bg">
			<thead>
				<tr>
					<th style="text-align:center;" width="8%">#</th>
					<th width="30%">&nbsp;</th>
					<th width="40%">Message</th>
					<th width="12%">Date</th>
					<th style="text-align:center;" width="10%">Action</th>
				</tr>
			</thead>
			
			<tbody id="mwqs-log-list">
				<?php if(is_array($log_data) && !empty($log_data)):?>
				<?php foreach($log_data as $data):?>
				
				<?php 
					$is_log_error  = (!$data['status'] || $data['status'] < 1)?true:false;
					$ls_class = ($is_log_error)?' cl_err':'';
					
					$details = $MWXS_L->get_log_page_details_field_data_formatted($data);
				?>
				
				<tr>
					<td style="text-align:center;"><?php echo (int) $data['id'];?></td>
					
					<td>
						<h4 class="mq_lp_lth"><?php echo $MWXS_L->escape($data['log_type'])?></h4>
						<div class="mq_lp_tbd<?php echo esc_attr($ls_class);?>">
							<?php echo stripslashes($MWXS_L->escape($data['log_title']));?>
						</div>
					</td>
					
					<td <?php echo ($is_log_error)?' style="color:#dd281a;"':'';?>>			
						<?php
							echo str_replace(
								['{LPDW_S}','{LPDX_S}','{LPDW_E}','{LPDX_E}','{LB}'],
								['<span class="lm_wid">','<span class="lm_qid">','</span>','</span>','<br>'],
								stripcslashes($MWXS_L->escape($details))
							);			
						?>
					</td>
					
					<td>
						<span class="mq_lp_ltime"><?php echo date('h:i:s A',strtotime($data['added_date']));?></span>
						<span><?php echo date('Y-m-d',strtotime($data['added_date']));?></span>
					</td>
					
					<td style="text-align:center;">
						<a class="mwqslld_btn" title="Delete" href="javascript:void(0);" onclick="javascript:if(confirm('<?php echo __('Are you sure, you want to delete this!','mw_wc_qbo_sync')?>')){window.location='<?php echo  esc_url($page_url);?>&del_log=<?php echo (int) $data['id']?>';}">x</a>						
						<?php $MWXS_L->get_log_page_view_in_xero_link($data);?>
					</td>
				</tr>
				
				<?php endforeach;?>
				<?php else:?>
				
				<tr>
					<td colspan="5">
						<span class="mwxs_tnd">
							<?php _e( 'No logs found.', 'myworks-sync-for-xero' );?>
						</span>
					</td>
				</tr>
				
				<?php endif;?>
			</tbody>
		</table>
	</div>
	<?php $MWXS_L->get_paginate_links($total_records,$items_per_page);?>
	
	<?php if($total_records > 0):?>
	<br>
	<div>
		<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_all_logs', 'clear_all_logs' ); ?>
		<button id="mwqs_clear_all_logs_btn"><?php _e( 'Clear Entire Log', 'myworks-sync-for-xero' );?></button>
		&nbsp;
		<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_all_log_errors', 'clear_all_log_errors' ); ?>
		<button id="mwqs_clear_all_log_errors_btn"><?php _e( 'Clear Error Logs', 'myworks-sync-for-xero' );?></button>
	</div>
	<?php endif;?>
</div>

<script type="text/javascript">
	function search_item(){
		let log_search = jQuery('#log_search').val();
		log_search = jQuery.trim(log_search);
		
		if(log_search!=''){
			window.location = '<?php echo esc_url($page_url);?>&log_search='+log_search;
		}else{
			alert('<?php echo __('Please enter search keyword.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){		
		window.location = '<?php echo esc_url($page_url);?>&log_search=';
	}
	
	<?php if($total_records > 0):?>
	jQuery(document).ready(function($){		
		$('#mwqs_clear_all_logs_btn').click(function(){
			if(confirm('<?php echo __('This will clear all log entries. OK to proceed?','myworks-sync-for-xero')?>')){
				var data = {
					"action": 'myworks_wc_xero_sync_clear_all_logs',
					"clear_all_logs": $('#clear_all_logs').val(),
				};
				
				var btn_text = $(this).html();
				var loading_msg = 'Loading...';
				$(this).html(loading_msg);
				
				$.ajax({
				   type: "POST",
				   url: ajaxurl,
				   data: data,
				   cache:  false ,
				   //datatype: "json",
				   success: function(result){
					   $('#mwqs_clear_all_logs_btn').html(btn_text);
					   if(result!=0 && result!=''){						 
						 window.location='<?php echo esc_url($page_url);?>';
					   }else{
						 alert('Error!');			 
					   }					   	
				   },
				   error: function(result) {
						$('#mwqs_clear_all_logs_btn').html(btn_text);
						alert('Error!');					
				   }
				});
			}
		});
		
		$('#mwqs_clear_all_log_errors_btn').click(function(){			
			if(confirm('<?php echo __('This will clear all error log entries. OK to proceed?','myworks-sync-for-xero')?>')){
				var data = {
					"action": 'myworks_wc_xero_sync_clear_all_log_errors',
					"clear_all_log_errors": $('#clear_all_log_errors').val(),
				};
				
				var btn_text = $(this).html();				
				var loading_msg = 'Loading...';
				$(this).html(loading_msg);
				
				$.ajax({
				   type: "POST",
				   url: ajaxurl,
				   data: data,
				   cache:  false ,
				   //datatype: "json",
				   success: function(result){
					    $('#mwqs_clear_all_log_errors_btn').html(btn_text);
					   if(result!=0 && result!=''){						 
						 window.location='<?php echo esc_url($page_url);?>';
					   }else{
						 alert('Error!');			 
					   }				  
				   },
				   error: function(result) {
						$('#mwqs_clear_all_log_errors_btn').html(btn_text);
						alert('Error!');					
				   }
				});
			}
		});
	});
	<?php endif;?>
</script>