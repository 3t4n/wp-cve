<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;

$page_url = $UP.'tax-class';

$table = $MWXS_L->gdtn('map_tax');

# POST Action
if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_map_wc_xero_tax', 'map_wc_xero_tax' ) ) {
	$item_ids = array();
	$item_ids_combo = array();
	
	foreach ($_POST as $key=>$value){
		$key = $MWXS_L->sanitize($key);
		$value = $MWXS_L->array_sanitize($value);
		
		if ($MWXS_L->start_with($key, "wtax_")){
			$id = (int) str_replace("wtax_", "", $key);			
			$item_ids[$id] = $value;
		}
		
		if ($MWXS_L->start_with($key, "cobmbo_wtax_")){
			$id = (int) str_replace("cobmbo_wtax_", "", $key);			
			//if($id && $value!=''){}
			$item_ids_combo[$id] = $value;
		}	
	}
	
	$is_tax_saved = false;
	
	if(count($item_ids)){
		foreach ($item_ids as $key=>$value){
			$save_data = array();
			$save_data['wc_tax_id'] = $key;
			$save_data['xero_tax'] = $value;
			$save_data['wc_tax_id_2'] = 0;				
			
			//Update
			$eq = $wpdb->prepare("SELECT `id` FROM `{$table}` WHERE `wc_tax_id` = %d AND `wc_tax_id_2` = %d ",$save_data['wc_tax_id'],$save_data['wc_tax_id_2']);
			$ed = $MWXS_L->get_row($eq);
			
			if(is_array($ed) && !empty($ed)){
				unset($save_data['wc_tax_id']);
				unset($save_data['wc_tax_id_2']);
				$wpdb->update($table,$save_data,array('id'=>$ed['id']),'',array('%d'));
			}else{					
				$wpdb->insert($table, $save_data);
			}				
		}
		
		$is_tax_saved = true;		
	}
	
	if(count($item_ids_combo)){		
		foreach ($item_ids_combo as $key=>$value){
			$save_data = array();
			$save_data['wc_tax_id'] = $key;
			$save_data['xero_tax'] = $value;				
			$save_data['wc_tax_id_2'] = (int) $MWXS_L->var_p('sc_wtax_'.$key,0);
			
			if($save_data['wc_tax_id_2'] < 1){
				$wpdb->query($wpdb->prepare("DELETE FROM `".$table."` WHERE `wc_tax_id` = %d AND `wc_tax_id_2` > 0 ",$key));
				continue;
			}				
			
			//Update
			$eq = $wpdb->prepare("SELECT `id` FROM `{$table}` WHERE `wc_tax_id` = %d AND `wc_tax_id_2` = %d ",$save_data['wc_tax_id'],$save_data['wc_tax_id_2']);
			
			$ed = $MWXS_L->get_row($eq);				
			if(is_array($ed) && !empty($ed)){
				unset($save_data['wc_tax_id']);
				unset($save_data['wc_tax_id_2']);
				$wpdb->update($table,$save_data,array('id'=>$ed['id']),'',array('%d'));
			}else{						
				$wpdb->insert($table, $save_data);
			}				
		}
		
		$is_tax_saved = true;
	}
	
	if($is_tax_saved){
		$MWXS_L->set_session_val('map_page_update_message',__('Tax rates mapped successfully.','myworks-sync-for-xero'));
	}	
	
	$MWXS_L->set_and_post('sh_aps_sec');

	$MWXS_L->redirect($page_url);
}


# Search and listing
$sh_aps_sec = $MWXS_L->get_session_val('sh_aps_sec');

$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('tax_map_search');
$tax_map_search = $MWXS_L->get_session_val('tax_map_search');

$wtr_t = $wpdb->prefix.'woocommerce_tax_rates';

$wc_tax_rates_a = $MWXS_L->get_tbl($wtr_t,'','','tax_rate_class ASC');
$wc_tax_rates_a = $this->dlobj->get_wc_tax_rates_a_lc_add($wc_tax_rates_a);

# Query
$tax_map_search = $MWXS_L->sanitize($tax_map_search);
$whr = '';

$wtr_lt = $wpdb->prefix.'woocommerce_tax_rate_locations';

$join = " LEFT JOIN `{$wtr_lt}` trl ON (tr.tax_rate_id = trl.tax_rate_id AND trl.location_type = 'city') ";

