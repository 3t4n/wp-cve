<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'payment-method';

$table = $MWXS_L->gdtn('map_payment_method');
# POST Action
if (! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_map_wc_xero_payment_methods', 'map_wc_xero_payment_methods' ) ) {
	#$MWXS_L->_p($_POST);	
	$wpdb->query($wpdb->prepare("DELETE FROM `{$table}` WHERE `id` > %d",0));
	$wpdb->query("TRUNCATE table {$table}");	
	
	foreach($_POST as $k=>$val){
		$k = $MWXS_L->sanitize($k);
		$val = $MWXS_L->array_sanitize($val);
		
		$kpm = false;
		 if (preg_match('#^kpm__#', $k) === 1) {
			$kpm = true;
		 }
		
		if($kpm){
			$p_method_str = $k;
			$p_method_arr = array();
			
			if($kpm){
				$p_method_arr = explode('__',$p_method_str);
			}				

			$p_method = '';
			$p_cur = '';
			
			if(is_array($p_method_arr) && count($p_method_arr)>2){
				$p_method = $p_method_arr[1];
				$p_cur = $p_method_arr[2];
			}
			
			if(!empty($p_method) && !empty($p_cur)){
				$p_map_ep = (isset($_POST[$p_method.'__'.$p_cur.'_ep']))?1:0;
				$x_ba_id  = $MWXS_L->var_p('pm__'.$p_method.'__'.$p_cur);

				$p_map_etfngli = (isset($_POST[$p_method.'__'.$p_cur.'_etfngli']))?1:0;
				$p_map_tfnlixp  = $MWXS_L->var_p($p_method.'__'.$p_cur.'_tfnlixp');

				$p_map_iddd  = $MWXS_L->var_p($p_method.'__'.$p_cur.'_iddd');
				$p_map_apsos = $MWXS_L->var_p($p_method.'__'.$p_cur.'_apsos');
				
				# Save
				$pm_map_save_data = array();
				
				$pm_map_save_data['wc_payment_method'] = $p_method;					
				$pm_map_save_data['currency'] = $p_cur;
				
				$pm_map_save_data['enable_payment'] = $p_map_ep;
				$pm_map_save_data['X_ACC_ID'] = $x_ba_id;

				$pm_map_save_data['enable_txn_fee'] = $p_map_etfngli;
				$pm_map_save_data['txn_fee_x_product'] = $p_map_tfnlixp;

				$pm_map_save_data['x_invoice_ddd'] = (int) $p_map_iddd;
				$pm_map_save_data['aps_order_status'] = $p_map_apsos;
				
				$pm_map_save_data = array_map(array($MWXS_L, 'sanitize'), $pm_map_save_data);					
				$wpdb->insert($table, $pm_map_save_data);
			}
		}
	}
	
	$MWXS_L->set_session_val('map_page_update_message',__('Payment methods mapped successfully.','myworks-sync-for-xero'));
	$MWXS_L->redirect($page_url);
	
}

$wc_p_methods = $this->dlobj->get_wc_active_payment_gateways();
#$wc_currency_list = array();
# Will add multi currency conditions later
$wc_currency_list[] = get_woocommerce_currency();

$MWXS_L->xero_connect();
$xaa = $MWXS_L->xero_get_accounts_kva();

$is_ajax_dd = $MWXS_L->is_s2_ajax_dd();

if(!$is_ajax_dd){
	$xpsb = 'Name';	
}

$is_valid_pm = false;
$pm_map_data = array();
if(is_array($wc_p_methods) && !empty($wc_p_methods) && is_array($wc_currency_list) && !empty($wc_currency_list)){
	$is_valid_pm = true;
	$pm_map_data = $MWXS_L->get_tbl($table);
}

$wc_order_statuses = wc_get_order_statuses();

