<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $wpdb;
$page_url = $UP.'variation';

# Data Listing / Search
$MWXS_L->set_per_page_from_url();
$items_per_page = $MWXS_L->get_item_per_page();

$MWXS_L->set_and_get('variation_push_search');
$variation_push_search = $MWXS_L->get_session_val('variation_push_search');

$MWXS_L->set_and_get('variation_um_srch');
$variation_um_srch = $MWXS_L->get_session_val('variation_um_srch');

$total_records = $this->dlobj->count_wc_variations(false,$variation_push_search,'',$variation_um_srch);

$offset = $MWXS_L->get_offset($MWXS_L->get_page_var(),$items_per_page);

$Limit = ' '.$offset.' , '.$items_per_page;

$wc_variation_list = $this->dlobj->get_wc_variations(false,$Limit,false,$variation_push_search,'',$variation_um_srch);
#$MWXS_L->_p($wc_variation_list);

$wc_currency_symbol = get_woocommerce_currency_symbol();

$is_update_enabled = $MWXS_L->is_update_enabled('variation-push');
require_once plugin_dir_path( __FILE__ ) . 'push-nav.php';
?>
<style>	
	span.ss_pf_span{display:none;}
</style>

<div class="container">
	<div class="page_title"><h4><?php _e( 'Variation Push', 'myworks-sync-for-xero' );?></h4></div>
	<div class="card qo-push-responsive">
		<div class="card-content">
			<div class="col s12 m12 l12">
				<div class="panel panel-primary">
					<div class="mw_wc_filter">
						<span class="search_text"><?php _e( 'Search', 'myworks-sync-for-xero' );?></span>
						&nbsp;
						<input type="text" id="variation_push_search" placeholder="NAME / SKU / ID" value="<?php echo esc_attr($variation_push_search);?>">
						&nbsp;
						
						<span>
							<select title="Mapped/UnMapped" style="width:80px;" name="variation_um_srch" id="variation_um_srch">
							<?php if(empty($variation_um_srch)):?>
								<option value="">All</option>
							<?php endif;?>
							<?php $MWXS_L->only_option($variation_um_srch,array('only_um'=>'Only Unmapped','only_m'=>'Only Mapped'));?>
							</select>
						</span>
						
						<?php myworks_woo_sync_for_xero_filter_reset_show_entries_html($page_url,$items_per_page);?>
					</div>
					<br>
					
					<?php if($total_records > 0):?>
					<div class="row">
						<div class="input-field col s12 m12 14">
							<button id="push_selected_variation_btn" class="waves-effect waves-light btn save-btn mw-qbo-sync-green">
								<?php echo __('Push Selected Variations','myworks-sync-for-xero')?>
							</button>										
						</div>
					</div>
					<br>
					<?php endif;?>
					
					<div class="table-m">
						<div class="myworks-wc-qbo-sync-table-responsive">
							<table class="table tablesorter" id="mwqs_variation_push_table">
								<thead>
									<tr>
										<th width="2%">
											<input type="checkbox" onclick="javascript:mw_xero_sync_check_all(this,'variation_push_')">
										</th>
										
										<th width="5%">ID</th>
										<th width="24%">Variation Name</th>
										<th width="24%">Parent Product</th>
										<th width="10%">SKU</th>
										<th width="8%">Price</th>
										<th width="7%">Manage<br>Stock</th>
										<th width="6%">Stock</th>
										<th width="8%">Stock<br>Status</th>
										<th width="6%">Sync<br>Status</th>										
									</tr>									
								</thead>
								
								<tbody>
									<?php if(!empty($wc_variation_list)):?>
									<?php foreach($wc_variation_list as $data):?>
									<?php										
										$cd = (!$is_update_enabled && !empty($data['X_ItemID']))?' disabled':'';
									?>
									
									<tr>
										<td><input type="checkbox" id="variation_push_<?php echo esc_attr($data['ID'])?>"<?php echo esc_attr($cd);?>></td>
										<td><?php echo (int) $data['ID']?></td>
										
										<td>
											<?php 
												echo $MWXS_L->escape($MWXS_L->get_variation_name_from_id($data['name'],$data['parent_name'],$data['ID'],$data['parent_id']));
											?>
										</td>
										
										<td>
											<a title="<?php echo (int) $data['parent_id']?>" target="_blank" href="<?php echo esc_url_raw(admin_url('post.php?action=edit&post=').(int) $data['parent_id']) ?>">
												<?php echo $MWXS_L->escape($data['parent_name']);?>
											</a>
										</td>
										
										<td><?php echo $MWXS_L->escape($data['sku']);?></td>
										<td>
											<?php
												echo $MWXS_L->escape($wc_currency_symbol);
												echo (isset($data['price']))?floatval($data['price']):'0.00';
											?>
										</td>
										<td><?php echo $MWXS_L->escape($data['manage_stock']);?></td>
										<td><?php echo number_format(floatval($data['stock']),2);?></td>
										<td><?php echo $MWXS_L->escape($data['stock_status']);?></td>
										<td>
											<span class="ss_pf_span"><?php echo (!empty($data['X_ItemID']))?1:0;?></span>											
											<?php 
												if(!empty($data['X_ItemID'])){
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
										<td colspan="10">
											<span class="mwxs_tnd">
												<?php _e( 'No variations found.', 'myworks-sync-for-xero' );?>
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
<?php endif;?>

<script type="text/javascript">
	function search_item(){
		let variation_push_search = jQuery('#variation_push_search').val();
		variation_push_search = jQuery.trim(variation_push_search);
		
		let variation_um_srch = jQuery('#variation_um_srch').val();
		variation_um_srch = jQuery.trim(variation_um_srch);
		
		if(variation_push_search!='' || variation_um_srch!=''){
			window.location = '<?php echo esc_url_raw($page_url);?>&variation_push_search='+variation_push_search+'&variation_um_srch='+variation_um_srch;
		}else{
			alert('<?php echo __('Please enter or select search term.','myworks-sync-for-xero')?>');
		}
	}
	
	function reset_item(){
		window.location = '<?php echo esc_url_raw($page_url);?>&variation_push_search=&variation_um_srch=';
	}
	
	<?php if($total_records > 0):?>
	jQuery(document).ready(function($) {
		var item_type = 'variation';
		$('#push_selected_variation_btn').click(function(){
			var item_ids = '';
			var item_checked = 0;
			
			jQuery( "input[id^='variation_push_']" ).each(function(){
				if(jQuery(this).is(":checked")){
					item_checked = 1;
					var only_id = jQuery(this).attr('id').replace('variation_push_','');
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
			
			popUpWindow('<?php echo esc_url_raw($sync_window_url);?>&sync_type=push&item_ids='+item_ids+'&item_type='+item_type,'mw_xs_variation_push',0,0,650,350);
			return false;
		});
	});
	<?php endif;?>
</script>