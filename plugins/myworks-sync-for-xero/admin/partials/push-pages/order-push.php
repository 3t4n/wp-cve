<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'order';

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('order_push_search');
$order_push_search = $MWXS_L->get_session_val('order_push_search');

$MWXS_L->set_and_get('order_date_from');
$order_date_from = $MWXS_L->get_session_val('order_date_from');

$MWXS_L->set_and_get('order_date_to');
$order_date_to = $MWXS_L->get_session_val('order_date_to');

$MWXS_L->set_and_get('order_status_srch');
$order_status_srch = $MWXS_L->get_session_val('order_status_srch');

$total_records = $this->dlobj->count_wc_order_list($order_push_search,$order_date_from,$order_date_to,$order_status_srch);

$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$Limit = ' '.$offset.' , '.$items_per_page;

$wc_order_list = $this->dlobj->get_wc_order_list(false,$Limit,$order_push_search,$order_date_from,$order_date_to,$order_status_srch);
#$MWXS_L->_p($wc_order_list);

# Static data
$wc_order_statuses = wc_get_order_statuses();

$wc_currency_symbol = get_woocommerce_currency_symbol();
#$wc_currency_symbol = '$';

# Syns Status Related
$order_id_num_arr = array();

require_once plugin_dir_path( __FILE__ ) . 'push-nav.php';
?>
<style>	
	span.ss_pf_span{display:none;}
	.x_ss{display:none;}
</style>

<div class="container">
	<div class="page_title"><h4><?php _e( 'Order Push', 'myworks-sync-for-xero' );?></h4></div>
	<div class="card qo-push-responsive">
		<div class="card-content">			
			<div class="col s12 m12 l12">
				<div class="panel panel-primary">
					<div class="mw_wc_filter">
						<span class="search_text">Search</span>
						&nbsp;
						
						<input placeholder="<?php echo __('Name / Company / ID / NUM','myworks-sync-for-xero')?>" type="text" id="order_push_search" value="<?php echo esc_attr($order_push_search);?>">
						&nbsp;
						
						<input style="width:130px;" class="mwqs_datepicker" placeholder="<?php echo __('From yyyy-mm-dd','myworks-sync-for-xero')?>" type="text" id="order_date_from" value="<?php echo esc_attr($order_date_from);?>">
						&nbsp;
						
						<input style="width:130px;" class="mwqs_datepicker" placeholder="<?php echo __('To yyyy-mm-dd','myworks-sync-for-xero')?>" type="text" id="order_date_to" value="<?php echo esc_attr($order_date_to);?>">
						&nbsp;
						
						<span>
							<select style="width:130px;" name="order_status_srch" id="order_status_srch">
								<option value="">All</option>
								<?php $MWXS_L->only_option($order_status_srch,$wc_order_statuses);?>
							</select>
						</span>
						
						<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
					</div>
					<br>
					
					<?php if($total_records > 0):?>
					<div class="row">
						<div class="input-field col s12 m12 14">
							<button id="push_selected_order_btn" class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php echo __('Push Selected Orders','myworks-sync-for-xero')?>
							</button>										
						</div>
					</div>
					<br>
					<?php endif;?>
					
					<div class="table-m">
						<div class="myworks-wc-qbo-sync-table-responsive">
							<table id="mwqs_invoice_push_table" class="table tablesorter">
								<thead>
									<tr>
										<th width="2%">
											<input type="checkbox" onclick="javascript:mw_xero_sync_check_all(this,'order_push_')">
										</th>				
										<th width="13%">Order Number / ID</th>
										<th width="15%">Customer</th>
										<th width="15%">Company</th>
										<th width="13%">Date</th>
										<th width="10%">Amount</th>
										<th width="13%">Payment<br>Method</th>
										<th width="14%">Order<br>Status</th>
										<th width="5%" class="mwxs_tsns">Sync<br>Status</th>
									</tr>
								</thead>
								
								<tbody>
									<?php if(!empty($wc_order_list)):?>
									<?php foreach($wc_order_list as $data):?>
									<?php 
										#$sync_status_html = '<i class="fa fa-times-circle" style="color:red"></i>';
										
										$wc_inv_no = $MWXS_L->get_woo_ord_number_from_order($data['ID'],$data);
										#$wc_inv_no = '';
										$order_id_num_arr[$data['ID']] = (!empty($wc_inv_no))?$wc_inv_no:$data['ID'];
									?>
									
									<tr>
										<td><input type="checkbox" id="order_push_<?php echo esc_attr($data['ID'])?>"></td>
										
										<td>
											<a target="_blank" href="<?php echo esc_url_raw(admin_url('post.php?post='.(int) $data['ID'].'&action=edit')) ?>">
												<?php echo (!empty($wc_inv_no))?$MWXS_L->escape($wc_inv_no).'<br>':'';?>
												<?php echo (int) $data['ID'] ?>											
											</a>
										</td>
										
										<td>
											<?php echo $MWXS_L->escape($data['billing_first_name']) ?> <?php echo $MWXS_L->escape($data['billing_last_name']) ?>
										</td>
										
										<td><?php echo $MWXS_L->escape($data['billing_company']) ?></td>
										<td><?php echo $MWXS_L->escape($data['post_date']) ?></td>
										
										<td>
											<?php echo $MWXS_L->escape($wc_currency_symbol);?>
											<?php echo ($data['order_total']!='')?$MWXS_L->escape($data['order_total']):'0.00';?>
										</td>
										
										<td title="<?php echo $MWXS_L->escape($data['payment_method_title']) ?>">
											<?php echo $MWXS_L->escape($data['payment_method']) ?>
										</td>
										
										<td>
											<?php echo $MWXS_L->escape($MWXS_L->get_array_isset($wc_order_statuses,$data['post_status'],$data['post_status'])); ?>
										</td>
										<td class="td_ss" id="td_ss_<?php echo esc_attr($data['ID']); ?>"></td>
									</tr>
									
									<?php endforeach;?>									
									<?php else:?>
									<tr>
										<td colspan="6">
											<span class="mwxs_tnd">
												<?php _e( 'No orders found.', 'myworks-sync-for-xero' );?>
											</span>
										</td>
									</tr>
									<?php endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<?php $MWXS_L->get_paginate_links($total_records,$items_per_page);?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if($total_records > 0):?>
