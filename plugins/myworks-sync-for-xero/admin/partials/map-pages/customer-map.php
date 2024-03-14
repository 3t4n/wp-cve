<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'customer';

# POST Action
if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_map_wc_xero_customer', 'map_wc_xero_customer' ) ) {	
	$item_ids = array();
	$table = $MWXS_L->gdtn('map_customers');
	
	foreach ($_POST as $key=>$value){
		$key = $MWXS_L->sanitize($key);
		$value = $MWXS_L->array_sanitize($value);

		if ($MWXS_L->start_with($key, "map_customer_")){
			$id = (int) str_replace("map_customer_", "", $key);
			if($id > 0){
				$item_ids[$id] = $value;
			}
		}
	}
	
	if(count($item_ids)){
		foreach ($item_ids as $key=>$value){
			$save_data = array();			
			$save_data['X_C_ID'] = ($value!='')?$MWXS_L->sanitize($value):'';
			
			if($MWXS_L->get_field_by_val($table,'id','W_C_ID',$key)){
				$wpdb->update($table,$save_data,array('W_C_ID'=>$key),'',array('%d'));
			}else{
				$save_data['W_C_ID'] = $key;
				$wpdb->insert($table, $save_data);
			}
		}
		
		$MWXS_L->set_session_val('map_page_update_message',__('Customers mapped successfully.','myworks-sync-for-xero'));
	}
	
	$wpdb->query("DELETE FROM `".$table."` WHERE `X_C_ID` = '' ");
	$MWXS_L->redirect($page_url);
}

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('customer_map_search');
$customer_map_search = $MWXS_L->get_session_val('customer_map_search');

$total_records = $this->dlobj->count_wc_customers($customer_map_search);

$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$Limit = ' '.$offset.' , '.$items_per_page;
$wc_customer_list = $this->dlobj->get_wc_customers(false,false,$Limit,$customer_map_search);
#$MWXS_L->_p($wc_customer_list);

$is_ajax_dd = $MWXS_L->is_s2_ajax_dd();
# Xero Data
$xero_customers_options = '';
if(!$is_ajax_dd){
	$xcsb = 'Name';	
}

$selected_options_script = '';
$s_o_s_arr = array();

require_once plugin_dir_path( __FILE__ ) . 'map-nav.php';
?>

<div class="container map-customer-outer map-product-responsive">
	<div class="page_title">
		<h4><?php _e( 'Customer Mappings', 'myworks-sync-for-xero' );?></h4>
	</div>
	
	<div class="mw_wc_filter">
		<span class="search_text"><?php _e( 'Search', 'myworks-sync-for-xero' );?></span>
		&nbsp;
		<input type="text" id="customer_map_search" placeholder="NAME / EMAIL / COMPANY / ID" value="<?php echo esc_attr($customer_map_search);?>">
		
		<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
	</div>
	<br>
	<div class="card">
		<div class="card-content">
			<div class="row">
				<form method="POST" class="col s12 m12 l12">
					<div class="row">
						<div class="col s12 m12 l12">
							<div class="myworks-wc-qbo-sync-table-responsive">
								<table class="mw-qbo-sync-settings-table menu-blue-bg menu-bg-a new-table">
									<thead>
										<tr>
											<th width="6%">&nbsp; <?php _e( 'ID', 'myworks-sync-for-xero' );?></th>
											<th width="23%">WooCommerce Customer Name</th>
											<th width="23%"><?php _e( 'Email', 'myworks-sync-for-xero' );?></th>
											<th width="23%"><?php _e( 'Company', 'myworks-sync-for-xero' );?></th>
											<th width="25%" class="title-description mwxs_tsns">
												<?php _e( 'Xero Customer', 'myworks-sync-for-xero' );?>
											</th>
										</tr>
									</thead>
									
									<tbody>
									<?php if(!empty($wc_customer_list)):?>
									<?php foreach($wc_customer_list as $data):?>
										<tr>
											<td><?php echo (int) $data['ID']?></td>
											<td><?php echo $MWXS_L->escape($data['display_name'])?></td>
											<td><?php echo $MWXS_L->escape($data['user_email'])?></td>
											<td><?php echo $MWXS_L->escape($data['billing_company'])?></td>
											<?php												
												$dd_ext_class = '';												
												if($is_ajax_dd){
													$dd_ext_class = 'mwqs_dynamic_select';													
												}else{													
													if(!empty($data['X_ContactID'])){														
														$s_o_s_arr['#map_customer_'.$data['ID']] = $data['X_ContactID'];
													}
												}
											?>
											
											<td>
												<select class="mw_wc_qbo_sync_select2 <?php echo esc_attr($dd_ext_class);?>" name="map_customer_<?php echo esc_attr($data['ID'])?>" id="map_customer_<?php echo esc_attr($data['ID'])?>">													
													<?php
														if($is_ajax_dd){															
															if(!empty($data['X_ContactID'])){
																echo '<option value="'.$MWXS_L->escape($data['X_ContactID']).'">'.stripslashes($MWXS_L->escape($data['X_Name'])).'</option>';
															}else{
																echo '<option value=""></option>';
															}
														}else{
															echo '<option value=""></option>';
															$MWXS_L->option_html('', $MWXS_L->gdtn('customers'),'ContactID','Name','',$xcsb.' ASC');
														}														
													?>
												</select>
											</td>
										</tr>
									<?php endforeach;?>
									<?php else:?>
										<tr>
											<td colspan="5">
												<span class="mwxs_tnd">
													<?php _e( 'No customers found.', 'myworks-sync-for-xero' );?>
												</span>
											</td>
										</tr>
									<?php endif;?>
									</tbody>
								</table>
								<?php $MWXS_L->get_paginate_links($total_records,$items_per_page);?>
							</div>
						</div>
					</div>
					
					<?php if($total_records > 0):?>
					<div class="row">
						<?php wp_nonce_field( 'myworks_wc_xero_sync_map_wc_xero_customer', 'map_wc_xero_customer' ); ?>
						<div class="input-field col s12 m6 l4">
							<button class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php _e( 'Save', 'myworks-sync-for-xero' );?>
							</button>
						</div>
					</div>
					<?php endif;?>
				</form>
				
				<?php if($total_records > 0):?>
				<br>
				
				<div class="col col-m">
					<h5><?php _e( 'Clear all customer mappings', 'myworks-sync-for-xero' );?></h5>
					<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_customer_mappings', 'clear_customer_mappings' ); ?>
					<button id="mwqs_cacm_btn"><?php _e( 'Clear Mappings', 'myworks-sync-for-xero' );?></button>
					&nbsp;
					<span id="mwqs_cacm_msg"></span>
				</div>
				<?php endif;?>
				
			</div>
		</div>
	</div>
