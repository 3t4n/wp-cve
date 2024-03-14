<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
global $wpdb;
$pfn = MW_WC_XERO_SYNC_PLUGIN_NAME;
$page_url = admin_url('admin.php?page=myworks-wc-xero-sync-settings');

# Save Settings
if(!empty($_POST) && isset($_POST['mw_wc_xero_sync_settings']) && check_admin_referer( 'myworks_wc_xero_sync_save_settings', 'wxs_save_settings' )){
	$sfa = array();
	
	# Default Settings
	$sfa[] = array('name' => 'default_xero_product','dv' => '');
	$sfa[] = array('name' => 'default_xero_account_foli','dv' => '');
	$sfa[] = array('name' => 'default_xero_sales_account_fnp','dv' => '');
	$sfa[] = array('name' => 'default_xero_inventory_asset_account_fnp','dv' => '');
	$sfa[] = array('name' => 'default_xero_cogs_account_fnp','dv' => '');
	$sfa[] = array('name' => 'default_xero_shipping_product','dv' => '');
	
	# Order Settings
	$sfa[] = array('name' => 'order_sync_as','dv' => 'Invoice');
	
	# Order Sync as Per Role	
	if(isset($_POST['mw_wc_xero_sync_order_sync_as']) && $_POST['mw_wc_xero_sync_order_sync_as'] == 'Per Role'){
		$mw_wc_xero_sync_osa_pr_map_data = '';		
		if(isset($_POST['vpr_wr']) && is_array($_POST['vpr_wr']) && isset($_POST['vpr_qost']) && is_array($_POST['vpr_qost'])){
			if(is_array($_POST['vpr_wr']) && !empty($_POST['vpr_wr']) && is_array($_POST['vpr_qost']) && !empty($_POST['vpr_qost'])){
				if(count($_POST['vpr_wr']) == count($_POST['vpr_qost'])){
					$vpr_wr = $MWXS_L->var_p('vpr_wr');
					$vpr_qost = $MWXS_L->var_p('vpr_qost');
					$qosa_pa_data = array();
					
					foreach($vpr_wr as $k => $v){
						if(!empty($v)){
							$v = trim($v);
							if(isset($vpr_qost[$k]) && !empty($vpr_qost[$k])){
								$qv = trim($vpr_qost[$k]);
								$qosa_pa_data[$v] = $qv;
							}							
						}
					}
					
					if(!empty($qosa_pa_data)){
						$mw_wc_xero_sync_osa_pr_map_data = $qosa_pa_data;
					}
				}
			}
		}
		
		$MWXS_L->update_option('mw_wc_xero_sync_osa_pr_map_data',$mw_wc_xero_sync_osa_pr_map_data);
	}
	
	$sfa[] = array('name' => 'use_next_xero_order_number','ft' => 'option_check');
	$sfa[] = array('name' => 'block_syncing_orders_before_id','dv' => '','dt' => 'int');
	$sfa[] = array('name' => 'do_not_sync_0_orders','ft' => 'option_check');
	$sfa[] = array('name' => 's_order_notes_to','dv' => '');
	$sfa[] = array('name' => 'void_xero_order_on_wc_cancelled','ft' => 'option_check');
	
	$sfa[] = array('name' => 's_all_orders_to_one_xero_customer','ft' => 'option_check');
	
	# All Order to Single Customer Per Role
	if(isset($_POST['mw_wc_xero_sync_s_all_orders_to_one_xero_customer']) && $_POST['mw_wc_xero_sync_s_all_orders_to_one_xero_customer'] == 'true'){
		$mw_wc_xero_sync_aotc_rcm_data = '';		
		if(isset($_POST['saoqc_wr']) && is_array($_POST['saoqc_wr']) && isset($_POST['saoqc_qc']) && is_array($_POST['saoqc_qc'])){
			if(is_array($_POST['saoqc_wr']) && !empty($_POST['saoqc_wr']) && is_array($_POST['saoqc_qc']) && !empty($_POST['saoqc_qc'])){
				if(count($_POST['saoqc_wr']) == count($_POST['saoqc_qc'])){
					$saoqc_wr = $MWXS_L->var_p('saoqc_wr');
					$saoqc_qc = $MWXS_L->var_p('saoqc_qc');
					
					$aotc_rcm_data = array();
					
					foreach($saoqc_wr as $k => $v){
						if(!empty($v)){
							$v = trim($v);
							if(isset($saoqc_qc[$k]) && !empty($saoqc_qc[$k])){
								$qv = trim($saoqc_qc[$k]);
								$aotc_rcm_data[$v] = $qv;
							}
						}
					}
					
					if(!empty($aotc_rcm_data)){
						$mw_wc_xero_sync_aotc_rcm_data = $aotc_rcm_data;
					}
				}
			}
		}
		
		$MWXS_L->update_option('mw_wc_xero_sync_aotc_rcm_data',$mw_wc_xero_sync_aotc_rcm_data);
	}
	
	$sfa[] = array('name' => 's_order_when_status_in','dv' => 'wc-processing,wc-completed','ft' => 'c_s');
	$sfa[] = array('name' => 'order_date_val','dv' => 'order_date');
	$sfa[] = array('name' => 'order_line_item_desc_val_s','dv' => 'wc_pv_name');
	$sfa[] = array('name' => 'add_w_oli_meta_into_xero_oli','ft' => 'option_check');
	$sfa[] = array('name' => 'send_invoice_email_after_sync','ft' => 'option_check');
	$sfa[] = array('name' => 'xero_inv_status_for_unp_ord','dv' => 'DRAFT');
	
	# Tax Settings
	$sfa[] = array('name' => 'non_taxable_rate','dv' => '');
	$sfa[] = array('name' => 'order_tax_as_li','ft' => 'option_check');
	$sfa[] = array('name' => 'otli_xero_product','dv' => '');
	
	# Mapping Settings
	$sfa[] = array('name' => 'customer_append_id_if_name_taken','ft' => 'option_check');
	$sfa[] = array('name' => 'new_customer_dname_format','dv' => '{firstname} {lastname}');

	# Pull Settings
	$sfa[] = array('name' => 'pulled_product_wc_status','dv' => 'draft');
	$sfa[] = array('name' => 'payment_pull_wc_order_status','dv' => '');
	$sfa[] = array('name' => 'prevent_payment_pull_wc_order_status','dv' => '','ft' => 'c_s');
	
	# Automatic Sync Settings
	# WooCommerce > Xero
	$sfa[] = array('name' => 'rt_push_items','dv' => '','ft' => 'c_s'); # Customer,Order,Payment
	$sfa[] = array('name' => 'queue_cron_interval_time','dv' => 'MWXS_5min');

	# Clear queue cron evrnt if interval setting changed	
	if($_POST['mw_wc_xero_sync_queue_cron_interval_time'] != $MWXS_L->get_option('mw_wc_xero_sync_queue_cron_interval_time')){
		$qcit = $MWXS_L->var_p('mw_wc_xero_sync_queue_cron_interval_time');
		if(empty($qcit)){
			$qcit = 'MWXS_5min';
		}

		$qc_hook_name = 'mw_wc_xero_sync_queue_cron_hook';
		if(wp_next_scheduled($qc_hook_name)){
			wp_clear_scheduled_hook($qc_hook_name);
			wp_schedule_event(time(), $qcit, $qc_hook_name);
		}
	}

	# Xero > WooCommerce
	$sfa[] = array('name' => 'rt_pull_items','dv' => '','ft' => 'c_s'); # Inventory
	$sfa[] = array('name' => 'ivnt_pull_interval_time','dv' => 'MWXS_5min');

	# Clear inventory pull evrnt if interval setting changed	
	if($_POST['mw_wc_xero_sync_ivnt_pull_interval_time'] != $MWXS_L->get_option('mw_wc_xero_sync_ivnt_pull_interval_time')){
		$ipit = $MWXS_L->var_p('mw_wc_xero_sync_ivnt_pull_interval_time');
		if(empty($ipit)){
			$ipit = 'MWXS_5min';
		}

		$ip_hook_name = 'mw_wc_xero_sync_ivnt_pull_hook';
		if(wp_next_scheduled($ip_hook_name)){
			wp_clear_scheduled_hook($ip_hook_name);
			wp_schedule_event(time(), $ipit, $ip_hook_name);
		}
	}
	
	# Miscellaneous Settings
	$sfa[] = array('name' => 'invoice_tab_in_cus_acc_area','ft' => 'option_check');
	$sfa[] = array('name' => 'enable_select2_dd','ft' => 'option_check');
	$sfa[] = array('name' => 'select2_ajax_dd','ft' => 'option_check');
	$sfa[] = array('name' => 'save_log_for_days','dv' => 30,'dt' => 'int');	
	$sfa[] = array('name' => 'add_sync_error_data_into_log_file','ft' => 'option_check');
	$sfa[] = array('name' => 'add_sync_success_data_into_log_file','ft' => 'option_check');
	
	# Bulk Save
	if(is_array($sfa) && !empty($sfa)){
		foreach($sfa as $s){
			if(isset($s['name']) && !empty($s['name'])){
				$ft = (isset($s['ft']))?$s['ft']:'';
				$dv = (isset($s['dv']))?$s['dv']:'';
				$dt = (isset($s['dt']))?$s['dt']:'';
				
				$ext = (isset($s['ext']))?$s['ext']:'';
				
				$MWXS_L->save_setting_page_data($MWXS_L->array_sanitize($_POST),$s['name'],$ft,$dv,$dt,$ext);
			}			
		}
		
		$MWXS_L->set_session_val('settings_save_status','admin-success-green');
	}
	
	$MWXS_L->set_session_val('settings_current_tab',$MWXS_L->var_p('settings_current_tab'));
	$MWXS_L->redirect($page_url);
}

