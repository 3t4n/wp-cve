<?php
if ( ! defined( 'ABSPATH' ) )
exit;

# License
function myworks_wc_xero_sync_check_license(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_check_license', 'check_plugin_license' ) ) {
		// process form data
		global $MWXS_L;
		
		$mw_wc_xero_sync_localkey = get_option('mw_wc_xero_localkey','');
		$mw_wc_xero_sync_localkey = $MWXS_L->sanitize($mw_wc_xero_sync_localkey);
		
		$mw_wc_xero_sync_license =  $MWXS_L->var_p('mw_wc_xero_license');	
		
		if($mw_wc_xero_sync_license!=$MWXS_L->get_option('mw_wc_xero_license')){
			#$MWXS_L->initialize_session();
			#$MWXS_L->set_session_val('new_license_check',1);
		}		
		
		if($MWXS_L->is_valid_license($mw_wc_xero_sync_license,$mw_wc_xero_sync_localkey,true)){
			echo 'License Activated';
		}else{
			echo 'Invalid License key';
		}		
	}
	wp_die();
}

# Dashboard Graph
function myworks_wc_xero_sync_refresh_log_chart(){
	global $MWXS_L;
	$vp = $MWXS_L->var_p('period');
	
	$MWXS_L->initialize_session();
	$MWXS_L->set_session_val('dashboard_graph_period',$vp);
	require_once('admin-page-hcj-functions.php');
	myworks_woo_sync_for_xero_get_log_chart_output($vp);	
	wp_die();
}

# Connection Key
function myworks_wc_xero_sync_save_xero_c_key(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_save_xero_c_key', 'save_xero_c_key' ) ) {
		global $MWXS_L;		
		$f_xc_key = $MWXS_L->var_p('f_xc_key');
		
		if(!empty($f_xc_key)){			
			if(strlen($f_xc_key) == '35' && $MWXS_L->validate_connection_key($f_xc_key)){
				$MWXS_L->update_option('mw_wc_xero_f_xc_key',$f_xc_key);
				echo '<br><span style="color:green;">Connection key saved</span>';
			}			
		}
	}
	wp_die();
}

# Quick Refresh
function myworks_wc_xero_sync_quick_refresh_cp(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_quick_refresh_cp', 'quick_refresh_cp' ) ) {
		global $MWXS_L;
		
		if(!$MWXS_L->is_xero_connected()){$MWXS_L->xero_connect();}
		
		if($MWXS_L->is_xero_connected()){
			$tci = (int) $MWXS_L->xero_refresh_customers();
			$tpi = (int) $MWXS_L->xero_refresh_products();
			
			# Clear Invalid Mappings
			if($tci > 0){
				$MWXS_L->clear_customer_invalid_mappings();
			}
			
			if($tpi > 0){
				$MWXS_L->clear_product_invalid_mappings();
				$MWXS_L->clear_variation_invalid_mappings();
			}			
			
			echo '<br>Total Customer Imported: <b>'.$MWXS_L->escape($tci).'</b><br>';
			echo 'Total Product Imported: <b>'.$MWXS_L->escape($tpi).'</b>';			
			
		}else{
			echo '<br><span style="color:red;">Xero connection problem</span>';
		}
	}
	wp_die();
}

function myworks_wc_xero_sync_quick_refresh_customers(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_quick_refresh_customers', 'quick_refresh_customers' ) ) {
		global $MWXS_L;
		
		if(!$MWXS_L->is_xero_connected()){$MWXS_L->xero_connect();}
		
		if($MWXS_L->is_xero_connected()){
			$tci = (int) $MWXS_L->xero_refresh_customers();
			
			# Clear Invalid Mappings
			if($tci > 0){
				$MWXS_L->clear_customer_invalid_mappings();
			}
			
			echo 'Total Customer Imported: <b>'.$MWXS_L->escape($tci).'</b>';
			
		}else{
			echo '<font style="color:red;">Xero connection problem</font>';
		}
	}
	wp_die();
}

function myworks_wc_xero_sync_quick_refresh_products(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_quick_refresh_products', 'quick_refresh_products' ) ) {
		global $MWXS_L;
		
		if(!$MWXS_L->is_xero_connected()){$MWXS_L->xero_connect();}
		
		if($MWXS_L->is_xero_connected()){			
			$tpi = (int) $MWXS_L->xero_refresh_products();
			
			# Clear Invalid Mappings
			if($tpi > 0){
				$MWXS_L->clear_product_invalid_mappings();
				$MWXS_L->clear_variation_invalid_mappings();
			}
			
			echo 'Total Product Imported: <b>'.$MWXS_L->escape($tpi).'</b>';
			
		}else{
			echo '<font style="color:red;">Xero connection problem</font>';
		}
	}
	wp_die();
}

