<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://myworks.software/
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define custom plugin functions.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 * @author     MyWorks Software <support@myworks.software>
 */

# Xero Lib
require_once plugin_dir_path( __FILE__ ) . 'lib/xero-lib/vendor/autoload.php';
use XeroAPI\XeroPHP\AccountingObjectSerializer;

#Plugin Lib
require_once plugin_dir_path( __FILE__ ) . 'class-config.php';
require_once plugin_dir_path( __FILE__ ) . 'class-functions/class-core-functions.php';

class MyWorks_WC_Xero_Sync_Lib extends MyWorks_WC_Xero_Sync_Core {
	protected $plugin_license_url;
	protected $xero_c_dashboard_url;
	
	protected $plugin_license_status = '';
	protected $is_valid_license = false;
	private $license_data_for_conn_page_view;
	
	private $XeroTenantId;
	private $AccessToken;
	
	private $XAccountingApiInstance;
	
	private $is_xero_connected;
	private $XeroCompanyDetails;
	
	private $_disable_all_sync = false;

	private $_plugin_db_version = '1.0.2';
	
	public function __construct(){
		parent::__construct();
		
		#New
		$this->plugin_license_url = MWXS_C_Settings::Get_C_Setting('plugin_license_url');
		$this->xero_c_dashboard_url = MWXS_C_Settings::Get_C_Setting('xero_c_dashboard_url');
		
		#global $wpdb;
		#$wpdb->query('SET SQL_BIG_SELECTS=1');
		
		if(!$this->is_valid_license){
			$this->is_valid_license($this->get_option('mw_wc_xero_license'),$this->get_option('mw_wc_xero_localkey'));
		}
		
		$this->is_xero_connected = false;
	}
	
	public function debug(){		
		global $wpdb;
		
		if($this->is_xero_connected()){
			#$d = $this->get_xero_org_actions();			
			#$d = $this->xero_get_accounts_kva();
			
			#$this->_p($d);
			
		}		
	}
	
	# Init Hook Function
	public function init(){
		if($this->_plugin_db_version != $this->get_option('mw_wc_xero_plugin_db_version')){
			$this->alter_plugin_db_tables();
		}		
	}

