<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'product';

# POST Action
if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_map_wc_xero_product', 'map_wc_xero_product' ) ) {	
	$item_ids = array();$a_item_ids = array();
	$table = $MWXS_L->gdtn('map_products');
	$m_table = $MWXS_L->gdtn('map_multiple');

	foreach ($_POST as $key=>$value){
		$key = $MWXS_L->sanitize($key);
		$value = $MWXS_L->array_sanitize($value);

		if ($MWXS_L->start_with($key, "map_product_")){
			$id = (int) str_replace("map_product_", "", $key);
			if($id > 0){
				$item_ids[$id] = $value;
			}
		}

		# Accounts
		if ($MWXS_L->start_with($key, "map_account_")){
			$id = (int) str_replace("map_account_", "", $key);
			if($id > 0){
				$a_item_ids[$id] = $value;
			}
		}
	}
	
	if(!empty($item_ids)){
		foreach ($item_ids as $key=>$value){
			$save_data = array();			
			$save_data['X_P_ID'] = ($value!='')?$MWXS_L->sanitize($value):'';
			
			if($MWXS_L->get_field_by_val($table,'id','W_P_ID',$key)){
				$wpdb->update($table,$save_data,array('W_P_ID'=>$key),'',array('%d'));
			}else{
				$save_data['W_P_ID'] = $key;
				$wpdb->insert($table, $save_data);
			}
		}
		
		$MWXS_L->set_session_val('map_page_update_message',__('Products mapped successfully.','myworks-sync-for-xero'));
	}
	
	# Account Map
	if(!empty($a_item_ids)){		
		$wc_type = 'product';
		$x_type = 'account';
		foreach ($a_item_ids as $key=>$value){			
			$wc_id = $key;			
			$x_id = $value;			
			$ec_d = $MWXS_L->get_mapping_data_from_table_multiple($wc_type,$x_type,$wc_id);
			
			$save_data = array(				
				'x_id' => $x_id,
			);

			if(is_array($ec_d) && !empty($ec_d)){
				$wpdb->update($m_table,$save_data,array('wc_id'=>$wc_id),'',array('%d'));
			}else{
				if(!empty($x_id)){
					$save_data['wc_type'] = $wc_type;
					$save_data['x_type'] = $x_type;
					$save_data['wc_id'] = $wc_id;
					$wpdb->insert($m_table, $save_data);
				}
			}
			
		}

		if(empty($item_ids)){
			$MWXS_L->set_session_val('map_page_update_message',__('Products mapped successfully.','myworks-sync-for-xero'));
		}
	}
	
	$wpdb->query("DELETE FROM `".$table."` WHERE `X_P_ID` = '' ");
	$wpdb->query("DELETE FROM `".$m_table."` WHERE `wc_type` = 'product' AND `x_type` = 'account' AND `x_id` = '' ");
	$MWXS_L->redirect($page_url);
}

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('product_map_search');
$product_map_search = $MWXS_L->get_session_val('product_map_search');

$MWXS_L->set_and_get('product_type_srch');
$product_type_srch = $MWXS_L->get_session_val('product_type_srch');

$MWXS_L->set_and_get('product_um_srch');
$product_um_srch = $MWXS_L->get_session_val('product_um_srch');

$total_records = $this->dlobj->count_wc_products(false,$product_map_search,'',0,$product_type_srch,$product_um_srch);

$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$Limit = ' '.$offset.' , '.$items_per_page;

$wc_product_list = $this->dlobj->get_wc_products(false,$Limit,false,$product_map_search,'',0,$product_type_srch,$product_um_srch);
#$MWXS_L->_p($wc_product_list);

$wc_p_types = wc_get_product_types();

$is_ajax_dd = $MWXS_L->is_s2_ajax_dd();
# Xero Data
$MWXS_L->xero_connect();

$xero_products_options = '';
if(!$is_ajax_dd){
	$xpsb = 'Name';	
}

$xaa = $MWXS_L->xero_get_accounts_kva();

# JS
$selected_options_script = '';
$s_o_s_arr = array();

require_once plugin_dir_path( __FILE__ ) . 'map-nav.php';
?>

