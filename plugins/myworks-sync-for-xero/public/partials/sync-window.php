<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://myworks.software
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/public/partials
 */
 
global $MWXS_L;
global $wpdb;

$is_valid_user = false;
if(is_user_logged_in() && current_user_can('manage_woocommerce')){
	$is_valid_user = true;
}

if(!$is_valid_user){
	$MWXS_L->get_html_msg(__('Not Authorized','myworks-sync-for-xero'),__('Not Authorized','myworks-sync-for-xero'));
	die;
}

$is_valid_sync = true;
$ajaxurl = admin_url( 'admin-ajax.php' );

$sync_type = $MWXS_L->var_g('sync_type');
if($sync_type!='push' && $sync_type!='pull'){
	$is_valid_sync = false;
}

$item_type = $MWXS_L->var_g('item_type');
if($item_type!='customer' && $item_type!='order' && $item_type!='product' && $item_type!='variation'){
	$is_valid_sync = false;
}

if(!$is_valid_sync){
	$MWXS_L->get_html_msg(__('Invalid Sync Type','myworks-sync-for-xero'),__('Invalid Sync Type','myworks-sync-for-xero'));
	die;
}

$tot = 0;
$item_ids = $MWXS_L->var_g('item_ids');

if($item_ids!=''){	
	$item_ids_arr = explode(',',$item_ids);
	if(is_array($item_ids_arr) && count($item_ids_arr)){
		if($sync_type == 'pull'){
			$item_ids_arr = array_map('trim',$item_ids_arr);
			$item_ids_arr = array_filter($item_ids_arr, function($a) { return ($a !== ''); });
		}else{
			$item_ids_arr = array_map('intval',$item_ids_arr);
			$item_ids_arr = array_filter($item_ids_arr, function($a) { return ($a !== 0); });
		}		
	}
	
	#$MWXS_L->_p($item_ids_arr);
	
	if(is_array($item_ids_arr) && count($item_ids_arr)){
		$tot = count($item_ids_arr);
		$item_ids = implode(',',$item_ids_arr);		
	}
}

$p_title = 'Item';

if(!$is_valid_sync){
	$item_ids = '';
}

$item_type_txt = '';
if($item_ids!=''){
	$item_type_txt = $item_type;
	
	if($sync_type=='push'){
		$p_title = __('Export ','myworks-sync-for-xero').ucfirst($item_type_txt);
	}
	if($sync_type=='pull'){
		$p_title = __('Import ','myworks-sync-for-xero').ucfirst($item_type_txt);
	}
	
}

