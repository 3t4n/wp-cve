<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
global $MWXS_A;

# For Debug
$is_debug = false;
$is_xc_required = true;
$exit_after_debug = false;

if(isset($_GET['debug']) && $_GET['debug'] == 1){
	$is_debug = true;
	$xc = (isset($_GET['xc']) && $_GET['xc'] == 1)?true:false;
	if(!$xc){
		$is_xc_required = false;
	}
	
	if(isset($_GET['exit']) && $_GET['exit'] == 1){
		$exit_after_debug = true;
	}
}

if($is_xc_required){
	$MWXS_L->xero_connect();
}

if($is_debug){
	$MWXS_L->debug();

	#->Customer
	#$debug_data = $MWXS_L->get_wc_customer_info(2);
	#$MWXS_L->_p($debug_data);
	
	#->Order
	#$debug_data = $MWXS_L->get_wc_customer_info_from_order(22);
	#$MWXS_L->_p($debug_data);
	#$debug_data = $MWXS_L->get_wc_order_details_from_order(14);
	#$MWXS_L->_p($debug_data);
	#echo $MWXS_L->get_xero_customer_for_order_sync($debug_data);
	#$MWXS_L->X_Add_Invoice($debug_data);
	#$MWXS_A->hook_order_add(array('order_id'=>85,'f_p_p'=>true));
	#$MWXS_A->hook_order_add(137);
	#$MWXS_L->_p($MWXS_L->check_xero_invoice_get_obj(array('wc_inv_id'=>82,'wc_inv_num'=>'')));
	#$MWXS_L->_p($MWXS_L->get_xero_order_sync_as(14));
	
	#->Payment
	#$MWXS_A->hook_payment_add(array('order_id'=>97,'f_p_p'=>true));
	
	#->Product
	#$debug_data = $MWXS_L->get_wc_product_info(26);
	#$MWXS_L->_p($debug_data);
	#$MWXS_L->X_Add_Product($debug_data);
	#$MWXS_A->hook_product_add(array('product_id'=>26,'f_p_p'=>true));

	#->Variation
	#$debug_data = $MWXS_L->get_wc_variation_info(61);
	#$MWXS_L->_p($debug_data);
	#$MWXS_A->hook_variation_add(array('variation_id'=>61,'f_p_p'=>true));
	
	#->Log
	#$MWXS_L->add_text_into_log_file('Test');
	
	#->Queue
	#$x_id = 'g6734e2d-2c2c-1111-980b-90c49ff68890';
	#$w_id = 1;
	#$MWXS_L->wx_queue_add('Product',$w_id,'Push');
	#$MWXS_L->wx_queue_add('Product',$x_id,'Pull');	

	#->Others
	#$MWXS_L->_p($MWXS_L->db_check_get_fields_details());
	#$terms = get_the_terms ( 91, 'product_cat' );
	#$MWXS_L->_p($terms);
	
	if($exit_after_debug){
		exit();
	}
}

$dashboard_graph_period = $MWXS_L->get_session_val('dashboard_graph_period','month');

$dbsd = $this->dlobj->get_dashboard_status_data();

$wc_p_methods = $this->dlobj->get_wc_active_payment_gateways();
$wc_apg_count = (is_array($wc_p_methods))?count($wc_p_methods):0;
?>

<div class="qcpp_cnt" title="Version: <?php echo esc_attr(MW_WC_XERO_SYNC_PLUGIN_VERSION);?>">
	<img width="300"  alt="WooCommerce Sync for Xero - by MyWorks Software" src="<?php echo esc_url(plugins_url( MW_WC_XERO_SYNC_PLUGIN_NAME.'/admin/image/mwd-logo.png' )) ?>" class="mw-qbo-sync-logo">
</div>