$s_c_tab = $MWXS_L->get_session_val('settings_current_tab','s_tab_default',true);
$settings_save_status = $MWXS_L->get_session_val('settings_save_status','',true);

# Data - Wc / Plugin DB Table
$wc_order_statuses = wc_get_order_statuses();
$wu_roles = get_editable_roles();

$is_ajax_dd = $MWXS_L->is_s2_ajax_dd();

$xero_products_options = '';
$xero_customers_options = '';

$x_p_o_params = array();
$x_c_o_params = array();

if(!$is_ajax_dd){
	$xpsb = 'Name';	
	$x_p_o_params = array('', $MWXS_L->gdtn('products'),'ItemID','Name','',$xpsb.' ASC','',false);
	
	$xcsb = 'Name';	
	$x_c_o_params = array('', $MWXS_L->gdtn('customers'),'ContactID','Name','',$xcsb.' ASC','',false);
}

# Data - Xero
$xaa = $MWXS_L->xero_get_accounts_kva();
$xero_account_options = '';

$x_a_o_params = array('',$xaa,'','',false);

$xta = $MWXS_L->xero_get_tax_rates_kva();
$xero_tax_options = '';

$x_t_o_params = array('',$xta,'','',false);

#Setting data / selected options
$settings_data = $MWXS_L->plugin_get_all_options();

$list_selected = '';
$s_o_s_arr = array();

if(is_array($settings_data) && !empty($settings_data)){
	$sl_fields = array(
		'default_xero_product',
		'default_xero_account_foli',
		'default_xero_sales_account_fnp',
		'default_xero_inventory_asset_account_fnp',
		'default_xero_cogs_account_fnp',
		'default_xero_shipping_product',
		
		'non_taxable_rate',
		'otli_xero_product',
	);
	
	if(is_array($sl_fields) && !empty($sl_fields)){
		foreach($sl_fields as $v){
			$v = $MWXS_L->get_s_o_p().$v;			
			$sv = (isset($settings_data[$v]))?$settings_data[$v]:'';
			if(!empty($sv)){				
				$s_o_s_arr['#'.$v] = $sv;
			}			
		}
	}
}

?>