<div class="container map-product-responsive">
	<div class="page_title">
		<h4><?php _e( 'Product Mappings', 'myworks-sync-for-xero' );?></h4>
	</div>
	
	<div class="mw_wc_filter">
		<span class="search_text"><?php _e( 'Search', 'myworks-sync-for-xero' );?></span>
		&nbsp;
		<input type="text" id="product_map_search" placeholder="NAME / SKU / ID" value="<?php echo esc_attr($product_map_search);?>">
		&nbsp;
		
		<span class="search_text"><?php _e( 'Product Type', 'myworks-sync-for-xero' );?></span>
		&nbsp;
		<select id="product_type_srch" style="width:200px !important;">
			<option value=""><?php _e( 'All but parent variable products', 'myworks-sync-for-xero' );?></option>
			<option value="all"<?php if($product_type_srch == 'all'){echo ' selected';}?>>All</option>
			<?php $MWXS_L->only_option($product_type_srch,$wc_p_types);?>
		</select>
		&nbsp;
		
		<span>
			<select title="Mapped/UnMapped" style="width:80px;" name="product_um_srch" id="product_um_srch">
			<?php if(empty($product_um_srch)):?>
				<option value="">All</option>
			<?php endif;?>
			<?php $MWXS_L->only_option($product_um_srch,array('only_um'=>'Only Unmapped','only_m'=>'Only Mapped'));?>
			</select>
		</span>
		
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
											<th width="34%">WooCommerce Product</th>
											<th width="14%"><?php _e( 'SKU', 'myworks-sync-for-xero' );?></th>
											<th width="12%"><?php _e( 'Type', 'myworks-sync-for-xero' );?></th>
											<th width="17%" class="title-description mwxs_tsns">
												<?php _e( 'Xero Product', 'myworks-sync-for-xero' );?>
											</th>
											<th width="17%" class="title-description mwxs_tsns">
												<?php _e( 'Xero Account', 'myworks-sync-for-xero' );?>
											</th>
										</tr>
									</thead>
									
									<tbody>
									<?php if(!empty($wc_product_list)):?>
									<?php foreach($wc_product_list as $data):?>
										<tr>
											<td><?php echo (int) $data['ID']?></td>
											<td><?php echo  $MWXS_L->escape($data['name'])?></td>
											<td><?php echo  $MWXS_L->escape($data['sku'])?></td>
											<td><?php echo  $MWXS_L->escape($data['wc_product_type'])?></td>
											<?php												
												$dd_ext_class = '';												
												if($is_ajax_dd){
													$dd_ext_class = 'mwqs_dynamic_select';													
												}else{													
													if(!empty($data['X_ItemID'])){														
														$s_o_s_arr['#map_product_'.$data['ID']] = $data['X_ItemID'];
													}
												}
												
												# Account
												$account_map_data = $MWXS_L->get_mapping_data_from_table_multiple('product','account',$data['ID']);
												if(is_array($account_map_data) && !empty($account_map_data)){
													$s_o_s_arr['#map_account_'.$data['ID']] = $account_map_data['x_id'];
												}
											?>
											
											<td>
												<select class="mw_wc_qbo_sync_select2 <?php echo esc_attr($dd_ext_class);?>" name="map_product_<?php echo esc_attr($data['ID'])?>" id="map_product_<?php echo esc_attr($data['ID'])?>">													
													<?php 
														if($is_ajax_dd){
															if(!empty($data['X_ItemID'])){
																echo '<option value="'.$MWXS_L->escape($data['X_ItemID']).'">'.stripslashes($MWXS_L->escape($data['X_Name'])).'</option>';
															}else{
																echo '<option value=""></option>';
															}
														}else{
															echo '<option value=""></option>';
															$MWXS_L->option_html('', $MWXS_L->gdtn('products'),'ItemID','Name','',$xpsb.' ASC');
														}
													?>
												</select>
											</td>
											
											<td>
												<select class="mw_wc_qbo_sync_select2" name="map_account_<?php echo esc_attr($data['ID'])?>" id="map_account_<?php echo esc_attr($data['ID'])?>">
													<option value=""></option>
													<?php $MWXS_L->only_option('', $xaa);?>
												</select>
											</td>
										</tr>
									<?php endforeach;?>
									<?php else:?>
										<tr>
											<td colspan="6">
												<span class="mwxs_tnd">
													<?php _e( 'No products found.', 'myworks-sync-for-xero' );?>
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
						<?php wp_nonce_field( 'myworks_wc_xero_sync_map_wc_xero_product', 'map_wc_xero_product' ); ?>
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
					<h5><?php _e( 'Clear all product mappings', 'myworks-sync-for-xero' );?></h5>
					<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_product_mappings', 'clear_product_mappings' ); ?>
					<button id="mwqs_capm_btn"><?php _e( 'Clear Mappings', 'myworks-sync-for-xero' );?></button>
					&nbsp;
					<span id="mwqs_capm_msg"></span>
				</div>
				<?php endif;?>
				
			</div>
		</div>
	</div>
</div>

<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>

