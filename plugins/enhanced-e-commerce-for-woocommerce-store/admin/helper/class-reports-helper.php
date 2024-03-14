<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * Woo Order Reports
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('Conversios_Reports_Helper')) {
	class Conversios_Reports_Helper
	{
		protected $ShoppingApi;
		protected $CustomApi;
		protected $TVC_Admin_Helper;
		protected $TVC_Admin_DB_Helper;
		public function __construct()
		{
			$this->req_int();
			$this->TVC_Admin_Helper = new TVC_Admin_Helper();
			$this->TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
			$this->ShoppingApi = new ShoppingApi();
			$this->CustomApi = new CustomApi();
			add_action('wp_ajax_get_google_analytics_reports', array($this, 'get_google_analytics_reports'));
			add_action('wp_ajax_get_google_ads_reports_chart', array($this, 'get_google_ads_reports_chart'));
			add_action('wp_ajax_get_google_ads_campaign_performance', array($this, 'get_google_ads_campaign_performance'));
			add_action('wp_ajax_get_ga_source_performance', array($this, 'get_ga_source_performance'));
			add_action('wp_ajax_get_ga_product_performance', array($this, 'get_ga_product_performance'));
			add_action('wp_ajax_set_email_configurationGA4', array($this, 'set_email_configurationGA4'));
			add_action('wp_ajax_get_google_analytics_order_performance', array($this, 'get_google_analytics_order_performance'));
			add_action('wp_ajax_generate_ai_response', array($this, 'generate_ai_response'));
			add_action('wp_ajax_save_all_reports', array($this, 'save_all_reports'));
			add_action('wp_ajax_get_ecomm_checkout_funnel', array($this, 'get_ecomm_checkout_funnel'));
			add_action('wp_ajax_save_prompt_suggestions', array($this, 'save_prompt_suggestions'));
		}

		public function req_int()
		{
			if (!class_exists('ShoppingApi')) {
				require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/ShoppingApi.php');
			}
			if (!class_exists('CustomApi')) {
				require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
			}
		}
		protected function admin_safe_ajax_call($nonce, $registered_nonce_name)
		{
			// only return results when the user is an admin with manage options
			if (is_admin() && wp_verify_nonce($nonce, $registered_nonce_name)) {
				return true;
			} else {
				return false;
			}
		}
		public function save_prompt_suggestions()
		{
			$nonce = isset($_POST['conversios_nonce']) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";

				if( isset($_POST['data']) )
					$suggestions = is_array($_POST['data']) ? array_map('sanitize_text_field', $_POST['data']) : sanitize_text_field($_POST['data']);
				else
					$suggestions = "";

				$domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : "";
				if($subscription_id != "" && $domain != "" ) {
					if( !empty($suggestions )) {
						$suggestions['date'] = gmdate('Y-m-d');
						$api_rs = $this->ShoppingApi->save_suggestions($subscription_id, $suggestions, $domain);
						echo wp_json_encode($api_rs);
					}else{
						echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Please fill in any suggestions to be submitted.", "enhanced-e-commerce-for-woocommerce-store")));	
					}
				}else{
					echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Invalid Request.", "enhanced-e-commerce-for-woocommerce-store")));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			wp_die();
		}
		public function save_all_reports()
		{
			$nonce = isset($_POST['conversios_nonce']) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
				$ga4_analytic_account_id = isset($_POST['ga4_analytic_account_id']) ? sanitize_text_field($_POST['ga4_analytic_account_id']) : "";
				$ga4_property_id = isset($_POST['property_id']) ? sanitize_text_field($_POST['property_id']) : "";
				$measurement_id = isset($_POST['measurement_id']) ? sanitize_text_field($_POST['measurement_id']) : "";
				$start_date = gmdate('Y-m-d', strtotime('-45 day'));
				$end_date =  gmdate('Y-m-d');
				$domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : "";
				$google_ads_id = isset($_POST['google_ads_id']) ? sanitize_text_field($_POST['google_ads_id']) : "";
				if($subscription_id != "" && $domain != "" ) {
					if($ga4_property_id != "" || $google_ads_id != "") {
						$api_rs = $this->ShoppingApi->save_all_reports($subscription_id,$start_date,$end_date, $domain, $ga4_analytic_account_id, $ga4_property_id, $google_ads_id, $measurement_id);
						echo wp_json_encode($api_rs);
					}else{
						echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Required parameters not found.", "enhanced-e-commerce-for-woocommerce-store")));	
					}
				}else{
					echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Invalid Request.", "enhanced-e-commerce-for-woocommerce-store")));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			wp_die();	
		}
		public function generate_ai_response()
		{
			$nonce = isset($_POST['conversios_nonce']) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
				$key = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : "";
				$domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : "";
				if ($key !="" && $domain !="" && $subscription_id !="" ) {
						$ai_flag= "1";
						$api_rs = $this->ShoppingApi->generate_ai_response($subscription_id, $key, $domain,$ai_flag);
						//print_r($api_rs->data); die;
						if(isset($api_rs->error) && $api_rs->error == false){
							if(isset($api_rs->data) && !empty($api_rs->data)){
								$allPrompts = array(
									"SourceSales25" => "source_performance_ga4",
									"SourceConv20" => "source_performance_ga4",
									"SourceProfit20" => "source_performance_ga4",
									"ProductConv15" => "product_performance_ga4",
									"Productlowperform" => "product_performance_ga4",
									"CampaignPerformImprove" => "campaign_performance"
								);
						//save in wp database add/update query
						$InsertData = array(
							'prompt_key' => esc_sql($key),
							'ai_response' =>esc_sql($api_rs->data),
							'subscription_id' => esc_sql($subscription_id),
							'report_cat'=> esc_sql($allPrompts[$key]),
							'last_prompt_date'=>gmdate('Y-m-d H:i:s'),
							'updated_date'=>gmdate('Y-m-d H:i:s')
						);
						$where = "prompt_key = '" . esc_sql($key) . "'";
						$existing_record = $this->TVC_Admin_DB_Helper->tvc_check_row('ee_ai_reportdata', $where);
						if ($existing_record == "0") { //insert new
							$InsertData['created_date'] = gmdate('Y-m-d H:i:s');
							$this->TVC_Admin_DB_Helper->tvc_add_row('ee_ai_reportdata', $InsertData);
						} else { //update existing
							$this->TVC_Admin_DB_Helper->tvc_update_row('ee_ai_reportdata', $InsertData, array('prompt_key' => $InsertData['prompt_key']));
						}
					 }
					}
					//return response for display
					echo wp_json_encode($api_rs);
				}else{
						echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Required fields missing.", "enhanced-e-commerce-for-woocommerce-store")));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			wp_die();
		}
		public function set_email_configurationGA4()
		{
			$nonce = isset($_POST['conversios_nonce']) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
				$is_disabled = isset($_POST['is_disabled']) ? sanitize_text_field($_POST['is_disabled']) : "";
				$custom_email = isset($_POST['custom_email']) ? sanitize_text_field($_POST['custom_email']) : "";
				$email_frequency = isset($_POST['email_frequency']) ? sanitize_text_field($_POST['email_frequency']) : "";
				
				if ($subscription_id != "" && $is_disabled != "" && $custom_email != "" && $email_frequency != "") {
					$api_rs = $this->ShoppingApi->set_email_configurationGA4($subscription_id, $is_disabled, $custom_email, $email_frequency);
					echo wp_json_encode($api_rs);
				} else {
					echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Invalid required fields", "enhanced-e-commerce-for-woocommerce-store")));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			wp_die();
		}

		public function get_google_analytics_order_performance()
		{
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) { 
				$domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : "";
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));

				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$limit = isset($_POST['length'])?sanitize_text_field($_POST['length']):"10";
				$dimensionNamefilter = isset($_POST['search']['value'])?sanitize_text_field($_POST['search']['value']):"";
				$offset = isset($_POST['start'])?sanitize_text_field($_POST['start']):"0";
				$api_rs = $this->ShoppingApi->order_performance($start_date, $end_date, $limit, $offset, $dimensionNamefilter,$domain);

				if (isset($api_rs->error) && $api_rs->error == '') {
					if (isset($api_rs->data) && $api_rs->data != "") {
						$recievedArr = array();
						$recievedArr = json_decode($api_rs->data);
						$recordsTotal = $recievedArr->recordsTotal;
						$recordsFiltered = $recievedArr->recordsFiltered;
						//$currencyCode = $recievedArr->currencyCode;
						unset($recievedArr->recordsTotal);
						unset($recievedArr->recordsFiltered);
						unset($recievedArr->currencyCode);

						if( isset($_POST['draw']) )
							$draw = is_array($_POST['draw']) ? array_map('sanitize_text_field', $_POST['draw']) : sanitize_text_field($_POST['draw']);
						else
							$draw = "";

						$FinalArr = array('data' => (array)$recievedArr, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'draw' =>  $draw,'error' => false);
						echo wp_json_encode($FinalArr);
					}
				} else {
					$errormsg = isset($api_rs->errors[0]) ? $api_rs->errors[0] : "";
					echo wp_json_encode(array('error' => true, 'errors' => $errormsg,  'status' => $api_rs->status));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			// echo wp_json_encode($return);
			wp_die();
		}

		public function get_ga_product_performance()
		{
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));

				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$limit = isset($_POST['length']) ? sanitize_text_field($_POST['length']) : '';
				$dimensionNamefilter = isset($_POST['search']['value']) ? sanitize_text_field($_POST['search']['value']) : '';
				$offset = isset($_POST['start']) ? sanitize_text_field($_POST['start']) : '';
				$api_rs = $this->ShoppingApi->product_performance(2, 7, $start_date, $end_date, $limit, $offset, $dimensionNamefilter);

				if (isset($api_rs->error) && $api_rs->error == '') {
					if (isset($api_rs->data) && $api_rs->data != "") {
						$recievedArr = array();
						$recievedArr = json_decode($api_rs->data);
						$recordsTotal = $recievedArr->recordsTotal;
						$recordsFiltered = $recievedArr->recordsFiltered;
						//$currencyCode = $recievedArr->currencyCode;
						unset($recievedArr->recordsTotal);
						unset($recievedArr->recordsFiltered);
						unset($recievedArr->currencyCode);

						if( isset($_POST['draw']) )
							$draw = is_array($_POST['draw']) ? array_map('sanitize_text_field', $_POST['draw']) : sanitize_text_field($_POST['draw']);
						else
							$draw = "";

						$FinalArr = array('data' => (array)$recievedArr, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'draw' => $draw);
						echo wp_json_encode($FinalArr);
					}
				} else {
					$errormsg = isset($api_rs->errors[0]) ? $api_rs->errors[0] : "";
					echo wp_json_encode(array('error' => true, 'errors' => $errormsg,  'status' => $api_rs->status));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			// echo wp_json_encode($return);
			wp_die();
		}
		public function get_ga_source_performance()
		{
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));

				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$api_rs = $this->ShoppingApi->source_performance(2, 7, $start_date, $end_date);
				if (isset($api_rs->error) && $api_rs->error == '') {
					if (isset($api_rs->data) && $api_rs->data != "") {
						$return = array('error' => false, 'data' => $api_rs->data);
					}
				} else {
					$errormsg = isset($api_rs->errors[0]) ? $api_rs->errors[0] : "";
					$return = array('error' => true, 'errors' => $errormsg,  'status' => $api_rs->status);
				}
			} else {
				$return = array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store"));
			}
			echo wp_json_encode($return);
			wp_die();
		}
		public function get_google_ads_campaign_performance()
		{
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));

				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$limit = (isset($_POST['limit'])) ? sanitize_text_field($_POST['limit']) : "";
				if ($limit != "") {
					$api_rs = $this->ShoppingApi->campaign_performance(2, 7, $start_date, $end_date, $limit);
				} else {
					$api_rs = $this->ShoppingApi->campaign_performance(2, 7, $start_date, $end_date);
				}
				if (isset($api_rs->error) && $api_rs->error == '') {
					if (isset($api_rs->data) && $api_rs->data != "") {
						$return = array('error' => false, 'data' => $api_rs->data);
					}
				} else {
					$errormsg = isset($api_rs->errors[0]) ? $api_rs->errors[0] : "";
					$return = array('error' => true, 'errors' => $errormsg,  'status' => $api_rs->status);
				}
			} else {
				$return = array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store"));
			}
			echo wp_json_encode($return);
			wp_die();
		}
		public function get_google_ads_reports_chart()
		{
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));
				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$api_rs = $this->ShoppingApi->accountPerformance_for_dashboard($start_date, $end_date);
				if (isset($api_rs->error) && $api_rs->error == false) {
					if (isset($api_rs->data) /*&& !empty($api_rs->data)*/ ) {
						$return = array('error' => false, 'data' => $api_rs->data);
					}else{ 
						$data = array();
						$return = array('error' => true, 'data' => $data);
					}
				} else {
					$errormsg = isset($api_rs->errors->message) ? $api_rs->errors->message : "";
					$status = isset($api_rs->status) ? $api_rs->status : "";
					$return = array('error' => true, 'errors' => $errormsg, 'status' => $status );
				}
			} else {
				$return = array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store"));
			}
			echo wp_json_encode($return);
			wp_die();
		}
		public function get_google_analytics_reports()
		{
			
			$nonce = (isset($_POST['conversios_nonce'])) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				// $post_data = (object)$_POST;
				$subscription_id = sanitize_text_field(isset($_POST['subscription_id']) ? $_POST['subscription_id'] : "");
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));
				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$return = array();
				if ($subscription_id != "" ) {
					$api_rs = "";		
					$data = array(
						'subscription_id' => sanitize_text_field($subscription_id),
						'start_date' => $start_date,
						'end_date' => $end_date
					);
					
					$api_rs = $this->CustomApi->get_google_analytics_reports_ga4($data);	
					if (isset($api_rs->error) && $api_rs->error == '') {
						if (isset($api_rs->data) && $api_rs->data != "") {
							$return = array('error' => false, 'data' => $api_rs->data, 'errors' => '');
						}
					} else {										
						$return = array('error' => true, 'errors' => isset($api_rs->message) ? $api_rs->message : '');
					}
				} else {
					$return = array('error' => true, 'errors' =>esc_html__("Subscription id is null.", "enhanced-e-commerce-for-woocommerce-store"));
				}
			} else {
				$return = array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store"));
			}
			echo wp_json_encode($return);
			wp_die();
		}

		public function get_ecomm_checkout_funnel()
		{
			$nonce = isset($_POST['conversios_nonce']) ? sanitize_text_field($_POST['conversios_nonce']) : "";
			if ($this->admin_safe_ajax_call($nonce, 'conversios_nonce')) {
				$domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : "";
				$start_date = str_replace(' ', '', (isset($_POST['start_date'])) ? sanitize_text_field($_POST['start_date']) : "");
				if ($start_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $start_date);
					$start_date = $date->format('Y-m-d');
				}
				$start_date == (false !== strtotime($start_date)) ? gmdate('Y-m-d', strtotime($start_date)) : gmdate('Y-m-d', strtotime('-1 month'));

				$end_date = str_replace(' ', '', (isset($_POST['end_date'])) ? sanitize_text_field($_POST['end_date']) : "");
				if ($end_date != "") {
					$date = DateTime::createFromFormat('d-m-Y', $end_date);
					$end_date = $date->format('Y-m-d');
				}
				$end_date == (false !== strtotime($end_date)) ? gmdate('Y-m-d', strtotime($end_date)) : gmdate('Y-m-d', strtotime('now'));

				$start_date = sanitize_text_field($start_date);
				$end_date = sanitize_text_field($end_date);
				$api_rs = $this->ShoppingApi->ecommerce_checkout_funnel($start_date, $end_date, $domain);
				
				if (isset($api_rs->error) && $api_rs->error == '') {
					if (isset($api_rs->data) && $api_rs->data != "") {
						echo wp_json_encode(array('error' => false, 'data' => $api_rs->data));
					}
				} else {
					$errormsg = isset($api_rs->errors[0]) ? $api_rs->errors[0] : "";
					echo wp_json_encode(array('error' => true, 'errors' => $errormsg,  'status' => $api_rs->status));
				}
			} else {
				echo wp_json_encode(array('error' => true, 'errors' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
			}
			wp_die();	
		}
	}
}
new Conversios_Reports_Helper();