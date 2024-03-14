<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
global $wpdb;

$page_url = admin_url('admin.php?page=myworks-wc-xero-sync-queue');

$table = $MWXS_L->gdtn('queue');

$del_queue_id = (int) $MWXS_L->var_g('del_queue',0);
if($del_queue_id > 0){
	$wpdb->query($wpdb->prepare("DELETE FROM `".$table."` WHERE `id` = %d ",$del_queue_id));
	$MWXS_L->redirect($page_url);
}

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('queue_search');
$queue_search = $MWXS_L->get_session_val('queue_search');
$queue_search = $MWXS_L->sanitize($queue_search);

$MWXS_L->set_and_get('queue_st_search');
$queue_st_search = $MWXS_L->get_session_val('queue_st_search');
$queue_st_search = $MWXS_L->sanitize($queue_st_search);

if(empty($queue_st_search)){
	$queue_st_search = 'Pending';
}

$whr = 'AND `run` = 0';
if($queue_st_search == 'Previous'){
	$whr = 'AND `run` = 1';
}

if($queue_st_search == 'All'){
	$whr = '';
}

if(!empty($queue_search)){
	$whr.=$wpdb->prepare(" AND (`item_type` LIKE '%%%s%%' OR `item_action` LIKE '%%%s%%' OR `item_id` = %s ) ",$queue_search,$queue_search,$queue_search);
}

$total_records = $wpdb->get_var("SELECT COUNT(*) FROM `".$table."` WHERE `id` >0 {$whr} ");

$page = $MWXS_L->get_page_var();
$offset = ( $page * $items_per_page ) - $items_per_page;

$queue_q = "SELECT * FROM `".$table."` WHERE `id` >0 {$whr} ORDER BY `id` DESC LIMIT {$offset} , {$items_per_page}";

$queue_data = $MWXS_L->get_data($queue_q);

# Queue cron countdown
$show_countdown = false;

$cdt = date('Y-m-d H:i:s');
$next_queue_cron_run = wp_next_scheduled('mw_wc_xero_sync_queue_cron_hook');

if(!empty($next_queue_cron_run)){
	$show_countdown = true;

	$s_ncrt_cdt_diff = $next_queue_cron_run-strtotime($cdt);

	$next_queue_cron_run = date('Y-m-d H:i:s',$next_queue_cron_run);
	$start_date = new DateTime($cdt);
	$since_start = $start_date->diff(new DateTime($next_queue_cron_run));

	$min_d = $since_start->i;
	$min_s = $since_start->s;

	$qcit = $MWXS_L->get_option('mw_wc_xero_sync_queue_cron_interval_time');
	if(empty($qcit)){$qcit = 'MWXS_5min';}

	$ncrt_int = str_replace(array('MWXS_','min'),'',$qcit);
	$ncrt_int = (int) $ncrt_int;

	if($ncrt_int < 5){
		$ncrt_int = 5;
	}

	$ncrt_int = $ncrt_int*60;
}

?>

<br><br>
<div class="container queue-outr-sec">
	<div class="page_title"><h4><?php _e( 'Queue', 'myworks-sync-for-xero' );?></h4></div>
	<div class="mw_wc_filter">
		<input placeholder="Search Queue" type="text" id="queue_search" value="<?php echo esc_attr($queue_search);?>">
		&nbsp;

		<select id="queue_st_search" style="width:130px !important;">
			<?php 
				$MWXS_L->only_option(
					$queue_st_search,
					array('Pending'=>'Pending','Previous'=>'Previous','All'=>'All')
				);
			?>
		</select>
		
		<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
	</div>
	
	<br>
	
	<?php if($show_countdown):?>
	<div id="mwqs_q_ncr_tdv">
		<h3 style="text-align:center"><?php echo $MWXS_L->escape($min_d);?> min, <?php echo $MWXS_L->escape($min_s);?> sec</h3>
		<p style="text-align:center; margin-top:-20px;">until next queue sync</p>
	</div>
	<?php endif;?>
	
	<div class="myworks-wc-qbo-sync-table-responsive">
		<table class="wp-list-table widefat fixed striped posts  menu-blue-bg">
			<thead>
				<tr>
					<th width="10%">#</th>
					<th width="25%">Item Type</th>
					<th width="15%">Item Action</th>
					<th width="25%">Item ID</th>
					<th width="15%">Added</th>
					<th style="text-align:center;" width="10%">Action</th>
				</tr>
			</thead>
			
			<tbody id="mwqs-queue-list">
				<?php if(is_array($queue_data) && !empty($queue_data)):?>
				<?php foreach($queue_data as $data):?>
							
				<tr <?php echo ($data['run'] == 1)?' style="opacity:0.5;"':'';?>>
					<td><?php echo (int) $data['id'];?></td>
					<td><?php echo $MWXS_L->escape($data['item_type'])?></td>
					<td><?php echo $MWXS_L->escape($data['item_action'])?></td>
					<td><?php echo (!empty($data['xero_id']))?$MWXS_L->escape($data['xero_id']):$MWXS_L->escape($data['item_id'])?></td>		
					<td><?php echo $MWXS_L->escape($data['added_date'])?></td>
					<td style="text-align:center;">
						<a class="mwqslld_btn" title="Delete" href="javascript:void(0);" onclick="javascript:if(confirm('<?php echo __('Are you sure, you want to delete this!','mw_wc_qbo_sync')?>')){window.location='<?php echo  esc_url($page_url);?>&del_queue=<?php echo (int) $data['id']?>';}">x</a>
					</td>

				</tr>

				<?php endforeach;?>
				<?php else:?>
					
				<tr>
					<td colspan="6">
						<span class="mwxs_tnd">
							<?php _e( 'No queue entries found.', 'myworks-sync-for-xero' );?>
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
		<?php echo str_repeat('&nbsp;',3);?>
		<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_all_pending_queues', 'clear_all_pending_queues' ); ?>		
		<button id="mwqs_clear_all_pending_queues_btn" class="mwxsb-s"><?php _e( 'Clear all pending queues', 'myworks-sync-for-xero' );?></button>
		&nbsp;
		
		<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_all_queues', 'clear_all_queues' ); ?>		
		<button id="mwqs_clear_all_queues_btn" class="mwxsb-b"><?php _e( 'Clear all queues', 'myworks-sync-for-xero' );?></button>
	</div>
	<br>
	<br>
	<?php endif;?>