# Clear Mappings
function myworks_wc_xero_sync_clear_all_mappings(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_all_mappings', 'clear_all_mappings' ) ) {
		global $MWXS_L;
		global $wpdb;		
		
		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_customers')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_customers')."` ");
		
		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_products')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_products')."` ");
		
		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_products')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_products')."` ");
		
		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_payment_method')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_payment_method')."` ");
		
		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_tax')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_tax')."` ");

		$wpdb->query("DELETE FROM `".$MWXS_L->gdtn('map_multiple')."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$MWXS_L->gdtn('map_multiple')."` ");
		
		echo 'Success';
	}
	wp_die();
}

function myworks_wc_xero_sync_clear_customer_mappings(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_customer_mappings', 'clear_customer_mappings' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('map_customers');
		
		$wpdb->query("DELETE FROM `".$table."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$table."` ");
		echo 'Success';
	}
	wp_die();
}

function myworks_wc_xero_sync_clear_product_mappings(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_product_mappings', 'clear_product_mappings' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('map_products');
		
		$wpdb->query("DELETE FROM `".$table."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$table."` ");

		# Account Map
		$table = $MWXS_L->gdtn('map_multiple');
		$wpdb->query("DELETE FROM `".$table."` WHERE `wc_type` = 'product' AND `x_type` = 'account' ");
		
		echo 'Success';
	}
	wp_die();
}

function myworks_wc_xero_sync_clear_variation_mappings(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_variation_mappings', 'clear_variation_mappings' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('map_variations');
		
		$wpdb->query("DELETE FROM `".$table."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$table."` ");
		
		# Account Map
		$table = $MWXS_L->gdtn('map_multiple');
		$wpdb->query("DELETE FROM `".$table."` WHERE `wc_type` = 'variation' AND `x_type` = 'account' ");

		echo 'Success';
	}
	wp_die();
}