<script type="text/javascript">
	function search_item(){
		let product_map_search = jQuery('#product_map_search').val();
		product_map_search = jQuery.trim(product_map_search);
		
		let product_type_srch = jQuery('#product_type_srch').val();
		product_type_srch = jQuery.trim(product_type_srch);
		
		let product_um_srch = jQuery('#product_um_srch').val();
		product_um_srch = jQuery.trim(product_um_srch);
		
		if(product_map_search!='' || product_type_srch!='' || product_um_srch!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&product_map_search='+product_map_search+'&product_type_srch='+product_type_srch+'&product_um_srch='+product_um_srch;
		}else{
			alert('<?php echo __('Please enter or search term.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){
		window.location = '<?php echo esc_url_raw($page_url);?>&product_map_search=&product_type_srch=&product_um_srch=';
	}
	
	jQuery(document).ready(function($){		
		<?php 
			if(is_array($s_o_s_arr) && !empty($s_o_s_arr)){
				foreach($s_o_s_arr as $k => $v){
					echo '$(\''.$MWXS_L->escape($k).'\').val(\''.$MWXS_L->escape($v).'\');';
				}
			}
		?>		
		
		// Quick Refresh
		$('.glp_rxp').click(function(e) {
			e.preventDefault();
			
			if($('#mwxs_qr_p_tf').val() == 1){
				alert('Process already running.');
				return false;
			}
			
			if(!confirm('<?php echo __('This will update the data in our sync with the latest Products in your Xero company. No data will be synced at this time.','myworks-sync-for-xero')?>')){
				return false;
			}
			
			$('#mwxs_qr_p_tf').val(1);
			$('#mwqs_automap_products_msg').html('Importing Products from Xero...');
			
			let data = {
				"action": 'myworks_wc_xero_sync_quick_refresh_products',
				"quick_refresh_products": $('#quick_refresh_products').val()				
			};
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				cache:  false ,
				//datatype: "json",
				success: function(r){
					$('#mwqs_automap_products_msg').html(r);
					location.reload();
				},
				error: function(r) {
					$('#mwqs_automap_products_msg').html('<font style="color:red;">Something went wrong</font>');
				}
			});
			
			$('#mwxs_qr_p_tf').val(0);
		});
		
		// Auto Map
		$('#mwqs_automap_products_wf_qf').click(function(){
			var pam_wf = $('#pam_wf').val().trim();
			var pam_qf = $('#pam_qf').val().trim();

			var mo_um = '';
				if($('#pam_moum_chk').is(':checked')){
				mo_um = 'true';
			}

			if(pam_wf!='' && pam_qf!=''){
				$('#pam_wqf_e_msg').html('');
				if(confirm('<?php echo __('This will override any previous product mappings, and scan your WooCommerce & Xero products by selected fields to automatically match them for you.','myworks-sync-for-xero')?>')){
					var data = {
						"action": 'myworks_wc_xero_sync_automap_products_wf_xf',
						"automap_products_wf_xf": $('#automap_products_wf_xf').val(),
						"pam_wf": pam_wf,
						"pam_qf": pam_qf,
						"mo_um": mo_um,
					};
					
					var loading_msg = 'Loading...';
					$('#mwqs_automap_products_msg').html(loading_msg);

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: data,
						cache:  false ,
						//datatype: "json",
						success: function(result){
						if(result!=0 && result!=''){							
							$('#mwqs_automap_products_msg').html(result);							
							window.location='<?php echo esc_url_raw($page_url);?>';
						}else{
							$('#mwqs_automap_products_msg').html('Automap was timed out and could not fully complete. Please try again');							
						}				  
						},
						error: function(result) {							
							$('#mwqs_automap_products_msg').html('Automap was timed out and could not fully complete. Please try again');
						}
					});
				}
			}else{				
				$('#pam_wqf_e_msg').html('Please select automap fields.');
			}
		});
		
		<?php if($total_records > 0):?>
		// Clear Mappings
		$('#mwqs_capm_btn').click(function(){
			if(confirm('<?php echo __('Are you sure, you want to clear all product mappings?','myworks-sync-for-xero')?>')){
				var loading_msg = 'Loading...';
				jQuery('#mwqs_capm_msg').html(loading_msg);
				var data = {
					"action": 'myworks_wc_xero_sync_clear_product_mappings',
					"clear_product_mappings": jQuery('#clear_product_mappings').val(),
				};
				
				jQuery.ajax({
				   type: "POST",
				   url: ajaxurl,
				   data: data,
				   cache:  false ,
				   //datatype: "json",
				   success: function(result){
					   if(result!=0 && result!=''){						 
						 jQuery('#mwqs_capm_msg').html('Success!');
						 window.location='<?php echo esc_url_raw($page_url);?>';
					   }else{						 
						jQuery('#mwqs_capm_msg').html('Error!');
					   }				  
				   },
				   error: function(result) { 
						jQuery('#mwqs_capm_msg').html('Error!');
				   }
				});
			}
		});
		<?php endif;?>
	});
</script>
<?php myworks_woo_sync_for_xero_get_select2_js('.mw_wc_qbo_sync_select2','xero_product');?>