	# Alter plugin DB tables
	private function alter_plugin_db_tables(){
		global $wpdb;
		$server_db = $this->db_check_get_fields_details();
		if(is_array($server_db) && count($server_db)){
			# Existing Tables Field Add / Update
			$is_db_updated = false;
			foreach($server_db as $k=>$v){
				# Payment Method Map
				if($k == $this->gdtn('map_payment_method')){
					if(!array_key_exists("aps_order_status",$v)){
						$sql = "ALTER TABLE `{$k}` ADD `aps_order_status` VARCHAR(255) NOT NULL AFTER `x_invoice_ddd`;";
						$wpdb->query($sql);
						$is_db_updated = true;
					}
				}
			}
			
			# New Tables
			$is_new_db_tbl_created = false;
			if(!isset($server_db[$this->gdtn('map_multiple')])){
				$table = $this->gdtn('map_multiple');
				$sql = "CREATE TABLE IF NOT EXISTS {$table} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`wc_type` varchar(255) NOT NULL,
					`wc_id` bigint(20) NOT NULL,
					`x_type` varchar(255) NOT NULL,
					`x_id` varchar(255) NOT NULL,	
					PRIMARY KEY (id)
				)";

				$wpdb->query($sql);
				$is_new_db_tbl_created = true;
			}

			if(!isset($server_db[$this->gdtn('map_categories')])){
				$table = $this->gdtn('map_categories');
				$sql = "CREATE TABLE IF NOT EXISTS {$table} (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`W_CAT_ID` bigint(20) NOT NULL,
					`X_P_ID` varchar(36) NOT NULL,
					`X_ACC_CODE` varchar(255) NOT NULL,
					PRIMARY KEY (id)
				)";

				$wpdb->query($sql);
				$is_new_db_tbl_created = true;
			}
		}

		$this->update_option('mw_wc_xero_plugin_db_version',$this->_plugin_db_version);
	}
	
	public function get_xcd_url(){
		return $this->xero_c_dashboard_url;
	}

	public function sync_enabled(){
		if(!$this->_disable_all_sync){
			return true;
		}
		
		return false;
	}
	
	/*Connection*/
	public function is_xero_connected(){
		#return true;
		return $this->is_xero_connected;
	}
	
	public function get_connected_xero_cd(){
		return $this->XeroCompanyDetails;
	}
	
	public function get_xero_org_actions(){
		$oaa = array();
		if($this->is_xero_connected()){
			$org_actions = $this->X_API_I()->getOrganisationActions($this->XeroTenantId);
			if(!empty($org_actions)){
				$org_actions = $org_actions->getActions();
				if(is_array($org_actions) && !empty($org_actions)){
					foreach($org_actions as $Action){
						$oaa[$Action->getName()] = $Action->getStatus();
					}
				}
			}			
		}
		
		return $oaa;
	}
	
	public function xero_connect(){
		if(!$this->is_xero_connected){
			$this->xero_gcc();
			if(!empty($this->AccessToken) && !empty($this->XeroTenantId)){
				$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken((string)$this->AccessToken);
				$this->XAccountingApiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
				  new GuzzleHttp\Client(),
				  $config
				);
				
				try{
					$xero_orgs_api_r = $this->XAccountingApiInstance->getOrganisations($this->XeroTenantId);
					#$this->_p($xero_orgs_api_r);
					if(!empty($xero_orgs_api_r)){
						$xero_orgs_api_r = $xero_orgs_api_r[0];
						$this->is_xero_connected = true;

						# One time setup on first connection
						$this->one_time_setup_afxc();

						$xccd = array(
							'Name' => $xero_orgs_api_r->getName(),
							'LegalName' => $xero_orgs_api_r->getLegalName(),
							'Email' => '',
							
							'OrganisationStatus' => $xero_orgs_api_r->getOrganisationStatus(),
							'OrganisationType' => $xero_orgs_api_r->getOrganisationType(),
							
							'CountryCode' => $xero_orgs_api_r->getCountryCode(),
							'BaseCurrency' => $xero_orgs_api_r->getBaseCurrency(),	
							
							'PaysTax' => $xero_orgs_api_r->getPaysTax(),
							'DefaultSalesTax' => $xero_orgs_api_r->getDefaultSalesTax(),
							'DefaultPurchasesTax' => $xero_orgs_api_r->getDefaultPurchasesTax(),
							
							'Edition' => $xero_orgs_api_r->getEdition(),
							'Class' => $xero_orgs_api_r->getClass(),

							'Timezone' => $xero_orgs_api_r->getTimezone(),
						);				
						
						$this->XeroCompanyDetails = $xccd;
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					#$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);
					#$e->getMessage();
				}
			}
		}		
	}
	
	public function X_API_I(){
		return $this->XAccountingApiInstance;
	}
	
	# Credentials
	public function get_xero_tenant_id(){
		return $this->XeroTenantId;
	}
	
	private function xero_gcc(){
		$xcc = array();
		$cc_url = $this->get_xcd_url().'/api/xero-connection-credentials';
		
		if(!empty($cc_url)){
			#$xck = $this->get_option('mw_wc_xero_f_xc_key');
			$xck = $this->get_option('mw_wc_xero_license');
			$site_url = $this->get_option('siteurl');
			
			if(!empty($xck) && !empty($site_url) && strlen($xck) == '35' && $this->validate_connection_key($xck)){				
				$r = wp_remote_post($cc_url,array(
					'method'      => 'POST',
					'body' => array(
						'xck' => $xck,
						'site_url' => $site_url,
					)
				));
				
				if(!is_wp_error($r)){
					#$this->_p($r);
					if($r['response']['code'] == '200' && !empty($r['body'])){
						$rb = json_decode($r['body'],true);
						if($rb['status'] == 'OK' && is_array($rb['data']) && isset($rb['data']['AccessToken']) && isset($rb['data']['XeroTenantId'])){
							$this->AccessToken = $rb['data']['AccessToken'];
							$this->XeroTenantId = $rb['data']['XeroTenantId'];
						}
					}
				}else{
					#echo $r->get_error_message();
				}
			}
		}		
		
		return $xcc;
	}
	
	# After first time connected to xero - only one time
	private function one_time_setup_afxc(){
		if($this->is_xero_connected()){
			if(!$this->option_checked('mw_wc_xero_ots_afxc_run')){
				# Import customers and products from xero
				$this->xero_refresh_customers();
				$this->xero_refresh_products();
				
				# Default Settings

				# Default for unmatched products
				if(empty($this->get_option('mw_wc_xero_sync_default_xero_product'))){
					$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Name','Default for Unmatched Products');

					if(empty($ItemID)){						
						#X_Add_Product
						$Item = new XeroAPI\XeroPHP\Models\Accounting\Item;
						$Item->setName('Default for Unmatched Products');
						$Item->setCode('Default');
						#$Item->setDescription('');
						#$Item->setIsSold(true);
						
						$Arr_Items = array();
						array_push($Arr_Items, $Item);
						
						$Items = new XeroAPI\XeroPHP\Models\Accounting\Items;
						$Items->setItems($Arr_Items);
						
						try{
							$result = $this->X_API_I()->createItems($this->XeroTenantId,$Items,true,$this->x_unitdp());

							if(!empty($result)){
								$xr_items = $result->getItems();
								if(is_array($xr_items) && !empty($xr_items)){
									$x_item = $xr_items[0];
									$X_ItemID = $x_item->getItemID();
									if(!empty($X_ItemID)){							
										$ItemID = $X_ItemID;
										# Save into local table
										$this->save_xero_product_into_local_dbt($x_item);
									}
								}
							}
						}catch (\XeroAPI\XeroPHP\ApiException $e) {
							$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
							#$ld = $this->get_error_message_from_xero_error_object($error);
						}
					}

					if(!empty($ItemID)){
						$this->update_option('mw_wc_xero_sync_default_xero_product',$ItemID);
					}
				}

				# Accounts
				$xaa = $this->xero_get_accounts_kva();
				
				if(is_array($xta) && !empty($xta)){
					# 1st Condition
					foreach($xta as $k => $v){
						if(empty($k)){
							continue;
						}

						# Default Xero Account for Order Line Items
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_account_foli'))){
							if($v == 'Sales (Sales)'){
								$this->update_option('mw_wc_xero_sync_default_xero_account_foli',$k);
							}
						}

						# Default Xero Sales Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_sales_account_fnp'))){
							if($v == 'Sales (Sales)'){
								$this->update_option('mw_wc_xero_sync_default_xero_sales_account_fnp',$k);
							}
						}

						# Default Xero Inventory Asset Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_inventory_asset_account_fnp'))){
							if($v == 'Inventory (Inventory)'){
								$this->update_option('mw_wc_xero_sync_default_xero_inventory_asset_account_fnp',$k);
							}
						}

						# Default Xero COGS Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_cogs_account_fnp'))){
							if($v == 'Cost of Goods Sold (Directcosts)'){
								$this->update_option('mw_wc_xero_sync_default_xero_cogs_account_fnp',$k);
							}
						}
					}

					# 2nd Condition
					foreach($xta as $k => $v){
						if(empty($k)){
							continue;
						}

						# Default Xero Account for Order Line Items
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_account_foli'))){
							if(strlen($v) > 7 && substr($v, -7) == '(Sales)'){
								$this->update_option('mw_wc_xero_sync_default_xero_account_foli',$k);
							}
						}

						# Default Xero Sales Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_sales_account_fnp'))){
							if(strlen($v) > 7 && substr($v, -7) == '(Sales)'){
								$this->update_option('mw_wc_xero_sync_default_xero_sales_account_fnp',$k);
							}
						}

						# Default Xero Inventory Asset Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_inventory_asset_account_fnp'))){
							if(strlen($v) > 11 && substr($v, -11) == '(Inventory)'){
								$this->update_option('mw_wc_xero_sync_default_xero_inventory_asset_account_fnp',$k);
							}
						}
						
						# Default Xero COGS Account for New Products
						if(empty($this->get_option('mw_wc_xero_sync_default_xero_cogs_account_fnp'))){
							if(strlen($v) > 13 && substr($v, -13) == '(Directcosts)'){
								$this->update_option('mw_wc_xero_sync_default_xero_cogs_account_fnp',$k);
							}
						}
					}
				}

				# Default Xero Shipping Product
				if(empty($this->get_option('mw_wc_xero_sync_default_xero_shipping_product'))){
					$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Name','Shipping');
					if(empty($ItemID)){
						$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Name','Freight');
					}

					if(!empty($ItemID)){
						$this->update_option('mw_wc_xero_sync_default_xero_shipping_product',$ItemID);
					}
				}

				# Xero Non-taxable Rate
				if(empty($this->get_option('mw_wc_xero_sync_non_taxable_rate'))){
					$xta = $this->xero_get_tax_rates_kva();
					if(is_array($xta) && !empty($xta)){
						foreach($xta as $k => $v){
							if(empty($k)){
								continue;
							}

							if($k == 'NONE' && $v == 'Tax Exempt (0%)'){
								$this->update_option('mw_wc_xero_sync_non_taxable_rate',$k);
								break;
							}
						}
					}
				}
				
				$this->update_option('mw_wc_xero_ots_afxc_run','true');
			}			
		}
	}
	
	#Validation
	public function validate_connection_key($key){
		$regex = '/^[A-Z0-9-]+$/i';
		return preg_match($regex, $key);
	}
	
	#Settings Save
	public function save_setting_page_data($post,$fn,$ft='',$dv='',$dt='',$ext=''){
		if(!empty($fn) && is_array($post) && !empty($post)){
			$fn = $this->get_s_o_p().$fn;
			
			if($ft == 'option_check'){
				$dv = 'false';
			}
			
			$val = isset($post[$fn])?$post[$fn]:$dv;
			
			if($ft == 'c_s'){
				if(is_array($val) && !empty($val)){
					$val = implode(',',$val);
				}else{
					$val = '';
				}
			}
			
			if(!is_array($val)){
				$val = trim($val);
				if(!empty($dt) && !empty($val)){
					if($dt == 'int'){
						$val = intval($val);
					}
					
					if($dt == 'float'){
						$val = floatval($val);
					}
				}
			}
			
			$this->update_option($fn,$val);
		}
	}
	
	public function is_queue_sync_e(){
		return true;
	}
	
	/*License*/
	public function is_valid_license($licensekey,$localkey="",$realtime=false){
		if(!$this->is_valid_license){			
			$license_data = $this->myworks_wc_xero_sync_check_license($licensekey,$localkey,$realtime);			
			if(!$realtime){
				$this->mw_license_lk_blank_check_run($licensekey,$localkey,$realtime);
			}			
			
			$this->plugin_license_status = (isset($license_data['status']))?$license_data['status']:'';
			if(isset($license_data['status']) && $license_data['status']=='Active' && !isset($license_data['trial_expired'])){
				$this->is_valid_license = true;
			}else{
				if(isset($license_data['trial_expired'])){
					$this->plugin_license_status = 'Invalid';
				}
			}
		}
		return $this->is_valid_license;
	}
	
	public function get_license_status(){
		return $this->plugin_license_status;
	}

	public function is_license_active(){
		if($this->is_valid_license && $this->plugin_license_status == 'Active'){
			return true;
		}

		return false;
	}
	
	public function get_ldfcpv(){
		return (array) $this->license_data_for_conn_page_view;
	}

	protected function myworks_wc_xero_sync_check_license($licensekey,$localkey,$realtime){
		$results = array('status'=>'Invalid');
		$lc_url = $this->plugin_license_url;
		
		if(!empty($lc_url)){
			$site_url = $this->get_option('siteurl');
						
			if(!empty($licensekey) && !empty($site_url) && strlen($licensekey) == '35' && $this->validate_connection_key($licensekey)){	
				$this->update_option('mw_wc_xero_license',$licensekey);			
				$r = wp_remote_post($lc_url,array(
					'method'      => 'POST',
					'body' => array(
						'licensekey' => $licensekey,
						'domain' => $site_url,
					)
				));
				
				if(!is_wp_error($r)){
					#$this->_p($r);
					if($r['response']['code'] == '200' && !empty($r['body'])){
						$rb = json_decode($r['body'],true);
						if(is_array($rb) && isset($rb['status'])){
							$results = $rb;	
							$ldfcpv = array();
							$ldfcpv['status'] = $results['status'];
							$ldfcpv['nextduedate'] = (isset($results['nextduedate']))?$results['nextduedate']:'';
							$ldfcpv['billingcycle'] = (isset($results['billingcycle']))?$results['billingcycle']:'';
							$l_pln = '';
							if((isset($results['productname'])) && !empty($results['productname']) && strpos($results['productname'],' | ')!==false){
								$pn_arr = explode(' | ',$results['productname']);
								if(is_array($pn_arr) && count($pn_arr) == 2){
									$l_pln = $pn_arr[1];
								}
							}
							$ldfcpv['plan'] = $l_pln;
							$ldfcpv['productname'] = (isset($results['productname']))?$results['productname']:'';
							$this->license_data_for_conn_page_view = $ldfcpv;						
						}
					}
				}else{
					#echo $r->get_error_message();
				}
			}
		}		
		
		return $results;
	}
	
	private function mw_license_lk_blank_check_run($licensekey,$localkey="",$realtime=false){
		$recent_llk = get_option('mw_wc_xero_localkey','');		
		if(empty($recent_llk)){
			$is_lbcr = true;
			$td = date('Y-m-d');
			$td_rc = 0;
			
			$lbcr_chk_opt = get_option('mw_wc_xero_lbcr_chk_count_dt','');
			if(!empty($lbcr_chk_opt) && is_array($lbcr_chk_opt)){
				if(isset($lbcr_chk_opt[$td])){
					$td_rc = (int) $lbcr_chk_opt[$td];
					if($td_rc  < 0){$td_rc = 0;}
					if($td_rc  >= 2){
						$is_lbcr = false;
					}
				}
			}
			
			if($is_lbcr){
				$td_rc++;
				$lbcr_nd = array();
				$lbcr_nd[$td] = $td_rc;
				
				$this->update_option('mw_wc_xero_lbcr_chk_count_dt',$lbcr_nd);
				
				if($this->is_valid_license($licensekey,$localkey,true)){
					return true;
				}				
			}
		}
		
		return false;
	}
	
	#Dashboard Log Graph Data
	public function get_log_chart_data(){
		global $wpdb;
		$p_log_tbl = $this->gdtn('log');
		
		$today = date("Y-m-d").' 00:00:00';
        $month = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y")));
        $year = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 12, 1, date("Y")));

		$invoiceData = array();
		$result_inv_today = $this->get_data("SELECT date_format(added_date, '%k') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$today' AND `log_type`='Order' AND `status`=1 AND `details` NOT LIKE '%Draft Invoice not allowed%' GROUP BY date_format(added_date, '%k')");
		if(is_array($result_inv_today) && !empty($result_inv_today)){
			foreach($result_inv_today as $data){
				$invoiceData['today'][$data['date']] = $data['count'];
			}
		}
		$result_inv_month = $this->get_data("SELECT date_format(added_date, '%e %M') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$month' AND `log_type`='Order' AND `status`=1 AND `details` NOT LIKE '%Draft Invoice not allowed%' GROUP BY date_format(added_date, '%e')");
		if(is_array($result_inv_month) && !empty($result_inv_month)){
			foreach($result_inv_month as $data){
				$invoiceData['month'][$data['date']] = $data['count'];
			}
		}

		$result_inv_year = $this->get_data("SELECT date_format(added_date, '%M %Y') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$year' AND `log_type`='Order' AND `status`=1 AND `details` NOT LIKE '%Draft Invoice not allowed%' GROUP BY date_format(added_date, '%M')");
		if(is_array($result_inv_year) && !empty($result_inv_year)){
			foreach($result_inv_year as $data){
				$invoiceData['year'][$data['date']] = $data['count'];
			}
		}
		
		$paymentData = array();
		$result_pmnt_today = $this->get_data("SELECT date_format(added_date, '%k') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$today' AND `log_type`='Payment' AND `status`=1 GROUP BY date_format(added_date, '%k')");
		if(is_array($result_pmnt_today) && !empty($result_pmnt_today)){
			foreach($result_pmnt_today as $data){
				$paymentData['today'][$data['date']] = $data['count'];
			}
		}
		$result_pmnt_month = $this->get_data("SELECT date_format(added_date, '%e %M') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$month' AND `log_type`='Payment' AND `status`=1 GROUP BY date_format(added_date, '%e')");
		if(is_array($result_pmnt_month) && !empty($result_pmnt_month)){
			foreach($result_pmnt_month as $data){
				$paymentData['month'][$data['date']] = $data['count'];
			}
		}

		$result_pmnt_year = $this->get_data("SELECT date_format(added_date, '%M %Y') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$year' AND `log_type`='Payment' AND `status`=1 GROUP BY date_format(added_date, '%M')");
		if(is_array($result_pmnt_year) && !empty($result_pmnt_year)){
			foreach($result_pmnt_year as $data){
				$paymentData['year'][$data['date']] = $data['count'];
			}
		}
		
		$clientData = array();
		$result_cl_today = $this->get_data("SELECT date_format(added_date, '%k') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$today' AND `log_type`='Customer' AND `status`=1 GROUP BY date_format(added_date, '%k')");
		if(is_array($result_cl_today) && !empty($result_cl_today)){
			foreach($result_cl_today as $data){
				$clientData['today'][$data['date']] = $data['count'];
			}
		}
		$result_cl_month = $this->get_data("SELECT date_format(added_date, '%e %M') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$month' AND `log_type`='Customer' AND `status`=1 GROUP BY date_format(added_date, '%e')");
		if(is_array($result_cl_month) && !empty($result_cl_month)){
			foreach($result_cl_month as $data){
				$clientData['month'][$data['date']] = $data['count'];
			}
		}

		$result_cl_year = $this->get_data("SELECT date_format(added_date, '%M %Y') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$year' AND `log_type`='Customer' AND `status`=1 GROUP BY date_format(added_date, '%M')");
		if(is_array($result_cl_year) && !empty($result_cl_year)){
			foreach($result_cl_year as $data){
				$clientData['year'][$data['date']] = $data['count'];
			}
		}
		
		$errorData = array();
		$result_er_today = $this->get_data("SELECT date_format(added_date, '%k') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$today' AND `status`=0 GROUP BY date_format(added_date, '%k')");
		if(is_array($result_er_today) && !empty($result_er_today)){
			foreach($result_er_today as $data){
				$errorData['today'][$data['date']] = $data['count'];
			}
		}
		$result_er_month = $this->get_data("SELECT date_format(added_date, '%e %M') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$month' AND `status`=0 GROUP BY date_format(added_date, '%e')");
		if(is_array($result_er_month) && !empty($result_er_month)){
			foreach($result_er_month as $data){
				$errorData['month'][$data['date']] = $data['count'];
			}
		}

		$result_er_year = $this->get_data("SELECT date_format(added_date, '%M %Y') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$year' AND `status`=0 GROUP BY date_format(added_date, '%M')");
		if(is_array($result_er_year) && !empty($result_er_year)){
			foreach($result_er_year as $data){
				$errorData['year'][$data['date']] = $data['count'];
			}
		}
		
		$productData = array();
		$result_prd_today = $this->get_data("SELECT date_format(added_date, '%k') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$today' AND (`log_type`='Product' OR `log_type`='Variation') AND `status`=1 GROUP BY date_format(added_date, '%k')");
		if(is_array($result_prd_today) && !empty($result_prd_today)){
			foreach($result_prd_today as $data){
				$productData['today'][$data['date']] = $data['count'];
			}
		}

		$result_prd_month = $this->get_data("SELECT date_format(added_date, '%e %M') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$month' AND (`log_type`='Product' OR `log_type`='Variation') AND `status`=1 GROUP BY date_format(added_date, '%e')");
		if(is_array($result_prd_month) && !empty($result_prd_month)){
			foreach($result_prd_month as $data){
				$productData['month'][$data['date']] = $data['count'];
			}
		}

		$result_prd_year = $this->get_data("SELECT date_format(added_date, '%M %Y') AS date, COUNT(id) AS count FROM `".$p_log_tbl."` WHERE added_date>'$year' AND (`log_type`='Product' OR `log_type`='Variation') AND `status`=1 GROUP BY date_format(added_date, '%M')");
		if(is_array($result_prd_year) && !empty($result_prd_year)){
			foreach($result_prd_year as $data){
				$productData['year'][$data['date']] = $data['count'];
			}
		}
		
		return array(
            'invoices' => array(
                'total' => $invoiceData,
            ),
            'clients' => array(
                'total' => $clientData,
            ),
			 'errors' => array(
                'total' => $errorData,
            ),
			'payments' => array(
                'total' => $paymentData,
            ),			
			'products' => array(
                'total' => $productData,
            ),

        );
	}
	
	#WC Data Functions
	public function get_variation_name_from_id($v_name,$p_name='',$v_id,$p_id=0){
		$v_name = trim($v_name);$p_name = trim($p_name);		
		
		$v_id = intval($v_id);$p_id = intval($p_id);
		if($v_name!='' && $v_id>0){
			global $wpdb;
			if(!$p_id || empty($p_name)){
				$p_data = $this->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->posts}` WHERE  `ID` = %d AND `post_type` = 'product_variation'  ",$v_id));
				if(is_array($p_data) && count($p_data)){
					$p_id = (int) $p_data['post_parent'];
					#$p_name = $p_data['post_title'];
					$p_name = $this->get_field_by_val($wpdb->posts,'post_title','ID',$p_id);
				}
			}
			
			if($p_id>0 && !empty($p_name)){
				$_product_attributes_a = get_post_meta($p_id,'_product_attributes',true);
				if(is_array($_product_attributes_a) && count($_product_attributes_a)){
					$pa_k_a = array();
					foreach($_product_attributes_a as $pak => $pav){
						$pa_k_a[] = $pak;
					}
					
					$v_meta = get_post_meta($v_id);					
					
					if(is_array($v_meta) && count($v_meta)){
						$v_av_pa = array();
						foreach($v_meta as $vmk => $vmv){								
							if (substr($vmk, 0, strlen('attribute_')) == 'attribute_') {
								$vmk = substr($vmk, strlen('attribute_'));
								if(in_array($vmk,$pa_k_a)){
									$vmv = ($vmv[0])?$vmv[0]:'';
									if(!is_numeric($vmv)){
										$vmv = ucfirst($vmv);
									}
									$p_name.=' - '.$vmv;
									
									/*
									if($this->start_with($vmk,'pa_')){
										$vmk = $this->sanitize(substr($vmk,3));
									}
									$v_av_pa[$vmk] = $vmv;
									*/
								}
							}								
						}
					}
					
					return $p_name;
				}
			}
		}
		return $v_name;
	}
	
	# Automap	
	public function AutoMapCustomers($cam_wf,$cam_qf,$mo_um=false){
		global $wpdb;
		$map_count = 0;
		
		$map_tbl = $this->gdtn('map_customers');
		$x_customer_tbl = $this->gdtn('customers');
		
		if(empty($cam_wf) || empty($cam_qf)){
			return $map_count;
		}
		
		if(!is_array($this->wc_customer_automap_fields()) || !is_array($this->xero_customer_automap_fields())){
			return $map_count;
		}
		
		$cam_wf_la = $this->wc_customer_automap_fields();
		$cam_qf_la = $this->xero_customer_automap_fields();
		
		if(!isset($cam_wf_la[$cam_wf]) || !isset($cam_qf_la[$cam_qf])){
			return $map_count;
		}
		
		$roles = 'customer';
		
		$ext_roles = '';
		if($ext_roles!=''){
			$roles.=','.$ext_roles;
		}
		
		if ( ! is_array( $roles ) ){			
			$roles = array_map('trim',explode( ",", $roles ));
		}
		
		$sql = '
			SELECT  ' . $wpdb->users . '.ID, ' . $wpdb->users . '.display_name, ' . $wpdb->users . '.user_email
			FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
			ON          ' . $wpdb->users . '.ID = ' . $wpdb->usermeta . '.user_id
			WHERE       ' . $wpdb->usermeta . '.meta_key = \'' . $wpdb->prefix . 'capabilities\'
			AND     (
		';
		
		$i = 1;
		foreach ( $roles as $role ) {
			$sql .= ' ' . $wpdb->usermeta . '.meta_value    LIKE    \'%%"' . $role . '"%%\' ';
			if ( $i < count( $roles ) ) $sql .= ' OR ';
			$i++;
		}
		$sql .= ' ) ';
		
		if($cam_qf=='first_last'){
			$cam_qf_cl = "`FirstName` , `LastName`";
		}else{
			$cam_qf_cl = "`{$cam_qf}`";
		}		
		
		$all_wc_customers = $this->get_data($sql);
		$all_qbo_customers = $this->get_data("SELECT `ContactID`, {$cam_qf_cl} FROM ".$x_customer_tbl);
		
		if(!$mo_um){
			$wpdb->query("DELETE FROM `".$map_tbl."` WHERE `id` > 0 ");
			$wpdb->query("TRUNCATE TABLE `".$map_tbl."` ");
		}
		
		if(is_array($all_wc_customers) && count($all_wc_customers) && is_array($all_qbo_customers) && count($all_qbo_customers)){
			foreach($all_wc_customers as $w_cus){
				$insert_id = (int) $this->check_save_automap_customer_data($w_cus,$all_qbo_customers,$cam_wf,$cam_qf,$mo_um);
				if($insert_id>0){
					$map_count++;
				}
			}
		}
		
		unset($all_wc_customers);
		unset($all_qbo_customers);
		
		return $map_count;
	}
	
	public function check_save_automap_customer_data($w_cus,$all_qbo_customers,$cam_wf,$cam_qf,$mo_um=false){
		global $wpdb;
		$map_tbl = $this->gdtn('map_customers');
		
		# For Only Unmapped
		if($mo_um){
			$ID = $w_cus['ID'];							
			$e_mr = $this->get_row($wpdb->prepare("SELECT `id` FROM {$map_tbl} WHERE `W_C_ID` = %d ",$ID));
			if(!empty($e_mr)){
				return;
			}
		}
		
		if(!isset($w_cus[$cam_wf])){
			if($cam_wf=='first_name_last_name'){
				$w_cus[$cam_wf] = get_user_meta($w_cus['ID'],'first_name',true) . ' '. get_user_meta($w_cus['ID'],'last_name',true);
			}else{
				$w_cus[$cam_wf] = get_user_meta($w_cus['ID'],$cam_wf,true);
			}			
		}
		
		$wf_v = $this->get_array_isset($w_cus,$cam_wf,'',true);		
		
		if(!empty($cam_wf) && !empty($cam_qf)){
			foreach($all_qbo_customers as $q_cus){
				$is_match_map_customer = false;
				if(isset($q_cus[$cam_qf]) || $cam_qf == 'first_last'){
					if($cam_qf == 'first_last'){
						$qf_v = $this->get_array_isset($q_cus,'FirstName','',true) . ' '. $this->get_array_isset($q_cus,'LastName','',true);
					}else{
						$qf_v = $this->get_array_isset($q_cus,$cam_qf,'',true);
					}
					
					if($wf_v!='' && strtoupper($wf_v) == strtoupper($qf_v)){
						$is_match_map_customer = true;
					}
					
					if($is_match_map_customer){
						$save_data = array();
						$save_data['W_C_ID'] = $w_cus['ID'];
						$save_data['X_C_ID'] = $q_cus['ContactID'];
						$wpdb->insert($map_tbl,$save_data);
						return (int) $wpdb->insert_id;
						break;
					}
				}
			}
		}
	}
	
	public function AutoMapProducts($pam_wf,$pam_qf,$mo_um=false){
		global $wpdb;
		$map_count = 0;
		
		$map_tbl = $this->gdtn('map_products');
		$x_product_tbl = $this->gdtn('products');
		
		if(empty($pam_wf) || empty($pam_qf)){
			return $map_count;
		}
		
		if(!is_array($this->wc_product_automap_fields()) || !is_array($this->xero_product_automap_fields())){
			return $map_count;
		}
		
		$pam_wf_la = $this->wc_product_automap_fields();
		$pam_qf_la = $this->xero_product_automap_fields();
		
		if(!isset($pam_wf_la[$pam_wf]) || !isset($pam_qf_la[$pam_qf])){
			return $map_count;
		}
		
		$m_whr = '';
		if($pam_wf=='sku'){
			$m_whr.=" AND pm1.meta_value!=''";
		}
		
		$sql = "
			SELECT DISTINCT(p.ID), p.post_title AS name, pm1.meta_value AS sku
			FROM ".$wpdb->posts." p
			LEFT JOIN ".$wpdb->postmeta." pm1 ON ( pm1.post_id = p.ID
			AND pm1.meta_key =  '_sku' )
			WHERE p.post_type =  'product'
			AND p.post_status NOT IN('trash','auto-draft','inherit')
			{$m_whr}
		";
		
		$all_wc_products = $this->get_data($sql);
		$all_qbo_products = $this->get_data("SELECT `ItemID`, `Name` , `Code` FROM ".$x_product_tbl);
		
		if(!$mo_um){
			$wpdb->query("DELETE FROM `".$map_tbl."` WHERE `id` > 0 ");
			$wpdb->query("TRUNCATE TABLE `".$map_tbl."` ");
		}
		
		if(is_array($all_wc_products) && count($all_wc_products) && is_array($all_qbo_products) && count($all_qbo_products)){
			foreach($all_wc_products as $w_pro){
				$insert_id = (int) $this->check_save_automap_product_data($w_pro,$all_qbo_products,$pam_wf,$pam_qf,$mo_um);
				if($insert_id>0){
					$map_count++;
				}
			}
		}
		unset($all_wc_products);
		unset($all_qbo_products);
		return $map_count;
	}
	
	public function check_save_automap_product_data($w_pro,$all_qbo_products,$pam_wf,$pam_qf,$mo_um=false){
		global $wpdb;
		$map_tbl = $this->gdtn('map_products');
		
		# For Only Unmapped
		if($mo_um){
			$ID = $w_pro['ID'];
			$e_mr = $this->get_row($wpdb->prepare("SELECT `id` FROM {$map_tbl} WHERE `W_P_ID` = %d ",$ID));
			if(!empty($e_mr)){
				return;
			}
		}
		
		$wf_v = $this->get_array_isset($w_pro,$pam_wf,'',true);		
		
		foreach($all_qbo_products as $q_pro){
			$is_match_map_product = false;
			if(isset($q_pro[$pam_qf])){				
				$qf_v = $this->get_array_isset($q_pro,$pam_qf,'',true);				
				if($wf_v!='' && strtoupper($wf_v) == strtoupper($qf_v)){
					$is_match_map_product = true;
				}
			}
			
			if($is_match_map_product){
				$save_data = array();
				$save_data['W_P_ID'] = $w_pro['ID'];
				$save_data['X_P_ID'] = $q_pro['ItemID'];
				$wpdb->insert($map_tbl,$save_data);
				return (int) $wpdb->insert_id;
				break;
			}
		}
	}
	
	#With new last count functionality
	public function AutoMapVariations($vam_wf,$vam_qf,$mo_um=false){
		global $wpdb;
		
		$map_count = 0;
		
		$map_tbl = $this->gdtn('map_variations');
		$x_product_tbl = $this->gdtn('products');
		
		if(empty($vam_wf) || empty($vam_qf)){
			return $map_count;
		}
		
		if(!is_array($this->wc_variation_automap_fields()) || !is_array($this->xero_variation_automap_fields())){
			return $map_count;
		}
		
		$vam_wf_la = $this->wc_variation_automap_fields();
		$vam_qf_la = $this->xero_variation_automap_fields();
		
		if(!isset($vam_wf_la[$vam_wf]) || !isset($vam_qf_la[$vam_qf])){
			return $map_count;
		}
		
		$m_whr = '';
		$m_join = '';
		$m_slt = '';
		
		if($vam_wf=='sku'){
			$m_whr.=" AND pm1.meta_value!=''";
			$m_join.=" INNER JOIN ".$wpdb->postmeta." pm1 ON ( pm1.post_id = p.ID
			AND pm1.meta_key =  '_sku' )";
			
			$m_slt.=" , pm1.meta_value AS sku";
		}
		
		$sql_c = "
			SELECT COUNT(*)
			FROM ".$wpdb->posts." p	
			{$m_join}
			WHERE p.post_type =  'product_variation'
			AND p.post_status NOT IN('trash','auto-draft','inherit')
			{$m_whr}
		";
		
		$mml = 500;
		$amc = (int) $wpdb->get_var($sql_c);
		
		$mbc =  ($mml >= $amc) ? 1 : ceil($amc / $mml);
		
		/**/
		$li = 0;
		$amlcd_a = get_option('mw_wc_xero_automap_last_c_data');
		if(!is_array($amlcd_a)){$amlcd_a = array();}
		if(!empty($amlcd_a) && isset($amlcd_a['pv']) && is_array($amlcd_a['pv']) && !empty($amlcd_a['pv'])){
			$lcd_pv = $amlcd_a['pv'];
			if($lcd_pv['wk'] == $vam_wf && $lcd_pv['qk'] == $vam_qf){
				if($lcd_pv['li'] != $mbc){
					$li = $lcd_pv['li'];
				}
			}
		}
		
		$all_wc_variations = array();
		#$all_qbo_products = array();
		
		if(!$mo_um){
			$wpdb->query("DELETE FROM `".$map_tbl."` WHERE `id` > 0 ");
			$wpdb->query("TRUNCATE TABLE `".$map_tbl."` ");
		}
		
		$all_qbo_products = $this->get_data("SELECT `ItemID`, `Name` , `Code` FROM ".$x_product_tbl);
		
		$li_n = 0;
		for ($i=$li; $i<$mbc; $i++) {
			$mlo = $i*$mml;
			
			$sql_d = "
				SELECT DISTINCT(p.ID), p.post_title AS name {$m_slt}
				FROM ".$wpdb->posts." p	
				{$m_join}
				WHERE p.post_type =  'product_variation'
				AND p.post_status NOT IN('trash','auto-draft','inherit')
				{$m_whr}
				ORDER BY name ASC
				LIMIT {$mlo}, {$mml}
			";
			
			$all_wc_variations = $this->get_data($sql_d);
			
			if(is_array($all_wc_variations) && count($all_wc_variations) && is_array($all_qbo_products) && count($all_qbo_products)){
				foreach($all_wc_variations as $w_pro){
					$insert_id = (int) $this->check_save_automap_variation_data($w_pro,$all_qbo_products,$vam_wf,$vam_qf,$mo_um);
					if($insert_id>0){
						$map_count++;
					}
				}
			}			
			
			$li_n = $i+1;			
		}
		
		$amlcd_a['pv'] = array(
			'wk' => $vam_wf,
			'qk' => $vam_qf,
			'li' => $li_n
		);
		
		update_option('mw_wc_xero_automap_last_c_data',$amlcd_a,'no');
		
		unset($all_wc_variations);
		unset($all_qbo_products);
		return $map_count;
	}
	
	public function check_save_automap_variation_data($w_pro,$all_qbo_products,$vam_wf,$vam_qf,$mo_um=false){
		global $wpdb;
		$map_tbl = $this->gdtn('map_variations');
		
		# For Only Unmapped
		if($mo_um){
			$ID = $w_pro['ID'];
			$e_mr = $this->get_row($wpdb->prepare("SELECT `id` FROM {$map_tbl} WHERE `W_V_ID` = %d ",$ID));
			if(!empty($e_mr)){
				return;
			}
		}
		
		$wf_v = $this->get_array_isset($w_pro,$vam_wf,'',true);
		
		foreach($all_qbo_products as $q_pro){
			$is_match_map_variation = false;
			if(isset($q_pro[$vam_qf])){				
				$qf_v = $this->get_array_isset($q_pro,$vam_qf,'',true);				
				if($wf_v!='' && strtoupper($wf_v) == strtoupper($qf_v)){
					$is_match_map_variation = true;
				}
			}
			
			if($is_match_map_variation){
				$save_data = array();
				$save_data['W_V_ID'] = $w_pro['ID'];
				$save_data['X_P_ID'] = $q_pro['ItemID'];
				$wpdb->insert($map_tbl,$save_data);
				return (int) $wpdb->insert_id;
				break;
			}
		}
	}
	
	# Clear Invalid Mappings
	private function clear_invalid_mappings($type,$loop=false){
		$list_table = '';$it_id_field = '';$it_qb_id_field = '';
		$map_table = '';$mt_id_field = '';$mt_qb_id_field = '';
		
		switch ($type) {
			case "product":
				$list_table = $this->gdtn('products');
				$map_table = $this->gdtn('map_products');
				
				$it_qb_id_field = 'ItemID';
				$mt_qb_id_field = 'X_P_ID';
				
				break;
			case "variation":
				$list_table = $this->gdtn('products');
				$map_table = $this->gdtn('map_variations');
				
				$it_qb_id_field = 'ItemID';
				$mt_qb_id_field = 'X_P_ID';
				
				break;
			case "customer":
				$list_table = $this->gdtn('customers');
				$map_table = $this->gdtn('map_customers');
				
				$it_qb_id_field = 'ContactID';
				$mt_qb_id_field = 'X_C_ID';
				
				break;
			case "paymentmethod":
				
				break;
			default:			
		}
		
		if($list_table!='' && $map_table!='' && $it_qb_id_field!='' && $mt_qb_id_field!=''){
			global $wpdb;
			$list_table = $wpdb->prefix.$list_table;
			$map_table = $wpdb->prefix.$map_table;
			
			if(empty($it_id_field)){
				$it_id_field = 'id';
			}
			
			if(empty($mt_id_field)){
				$mt_id_field = 'id';
			}
			
			if($loop){
				return $this->clear_invalid_mappings_by_loop($list_table,$map_table,$it_id_field,$mt_id_field,$it_qb_id_field,$mt_qb_id_field);
			}
			
			/*
			$sq = " SELECT `{$it_qb_id_field}` FROM {$list_table} ";
			$q = " DELETE FROM {$map_table} WHERE `{$mt_qb_id_field}` NOT IN ({$sq}) ";
			*/
			
			$sq = "SELECT `{$it_qb_id_field}` FROM {$list_table} WHERE {$list_table}.{$it_qb_id_field} = {$map_table}.{$mt_qb_id_field}";
			$q = "DELETE FROM {$map_table} WHERE NOT EXISTS ({$sq}); ";
			$wpdb->query($q);
			return true;
		}
	}
	
	private function clear_invalid_mappings_by_loop($list_table,$map_table,$it_id_field,$mt_id_field,$it_qb_id_field,$mt_qb_id_field){
		global $wpdb;
		$map_data = $this->get_data("SELECT `{$mt_id_field}` , `{$mt_qb_id_field}` FROM {$map_table}");
		$tot_deleted = 0;
		if(is_array($map_data) && count($map_data)){
			foreach($map_data as $md){
				$mt_id_val = (int) $md[$mt_id_field];
				$mt_qb_val = $md[$mt_qb_id_field];
				$mt_qb_val = $this->sanitize($mt_qb_val);
				$ld = $this->get_row("SELECT `{$it_id_field}` FROM {$list_table} WHERE `{$it_qb_id_field}` !='' AND `{$it_qb_id_field}` = '{$mt_qb_val}' ");
				if(empty($ld)){
					$wpdb->query("DELETE FROM `{$map_table}` WHERE `{$mt_id_field}` = {$mt_id_val} AND `{$mt_qb_id_field}` = '{$mt_qb_val}' ");
					$tot_deleted++;
				}
			}
		}
		return $tot_deleted;
	}
	
	public function clear_customer_invalid_mappings(){
		return $this->clear_invalid_mappings('customer',true);
	}
	
	public function clear_product_invalid_mappings(){
		return $this->clear_invalid_mappings('product',true);
	}
	
	public function clear_variation_invalid_mappings(){
		return $this->clear_invalid_mappings('variation',true);
	}
	
	# WC Customer details by ID
	public function get_wc_customer_info($customer_id,$user_info=null,$manual=false){
		$customer_data = array();
		$customer_id = (int) $customer_id;
		
		if($customer_id>0){
			if(is_null($user_info)){
				$user_info = get_userdata($customer_id);
			}
			
			if(empty($user_info)){
				return $customer_data;
			}
			
			$user_id = $user_info->ID;
			$user_meta = get_user_meta($user_id);
			
			if(!is_array($user_meta)){
				$user_meta = array();
			}
			
			$customer_data['wc_cus_id'] = $user_id;
			$customer_data['first_name'] = (isset($user_info->first_name))?$user_info->first_name:'';
			$customer_data['last_name'] = (isset($user_info->last_name))?$user_info->last_name:'';
			$customer_data['full_name'] = $customer_data['first_name'].' '.$customer_data['last_name'];
			
			$customer_data['email'] = (isset($user_info->user_email))?$user_info->user_email:'';
			$customer_data['display_name'] = (isset($user_info->display_name))?$user_info->display_name:'';
			$customer_data['username'] = (isset($user_info->user_login))?$user_info->user_login:'';
			
			$customer_data['company'] = (isset($user_meta['billing_company'][0]))?$user_meta['billing_company'][0]:'';
			
			$amk = array(
				'nickname',
				'description',
				#'wc_last_active',
				#'dismissed_wp_pointers',
				#'dismissed_update_notice',				
			);
			
			if(!empty($user_meta)){
				foreach ($user_meta as $key => $value){
					if(in_array($key,$amk) || $this->start_with($key,'billing_') || $this->start_with($key,'shipping_')){
						$customer_data[$key] = ($value[0])?$value[0]:'';
					}					
				}
			}

			$customer_data['currency'] = (string) $this->get_wc_customer_currency($user_id);
			
			$customer_data['manual'] = $manual;
		}
		
		return $customer_data;
	}
	
	public function get_wc_customer_info_from_order($order_id,$manual=false){
		$customer_data = array();
		$order_id = (int) $order_id;
		if($order_id > 0){
			$order_meta = get_post_meta($order_id);
			if(empty($order_meta)){
				return $customer_data;
			}

			$_customer_user = (isset($order_meta['_customer_user'][0]))?(int) $order_meta['_customer_user'][0]:0;
			$customer_data['wc_cus_id'] = $_customer_user;
			$customer_data['order_id'] = $order_id;

			if(is_array($order_meta) && count($order_meta)){
				foreach ($order_meta as $key => $value){
					if($this->start_with($key,'_billing_') || $this->start_with($key,'_shipping_')){
						if($this->start_with($key,'_billing_')){
							$key = str_replace('_billing_','billing_',$key);
						}else{
							$key = str_replace('_shipping_','shipping_',$key);
						}
						$customer_data[$key] = ($value[0])?$value[0]:'';
					}
				}
			}

			$customer_data['first_name'] = $this->get_array_isset($customer_data,'billing_first_name','',true);
			$customer_data['last_name'] = $this->get_array_isset($customer_data,'billing_last_name','',true);
			$customer_data['full_name'] = $customer_data['first_name'].' '.$customer_data['last_name'];

			$customer_data['email'] = $this->get_array_isset($customer_data,'billing_email','',true);
			$customer_data['company'] = $this->get_array_isset($customer_data,'billing_company','',true);

			$_order_currency = (isset($order_meta['_order_currency'][0]))?$order_meta['_order_currency'][0]:'';
			$customer_data['currency'] = $_order_currency;

			$customer_data['manual'] = $manual;
		}

		return $customer_data;
	}
	
	# WC Order details by ID
	public function get_wc_order_details_from_order($order_id,$order=null,$manual=false){
		global $wpdb;
		$invoice_data = array();
		
		$order_id = (int) $order_id;
		if($order_id > 0){
			if(is_null($order)){
				$order = get_post($order_id);
			}
			
			if(is_object($order) && !empty($order)){
				$order_meta = get_post_meta($order_id);
				if(!is_array($order_meta)){
					$order_meta = array();
				}
				
				$invoice_data['wc_inv_id'] = $order_id;
				
				#$invoice_data['wc_inv_num'] = '';
				$invoice_data['wc_inv_num'] = $this->get_woo_ord_number_from_order($order_id);
				
				$invoice_data['order_type'] = '';				
				
				$order_date = $order->post_date;
				$wc_inv_date = $order_date;
				
				$s_odf = $this->get_option('mw_wc_xero_sync_order_date_val');
				if($s_odf == 'order_completed_date'){
					$wc_inv_date = (isset($order_meta['_completed_date'][0]) && !empty($order_meta['_completed_date'][0]))?$order_meta['_completed_date'][0]:$order_date;
				}
				
				if($s_odf == 'order_paid_date'){
					$wc_inv_date = (isset($order_meta['_paid_date'][0]) && !empty($order_meta['_paid_date'][0]))?$order_meta['_paid_date'][0]:$order_date;
				}
				
				if($s_odf == 'date_of_sync'){
					$wc_inv_date = $this->now('Y-m-d');
				}
				
				$invoice_data['wc_inv_date'] = $wc_inv_date;
				$invoice_data['order_date'] = $order_date;
				
				$invoice_data['customer_note'] = $order->post_excerpt;
				$invoice_data['order_status'] = $order->post_status;
				
				$wc_cus_id = isset($order_meta['_customer_user'][0])?(int) $order_meta['_customer_user'][0]:0;
				$invoice_data['wc_cus_id'] = $wc_cus_id;
				#Wc user role
				$wc_user_role = ($wc_cus_id > 0)?$this->get_wc_user_role_by_id($wc_cus_id):'wc_guest_user';
				$invoice_data['wc_user_role'] = $wc_user_role;
				
				if(!empty($order_meta)){
					foreach ($order_meta as $key => $value){
						$invoice_data[$key] = ($value[0])?$value[0]:'';
					}
				}
				
				$cf_map_data = array();
				
				$wc_oi_table = $wpdb->prefix.'woocommerce_order_items';
				$wc_oi_meta_table = $wpdb->prefix.'woocommerce_order_itemmeta';
				
				$order_items = $this->get_data($wpdb->prepare("SELECT * FROM {$wc_oi_table} WHERE `order_id` = %d ORDER BY order_item_id ASC ",$order_id));
				$line_items = $used_coupons = $tax_details = $shipping_details = array();
				
				$dc_gt_fees = array();
				$pw_gift_card = array();
				$gift_card = array();
				
				if(is_array($order_items) && !empty($order_items)){
					foreach($order_items as $oi){
						$order_item_id = (int) $oi['order_item_id'];
						$oi_meta = $this->get_data($wpdb->prepare("SELECT * FROM {$wc_oi_meta_table} WHERE `order_item_id` = %d ",$order_item_id));
						
						$om_arr = array();
						if(is_array($oi_meta) && count($oi_meta)){
							foreach($oi_meta as $om){
								$om_arr[$om['meta_key']] = $om['meta_value'];
							}
						}

						$om_arr['name'] = $oi['order_item_name'];
						$om_arr['type'] = $oi['order_item_type'];

						if($oi['order_item_type']=='line_item'){
							$om_arr['order_item_id'] = $order_item_id;
							$line_items[] = $om_arr;
						}

						if($oi['order_item_type']=='coupon'){
							$used_coupons[] = $om_arr;
						}
						
						if($oi['order_item_type']=='shipping'){
							if(isset($om_arr['name'])){
								$om_arr['name'] = $this->get_array_isset($om_arr,'name');
							}
							$shipping_details[] = $om_arr;
						}						
						
						if($oi['order_item_type']=='tax'){
							if(isset($om_arr['label'])){
								$om_arr['label'] = $this->get_array_isset($om_arr,'label');
							}
							$tax_details[] = $om_arr;
						}
						
						if($oi['order_item_type']=='fee' || $oi['order_item_type'] == 'shipping_option'){
							if(isset($om_arr['name'])){
								$om_arr['name'] = $this->get_array_isset($om_arr,'name');
							}							
							
							if($oi['order_item_type'] == 'shipping_option'){
								$om_arr['_line_total'] = $om_arr['cost'];
								$om_arr['_line_tax'] = $om_arr['tax_amount'];
								if($om_arr['total_tax']){
									$om_arr['_line_tax'] = $om_arr['total_tax'];
								}
							}
							
							$dc_gt_fees[] = $om_arr;
						}
						
						# Gift card will be added later
					}
				}
				
				$xero_inv_items = array();
				if(is_array($line_items) && !empty($line_items)){
					foreach ( $line_items as $item ) {
						$product_data = array();
					
						foreach($item as $key=>$val){
							if($this->start_with($key,'_')  && $key != '_qty'){
								$key = substr($key,1);
							}
							
							$product_data[$key] = $val;
						}
						
						$_qty = abs($product_data['_qty']);
						if(!$_qty){$_qty = 1;}
						$product_data['_qty'] = $_qty;
						
						$l_up = ($product_data['line_subtotal']/$product_data['_qty']);
						$product_data['unit_price'] = $l_up;
						
						$xero_inv_items[] = $this->get_mapped_xero_items_from_wc_items($product_data,$invoice_data,$cf_map_data);
					}
				}
				
				$invoice_data['used_coupons'] = $used_coupons;

				$order_shipping_total = isset($order_meta['_order_shipping'][0])?$order_meta['_order_shipping'][0]:0;
				$invoice_data['shipping_details'] = $shipping_details;
				$invoice_data['order_shipping_total'] = $order_shipping_total;

				$invoice_data['tax_details'] = $tax_details;

				$invoice_data['xero_inv_items'] = $xero_inv_items;
				
				$invoice_data['dc_gt_fees'] = $dc_gt_fees;
				
				#$invoice_data['pw_gift_card'] = $pw_gift_card;				
				#$invoice_data['gift_card'] = $gift_card;
				
				$invoice_data['manual'] = $manual;
			}
		}
		
		return $invoice_data;
	}
	
	public function get_mapped_xero_items_from_wc_items($wc_items=array(),$invoice_data=array(),$cf_map_data=array()){
		$xero_items = array();
		if(is_array($wc_items) && !empty($wc_items)){
			$map_data = array();
			global $wpdb;
			
			$wc_variation_id = (isset($wc_items['variation_id']))?(int) $wc_items['variation_id']:0;
			$wc_product_id = (isset($wc_items['product_id']))?(int) $wc_items['product_id']:0;
			
			if(empty($map_data)){				
				if($wc_variation_id > 0){
					$map_data = $this->get_row($wpdb->prepare("SELECT `X_P_ID` AS ItemID FROM `".$this->gdtn('map_variations')."` WHERE `W_V_ID` = %d AND `X_P_ID` !='' ",$wc_variation_id));
				}
			}
			
			if(empty($map_data) && $wc_product_id > 0){				
				$map_data = $this->get_row($wpdb->prepare("SELECT `X_P_ID` AS ItemID FROM `".$this->gdtn('map_products')."` WHERE `W_P_ID` = %d AND `X_P_ID` !='' ",$wc_product_id));				
			}
			
			$xero_item_id = '';
			$xero_product_data = array();

			# Category -> Product Map
			if(empty($map_data)){
				if($wc_product_id > 0){
					$terms = get_the_terms ( (int) $wc_product_id, 'product_cat' );
					if(!empty($terms)){
						foreach ( $terms as $term ) {
							$cat_map_data = $this->get_row_by_val($this->gdtn('map_categories'),'W_CAT_ID',$term->term_id);
							if(is_array($cat_map_data) && !empty($cat_map_data)){
								if(!empty($cat_map_data['X_P_ID'])){
									$map_data['ItemID'] = $cat_map_data['X_P_ID'];
									break;
								}
							}
						}
					}
				}
			}

			if(!empty($map_data)){
				$xero_item_id = $map_data['ItemID'];
				$xero_item_id = $this->sanitize($xero_item_id);								
			}			
			
			if(empty($map_data)){
				$xero_item_id = $this->get_option('mw_wc_xero_sync_default_xero_product');
				$xero_item_id = $this->sanitize($xero_item_id);							
			}

			if(!empty($xero_item_id)){
				$xero_product_data = $this->get_row_by_val($this->gdtn('products'),'ItemID',$xero_item_id);
			}

			if(empty($xero_product_data)){
				#return $wc_items;
				return array();
			}
			
			$wc_pv_id = ($wc_variation_id > 0)?$wc_variation_id:$wc_product_id;
			
			$Description = $this->get_array_isset($wc_items,'name','');
			
			$s_olidfv = $this->get_option('mw_wc_xero_sync_order_line_item_desc_val_s');
			
			if($s_olidfv  == 'wc_pv_short_desc' || $s_olidfv  == 'xero_p_desc'){
				$dfv = ($s_olidfv  == 'wc_pv_short_desc')?'post_excerpt':'post_content';
				$Description = $this->get_field_by_val($wpdb->posts,$dfv,'ID',$wc_pv_id);
				
				if(empty($Description) && $wc_variation_id > 0){
					$Description = $this->get_field_by_val($wpdb->posts,$dfv,'ID',$wc_product_id);
				}
			}
			
			if($s_olidfv  == 'wc_pv_backorder_s'){
				$pbs = get_post_meta($wc_pv_id,'_backorders',true);
				if($pbs == 'yes'){					
					$Description = 'Allow';
				}elseif($pbs == 'notify'){
					$Description = 'Allow, but notify customer';
				}else{
					$Description = 'Do not allow';
				}
			}
			
			if($s_olidfv  == 'no_desc'){
				$Description = '';
			}
			
			/*Extra Description*/
			#$o_li_meta_data = '';			
			
			# WooCommerce Product Add-ons
			$pv_adn_arr = array();
			
			# Add WooCommerce Custom Order Line Item Meta Into Xero Line Item Description
			if($this->option_checked('mw_wc_xero_sync_add_w_oli_meta_into_xero_oli')){
				$solm_arr = array(
					'name', '_qty', 'qty',
					'unit_price', 'product_id', 'variation_id',
					'tax_class', 'line_subtotal', 'line_subtotal_tax',
					'line_total', 'line_tax', 'line_tax_data',
					'wc_avatax_rate', 'wc_avatax_code', 'wc_cog_item_cost',
					'wc_cog_item_total_cost', 'reduced_stock', 'type','order_item_id',
					'tmcartepo_data', 'vpc-cart-data', '_order_item_wh',
					
					'line_subtotal_base_currency', 'line_total_base_currency', 'line_tax_base_currency',
				);
				
				if(is_array($pv_adn_arr) && !empty($pv_adn_arr)){
					foreach($pv_adn_arr as $paa){
						$solm_arr[] = $paa;
					}
				}
				
				# Only Add Specific Line Item Metas Into Xero Line Item Description
				$oaslim_a = array();
				$oaslim = $this->get_option('mw_wc_xero_sync_only_add_slim_into_xero_oli');
				if(!empty($oaslim)){
					$oaslim_a = explode(',',$oaslim);
					if(is_array($oaslim_a) && !empty($oaslim_a)){
						$oaslim_a = array_map('trim',$oaslim_a);
					}
				}
				
				$ext_olim_d = '';
				foreach($wc_items as $wk => $wv){
					if(empty($wv)){continue;}
					
					$is_olim_lid_add = true;
					if(in_array($wk,$solm_arr)){
						$is_olim_lid_add = false;
					}					
					
					if(!empty($oaslim_a) && !in_array($wk,$oaslim_a)){
						$is_olim_lid_add = false;
					}
					
					$is_va_pa_olim = false;
					if($wc_variation_id && $this->start_with($wk,'pa_')){						
						$is_va_pa_olim = true;
					}
					
					if($is_olim_lid_add && !$is_va_pa_olim){
						$olim_csd = @unserialize($wv);
						if ($wv === 'b:0;' || $olim_csd !== false) {
							$is_olim_lid_add = false;							
							$is_sv_a = false;
							if($wk == 'product_attributes'){
								$is_sv_a = true;
								
							}
							
							if($is_sv_a){
								if(is_array($olim_csd) && !empty($olim_csd)){									
									$iaa = false;
									if(array_keys($olim_csd) !== range(0, count($olim_csd) - 1)){
										$iaa = true;										
									}
									
									foreach($olim_csd as $sak => $sav){
										$sav = trim($sav);
										if($iaa){
											$sak = ucfirst(str_replace('_',' ',$sak));	
											$ext_olim_d.=$sak.': '.$sav.PHP_EOL;
										}else{
											$ext_olim_d.=$sav.PHP_EOL;
										}
									}
								}
							}							
						}
					}
					
					if($is_olim_lid_add){						
						if($is_va_pa_olim){
							$eolm_k = wc_attribute_label($wk);
						}else{
							$eolm_k = ucfirst(str_replace('_',' ',$wk));
						}
						
						$eolm_v = trim($wv);
						if($is_va_pa_olim){							
							$eolm_v = ucfirst(str_replace('-',' ',$wv));
						}
						
						$ext_olim_d.=$eolm_k.': '.$eolm_v.PHP_EOL;
						
					}
				}
				
				if(!empty($ext_olim_d)){
					$Description.=PHP_EOL.$ext_olim_d;
				}
				
				# SKU
				$is_lim_sku_ad = true;
				if(!empty($oaslim_a) && !in_array('sku',$oaslim_a)){
					$is_lim_sku_ad = false;
				}
				
				$lim_sku = '';
				if($is_lim_sku_ad){
					$lim_sku = $this->get_field_by_val($wpdb->prefix.'wc_product_meta_lookup','sku','product_id',$wc_pv_id);
				}
				
				if(!empty($lim_sku)){
					$Description.=PHP_EOL.'SKU: '.$lim_sku;
				}
			}
			
			$Description = str_replace(PHP_EOL . PHP_EOL,PHP_EOL,$Description);
			$Description = $this->get_array_isset(array('Description'=>$Description),'Description','',false);
			
			if(is_array($xero_product_data) && !empty($xero_product_data)){
				$xero_items_tmp = array();
				$xero_items_tmp['Description'] = $Description;
				$xero_items_tmp['UnitPrice'] = $wc_items['unit_price'];
				$xero_items_tmp['Qty'] = $wc_items['_qty'];
				
				# Xero Product Data
				$X_P_L_D = $this->get_xero_product_line_item_data($xero_product_data['ItemID'],$xero_product_data);
				$xero_items_tmp['X_ItemID'] = $xero_product_data['ItemID'];

				$xero_items_tmp['X_Code'] = $X_P_L_D['Code'];	
				$xero_items_tmp['X_IsTrackedAsInventory'] = $X_P_L_D['IsTrackedAsInventory'];

				$xero_items_tmp['X_SD_AccountCode'] = $X_P_L_D['SD_AccountCode'];
				$xero_items_tmp['X_SD_TaxType'] = $X_P_L_D['SD_TaxType'];
				
				$xero_items_tmp['Taxed'] = ($wc_items['line_tax']>0)?1:0;
				
				$xero_items = $xero_items_tmp;
				foreach($wc_items as $k => $val){
					if($k!='name' && $k!='_qty' && $k!='unit_price'){
						$xero_items[$k] = $val;
					}					
				}
			}			
		}
		
		return $xero_items;
	}
	
	# Get Product details by ID
	public function get_wc_product_info($product_id,$_product=null,$manual=false){
		$product_id = (int) $product_id;
		if($product_id>0){
			if(is_null($_product)){
				$_product = wc_get_product($product_id);
			}
			
			#$this->_p($_product);
			
			if(!is_object($_product) || empty($_product)){
				return;
			}
			
			$product_meta = get_post_meta($product_id);
			
			$product_data = array();
			
			$woo_version = $this->get_woo_version_number();
			if ( $woo_version >= 3.0 ) {
				$product_data['wc_product_id'] = $_product->get_id();
				$p_data = $_product->get_data();
				$product_data['product_type'] = '';
				$product_data['total_stock'] = '';
				
				$product_data['name'] = $p_data['name'];			
				$product_data['description'] = $p_data['description'];
				$product_data['short_description'] = $p_data['short_description'];
			}else{
				$product_data['wc_product_id'] = $_product->id;
				$product_data['product_type'] = $_product->product_type;
				$product_data['total_stock'] = $_product->total_stock;
				
				$product_data['name'] = $_product->post->post_title;			
				$product_data['description'] = $_product->post->post_content;
				$product_data['short_description'] = $_product->post->post_excerpt;
			}			
			
			if(is_array($product_meta) && count($product_meta)){
				foreach ($product_meta as $key => $value){
					$product_data[$key] = ($value[0])?$value[0]:'';
				}
			}
			
			$product_data['manual'] = $manual;
			return $product_data;	
		}
	}

	# Get Variation details by ID
	public function get_wc_variation_info($variation_id,$_variation=null,$manual=false){
		$variation_id = (int) $variation_id;
		if($variation_id>0){
			if(is_null($_variation)){
				$_variation = get_post($variation_id);
			}
			
			#$this->_p($_variation);

			if(!is_object($_variation) || empty($_variation)){
				return;
			}
			
			$variation_meta = get_post_meta($variation_id);
			
			$variation_data = array();
			$variation_data['wc_variation_id'] = $_variation->ID;
			$variation_data['wc_product_id'] = $variation_data['wc_variation_id'];

			$variation_data['name'] = $this->get_variation_name_from_id($_variation->post_title,'',$variation_id);
			$variation_data['name_t'] = $this->get_woo_v_name_trimmed($variation_data['name']);

			$variation_data['description'] = $_variation->post_content;
			$variation_data['short_description'] = $_variation->post_excerpt;

			if(is_array($variation_meta) && count($variation_meta)){
				foreach ($variation_meta as $key => $value){
					$variation_data[$key] = ($value[0])?$value[0]:'';
				}
			}

			$variation_data['is_variation'] = true;
			$variation_data['manual'] = $manual;
			return $variation_data;
		}
	}
	
	# Xero order sync as
	public function get_xero_order_sync_as($order_id=0,$invoice_data=null){
		$xosa = 'Invoice';
		$s_order_sync_as = $this->get_option('mw_wc_xero_sync_order_sync_as');
		if(!empty($s_order_sync_as)){
			$osa_arr = $this->get_xosa_arr();
			if(in_array($s_order_sync_as,$osa_arr)){
				$xosa = $s_order_sync_as;
			}else{
				$order_id = (int) $order_id;
				if($s_order_sync_as == 'Per Role' || $s_order_sync_as == 'Per Gateway' && $order_id > 0){
					if(is_null($invoice_data)){
						$wc_cus_id = (int) get_post_meta($order_id,'_customer_user',true);
						$wc_user_role = ($wc_cus_id > 0)?$this->get_wc_user_role_by_id($wc_cus_id):'wc_guest_user';

						$_payment_method = get_post_meta($order_id,'_payment_method',true);
						$_order_currency = get_post_meta($order_id,'_order_currency',true);

						$invoice_data = array(
							'wc_user_role' => $wc_user_role,
							'_payment_method' => $_payment_method,
							'_order_currency' => $_order_currency,
						);
					}

					if(!is_array($invoice_data) || empty($invoice_data)){
						return $xosa;
					}

					if($s_order_sync_as == 'Per Role'){
						$wc_user_role = $this->get_array_isset($invoice_data,'wc_user_role','');
						
						if(!empty($wc_user_role)){
							$osa_pr_map_data = get_option('mw_wc_xero_sync_osa_pr_map_data');
							if(is_array($osa_pr_map_data) && !empty($osa_pr_map_data)){
								if(isset($osa_pr_map_data[$wc_user_role]) && !empty($osa_pr_map_data[$wc_user_role])){
									if(in_array($osa_pr_map_data[$wc_user_role],$osa_arr)){
										$xosa = $osa_pr_map_data[$wc_user_role];
									}
								}
							}
						}						
					}
					
					if($s_order_sync_as == 'Per Gateway'){
						$_payment_method = $this->get_array_isset($invoice_data,'_payment_method','');
						$_order_currency = $this->get_array_isset($invoice_data,'_order_currency','');
						if(!empty($_payment_method) && !empty($_order_currency)){
							$pm_map_data = $this->get_mapped_payment_method_data($_payment_method,$_order_currency);
							$pg_order_sync_as = $this->get_array_isset($pm_map_data,'order_sync_as','');
							if(in_array($pg_order_sync_as,$osa_arr)){
								$xosa = $pg_order_sync_as;
							}
						}
					}
				}
			}
		}

		return $xosa;
	}
	
	# Xero Data Checking
	#-> This function is for both wc products and variations
	public function if_xero_product_exists($product_data, $omc=false){
		if(is_array($product_data) && !empty($product_data)){
			$nr_chars = $this->x_nrc('product');
			global $wpdb;
			
			$wc_product_id = (int) $this->get_array_isset($product_data,'wc_product_id',0,false);
			
			$is_variation = $this->get_array_isset($product_data,'is_variation',false,false);
			$ItemID = '';
			
			$map_tbl = ($is_variation)?$this->gdtn('map_variations'):$this->gdtn('map_products');
			$w_p_f = ($is_variation)?'W_V_ID':'W_P_ID';
			
			$query = $wpdb->prepare("SELECT `X_P_ID` FROM `{$map_tbl}` WHERE `{$w_p_f}` = %d AND `X_P_ID` !='' AND `{$w_p_f}` > 0 ",$wc_product_id);
			
			$query_product = $this->get_row($query);
			if(!empty($query_product)){
				$ItemID =  $query_product['X_P_ID'];
			}
			
			# Only map check
			if($omc){
				return $ItemID;
			}
			
			$pvnf = (isset($product_data['name_t']) && !empty($product_data['name_t']))?'name_t':'name';

			$name = $this->get_array_isset($product_data,$pvnf,'',true,50,false,$nr_chars);
			$sku = $this->get_array_isset($product_data,'_sku','',true,30);
			
			if(empty($ItemID) && !empty($sku)){
				$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Code',$sku);
			}
			
			# Wc ID -> Xero Code
			if(empty($ItemID) && $wc_product_id > 0){
				$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Code',$wc_product_id);
			}
			
			# Name
			if(empty($ItemID) && !empty($name)){				
				$ItemID = $this->get_field_by_val($this->gdtn('products'),'ItemID','Name',$name);
			}
			
			return $ItemID;
		}
		
		return false;
	}
	
	public function if_xero_customer_exists($customer_data,$omc=false,$cbn=false,$rt_check=true,$r_obj=false){
		if(is_array($customer_data) && !empty($customer_data)){
			$nr_chars = $this->x_nrc('customer');
			global $wpdb;
			
			$wc_cus_id = (int) $this->get_array_isset($customer_data,'wc_cus_id',0,false);
			$ContactID = '';
			$map_tbl = $this->gdtn('map_customers');
			$w_c_f = 'W_C_ID';
			
			$query = $wpdb->prepare("SELECT `X_C_ID` FROM `{$map_tbl}` WHERE `{$w_c_f}` = %d AND `X_C_ID` !='' AND `{$w_c_f}` > 0 ",$wc_cus_id);
			
			$query_customer = $this->get_row($query);
			if(!empty($query_customer)){
				$ContactID =  $query_customer['X_C_ID'];
			}
			
			# Only map check
			if($omc){
				return $ContactID;
			}
			
			$email = $this->get_array_isset($customer_data,'email','',true,255,false,$nr_chars);
			$full_name = $this->get_array_isset($customer_data,'full_name','',true,255,false,$nr_chars);
			
			$name = $this->get_customer_formated_display_name_for_xs($customer_data,$full_name);
			
			if(empty($ContactID) && !empty($email)){
				$ContactID = $this->get_field_by_val($this->gdtn('customers'),'ContactID','EmailAddress',$email);
			}
			
			if(empty($ContactID) && $cbn && !empty($name)){
				$ContactID = $this->get_field_by_val($this->gdtn('customers'),'ContactID','Name',$name);
			}

			# Remote Check
			if(empty($ContactID) && $rt_check){
				$name_p = ($cbn)?$name:'';
				$ContactID = $this->get_xero_customer_by_email_or_name($email,$name_p);
			}
			
			return $ContactID;
			
		}
	}
	
	public function if_xero_guest_exists($customer_data,$cbn=false,$rt_check=true,$r_obj=false){
		if(is_array($customer_data) && !empty($customer_data)){
			$nr_chars = $this->x_nrc('customer');
			global $wpdb;

			$ContactID = '';

			$email = $this->get_array_isset($customer_data,'email','',true,255,false,$nr_chars);
			$full_name = $this->get_array_isset($customer_data,'full_name','',true,255,false,$nr_chars);

			$name = $this->get_customer_formated_display_name_for_xs($customer_data,$full_name);			
			if((!$cbn && empty($email)) || ($cbn && empty($name) && empty($email))){
				return $ContactID;
			}

			#-> DB Check If Needed

			# Remote Check
			if(empty($ContactID) && $rt_check){
				$name_p = ($cbn)?$name:'';
				$ContactID = $this->get_xero_customer_by_email_or_name($email,$name_p);
			}			

			return $ContactID;
		}
	}

	public function check_save_get_xero_customer_id($customer_data){
		if(is_array($customer_data) && !empty($customer_data)){
			$xero_contact_id = (string) $this->if_xero_customer_exists($customer_data);
			if(!empty($xero_contact_id)){
				return $xero_contact_id;
			}

			$xero_contact_id = $this->X_Add_Customer($customer_data);
			if($xero_contact_id && strlen($xero_contact_id) == '36'){
				return $xero_contact_id;
			}
		}

		return '';
	}

	public function check_save_get_xero_guest_id($customer_data){
		if(is_array($customer_data) && !empty($customer_data)){
			$xero_contact_id = (string) $this->if_xero_guest_exists($customer_data);
			if(!empty($xero_contact_id)){
				return $xero_contact_id;
			}

			$xero_contact_id = $this->X_Add_Guest($customer_data);
			if($xero_contact_id && strlen($xero_contact_id) == '36'){
				return $xero_contact_id;
			}
		}
	}

	# Get xero customer ID by email or name (if not found by email)
	public function get_xero_customer_by_email_or_name($email,$name=''){
		$ContactID = '';
		if($this->is_xero_connected() && !empty($email)){
			$if_modified_since = $this->get_x_f_ms_filter_datetime();	
			#IsSupplier==FALSE IsCustomer==TRUE
			$X_Whr = 'EmailAddress ==  "'.$email.'" AND ContactStatus=="ACTIVE"';

			$result = $this->X_API_I()->getContacts($this->XeroTenantId,$if_modified_since,$X_Whr,null,null,1);
			if(!empty($result)){
				$contacts = $result->getContacts();				
				if(is_array($contacts) && !empty($contacts)){
					$ContactID = $contacts[0]->getContactId();
				}
			}

			if(empty($ContactID) && !empty($name)){
				$ContactID = $this->get_xero_customer_by_name($name);
			}
		}

		return $ContactID;
	}
	
	# Get xero customer ID by name
	public function get_xero_customer_by_name($name,$active=true){
		$ContactID = '';
		if($this->is_xero_connected() && !empty($name)){
			$if_modified_since = $this->get_x_f_ms_filter_datetime();
			if($active){
				$X_Whr = 'Name ==  "'.$name.'" AND ContactStatus=="ACTIVE"';
			}else{
				# For append ID check
				$X_Whr = 'Name ==  "'.$name.'"';
			}
				
			$result = $this->X_API_I()->getContacts($this->XeroTenantId,$if_modified_since,$X_Whr,null,null,1);
			if(!empty($result)){
				$contacts = $result->getContacts();				
				if(is_array($contacts) && !empty($contacts)){
					$ContactID = $contacts[0]->getContactId();
				}
			}
		}

		return $ContactID;
	}
	
	# Get xero customer ID when syncing order
	public function get_xero_customer_for_order_sync($invoice_data){
		$X_ContactID = '';
		#return 'b1946ff6-e266-4be1-a061-592041f13fc6'; # Test
		if(is_array($invoice_data) && !empty($invoice_data)){
			$wc_inv_id = (int) $this->get_array_isset($invoice_data,'wc_inv_id',0,false);
			$wc_cus_id = (int) $this->get_array_isset($invoice_data,'wc_cus_id',0,false);
			$wc_user_role = $this->get_array_isset($invoice_data,'wc_user_role','');

			if(empty($X_ContactID)){
				if($this->option_checked('mw_wc_xero_sync_s_all_orders_to_one_xero_customer')){
					if(!empty($wc_user_role)){
						$aotc_rcm_data = get_option('mw_wc_xero_sync_aotc_rcm_data');
						if(isset($aotc_rcm_data[$wc_user_role]) && !empty($aotc_rcm_data[$wc_user_role])){
							if($aotc_rcm_data[$wc_user_role] != 'Individual'){
								$X_ContactID = $aotc_rcm_data[$wc_user_role];
							}
						}
					}					
				}else{
					if($wc_cus_id > 0){
						$customer_data = $this->get_wc_customer_info($wc_cus_id);
						$customer_data['order_id'] = $wc_inv_id;
						$X_ContactID = $this->if_xero_customer_exists($customer_data);
					}else{
						$customer_data = $this->get_wc_customer_info_from_order($wc_inv_id);
						$X_ContactID = $this->if_xero_guest_exists($customer_data);
					}			
				}
			}
		}

		return $X_ContactID;
	}

	public function if_xero_order_exists($xosa,$invoice_data){
		if(!empty($xosa) && is_array($invoice_data) && !empty($invoice_data)){
			$osa_arr = $this->get_xosa_arr();
			if(in_array($xosa,$osa_arr)){
				if($xosa == 'Invoice'){
					return $this->if_xero_invoice_exists($invoice_data);
				}

				if($xosa == 'Quote'){
					return $this->if_xero_quote_exists($invoice_data);
				}
			}
		}
		return false;
	}

	public function if_xero_invoice_exists($invoice_data){
		return $this->check_xero_invoice_get_obj($invoice_data,true);
	}
	
	public function check_xero_invoice_get_obj($invoice_data,$r_only_id=false){
		if(is_array($invoice_data) && !empty($invoice_data)){
			$wc_inv_id = (int) $this->get_array_isset($invoice_data,'wc_inv_id',0,false);
			if($wc_inv_id > 0){
				if($this->is_xero_connected()){
					$wc_inv_num = $this->get_array_isset($invoice_data,'wc_inv_num','',true,255);
					# Next Xero Order Number
					if($this->use_next_xero_order_number()){
						$n_x_o_n = $this->get_next_xero_order_number($invoice_data['wc_inv_id']);
						if(empty($n_x_o_n)){
							#return false;
						}else{
							$wc_inv_num = $n_x_o_n;
						}					
					}
					
					$InvoiceNumber = (!empty($wc_inv_num))?$wc_inv_num:$wc_inv_id;
					# Reference
					# ACCREC -> sales invoice
					$if_modified_since = $this->get_x_f_ms_filter_datetime();
					$where = 'InvoiceNumber=="'.$InvoiceNumber.'" AND Type=="ACCREC" AND Status != "DELETED"';
					$result = $this->X_API_I()->getInvoices($this->XeroTenantId,$if_modified_since,$where,null,null,null,null,null,1,null,null,null,false);
					$invoices = $result->getInvoices();
					if(is_array($invoices) && !empty($invoices)){
						if($r_only_id){
							return $invoices[0]->getInvoiceID();
						}

						return $invoices[0];
					}
				}
			}
		}

		return false;
	}

	public function if_xero_quote_exists($invoice_data){
		
	}

	public function check_xero_quote_get_obj($invoice_data,$r_only_id=false){

	}

	public function if_xero_payment_exists($invoice_data,$xero_invoice_object){
		return $this->check_xero_payment_get_obj($invoice_data,$xero_invoice_object,true);
	}

	public function check_xero_payment_get_obj($invoice_data,$xero_invoice_object,$r_only_id=false){
		if(is_array($invoice_data) && !empty($invoice_data) && is_object($xero_invoice_object) && !empty($xero_invoice_object)){
			$wc_inv_id = (int) $this->get_array_isset($invoice_data,'wc_inv_id',0,false);
			if($wc_inv_id > 0){
				if($this->is_xero_connected()){
					$xero_invoice_id = $xero_invoice_object->getInvoiceID();
					if(!empty($xero_invoice_id)){
						$if_modified_since = $this->get_x_f_ms_filter_datetime();
						$where = 'Invoice.InvoiceID==GUID("'.$xero_invoice_id.'")  AND Status != "DELETED"';
						$result = $this->X_API_I()->getPayments($this->XeroTenantId,$if_modified_since,$where,null,1);
						$payments = $result->getPayments();
						if(is_array($payments) && !empty($payments)){
							if($r_only_id){
								return $payments[0]->getPaymentId();
							}
	
							return $payments[0];
						}
					}
				}
			}
		}

		return false;
	}
	
	# Xero Data Functions
	public function xero_refresh_customers(){
		$tci = 0;		
		if($this->is_xero_connected()){
			$if_modified_since = $this->get_x_f_ms_filter_datetime();
			$page = 1;
			$is_next_request_needed = false;

			do{
				$is_next_request_needed = false;
				$result = $this->X_API_I()->getContacts($this->XeroTenantId,$if_modified_since,'ContactStatus=="ACTIVE"',null,null,$page);
				if(!empty($result)){
					$contacts = $result->getContacts();				
					if(is_array($contacts) && !empty($contacts)){
						global $wpdb;
						$tbl = $this->gdtn('customers');
						
						if($page == 1){
							$wpdb->query('DELETE FROM '.$tbl.' WHERE id > 0');
							$wpdb->query('TRUNCATE TABLE '.$tbl);
						}						
						
						foreach($contacts as $Contact){
							if($this->save_xero_customer_into_local_dbt($Contact)){
								$tci++;
							}
						}
						
						if(count($contacts) == 100){
							$is_next_request_needed = true;
							$page ++;
						}
					}
				}
			}while($is_next_request_needed);
		}
		
		return $tci;
	}
	
	public function xero_refresh_products(){		
		$tpi = 0;		
		if($this->is_xero_connected()){			
			$result = $this->X_API_I()->getItems($this->XeroTenantId);
			if(!empty($result)){
				$items = $result->getItems();				
				if(is_array($items) && !empty($items)){
					global $wpdb;
					$tbl = $this->gdtn('products');
					
					#No batch query support
					$wpdb->query('DELETE FROM '.$tbl.' WHERE id > 0');
					$wpdb->query('TRUNCATE TABLE '.$tbl);
					
					foreach($items as $Item){
						if($this->save_xero_product_into_local_dbt($Item)){
							$tpi++;
						}						
					}					
				}
			}			
		}
		
		return $tpi;
	}
	
	public function xero_get_accounts_kva(){
		if($this->is_xero_connected()){			
			$Accounts = $this->X_API_I()->getAccounts($this->XeroTenantId,null,null,null);
			if(!empty($Accounts) && is_array($Accounts->getAccounts()) && !empty($Accounts->getAccounts())){
				$X_Accounts_Kva = array();
				foreach($Accounts->getAccounts() as $Account){
					$xakfv = $Account->getCode();					
					
					if(empty($xakfv) || $Account->getType() == 'BANK'){
						$xakfv = $Account->getAccountID();
					}

					$X_Accounts_Kva[$xakfv] = $Account->getName().' ('.ucfirst(strtolower($Account->getType())).')';
				}
				return $X_Accounts_Kva;
			}
		}
		
		return array();
	}
	
	public function xero_get_tax_rates_kva(){
		if($this->is_xero_connected()){
			$TaxRates = $this->X_API_I()->getTaxRates($this->XeroTenantId,null,null,null);
			if(!empty($TaxRates) && is_array($TaxRates->getTaxRates()) && !empty($TaxRates->getTaxRates())){
				$X_TaxRates_Kva = array();
				foreach($TaxRates->getTaxRates() as $TaxRate){					
					$X_TaxRates_Kva[$TaxRate->getTaxType()] = $TaxRate->getName().' ('.$TaxRate->getDisplayTaxRate().'%)';
				}
				return $X_TaxRates_Kva;
			}
		}
		
		return array();
	}
	
	# Data map / save after sync (push / pull)
	protected function save_xero_product_into_local_dbt($Item){
		if(is_object($Item) && !empty($Item)){
			global $wpdb;
			$tbl = $this->gdtn('products');
			
			$SD = array(
				'ItemID' => $Item->getItemID(),
				'Name' => $Item->getName(),
				'Code' => $Item->getCode(),
				'Description' => $Item->getDescription(),
				'PurchaseDescription' => $Item->getPurchaseDescription(),
				'IsTrackedAsInventory' => ($Item->getIsTrackedAsInventory())?1:0,
				'UnitPrice' => (float) $Item->getSalesDetails()->getUnitPrice(),						
			);
			
			$SD = $this->arr_nts($SD);
			
			$X_Data = array(
				'IsSold' => $Item->getIsSold(),
				'IsPurchased' => $Item->getIsPurchased(),
				'P_UnitPrice' => (float) $Item->getPurchaseDetails()->getUnitPrice(),
				
				'SD_AccountCode' => $Item->getSalesDetails()->getAccountCode(),
				'SD_TaxType' => $Item->getSalesDetails()->getTaxType(),								
				'SD_CogsAccountCode' => $Item->getSalesDetails()->getCogsAccountCode(),
				
				'PD_AccountCode' => $Item->getPurchaseDetails()->getAccountCode(),
				'PD_TaxType' => $Item->getPurchaseDetails()->getTaxType(),								
				'PD_CogsAccountCode' => $Item->getPurchaseDetails()->getCogsAccountCode(),
			);
			
			$X_Data = $this->arr_nts($X_Data);
			
			$SD['X_Data'] = serialize($X_Data);
			
			if(!empty($SD['ItemID'])){
				$r = $wpdb->insert($tbl,$SD);				
				return $r;
			}
		}
		
		return false;
	}
	
	protected function save_xero_customer_into_local_dbt($Contact){
		if(is_object($Contact) && !empty($Contact)){
			global $wpdb;
			$tbl = $this->gdtn('customers');
			
			$SD = array(
				'ContactID' => $Contact->getContactID(),
				'ContactStatus' => $Contact->getContactStatus(),
				'AccountNumber' => $Contact->getAccountNumber(),
				'CompanyNumber' => $Contact->getCompanyNumber(),
				'FirstName' => $Contact->getFirstName(),
				'LastName' => $Contact->getLastName(),
				'Name' => $Contact->getName(),
				'EmailAddress' => $Contact->getEmailAddress(),
				'DefaultCurrency' => $Contact->getDefaultCurrency(),
			);
			
			$Phones = $Contact->getPhones();
			if(is_array($Phones) && !empty($Phones)){
				foreach($Phones as $Phone){
					if($Phone->getPhoneType() == 'MOBILE' && !empty($Phone->getPhoneNumber())){
						$SD['Mobile'] = $Phone->getPhoneNumber();
						break;
					}
				}
			}
			
			$SD = $this->arr_nts($SD);
			
			$X_Data = array(
				'HasAttachments' => $Contact->getHasAttachments(),							
				#'BankAccountDetails' => $Contact->getBankAccountDetails(),
				'Website' => $Contact->getWebsite(),
				'IsCustomer' => $Contact->getIsCustomer(),
				'IsSupplier' => $Contact->getIsSupplier(),						
			);
			
			$X_Data = $this->arr_nts($X_Data);
			
			$SD['X_Data'] = serialize($X_Data);						
			if(!empty($SD['ContactID'])){
				$r = $wpdb->insert($tbl,$SD);				
				return $r;				
				#echo $wpdb->last_error;
			}
		}
		
		return false;
	}
	
	protected function save_xero_product_variation_map($wc_pv_id,$X_P_ID,$is_variation=false,$pull=false){
		$wc_pv_id = intval($wc_pv_id);
		$X_P_ID = trim($X_P_ID);
		
		if($wc_pv_id > 0 && !empty($X_P_ID)){
			$table = (!$is_variation)?$this->gdtn('map_products'):$this->gdtn('map_variations');
			global $wpdb;
			
			$save_data = array();
			$wpvf = (!$is_variation)?'W_P_ID':'W_V_ID';
			
			if(!$pull){
				$save_data['X_P_ID'] = $X_P_ID;
				if($this->get_field_by_val($table,'id',$wpvf,$wc_pv_id)){
					$wpdb->update($table,$save_data,array($wpvf=>$wc_pv_id),'',array('%d'));
				}else{
					$save_data[$wpvf] = $wc_pv_id;
					$wpdb->insert($table, $save_data);
				}
			}else{	
				$save_data[$wpvf] = $wc_pv_id;
				if($this->get_field_by_val($table,'id','X_P_ID',$X_P_ID)){
					$wpdb->update($table,$save_data,array('X_P_ID'=>$X_P_ID),'',array('%s'));
				}else{					
					$save_data['X_P_ID'] = $X_P_ID;
					$wpdb->insert($table, $save_data);
				}
			}
		}
	}

	protected function save_xero_customer_map($wc_cus_id,$X_C_ID,$is_supplier=false,$pull=false){
		$wc_cus_id = intval($wc_cus_id);
		$X_C_ID = trim($X_C_ID);
		
		if($wc_cus_id > 0 && !empty($X_C_ID)){
			$table = (!$is_supplier)?$this->gdtn('map_customers'):'';
			if(empty($table)){
				return;
			}

			global $wpdb;
			
			$save_data = array();
			$wcvf = 'W_C_ID';
			
			if(!$pull){
				$save_data['X_C_ID'] = $X_C_ID;
				if($this->get_field_by_val($table,'id',$wcvf,$wc_cus_id)){
					$wpdb->update($table,$save_data,array($wcvf=>$wc_cus_id),'',array('%d'));
				}else{
					$save_data[$wcvf] = $wc_cus_id;
					$wpdb->insert($table, $save_data);
				}
			}else{
				$save_data[$wcvf] = $wc_cus_id;
				if($this->get_field_by_val($table,'id','X_C_ID',$X_C_ID)){
					$wpdb->update($table,$save_data,array('X_C_ID'=>$X_C_ID),'',array('%d'));
				}else{
					$save_data['X_C_ID'] = $X_C_ID;
					$wpdb->insert($table, $save_data);
				}
			}
		}
	}

	# Order Sync Status List
	public function get_order_sync_status_list($order_id_num_arr){
		$s_order_sync_as = $this->get_option('mw_wc_xero_sync_order_sync_as');
		if($s_order_sync_as != 'Invoice'){
			return array();
		}

		if($this->is_xero_connected()){
			if(is_array($order_id_num_arr) && !empty($order_id_num_arr)){
				$a_c_s = 50;
				if(count($order_id_num_arr) > $a_c_s){
					$oina_n = array_chunk($order_id_num_arr,$a_c_s,true);
				}else{
					$oina_n = array();
					$oina_n[0] = $order_id_num_arr;
				}
				
				$w_x_o_a = array();

				# Invoice
				foreach($oina_n as $v_arr){
					# Filter Loop
					foreach($v_arr as $k => $v){
						$order_sync_as = $this->get_xero_order_sync_as($k);						
						if($order_sync_as != 'Invoice'){
							unset($v_arr[$k]);
						}

						# Next Xero Order Number
						if($this->use_next_xero_order_number()){
							$n_x_o_n = $this->get_next_xero_order_number($k);
							if(empty($n_x_o_n)){
								#unset($v_arr[$k]);
							}else{
								$v_arr[$k] = $n_x_o_n;
							}
						}
					}
					
					#$this->_p($v_arr);
					$w_o_i_n = (!empty($v_arr))?implode(',',$v_arr):'';	
					if(!empty($w_o_i_n)){
						$if_modified_since = $this->get_x_f_ms_filter_datetime();					
						$where = 'Type=="ACCREC" AND Status != "DELETED"';
						$result = $this->X_API_I()->getInvoices($this->XeroTenantId,$if_modified_since,$where,null,null,$w_o_i_n,null,null,1,null,null,null,false);			
						$invoices = $result->getInvoices();
						if(is_array($invoices) && !empty($invoices)){
							foreach($invoices as $invoice){
								$InvoiceNumber = $invoice->getInvoiceNumber();
								if(!empty($InvoiceNumber)){
									$key = array_search($InvoiceNumber, $v_arr);
									if ($key !== false) {
										$key = (int) $key;
										#$w_x_o_a[$key] = $invoice->getInvoiceID();
										$w_x_o_a[$key] = array(
											'ID' => $invoice->getInvoiceID(),
											'Type' => 'Invoice',
											'Xero_Link' => $this->get_xero_view_invoice_link_by_id($invoice->getInvoiceID())
										);
									}
								}								
							}
						}
					}
				}
				
				#$this->_p($w_x_o_a);
				$main_arr = $order_id_num_arr;
				$allow_empty = true;
				if($allow_empty || !empty($w_x_o_a)){
					foreach($main_arr as $k => $v){
						if(isset($w_x_o_a[$k]) && !empty($w_x_o_a[$k])){
							$order_id_num_arr[$k] = $w_x_o_a[$k];
						}else{
							$order_id_num_arr[$k] = '';
						}
					}

					return $order_id_num_arr;
				}
			}
		}
		
		return array();
	}

	# Xero Pull Data List Functions
	public function X_Get_Items($StartsWith=''){
		if($this->is_xero_connected()){			
			$order = 'Name ASC';
			$where = null;

			$StartsWith = $this->sanitize($StartsWith);
			if(!empty($StartsWith)){
				$where = 'Name.StartsWith("'.$StartsWith.'")';
			}

			$result = $this->X_API_I()->getItems($this->XeroTenantId,null,$where,$order);
			$items = $result->getItems();
			return $items;
		}
	}
	
	# Xero Sync Functions
	public function X_Add_Product($product_data){
		if(is_array($product_data) && !empty($product_data)){
			if($this->is_xero_connected()){
				if($xero_product_id = $this->if_xero_product_exists($product_data)){
					return false;
				}
				
				$wc_pv_id = (int) $this->get_array_isset($product_data,'wc_product_id',0,false);
				$is_variation = $this->get_array_isset($product_data,'is_variation',false,false);
				$lt = (!$is_variation)?'Product':'Variation';
				
				$_manage_stock = $this->get_array_isset($product_data,'_manage_stock','no');
				$IsTrackedAsInventory = ($_manage_stock == 'yes')?true:false;
						
				$dxsa = $this->get_option('mw_wc_xero_sync_default_xero_sales_account_fnp');

				if($IsTrackedAsInventory){
					$dxcogsa = $this->get_option('mw_wc_xero_sync_default_xero_cogs_account_fnp');
					$dxiaa = $this->get_option('mw_wc_xero_sync_default_xero_inventory_asset_account_fnp');
					if(empty($dxiaa)){
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_pv_id,'details'=>'Xero Inventory Asset Account not set','status'=>0));
						return false;
					}					
					
					if(empty($dxcogsa)){
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_pv_id,'details'=>'Xero COGS Account not set (required for inventory product)','status'=>0));
						return false;
					}					
				}
				
				$nr_chars = $this->x_nrc('product');
				$name = $this->get_array_isset($product_data,'name','',true,50,false,$nr_chars);
				
				$sku = $this->get_array_isset($product_data,'_sku','',true,30);
				$description = $this->get_array_isset($product_data,'description','',true,4000);				
				
				$Item = new XeroAPI\XeroPHP\Models\Accounting\Item;
				$Item->setName($name);
				
				$Code = (!empty($sku))?$sku:$wc_pv_id;
				$Item->setCode($Code);
				$Item->setDescription($description);
				
				$Item->setIsSold(true);
				
				$UnitPrice = $this->get_array_isset($product_data,'_regular_price',0);
				$UnitPrice = str_replace(',','',$UnitPrice);
				$UnitPrice = floatval($UnitPrice);

				#SalesDetails
				$SalesDetails = new XeroAPI\XeroPHP\Models\Accounting\Purchase;
				$SalesDetails->setUnitPrice($UnitPrice);
				if(!empty($dxsa)){
					$SalesDetails->setAccountCode($dxsa);
				}

				$Item->setSalesDetails($SalesDetails);

				#PurchaseDetails
				if($IsTrackedAsInventory && !empty($dxcogsa)){
					$PurchaseDetails = new XeroAPI\XeroPHP\Models\Accounting\Purchase;
					$PurchaseDetails->setCOGSAccountCode($dxcogsa);
					$Item->setPurchaseDetails($PurchaseDetails);
				}
				
				$_stock = (int) $this->get_array_isset($product_data,'_stock',0);
				
				$Item->setIsTrackedAsInventory($IsTrackedAsInventory);
				if($IsTrackedAsInventory){
					$Item->setInventoryAssetAccountCode($dxiaa);
					$Item->setQuantityOnHand($_stock);		
				}
				
				$Arr_Items = array();
				array_push($Arr_Items, $Item);
				
				$Items = new XeroAPI\XeroPHP\Models\Accounting\Items;
				$Items->setItems($Arr_Items);

				#Debug
				#$this->_p($product_data);
				#$this->_p($Item);
				#return;
				
				try{
					$result = $this->X_API_I()->createItems($this->XeroTenantId,$Items,true,$this->x_unitdp());
					#$this->add_text_into_log_file($Item);
					#$this->add_text_into_log_file($result);
					
					if(!empty($result)){
						$xr_items = $result->getItems();
						if(is_array($xr_items) && !empty($xr_items)){
							$x_item = $xr_items[0];
							$X_ItemID = $x_item->getItemID();
							if(!empty($X_ItemID)){							
								$this->save_xero_product_into_local_dbt($x_item);
								$this->save_xero_product_variation_map($wc_pv_id,$X_ItemID,$is_variation);
								
								$ld = "{$lt} #{$wc_pv_id} added into Xero successfully".PHP_EOL;
								$ld.="Xero Item ID #".$X_ItemID;
								
								$this->save_log(
									array(
										'type'=>$lt,
										'title'=>'Create '.$lt.' #'.$wc_pv_id,
										'details'=>$ld,
										'status'=>1,
										'wc_id'=>$wc_pv_id,
										'xero_id'=>$X_ItemID,
										)
								,true);
								return $X_ItemID;
							}
						}
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
					$ld = $this->get_error_message_from_xero_error_object($error);
					if(!empty($ld)){						
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_pv_id,'details'=>$ld,'status'=>0),true);
					}

					return false;
				}				
				
				return false;
			}
		}
	}
	
	public function X_Add_Customer($customer_data){
		if(is_array($customer_data) && !empty($customer_data)){
			if($this->is_xero_connected()){
				if($xero_contact_id = $this->if_xero_customer_exists($customer_data)){
					return false;
				}
				
				$nr_chars = $this->x_nrc('customer');
				$lt = 'Customer';
				
				$wc_cus_id = (int) $this->get_array_isset($customer_data,'wc_cus_id',0,false);
				
				$first_name = $this->get_array_isset($customer_data,'first_name','',true,255,false,$nr_chars);
				$last_name = $this->get_array_isset($customer_data,'last_name','',true,255,false,$nr_chars);
				$email = $this->get_array_isset($customer_data,'email','',true,255,false,$nr_chars);
				
				$full_name = $this->get_array_isset($customer_data,'full_name','',true,255,false,$nr_chars);
				$name = $this->get_customer_formated_display_name_for_xs($customer_data,$full_name);
				
				if(empty($name)){
					$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_cus_id,'details'=>'Customer name is empty','status'=>0));
					return false;
				}

				# Xero duplicate name check
				if($this->option_checked('mw_wc_xero_sync_customer_append_id_if_name_taken',true)){
					$ContactID = $this->get_xero_customer_by_name($name,false);
					if(!empty($ContactID)){
						$name .= '-'.$wc_cus_id;
					}
				}				
				
				$Contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
				
				$Contact->setFirstName($first_name);
				$Contact->setLastName($last_name);
				$Contact->setName($name);
				$Contact->setEmailAddress($email);
				
				$Contact->setIsCustomer(1);

				#Phones
				$Phones = array();
				$billing_phone = $this->get_array_isset($customer_data,'billing_phone','');
				if(!empty($billing_phone)){
					$Phone = new XeroAPI\XeroPHP\Models\Accounting\Phone;
					$Phone->setPhoneType('MOBILE');
					$Phone->setPhoneNumber($billing_phone);
					
					$Phones[] = $Phone;
				}

				if(!empty($Phones)){
					$Contact->setPhones($Phones);
				}
				
				#Addresses
				#-> Billing / POBOX
				$Addresses = array();
				$billing_address_1 = $this->get_array_isset($customer_data,'billing_address_1','',true);
				if(!empty($billing_address_1)){
					$Address = new XeroAPI\XeroPHP\Models\Accounting\Address;

					$Address->setAddressType('POBOX');
					$Address->setAddressLine1($billing_address_1);

					$billing_address_2 = $this->get_array_isset($customer_data,'billing_address_2','',true);
					if(!empty($billing_address_2)){
						$Address->setAddressLine2($billing_address_2);
					}

					$city = $this->get_array_isset($customer_data,'billing_city','',true,255);
					$Address->setCity($city);
					
					$region = $this->get_array_isset($customer_data,'billing_state','',true,255);
					$Address->setRegion($region);

					$postal_code = $this->get_array_isset($customer_data,'billing_postcode','',true,50);
					$Address->setPostalCode($postal_code);

					$country = $this->get_array_isset($customer_data,'billing_country','',true,50);
					$Address->setCountry($country);
					
					$Addresses[] = $Address;
				}
				
				#-> Shipping / STREET
				$shipping_address_1 = $this->get_array_isset($customer_data,'shipping_address_1','',true);
				if(!empty($shipping_address_1)){
					$Address = new XeroAPI\XeroPHP\Models\Accounting\Address;

					$Address->setAddressType('STREET');
					$Address->setAddressLine1($shipping_address_1);

					$shipping_address_2 = $this->get_array_isset($customer_data,'shipping_address_2','',true);
					if(!empty($shipping_address_2)){
						$Address->setAddressLine2($shipping_address_2);
					}

					$city = $this->get_array_isset($customer_data,'shipping_city','',true,255);
					$Address->setCity($city);
					
					$region = $this->get_array_isset($customer_data,'shipping_state','',true,255);
					$Address->setRegion($region);

					$postal_code = $this->get_array_isset($customer_data,'shipping_postcode','',true,50);
					$Address->setPostalCode($postal_code);

					$country = $this->get_array_isset($customer_data,'shipping_country','',true,50);
					$Address->setCountry($country);

					$Addresses[] = $Address;					
				}

				if(!empty($Addresses)){
					$Contact->setAddresses($Addresses);
				}
				
				#DefaultCurrency
				$currency = $this->get_array_isset($customer_data,'currency','',true);
				if(!empty($currency)){
					$Contact->setDefaultCurrency($currency);
				}
				
				#ContactNumber
				if($wc_cus_id > 0){
					#$Contact->setContactNumber($wc_cus_id);
				}

				#CompanyNumber
				
				$Arr_Contacts = array();
				array_push($Arr_Contacts, $Contact);
				
				$Contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
				$Contacts->setContacts($Arr_Contacts);
				
				#Debug
				#$this->_p($customer_data);
				#$this->_p($Contact);
				#return;
				
				try{
					$result = $this->X_API_I()->createContacts($this->XeroTenantId,$Contacts);
				
					#$this->add_text_into_log_file($Contact);
					#$this->add_text_into_log_file($result);
					
					if(!empty($result)){
						$xr_contacts = $result->getContacts();
						if(is_array($xr_contacts) && !empty($xr_contacts)){
							$x_contact = $xr_contacts[0];
							
							if(!$x_contact->getHasValidationErrors()){
								$X_ContactID = $x_contact->getContactID();
								if(!empty($X_ContactID) && $X_ContactID != '00000000-0000-0000-0000-000000000000'){							
									$this->save_xero_customer_into_local_dbt($x_contact);
									$this->save_xero_customer_map($wc_cus_id,$X_ContactID);
									
									$ld = "{$lt} #{$wc_cus_id} added into Xero successfully".PHP_EOL;
									$ld.="Xero Contact ID #".$X_ContactID;

									$this->save_log(
										array(
											'type'=>$lt,
											'title'=>'Create '.$lt.' #'.$wc_cus_id,
											'details'=>$ld,
											'status'=>1,
											'wc_id'=>$wc_cus_id,
											'xero_id'=>$X_ContactID,
											)
									,true);
									
									return $X_ContactID;
								}
							}else{
								# Error
								# status_attribute_string ,warnings
								$validation_errors = $x_contact->getValidationErrors();
								$ld = $this->get_error_message_from_xero_validation_errors($validation_errors);
								if(!empty($ld)){
									$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_cus_id,'details'=>$ld,'status'=>0),true);
								}
							}							
						}
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
					$ld = $this->get_error_message_from_xero_error_object($error);
					if(!empty($ld)){						
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$wc_cus_id,'details'=>$ld,'status'=>0),true);
					}

					return false;
				}				
				
				return false;	
			}	
		}
	}

	public function X_Add_Guest($customer_data){
		if(is_array($customer_data) && !empty($customer_data)){
			if($this->is_xero_connected()){
				if($xero_contact_id = $this->if_xero_guest_exists($customer_data)){
					return false;
				}
				
				$nr_chars = $this->x_nrc('customer');
				$lt = 'Customer';
				$ltt = 'Customer/Guest';
				
				$order_id = (int) $this->get_array_isset($customer_data,'order_id',0,false);
				
				$first_name = $this->get_array_isset($customer_data,'first_name','',true,255,false,$nr_chars);
				$last_name = $this->get_array_isset($customer_data,'last_name','',true,255,false,$nr_chars);
				$email = $this->get_array_isset($customer_data,'email','',true,255,false,$nr_chars);
				
				$full_name = $this->get_array_isset($customer_data,'full_name','',true,255,false,$nr_chars);
				$name = $this->get_customer_formated_display_name_for_xs($customer_data,$full_name);
				
				if(empty($name)){
					$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' Error for Order #'.$order_id,'details'=>'Customer name is empty','status'=>0));
					return false;
				}

				# Xero duplicate name check
				if($this->option_checked('mw_wc_xero_sync_customer_append_id_if_name_taken',true)){
					$ContactID = $this->get_xero_customer_by_name($name,false);
					if(!empty($ContactID)){
						$name .= '--'.$order_id;
					}
				}
				
				$Contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
				
				$Contact->setFirstName($first_name);
				$Contact->setLastName($last_name);
				$Contact->setName($name);
				$Contact->setEmailAddress($email);
				
				$Contact->setIsCustomer(1);

				#Phones
				$Phones = array();
				$billing_phone = $this->get_array_isset($customer_data,'billing_phone','');
				if(!empty($billing_phone)){
					$Phone = new XeroAPI\XeroPHP\Models\Accounting\Phone;
					$Phone->setPhoneType('MOBILE');
					$Phone->setPhoneNumber($billing_phone);
					
					$Phones[] = $Phone;
				}

				if(!empty($Phones)){
					$Contact->setPhones($Phones);
				}
				
				#Addresses
				#-> Billing / POBOX
				$Addresses = array();
				$billing_address_1 = $this->get_array_isset($customer_data,'billing_address_1','',true);
				if(!empty($billing_address_1)){
					$Address = new XeroAPI\XeroPHP\Models\Accounting\Address;

					$Address->setAddressType('POBOX');
					$Address->setAddressLine1($billing_address_1);

					$billing_address_2 = $this->get_array_isset($customer_data,'billing_address_2','',true);
					if(!empty($billing_address_2)){
						$Address->setAddressLine2($billing_address_2);
					}

					$city = $this->get_array_isset($customer_data,'billing_city','',true,255);
					$Address->setCity($city);
					
					$region = $this->get_array_isset($customer_data,'billing_state','',true,255);
					$Address->setRegion($region);

					$postal_code = $this->get_array_isset($customer_data,'billing_postcode','',true,50);
					$Address->setPostalCode($postal_code);

					$country = $this->get_array_isset($customer_data,'billing_country','',true,50);
					$Address->setCountry($country);
					
					$Addresses[] = $Address;
				}
				
				#-> Shipping / STREET
				$shipping_address_1 = $this->get_array_isset($customer_data,'shipping_address_1','',true);
				if(!empty($shipping_address_1)){
					$Address = new XeroAPI\XeroPHP\Models\Accounting\Address;

					$Address->setAddressType('STREET');
					$Address->setAddressLine1($shipping_address_1);

					$shipping_address_2 = $this->get_array_isset($customer_data,'shipping_address_2','',true);
					if(!empty($shipping_address_2)){
						$Address->setAddressLine2($shipping_address_2);
					}

					$city = $this->get_array_isset($customer_data,'shipping_city','',true,255);
					$Address->setCity($city);
					
					$region = $this->get_array_isset($customer_data,'shipping_state','',true,255);
					$Address->setRegion($region);

					$postal_code = $this->get_array_isset($customer_data,'shipping_postcode','',true,50);
					$Address->setPostalCode($postal_code);

					$country = $this->get_array_isset($customer_data,'shipping_country','',true,50);
					$Address->setCountry($country);

					$Addresses[] = $Address;					
				}

				if(!empty($Addresses)){
					$Contact->setAddresses($Addresses);
				}
				
				#DefaultCurrency
				$currency = $this->get_array_isset($customer_data,'currency','',true);
				if(!empty($currency)){
					$Contact->setDefaultCurrency($currency);
				}
				
				#ContactNumber
				if($order_id > 0){
					#$Contact->setContactNumber($order_id);
				}
				
				#CompanyNumber
				
				$Arr_Contacts = array();
				array_push($Arr_Contacts, $Contact);
				
				$Contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
				$Contacts->setContacts($Arr_Contacts);
				
				#Debug
				#$this->_p($customer_data);
				#$this->_p($Contact);
				#return;
				
				try{
					$result = $this->X_API_I()->createContacts($this->XeroTenantId,$Contacts);
				
					#$this->add_text_into_log_file($Contact);
					#$this->add_text_into_log_file($result);
					
					if(!empty($result)){
						$xr_contacts = $result->getContacts();
						if(is_array($xr_contacts) && !empty($xr_contacts)){
							$x_contact = $xr_contacts[0];
							
							if(!$x_contact->getHasValidationErrors()){
								$X_ContactID = $x_contact->getContactID();
								if(!empty($X_ContactID) && $X_ContactID != '00000000-0000-0000-0000-000000000000'){							
									$this->save_xero_customer_into_local_dbt($x_contact);								
									
									$ld = "{$ltt} for Order #{$order_id} added into Xero successfully".PHP_EOL;
									$ld.="Xero Contact ID #".$X_ContactID;

									$this->save_log(
										array(
											'type'=>$lt,
											'title'=>'Create '.$ltt.' for Order #'.$order_id,
											'details'=>$ld,
											'status'=>1,
											'wc_id'=>$order_id,
											'xero_id'=>$X_ContactID,
											)
									,true);
									
									return $X_ContactID;
								}
							}else{
								# Error
								# status_attribute_string ,warnings
								$validation_errors = $x_contact->getValidationErrors();
								$ld = $this->get_error_message_from_xero_validation_errors($validation_errors);
								if(!empty($ld)){						
									$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' for Order Error #'.$order_id,'details'=>$ld,'status'=>0),true);
								}
							}							
						}
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
					$ld = $this->get_error_message_from_xero_error_object($error);
					if(!empty($ld)){						
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' for Order Error #'.$order_id,'details'=>$ld,'status'=>0),true);
					}

					return false;
				}				
				
				return false;	
			}	
		}
	}

	public function X_Add_Invoice($invoice_data){
		if(is_array($invoice_data) && !empty($invoice_data)){
			if($this->is_xero_connected()){
				if($xero_invoice_id = $this->if_xero_invoice_exists($invoice_data)){
					return false;
				}

				$lt = 'Order';
				$ltt = 'Order';# Invoice

				$wc_inv_id = (int) $this->get_array_isset($invoice_data,'wc_inv_id',0,false);
				$wc_inv_num = $this->get_array_isset($invoice_data,'wc_inv_num','');

				$ord_id_num = (!empty($wc_inv_num))?$wc_inv_num:$wc_inv_id;
				$DocNumber = $ord_id_num;

				# Payment method map data
				$_payment_method = $this->get_array_isset($invoice_data,'_payment_method','',true);
				$_order_currency = $this->get_array_isset($invoice_data,'_order_currency','',true);
				$_currency_applicable = $_order_currency;

				$pm_map_data = $this->get_mapped_payment_method_data($_payment_method,$_currency_applicable);

				# Xero Customer
				if(isset($invoice_data['X_ContactID']) && !empty($invoice_data['X_ContactID'])){
					$X_ContactID = $this->get_array_isset($invoice_data,'X_ContactID','');
				}else{
					$X_ContactID = $this->get_xero_customer_for_order_sync($invoice_data);
				}

				if(empty($X_ContactID)){
					$this->save_log(array('type'=>$lt,'title'=>'Create '.$lt.' Error #'.$ord_id_num,'details'=>'Xero customer not found','status'=>0));
					return false;
				}

				$wc_inv_date = $this->get_array_isset($invoice_data,'wc_inv_date','');
				#$wc_inv_date = $this->format_date($wc_inv_date);
				
				$wc_inv_due_date = $wc_inv_date;
				$inv_due_date_days = (int) $this->get_array_isset($pm_map_data,'x_invoice_ddd',0,false);

				if(!empty($wc_inv_date) && $inv_due_date_days > 0){
					$wc_inv_due_date = date('Y-m-d',strtotime($wc_inv_date . "+{$inv_due_date_days} days"));
				}

				$Invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
				$Invoice->setType(XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC);

				# Next Xero Order Number
				if(!$this->use_next_xero_order_number()){
					$Invoice->setInvoiceNumber($DocNumber);
				}
				
				# Reference
				
				$Invoice->setDate(new DateTime($wc_inv_date));
				$Invoice->setDueDate(new DateTime($wc_inv_due_date));

				$Contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
				$Contact->setContactId($X_ContactID);
				$Invoice->setContact($Contact);

				# URL
				$wc_order_url = admin_url('post.php?post='.$wc_inv_id.'&action=edit');
				if(!empty($wc_order_url)){
					if(strpos(strtolower($wc_order_url), 'xero') === false) {
						$Invoice->setUrl($wc_order_url);
					}
				}				
				
				#_date_paid
				$_paid_date = $this->get_array_isset($invoice_data,'_paid_date','');

				# Status
				if(empty($_paid_date)){
					# For WooCommerce Unpaid Orders
					$xero_inv_status_for_unp_ord = $this->get_option('mw_wc_xero_sync_xero_inv_status_for_unp_ord');
					if(!empty($xero_inv_status_for_unp_ord)){					
						$Invoice->setStatus($xero_inv_status_for_unp_ord);
					}
				}else{
					$enable_payment = (int) $this->get_array_isset($pm_map_data,'enable_payment',0,false);
					if($enable_payment){
						$Invoice->setStatus('AUTHORISED');
					}					
				}
				
				# Send Email
				if($this->option_checked('mw_wc_xero_sync_send_invoice_email_after_sync')){
					$Invoice->SentToContact(true);
				}
				
				$Discount_Sync_Type = 'Per_Line_Item';# Per_Line_Item|Per_Order
				$Tax_Sync_Type = 'Xero_Tax';# Order_Line_Item|Xero_Tax

				if($this->option_checked('mw_wc_xero_sync_order_tax_as_li')){
					$Tax_Sync_Type = 'Order_Line_Item';
				}

				$xero_inv_items = (isset($invoice_data['xero_inv_items']))?$invoice_data['xero_inv_items']:array();

				# Add Invoice items
				$LineItems = array();
				if(is_array($xero_inv_items) && !empty($xero_inv_items)){
					foreach($xero_inv_items as $xi_k => $xero_item){
						$LineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
						$LineItem->setDescription($xero_item['Description']);
						$LineItem->setQuantity($xero_item['Qty']);
						$LineItem->setUnitAmount($xero_item['UnitPrice']);
						$LineItem->setItemCode($xero_item['X_Code']);

						$AccountCode = (isset($xero_item['X_SD_AccountCode']) && !empty($xero_item['X_SD_AccountCode']))?$xero_item['X_SD_AccountCode']:'';

						$is_p_mapped_acc = false;

						# Variation -> Account Map
						if((int) $xero_item['variation_id'] > 0){
							$account_map_data = $this->get_mapping_data_from_table_multiple('variation','account',(int) $xero_item['variation_id']);
							if(is_array($account_map_data) && !empty($account_map_data) && isset($account_map_data['x_id']) && !empty($account_map_data['x_id'])){
								$AccountCode = $account_map_data['x_id'];
								$is_p_mapped_acc = true;
							}
						}

						# Product -> Account Map						
						if((int) $xero_item['variation_id'] < 1 && (int) $xero_item['product_id'] > 0){
							$account_map_data = $this->get_mapping_data_from_table_multiple('product','account',(int) $xero_item['product_id']);
							if(is_array($account_map_data) && !empty($account_map_data) && isset($account_map_data['x_id']) && !empty($account_map_data['x_id'])){
								$AccountCode = $account_map_data['x_id'];
								$is_p_mapped_acc = true;
							}
						}
						
						# Category -> Account Map
						
						if(!$is_p_mapped_acc && (int) $xero_item['product_id'] > 0){
							$terms = get_the_terms ( (int) $xero_item['product_id'], 'product_cat' );
							if(!empty($terms)){
								foreach ( $terms as $term ) {
									$cat_map_data = $this->get_row_by_val($this->gdtn('map_categories'),'W_CAT_ID',$term->term_id);
									if(is_array($cat_map_data) && !empty($cat_map_data)){
										if(!empty($cat_map_data['X_ACC_CODE'])){
											$AccountCode = $cat_map_data['X_ACC_CODE'];
											break;
										}
									}
								}
							}
						}
						
						if(!empty($AccountCode)){
							$LineItem->setAccountCode($AccountCode);
						}
						
						# Discount Per Line
						if($Discount_Sync_Type == 'Per_Line_Item'){
							$DiscountAmount = ($xero_item['line_subtotal'] - $xero_item['line_total']);
							if($DiscountAmount > 0){
								$LineItem->setDiscountAmount($DiscountAmount);
							}							
						}
						
						# Line Tax
						if($Tax_Sync_Type == 'Xero_Tax'){
							$TaxType = $this->get_xero_tax_code_from_line_tax_data($xero_item);							
						}else{
							$TaxType = $this->get_xero_non_taxable_tax_code();
						}

						if(!empty($TaxType)){
							$LineItem->setTaxType($TaxType);
						}

						$LineItems[] = $LineItem;
					}
				}

				# Discount Line
				if($Discount_Sync_Type == 'Per_Order'){
					#->
				}

				# Fee Line
				if($this->is_sync_fee_line()){
					$dc_gt_fees = (isset($invoice_data['dc_gt_fees']))?$invoice_data['dc_gt_fees']:array();
					if(is_array($dc_gt_fees) && !empty($dc_gt_fees)){
						#->
					}
				}

				# Shipping Line
				$shipping_details = (isset($invoice_data['shipping_details']))?$invoice_data['shipping_details']:array();
				if(is_array($shipping_details) && !empty($shipping_details)){
					$dxsp_code = '';
					$dxsp_id = $this->get_option('mw_wc_xero_sync_default_xero_shipping_product');
					if(empty($dxsp_id)){
						$dxsp_id = $this->get_option('mw_wc_xero_sync_default_xero_product');
					}

					if(!empty($dxsp_id)){
						$X_P_L_D = $this->get_xero_product_line_item_data($dxsp_id);
						$dxsp_code = $X_P_L_D['Code'];
					}
					
					if(!empty($dxsp_code)){
						foreach($shipping_details as $sk => $sd){
							$shipping_method_name =  $this->get_array_isset($sd,'name','');
							$description = (!empty($shipping_method_name))?'Shipping ('.$shipping_method_name.')':'Shipping';
							$shipping_amount = (float) $this->get_array_isset($sd,'cost',0,false);
							
							$LineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
							$LineItem->setDescription($description);
							#$LineItem->setQuantity(1);
							$LineItem->setUnitAmount($shipping_amount);
							$LineItem->setLineAmount($shipping_amount);
							$LineItem->setItemCode($dxsp_code);

							$AccountCode = (isset($X_P_L_D['SD_AccountCode']) && !empty($X_P_L_D['SD_AccountCode']))?$X_P_L_D['SD_AccountCode']:'';
							if(!empty($AccountCode)){
								$LineItem->setAccountCode($AccountCode);
							}
							
							# Line Tax
							if($Tax_Sync_Type == 'Xero_Tax'){
								$TaxType = $this->get_xero_tax_code_from_line_tax_data($sd,'shipping');								
							}else{
								$TaxType = $this->get_xero_non_taxable_tax_code();
							}

							if(!empty($TaxType)){
								$LineItem->setTaxType($TaxType);
							}

							$LineItems[] = $LineItem;
						}
					}					
				}

				# Tax Line
				if($Tax_Sync_Type == 'Order_Line_Item'){
					$tax_details = (isset($invoice_data['tax_details']))?$invoice_data['tax_details']:array();
					if(is_array($tax_details) && !empty($tax_details)){
						$dxtp_code = '';
						$dxtp_id = $this->get_option('mw_wc_xero_sync_otli_xero_product');
						if(empty($dxtp_id)){
							$dxtp_id = $this->get_option('mw_wc_xero_sync_default_xero_product');
						}
						
						if(!empty($dxtp_id)){
							$X_P_L_D = $this->get_xero_product_line_item_data($dxtp_id);
							$dxtp_code = $X_P_L_D['Code'];							
						}

						if(!empty($dxtp_code)){
							foreach($tax_details as $tk => $td){
								$description = $td['label'];
								if(!empty($td['name'])){
									$description.= ' - '.$td['name'];
								}

								$tax_amount = (float) $this->get_array_isset($td,'tax_amount',0,false);
								$shipping_tax_amount = (float) $this->get_array_isset($td,'shipping_tax_amount',0,false);

								$LineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
								$LineItem->setDescription($description);
								#$LineItem->setQuantity(1);
								$LineItem->setUnitAmount($tax_amount+$shipping_tax_amount);
								$LineItem->setLineAmount($tax_amount+$shipping_tax_amount);
								$LineItem->setItemCode($dxtp_code);

								$AccountCode = (isset($X_P_L_D['SD_AccountCode']) && !empty($X_P_L_D['SD_AccountCode']))?$X_P_L_D['SD_AccountCode']:'';
								if(!empty($AccountCode)){
									$LineItem->setAccountCode($AccountCode);
								}

								# Line Tax
								$TaxType = $this->get_xero_non_taxable_tax_code();
								if(!empty($TaxType)){
									$LineItem->setTaxType($TaxType);
								}

								$LineItems[] = $LineItem;
							}
						}
					}
				}

				# Txn fee line
				$enable_txn_fee = (int) $this->get_array_isset($pm_map_data,'enable_txn_fee',0,false);
				if($enable_txn_fee){
					$txn_fee_data = $this->get_txn_fee_data_from_order($invoice_data);
					$txn_fee_desc = $txn_fee_data['t_f_desc'];
					$txn_fee_amount = (float) $txn_fee_data['t_f_amnt'];

					if($txn_fee_amount > 0){
						$dxtfp_code = '';
						$dxtfp_id = $this->get_array_isset($pm_map_data,'txn_fee_x_product','');
						if(empty($dxtfp_id)){
							$dxtfp_id = $this->get_option('mw_wc_xero_sync_default_xero_product');
						}
						
						if(!empty($dxtfp_id)){
							$X_P_L_D = $this->get_xero_product_line_item_data($dxtfp_id);
							$dxtfp_code = $X_P_L_D['Code'];							
						}

						if(!empty($dxtfp_code)){
							$txn_fee_amount = -1 * abs($txn_fee_amount);
							$description = $txn_fee_desc;

							$LineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
							$LineItem->setDescription($description);
							#$LineItem->setQuantity(1);
							$LineItem->setUnitAmount($txn_fee_amount);
							$LineItem->setLineAmount($txn_fee_amount);
							$LineItem->setItemCode($dxtfp_code);

							$AccountCode = (isset($X_P_L_D['SD_AccountCode']) && !empty($X_P_L_D['SD_AccountCode']))?$X_P_L_D['SD_AccountCode']:'';
							if(!empty($AccountCode)){
								$LineItem->setAccountCode($AccountCode);
							}

							# Line Tax
							$TaxType = $this->get_xero_non_taxable_tax_code();
							if(!empty($TaxType)){
								$LineItem->setTaxType($TaxType);
							}

							$LineItems[] = $LineItem;
						}
					}					
				}
				
				#->
				if(!empty($LineItems)){
					$Invoice->setLineItems($LineItems);
				}
				
				$Arr_Invoices = array();
				array_push($Arr_Invoices, $Invoice);
				
				$Invoices = new XeroAPI\XeroPHP\Models\Accounting\Invoices;
				$Invoices->setInvoices($Arr_Invoices);
				
				#Debug
				#$this->_p($invoice_data);				
				#$this->_p($Invoice);
				#return;
				
				try{
					$result = $this->X_API_I()->createInvoices($this->XeroTenantId,$Invoices);
				
					#$this->add_text_into_log_file($Invoice);
					#$this->add_text_into_log_file($result);
					#$this->_p($result);
					
					if(!empty($result)){
						$xr_invoices = $result->getInvoices();
						if(is_array($xr_invoices) && !empty($xr_invoices)){
							$x_invoice = $xr_invoices[0];

							if(!$x_invoice->getHasErrors()){
								$X_InvoiceID = $x_invoice->getInvoiceID();
								if(!empty($X_InvoiceID) && $X_InvoiceID != '00000000-0000-0000-0000-000000000000'){
									$ld = "{$ltt} #{$ord_id_num} added into Xero successfully".PHP_EOL;
									$ld.="Xero Invoice ID #".$X_InvoiceID;

									# Next Xero Order Number
									if($this->use_next_xero_order_number()){
										$X_InvoiceNumber = (string) $x_invoice->getInvoiceNumber();
										update_post_meta($wc_inv_id,'_myworks_xero_sync_order_number',$X_InvoiceNumber,false);
									}

									$this->save_log(
										array(
											'type'=>$lt,
											'title'=>'Create '.$ltt.' #'.$ord_id_num,
											'details'=>$ld,
											'status'=>1,
											'wc_id'=>$wc_inv_id,
											'xero_id'=>$X_InvoiceID,
											)
									,true);
									
									# Order Note
									$o_note = __('Order synced to Xero - MyWorks Sync','myworks-sync-for-xero');
									$this->add_order_note($wc_inv_id,$o_note);
									
									return $X_InvoiceID;
								}
							}else{
								# Error
								# status_attribute_string ,warnings
								$validation_errors = $x_invoice->getValidationErrors();
								$ld = $this->get_error_message_from_xero_validation_errors($validation_errors);
								if(!empty($ld)){						
									$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' Error #'.$ord_id_num,'details'=>$ld,'status'=>0),true);
								}

								# Order Note
								$o_note = __('Order attempted sync to Xero but failed. Check MyWorks Sync > Log for more info.','myworks-sync-for-xero');
								$this->add_order_note($wc_inv_id,$o_note);

								return false;
							}							
						}
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
					$ld = $this->get_error_message_from_xero_error_object($error);
					if(!empty($ld)){						
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' Error #'.$ord_id_num,'details'=>$ld,'status'=>0),true);
					}

					# Order Note
					$o_note = __('Order attempted sync to Xero but failed. Check MyWorks Sync > Log for more info.','myworks-sync-for-xero');
					$this->add_order_note($wc_inv_id,$o_note);

					return false;
				}

			}
		}
	}
	
	# Payment Sync
	public function X_Add_Payment($invoice_data,$xero_invoice_object){	
		if(is_array($invoice_data) && !empty($invoice_data) && is_object($xero_invoice_object) && !empty($xero_invoice_object)){
			if($this->is_xero_connected()){
				if($xero_payment_id = $this->if_xero_payment_exists($invoice_data,$xero_invoice_object)){
					return false;
				}

				if($xero_invoice_object->getStatus() != 'AUTHORISED'){
					return false;
				}
				
				$lt = 'Payment';
				$ltt = 'Payment';

				$wc_inv_id = (int) $this->get_array_isset($invoice_data,'wc_inv_id',0,false);
				$wc_inv_num = $this->get_array_isset($invoice_data,'wc_inv_num','');

				$ord_id_num = (!empty($wc_inv_num))?$wc_inv_num:$wc_inv_id;

				$_order_total = (float) $this->get_array_isset($invoice_data,'_order_total',0,false);
				$payment_amount = $_order_total;

				$enable_txn_fee = (int) $this->get_array_isset($pm_map_data,'enable_txn_fee',0,false);

				if($payment_amount == 0 || $payment_amount < 0){
					return false;
				}

				#$wc_inv_date = $this->get_array_isset($invoice_data,'wc_inv_date','');
				$order_date = $this->get_array_isset($invoice_data,'order_date','');

				$artificial_payment_allowed = false;
				$_paid_date = $this->get_array_isset($invoice_data,'_paid_date','');				
				if(!$artificial_payment_allowed && empty($_paid_date)){
					return false;
				}

				$_transaction_id = $this->get_array_isset($invoice_data,'_transaction_id','');

				$_payment_method = $this->get_array_isset($invoice_data,'_payment_method','',true);
				$_payment_method_title = $this->get_array_isset($invoice_data,'_payment_method_title','',true);
				$_order_currency = $this->get_array_isset($invoice_data,'_order_currency','',true);

				if(empty($_payment_method) || empty($_order_currency)){
					return false;
				}

				$_currency_applicable = $_order_currency;

				$pm_map_data = $this->get_mapped_payment_method_data($_payment_method,$_currency_applicable);
				$enable_payment = (int) $this->get_array_isset($pm_map_data,'enable_payment',0,false);
				$AccountID = $this->get_array_isset($pm_map_data,'X_ACC_ID','');

				if($enable_payment != 1 || empty($AccountID)){
					return false;
				}

				$enable_txn_fee = (int) $this->get_array_isset($pm_map_data,'enable_txn_fee',0,false);
				if($enable_txn_fee){
					$txn_fee_data = $this->get_txn_fee_data_from_order($invoice_data);
					$txn_fee_amount = (float) $txn_fee_data['t_f_amnt'];
					if($txn_fee_amount > 0){
						$payment_amount = (float) ($payment_amount-$txn_fee_amount);
					}
				}

				if($payment_amount == 0 || $payment_amount < 0){
					return false;
				}

				$xero_invoice_id = $xero_invoice_object->getInvoiceID();
				
				$Payment = new XeroAPI\XeroPHP\Models\Accounting\Payment;

				$Invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;				
				$Invoice->setInvoiceID($xero_invoice_id);
				
				$BankAccount = new XeroAPI\XeroPHP\Models\Accounting\Account;				
				$BankAccount->setAccountID($AccountID);
				
				$Payment->setInvoice($Invoice);
				$Payment->setAccount($BankAccount);
				
				$Payment->setAmount($payment_amount);

				$payment_date = (!empty($_paid_date))?$_paid_date:$order_date;				
				$Payment->setDate(new DateTime($payment_date));				

				$Reference = (!empty($_transaction_id))?$_transaction_id:$_payment_method_title;				
				$Payment->setReference($Reference);
				
				#Debug
				#$this->_p($invoice_data);
				#$this->_p($xero_invoice_object);
				#$this->_p($Payment);
				#return;
				
				try{
					$result = $this->X_API_I()->createPayment($this->XeroTenantId,$Payment);
					
					#$this->_p($result);
					#$this->add_text_into_log_file($Payment);
					#$this->add_text_into_log_file($result);
					
					if(!empty($result)){
						$xr_payments = $result->getPayments();
						if(is_array($xr_payments) && !empty($xr_payments)){
							$x_payment = $xr_payments[0];
							$X_PaymentID = $x_payment->getPaymentID();
							if(!empty($X_PaymentID)){								
								$ld = "{$ltt} for Order #{$ord_id_num} added into Xero successfully".PHP_EOL;
								$ld.="Xero Payment ID #".$X_PaymentID;

								$this->save_log(
									array(
										'type'=>$lt,
										'title'=>'Create '.$ltt.' for Order #'.$ord_id_num,
										'details'=>$ld,
										'status'=>1,
										'wc_id'=>$wc_inv_id,
										'xero_id'=>$X_PaymentID,
										)
								,true);
								
								return $X_PaymentID;
							}
						}
					}
				}catch (\XeroAPI\XeroPHP\ApiException $e) {
					$error = AccountingObjectSerializer::deserialize($e->getResponseBody(), '\XeroAPI\XeroPHP\Models\Accounting\Error',[]);					
					$ld = $this->get_error_message_from_xero_error_object($error);
					if(!empty($ld)){						
						$this->save_log(array('type'=>$lt,'title'=>'Create '.$ltt.' for Order Error #'.$ord_id_num,'details'=>$ld,'status'=>0),true);
					}

					return false;
				}				
				
				return false;
			}
		}
	}
	
	public function X_Pull_Inventory(){
		if($this->is_xero_connected()){
			$if_modified_since = null;
			
			$now = new DateTime("now", new DateTimeZone($this->get_xero_timezone()));
			$datetime = $now->format('Y-m-d H:i:s');

			$last_timestamp = $this->get_option('mw_wc_xero_last_ivnt_pull_timestamp');
			if(!empty($last_timestamp)){
				#$if_modified_since = $last_timestamp;
			}else{
				$interval_mins = 5;				
				$datetime_m = date('Y-m-d H:i:s',strtotime("-{$interval_mins} minutes",strtotime($datetime)));
				#$if_modified_since = date('Y-m-d', strtotime($datetime_m)) . 'T' . date('H:i:s', strtotime($datetime_m));				
			}

			$last_timestamp = date('Y-m-d', strtotime($datetime)) . 'T' . date('H:i:s', strtotime($datetime));
			if(!empty($last_timestamp)){
				#$this->update_option('mw_wc_xero_last_ivnt_pull_timestamp',$last_timestamp);
			}
			
			$where = 'IsTrackedAsInventory=True';

			$result = $this->X_API_I()->getItems($this->XeroTenantId,$if_modified_since,$where);
			if(!empty($result)){
				$items = $result->getItems();
				if(is_array($items) && !empty($items)){
					foreach($items as $Item){						
						$ItemID = $Item->getItemID();
						$QuantityOnHand = $Item->getQuantityOnHand(); # Float value in Xero
						$item_data = array(
							'Name' => $Item->getName(),
							'Code' => $Item->getCode(),
						);

						$this->UpdateWooCommerceInventory($ItemID,$QuantityOnHand,$item_data);
					}
				}
			}
		}
	}

	public function UpdateWooCommerceInventory($ItemID,$QuantityOnHand,$item_data=array()){
		if(!empty($ItemID)){
			global $wpdb;
			$map_data = $this->get_data($wpdb->prepare("SELECT `W_P_ID` FROM `".$this->gdtn('map_products')."` WHERE `X_P_ID` = %s AND `W_P_ID` > 0 ",$ItemID));
			$is_variation = false;
			if(empty($map_data)){
				$map_data = $this->get_data($wpdb->prepare("SELECT `W_V_ID` FROM `".$this->gdtn('map_variations')."` WHERE `X_P_ID` = %s AND `W_V_ID` > 0 ",$ItemID));
				$is_variation = true;
			}

			if(empty($map_data)){
				return false;
			}

			$item_code = '';

			$ltp = '';
			$ext_log = '';			
			if(is_array($item_data) && !empty($item_data)){
				if(isset($item_data['Name'])){
					$ext_log = PHP_EOL.'Name: '.$item_data['Name'];
				}
				
				if(isset($item_data['Code'])){
					$item_code = $item_data['Code'];
				}
			}
			
			if(is_array($map_data)){
				$is_v_parent_stock_status_updated = false;
				foreach($map_data as $map_data_c){
					$wc_product_id = 0;
					$is_variation_parent = false;
					$parent_id = 0;

					if($is_variation){
						$wc_variation_id = $map_data_c['W_V_ID'];
						$variation_manage_stock = get_post_meta($wc_variation_id,'_manage_stock',true);
						$parent_id = (int) $this->get_field_by_val($wpdb->posts,'post_parent','ID',$wc_variation_id);
						if($variation_manage_stock=='yes'){
							$wc_product_id = $wc_variation_id;
						}else{								
							if($parent_id){
								$wc_product_id = $parent_id;
								$is_variation_parent = true;
							}
						}
					}else{
						$wc_product_id = $map_data_c['W_P_ID'];
					}

					if(!$wc_product_id){
						continue;
					}

					$product_meta = get_post_meta($wc_product_id);
					if(!is_array($product_meta) || empty($product_meta)){
						continue;
					}

					$_manage_stock = (isset($product_meta['_manage_stock'][0]))?$product_meta['_manage_stock'][0]:'no';
					$_backorders = (isset($product_meta['_backorders'][0]))?$product_meta['_backorders'][0]:'no';
					$_stock = (isset($product_meta['_stock'][0]))?$product_meta['_stock'][0]:0;
					if(is_null($_stock) || empty($_stock)){
						$_stock = 0;
					}

					$is_valid_wc_inventory = false;
					if($_manage_stock=='yes'){
						$is_valid_wc_inventory = true;
					}

					if(!$is_valid_wc_inventory){
						continue;
					}

					# Parent
					if($is_variation_parent){
						if(!$this->is_xero_connected()){
							continue;
						}

						$parent_x_item_id = $this->get_field_by_val($this->gdtn('map_products'),'X_P_ID','W_P_ID',$wc_product_id);
						if(empty($parent_x_item_id)){
							continue;
						}
						
						$xp_qty = false;
						$result = $this->X_API_I()->getItem($this->XeroTenantId,$parent_x_item_id);
						if(!empty($result)){
							$items = $result->getItems();
							if(is_array($items) && !empty($items)){
								$xp_item = $items[0];
								$QuantityOnHand = $xp_item->getQuantityOnHand();
								$xp_qty = true;
							}
						}

						if(!$xp_qty){
							continue;
						}
					}

					$QuantityOnHand = floatval($QuantityOnHand);
					$_stock = floatval($_stock);

					if($QuantityOnHand!=$_stock){
						$_stock = number_format(floatval($_stock),2);
						wc_update_product_stock($wc_product_id,(int) $QuantityOnHand);

						$QuantityOnHand = number_format(floatval($QuantityOnHand),2);

						$x_lt_id = (!empty($item_code))?$item_code:$ItemID;
						
						$log_title = $ltp.'Import Inventory #'.$x_lt_id;
						$log_details = "WooCommerce Product #{$wc_product_id} stock updated from {$_stock} to {$QuantityOnHand}".$ext_log;
						$this->save_log(array('type'=>'Inventory','title'=>$log_title,'details'=>$log_details,'status'=>1,'wc_id'=>$wc_product_id,'xero_id'=>$ItemID),true);
					}else{
						# Same Qty
					}
				}
			}
		}
	}

	public function PaymentPull_UpdateWooCommerceOrderStatus($order_id,$InvoiceNumber){
		$order_id = (int) $order_id;
		if($order_id > 0 && !empty($InvoiceNumber)){
			global $wpdb;
			$e_order_status = $this->get_field_by_val($wpdb->posts,'post_status','ID',$order_id);
			if(!empty($e_order_status)){
				if($e_order_status == 'trash' || $e_order_status == 'auto-draft'){
					return false;
				}

				$prevent_status_l = $this->get_option('mw_wc_xero_sync_prevent_payment_pull_wc_order_status');
				if(!empty($prevent_status_l)){
					$prevent_status_l = explode(',',$prevent_status_l);
					if(is_array($prevent_status_l) && in_array($e_order_status,$prevent_status_l)){
						return false;
					}
				}

				$ext_log = '';
				$ltp = '';

				$n_order_status = $this->get_option('mw_wc_xero_sync_payment_pull_wc_order_status');
				if(!empty($n_order_status) && $e_order_status != $n_order_status){					
					$order = new WC_Order($order_id);
					$r = $order->update_status( $n_order_status );
					if($r){
						$w_order_statuses = wc_get_order_statuses();
						if(is_array($w_order_statuses)){
							if(isset($w_order_statuses[$e_order_status])){
								$e_order_status = $w_order_statuses[$e_order_status];
							}

							if(isset($w_order_statuses[$n_order_status])){
								$n_order_status = $w_order_statuses[$n_order_status];
							}

							$order_note = 'Order status changed from '.$e_order_status.' to '.$n_order_status;
							$order_note.=PHP_EOL;
							$order_note.='Payment Pull - MyWorks WooCommerce Sync for Xero';
							$order->add_order_note($order_note);
							
							$log_title = $ltp.'Pull Payment for order #'.$InvoiceNumber;
							$log_details = "WooCommerce Order #{$InvoiceNumber} status changed from {$e_order_status} to {$n_order_status}".$ext_log;
							$this->save_log(array('type'=>'Payment','title'=>$log_title,'details'=>$log_details,'status'=>1,'wc_id'=>$order_id,'xero_id'=>''),true);
						}
					}else{
						$wp_err_txt = 'Order Status Update Error';
						$log_title = $ltp.'Pull Payment Error for order #'.$InvoiceNumber;
						$log_details = "Error: ".$wp_err_txt;
						$this->save_log(array('type'=>'Payment','title'=>$log_title,'details'=>$log_details,'status'=>0,'wc_id'=>$order_id,'xero_id'=>''),true);
					}
				}
			}
		}	
		
		return false;
	}

	public function X_Pull_Payment(){
		if($this->is_xero_connected()){
			$if_modified_since = null;

			$now = new DateTime("now", new DateTimeZone($this->get_xero_timezone()));
			$datetime = $now->format('Y-m-d H:i:s');

			$last_timestamp = $this->get_option('mw_wc_xero_last_payment_pull_timestamp');
			if(!empty($last_timestamp)){
				$if_modified_since = $last_timestamp;
			}else{
				$interval_mins = 5;				
				$datetime_m = date('Y-m-d H:i:s',strtotime("-{$interval_mins} minutes",strtotime($datetime)));
				$if_modified_since = date('Y-m-d', strtotime($datetime_m)) . 'T' . date('H:i:s', strtotime($datetime_m));				
			}

			$last_timestamp = date('Y-m-d', strtotime($datetime)) . 'T' . date('H:i:s', strtotime($datetime));
			if(!empty($last_timestamp)){
				$this->update_option('mw_wc_xero_last_payment_pull_timestamp',$last_timestamp);
			}

			$where = 'Invoice.Type="ACCREC" AND Status != "DELETED"';
			$page = null;
			$result = $this->X_API_I()->getPayments($this->XeroTenantId,$if_modified_since,$where,null,$page);
			if(!empty($result)){
				$payments = $result->getPayments();
				if(is_array($payments) && !empty($payments)){
					foreach($payments as $payment){
						if(!empty($payment->getInvoice()) && $payment->getInvoice()->getType() == 'ACCREC'){
							$InvoiceID = $payment->getInvoice()->getInvoiceID();
							$InvoiceNumber = $payment->getInvoice()->getInvoiceNumber();
							if(!empty($InvoiceNumber)){
								$order_id = $this->check_if_woocommerce_order_exists_by_xero_iq_number($InvoiceNumber);
								if($order_id){
									$this->PaymentPull_UpdateWooCommerceOrderStatus($order_id,$InvoiceNumber);
								}
							}
						}
					}
				}
			}
		}
	}

	public function X_Pull_Product(){
		if($this->is_xero_connected()){
			$if_modified_since = null;
			
			$now = new DateTime("now", new DateTimeZone($this->get_xero_timezone()));
			$datetime = $now->format('Y-m-d H:i:s');

			$last_timestamp = $this->get_option('mw_wc_xero_last_product_pull_timestamp');
			if(!empty($last_timestamp)){
				$if_modified_since = $last_timestamp;
			}else{
				$interval_mins = 5;				
				$datetime_m = date('Y-m-d H:i:s',strtotime("-{$interval_mins} minutes",strtotime($datetime)));
				$if_modified_since = date('Y-m-d', strtotime($datetime_m)) . 'T' . date('H:i:s', strtotime($datetime_m));				
			}

			$last_timestamp = date('Y-m-d', strtotime($datetime)) . 'T' . date('H:i:s', strtotime($datetime));
			if(!empty($last_timestamp)){
				$this->update_option('mw_wc_xero_last_product_pull_timestamp',$last_timestamp);
			}

			$where = null;
			$result = $this->X_API_I()->getItems($this->XeroTenantId,$if_modified_since,$where);

			if(!empty($result)){
				$items = $result->getItems();
				if(is_array($items) && !empty($items)){
					foreach($items as $Item){
						$this->AddUpdateWooCommerceProduct($Item);
					}
				}
			}
		}
	}

	public function X_Pull_Product_By_Id($ItemId){		
		if(!empty($ItemId) && $this->is_xero_connected()){			
			$ItemId = $this->sanitize($ItemId);
			$result = $this->X_API_I()->getItem($this->XeroTenantId,$ItemId);

			if(!empty($result)){
				$items = $result->getItems();
				if(is_array($items) && !empty($items)){
					$Item = $items[0];					
					
					return $this->AddUpdateWooCommerceProduct($Item);
				}
			}
		}

		return false;
	}

	protected function AddUpdateWooCommerceProduct($Item){
		if(!is_object($Item) || empty($Item)){
			return false;
		}

		$ItemId = $Item->getItemID();
		
		$item_data = array(
			'Name' => $Item->getName(),
			'Code' => $Item->getCode(),
			'Description' => $Item->getDescription(),
			'IsTrackedAsInventory' => $Item->getIsTrackedAsInventory(),
			'UnitPrice' => 0,
			'TaxType' => '',

		);
		
		if(!empty($Item->getSalesDetails())){
			$item_data['UnitPrice'] = floatval($Item->getSalesDetails()->getUnitPrice());
			$item_data['TaxType'] = $Item->getSalesDetails()->getTaxType();
		}

		if($Item->getIsTrackedAsInventory()){
			$item_data['QuantityOnHand'] = $Item->getQuantityOnHand();
		}
		
		$item_data['X_P_ID'] = $ItemId;
		$mapped_data = $this->if_xero_item_exists_in_woo($item_data,false);

		$ltp = '';
		$x_lt_id = (!empty($item_data['Code']))?$item_data['Code']:$ItemId;
		$ext_log = (!empty($item_data['Name']))?PHP_EOL.'Name: '.$item_data['Name']:'';

		if(empty($mapped_data)){						
			$wc_product_data = array();
			$wc_product_data['wp_error'] = true;

			$wc_product_data['post_title'] = $item_data['Name'];
			$wc_product_data['post_content'] = $item_data['Description'];

			$post_status = $this->get_option('mw_wc_xero_sync_pulled_product_wc_status','draft');
			$wc_product_data['post_status'] = $post_status;

			$wc_product_meta = array();

			if(!empty($item_data['Code'])){
				$wc_product_meta['_sku'] = $item_data['Code'];
			}

			$_manage_stock = ($item_data['IsTrackedAsInventory'])?'yes':'no';						
			$wc_product_meta['_manage_stock'] = $_manage_stock;

			if($_manage_stock == 'yes'){
				$_stock = $item_data['QuantityOnHand'];
				$wc_product_meta['_stock'] = $_stock;
				if($_stock && $_stock>0){
					$wc_product_meta['_stock_status'] = 'instock';
				}else{
					$wc_product_meta['_stock_status'] = 'outofstock';
				}
			}

			if($_manage_stock == 'no' || $this->get_option('woocommerce_manage_stock')  != 'yes'){
				$wc_product_meta['_stock_status'] = 'instock';
			}

			$_tax_status = (!empty($item_data['TaxType']) && $item_data['TaxType'] != 'NONE')?'taxable':'';
			$wc_product_meta['_tax_status'] = $_tax_status;

			$_price = $item_data['UnitPrice'];
			$wc_product_meta['_regular_price'] = $_price;
			$wc_product_meta['_price'] = $_price;

			$wc_product_meta['total_sales'] = '0';
			$wc_product_meta['_downloadable'] = 'no';
			$wc_product_meta['_visibility'] = 'visible';
			$wc_product_meta['_virtual'] = 'no';

			$wc_product_meta['_purchase_note'] = '';

			$tax_input = array();

			#$this->_p($Item);
			#$this->_p($wc_product_data);
			#$this->_p($wc_product_meta);
			#return false;

			$return = $this->save_wp_post('product',$wc_product_data,$wc_product_meta,$tax_input);
			if(!is_wp_error($return) && (int) $return){
				$post_id = (int) $return;							
				$this->save_xero_product_variation_map($post_id,$ItemId,false,true);

				$log_title = $ltp.'Import Product #'.$x_lt_id;
				$log_details = "Product #{$x_lt_id} has been imported, WooCommerce Product ID is #".$post_id.$ext_log;
				$this->save_log(array('type'=>'Product','title'=>$log_title,'details'=>$log_details,'status'=>1,'wc_id'=>$post_id,'xero_id'=>$ItemId),true);
				
				return true;
			}else{
				if(isset($wc_product_data['wp_error'])){
					$log_details = 'Error: '.$return->get_error_message();
				}else{
					$log_details = 'Error: Wordpress save post error';
				}

				$log_title = $ltp.'Import Product Error #'.$x_lt_id;
				$this->save_log(array('type'=>'Product','title'=>$log_title,'details'=>$log_details,'status'=>0,'wc_id'=>0,'xero_id'=>$ItemId),true);
			}
		}else{
			# Update
		}

		return false;
	}

	##-->[End]<--##
	
}