</div>

<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>

<script type="text/javascript">
	function search_item(){
		let customer_map_search = jQuery('#customer_map_search').val();
		customer_map_search = jQuery.trim(customer_map_search);
		
		if(customer_map_search!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&customer_map_search='+customer_map_search;
		}else{
			alert('<?php echo __('Please enter search keyword.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){
		window.location = '<?php echo esc_url_raw($page_url);?>&customer_map_search=';
	}
	
	jQuery(document).ready(function($){		
		<?php 
			if(is_array($s_o_s_arr) && !empty($s_o_s_arr)){
				foreach($s_o_s_arr as $k => $v){
					echo '$(\''.$MWXS_L->escape($k).'\').val(\''.$MWXS_L->escape($v).'\');';
				}
			}
		?>		
		
		<?php if($total_records > 0):?>
		// Quick Refresh
		$('.glp_rxc').click(function(e) {
			e.preventDefault();

			if($('#mwxs_qr_c_tf').val() == 1){
				alert('Process already running.');
				return false;
			}

			if(!confirm('<?php echo __('This will update the data in our sync with the latest Customers in your Xero company. No data will be synced at this time.','myworks-sync-for-xero')?>')){
				return false;
			}

			$('#mwxs_qr_c_tf').val(1);
			$('#mwqs_automap_customers_msg').html('Importing Customers from Xero...');

			let data = {
				"action": 'myworks_wc_xero_sync_quick_refresh_customers',
				"quick_refresh_customers": $('#quick_refresh_customers').val()				
			};

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				cache:  false ,
				//datatype: "json",
				success: function(r){
					$('#mwqs_automap_customers_msg').html(r);
					location.reload();
				},
				error: function(r) {
					$('#mwqs_automap_customers_msg').html('<font style="color:red;">Something went wrong</font>');
				}
			});

			$('#mwxs_qr_c_tf').val(0);
		});
		
		// Auto Map
		$('#mwqs_automap_customers_wf_qf').click(function(){
			var cam_wf = $('#cam_wf').val().trim();
			var cam_qf = $('#cam_qf').val().trim();

			var mo_um = '';
				if($('#cam_moum_chk').is(':checked')){
				mo_um = 'true';
			}

			if(cam_wf!='' && cam_qf!=''){
				$('#cam_wqf_e_msg').html('');
				if(confirm('<?php echo __('This will override any previous customer mappings, and scan your WooCommerce & QuickBooks Online customers by selected fields to automatically match them for you.','myworks-sync-for-xero')?>')){
					var data = {
						"action": 'myworks_wc_xero_sync_automap_customers_wf_xf',
						"automap_customers_wf_xf": $('#automap_customers_wf_xf').val(),
						"cam_wf": cam_wf,
						"cam_qf": cam_qf,
						"mo_um": mo_um,
					};
					
					var loading_msg = 'Loading...';
					$('#mwqs_automap_customers_msg').html(loading_msg);

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: data,
						cache:  false ,
						//datatype: "json",
						success: function(result){
						if(result!=0 && result!=''){							
							$('#mwqs_automap_customers_msg').html(result);							
							window.location='<?php echo esc_url_raw($page_url);?>';
						}else{
							$('#mwqs_automap_customers_msg').html('Automap was timed out and could not fully complete. Please try again');							
						}				  
						},
						error: function(result) {							
							$('#mwqs_automap_customers_msg').html('Automap was timed out and could not fully complete. Please try again');
						}
					});
				}
			}else{				
				$('#cam_wqf_e_msg').html('Please select automap fields.');
			}
		});
		
		// Clear Mappings
		$('#mwqs_cacm_btn').click(function(){
			if(confirm('<?php echo __('Are you sure, you want to clear all customer mappings?','myworks-sync-for-xero')?>')){
				var loading_msg = 'Loading...';
				$('#mwqs_cacm_msg').html(loading_msg);
				
				var data = {
					"action": 'myworks_wc_xero_sync_clear_customer_mappings',
					"clear_customer_mappings": $('#clear_customer_mappings').val(),
				};
				
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: data,
					cache:  false ,
					//datatype: "json",
					success: function(result){
						if(result!=0 && result!=''){
							$('#mwqs_cacm_msg').html('Success!');
							window.location='<?php echo esc_url_raw($page_url);?>';
						}else{
							$('#mwqs_cacm_msg').html('Error!');
						}				  
					},
					error: function(result) {
						$('#mwqs_cacm_msg').html('Error!');
					}
				});
			}
		});
		<?php endif;?>
	});
</script>
<?php myworks_woo_sync_for_xero_get_select2_js('.mw_wc_qbo_sync_select2','xero_customer');?>