require_once plugin_dir_path( __FILE__ ) . 'map-nav.php';
?>
<style>
	.pmm_aps{display:none;}
	.hide_advanced_payment_sync{display:none;}
	.mw_pmm_tbl{border-bottom:1px solid #DDDDDD;}
</style>

<div class="container map-product-responsive">
	<div class="page_title">
		<div class="page_title flex-box">
			<h4><?php _e( 'Payment Method Mappings', 'myworks-sync-for-xero' );?></h4>
			<div class="dashboard_main_buttons p-mapbtn">
				<button class="show_advanced_payment_sync" id="show_advanced_payment_sync">Show Advanced Options</button>
				<button class="hide_advanced_payment_sync" id="hide_advanced_payment_sync">Hide Advanced Options</button>
			</div>
		</div>
	</div>
	
	<div class="card">
		<div class="card-content">
			<div class="row">
				<?php if($is_valid_pm):?>
				<form method="POST" class="col s12 m12 l12" id="mw_pmm_form" onsubmit="javascript:return mw_pmm_f_validation();">
					<div class="row">
						<div class="col s12 m12 l12">
							<?php foreach($wc_p_methods as $pm_key => $pm_val):?>
							<div class="pm_map_list" style="margin:10px 0px 10px 0px;">
								<h5><?php echo $MWXS_L->escape($pm_val).' ('.$MWXS_L->escape($pm_key).')';?></h5>
								<div class="myworks-wc-qbo-sync-table-responsive">
									<table class="mw-qbo-sync-settings-table menu-blue-bg menu-bg-a new-table mw_pmm_tbl" style="width:100%" cellpadding="5" cellspacing="5">
										<thead>
											<tr>
												<th width="40%">&nbsp;</th>
												<?php foreach($wc_currency_list as $c_val):?>
												<th><b><?php echo $MWXS_L->escape($c_val);?></b></th>
												<?php endforeach;?>
												<th>&nbsp;</th>
											</tr>
										</thead>
										
										<tbody>
											<tr class="pmm_dps">
												<td height="40">
													<?php _e( 'Enable Payment Syncing', 'myworks-sync-for-xero' );?>
												</td>
												
												<?php foreach($wc_currency_list as $c_val):?>
												<td>
													<input data-cba="pm__<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>" type="checkbox" class="pm_chk_ep pm_chk" value="1" name="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_ep" id="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_ep">
												</td>												
												<?php endforeach;?>
												
												<td>
													<?php
														$tt = 'Enable the syncing of payments for this gateway & specific currency. If not enabled, payments will not be synced in real time to Xero.';
														myworks_woo_sync_for_xero_set_tooltip($tt);
													?>
												</td>
											</tr>
											
											<tr class="pmm_dps">
												<td><?php _e( 'Xero Bank Account', 'myworks-sync-for-xero' );?></td>
												<?php foreach($wc_currency_list as $c_val):?>
												<td class="new-widt">
													<input type="hidden" name="kpm__<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>">
													
													<select class="qbo_select dd_xba" name="pm__<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>" id="pm__<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>" style="background-color:#f4f4f4" disabled>														
														<?php $MWXS_L->only_option('',$xaa);?>
													</select>
												</td>
												<?php endforeach;?>
												
												<td>
													<?php
														$tt = 'Choose the Bank Account in Xero that payments from your woocommerce gateway will be deposited into in real life / in Xero.';
														myworks_woo_sync_for_xero_set_tooltip($tt);
													?>
												</td>
											</tr>
											
											<tr class="pmm_dps">
												<td height="40">
													<?php _e( 'Enable Transaction Fee As Negative Line Item', 'myworks-sync-for-xero' );?>
												</td>

												<?php foreach($wc_currency_list as $c_val):?>
												<td>
													<input class="pm_chk pm_chk_etfngli" type="checkbox" value="1" name="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_etfngli" id="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_etfngli" data-cba="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_tfnlixp">
												</td>
												<?php endforeach;?>

												<td>
													<?php
													$tt = 'Enable this to sync transaction fee as negative line item in the order.';
													myworks_woo_sync_for_xero_set_tooltip($tt);
													?>												
												</td>
											</tr>
											
											<tr class="pmm_dps">
												<td height="40">
													<?php _e( 'Transaction Fee Negative Line Item Product', 'myworks-sync-for-xero' );?>
												</td>

												<?php foreach($wc_currency_list as $c_val):?>
												<?php													
													$dd_ext_class = '';													
													if($is_ajax_dd){
														$dd_ext_class = 'mwqs_dynamic_select';														
														
													}
												?>
												
												<td>
													<select class="qbo_select <?php echo esc_attr($dd_ext_class);?>" name="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_tfnlixp" id="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_tfnlixp" style="background-color:#f4f4f4" disabled>
														<?php 
															if($is_ajax_dd){
																$X_ItemID = '';
																if(is_array($pm_map_data) && !empty($pm_map_data)){
																	foreach($pm_map_data as $list){
																		if($list['wc_payment_method'] == $pm_key && $list['currency'] == $c_val){
																			$X_ItemID = $list['txn_fee_x_product'];
																			break;
																		}
																	}
																}

																if(!empty($X_ItemID)){
																	$x_item_name = $MWXS_L->get_field_by_val($MWXS_L->gdtn('products'),'Name','ItemID',$X_ItemID);
																	if(!empty($x_item_name)){
																		echo '<option value="'.$MWXS_L->escape($X_ItemID).'">'.stripslashes($MWXS_L->escape($x_item_name)).'</option>';
																	}
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
												<?php endforeach;?>
												
												<td>
													<?php
													$tt = 'Choose the Xero product for txn fee negative line item.';
													myworks_woo_sync_for_xero_set_tooltip($tt);
													?>												
												</td>
											</tr>
											
											<tr class="pmm_dps">
												<td height="40">
													<?php _e( 'Xero Invoice Due Date Delay', 'myworks-sync-for-xero' );?>
												</td>

												<?php foreach($wc_currency_list as $c_val):?>
												<td>
													<select class="qbo_select <?php echo esc_attr($dd_ext_class);?>" name="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_iddd" id="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_iddd">
														<option value="0">0</option>
														<?php $MWXS_L->only_option('',$MWXS_L->invoice_due_days_options());?>
													</select>
												</td>
												<?php endforeach;?>

												<td>
													<?php
													$tt = 'Select the amount of days from the order date to set the Xero Invoice Due Date field. The default is 0 - the same date as the WooCommerce order.';
													myworks_woo_sync_for_xero_set_tooltip($tt);
													?>												
												</td>
											</tr>
											
											<tr class="pmm_aps">
												<td height="40">
													<?php _e( 'Sync artificial payment when order is marked as', 'myworks-sync-for-xero' );?>
													<br>
													<span style="font-size:10px;color:grey;"><?php _e( 'This setting is ONLY for gateways like COD or Check where the payment is actually not recorded in WooCommerce.', 'myworks-sync-for-xero' );?></span>
												</td>
												
												<?php foreach($wc_currency_list as $c_val):?>
												<td>
													<select class="qbo_select <?php echo esc_attr($dd_ext_class);?>" name="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_apsos" id="<?php echo esc_attr($pm_key);?>__<?php echo esc_attr($c_val);?>_apsos">
														<option value=""></option>
														<?php $MWXS_L->only_option('',$wc_order_statuses);?>
													</select>
												</td>
												<?php endforeach;?>
												
												<td>
													<?php
													$tt = 'This setting is ONLY for gateways like COD or BACS where the payment is actually not recorded in WooCommerce. When orders are placed with these types of gateways, there is no actual payment recorded in WooCommerce, so the payment can only be synced to Xero when the order reaches a certain status.';
													myworks_woo_sync_for_xero_set_tooltip($tt);
													?>												
												</td>
											</tr>

										</tbody>
									</table>								
								</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>					
					
					<div class="row">
						<?php wp_nonce_field( 'myworks_wc_xero_sync_map_wc_xero_payment_methods', 'map_wc_xero_payment_methods' ); ?>
						<div class="input-field col s12 m6 l4">
							<button id="mw_pmm_sbtn" class="waves-effect waves-light btn save-btn mw-qbo-sync-green" disabled>
								<?php _e( 'Save', 'myworks-sync-for-xero' );?>
							</button>
						</div>
					</div>					
				</form>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>

<?php if($is_valid_pm):?>

<script type="text/javascript">
	jQuery(document).ready(function($){	
		/*On Click Checking*/	
		$('.pm_chk_ep , .pm_chk_etfngli').on('switchChange.bootstrapSwitch', function () {
			let chk_ba = $(this).attr('data-cba');
			if(chk_ba==''){return false;}

			let hide_tr = $(this).hasClass('pm_chk_etfngli');

			if($(this).is(':checked')){
				$('#'+chk_ba).removeAttr('disabled');
				$('#'+chk_ba).css('background-color','#ffffff');

				if(hide_tr){
					$('#'+chk_ba).parent('td').parent('tr').fadeIn("slow");
				}
				
			}else{
				$('#'+chk_ba).val('');
				$('#'+chk_ba).attr('disabled','disabled');
				$('#'+chk_ba).css('background-color','#f4f4f4');

				if(hide_tr){
					$('#'+chk_ba).parent('td').parent('tr').fadeOut("slow");
				}				
			}			
		});

		/*Show Hide Advance Payment Sync*/
		$('#show_advanced_payment_sync').on('click', function(e){
			$('#show_advanced_payment_sync').hide();
			$('.pmm_aps').show();
			$('#hide_advanced_payment_sync').show();			
		});

		jQuery('#hide_advanced_payment_sync').on('click', function(e){
			$('#show_advanced_payment_sync').show();
			$('.pmm_aps').hide();
			$('#hide_advanced_payment_sync').hide();			
		});
		
		/*Select Values*/
		<?php if(is_array($pm_map_data) && !empty($pm_map_data)):?>
		<?php foreach($pm_map_data as $list):?>		
		
		<?php if($list['enable_payment'] == 1):?>
		$('#<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>_ep').prop('checked', true);
		$('#pm__<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>').val('<?php echo $MWXS_L->escape($list['X_ACC_ID']);?>');
		<?php endif;?>
		
		<?php if($list['enable_txn_fee'] == 1):?>
		$('#<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>_etfngli').prop('checked', true);
		$('#<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>_tfnlixp').val('<?php echo $MWXS_L->escape($list['txn_fee_x_product']);?>');
		<?php endif;?>
		
		$('#<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>_iddd').val('<?php echo $MWXS_L->escape($list['x_invoice_ddd']);?>');
		$('#<?php echo $MWXS_L->escape($list['wc_payment_method']);?>__<?php echo $MWXS_L->escape($list['currency']);?>_apsos').val('<?php echo $MWXS_L->escape($list['aps_order_status']);?>');
		
		<?php endforeach;?>
		<?php endif;?>
		
		/*Checking in Loop*/
		$('.pm_chk_ep , .pm_chk_etfngli').each(function(){
			let chk_ba = $(this).attr('data-cba');
			if(chk_ba==''){return false;}

			let hide_tr = $(this).hasClass('pm_chk_etfngli');
			
			if($(this).is(':checked')){
				$('#'+chk_ba).removeAttr('disabled');
				$('#'+chk_ba).css('background-color','#ffffff');

				if(hide_tr){
					$('#'+chk_ba).parent('td').parent('tr').show();	
				}				
			}else{
				$('#'+chk_ba).val('');
				$('#'+chk_ba).attr('disabled','disabled');
				$('#'+chk_ba).css('background-color','#f4f4f4');

				if(hide_tr){
					$('#'+chk_ba).parent('td').parent('tr').hide();
				}				
			}
		});
		
		/*Bootstrap Switch*/
		$('input.pm_chk').attr('data-size','small');
		$('input.pm_chk').bootstrapSwitch();

		/*Enable Save Button*/
		$('#mw_pmm_sbtn').removeAttr('disabled');

		/*Bank Account Filters*/
		$('.dd_xba option').filter(function() {
			return $.trim(this.text).indexOf('(Bank)') === -1;
		}).remove();
		
		//$(".dd_xba").prepend('<option value=""></option>');
	});
	
	/*Form Validation*/
	function mw_pmm_f_validation(){
		let ive = false;
		jQuery('.pm_chk_ep').each(function(){
			if(jQuery(this).is(':checked')){
				let epf_id = jQuery(this).attr('id');
				let epf_id_st = epf_id.substring(0,epf_id.length - 3);				
				let xmba_v = jQuery('#pm__'+epf_id_st).val();
				
				if(mw_xero_sync_string_is_empty(xmba_v)){
					ive = true;
				}
			}
		});
		
		if(ive){
			alert('Plesae select Xero bank account for all enabled payments gateways');
			return false;
		}		
	}	
</script>
<?php myworks_woo_sync_for_xero_get_select2_js('.qbo_select','xero_product');?>

<?php endif;?>