$ext_options = array();
if(isset($_GET['fwop']) && $_GET['fwop'] == 1){
	$ext_options['from_wc_order_page'] = true;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo __('Xero Sync Progress','myworks-sync-for-xero');?></title>
		<link rel='stylesheet' href='<?php echo esc_url(plugin_dir_url( dirname(dirname(__FILE__)) )) . 'admin/css/bootstrap.min.css';?>' type='text/css' media='all' />
		<script type='text/javascript' src="<?php echo esc_url(plugin_dir_url( dirname(dirname(__FILE__)) )) . 'public/js/jquery.min.js';?>"></script>
		<script type='text/javascript' src='<?php echo esc_url(plugin_dir_url( dirname(dirname(__FILE__)) )) . 'admin/js/bootstrap.min.js';?>'></script>
		
		<link rel='stylesheet' href="<?php echo esc_url(plugin_dir_url( dirname(dirname(__FILE__)) )) . 'admin/css/font-awesome.css';?>" type='text/css' media='all' />
		<link rel='stylesheet' href="<?php echo esc_url(plugin_dir_url( dirname(__FILE__) )) . 'css/myworks-wc-xero-sync-window.css';?>" type='text/css' media='all' />
		
		<?php if($is_valid_sync):?>
		<script type="text/javascript">
			function addLog(message) {
				var r = document.getElementById('results');
				r.innerHTML += message;// + '<br>'
				r.scrollTop = r.scrollHeight;
			}

			var jqXHR = 0;

			function stop_ajax(){
				addLog('Process Interrupted<br>');
				jQuery('#stop_process').val(1);
				if(jqXHR){
					jqXHR.abort();
					document.getElementById('sw_img').src = '';
				}
				
				
			}

			var item_ids = '<?php echo $MWXS_L->escape($item_ids);?>';
			var item_type = '<?php echo $MWXS_L->escape($item_type);?>';

			function start_ajax_push(){	
				if(item_ids!=''){
					//
					var ids_arr = item_ids.split(',');
					if(!jQuery.isEmptyObject(ids_arr)){
						jQuery('#sw_cur_item').html('1');
						var i_last_id = ids_arr[ids_arr.length-1];
						
						var complete_msg = '';
						 var i = 0;
						 
						 document.getElementById('sw_img').src = '<?php echo esc_url( plugins_url( 'image/ajax-loader.gif', dirname(__FILE__) ) );?>';	
						 document.getElementById('progressor').setAttribute('aria-valuenow',0);
						 document.getElementById('progressor').style='min-width: 2em;';
						 document.getElementById('progressor').innerHTML='0%';
						 
						 loop_ajax_func(i,ids_arr,i_last_id);
						 
					}
				}
			}


			function loop_ajax_func(i,ids_arr,i_last_id){
				var cur_item = i+1;
				if(jQuery('#stop_process').val()==0){		
					var ai_id = ids_arr[i];					
					var ajaxurl = '<?php echo esc_url_raw($ajaxurl);?>';
					var sync_type = '<?php echo $MWXS_L->escape($sync_type);?>';
					
					var data = '';
					
					var tot_item = ids_arr.length;
					
					var sync_action = 'myworks_wc_xero_sync_window';
					var window_xero_sync = jQuery('#window_xero_sync').val();
					data = {
						"action": sync_action,
						"item_type": item_type,
						"sync_type": sync_type,
						"id": ai_id,
						"tot_item": tot_item,
						"cur_item": cur_item,
						"window_xero_sync":window_xero_sync
					};
					
					if(sync_type=='pull'){
						complete_msg = "<span class='success_green'><?php echo ucfirst($MWXS_L->escape($item_type_txt));?> Pull Complete</span>";
					}else if(sync_type=='push'){
						complete_msg = "<span class='success_green'><?php echo ucfirst($MWXS_L->escape($item_type_txt));?> Push Complete</span>";
					}else{
						complete_msg = "<span class='success_green'><?php echo ucfirst($MWXS_L->escape($item_type_txt));?> Sync Complete</span>";
					}		
					
					if(data!=''){
						data_json = jQuery.param(data);			
						
						jqXHR = jQuery.ajax({
						   type: "POST",
						   url: ajaxurl,
						   data: data_json,
						   cache:  false ,
						   datatype: "json",
						   success: function(result){							  
							   if(result!='' && result!='0'){
								try {									
									result = jQuery.parseJSON(result);						
									if(ai_id==i_last_id){
										addLog(result.message);
										addLog(complete_msg);
										
										jQuery('#process_txt').html('Completed');
										
										var pBar = document.getElementById('progressor');
										
										pBar.setAttribute('aria-valuenow', pBar.getAttribute('aria-valuemax'));
										
										document.getElementById('sw_img').src = '';
										
										var prgs = result.progress;
										if(prgs>100){
											prgs = 100;
										}
										
										pBar.style.width=result.progress+'%';
										pBar.innerHTML   =  prgs + "%";
										
										document.getElementById('sw_tot_item').innerHTML   = result.total;
										document.getElementById('sw_cur_item').innerHTML   = result.cur;
										
										document.getElementById("start_p_bt").disabled = true;
										document.getElementById("stop_p_bt").disabled = true;										
										
										jQuery('#close_btn_id').addClass('green_btn');
										jQuery('#progressor').removeClass('progress-bar-animated');
										jQuery('#progressor').addClass('progress-bar-success');
										jQuery('#stop_p_bt').addClass('p_complete');

									}else{
										addLog(result.message);
										
										var pBar = document.getElementById('progressor');
										
										var prgs = result.progress;
										if(prgs>100){
											prgs = 100;
										}
										
										pBar.setAttribute('aria-valuenow', prgs);
										
										pBar.style.width=result.progress+'%';
										pBar.innerHTML   = prgs  + "%";
										
										document.getElementById('sw_tot_item').innerHTML   = result.total;
										document.getElementById('sw_cur_item').innerHTML   = result.cur;
										
									}
									
								}catch(err) {
									document.getElementById('sw_cur_item').innerHTML   = data.cur_item;
									addLog('<span class=\'error_red\'>Error occurred for #'+ai_id+'\n'+err.message+'</span>');
								}
								
							   }else{						   
								   addLog('<span class=\'error_red\'>Error occurred for #'+ai_id+'\n Invalid response</span>');
								   document.getElementById('sw_cur_item').innerHTML   = data.cur_item;
							   }
							   i++;
							   if( i < ids_arr.length ){
								   loop_ajax_func(i,ids_arr,i_last_id);
							   }
							   
						   },
						   error: function(result) {
							   addLog('<span class=\'error_red\'>Request error occurred for #'+ai_id+'</span>');							   
							   document.getElementById('sw_cur_item').innerHTML   = data.cur_item;
							   i++;
							   if( i < ids_arr.length ){
								   loop_ajax_func(i,ids_arr,i_last_id);
							   }
							   
						   }
						});
						
					}
					
					
				}else{					
					stop_ajax();
					document.getElementById('sw_img').src = '';
				}
			}
		</script>
		<?php endif;?>
	</head>
	
	<body <?php if($is_valid_sync){echo 'onload="start_ajax_push();"';}?>>
		<?php if($is_valid_sync):?>
		<div class="sw_div">
			<h3><?php echo $MWXS_L->escape($p_title);?></h3>
			<h5><?php echo __('Total','myworks-sync-for-xero');?>: <?php echo $MWXS_L->escape($tot);?></h5>

			<div class="progress">
				<div id='progressor' class="progress-bar progress-bar-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
				0%
				</div>
			</div>
			
			<div class="sw_process">
				<b id="process_txt"><?php echo __('Processing','myworks-sync-for-xero');?>:</b>
				&nbsp;
				<span id="sw_cur_item">0</span>/<span id="sw_tot_item"><?php echo $MWXS_L->escape($tot);?></span>
				&nbsp;
				<span id="sw_loading_img"><img id="sw_img" src="" alt=""></span>
			</div>

			<div id="results"  class="sw_result"></div>

			<div class="sw_close">
				<input style="display:none;" id="start_p_bt" class="btn btn-info m_top10" type="button" onclick="start_ajax_push();"  value="Start Pushing" />

				<input id="stop_p_bt" class="btn btn-danger m_top10" type="button" onclick="stop_ajax();"  value="Stop Process" />

				<button id="close_btn_id" class="btn btn-default right-btn" onclick="javascript:stop_ajax();self.close ();"><?php echo __('Close','myworks-sync-for-xero');?></button>

				<input type="hidden" id="stop_process" value="0">
				<?php wp_nonce_field( 'myworks_wc_xero_sync_window', 'window_xero_sync' ); ?>
			</div>
		</div>
		<?php else:?>
		<h1><?php echo __('Error','myworks-sync-for-xero');?>!</h1>
		<?php endif;?>
	</body>
</html>