if($tax_map_search!=''){
	$whr.=" AND (tr.`tax_rate_name` LIKE '%$tax_map_search%' OR tr.`tax_rate_class` LIKE '%$tax_map_search%' OR trl.`location_code` LIKE '%$tax_map_search%' ) ";
}

$total_records = $wpdb->get_var("SELECT COUNT(*) FROM `".$wtr_t."` tr {$join} WHERE tr.`tax_rate_id` >0 {$whr} ");
$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$tax_q = "SELECT tr.* , trl.location_code FROM `".$wtr_t."` tr {$join} WHERE tr.`tax_rate_id` >0 {$whr} ORDER BY tr.`tax_rate_class` ASC LIMIT {$offset} , {$items_per_page} ";

$wc_tax_rates = $MWXS_L->get_data($tax_q);
#$MWXS_L->_p($wc_tax_rates);

$MWXS_L->xero_connect();
$xta = $MWXS_L->xero_get_tax_rates_kva();

$selected_options_script = '';
$s_o_s_arr = array();

$wc_all_tax_rates = $this->dlobj->get_wc_tax_rate_id_array($wc_tax_rates_a);
$tm_map_data = $MWXS_L->get_tbl($table);
#$MWXS_L->_p($tm_map_data);

if(is_array($tm_map_data) && count($tm_map_data)){
	foreach($tm_map_data as $tm_k=>$tm_val){		
		if($tm_val['wc_tax_id_2']>0){
			$tl_tax_rate_class = (isset($wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_class']))?$wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_class']:'';
			$tl_tax_rate_class = ($tl_tax_rate_class=='')?'Standard rate':ucfirst(str_replace('-',' ',$tl_tax_rate_class));
			$tl_city = (isset($wc_all_tax_rates[$tm_val['wc_tax_id_2']]['location_code']))?$wc_all_tax_rates[$tm_val['wc_tax_id_2']]['location_code']:'';
			$tl_country = (isset($wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_country']))?$wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_country']:'';
			$tl_state = (isset($wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_state']))?$wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate_state']:'';
			$tl_taxrate = (isset($wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate']))?$wc_all_tax_rates[$tm_val['wc_tax_id_2']]['tax_rate']:'';

			$s_o_s_arr['#sc_wtax_'.$tm_val['wc_tax_id']] = $tm_val['wc_tax_id_2'];
			$s_o_s_arr['#cobmbo_wtax_'.$tm_val['wc_tax_id']] = $tm_val['xero_tax'];

			$s_o_s_arr['#tl_tax_rate_class_'.$tm_val['wc_tax_id']] = $tl_tax_rate_class;
			$s_o_s_arr['#tl_city_'.$tm_val['wc_tax_id']] = $tl_city;
			$s_o_s_arr['#tl_country_'.$tm_val['wc_tax_id']] = $tl_country;
			$s_o_s_arr['#tl_state_'.$tm_val['wc_tax_id']] = $tl_state;
			$s_o_s_arr['#tl_taxrate_'.$tm_val['wc_tax_id']] = $tl_taxrate;
		}else{			
			$s_o_s_arr['#wtax_'.$tm_val['wc_tax_id']] = $tm_val['xero_tax'];
		}		
	}	
}

require_once plugin_dir_path( __FILE__ ) . 'map-nav.php';

?>

<div class="container map-tax-class-outer map-product-responsive">
	<div class="page_title flex-box">
		<h4><?php _e( 'Tax Mappings', 'myworks-sync-for-xero' );?></h4>
		<div class="dashboard_main_buttons p-mapbtn">
			<?php if($sh_aps_sec != 'show'):?>
			<button class="sh_compound_tx show_advanced_payment_sync">Show Compound Taxes</button>
			<?php else:?>
			<button class="sh_compound_tx hide_advanced_payment_sync">Hide Compound Taxes</button>
			<?php endif;?>
		</div>
	</div>
	
	<div class="mw_wc_filter">
		<span class="search_text"><?php _e( 'Search', 'myworks-sync-for-xero' );?></span>
		&nbsp;
		<input type="text" id="tax_map_search" placeholder="" value="<?php echo esc_attr($tax_map_search);?>">
		
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
								<table class="mw-qbo-sync-map-table menu-blue-bg" width="100%">
									<thead>
										<tr>
											<th width="5%" class="title-description" id="th_id">
												<?php _e( 'ID', 'myworks-sync-for-xero' );?>							    	
											</th>
											
	                                        <th width="25%" class="title-description" id="th_tn">
												<?php _e( 'Tax Name', 'myworks-sync-for-xero' );?>
	                                        </th>
											
	                                        <th width="10%" class="title-description" id="th_tc">
	                                            <?php _e( 'Tax Class', 'myworks-sync-for-xero' );?>	
	                                        </th>
											
											<th width="10%" class="title-description" id="th_ct">
	                                            <?php _e( 'City', 'myworks-sync-for-xero' );?>
	                                        </th>
											
	                                        <th width="10%" class="title-description" id="th_cn">
	                                            <?php _e( 'Country', 'myworks-sync-for-xero' );?>
	                                        </th>
											
	                                        <th width="10%" class="title-description" id="th_st">
	                                            <?php _e( 'State', 'myworks-sync-for-xero' );?>
	                                        </th>
											
	                                        <th width="10%" class="title-description" id="th_rt">
	                                            <?php _e( 'Rate', 'myworks-sync-for-xero' );?>
	                                        </th>
											
	                                        <th width="20%" class="title-description" id="th_qt">
	                                            <?php _e( 'Xero Tax', 'myworks-sync-for-xero' );?>
	                                        </th>
										</tr>
									</thead>
									
									<tbody>
									<?php if(!empty($wc_tax_rates)):?>
									<?php foreach($wc_tax_rates as $rates):?>
										<tr>
											<td><?php echo (int) $rates['tax_rate_id'];?></td>
											<td><?php echo $MWXS_L->escape($rates['tax_rate_name']);?></td>
											<td>
												<?php
													$tax_rate_class = ($rates['tax_rate_class']=='')?'Standard rate':ucfirst(str_replace('-',' ',$rates['tax_rate_class']));
													echo $MWXS_L->escape($tax_rate_class);
												?>
											</td>
											<td><?php echo $MWXS_L->escape($rates['location_code']);?></td>
											<td><?php echo $MWXS_L->escape($rates['tax_rate_country']);?></td>
											<td><?php echo $MWXS_L->escape($rates['tax_rate_state']);?></td>
											<td><?php echo $MWXS_L->escape($rates['tax_rate']);?></td>
											<td>											
												<select class="mw_wc_qbo_sync_select2 qbo_select" name="wtax_<?php echo esc_attr($rates['tax_rate_id']);?>" id="wtax_<?php echo esc_attr($rates['tax_rate_id']);?>">
													<option value=""></option>													
													<?php $MWXS_L->only_option('',$xta);?>
												</select>							
											</td>
										</tr>
										
										<tr class="crs_tr" <?php if($sh_aps_sec!='show'){echo 'style="display:none;"';}?> id="sc_tx_row_<?php echo esc_attr($rates['tax_rate_id']);?>">
											<td><?php echo $MWXS_L->escape($rates['tax_rate_name']);?>&nbsp;+&nbsp;</td>
											<td>										
											<select class="qbo_select mw_wc_qbo_sync_select2 sc_sel_tx" name="sc_wtax_<?php echo esc_attr($rates['tax_rate_id']);?>" id="sc_wtax_<?php echo esc_attr($rates['tax_rate_id']);?>">
												<?php $this->dlobj->get_wc_tax_rate_dropdown($wc_tax_rates_a,'',$rates['tax_rate_id']);?>
											</select>
											</td>
											
											<td id="tl_tax_rate_class_<?php echo esc_attr($rates['tax_rate_id']);?>"></td>
											<td id="tl_city_<?php echo esc_attr($rates['tax_rate_id']);?>"></td>
											<td id="tl_country_<?php echo esc_attr($rates['tax_rate_id']);?>"></td>
											<td id="tl_state_<?php echo esc_attr($rates['tax_rate_id']);?>"></td>
											<td id="tl_taxrate_<?php echo esc_attr($rates['tax_rate_id']);?>"></td>
											<td>
												<select class="qbo_select mw_wc_qbo_sync_select2" name="cobmbo_wtax_<?php echo esc_attr($rates['tax_rate_id']);?>" id="cobmbo_wtax_<?php echo esc_attr($rates['tax_rate_id']);?>">
													<option value=""></option>													
													<?php $MWXS_L->only_option('',$xta);?>
												</select>
											</td>
										</tr>
									
									<?php endforeach;?>
									<?php else:?>
										<tr>
											<td colspan="8">
												<span class="mwxs_tnd">
													<?php _e( 'No taxes found.', 'myworks-sync-for-xero' );?>
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
						<?php wp_nonce_field( 'myworks_wc_xero_sync_map_wc_xero_tax', 'map_wc_xero_tax' ); ?>
						<input type="hidden" name="sh_aps_sec" id="sh_aps_sec" value="">
						<div class="input-field col s12 m6 l4">
							<button class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php _e( 'Save', 'myworks-sync-for-xero' );?>
							</button>
						</div>
					</div>
					<?php endif;?>
				</form>
			</div>
		</div>
	</div>
</div>

<?php if($total_records > 0):?>

<script type="text/javascript">
	function search_item(){
		let tax_map_search = jQuery('#tax_map_search').val();
		tax_map_search = jQuery.trim(tax_map_search);
		if(tax_map_search!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&tax_map_search='+tax_map_search;
		}else{
			alert('<?php echo __('Please enter search keyword.','myworks-sync-for-xero')?>');
		}
	}

	function reset_item(){		
		window.location = '<?php echo esc_url_raw($page_url);?>&tax_map_search=';
	}
	
	jQuery(document).ready(function($){
		$('.sc_sel_tx').change(function(){			
			var p_tx = $(this).attr('id');
			p_tx = p_tx.replace('sc_wtax_','');
			
			var tx_val = $('option:selected', this).val();
					
			if(tx_val!=''){				
				var tax_rate_class = $('option:selected', this).attr('data-tax_rate_class');
				if(!tax_rate_class.trim()){
					tax_rate_class = 'Standard rate';
				}
				
				var tx_city = $('option:selected', this).attr('data-tax_rate_city');
				var tx_country = $('option:selected', this).attr('data-tax_rate_country');
				var tx_state = $('option:selected', this).attr('data-tax_rate_state');
				var tx_taxrate = $('option:selected', this).attr('data-tax_rate');
				
				$('#tl_tax_rate_class_'+p_tx).html(tax_rate_class);
				$('#tl_city_'+p_tx).html(tx_city);
				$('#tl_country_'+p_tx).html(tx_country);
				$('#tl_state_'+p_tx).html(tx_state);
				$('#tl_taxrate_'+p_tx).html(tx_taxrate);
			}else{
				$('#tl_tax_rate_class_'+p_tx).html('');
				$('#tl_city_'+p_tx).html('');
				$('#tl_country_'+p_tx).html('');
				$('#tl_state_'+p_tx).html('');
				$('#tl_taxrate_'+p_tx).html('');
			}
		});		
		
		<?php 
			if(is_array($s_o_s_arr) && !empty($s_o_s_arr)){
				foreach($s_o_s_arr as $k => $v){
					if($MWXS_L->start_with($k,'#tl_')){
						echo '$(\''.$MWXS_L->escape($k).'\').html(\''.$MWXS_L->escape($v).'\');';
					}else{
						echo '$(\''.$MWXS_L->escape($k).'\').val(\''.$MWXS_L->escape($v).'\');';
					}				
				}
			}
		?>
		
		$('.sh_compound_tx').click(function(){
			var crs = $(this).text();			
			if(crs=='Show Compound Taxes'){
				$('#sh_aps_sec').val('show');
				$(this).addClass('hide_advanced_payment_sync').removeClass('show_advanced_payment_sync');
				
				$('#th_id').attr('width','20%');$('#th_tn').attr('width','19%');$('#th_tc').attr('width','10%');$('#th_ct').attr('width','10%');
				$('#th_cn').attr('width','7%');$('#th_st').attr('width','7%');$('#th_rt').attr('width','7%');$('#th_qt').attr('width','20%');
				
				$('.crs_tr').show();			
				$(this).text('Hide Compound Taxes');	
			}
			
			if(crs=='Hide Compound Taxes'){
				$('#sh_aps_sec').val('hide');
				$(this).addClass('show_advanced_payment_sync').removeClass('hide_advanced_payment_sync');
				
				$('#th_id').attr('width','5%');$('#th_tn').attr('width','25%');$('#th_tc').attr('width','10%');$('#th_ct').attr('width','10%');
				$('#th_cn').attr('width','10%');$('#th_st').attr('width','10%');$('#th_rt').attr('width','10%');$('#th_qt').attr('width','20%');
				
				$('.crs_tr').hide();				
				$(this).text('Show Compound Taxes');
			}
		});
	});
</script>
<?php myworks_woo_sync_for_xero_get_select2_js('.qbo_select');?>
<?php endif;?>