# Clear Logs
function myworks_wc_xero_sync_clear_all_logs(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_all_logs', 'clear_all_logs' ) ) {
		global $MWXS_L;
		global $wpdb;

		$table = $MWXS_L->gdtn('log');
		$wpdb->query("DELETE FROM `".$table."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$table."` ");
		echo 'Success';
	}	
	wp_die();
}

function myworks_wc_xero_sync_clear_all_log_errors(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_all_log_errors', 'clear_all_log_errors' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('log');
		
		$wpdb->query("DELETE FROM `".$table."` WHERE `status` = 0 ");
		echo 'Success';
	}	
	wp_die();
}

# Clear Queue
function myworks_wc_xero_sync_clear_all_pending_queues(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_all_pending_queues', 'clear_all_pending_queues' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('queue');
		$wpdb->query("DELETE FROM `".$table."` WHERE `run` = 0 ");
		echo 'Success';
	}	
	wp_die();
}

function myworks_wc_xero_sync_clear_all_queues(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_clear_all_queues', 'clear_all_queues' ) ) {
		global $MWXS_L;
		global $wpdb;
		
		$table = $MWXS_L->gdtn('queue');
		
		$wpdb->query("DELETE FROM `".$table."` WHERE `id` > 0 ");
		$wpdb->query("TRUNCATE TABLE `".$table."` ");
		echo 'Success';
	}	
	wp_die();
}

# Auto Map
function myworks_wc_xero_sync_automap_customers_wf_xf(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_automap_customers_wf_xf', 'automap_customers_wf_xf' ) ) {
		global $MWXS_L;
		
		$cam_wf = $MWXS_L->var_p('cam_wf');		
		$cam_qf = $MWXS_L->var_p('cam_qf');		
		
		$mo_um = false;
		if(isset($_POST['mo_um']) && $_POST['mo_um'] == 'true'){
			$mo_um = true;
		}
		
		$map_count = (int) $MWXS_L->AutoMapCustomers($cam_wf,$cam_qf,$mo_um);
		
		echo 'Total Customer Mapped: '.$MWXS_L->escape($map_count);
	}
	wp_die();
}

function myworks_wc_xero_sync_automap_products_wf_xf(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_automap_products_wf_xf', 'automap_products_wf_xf' ) ) {
		global $MWXS_L;		
		
		$pam_wf = $MWXS_L->var_p('pam_wf');		
		$pam_qf = $MWXS_L->var_p('pam_qf');
		
		$mo_um = false;
		if(isset($_POST['mo_um']) && $_POST['mo_um'] == 'true'){
			$mo_um = true;
		}
		
		$map_count = (int) $MWXS_L->AutoMapProducts($pam_wf,$pam_qf,$mo_um);
		
		echo 'Total Product Mapped: '.$MWXS_L->escape($map_count);
	}	
	wp_die();
}

function myworks_wc_xero_sync_automap_variations_wf_xf(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_automap_variations_wf_xf', 'automap_variations_wf_xf' ) ) {
		global $MWXS_L;		
		
		$vam_wf = $MWXS_L->var_p('vam_wf');		
		$vam_qf = $MWXS_L->var_p('vam_qf');
		
		$mo_um = false;
		if(isset($_POST['mo_um']) && $_POST['mo_um'] == 'true'){
			$mo_um = true;
		}
		
		$map_count = (int) $MWXS_L->AutoMapVariations($vam_wf,$vam_qf,$mo_um);
		
		echo 'Total Variation Mapped: '.$MWXS_L->escape($map_count);
	}	
	wp_die();
}

function myworks_wc_xero_sync_window(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_window', 'window_xero_sync' ) ) {
		global $MWXS_L;
		global $MWXS_A;
		
		$sync_type = $MWXS_L->var_p('sync_type');
		$item_type = $MWXS_L->var_p('item_type');
		if($sync_type == 'pull'){
			$id = $MWXS_L->var_p('id');
		}else{
			$id = (int) $MWXS_L->var_p('id');
		}
		
		$cur_item = (int) $MWXS_L->var_p('cur_item');
		$tot_item = (int ) $MWXS_L->var_p('tot_item');
		
		$check_sync_valid = true;
		
		if($sync_type!='push' && $sync_type!='pull'){
			$check_sync_valid = false;
		}
		
		if($item_type!='customer' && $item_type!='order' && $item_type!='product' && $item_type!='variation'){
			$check_sync_valid = false;
		}
		
		if(($sync_type != 'pull' && $id < 1) || ($sync_type == 'pull' && empty($id)) || !$cur_item || !$tot_item){
			$check_sync_valid = false;
		}
		
		if($check_sync_valid){
			try{
				$key =  $cur_item;		  
				$per = $key/$tot_item*100;
				$per = ceil($per);			
				$msg = "<span class='error_red'>Something went wrong.</span>";
				
				if($sync_type=='push'){
					if($item_type=='customer'){				
						$r = $MWXS_A->hook_user_register(array('user_id'=>$id,'f_p_p'=>true));
						if($r){
							$msg = "<span class='success_green'>Customer #{$MWXS_L->escape($id)} has been pushed into Xero</span>";
						}else{
							$msg = "<span class='error_red'>There was an error pushing customer #{$MWXS_L->escape($id)} , Check MyWorks Sync > Log for additional details.</span>";
						}						
					}
					
					if($item_type=='product'){
						#Will add array return f later
						$r = $MWXS_A->hook_product_add(array('product_id'=>$id,'f_p_p'=>true));
						if($r){
							$msg = "<span class='success_green'>Product #{$MWXS_L->escape($id)} has been pushed into Xero</span>";
						}else{
							$msg = "<span class='error_red'>There was an error pushing product #{$MWXS_L->escape($id)} , Check MyWorks Sync > Log for additional details.</span>";
						}						
					}
					
					if($item_type=='variation'){
						$r = $MWXS_A->hook_variation_add(array('variation_id'=>$id,'f_p_p'=>true));
						if($r){
							$msg = "<span class='success_green'>Variation #{$MWXS_L->escape($id)} has been pushed into Xero</span>";
						}else{
							$msg = "<span class='error_red'>There was an error pushing variation #{$MWXS_L->escape($id)} , Check MyWorks Sync > Log for additional details.</span>";
						}						
					}
					
					if($item_type=='order'){
						$r = $MWXS_A->hook_order_add(array('order_id'=>$id,'f_p_p'=>true));
						if($r){
							$msg = "<span class='success_green'>Order #{$MWXS_L->escape($id)} has been pushed into Xero</span>";
						}else{
							$msg = "<span class='error_red'>There was an error pushing order #{$MWXS_L->escape($id)} , Check MyWorks Sync > Log for additional details.</span>";
						}						
					}
				}
				
				if($sync_type=='pull'){
					$MWXS_L->xero_connect();
					if($item_type=='product'){						
						$r = $MWXS_L->X_Pull_Product_By_Id($id);
						if($r){
							$msg = "<span class='success_green'>Product #{$MWXS_L->escape($id)} has been pulled into WooCommerce</span>";
						}else{
							$msg = "<span class='error_red'>There was an error pulling product #{$MWXS_L->escape($id)} , Check MyWorks Sync > Log for additional details.</span>";
						}
					}
				}
				
				$MWXS_L->show_sync_window_message($key, $msg , $per, $tot_item);
				
			}catch (Exception $e) {
				$Exception = $e->getMessage();
			}
		}
	}
	wp_die();
}

function myworks_wc_xero_sync_order_sync_status_list(){
	if ( ! empty( $_POST ) && check_admin_referer( 'myworks_wc_xero_sync_order_sync_status_list', 'order_sync_status_list' ) ) {
		global $MWXS_L;		
		$order_id_num_arr = $MWXS_L->var_p('order_id_num_arr');
		if(is_array($order_id_num_arr) && !empty($order_id_num_arr)){
			$MWXS_L->xero_connect();
			$order_id_num_arr = $MWXS_L->get_order_sync_status_list($order_id_num_arr);

			if(empty($order_id_num_arr)){
				$order_id_num_arr = array_fill_keys(array_keys($order_id_num_arr), null);
			}			
		}
		
		#$MWXS_L->_p($order_id_num_arr);		
		echo json_encode($order_id_num_arr);
	}
	wp_die();
}