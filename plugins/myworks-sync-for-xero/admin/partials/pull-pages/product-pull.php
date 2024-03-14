<?php
if(!defined( 'ABSPATH' )){
	exit;
}

$page_url = $PP.'product';

$MWXS_L->xero_connect();

$xero_item_list = $MWXS_L->X_Get_Items();
#$MWXS_L->_p($xero_item_list);
$total_records = (is_array($xero_item_list))?count($xero_item_list):0;

$wc_currency_symbol = get_woocommerce_currency_symbol();

$is_update_enabled = $MWXS_L->is_update_enabled('product-pull');
require_once plugin_dir_path( __FILE__ ) . 'pull-nav.php';
?>

<?php echo str_repeat('<br>',2);?>
<div class="container">
	<div class="page_title"><h4><?php _e( 'Product Pull', 'myworks-sync-for-xero' );?></h4></div>
	<div class="card pull-block-responsive">
		<div class="card-content">
			<div class="col s12 m12 l12">
				<div class="panel panel-primary">
					<!--No Filter Section For Now-->
					<?php if($total_records > 0):?>
					<div class="row">
						<div class="input-field col s12 m12 14">
							<button id="pull_selected_product_btn" class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php echo __('Pull Selected Products','myworks-sync-for-xero')?>
							</button>										
						</div>
					</div>
					<br>
					<?php endif;?>

					<div class="table-m">
						<div class="myworks-wc-qbo-sync-table-responsive">
							<table class="table tablesorter" id="mwqs_product_pull_table">
								<thead>
									<tr>
										<th width="2%">
											<input type="checkbox" onclick="javascript:mw_xero_sync_check_all(this,'product_pull_')">
										</th>
										<th width="30%">Xero Product Name</th>
										<th width="20%">Code</th>
										<th width="8%">Price</th>
										<th width="10%">Type</th>
										<th width="5%">Qty</th>
										<th width="15%">Last Updated</th>
										<th width="10%">Sync Status</th>
									</tr>
								</thead>

								<tbody>
									<?php if(is_array($xero_item_list) && !empty($xero_item_list)):?>
									<?php foreach($xero_item_list as $Item):?>
									<?php 
										$mapped_data = $MWXS_L->if_xero_item_exists_in_woo(
											[
												'X_P_ID' => $Item->getItemId(),
												'Name' => $Item->getName(),
												'Code' => $Item->getCode(),
											],
										);

										$cd = (!$is_update_enabled && is_array($mapped_data) && !empty($mapped_data))?' disabled':'';
									?>
									<tr>
										<td><input type="checkbox" id="product_pull_<?php echo esc_attr($Item->getItemId())?>"<?php echo esc_attr($cd);?>></td>										
										<td><?php echo $MWXS_L->escape($Item->getName()) ?></td>
										<td><?php echo $MWXS_L->escape($Item->getCode()) ?></td>
										<td>
											<?php 
												echo $MWXS_L->escape($wc_currency_symbol);
												echo number_format(floatval($Item->getSalesDetails()->getUnitPrice()),2);
											?>											
										</td>
										<td><?php echo ($Item->getIsTrackedAsInventory())?'Inventory':'Non Inventory'; ?></td>
										<td><?php echo ($Item->getIsTrackedAsInventory())?(float) $Item->getQuantityOnHand():''; ?></td>
										<td><?php echo $MWXS_L->escape($MWXS_L->format_xero_date($Item->getUpdatedDateUTC()))?></td>
										
										<td>
											<?php 
												if(is_array($mapped_data) && !empty($mapped_data)){
													$wpv = (isset($mapped_data['is_variation']))?'Variation':'Product';
													if(isset($mapped_data['mapped'])){
														echo '<i title="Mapped to '.$MWXS_L->escape($wpv).' #'.$MWXS_L->escape($mapped_data['ID']).'" class="fa fa-check-circle" style="color:green"></i>';
													}else{
														echo '<i title="Mached (SKU / Name) to '.$MWXS_L->escape($wpv).' #'.$MWXS_L->escape($mapped_data['ID']).'" class="fa fa-check-circle" style="color:green"></i>';
													}
													
												}else{
													echo '<i class="fa fa-times-circle" style="color:red"></i>';
												}
											?>
										</td>
									</tr>
									<?php endforeach;?>
									<?php else:?>
									<tr>
										<td colspan="8">
											<span class="mwxs_tnd">
												<?php _e( 'No products found.', 'myworks-sync-for-xero' );?>
											</span>
										</td>
									</tr>
									<?php endif;?>									
								</tbody>
							</table>
						</div>
					</div>

					<?php if($total_records > 0):?>
					<!--Pagination-->
					<div class="mwqs_paginate_div mwqbd_pd">
						<div>Showing 1 to <?php echo $MWXS_L->escape($total_records)?> of <?php echo $MWXS_L->escape($total_records)?> entries</div>
					</div>
					<?php endif;?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php if($total_records > 0):?>
<?php myworks_woo_sync_for_xero_get_tablesorter_js();?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var item_type = 'product';
		$('#pull_selected_product_btn').click(function(){
			var item_ids = '';
			var item_checked = 0;
			
			jQuery( "input[id^='product_pull_']" ).each(function(){
				if(jQuery(this).is(":checked")){
					item_checked = 1;
					var only_id = jQuery(this).attr('id').replace('product_pull_','');
					only_id = jQuery.trim(only_id);

					if(only_id != ''){
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
			
			popUpWindow('<?php echo esc_url_raw($sync_window_url);?>&sync_type=pull&item_ids='+item_ids+'&item_type='+item_type,'mw_xs_product_pull',0,0,650,350);
			return false;
		});
	});
</script>
<?php endif;?>