<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>
<?php wp_nonce_field( 'myworks_wc_xero_sync_order_sync_status_list', 'order_sync_status_list' ); ?>
<?php endif;?>

<script type="text/javascript">
	function search_item(){
		let order_push_search = jQuery('#order_push_search').val();
		order_push_search = jQuery.trim(order_push_search);
		
		let order_date_from = jQuery('#order_date_from').val();
		order_date_from = jQuery.trim(order_date_from);
		
		let order_date_to = jQuery('#order_date_to').val();
		order_date_to = jQuery.trim(order_date_to);
		
		let order_status_srch = jQuery('#order_status_srch').val();
		order_status_srch = jQuery.trim(order_status_srch);
		
		if(order_push_search!='' || order_date_from!='' || order_date_to!='' || order_status_srch!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&order_push_search='+order_push_search+'&order_date_from='+order_date_from+'&order_date_to='+order_date_to+'&order_status_srch='+order_status_srch;
		}else{
			alert('<?php echo __('Please enter or select search term.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){
		window.location = '<?php echo esc_url_raw($page_url);?>&order_push_search=&order_date_from=&order_date_to=&order_status_srch=';
	}
	
	jQuery(document).ready(function($) {
		$('.mwqs_datepicker').css('cursor','pointer');
		$( ".mwqs_datepicker" ).datepicker(
			{ 
			dateFormat: 'yy-mm-dd',
			yearRange: "-50:+0",
			changeMonth: true,
			changeYear: true
			}
		);
		
		<?php if($total_records > 0):?>
		var item_type = 'order';
		$('#push_selected_order_btn').click(function(){
			var item_ids = '';
			var item_checked = 0;
			
			jQuery( "input[id^='order_push_']" ).each(function(){
				if(jQuery(this).is(":checked")){
					item_checked = 1;
					var only_id = jQuery(this).attr('id').replace('order_push_','');
					only_id = parseInt(only_id);
					if(only_id>0){
						item_ids+=only_id+',';
					}					
				}
			});
			
			if(item_ids!=''){
				item_ids = item_ids.substring(0, item_ids.length - 1);
			}
			
			if(item_checked==0){
				alert('<?php echo __('Please select at least one item.','myworks-sync-for-xero');?>');
				return false;
			}
			
			popUpWindow('<?php echo esc_url_raw($sync_window_url);?>&sync_type=push&item_ids='+item_ids+'&item_type='+item_type,'mw_xs_order_push',0,0,650,350);
			return false;
		});

		//Order sync status
		var data = {
			"action": 'myworks_wc_xero_sync_order_sync_status_list',
			"order_sync_status_list": $('#order_sync_status_list').val(),
			"order_id_num_arr":<?php echo json_encode((array) $MWXS_L->array_sanitize($order_id_num_arr));?>
		};		
		
		var loading_msg = '...';
		$('td.td_ss').html(loading_msg);
		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			cache:  false ,
			datatype: "json",
			success: function(result){
				var w_x_o_a = JSON.parse(result);				
				if(!$.isEmptyObject(w_x_o_a)){
					$.each(w_x_o_a, function(key,val){
						if(!$.isEmptyObject(val)){
							//val.ID.trim()
							$('#td_ss_'+key).html('<span class="ss_pf_span">1</span><span class="x_ss"><i class="fa fa-check-circle" style="color:green"></i></span>');
							$('#order_push_'+key).attr("disabled", true);
						}else{
							$('#td_ss_'+key).html('<span class="ss_pf_span">0</span><span class="x_ss"><i class="fa fa-times-circle" style="color:red"></i></span>');
						}						
					});
					
					$('span.x_ss').each(function(i) {
						$(this).delay(100*i).fadeIn(100);
					});
				}
			},
			error: function(result) {
				$('td.td_ss').html('<span class="ss_pf_span">2</span>!');
			}
		});
		<?php endif;?>
	});
</script>