<div id="mw_wc_qbo_sync_grph_div" style="background:white;">
	<div class="page_title">
		<div class="dashboard_main_buttons">
			<?php wp_nonce_field( 'myworks_wc_xero_sync_clear_all_mappings', 'clear_all_mappings' ); ?>
			<button title="<?php _e( 'Clear all data from map tables', 'myworks-sync-for-xero' );?>" id="mwqs_clear_all_mappings"><?php _e( 'Clear All Mappings', 'myworks-sync-for-xero' );?>
			</button>
			&nbsp;
			
			<a id="mwqs_refresh_data_from_qbo" href="#">
				<button title="<?php _e( 'Refresh your sync to recognize the latest customers and products currently in Xero.', 'myworks-sync-for-xero' );?>">
					<?php _e( 'Refresh Customers & Products', 'myworks-sync-for-xero' );?>
				</button>
			</a>
			<div id="mwqs_dashboard_ajax_loader"></div>
			<input type="hidden" id="mwxs_qr_cp_tf" value="0">
			<?php 
				wp_nonce_field( 'myworks_wc_xero_sync_quick_refresh_cp', 'quick_refresh_cp' );
			?>
		</div>
	</div>
	
	<!--Graph / Chart-->
	<div id="mw_wc_qbo_sync_grph_div_new">
		<?php myworks_woo_sync_for_xero_get_log_chart_output($dashboard_graph_period);?>
	</div>
</div>

<div class="dash-bottm mwqs_db_status_cont">
	<div class="col-sm3 module-stat">
		<h3><?php _e( 'Sync Status', 'myworks-sync-for-xero' );?></h3>
		<ul>
			<li>
				<a <?php if(!$MWXS_L->is_xero_connected()){echo 'class="dbst_err"';}?>>
					<?php _e( 'Xero Connection', 'myworks-sync-for-xero' );?>
				</a>
			</li>
			
			<li>
				<a <?php if(!$dbsd['xero_initial_data_loaded']){echo 'class="dbst_err"';}?> title="Customers / Products">
					<?php _e( 'Initial Xero Data Loaded', 'myworks-sync-for-xero' );?>
				</a>
			</li>
			
			<li>
				<a <?php if(!$dbsd['default_settings_saved']){echo 'class="dbst_err"';}?>>
					<?php _e( 'Default Settings Saved', 'myworks-sync-for-xero' );?>
				</a>
			</li>
			
			<li>
				<a <?php if(!$dbsd['basic_mapping_done']){echo 'class="dbst_err"';}?>>
					<?php _e( 'Mapping Active', 'myworks-sync-for-xero' );?>
				</a>
			</li>
		</ul>
	</div>
	
	<div class="col-sm3 mapping-stat map-sta-a">
		<h3><?php _e( 'Mapping Status', 'myworks-sync-for-xero' );?></h3>
		<ul>
			<li>
				<a>
					<b><?php _e( 'Customers Mapped', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['customer_mapped']);?></span>
				</a>
			</li>
			
			<li>
				<a>
					<b><?php _e( 'Products Mapped', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['product_mapped']);?></span>
				</a>
			</li>
			
			<li>
				<a>
					<b><?php _e( 'Variations Mapped', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['variation_mapped']);?></span>
				</a>
			</li>
			
			<li>
				<a>
					<b><?php _e( 'Gateways Mapped', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['payment_gateways_mapped']);?></span>
				</a>
			</li>
		</ul>
	</div>
	
	<div class="col-sm3 mapping-stat sync-a">
		<h3><?php _e( 'WooCommerce Status', 'myworks-sync-for-xero' );?></h3>
		<ul>         	
			<li>
				<a>
					<b><?php _e( 'Customers', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['wc_total_customers']);?></span>
				</a>
			</li>
			
			<li>
				<a>
					<b><?php _e( 'Products', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['wc_total_products']);?></span>
				</a>
			</li>
			
			<li>
				<a>
					<b><?php _e( 'Variations', 'myworks-sync-for-xero' );?></b>
				<span class="right-btnn"><?php echo $MWXS_L->escape($dbsd['wc_total_variations']);?></span>
				</a>
			</li>

			<li>
				<a>
					<b><?php _e( 'Active Gateways', 'myworks-sync-for-xero' );?></b>
					<span class="right-btnn"><?php echo $MWXS_L->escape($wc_apg_count);?></span>
				</a>
			</li>
		</ul>
	</div>
</div>