<div class="mw_wc_qbo_sync_container">
	<form method="post">
		<input type="hidden" name="settings_current_tab" id="settings_current_tab" value="<?php echo esc_attr($s_c_tab); ?>">
		<!-- S Tab Nav -->
		<nav class="mw-qbo-sync-grey">
			<div class="nav-wrapper">
				<a class="brand-logo left" href="javascript:void(0)">
					<img alt="" src="<?php echo esc_url(plugins_url( $pfn.'/admin/image/mwd-logo.png' ));?>">
				</a>
				
				<ul class="hide-on-med-and-down right">
					<li class="default-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_default">			
							<?php _e( 'Default', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
					<li class="invoice-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_order">
							<?php _e( 'Order', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
					<li class="tax-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_tax">		
							<?php _e( 'Taxes', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
					<li class="mapping-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_mapping">				
							<?php _e( 'Mapping', 'myworks-sync-for-xero' );?>
						</a>
					</li>

					<li class="pull-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_pull">				
							<?php _e( 'Pull', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
					<li class="webhook-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_automatic_sync">				
							<?php _e( 'Automatic Sync', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
					<li class="misc-menu mwqs_stb">
						<a href="javascript:void(0)" id="s_tab_miscellaneous">				
							<?php _e( 'Miscellaneous', 'myworks-sync-for-xero' );?>
						</a>
					</li>
					
				</ul>
			</div>
		</nav>
		
		<div class="container" id="mw_qbo_sybc_settings_tables" style="margin-top:25px;">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="col s12 m12 l12">
							<div class="row">
								<div class="col s12 m12 l12">
								
									<!-- S Tab Body -->
									<div id="s_tab_default_body" style="display: none;">
										<h6><?php echo _e('Default Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default for unmatched products',
																	'name' => 'default_xero_product',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_p_o_params,
																	's_data_function' => 'option_html',
																	's_blank_option' => true,
																	'ajax_dd' => $is_ajax_dd,
																	'a_d_d_t' => 'xero_product',
																	
																	'tt_text' => 'This is a Xero product that is only used when syncing an order that contains line items not mapped to a Xero product. Think of this as a fallback / miscellaneous type product.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default Xero Account for Order Line Items',
																	'name' => 'default_xero_account_foli',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_a_o_params,
																	's_data_function' => 'only_option',
																	's_blank_option' => true,
																	
																	'tt_text' => 'Default account assigned to order line items if the product does not have an account set in Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default Xero Sales Account for New Products',
																	'name' => 'default_xero_sales_account_fnp',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_a_o_params,
																	's_data_function' => 'only_option',
																	's_blank_option' => true,
																	
																	'tt_text' => 'Default account assigned to your WooCommerce products when pushing them over to Xero. This should be an income or expense account.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default Xero Inventory Asset Account for New Products',
																	'name' => 'default_xero_inventory_asset_account_fnp',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_a_o_params,
																	's_data_function' => 'only_option',
																	's_blank_option' => true,
																	
																	'tt_text' => 'Default inventory asset account assigned to your WooCommerce products when pushing them over to Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default Xero COGS Account for New Products',
																	'name' => 'default_xero_cogs_account_fnp',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_a_o_params,
																	's_data_function' => 'only_option',
																	's_blank_option' => true,
																	
																	'tt_text' => 'Default Cost of Goods Sold account assigned to your WooCommerce products when pushing them over to Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Default Xero Shipping Product',
																	'name' => 'default_xero_shipping_product',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_p_o_params,
																	's_data_function' => 'option_html',
																	's_blank_option' => true,
																	'ajax_dd' => $is_ajax_dd,
																	'a_d_d_t' => 'xero_product',
																	
																	'tt_text' => 'Choose a Xero Product to fallback to for unmapped Shipping Methods.'
																),
															);
														?>
													</tr>
													
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_order_body" style="display: none;">
										<h6><?php echo _e('Order Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<!--**-->
													
													<?php
														$f_name = 'mw_wc_xero_sync_order_sync_as';
														$wo_qsa = $MWXS_L->get_option($f_name);
														if($wo_qsa!='Invoice' && $wo_qsa!='Quote' && $wo_qsa!='Per Role' && $wo_qsa!='Per Gateway'){
															$wo_qsa = 'Invoice';
														}
													?>
													
													<tr>
														<th class="title-description" width="35%">
															<?php echo __('Sync WooCommerce Orders as','myworks-sync-for-xero') ?>
														</th>
														
														<td>
															<div class="row">
																<div class="input-field col s12 m12 l12">												
																	<div class="switch-toggle switch-3 switch-candy">
																		<input id="wo_qsa_inv" value="Invoice" name="<?php echo esc_attr($f_name);?>" type="radio" <?php if($wo_qsa=='Invoice'){echo 'checked="checked"';}?>>
																		<label for="wo_qsa_inv" onclick="">Invoice</label>
																		
																		<input disabled id="wo_qsa_sr" value="Quote" name="<?php echo esc_attr($f_name);?>" type="radio" <?php if($wo_qsa=='Quote'){echo 'checked="checked"';}?>>
																		<label for="wo_qsa_sr" onclick="">Quote</label>
																		
																		<?php if(is_array($wu_roles) && !empty($wu_roles)):?>
																		<input disabled id="wo_qsa_vpr" value="Per Role" name="<?php echo esc_attr($f_name);?>" type="radio" <?php if($wo_qsa=='Per Role'){echo 'checked="checked"';}?>>
																		<label for="wo_qsa_vpr" onclick="">Per Role</label>
																		<?php endif;?>
																		
																		<input disabled id="wo_qsa_pg" value="Per Gateway" name="<?php echo esc_attr($f_name);?>" type="radio" <?php if($wo_qsa=='Per Gateway'){echo 'checked="checked"';}?>>
																		<label for="wo_qsa_pg" onclick="">Per Gateway</label>			
																		<a></a>
																	</div>
																	
																	<div id="mwoqsa_rm">
																		<?php
																		if($wo_qsa == 'Per Gateway'){
																			echo '<small>Please select the order sync type per gateway in Map > Payment Method page.</small>';
																		}
																		?>
																	</div>
																	
																</div>
															</div>
														</td>
														
														<td width="5%">															
															<?php myworks_woo_sync_for_xero_set_tooltip('Choose Wocommerce Order Syns as Xero Invoice or Quote.');?>
														</td>
													</tr>
													
													<!--**-->
													
													<?php
														$qost_arr = array(
															'Invoice' => 'Invoice',
															'Quote' => 'Quote',															
														);
														
														$osa_pr_map_data = get_option('mw_wc_xero_sync_osa_pr_map_data');
													?>
													
													<?php if(is_array($wu_roles) && count($wu_roles)):?>
													<tr id="wo_qsa_vpr_map_tr" <?php if($wo_qsa != 'Per Role'){echo 'style="display:none;"';}?>>
														<th class="title-description">
															<?php echo __('WooCommerce User Role -> Order Sync Type Mapping','myworks-sync-for-xero') ?>
														</th>
														
														<td>
															<table>																
																<?php foreach ($wu_roles as $role_name => $role_info):?>
																<?php 
																	$qost_va = '';
																	if(is_array($osa_pr_map_data) && isset($osa_pr_map_data[$role_name])){
																		$qost_va = $osa_pr_map_data[$role_name];
																	}
																?>
																<tr style="border:none; background:none;">
																	<td width="30%">
																		<?php echo $MWXS_L->escape($role_info['name']);?>
																		<input type="hidden" name="vpr_wr[]" value="<?php echo esc_attr($role_name);?>">
																	</td>
																	
																	<td>												
																		<select name="vpr_qost[]" class="filled-in production-option mw_wc_qbo_sync_select2">
																			<?php $MWXS_L->only_option($qost_va,$qost_arr);?>
																		</select>
																	</td>
																</tr>
																<?php endforeach;?>
																
																<?php 
																	$qost_va = '';
																	if(is_array($osa_pr_map_data) && isset($osa_pr_map_data['wc_guest_user'])){
																		$qost_va = $osa_pr_map_data['wc_guest_user'];
																	}
																?>
																
																<tr style="border:none; background:none;">
																	<td>
																		<strong>Guest User</strong>
																		<input type="hidden" name="vpr_wr[]" value="wc_guest_user">
																	</td>
																	
																	<td>
																		<select name="vpr_qost[]" class="filled-in production-option mw_wc_qbo_sync_select2">
																			<?php $MWXS_L->only_option($qost_va,$qost_arr);?>
																		</select>
																	</td>
																</tr>											
															</table>
														</td>
														
														<td>															
															<?php myworks_woo_sync_for_xero_set_tooltip('Choose Wocommerce Order Syns as Xero Invoice or Quote.');?>
														</td>
													</tr>
													<?php endif;?>
													
													<!--**-->
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Use Next Xero Order #',
																	'name' => 'use_next_xero_order_number',
																	
																	'tt_text' => 'Check to sync orders to Xero using the NEXT Xero Invoice/Quote # - instead of the WooCommerce order number.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'textbox',
																array(
																	'f_title' => 'Block syncing orders before ID',
																	'name' => 'block_syncing_orders_before_id',
																	
																	'tt_text' => 'Disable/block syncing WooCommerce orders before this Order ID to Xero Online. Default is 0 as previous orders will not be synced anyways unless edited and saved.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Do not Sync $0 Orders',
																	'name' => 'do_not_sync_0_orders',
																	
																	'tt_text' => 'Select to disable the real-time syncing of invoices with a $0 total to Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Sync order notes to',
																	'name' => 's_order_notes_to',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => array(
																		'' => 'None',																		
																		'Line_Item' => 'Line Item',
																	),
																	
																	'tt_text' => 'Select the Xero Field for Syncing WooCommerce Order Note contents to the Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Void orders in Xero when WooCommerce order is cancelled',
																	'name' => 'void_xero_order_on_wc_cancelled',
																	
																	'tt_text' => 'Check to mark orders as void in Xero when cancelled in WooCommerce. Works in real-time, not applicable to historical orders.'
																),
															);
														?>
													</tr>													
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Sync all orders to one Xero Customer',
																	'name' => 's_all_orders_to_one_xero_customer',
																	
																	'tt_text' => 'Turn on to sync WooCommerce orders to one Xero Customer.'
																),
															);
														?>
													</tr>
													
													<!--**-->
													<?php if(is_array($wu_roles) && count($wu_roles)):?>
													<?php
														$aotc_rcm_data = get_option('mw_wc_xero_sync_aotc_rcm_data');
													?>
													
													<tr id="saoqc_tr" <?php if(!$MWXS_L->option_checked('mw_wc_xero_sync_s_all_orders_to_one_xero_customer')) echo 'style="display: none;"' ?>>
														<th class="title-description">
															<?php echo __('WooCommerce User Role -> Xero Customer Mapping','mw_wc_qbo_desk') ?>
														</th>
														
														<td>
															<table>
																<tr>
																	<td><strong>WooCommerce Role</strong></td>
																	<td style="text-align:center;"><strong>Xero Customer</strong></td>
																</tr>
																
																<?php foreach ($wu_roles as $role_name => $role_info):?>
																<tr style="border:none; background:none;">
																	<td width="30%">
																		<?php echo $MWXS_L->escape($role_info['name']);?>
																		<input type="hidden" name="saoqc_wr[]" value="<?php echo esc_attr($role_name);?>">
																	</td>
																	
																	<?php
																	$X_C_ID = (is_array($aotc_rcm_data) && isset($aotc_rcm_data[$role_name]))?$aotc_rcm_data[$role_name]:'';																	
																	
																	$dd_ext_class = '';
																	if($is_ajax_dd){
																		$dd_ext_class = 'mwqs_dynamic_select';																		
																	}else{																		
																		if(!empty($X_C_ID)){																			
																			$s_o_s_arr['#saoqc_qc_'.$role_name] = $X_C_ID;
																		}
																	}																	
																	?>

																	<td>																		
																		<select id="saoqc_qc_<?php echo esc_attr($role_name);?>" name="saoqc_qc[]" class="filled-in production-option mw_wc_qbo_sync_select2_cus <?php echo esc_attr($dd_ext_class);?>">																			
																			<?php 
																				if($is_ajax_dd){
																					if(!empty($X_C_ID) && $X_C_ID != 'Individual'){
																						$X_C_Name = $MWXS_L->get_field_by_val($MWXS_L->gdtn('customers'),'Name','ContactID',$X_C_ID);
																						echo '<option value="'.$MWXS_L->escape($X_C_ID).'">'.stripslashes($MWXS_L->escape($X_C_Name)).'</option>';
																					}else{
																						echo '<option value="Individual">Individual</option>';
																					}
																				}else{
																					echo '<option value="Individual">Individual</option>';
																					$MWXS_L->option_html('', $MWXS_L->gdtn('customers'),'ContactID','Name','',$xcsb.' ASC');
																				}
																			?>
																		</select>														
																	</td>
																</tr>
																<?php endforeach;?>
																
																<tr style="border:none; background:none;">
																	<td width="30%">
																		<strong>Guest User</strong>
																		<input type="hidden" name="saoqc_wr[]" value="wc_guest_user">
																	</td>
																	
																	<?php
																	$X_C_ID = (is_array($aotc_rcm_data) && isset($aotc_rcm_data['wc_guest_user']))?$aotc_rcm_data['wc_guest_user']:'';																	
																	
																	$dd_ext_class = '';
																	if($is_ajax_dd){
																		$dd_ext_class = 'mwqs_dynamic_select';																		
																	}else{																		
																		if(!empty($X_C_ID)){																			
																			$s_o_s_arr['#saoqc_qc_wc_guest_user'] = $X_C_ID;
																		}
																	}
																	?>
																	
																	<td>												
																		<select id="saoqc_qc_wc_guest_user" name="saoqc_qc[]" class="filled-in production-option mw_wc_qbo_sync_select2_cus <?php echo esc_attr($dd_ext_class);?>">																			
																			<?php 
																				if($is_ajax_dd){
																					if(!empty($X_C_ID) && $X_C_ID != 'Individual'){
																						$X_C_Name = $MWXS_L->get_field_by_val($MWXS_L->gdtn('customers'),'Name','ContactID',$X_C_ID);
																						echo '<option value="'.$MWXS_L->escape($X_C_ID).'">'.stripslashes($MWXS_L->escape($X_C_Name)).'</option>';
																					}else{
																						echo '<option value="Individual">Individual</option>';
																					}
																				}else{
																					echo '<option value="Individual">Individual</option>';
																					$MWXS_L->option_html('', $MWXS_L->gdtn('customers'),'ContactID','Name','',$xcsb.' ASC');
																				}
																			?>
																		</select>														
																	</td>
																</tr>
															</table>
														</td>
														
														<td>															
															<?php myworks_woo_sync_for_xero_set_tooltip('Select Xero Customer.');?>
														</td>
													</tr>
													<?php endif;?>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Automatically sync orders when they reach any of these statuses ',
																	'name' => 's_order_when_status_in',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => $wc_order_statuses,
																	'multiple_select' => true,
																	'd_val' => 'wc-processing,wc-completed',
																	
																	'tt_text' => 'Choose a/multiple WooCommerce status that will act as a trigger to real-time sync the order to Xero. Defaults are Processing and Completed.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Date for Xero Order',
																	'name' => 'order_date_val',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => array(
																		'order_date' => 'Order Date',
																		'order_paid_date' => 'Order Paid Date',
																		'order_completed_date' => 'Order Completed Date',
																		'date_of_sync' => 'Date of Sync',
																	),
																	
																	'tt_text' => 'Orders date field value when syncing into Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Value for Xero Description Line Item',
																	'name' => 'order_line_item_desc_val_s',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => array(
																		'wc_pv_name' => 'Name of WooCommerce Product/Variation (default)',
																		'wc_pv_short_desc' => 'Short Description of WooCommerce Product/Variation',
																		'xero_p_desc' => 'Xero Product Description',
																		'wc_pv_backorder_s' => 'Product Backorder Status',
																		'no_desc' => 'Nothing',
																	),
																	
																	'tt_text' => 'Select the line item description value for Xero order.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Add WooCommerce Custom Order Line Item Meta Into Xero Line Item Description',
																	'name' => 'add_w_oli_meta_into_xero_oli',
																	
																	'tt_text' => 'Turn on to add WooCommerce Custom Order line item meta into Invoice /Sales Receipts Line Item Description.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Email an Invoice from Xero to customer after order syncs',
																	'name' => 'send_invoice_email_after_sync',
																	
																	'tt_text' => 'Turn on to send an invoice after syncing into Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Status of the Xero Invoice when an unpaid Order is Synced',
																	'name' => 'xero_inv_status_for_unp_ord',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => array(
																		'DRAFT' => 'DRAFT',
																		'SUBMITTED' => 'SUBMITTED',
																		'AUTHORISED' => 'AUTHORISED',																		
																	),
																	
																	'tt_text' => 'Invoice status when syncing into Xero - Only applied to unpaid WooCommerce orders.'
																),
															);
														?>
													</tr>
													
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_tax_body" style="display: none;">
										<h6><?php echo _e('Tax Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Xero Non-taxable Rate',
																	'name' => 'non_taxable_rate',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_t_o_params,
																	's_data_function' => 'only_option',
																	's_blank_option' => true,
																	
																	'tt_text' => 'This should be set to a 0% or Out of Scope tax rate in Xero, as it will be used for any line items not charged any tax rate.'
																),
															);
														?>
													</tr>
													
													<?php 
														$f_name = 'mw_wc_xero_sync_order_tax_as_li';
													?>
													
													<tr>
														<th class="title-description">
															<?php echo __('Sync WooCommerce Order Tax as a Line Item','myworks-sync-for-xero') ?>
															
															<br>
															<span style="font-size:10px;color:grey;">
																<?php echo __('Used for Automated Sales Tax compatibility. If enabled, this will sync order tax as a line item instead of assigning it to a rate in Xero following mappings in MyWorks Sync > Map > Taxes.','myworks-sync-for-xero') ?>
															</span> 
															
														</th>
														
														<td>
															<div class="row">
																<div class="input-field col s12 m12 l12">
																	<p>
																		<input type="checkbox" class="filled-in mwqs_st_chk  production-option" name="<?php echo esc_attr($f_name);?>" id="<?php echo esc_attr($f_name);?>" value="true" <?php if($MWXS_L->option_checked($f_name)) echo 'checked' ?>>
																	</p>
																</div>
															</div>
														</td>
														
														<td>															
															<?php myworks_woo_sync_for_xero_set_tooltip('If enabled, this will override/invalidate any tax mappings set in MyWorks Sync > Map > Taxes, and sync order tax as a line item instead of assigning it to a rate in Xero.');?>
														</td>
													</tr>
													
													<tr id="otli_qp_tr" <?php if(!$MWXS_L->option_checked('mw_wc_xero_sync_order_tax_as_li')){echo 'style="display:none;"';}?>>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Xero Product for Sales Tax line item',
																	'name' => 'otli_xero_product',																	
																	
																	's_data_src' => 'Options_Params',
																	's_data_params' => $x_p_o_params,
																	's_data_function' => 'option_html',
																	's_blank_option' => true,
																	'ajax_dd' => $is_ajax_dd,
																	'a_d_d_t' => 'xero_product',
																	
																	'tt_text' => 'Choose a Xero Product that will be the line item in the Xero Invoice/Quote to represent the sales tax from the WooCommerce Order.'
																),
															);
														?>
													</tr>
													
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_mapping_body" style="display: none;">
										<h6><?php echo _e('Mapping Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Append User ID if customer name is taken',
																	'name' => 'customer_append_id_if_name_taken',
																	'd_val' => 'check_if_empty',
																	'tt_text' => 'Append the WooCommerce User ID to the Xero Name if the customer\'s name already exists in Xero. Prevents errors from occuring when a customer with the same name but non-matching email is being synced.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'textarea',
																array(
																	'f_title' => 'Xero Display Name format for new customers',
																	'name' => 'new_customer_dname_format',
																	'd_val' => '{firstname} {lastname}',
																	
																	'tt_text' => 'Choose the WooCommerce client name values you would like to be assigned to the Xero "Display Name As" client field. This setting will determine the value in the Xero Display Name for clients synced over. Choose either first/last name OR Company name - not both.{LB}{BOLD_S}Available Tags: {firstname} , {lastname} , {companyname} , {id} ,{email} ,{display_name}, {phone_number}{BOLD_E}'
																),
															);
														?>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_pull_body" style="display: none;">
										<h6><?php echo _e('Pull Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Status for new products synced into WooCommerce',
																	'name' => 'pulled_product_wc_status',
																	
																	's_data_src' => 'Array',
																	'd_val' => 'draft',
																	's_data_arr' => array(
																		'pending' => 'Pending Review',
																		'publish' => 'Published',
																		'draft' => 'Draft',																		
																	),
																	
																	'tt_text' => 'Choose the product status that products inherit when they are first pulled in WooCommerce.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Update order to this status when payment is added in Xero',
																	'name' => 'payment_pull_wc_order_status',
																	
																	's_data_src' => 'Array',
																	's_blank_option' => true,
																	's_data_arr' => $wc_order_statuses,
																	
																	'tt_text' => 'Change the WooCommerce Order Status for orders in these statuses, when a payment is applied to the related invoice in Xero.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Don\'t update orders in these statuses',
																	'name' => 'prevent_payment_pull_wc_order_status',
																	'multiple_select' => true,
																	's_data_src' => 'Array',
																	's_blank_option' => true,
																	's_data_arr' => $wc_order_statuses,
																	
																	'tt_text' => 'Prevent pulling payments (changing WooCommerce order status) for orders in these statuses.'
																),
															);
														?>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_automatic_sync_body" style="display: none;">
										<h6><?php echo _e('Automatic Sync Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr>
														<th class="title-description">
															<b><?php echo __('WooCommerce > Xero','myworks-sync-for-xero') ?></b>
														</th>
														<td></td>
														<td>
															<?php myworks_woo_sync_for_xero_set_tooltip('Check to enable automatic sync for WooCommerce > Xero.');?>
														</td>
													</tr>
													
													<?php 
														$xero_rt_push_items = array(
															'Customer' => 'Customer',
															'Order' => 'Order',
															'Product' => 'Product',
															'Variation' => 'Variation',
															'Payment' => 'Payment',
														);
													?>
													<tr>
														<th class="title-description">
															<?php echo __('Data Types','mw_wc_qbo_sync') ?>															
														</th>
														
														<td>
															<div class="row">
																<div class="input-field col s12 m12 l12">
																	<p>
																		<?php if(is_array($xero_rt_push_items) && count($xero_rt_push_items)):?>
																		<?php 																			
																			$rpi_val_arr = '';
																			$mw_wc_xero_sync_rt_push_items = $MWXS_L->get_option('mw_wc_xero_sync_rt_push_items');
																			if(!empty($mw_wc_xero_sync_rt_push_items)){
																				$rpi_val_arr = explode(',',$mw_wc_xero_sync_rt_push_items);
																			}
																		?>
																		<?php foreach($xero_rt_push_items as $rpi_key => $rpi_val):?>
																		<?php
																			$rpi_checked = '';
																			if(is_array($rpi_val_arr) && in_array($rpi_key,$rpi_val_arr)){
																				$rpi_checked = ' checked';
																			}
																		?>
																		
																		<input type="checkbox" class="filled-in mwqs_st_chk  production-option" name="mw_wc_xero_sync_rt_push_items[]" id="mw_wc_xero_sync_rt_push_items_<?php echo esc_attr($rpi_key);?>" value="<?php echo esc_attr($rpi_key);?>" <?php echo esc_attr($rpi_checked);?>>
																		&nbsp;
																		<span class="rt_item_hd"><?php echo $MWXS_L->escape($rpi_val);?></span>
																		
																		<?php 
																			if($rpi_val == 'Product'):
																			$qcit = $MWXS_L->get_option('mw_wc_xero_sync_queue_cron_interval_time');
																			
																			$oa_qit = array(
																				'MWXS_5min'=>'5 minutes',
																				'MWXS_10min'=>'10 minutes',
																				'MWXS_15min'=>'15 minutes',
																				'MWXS_30min'=>'30 minutes',
																				'MWXS_60min'=>'1 hour'
																			);
																			
																			$oda = array();
																		?>
																		
																		<select name="mw_wc_xero_sync_queue_cron_interval_time" id="mw_wc_xero_sync_queue_cron_interval_time" class="mw_wc_qbo_sync_select2">
																			<?php $MWXS_L->only_option($qcit,$oa_qit,'','',false,$oda)?>
																		</select>
																		<?php endif;?>
																		
																		<br /><br />
																		<?php endforeach;?>
																		<?php endif;?>										            
																	</p>
																</div>
															</div>
														</td>
														
														<td>
															<div class="material-icons tooltipped right tooltip"><?php echo __('?','myworks-sync-for-xero') ?>
																<span class="tooltiptext" style="top: -300;left: -410px;width: 400px;text-align: left;">																	
																	<b><?php echo __('Customer','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Add/update Xero customers when WooCommerce customers are added/updated.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Order','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Add/update Xero invoices/quotes when WooCommerce orders are placed/updated.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Product','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Add/update Xero products when WooCommerce products are added/updated. This covers product title, description and price. Settings to control this are in Settings > Pull above.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Variation','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Add/update Xero products when WooCommerce variations are added/updated. This covers variation title, description and price. Settings to control this are in Settings > Pull above.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Payment','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Sync payments over to Xero when they are made in WooCommerce.','myworks-sync-for-xero')?>
																</span>																
															</div>
														</td>
													</tr>
													
													<tr>
														<th class="title-description">
															<b><?php echo __('Xero > WooCommerce','myworks-sync-for-xero') ?></b>
														</th>
														<td></td>
														<td>
															<?php myworks_woo_sync_for_xero_set_tooltip('Check to enable automatic sync for Xero > WooCommerce.');?>
														</td>
													</tr>
													
													<?php 
														$xero_rt_pull_items = array(
															'Product' => 'Product',
															'Inventory' => 'Inventory',
															'Payment' => 'Payment',															
														);
													?>
													<tr>
														<th class="title-description">
															<?php echo __('Data Types','mw_wc_qbo_sync') ?>															
														</th>
														
														<td>
															<div class="row">
																<div class="input-field col s12 m12 l12">
																	<p>
																		<?php if(is_array($xero_rt_pull_items) && count($xero_rt_pull_items)):?>
																		<?php 
																			$rpi_val_arr = '';
																			$mw_wc_xero_sync_rt_pull_items = $MWXS_L->get_option('mw_wc_xero_sync_rt_pull_items');
																			if(!empty($mw_wc_xero_sync_rt_pull_items)){
																				$rpi_val_arr = explode(',',$mw_wc_xero_sync_rt_pull_items);
																			}																			
																		?>
																		<?php foreach($xero_rt_pull_items as $rpi_key => $rpi_val):?>
																		<?php
																			$rpi_checked = '';
																			if(is_array($rpi_val_arr) && in_array($rpi_key,$rpi_val_arr)){
																				$rpi_checked = ' checked';
																			}
																		?>
																		
																		<input type="checkbox" class="filled-in mwqs_st_chk  production-option" name="mw_wc_xero_sync_rt_pull_items[]" id="mw_wc_xero_sync_rt_pull_items_<?php echo esc_attr($rpi_key);?>" value="<?php echo esc_attr($rpi_key);?>" <?php echo esc_attr($rpi_checked);?>>
																		&nbsp;
																		<span class="rt_item_hd"><?php echo $MWXS_L->escape($rpi_val);?></span>
																		
																		<?php 
																			if($rpi_val == 'Inventory'):
																			$ipit = $MWXS_L->get_option('mw_wc_xero_sync_ivnt_pull_interval_time');
																			
																			$oa_iit = array(
																				'MWXS_5min'=>'5 minutes',																				
																				'MWXS_15min'=>'15 minutes',
																				'MWXS_30min'=>'30 minutes',
																				'MWXS_60min'=>'1 hour',
																				'MWXS_360min'=>'6 hour'
																			);
																			
																			$oda = array();
																		?>
																		
																		<select name="mw_wc_xero_sync_ivnt_pull_interval_time" id="mw_wc_xero_sync_ivnt_pull_interval_time" class="mw_wc_qbo_sync_select2">
																			<?php $MWXS_L->only_option($ipit,$oa_iit,'','',false,$oda)?>
																		</select>
																		<?php endif;?>
																		
																		<br /><br />
																		<?php endforeach;?>
																		<?php endif;?>										            
																	</p>
																</div>
															</div>
														</td>
														
														<td>
															<div class="material-icons tooltipped right tooltip"><?php echo __('?','myworks-sync-for-xero') ?>
																<span class="tooltiptext" style="top: -300;left: -410px;width: 400px;text-align: left;">																	
																	<b><?php echo __('Inventory','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Update WooCommerce inventory when Xero inventory levels are updated.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Product','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Add/Update WooCommerce product when Xero products are added/updated.','myworks-sync-for-xero')?>
																	<br><br><b><?php echo __('Payment','myworks-sync-for-xero')?></b><br>
																	<?php echo __('Change a WooCommerce order status when payment is applied to the related invoice in Xero. Settings to control this are in Settings > Pull above.','myworks-sync-for-xero')?>																
																</span>																
															</div>
														</td>
													</tr>
													
												</tbody>
											</table>
										</div>
									</div>
									
									<div id="s_tab_miscellaneous_body" style="display: none;">
										<h6><?php echo _e('Miscellaneous Settings','myworks-sync-for-xero');?></h6>
										<div class="myworks-wc-qbo-sync-table-responsive myworks-setting">
											<table class="mw-qbo-sync-settings-table mwqs_setting_tab_body_body">
												<tbody>
													<tr height="50">
														<td colspan="3">
															<b><?php echo __('Customer Account Area','myworks-sync-for-xero') ?></b>										
														</td>								
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Enable Invoices tab in the WooCommerce Account menu',
																	'name' => 'invoice_tab_in_cus_acc_area',
																	
																	'tt_text' => 'If enabled, an Invoices tab will be present in the front-end WooCommerce Account menu - where the customer can view/pay a list of invoices present in their Xero customer account - based on their customer mapping in MyWorks Sync > Map > Customers.'
																),
															);
														?>
													</tr>
													
													<tr height="50">
														<td colspan="3">
															<b><?php echo __('Plugin Dropdown Settings','myworks-sync-for-xero') ?></b>										
														</td>								
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Enable Select2 searchable dropdown style',
																	'name' => 'enable_select2_dd',
																	'd_val' => 'check_if_empty',
																	'tt_text' => 'This setting is on by default - to enable the Select2 dropdown style. Turn this off to display a normal dropdown for the plugin.'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Enable Optimized AJAX-Search-Only for Select2 Dropdowns (customer and product)',
																	'name' => 'select2_ajax_dd',
																	
																	'tt_text' => 'Enable Optimized AJAX search only for Select2 dropdown styles. This option is applicable if Select2 is enabled on above setting. This is efficient if your install has huge customer and product data lists and will help avoid page load lags.'
																),
															);
														?>
													</tr>
													
													<tr height="50">
														<td colspan="3">
															<b><?php echo __('Log Settings','myworks-sync-for-xero') ?></b>										
														</td>								
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'select',
																array(
																	'f_title' => 'Save Logs for Days',
																	'name' => 'save_log_for_days',
																	
																	's_data_src' => 'Array',
																	's_data_arr' => array(
																		'30' => '30 days',
																		'60' => '60 days',
																		'90' => '90 days',
																		'120' => '120 days',
																		'NL' => 'No Limit',
																	),
																	'd_val' => '30',
																	
																	'tt_text' => 'Choose how many days log entry you want to save.'
																),
															);
														?>
													</tr>
													
													<tr height="50">
														<td colspan="3">
															<b><?php echo __('Plugin Debug Settings','myworks-sync-for-xero') ?></b>
														</td>								
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Add Xero Sync Error (Add/Update) Data Into Log File',
																	'name' => 'add_sync_error_data_into_log_file',
																	
																	'tt_text' => 'Only for debug, Add Xero error data into log file (last 24 hours).'
																),
															);
														?>
													</tr>
													
													<tr>
														<?php
															myworks_woo_sync_for_xero_g_settings_field(
																'option_check',
																array(
																	'f_title' => 'Add Xero Sync Success (Add/Update) Data Into Log File',
																	'name' => 'add_sync_success_data_into_log_file',
																	
																	'tt_text' => 'Only for debug, Add Xero success data into log file (last 24 hours).'
																),
															);
														?>
													</tr>
													
												</tbody>
											</table>
										</div>
									</div>
									
									<div class="mw_wc_qbo_sync_clear"></div>
									<?php wp_nonce_field( 'myworks_wc_xero_sync_save_settings', 'wxs_save_settings' ); ?>
									<div class="row">
										<div class="input-field col s12 m6 l4">
											<input type="submit" name="mw_wc_xero_sync_settings" id="mw_wc_xero_sync_settings" class="waves-effect waves-light btn save-btn mw-qbo-sync-green" value="Save All">
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		/*Tab*/
		$('.mwqs_stb a').on('click',function(e){
			var click_id = $(this).attr('id');
			$('#settings_current_tab').val(click_id);
			
			$('.mwqs_stb a').each(function(){
				var tab_id = $(this).attr('id');
				if(tab_id!=click_id){
					$('#'+tab_id).parent().removeClass('active');					
					$('#'+tab_id+'_body').hide();					
				}
			});
			
			$('#'+click_id).parent().addClass('active');
			$('#'+click_id+'_body').show();
		});
		
		$('#'+$('#settings_current_tab').val()).parent().addClass('active');
		$('#'+$('#settings_current_tab').val()+'_body').show();		
		
		<?php 
			if(is_array($s_o_s_arr) && !empty($s_o_s_arr)){
				foreach($s_o_s_arr as $k => $v){
					echo '$(\''.$MWXS_L->escape($k).'\').val(\''.$MWXS_L->escape($v).'\');';
				}
			}
		?>
		
		$("input:radio[name=mw_wc_xero_sync_order_sync_as]").click(function(){
			if($(this).attr('id') == 'wo_qsa_vpr'){
				$('#wo_qsa_vpr_map_tr').fadeIn("slow");
				$('#qbtao_tr').fadeOut("slow");
			}else{
				$('#wo_qsa_vpr_map_tr').fadeOut("slow");
				$('#qbtao_tr').fadeIn("slow");
			}
			
			if($(this).attr('id') == 'wo_qsa_pg'){
				$('#mwoqsa_rm').html('<small>Please select the order sync type per gateway in Map > Payment Method page.</small>');
				$('#mwoqsa_rm').fadeIn("slow");
			}else{
				$('#mwoqsa_rm').fadeOut("slow");
			}
			
		});
		
		/*Bootstrap Switch*/
		$('input.mwqs_st_chk').attr('data-size','small');
		$('input.mwqs_st_chk').bootstrapSwitch();
		
		$('#mw_wc_xero_sync_order_tax_as_li').on('switchChange.bootstrapSwitch', function (event, state) {		
			if($(this).is(':checked')) {
				$('#otli_qp_tr').fadeIn("slow");			
			} else {
				$('#otli_qp_tr').fadeOut("slow");
			}
		});
		
		$('#mw_wc_xero_sync_s_all_orders_to_one_xero_customer').on('switchChange.bootstrapSwitch', function (event, state) {
			if($(this).is(':checked')) {
				$('#saoqc_tr').fadeIn("slow");
			} else {
				$('#saoqc_tr').fadeOut("slow");
			}
			
		});
	});
</script>
<?php 
	if(!empty($settings_save_status)){
		myworks_woo_sync_for_xero_set_admin_sweet_alert($settings_save_status);
	}
?>

<?php myworks_woo_sync_for_xero_get_select2_js('.mw_wc_qbo_sync_select2','xero_product');?>
<?php myworks_woo_sync_for_xero_get_select2_js('.mw_wc_qbo_sync_select2_cus','xero_customer',true);?>