</div>

<script type="text/javascript">
	function search_item(){		
		let queue_search = jQuery('#queue_search').val();
		queue_search = jQuery.trim(queue_search);
		
		let queue_st_search = jQuery('#queue_st_search').val();
		queue_st_search = jQuery.trim(queue_st_search);
		
		let q_st_s_sv = '<?php echo $MWXS_L->escape($queue_st_search);?>';
		if(queue_search == '' && queue_st_search == 'Pending' && q_st_s_sv == 'Pending'){			
			queue_st_search = '';
		}
		
		if(queue_search!='' || queue_st_search!=''){
			window.location = '<?php echo esc_url($page_url);?>&queue_search='+queue_search+'&queue_st_search='+queue_st_search;
		}else{
			alert('<?php echo __('Please enter or select search term.','myworks-sync-for-xero')?>');
		}
	}

	function reset_item(){		
		window.location = '<?php echo esc_url($page_url);?>&queue_search=&queue_st_search=';
	}

	function mwxs_queue_cron_counter(duration){
		var timer = duration, minutes, seconds;
		var x = setInterval(function() {
			minutes = parseInt(timer / 60, 10);
			seconds = parseInt(timer % 60, 10);
			
			if(seconds>=0){
				document.getElementById("mwqs_q_ncr_tdv").innerHTML = '<h3 style="text-align:center">'+minutes+' min, '+seconds+' sec</h3><p style="text-align:center; margin-top:-20px;">until next queue sync</p>';
			}			

			if (--timer < 0) {
				timer = '<?php echo esc_js($ncrt_int);?>';
			}						
		}, 1000);
	}	
	
	jQuery(document).ready(function($){
		<?php if($total_records > 0):?>		
		$('#mwqs_clear_all_pending_queues_btn').click(function(){
			if(confirm('<?php echo __('This will clear all pending queue entries. OK to proceed?','myworks-sync-for-xero')?>')){
				var data = {
					"action": 'myworks_wc_xero_sync_clear_all_pending_queues',
					"clear_all_pending_queues": $('#clear_all_pending_queues').val(),
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
					   $('#mwqs_clear_all_pending_queues_btn').html(btn_text);
					   if(result!=0 && result!=''){						 
						 window.location='<?php echo esc_url($page_url);?>';
					   }else{
						 alert('Error!');			 
					   }					   	
				   },
				   error: function(result) {
						$('#mwqs_clear_all_pending_queues_btn').html(btn_text);
						alert('Error!');					
				   }
				});
			}
		});
		
		$('#mwqs_clear_all_queues_btn').click(function(){
			if(confirm('<?php echo __('This will clear all queue entries. OK to proceed?','myworks-sync-for-xero')?>')){
				var data = {
					"action": 'myworks_wc_xero_sync_clear_all_queues',
					"clear_all_queues": $('#clear_all_queues').val(),
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
					    $('#mwqs_clear_all_queues_btn').html(btn_text);
					   if(result!=0 && result!=''){						 
						 window.location='<?php echo esc_url($page_url);?>';
					   }else{
						 alert('Error!');			 
					   }				  
				   },
				   error: function(result) {
						$('#mwqs_clear_all_queues_btn').html(btn_text);
						alert('Error!');					
				   }
				});
			}
		});
		<?php endif;?>

		<?php if($show_countdown):?>
		mwxs_queue_cron_counter('<?php echo esc_js($s_ncrt_cdt_diff);?>');
		<?php endif;?>
	});	
</script>