<?php
	$is_dev = (isset($_GET['dev']) && $_GET['dev'] == 1)?true:false;
	if($is_debug && $is_dev):
	$lf = 'dev.log';
	$log_file_path = MW_WC_XERO_SYNC_P_DIR_P.'log'.DIRECTORY_SEPARATOR.$lf;

	if(file_exists($log_file_path)):
	$lf_r = @fopen($log_file_path, "r") or die("Unable to open plugin dev log file");
	$lf_s = filesize($log_file_path);
	$lf_s = (!$lf_s)?1:$lf_s;
	$lf_content = @fread($lf_r,$lf_s);	
?>

<div style="margin:20px 20px 0px 0px;">
	<h5>Debug Log File (Developer)</h5>
	<textarea readonly="true" style="height:400px;background:white;width:100%;"><?php echo esc_textarea($lf_content);?></textarea>
</div>

<?php
	fclose($lf_r);
	endif;
	endif;
?>

<!--Script-->
<script>
	function mw_wc_qbo_sync_refresh_log_chart(period){
		var data = {
			"action": 'myworks_wc_xero_sync_refresh_log_chart',
			"period": period,
		};
		
		jQuery('#mw_wc_qbo_sync_grph_div_new').css('opacity',0.6);
		jQuery.ajax({
		   type: "POST",
		   url: ajaxurl,
		   data: data,
		   cache:  false ,
		   //datatype: "json",
		   success: function(result){
			   if(result!=0 && result!=''){
				jQuery('#mw_wc_qbo_sync_grph_div_new').html(result);
			   }else{
				 alert('Error!');			 
			   }
			   jQuery('#mw_wc_qbo_sync_grph_div_new').css('opacity',1);
		   },
		   error: function(result) {  
				alert('Error!');
				jQuery('#mw_wc_qbo_sync_grph_div_new').css('opacity',1);
		   }
		});
	}
	
	jQuery(document).ready(function($){
	<?php if($MWXS_L->is_xero_connected()):?>
		$('#mwqs_refresh_data_from_qbo').click(function(e){
			e.preventDefault();
			if($('#mwxs_qr_cp_tf').val() == 1){
				alert('Process already running.');
				return false;
			}
			
			if(!confirm('<?php echo __('This will update the data in our sync with the latest Customers & Products in your Xero company. No data will be synced at this time.','myworks-sync-for-xero')?>')){
				return false;
			}
			
			$('#mwxs_qr_cp_tf').val(1);
			$('#mwqs_dashboard_ajax_loader').html('<br>Importing Customers & Products from Xero...');
			
			let data = {
				"action": 'myworks_wc_xero_sync_quick_refresh_cp',
				"quick_refresh_cp": $('#quick_refresh_cp').val()				
			};
			
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				cache:  false ,
				//datatype: "json",
				success: function(r){
					$('#mwqs_dashboard_ajax_loader').html(r);
					//location.reload();
				},
				error: function(r) {
					$('#mwqs_dashboard_ajax_loader').html('<br><span style="color:red;">Something went wrong</span>');
				}
			});
			
			$('#mwxs_qr_cp_tf').val(0);
		});
	<?php endif;?>
		
		$('#mwqs_clear_all_mappings').click(function(){
			if(confirm('<?php echo __('Are you sure you want to clear your mappings?','myworks-sync-for-xero')?>')){
				var loading_msg = '<br>Loading...';
				jQuery('#mwqs_dashboard_ajax_loader').html(loading_msg);
				
				var data = {
					"action": 'myworks_wc_xero_sync_clear_all_mappings',
					"clear_all_mappings": jQuery('#clear_all_mappings').val(),
				};
				
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: data,
					cache:  false ,			  
					success: function(result){
						if(result!=0 && result!=''){
							jQuery('#mwqs_dashboard_ajax_loader').html('<br>Success!');
							location.reload();
						}else{					
							jQuery('#mwqs_dashboard_ajax_loader').html('<br>Error!');
						}				  
					},
					error: function(result) {
						jQuery('#mwqs_dashboard_ajax_loader').html('<br>Error!');
					}
				});
			}
		});
	});
</script>