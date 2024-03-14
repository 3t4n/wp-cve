<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'customer';

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('customer_push_search');
$customer_push_search = $MWXS_L->get_session_val('customer_push_search');

$total_records = $this->dlobj->count_wc_customers($customer_push_search);

$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$Limit = ' '.$offset.' , '.$items_per_page;
$wc_customer_list = $this->dlobj->get_wc_customers(false,false,$Limit,$customer_push_search);
#$MWXS_L->_p($wc_customer_list);

$is_update_enabled = $MWXS_L->is_update_enabled('customer-push');
require_once plugin_dir_path( __FILE__ ) . 'push-nav.php';
?>

<style>	
	span.ss_pf_span{display:none;}
</style>

<div class="container">
	<div class="page_title"><h4><?php _e( 'Customer Push', 'myworks-sync-for-xero' );?></h4></div>
	<div class="card qo-push-responsive">
		<div class="card-content">
			<div class="">
				<div class="">
					<div class="col s12 m12 l12">
						<div class="">
							<div class="panel panel-primary">
								<div class="mw_wc_filter">
									<span class="search_text"><?php _e( 'Search', 'myworks-sync-for-xero' );?></span>
									&nbsp;
									<input type="text" id="customer_push_search" placeholder="NAME / EMAIL / COMPANY / ID" value="<?php echo esc_attr($customer_push_search);?>">
									
									<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
								</div>
								<br>
								
								<?php if($total_records > 0):?>
								<div class="row">
									<div class="input-field col s12 m12 14">
										<button id="push_selected_customer_btn" class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
											<?php echo __('Push Selected Customers','myworks-sync-for-xero')?>
										</button>										
									</div>
								</div>
								<br>
								<?php endif;?>
								
								<div class="table-m">
									<div class="myworks-wc-qbo-sync-table-responsive">
										<table id="mwqs_customer_push_table" class="table tablesorter">
											<thead>
												<th width="2%">
													<input type="checkbox" onclick="javascript:mw_xero_sync_check_all(this,'cus_push_')">
												</th>
												<th>ID</th>
												<th>Name</th>
												<th>Email</th>
												<th>Company</th>
												<th>Sync Status</th>
											</thead>
											
											<tbody>
												<?php if(!empty($wc_customer_list)):?>
												<?php foreach($wc_customer_list as $data):?>
												<?php													
													$cd = (!$is_update_enabled && !empty($data['X_ContactID']))?' disabled':'';
												?>
												
												<tr>
													<td><input type="checkbox" id="cus_push_<?php echo esc_attr($data['ID'])?>"<?php echo esc_attr($cd);?>></td>
													<td><?php echo (int) $data['ID']?></td>
													<td><?php echo $MWXS_L->escape($data['first_name']).' '.$MWXS_L->escape($data['last_name'])?></td>
													<td><?php echo $MWXS_L->escape($data['user_email'])?></td>
													<td><?php echo $MWXS_L->escape($data['billing_company'])?></td>
													<td>
														<span class="ss_pf_span"><?php echo (!empty($data['X_ContactID']))?1:0;?></span>														
														<?php 
															if(!empty($data['X_ContactID'])){
																echo '<i title="Mapped to '.$MWXS_L->escape($data['X_Name']).'" class="fa fa-check-circle" style="color:green"></i>';
															}else{
																echo '<i class="fa fa-times-circle" style="color:red"></i>';
															}
														?>
													</td>
												</tr>
												
												<?php endforeach;?>
												<?php else:?>
												<tr>
													<td colspan="6">
														<span class="mwxs_tnd">
															<?php _e( 'No customers found.', 'myworks-sync-for-xero' );?>
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
		</div>
	</div>
</div>

<?php if($total_records > 0):?>
<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>
<?php endif;?>

<script type="text/javascript">
	function search_item(){
		let customer_push_search = jQuery('#customer_push_search').val();
		customer_push_search = jQuery.trim(customer_push_search);
		
		if(customer_push_search!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&customer_push_search='+customer_push_search;
		}else{
			alert('<?php echo __('Please enter search keyword.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){
		window.location = '<?php echo esc_url_raw($page_url);?>&customer_push_search=';
	}
	
	<?php if($total_records > 0):?>
	jQuery(document).ready(function($){
		var item_type = 'customer';
		$('#push_selected_customer_btn').click(function(){
			var item_ids = '';
			var item_checked = 0;
			
			jQuery( "input[id^='cus_push_']" ).each(function(){
				if(jQuery(this).is(":checked")){
					item_checked = 1;
					var only_id = jQuery(this).attr('id').replace('cus_push_','');
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
			
			popUpWindow('<?php echo esc_url_raw($sync_window_url);?>&sync_type=push&item_ids='+item_ids+'&item_type='+item_type,'mw_xs_customer_push',0,0,650,350);
			return false;
		});
	});
	<?php endif;?>
</script>