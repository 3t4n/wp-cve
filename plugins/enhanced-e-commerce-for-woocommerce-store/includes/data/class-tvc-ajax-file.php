<?php

/**
 * TVC Ajax File Class.
 *
 * @package TVC Product Feed Manager/Data/Classes
 */
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('TVC_Ajax_File')) :
  /**
   * Ajax File Class
   */
  class TVC_Ajax_File extends TVC_Ajax_Calls
  {
    private $apiDomain;
    protected $access_token;
    protected $refresh_token;
    public function __construct()
    {
      parent::__construct();
      $this->apiDomain = TVC_API_CALL_URL;
      // hooks
      add_action('wp_ajax_tvcajax-get-campaign-categories', array($this, 'tvcajax_get_campaign_categories'));
      add_action('wp_ajax_tvcajax-update-campaign-status', array($this, 'tvcajax_update_campaign_status'));
      add_action('wp_ajax_tvcajax-delete-campaign', array($this, 'tvcajax_delete_campaign'));

      add_action('wp_ajax_tvcajax-gmc-category-lists', array($this, 'tvcajax_get_gmc_categories'));
      //add_action('wp_ajax_tvcajax-custom-metrics-dimension', array($this, 'tvcajax_custom_metrics_dimension'));
      add_action('wp_ajax_tvcajax-store-time-taken', array($this, 'tvcajax_store_time_taken'));

      add_action('wp_ajax_tvc_call_api_sync', array($this, 'tvc_call_api_sync'));
      add_action('wp_ajax_tvc_call_import_gmc_product', array($this, 'tvc_call_import_gmc_product'));
      add_action('wp_ajax_tvc_call_domain_claim', array($this, 'tvc_call_domain_claim'));
      add_action('wp_ajax_tvc_call_site_verified', array($this, 'tvc_call_site_verified'));
      add_action('wp_ajax_tvc_call_notice_dismiss', array($this, 'tvc_call_notice_dismiss'));
      add_action('wp_ajax_tvc_call_notice_dismiss_trigger', array($this, 'tvc_call_notice_dismiss_trigger'));
      add_action('wp_ajax_tvc_call_notification_dismiss', array($this, 'tvc_call_notification_dismiss'));
      add_action('wp_ajax_auto_product_sync_setting', array($this, 'auto_product_sync_setting'));
      add_action('wp_ajax_con_get_conversion_list', array($this, 'con_get_conversion_list'));
      add_action('wp_ajax_tvc_call_active_licence', array($this, 'tvc_call_active_licence'));
      add_action('wp_ajax_tvc_call_add_survey', array($this, 'tvc_call_add_survey'));
      add_action('wp_ajax_cov_save_badge_settings', array($this, 'cov_save_badge_settings'));

      add_action('wp_ajax_tvc_call_add_customer_feedback', array($this, 'tvc_call_add_customer_feedback'));
      add_action('wp_ajax_tvc_call_add_customer_featurereq', array($this, 'tvc_call_add_customer_featurereq'));

      // Not in use after product sync from backend
      //add_action('wp_ajax_tvcajax_product_sync_bantch_wise', array($this, 'tvcajax_product_sync_bantch_wise'));//deprecated!
      add_action('wp_ajax_update_user_tracking_data', array($this, 'update_user_tracking_data'));
      add_action('init_product_sync_process_scheduler', array($this, 'tvc_call_start_product_sync_process'), 10, 1);
      add_action('wp_ajax_auto_product_sync_process_scheduler', array($this, 'tvc_call_start_product_sync_process'));

      // For new UIUX
      add_action('wp_ajax_conv_save_pixel_data', array($this, 'conv_save_pixel_data'));
      add_action('wp_ajax_conv_save_googleads_data', array($this, 'conv_save_googleads_data'));
      add_action('wp_ajax_conv_get_conversion_list_gads', array($this, 'conv_get_conversion_list_gads'));
      add_action('wp_ajax_save_category_mapping', [$this, 'save_category_mapping']);
      add_action('wp_ajax_save_attribute_mapping', [$this, 'save_attribute_mapping']);
      add_action('wp_ajax_save_feed_data', [$this, 'save_feed_data']);
      add_action('wp_ajax_get_feed_data_by_id', [$this, 'get_feed_data_by_id']);
      add_action('wp_ajax_ee_duplicate_feed_data_by_id', [$this, 'ee_duplicate_feed_data_by_id']);
      add_action('wp_ajax_ee_get_product_details_for_table', [$this, 'ee_get_product_details_for_table']);
      add_action('wp_ajax_ee_delete_feed_data_by_id', [$this, 'ee_delete_feed_data_by_id']);
      add_action('wp_ajax_ee_delete_feed_gmc', [$this, 'ee_delete_feed_gmc']);
      add_action('wp_ajax_ee_get_product_status', [$this, 'ee_get_product_status']);
      add_action('wp_ajax_ee_syncProductCategory', [$this, 'ee_syncProductCategory']);
      add_action('wp_ajax_ee_feed_wise_product_sync_batch_wise', [$this, 'ee_feed_wise_product_sync_batch_wise']);
      add_action('init_feed_wise_product_sync_process_scheduler_ee', [$this, 'ee_call_start_feed_wise_product_sync_process']);
      add_action('auto_feed_wise_product_sync_process_scheduler_ee', [$this, 'ee_call_auto_feed_wise_product_sync_process']);
      add_action('wp_ajax_ee_super_AI_feed', [$this, 'ee_super_AI_feed']);
      add_action('wp_ajax_get_tiktok_business_account', [$this, 'get_tiktok_business_account']);
      add_action('wp_ajax_get_tiktok_user_catalogs', [$this, 'get_tiktok_user_catalogs']);
      add_action('wp_ajax_ee_getCatalogId', [$this, 'ee_getCatalogId']);

      // For EC
      add_action('wp_ajax_conv_create_ec_row', array($this, 'conv_create_ec_row'));
      add_action('wp_ajax_conv_create_ec_row_update', array($this, 'conv_create_ec_row_update'));
      add_action('wp_ajax_ee_createPmaxCampaign', [$this, 'ee_createPmaxCampaign']);

      add_action('wp_ajax_conv_get_conversion_list_gads_bycat', [$this, 'conv_get_conversion_list_gads_bycat']);
      add_action('wp_ajax_conv_create_gads_conversion', [$this, 'conv_create_gads_conversion']);
      add_action('wp_ajax_conv_save_gads_conversion', [$this, 'conv_save_gads_conversion']);
      add_action('wp_ajax_get_pf_accordian_data', array($this, 'get_pf_accordian_data'));
      add_action('wp_ajax_get_category_for_filter', [$this, 'get_category_for_filter']);
      add_action('wp_ajax_get_product_filter_count', [$this, 'get_product_filter_count']);
      add_action('wp_ajax_get_attribute_mappingv_div', [$this, 'get_attribute_mappingv_div']);
      add_action('wp_ajax_create_dashboard_feed_data', [$this, 'create_dashboard_feed_data']);
    }

    public function conv_create_ec_row()
    {
      if ($this->safe_ajax_call(sanitize_text_field($_POST['pix_sav_nonce']), 'pix_sav_nonce_val')) {
        $customObj = new CustomApi();
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
        $ecrandomstring = isset($_POST['ecrandomstring']) ? sanitize_text_field($_POST['ecrandomstring']) : "";
        $formdata = array("subscription_id" => $subscription_id, "ec_token" => $ecrandomstring);
        echo wp_json_encode($customObj->conv_create_ec_row_apicall($formdata));
      }
      die();
    }


    public function conv_create_ec_row_update()
    {
      $customObj = new CustomApi();
      $datadump_main = wp_json_encode(json_decode(stripslashes(sanitize_text_field($_POST['convReportData'])), true));
      $is_generate = isset($_POST['is_generate']) && $_POST['is_generate'] == 1 ? 1 : 0;
      $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
      $ec_token = isset($_POST['ec_token']) ? sanitize_text_field($_POST['ec_token']) : "";
      $formdata = array(
        "subscription_id" => $subscription_id,
        "ec_token" => $ec_token,
        "report_data" => $datadump_main,
        "is_generate" => $is_generate
      );
      //echo '<pre>'; print_r($formdata); echo '</pre>';
      echo wp_json_encode($customObj->conv_create_ec_row_apicall($formdata));
      die();
    }

    // Save data in ee_options
    public function conv_save_data_eeoption($data)
    {
      $ee_options = unserialize(get_option('ee_options'));
      foreach ($data['conv_options_data'] as $key => $conv_options_data) {
        if ($key == "conv_selected_events") {
          continue;
        }
        $key_name = $key;
        $key_name_arr = array();
        $key_name_arr["measurement_id"] = "gm_id";
        $key_name_arr["property_id"] = "ga_id";
        if (key_exists($key_name, $key_name_arr)) {
          $ee_options[$key_name_arr[$key_name]] = sanitize_text_field($conv_options_data);
        } else {

          if (is_array($conv_options_data)) {
            $posted_arr = $conv_options_data;
            $posted_arr_temp = [];
            if (!empty($posted_arr)) {
              $arr = $posted_arr;
              array_walk($arr, function (&$value) {
                $value = sanitize_text_field($value);
              });
              $posted_arr_temp = $arr;
              $ee_options[$key_name] = $posted_arr_temp;
            }
          } else {
            $ee_options[$key_name] = sanitize_text_field($conv_options_data);
          }
        }
      }
      update_option('ee_options', serialize($ee_options));
    }

    // Save data in ee_options
    public function conv_save_data_eeapidata($data)
    {
      $eeapidata = unserialize(get_option('ee_api_data'));
      $eeapidata_settings = $eeapidata['setting'];
      if (empty($eeapidata_settings)) {
        $eeapidata_settings = new stdClass();
      }

      foreach ($data['conv_options_data'] as $key => $conv_options_data) {
        if ($key == "conv_selected_events") {
          continue;
        }

        $key_name = $key;

        if (is_array($conv_options_data)) {
          $posted_arr = $conv_options_data;
          $posted_arr_temp = [];
          if (!empty($posted_arr)) {
            $arr = $posted_arr;
            array_walk($arr, function (&$value) {
              $value = sanitize_text_field($value);
            });
            $posted_arr_temp = $arr;
            $eeapidata_settings->$key_name = $posted_arr_temp;
          }
        } else {
          $eeapidata_settings->$key_name = sanitize_text_field($conv_options_data);
          if ($key_name == "google_merchant_center_id") {
            $eeapidata_settings->google_merchant_id = sanitize_text_field($conv_options_data);
          }
        }
      }
      $eeapidata['setting'] = $eeapidata_settings;
      update_option('ee_api_data', serialize($eeapidata));
    }

    //Save data in middleware
    public function conv_save_data_middleware($postDataFull = array())
    {
      $postData = $postDataFull['conv_options_data'];

      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = array();
        foreach ($postData as $key => $value) {
          $data[$key] = sanitize_text_field((isset($value)) ? $value : '');
        }

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = wp_remote_request(esc_url_raw($url), $args);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    // Save data in ee_convnotices
    public function conv_save_eeconvnotice($data)
    {
      $ee_eeconvnotice = get_option('ee_convnotice', array());
      $keyname = sanitize_text_field($data['conv_options_data']);
      $ee_eeconvnotice[$keyname] = "yes";
      update_option('ee_convnotice', $ee_eeconvnotice);
    }


    // All new functions for new UIUX
    public function conv_save_pixel_data()
    {
      if ($this->safe_ajax_call(sanitize_text_field($_POST['pix_sav_nonce']), 'pix_sav_nonce_val')) {
        $post = array(
          "conv_options_data" => "",
          "conv_options_type" => "",
          "conv_tvc_data" => "",
          "update_site_domain" => "",
          "customer_subscription_id" => "",
          "conv_catalogData" => "",
        );
        $post = array_intersect_key($_POST, $post);

        $TVC_Admin_Helper = new TVC_Admin_Helper();
        if (in_array("eeoptions", $_POST['conv_options_type'])) {
          $this->conv_save_data_eeoption($post);
        }
        if (in_array("middleware", $_POST['conv_options_type'])) {
          $this->conv_save_data_middleware($post);
        }
        if (in_array("eeapidata", $_POST['conv_options_type'])) {
          $this->conv_save_data_eeapidata($post);
        }
        if (in_array("eeapidata", $_POST['conv_options_type'])) {
          if (isset($_POST['update_site_domain']) && $_POST['update_site_domain'] === 'update') {
            $post['conv_options_data']['is_site_verified'] = '0';
            $post['conv_options_data']['is_domain_claim'] = '0';
          }
          $this->conv_save_data_eeapidata($post);
        }
        if (isset($_POST['conv_options_data']['ga_GMC']) && $_POST['conv_options_data']['ga_GMC'] == '1') {
          $access_token = $this->get_tvc_access_token();
          $refresh_token = $this->get_tvc_refresh_token();
          $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($access_token), sanitize_text_field($refresh_token));
          $postData = ['subscription_id' => sanitize_text_field($_POST['conv_options_data']['subscription_id']), 'merchant_id' => sanitize_text_field($_POST['conv_options_data']['merchant_id']), 'account_id' => sanitize_text_field($_POST['conv_options_data']['google_merchant_id']), 'adwords_id' => sanitize_text_field($_POST['conv_options_data']['google_ads_id'])];
          $api_obj->linkGoogleAdsToMerchantCenter($postData);
        }
        if (in_array("eeselectedevents", $_POST['conv_options_type'])) {
          $selectedevents = sanitize_text_field($_POST["conv_options_data"]["conv_selected_events"]);

          $conv_posted_events = [];
          if (!empty($selectedevents)) {
            $arr = $selectedevents;
            array_walk($arr, function (&$value) {
              $temp_arr = [];
              for ($i = 0; $i < count($value); $i++) {
                $temp_arr[] = sanitize_text_field($value[$i]);
              }
              $value = $temp_arr;
            });
            $conv_posted_events = $arr;
          }
          update_option("conv_selected_events", serialize($conv_posted_events));
        }
        if (in_array("tiktokmiddleware", $_POST['conv_options_type'])) {
          $this->conv_save_tiktokmiddleware($post);
        }
        if (in_array("tiktokcatalog", $_POST['conv_options_type'])) {
          $this->conv_save_tiktokcatalog($post);
        }

        if (in_array("eeconvnotice", $_POST['conv_options_type'])) {
          $this->conv_save_eeconvnotice($post);
        }

        $TVC_Admin_Helper->update_app_status();
        $TVC_Admin_Helper->update_subscription_details_api_to_db();
        echo "1";
      } else {
        echo "0";
      }
      exit;
    }
    // All new functions for new UIUX End

    // Save google ads settings
    public function conv_save_googleads_data()
    {
      if ($this->safe_ajax_call(sanitize_text_field($_POST['pix_sav_nonce']), 'pix_sav_nonce_val')) {
        $conv_options_data = is_array($_POST['conv_options_data']) ? array_map('sanitize_text_field', $_POST['conv_options_data']) : sanitize_text_field($_POST['conv_options_data']);
        $googleDetail_setting = array();
        if (isset($conv_options_data['remarketing_tags'])) {
          update_option('ads_ert', sanitize_text_field($conv_options_data['remarketing_tags']));
          $googleDetail_setting["remarketing_tags"] = sanitize_text_field($conv_options_data['remarketing_tags']);
        }
        if (isset($conv_options_data['dynamic_remarketing_tags'])) {
          update_option('ads_edrt', sanitize_text_field($conv_options_data['dynamic_remarketing_tags']));
          $googleDetail_setting["dynamic_remarketing_tags"] = sanitize_text_field($conv_options_data['dynamic_remarketing_tags']);
        }

        if (isset($conv_options_data['google_ads_conversion_tracking'])) {
          update_option('google_ads_conversion_tracking', sanitize_text_field($conv_options_data['google_ads_conversion_tracking']));
          $googleDetail_setting["google_ads_conversion_tracking"] = sanitize_text_field($conv_options_data['google_ads_conversion_tracking']);
        }

        if (isset($conv_options_data['ga_EC'])) {
          update_option('ga_EC', sanitize_text_field($conv_options_data['ga_EC']));
        }

        if (isset($conv_options_data['ee_conversio_send_to'])) {
          update_option('ee_conversio_send_to', sanitize_text_field($conv_options_data['ee_conversio_send_to']));
          $googleDetail_setting["ee_conversio_send_to"] = sanitize_text_field($conv_options_data['ee_conversio_send_to']);
        }

        if (isset($conv_options_data['ee_conversio_send_to_static']) && !empty($conv_options_data['ee_conversio_send_to_static'])) {
          update_option('ee_conversio_send_to', sanitize_text_field($conv_options_data['ee_conversio_send_to_static']));
          $googleDetail_setting["ee_conversio_send_to"] = sanitize_text_field($conv_options_data['ee_conversio_send_to_static']);
        }

        if (isset($conv_options_data['link_google_analytics_with_google_ads'])) {
          $googleDetail_setting["link_google_analytics_with_google_ads"] = sanitize_text_field($conv_options_data['link_google_analytics_with_google_ads']);
        }

        $googleDetail_setting["subscription_id"] = sanitize_text_field($conv_options_data['subscription_id']);

        $data_eeoptions = array();
        $data_eeapidata = array();
        $data_middleware = array();

        $data_eeoptions['conv_options_data']['google_ads_id'] = $conv_options_data['google_ads_id'];
        if ($conv_options_data['ga_GMC'] == '1') {
          $data_eeoptions['conv_options_data']['ga_GMC'] = sanitize_text_field($conv_options_data['ga_GMC']);
        }
        $this->conv_save_data_eeoption($data_eeoptions);

        $data_eeapidata['conv_options_data'] = $conv_options_data;
        $this->conv_save_data_eeapidata($data_eeapidata);

        $googleDetail_setting['google_ads_id'] = sanitize_text_field($conv_options_data['google_ads_id']);
        $data_middleware['conv_options_data'] = $googleDetail_setting;

        $this->conv_save_data_middleware($data_middleware);

        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $TVC_Admin_Helper->update_remarketing_snippets();
        $TVC_Admin_Helper->update_app_status();
        if ($conv_options_data['ga_GMC'] == '1') {
          $access_token = $this->get_tvc_access_token();
          $refresh_token = $this->get_tvc_refresh_token();
          $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($access_token), sanitize_text_field($refresh_token));
          $postData = ['subscription_id' => sanitize_text_field($_POST['conv_options_data']['subscription_id']), 'merchant_id' => sanitize_text_field($_POST['conv_options_data']['merchant_id']), 'account_id' => sanitize_text_field($_POST['conv_options_data']['google_merchant_id']), 'adwords_id' => sanitize_text_field($_POST['conv_options_data']['google_ads_id'])];
          $api_obj->linkGoogleAdsToMerchantCenter($postData);
        }
      }
      echo "1";
      exit;
    }


    public function cov_save_badge_settings()
    {
      $val = isset($_POST['bagdeVal']) ? sanitize_text_field($_POST['bagdeVal']) : "no";
      $data = array();
      $data = unserialize(get_option('ee_options'));
      $data['conv_show_badge'] = sanitize_text_field($val);
      if ($val == "yes") {
        $data['conv_badge_position'] = sanitize_text_field("center");
      } else {
        $data['conv_badge_position'] = "";
      }
      update_option('ee_options', serialize($data));
      exit;
    }

    public function update_user_tracking_data()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'update_user_tracking_data-nonce')) {
        $event_name = isset($_POST['event_name']) ? sanitize_text_field($_POST['event_name']) : "";
        $screen_name = isset($_POST['screen_name']) ? sanitize_text_field($_POST['screen_name']) : "";
        $error_msg = isset($_POST['error_msg']) ? sanitize_text_field($_POST['error_msg']) : "";
        $event_label = isset($_POST['event_label']) ? sanitize_text_field($_POST['event_label']) : "";
        // $timestamp = isset($_POST['timestamp'])?sanitize_text_field($_POST['timestamp']):"";
        $timestamp = gmdate("YmdHis");
        $t_data = array(
          'event_name' => esc_sql($event_name),
          'screen_name' => esc_sql($screen_name),
          'timestamp' => esc_sql($timestamp),
          'error_msg' => esc_sql($error_msg),
          'event_label' => esc_sql($event_label),
        );
        if (!empty($t_data)) {

          $options_val = get_option('ee_ut');
          if (!empty($options_val)) {
            $odata = (array) maybe_unserialize($options_val);
            array_push($odata, $t_data);
            update_option("ee_ut", serialize($odata));
          } else {
            $t_d[] = $t_data;
            update_option("ee_ut", serialize($t_d));
          }
        }
        wp_die();
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    /*
     * this function deprecated!
    public function tvcajax_product_sync_bantch_wise()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'tvcajax_product_sync_bantch_wise-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        try {
          $mappedCats = [];
          $mappedAttrs = [];
          $mappedCatsDB = [];
          $product_batch_size = isset($_POST['product_batch_size']) ? sanitize_text_field($_POST['product_batch_size']) : "25"; // barch size for inser product in GMC
          $data = $_POST['tvc_data']; //is_array($_POST['tvc_data']) ? array_map('sanitize_text_field', $_POST['tvc_data']) : sanitize_text_field($_POST['tvc_data']);

          $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
          parse_str($data, $formArray);
          if (!empty($formArray)) {
            foreach ($formArray as $key => $value) {
              $formArray[$key] = sanitize_text_field($value);
            }
          }

          // Collect Attribute/Categories Mapping
           
          foreach ($formArray as $key => $value) {
            if (preg_match("/^category-name-/i", $key)) {
              if ($value != '') {
                $keyArray = explode("name-", $key);
                $mappedCatsDB[$keyArray[1]]['name'] = $value;
              }
              unset($formArray[$key]);
            } else if (preg_match("/^category-/i", $key)) {
              if ($value != '' && $value > 0) {
                $keyArray = explode("-", $key);
                $mappedCats[$keyArray[1]] = $value;
                $mappedCatsDB[$keyArray[1]]['id'] = $value;
              }
              unset($formArray[$key]);
            } else {
              if ($value) {
                $mappedAttrs[$key] = $value;
              }
            }
          }

          //add/update data in default profile
          $profile_data = array("profile_title" => esc_sql("Default"), "g_attribute_mapping" => wp_json_encode($mappedAttrs), "update_date" => gmdate('Y-m-d'));
          if ($TVC_Admin_DB_Helper->tvc_row_count("ee_product_sync_profile") == 0) {
            $TVC_Admin_DB_Helper->tvc_add_row("ee_product_sync_profile", $profile_data, array("%s", "%s", "%s"));
          } else {
            $TVC_Admin_DB_Helper->tvc_update_row("ee_product_sync_profile", $profile_data, array("id" => 1));
          }
          // Update settings
          update_option("ee_prod_mapped_cats", serialize($mappedCatsDB));
          update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));

          // Batch settings
          $ee_additional_data['is_mapping_update'] = true;
          $ee_additional_data['is_process_start'] = false;
          $ee_additional_data['is_auto_sync_start'] = false;
          $ee_additional_data['product_sync_batch_size'] = $product_batch_size;
          $ee_additional_data['product_sync_alert'] = "Product sync settings updated successfully process will start soon...";
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);

          // add scheduled cron job 
          as_unschedule_all_actions('init_product_sync_process_scheduler');
          as_enqueue_async_action('init_product_sync_process_scheduler');

          $TVC_Admin_Helper->plugin_log("mapping saved and product sync process scheduled", 'product_sync'); // Add logs

          $sync_message = esc_html__("Initiated, products are being synced to Merchant Center.Do not refresh..", "enhanced-e-commerce-for-woocommerce-store");
          $sync_progressive_data = array("sync_message" => $sync_message);
          echo wp_json_encode(array('status' => 'success', "sync_progressive_data" => $sync_progressive_data));
        } catch (Exception $e) {
          $ee_additional_data['product_sync_alert'] = $e->getMessage();
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
          $TVC_Admin_Helper->plugin_log($e->getMessage(), 'product_sync');
        }
        wp_die();
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }*/

    function tvc_call_start_product_sync_process()
    {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      try {
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        as_unschedule_all_actions('init_product_sync_process_scheduler');
        as_schedule_single_action(time(), 'init_product_sync_process_scheduler');
        global $wpdb;
        if (!empty($ee_additional_data) && isset($ee_additional_data['is_mapping_update']) && $ee_additional_data['is_mapping_update'] == true) {
          $product_db_batch_size = 200; // batch size to insert in database
          $prouct_pre_sync_table = esc_sql($wpdb->prefix . "ee_prouct_pre_sync_data");
          $mappedCats = unserialize(get_option('ee_prod_mapped_cats'));
          // Add products in product pre sync table
          if (!empty($mappedCats)) {
            // truncate data from product pre sync table
            if ($TVC_Admin_DB_Helper->tvc_row_count("ee_prouct_pre_sync_data") > 0) {
              $TVC_Admin_DB_Helper->tvc_safe_truncate_table($prouct_pre_sync_table);
            }

            $batch_count = 0;
            $values = array();
            $place_holders = array();
            foreach ($mappedCats as $mc_key => $mappedCat) {
              $term = get_term_by('term_id', $mc_key, 'product_cat', 'ARRAY_A');
              //$TVC_Admin_Helper->plugin_log(" = = = =category id ".wp_json_encode($term), 'product_sync');
              //die;
              $total_page = 1;
              if (isset($term["count"]) && $term["count"] > 1000) {
                $total_page = ceil($term["count"] / 1000);
              }

              for ($i = 1; $i <= $total_page; $i++) {
                $TVC_Admin_Helper->plugin_log("Manual - category > " . $mappedCat['name'] . " > total_page " . wp_json_encode($total_page) . " page > " . $i, 'product_sync');
                $all_products = get_posts(
                  array(
                    'post_type' => 'product',
                    'posts_per_page' => 1000,
                    'paged' => $i,
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $mc_key,
                        /* category name */
                        'operator' => 'IN',
                        'include_children' => false
                      )
                    )
                  )
                );
                $TVC_Admin_Helper->plugin_log("Manual - category id " . $mc_key . " gmc product name " . $mappedCat['name'] . " - product count - " . count($all_products), 'product_sync'); // Add logs
                if (!empty($all_products)) {
                  foreach ($all_products as $postvalue) {
                    $batch_count++;
                    array_push($values, esc_sql($postvalue), esc_sql($mc_key), esc_sql($mappedCat['id']), 1, gmdate('Y-m-d H:i:s', current_time('timestamp')));
                    $place_holders[] = "('%d', '%d', '%d','%d', '%s')";
                    if ($batch_count >= $product_db_batch_size) {
                      $query = "INSERT INTO `$prouct_pre_sync_table` (w_product_id, w_cat_id, g_cat_id, product_sync_profile_id, create_date) VALUES ";
                      $query .= implode(', ', $place_holders);
                      $wpdb->query($wpdb->prepare($query, $values));
                      $batch_count = 0;
                      $values = array();
                      $place_holders = array();
                    }
                  } //end product list loop
                } // end products if
              } // Pagination loop
            } //end category loop

            // Add products in database
            if ($batch_count > 0) {
              $query = "INSERT INTO `$prouct_pre_sync_table` (w_product_id, w_cat_id, g_cat_id, product_sync_profile_id, create_date) VALUES ";
              $query .= implode(', ', $place_holders);
              $wpdb->query($wpdb->prepare($query, $values));
            }
          }

          $ee_additional_data['is_mapping_update'] = false;
          $ee_additional_data['is_process_start'] = true;
          $ee_additional_data['product_sync_alert'] = "Product sync process is ready to start";
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        }

        $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        if (!empty($ee_additional_data) && isset($ee_additional_data['is_process_start']) && $ee_additional_data['is_process_start'] == true) {
          $TVC_Admin_Helper->plugin_log("Manual - product sync process start", 'product_sync');
          if (!class_exists('TVCProductSyncHelper')) {
            include(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php');
          }
          $TVCProductSyncHelper = new TVCProductSyncHelper();
          $response = $TVCProductSyncHelper->call_batch_wise_auto_sync_product();
          if (!empty($response) && isset($response['message'])) {
            $TVC_Admin_Helper->plugin_log("Manual - Batch wise auto sync process response " . $response['message'], 'product_sync');
          }

          $tablename = esc_sql($wpdb->prefix . "ee_prouct_pre_sync_data");
          $total_pending_pro = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) as a FROM %i where `status` = 0", $tablename));
          if ($total_pending_pro == 0) {
            // Truncate pre sync table
            $TVC_Admin_DB_Helper->tvc_safe_truncate_table($tablename);

            $ee_additional_data['is_process_start'] = false;
            $ee_additional_data['is_auto_sync_start'] = true;
            $ee_additional_data['product_sync_alert'] = NULL;
            $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
            $TVC_Admin_Helper->plugin_log("Manual - product sync process done", 'product_sync');
            as_unschedule_all_actions('init_product_sync_process_scheduler');
          } else {
            $TVC_Admin_Helper->plugin_log("Manual - recall product sync process for remaining " . $total_pending_pro . " products", 'product_sync');
          }
        } else {
          $TVC_Admin_Helper->plugin_log("Manual - Nothing to Sync", 'product_sync');
        }
        echo wp_json_encode(array('status' => 'success', "message" => esc_html__("Product sync process started successfully", "enhanced-e-commerce-for-woocommerce-store")));
        return true;
      } catch (Exception $e) {
        $ee_additional_data['product_sync_alert'] = $e->getMessage();
        $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        $TVC_Admin_Helper->plugin_log("Manual - Error - " . $e->getMessage(), 'product_sync');
      }
      return true;
    }


    public function tvc_call_add_customer_featurereq()
    {
      if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['feature_req_nonce'])), 'feature_req_nonce_val')) {
        $formdata = array();
        $formdata['feedback'] = sanitize_text_field($_POST['featurereq_message']);
        $formdata['subscription_id'] = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
        $customObj = new CustomApi();
        unset($_POST['action']);
        echo wp_json_encode($customObj->record_customer_featurereq($formdata));
        exit;
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    public function tvc_call_add_customer_feedback()
    {
      if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['conv_customer_feed_nonce_field'])), 'conv_customer_feed_nonce_field_save')) {
        if (isset($_POST['que_one']) && isset($_POST['que_two']) && isset($_POST['que_three'])) {
          $formdata = array();
          $formdata['business_insights_index'] = sanitize_text_field($_POST['que_one']);
          $formdata['automate_integrations_index'] = sanitize_text_field($_POST['que_two']);
          $formdata['business_scalability_index'] = sanitize_text_field($_POST['que_three']);
          $formdata['subscription_id'] = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
          $formdata['customer_id'] = isset($_POST['customer_id']) ? sanitize_text_field($_POST['customer_id']) : "";
          $formdata['feedback'] = isset($_POST['feedback_description']) ? sanitize_text_field($_POST['feedback_description']) : "";
          $customObj = new CustomApi();
          unset($_POST['action']);
          echo wp_json_encode($customObj->record_customer_feedback($formdata));
          exit;
        } else {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Please answer the required questions", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    public function tvc_call_add_survey()
    {
      if (is_admin() && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tvc_call_add_survey'])), 'tvc_call_add_survey-nonce')) {
        if (!class_exists('CustomApi')) {
          include(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
        }
        $customObj = new CustomApi();
        unset($_POST['action']);
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : "";
        $customer_id = isset($_POST['customer_id']) ? sanitize_text_field($_POST['customer_id']) : "";
        $radio_option_val = isset($_POST['radio_option_val']) ? sanitize_text_field($_POST['radio_option_val']) : "";
        $other_reason = isset($_POST['other_reason']) ? sanitize_text_field($_POST['other_reason']) : "";
        $site_url = isset($_POST['site_url']) ? sanitize_text_field($_POST['site_url']) : "";
        $plugin_name = isset($_POST['plugin_name']) ? sanitize_text_field($_POST['plugin_name']) : "";

        $post = array(
          "customer_id" => $customer_id,
          "subscription_id" => $subscription_id,
          "radio_option_val" => $radio_option_val,
          "other_reason" => $other_reason,
          "site_url" => $site_url,
          "plugin_name" => $plugin_name
        );
        echo wp_json_encode($customObj->add_survey_of_deactivate_plugin($post));
      } else {
        echo wp_json_encode(array('error' => true, "is_connect" => false, 'message' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    //active licence key
    public function tvc_call_active_licence()
    {
      if (is_admin() && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['conv_licence_nonce'])), 'conv_lic_nonce')) {
        $licence_key = isset($_POST['licence_key']) ? sanitize_text_field($_POST['licence_key']) : "";
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $subscription_id = $TVC_Admin_Helper->get_subscriptionId();
        if ($subscription_id != "" && $licence_key != "") {
          $response = $TVC_Admin_Helper->active_licence($licence_key, $subscription_id);

          if ($response->error == false) {
            $TVC_Admin_Helper->update_subscription_details_api_to_db();
            echo wp_json_encode(array('error' => false, "is_connect" => true, 'message' => esc_html__("The licence key has been activated.", "enhanced-e-commerce-for-woocommerce-store")));
          } else {
            echo wp_json_encode(array('error' => true, "is_connect" => true, 'message' => $response->message));
          }
        } else if ($licence_key != "") {
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
          $ee_additional_data['temp_active_licence_key'] = $licence_key;
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
          echo wp_json_encode(array('error' => true, "is_connect" => false, 'message' => ""));
        } else {
          echo wp_json_encode(array('error' => true, "is_connect" => false, 'message' => esc_html__("Licence key is required.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('error' => true, "is_connect" => false, 'message' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function auto_product_sync_setting()
    {
      if (is_admin() && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['auto_product_sync_setting'])), 'auto_product_sync_setting-nonce')) {
        as_unschedule_all_actions('ee_auto_product_sync_check');
        $product_sync_duration = isset($_POST['product_sync_duration']) ? sanitize_text_field($_POST['product_sync_duration']) : "";
        $pro_snyc_time_limit = isset($_POST['pro_snyc_time_limit']) ? sanitize_text_field($_POST['pro_snyc_time_limit']) : "";
        $product_sync_batch_size = isset($_POST['product_sync_batch_size']) ? sanitize_text_field($_POST['product_sync_batch_size']) : "";
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        if ($product_sync_duration != "" && $pro_snyc_time_limit != "" && $product_sync_batch_size != "") {
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
          $ee_additional_data['product_sync_duration'] = $product_sync_duration;
          $ee_additional_data['pro_snyc_time_limit'] = $pro_snyc_time_limit;
          $ee_additional_data['product_sync_batch_size'] = $product_sync_batch_size;
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
          new TVC_Admin_Auto_Product_sync_Helper();
          echo wp_json_encode(array('error' => false, 'message' => esc_html__("Time interval and batch size successfully saved.", "enhanced-e-commerce-for-woocommerce-store")));
        } else {
          echo wp_json_encode(array('error' => true, 'message' => esc_html__("Error occured while saving the settings.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('error' => true, "is_connect" => false, 'message' => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    public function con_get_conversion_list()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'con_get_conversion_list-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $this->customApiObj = new CustomApi();
        $this->current_customer_id = $TVC_Admin_Helper->get_currentCustomerId();
        if ($this->current_customer_id != "") {
          $response = $this->customApiObj->get_conversion_list($this->current_customer_id);
          if (property_exists($response, "error") && $response->error == false) {
            if (property_exists($response, "data") && $response->data != "" && !empty($response->data)) {
              $selected_conversio_send_to = get_option('ee_conversio_send_to');
              $conversion_label = array();
              foreach ($response->data as $key => $value) {
                $con_string = strip_tags($value->tagSnippets);
                $conversion_label_check = $TVC_Admin_Helper->get_conversion_label($con_string);
                if ($conversion_label_check != "" && $conversion_label_check != null) {
                  $conversion_label[] = $TVC_Admin_Helper->get_conversion_label($con_string);
                }
              }
              echo wp_json_encode($conversion_label);
              exit;
            }
          }
        }
      }
      // IMPORTANT: don't forget to exit
      wp_die(0);
    }

    public function conv_get_conversion_list_gads()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'con_get_conversion_list-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $this->customApiObj = new CustomApi();
        $current_customer_id = sanitize_text_field($_POST['gads_id']);
        if ($current_customer_id != "") {
          $response = $this->customApiObj->get_conversion_list($current_customer_id);
          if (property_exists($response, "error") && $response->error == false) {
            if (property_exists($response, "data") && $response->data != "" && !empty($response->data)) {
              $selected_conversio_send_to = get_option('ee_conversio_send_to');
              $conversion_label = array();
              foreach ($response->data as $key => $value) {
                $con_string = strip_tags($value->tagSnippets);
                $conversion_label_check = $TVC_Admin_Helper->get_conversion_label($con_string);
                if ($conversion_label_check != "" && $conversion_label_check != null) {
                  $conversion_label[] = $TVC_Admin_Helper->get_conversion_label($con_string);
                }
              }
              echo wp_json_encode($conversion_label);
              exit;
            }
          }
        }
      }
      // IMPORTANT: don't forget to exit
      wp_die(0);
    }

    public function conv_get_conversion_list_gads_bycat()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'con_get_conversion_list-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $customApiObj = new CustomApi();

        $current_customer_id = sanitize_text_field($_POST['gads_id']);
        $conversionCategory = sanitize_text_field($_POST['conversionCategory']);

        if ($current_customer_id != "") {
          $response = $customApiObj->get_conversion_list($current_customer_id, $conversionCategory);
          if (property_exists($response, "error") && $response->error == false) {
            if (property_exists($response, "data") && $response->data != "" && !empty($response->data)) {
              $selected_conversio_send_to = get_option('ee_conversio_send_to');
              $conversion_label = array();
              foreach ($response->data as $key => $value) {
                //echo '<pre>'; print_r($value); echo '</pre>';
                $con_string = strip_tags($value->tagSnippets);
                $conversion_label_check = $TVC_Admin_Helper->get_conversion_label($con_string);
                if ($conversion_label_check != "" && $conversion_label_check != null) {
                  $conversion_label[$value->id] = $TVC_Admin_Helper->get_conversion_label($con_string);
                }
              }
              echo wp_json_encode($conversion_label);
              exit;
            }
          }
        }
      }
      // IMPORTANT: don't forget to exit
      wp_die(0);
    }


    public function conv_create_gads_conversion()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'con_get_conversion_list-nonce')) {
        //$TVC_Admin_Helper = new TVC_Admin_Helper();
        $customApiObj = new CustomApi();

        $current_customer_id = sanitize_text_field($_POST['gads_id']);
        $conversionCategory = sanitize_text_field($_POST['conversionCategory']);
        $conversionName = sanitize_text_field($_POST['conversionName']);
        if ($current_customer_id != "") {
          $response = $customApiObj->conv_create_gads_conversion($current_customer_id, $conversionName, $conversionCategory);
          if (property_exists($response, "error") && $response->error == false) {
            if (property_exists($response, "data") && $response->data != "" && !empty($response->data)) {
              echo wp_json_encode($response);
              exit;
            }
          }
        }
      }
    }


    public function conv_save_gads_conversion()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'CONVNonce', FILTER_SANITIZE_STRING), 'conv_save_gads_conversion-nonce')) {
        $ee_options = unserialize(get_option('ee_options'));
        if (isset($_POST['cleargadsconversions']) && sanitize_text_field($_POST['cleargadsconversions']) == "yes") {
          unset($ee_options["gads_conversions"]);
          update_option('ee_options', serialize($ee_options));

          $google_ads_conversion_tracking = 0;
          $ga_EC = 0;
          $ee_conversio_send_to = "";

          update_option('google_ads_conversion_tracking', sanitize_text_field($google_ads_conversion_tracking));
          $googleDetail_setting["google_ads_conversion_tracking"] = sanitize_text_field($google_ads_conversion_tracking);

          update_option('ga_EC', sanitize_text_field($ga_EC));

          update_option('ee_conversio_send_to', sanitize_text_field($ee_conversio_send_to));
          $googleDetail_setting["ee_conversio_send_to"] = sanitize_text_field($ee_conversio_send_to);
        } else {
          $ee_options_gads_conversions = [];
          if (array_key_exists("gads_conversions", $ee_options)) {
            $ee_options_gads_conversions = $ee_options["gads_conversions"];
          }
          $ee_options_gads_conversions[sanitize_text_field($_POST['conversion_category'])] = sanitize_text_field($_POST['conversion_action']);
          $ee_options["gads_conversions"] = $ee_options_gads_conversions;
          //unset($ee_options["gads_conversions"]);
          update_option('ee_options', serialize($ee_options));

          if (sanitize_text_field($_POST['conversion_category']) == "PURCHASE") {
            $google_ads_conversion_tracking = 1;
            $ga_EC = 1;
            $ee_conversio_send_to = sanitize_text_field($_POST['conversion_action']);

            update_option('google_ads_conversion_tracking', sanitize_text_field($google_ads_conversion_tracking));
            $googleDetail_setting["google_ads_conversion_tracking"] = sanitize_text_field($google_ads_conversion_tracking);

            update_option('ga_EC', sanitize_text_field($ga_EC));

            update_option('ee_conversio_send_to', sanitize_text_field($ee_conversio_send_to));
            $googleDetail_setting["ee_conversio_send_to"] = sanitize_text_field($ee_conversio_send_to);
          }
        }
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $TVC_Admin_Helper->update_app_status();
        die('1');
      } else {
        die('Security nonce not matched');
      }
    }

    public function tvc_call_notification_dismiss()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'TVCNonce', FILTER_SANITIZE_STRING), 'tvc_call_notification_dismiss-nonce')) {
        $ee_dismiss_id = isset($_POST['data']['ee_dismiss_id']) ? sanitize_text_field($_POST['data']['ee_dismiss_id']) : "";
        if ($ee_dismiss_id != "") {
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $ee_msg_list = $TVC_Admin_Helper->get_ee_msg_nofification_list();
          if (isset($ee_msg_list[$ee_dismiss_id])) {
            unset($ee_msg_list[$ee_dismiss_id]);
            $ee_msg_list[$ee_dismiss_id]["active"] = 0;
            $TVC_Admin_Helper->set_ee_msg_nofification_list($ee_msg_list);
            echo wp_json_encode(array('status' => 'success', 'message' => ""));
          }
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    public function tvc_call_notice_dismiss()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'apiNoticDismissNonce', FILTER_SANITIZE_STRING), 'tvc_call_notice_dismiss-nonce')) {
        $ee_notice_dismiss_id = isset($_POST['data']['ee_notice_dismiss_id']) ? sanitize_text_field($_POST['data']['ee_notice_dismiss_id']) : "";
        $ee_notice_dismiss_id = sanitize_text_field($ee_notice_dismiss_id);
        if ($ee_notice_dismiss_id != "") {
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
          $ee_additional_data['dismissed_' . $ee_notice_dismiss_id] = 1;
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
          echo wp_json_encode(array('status' => 'success', 'message' => $ee_additional_data));
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    public function tvc_call_notice_dismiss_trigger()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'apiNoticDismissNonce', FILTER_SANITIZE_STRING), 'tvc_call_notice_dismiss-nonce')) {
        $ee_notice_dismiss_id_trigger = isset($_POST['data']['ee_notice_dismiss_id_trigger']) ? sanitize_text_field($_POST['data']['ee_notice_dismiss_id_trigger']) : "";
        $ee_notice_dismiss_id_trigger = sanitize_text_field($ee_notice_dismiss_id_trigger);
        if ($ee_notice_dismiss_id_trigger != "") {
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
          $slug = $ee_notice_dismiss_id_trigger;
          $title = "";
          $content = "";
          $status = "0";
          $TVC_Admin_Helper->tvc_dismiss_admin_notice($slug, $content, $status, $title);
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    public function tvc_call_import_gmc_product()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'apiSyncupNonce', FILTER_SANITIZE_STRING), 'tvc_call_api_sync-nonce')) {
        $next_page_token = isset($_POST['next_page_token']) ? sanitize_text_field($_POST['next_page_token']) : "";
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $api_rs = $TVC_Admin_Helper->update_gmc_product_to_db($next_page_token);
        if (isset($api_rs['error'])) {
          echo wp_json_encode($api_rs);
        } else {
          echo wp_json_encode(array('error' => true, 'message' => esc_html__("Please try after some time.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    public function tvc_call_api_sync()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'apiSyncupNonce', FILTER_SANITIZE_STRING), 'tvc_call_api_sync-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $api_rs = $TVC_Admin_Helper->set_update_api_to_db();
        if (isset($api_rs['error']) && isset($api_rs['message']) && sanitize_text_field($api_rs['message'])) {
          echo wp_json_encode($api_rs);
        } else {
          echo wp_json_encode(array('error' => true, 'message' => esc_html__("Please try after some time.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }
    public function tvc_call_site_verified()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'SiteVerifiedNonce', FILTER_SANITIZE_STRING), 'tvc_call_site_verified-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $tvc_rs = [];
        $tvc_rs = $TVC_Admin_Helper->call_site_verified();
        if (isset($tvc_rs['error']) && $tvc_rs['error'] == 1) {
          echo wp_json_encode(array('status' => 'error', 'message' => sanitize_text_field($tvc_rs['msg'])));
        } else {
          echo wp_json_encode(array('status' => 'success', 'message' => sanitize_text_field($tvc_rs['msg'])));
        }
        exit;
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
        exit;
      }
    }
    public function tvc_call_domain_claim()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'apiDomainClaimNonce', FILTER_SANITIZE_STRING), 'tvc_call_domain_claim-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $tvc_rs = $TVC_Admin_Helper->call_domain_claim();
        if (isset($tvc_rs['error']) && $tvc_rs['error'] == 1) {
          echo wp_json_encode(array('status' => 'error', 'message' => sanitize_text_field($tvc_rs['msg'])));
        } else {
          echo wp_json_encode(array('status' => 'success', 'message' => sanitize_text_field($tvc_rs['msg'])));
        }
        exit;
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
        exit;
      }
    }
    public function get_tvc_access_token()
    {
      if (!empty($this->access_token)) {
        return $this->access_token;
      } else {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        $this->access_token = sanitize_text_field(base64_decode($google_detail['setting']->access_token));
        return $this->access_token;
      }
    }

    public function get_tvc_refresh_token()
    {
      if (!empty($this->refresh_token)) {
        return $this->refresh_token;
      } else {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        $this->refresh_token = sanitize_text_field(base64_decode($google_detail['setting']->refresh_token));
        return $this->refresh_token;
      }
    }
    /**
     * Delete the campaign
     */
    public function tvcajax_delete_campaign()
    {
      // make sure this call is legal
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignDeleteNonce', FILTER_SANITIZE_STRING), 'tvcajax_delete_campaign-nonce')) {

        $merchantId = filter_input(INPUT_POST, 'merchantId', FILTER_SANITIZE_STRING);
        $customerId = filter_input(INPUT_POST, 'customerId', FILTER_SANITIZE_STRING);
        $campaignId = filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_STRING);

        $url = $this->apiDomain . '/campaigns/delete';
        $data = [
          'merchant_id' => sanitize_text_field($merchantId),
          'customer_id' => sanitize_text_field($customerId),
          'campaign_id' => sanitize_text_field($campaignId)
        ];
        $args = array(
          'headers' => array(
            'Authorization' => "Bearer MTIzNA==",
            'Content-Type' => 'application/json'
          ),
          'method' => 'DELETE',
          'body' => wp_json_encode($data)
        );
        // Send remote request
        $request = wp_remote_request(esc_url_raw($url), $args);

        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response_body = json_decode(wp_remote_retrieve_body($request));

        if ((isset($response_body->error) && $response_body->error == '')) {
          $message = $response_body->message;
          echo wp_json_encode(['status' => 'success', 'message' => $message]);
        } else {
          $message = is_array($response_body->errors) ? $response_body->errors[0] : "Face some unprocessable entity";
          echo wp_json_encode(['status' => 'error', 'message' => $message]);
          // return new WP_Error($response_code, $response_message, $response_body);
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    /**
     * Update the campaign status pause/active
     */
    public function tvcajax_update_campaign_status()
    {
      // make sure this call is legal
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignStatusNonce', FILTER_SANITIZE_STRING), 'tvcajax-update-campaign-status-nonce')) {
        if (!class_exists('ShoppingApi')) {
          include(ENHANCAD_PLUGIN_DIR . 'includes/setup/ShoppingApi.php');
        }

        $header = array(
          "Authorization: Bearer MTIzNA==",
          "Content-Type" => "application/json"
        );

        $merchantId = filter_input(INPUT_POST, 'merchantId', FILTER_SANITIZE_STRING);
        $customerId = filter_input(INPUT_POST, 'customerId', FILTER_SANITIZE_STRING);
        $campaignId = filter_input(INPUT_POST, 'campaignId', FILTER_SANITIZE_STRING);
        $budgetId = filter_input(INPUT_POST, 'budgetId', FILTER_SANITIZE_STRING);
        $campaignName = filter_input(INPUT_POST, 'campaignName', FILTER_SANITIZE_STRING);
        $budget = filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $curl_url = $this->apiDomain . '/campaigns/update';
        $shoppingObj = new ShoppingApi();
        $campaignData = $shoppingObj->getCampaignDetails($campaignId);

        $data = [
          'merchant_id' => sanitize_text_field($merchantId),
          'customer_id' => sanitize_text_field($customerId),
          'campaign_id' => sanitize_text_field($campaignId),
          'account_budget_id' => sanitize_text_field($budgetId),
          'campaign_name' => sanitize_text_field($campaignName),
          'budget' => sanitize_text_field($budget),
          'status' => sanitize_text_field($status),
          'target_country' => sanitize_text_field($campaignData->data['data']->targetCountry),
          'ad_group_id' => sanitize_text_field($campaignData->data['data']->adGroupId),
          'ad_group_resource_name' => sanitize_text_field($campaignData->data['data']->adGroupResourceName)
        ];

        $args = array(
          'headers' => $header,
          'method' => 'PATCH',
          'body' => wp_json_encode($data)
        );
        $request = wp_remote_request(esc_url_raw($curl_url), $args);
        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response = json_decode(wp_remote_retrieve_body($request));
        if (isset($response->error) && $response->error == false) {
          $message = $response->message;
          echo wp_json_encode(['status' => 'success', 'message' => $message]);
        } else {
          $message = is_array($response->errors) ? $response->errors[0] : esc_html__("Face some unprocessable entity", "enhanced-e-commerce-for-woocommerce-store");
          echo wp_json_encode(['status' => 'error', 'message' => $message]);
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    /**
     * Returns the campaign categories from a selected country
     */
    public function tvcajax_get_campaign_categories()
    {
      // make sure this call is legal
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignCategoryListsNonce', FILTER_SANITIZE_STRING), 'tvcajax-campaign-category-lists-nonce')) {

        $country_code = filter_input(INPUT_POST, 'countryCode', FILTER_SANITIZE_STRING);
        $customer_id = filter_input(INPUT_POST, 'customerId', FILTER_SANITIZE_STRING);
        $url = $this->apiDomain . '/products/categories';

        $data = [
          'customer_id' => sanitize_text_field($customer_id),
          'country_code' => sanitize_text_field($country_code)
        ];

        $args = array(
          'headers' => array(
            'Authorization' => "Bearer MTIzNA==",
            'Content-Type' => 'application/json'
          ),
          'body' => wp_json_encode($data)
        );

        // Send remote request
        $request = wp_remote_post(esc_url_raw($url), $args);

        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response_body = json_decode(wp_remote_retrieve_body($request));

        if ((isset($response_body->error) && $response_body->error == '')) {
          echo wp_json_encode($response_body->data);
        } else {
          echo wp_json_encode([]);
        }
      }
      // IMPORTANT: don't forget to exit
      exit;
    }

    /**
     * Returns the campaign categories from a selected country
     */
    public function tvcajax_get_gmc_categories()
    {
      // make sure this call is legal
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'gmcCategoryListsNonce', FILTER_SANITIZE_STRING), 'tvcajax-gmc-category-lists-nonce')) {

        $country_code = filter_input(INPUT_POST, 'countryCode', FILTER_SANITIZE_STRING);
        $customer_id = filter_input(INPUT_POST, 'customerId', FILTER_SANITIZE_STRING);
        $parent = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_STRING);
        $url = $this->apiDomain . '/products/gmc-categories';

        $data = [
          'customer_id' => sanitize_text_field($customer_id),
          'country_code' => sanitize_text_field($country_code),
          'parent' => sanitize_text_field($parent)
        ];

        $args = array(
          'headers' => array(
            'Authorization' => "Bearer MTIzNA==",
            'Content-Type' => 'application/json'
          ),
          'body' => wp_json_encode($data)
        );

        // Send remote request
        $request = wp_remote_post(esc_url_raw($url), $args);

        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response_body = json_decode(wp_remote_retrieve_body($request));

        if ((isset($response_body->error) && $response_body->error == '')) {
          echo wp_json_encode($response_body->data);
        } else {
          echo wp_json_encode([]);
        }

        //   echo wp_json_encode( $categories );
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
        exit;
      }

      // IMPORTANT: don't forget to exit
      exit;
    }

    /**
     * function to Save Category Mapping
     * Hook used wp_ajax_save_category_mapping
     * Request Post
     * DB used ee_prod_mapped_cats
     */
    public function save_category_mapping()
    {
      if (is_admin() && wp_verify_nonce(filter_input(INPUT_POST, 'auto_product_sync_setting', FILTER_SANITIZE_STRING), 'auto_product_sync_setting-nonce')) {
        $data = is_array($_POST['ee_data']) ? array_map('sanitize_text_field', $_POST['ee_data']) : sanitize_text_field($_POST['ee_data']);
        parse_str($data, $formArray);
        if (!empty($formArray)) {
          foreach ($formArray as $key => $value) {
            $formArray[$key] = sanitize_text_field($value);
          }

          foreach ($formArray as $key => $value) {
            if (preg_match("/^category-name-/i", $key)) {
              if ($value != '') {
                $keyArray = explode("name-", $key);
                $mappedCatsDB[$keyArray[1]]['name'] = sanitize_text_field($value);
              }
              unset($formArray[$key]);
            } else if (preg_match("/^category-/i", $key)) {
              if ($value != '' && $value > 0) {
                $keyArray = explode("-", $key);
                $mappedCats[$keyArray[1]] = sanitize_text_field($value);
                $mappedCatsDB[$keyArray[1]]['id'] = sanitize_text_field($value);
              }
              unset($formArray[$key]);
            }
          }
          $categories = unserialize(get_option('ee_prod_mapped_cats'));
          $countCategories = is_array($categories) ? count($categories) : 0;
          update_option("ee_prod_mapped_cats", serialize($mappedCatsDB));

          if ($countCategories == 0) {
            $customObj = new CustomApi();
            $customObj->update_app_status();
          }

          echo wp_json_encode(array('error' => false, 'message' => esc_html__("Category Mapping successfully saved.", "enhanced-e-commerce-for-woocommerce-store")));
        } else {
          echo wp_json_encode(array('error' => true, 'message' => esc_html__("Error!!! No Category selected.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to Save Attribute Mapping
     * Hook used wp_ajax_ssave_attribute_mapping
     * Request Post
     * DB used ee_prod_mapped_attrs
     */
    public function save_attribute_mapping()
    {
      if (is_admin() && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['auto_product_sync_setting'])), 'auto_product_sync_setting-nonce')) {
        $data = isset($_POST['ee_data']) ? sanitize_text_field(urldecode($_POST['ee_data'])) : "";
        parse_str($data, $formArray);
        if (!empty($formArray)) {
          foreach ($formArray as $key => $value) {
            if ($key == 'additional_attr_') {
              $additional_attr = $value;
              unset($formArray['additional_attr_']);
            }
            if ($key == 'additional_attr_value_') {
              $additional_attr_value = $value;
              unset($formArray['additional_attr_value_']);
            }
            if (is_array($value) !== 1) {
              $formArray[$key] = sanitize_text_field($value);
            }
          }
          unset($formArray['additional_attr_']);
          unset($formArray['additional_attr_value_']);
          if (isset($additional_attr)) {
            foreach ($additional_attr as $key => $value) {
              $formArray[$value] = $additional_attr_value[$key];
            }
          }
          foreach ($formArray as $key => $value) {
            $mappedAttrs[$key] = sanitize_text_field($value);
          }
          $attributes = unserialize(get_option('ee_prod_mapped_attrs'));
          $countAttribute = is_array($attributes) ? count($attributes) : 0;
          //If cnt 
          unset($mappedAttrs['cnt']);
          update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));

          if ($countAttribute == 0) {
            $customObj = new CustomApi();
            $customObj->update_app_status();
          }

          echo wp_json_encode(array('error' => false, 'message' => esc_html__("Attribute Mapping successfully saved.", "enhanced-e-commerce-for-woocommerce-store")));
        } else {
          echo wp_json_encode(array('error' => true, 'message' => esc_html__("Error!!! No Attribute selected.", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array('status' => 'error', "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to get Product status by feed_id
     * Hook used wp_ajax_ee_get_product_status
     * Request Post
     * API call to get product status
     */
    public function ee_get_product_status()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_licence_nonce', FILTER_SANITIZE_STRING), 'conv_licence-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        $merchantId = $TVC_Admin_Helper->get_merchantId();
        $data = array(
          "merchant_id" => $merchantId,
          "maxResults" => sanitize_text_field($_POST['maxResults']),
          "pageToken" => "",
          "product_ids" => sanitize_text_field($_POST['product_list']),
          "store_feed_id" => sanitize_text_field($_POST['feed_id']),
          "store_id" => sanitize_text_field($google_detail['setting']->store_id)
        );
        $CustomApi = new CustomApi();
        $response = $CustomApi->getProductStatusByFeedId($data);
        if (isset($response->errors)) {
          echo wp_json_encode($response->errors = 'Product does not exists');
        } else {
          echo wp_json_encode(isset($response->data->products) ? $response->data->products : 'Product not synced');
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to Save and Update Feed data
     * Hook used wp_ajax_save_feed_data
     * Request Post
     * DB used ee_product_feed
     * Schedule cron set_recurring_auto_sync_product_feed_wise on update for conditions
     */
    public function save_feed_data()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $channel_id = array();
        if ($_POST['google_merchant_center'] == 1) {
          $channel_id['google_merchant_center'] = sanitize_text_field($_POST['google_merchant_center']);
        }
        if (sanitize_text_field($_POST['tiktok_id']) == 3) {
          $channel_id['tiktok_id'] = sanitize_text_field($_POST['tiktok_id']);
        }
        $channel_ids = implode(',', $channel_id);

        $tiktok_catalog_id = '';
        if (isset($_POST['tiktok_catalog_id']) === TRUE && $_POST['tiktok_catalog_id'] !== '') {
          $tiktok_catalog_id = sanitize_text_field($_POST['tiktok_catalog_id']);
        }
        /**
         * Check catalog id available
         */
        if (isset($_POST['tiktok_catalog_id']) === TRUE && sanitize_text_field($_POST['tiktok_catalog_id']) === 'Create New') {
          /**
           * Create catalog id
           */
          $getCountris = @file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/countries_currency.json");
          $contData = json_decode($getCountris);
          $currency_code = '';
          foreach ($contData as $key => $data) {
            if ($data->countryCode === $_POST['target_country']) {
              $currency_code = $data->currencyCode;
            }
          }
          $customer['customer_subscription_id'] = sanitize_text_field($_POST['customer_subscription_id']);
          $customer['business_id'] = sanitize_text_field($_POST['tiktok_business_account']);
          $customer['catalog_name'] = sanitize_text_field($_POST['feedName']);
          $customer['region_code'] = sanitize_text_field($_POST['target_country']);
          $customer['currency'] = sanitize_text_field($currency_code);
          $customObj = new CustomApi();
          $result = $customObj->createCatalogs($customer);
          if (isset($result->error_data) === TRUE) {
            foreach ($result->error_data as $key => $value) {
              echo wp_json_encode(array("error" => true, "message" => $value->errors[0], "errorType" => "tiktok"));
              exit;
            }
          }

          if (isset($result->status) === TRUE && $result->status === 200) {
            $tiktok_catalog_id = $result->data->catalog_id;
            $values = array();
            $place_holders = array();
            global $wpdb;
            $ee_tiktok_catalog = esc_sql($wpdb->prefix . "ee_tiktok_catalog");
            array_push($values, esc_sql(sanitize_text_field($_POST['target_country'])), esc_sql($tiktok_catalog_id), esc_sql(sanitize_text_field($_POST['feedName'])), gmdate('Y-m-d H:i:s', current_time('timestamp')));
            $place_holders[] = "('%s', '%s', '%s','%s')";
            $query = "INSERT INTO `$ee_tiktok_catalog` (country, catalog_id, catalog_name, created_date) VALUES ";
            $query .= implode(', ', $place_holders);
            $wpdb->query($wpdb->prepare($query, $values));

            /***Store Catalog data Middleware *****/
            //$this->storeNewCatalogMiddleware();
          }
        }

        if (isset($_POST['edit']) && $_POST['edit'] != '') {
          $next_schedule_date = NULL;
          as_unschedule_all_actions('init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => sanitize_text_field($_POST['edit'])));
          if ($_POST['autoSync'] != 0 && $_POST['is_mapping_update'] == 1) {
            $last_sync_date = sanitize_text_field($_POST['last_sync_date']);
            $next_schedule_date = gmdate('Y-m-d H:i:s', strtotime('+' . sanitize_text_field($_POST['autoSyncIntvl']) . 'day', strtotime($last_sync_date)));
            // add scheduled cron job
            $autoSyncIntvl = isset($_POST['autoSyncIntvl']) ? absint(sanitize_text_field($_POST['autoSyncIntvl'])) : 0;
            $time_space = strtotime($autoSyncIntvl . " days", 0);
            $timestamp = strtotime($autoSyncIntvl . " days");
            as_schedule_recurring_action(esc_attr($timestamp), esc_attr($time_space), 'init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => sanitize_text_field($_POST['edit'])), "product_sync");
          }
          $profile_data = array(
            'feed_name' => esc_sql(sanitize_text_field($_POST['feedName'])),
            'channel_ids' => esc_sql(sanitize_text_field($channel_ids)),
            'auto_sync_interval' => esc_sql(sanitize_text_field($_POST['autoSyncIntvl'])),
            'auto_schedule' => esc_sql(sanitize_text_field($_POST['autoSync'])),
            'updated_date' => esc_sql(gmdate('Y-m-d H:i:s', current_time('timestamp'))),
            'next_schedule_date' => $next_schedule_date,
            'target_country' => esc_sql(sanitize_text_field($_POST['target_country'])),
            'tiktok_catalog_id' => esc_sql(sanitize_text_field($tiktok_catalog_id)),
          );

          if ($_POST['is_mapping_update'] != 1) {
            $profile_data['status'] = strpos($channel_ids, '1') !== false ? esc_sql('Draft') : '';
            $profile_data['tiktok_status'] = strpos($channel_ids, '3') !== false ? esc_sql('Draft') : '';
          }
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $profile_data, array("id" => sanitize_text_field($_POST['edit'])));
          $result = array(
            'id' => sanitize_text_field($_POST['edit']),
          );
          echo wp_json_encode($result);
        } else {
          $profile_data = array(
            'feed_name' => esc_sql(sanitize_text_field($_POST['feedName'])),
            'channel_ids' => esc_sql(sanitize_text_field($channel_ids)),
            'auto_sync_interval' => esc_sql(sanitize_text_field($_POST['autoSyncIntvl'])),
            'auto_schedule' => esc_sql(sanitize_text_field($_POST['autoSync'])),
            'created_date' => esc_sql(gmdate('Y-m-d H:i:s', current_time('timestamp'))),
            'status' => strpos(sanitize_text_field($channel_ids), '1') !== false ? esc_sql('Draft') : '',
            'target_country' => esc_sql(sanitize_text_field($_POST['target_country'])),
            'tiktok_catalog_id' => esc_sql(sanitize_text_field($tiktok_catalog_id)),
            'tiktok_status' => strpos(sanitize_text_field($channel_ids), '3') !== false ? esc_sql('Draft') : '',
          );
          $TVC_Admin_DB_Helper->tvc_add_row("ee_product_feed", $profile_data, array("%s", "%s", "%s", "%d", "%s", "%s", "%s", "%s"));
          $result = $TVC_Admin_DB_Helper->tvc_get_last_row("ee_product_feed", array("id"));
          echo wp_json_encode($result);
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to get Feed data by id
     * Hook used wp_ajax_get_feed_data_by_id
     * Request Post
     * DB used ee_product_feed
     */
    public function get_feed_data_by_id()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['id']));
        $filed = array(
          'id',
          'feed_name',
          'channel_ids',
          'auto_sync_interval',
          'auto_schedule',
          'status',
          'is_mapping_update',
          'last_sync_date',
          'target_country',
          'tiktok_catalog_id',
        );
        $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
        echo wp_json_encode($result);
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to Duplicate Feed data by id
     * Hook used wp_ajax_ee_duplicate_feed_data_by_id
     * Request Post
     * DB used ee_product_feed
     */
    public function ee_duplicate_feed_data_by_id()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['id']));
        $filed = array(
          'feed_name',
          'channel_ids',
          'auto_sync_interval',
          'auto_schedule',
          'categories',
          'attributes',
          'filters',
          'include_product',
          'exclude_product',
          'total_product',
          'target_country',
          'tiktok_catalog_id',
        );
        $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
        $profile_data = array(
          'feed_name' => esc_sql('Copy of - ' . $result[0]['feed_name']),
          'channel_ids' => esc_sql($result[0]['channel_ids']),
          'auto_sync_interval' => esc_sql($result[0]['auto_sync_interval']),
          'auto_schedule' => esc_sql($result[0]['auto_schedule']),
          'filters' => $result[0]['filters'],
          'include_product' => esc_sql($result[0]['include_product']),
          'exclude_product' => esc_sql($result[0]['exclude_product']),
          'created_date' => esc_sql(gmdate('Y-m-d H:i:s', current_time('timestamp'))),
          'status' => esc_sql('Draft'),
          'target_country' => esc_sql($result[0]['target_country']),
          'tiktok_catalog_id' => esc_sql($result[0]['tiktok_catalog_id']),
          'tiktok_status' => strpos($result[0]['channel_ids'], '3') !== false ? esc_sql('Draft') : '',
        );

        $TVC_Admin_DB_Helper->tvc_add_row("ee_product_feed", $profile_data, array("%s", "%s", "%s", "%d", "%s", "%s", "%s", "%s", "%s", "%s", "%s"));
        echo wp_json_encode(array("error" => false, "message" => esc_html__("Dupliacte Feed created successfully", "enhanced-e-commerce-for-woocommerce-store")));
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to Delete Feed and product from GMC
     * Hook used wp_ajax_ee_delete_feed_data_by_id
     * Request Post
     * DB used ee_product_feed
     * Delete by id
     * Unschedule set_recurring_auto_sync_product_feed_wise cron 
     * Api Call to delete product from GMC 
     */
    public function ee_delete_feed_data_by_id()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['id']));
        $filed = array('exclude_product', 'status', 'include_product');
        $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
        if ($result[0]['status'] === 'Synced') {
          as_unschedule_all_actions('init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => sanitize_text_field($_POST['id'])));
          /**
           * Api call to delete GMC product
           */
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $google_detail = $TVC_Admin_Helper->get_ee_options_data();
          $merchantId = $TVC_Admin_Helper->get_merchantId();
          $data = array(
            "merchant_id" => $merchantId,
            "store_id" => $google_detail['setting']->store_id,
            "store_feed_id" => sanitize_text_field($_POST['id']),
            "product_ids" => ''
          );
          $CustomApi = new CustomApi();
          $response = $CustomApi->delete_from_channels($data);
          $TVC_Admin_Helper->plugin_log("Delete Feed from GMC" . wp_json_encode($response), 'product_sync');
        }
        $soft_delete_id = array('status' => 'Deleted', 'is_delete' => esc_sql(1));
        $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $soft_delete_id, array("id" => sanitize_text_field($_POST['id'])));
        echo wp_json_encode(array("error" => false, "message" => esc_html__("Feed Deleted Successfully.", "enhanced-e-commerce-for-woocommerce-store")));
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to delete Product by product id from GMC
     * Hook used wp_ajax_ee_delete_feed_gmc
     * DB used ee_product_feed
     * Request Post product id and feedId
     * Api Call to delete product from GMC
     */
    public function ee_delete_feed_gmc()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        $CONV_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['feed_id']));
        $filed = array('exclude_product', 'status', 'include_product', 'total_product');
        $result = $CONV_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
        $totProdRem = $result[0]['total_product'] - 1;
        if ($result[0]['exclude_product'] != '' && $_POST['product_ids'] != '') {
          $allExclude = $result[0]['exclude_product'] . ',' . sanitize_text_field($_POST['product_ids']);
          $profile_data = array(
            'exclude_product' => esc_sql($allExclude),
            'total_product' => $totProdRem >= 0 ? $totProdRem : 0,
          );
          $CONV_Admin_DB_Helper->tvc_update_row("ee_product_feed", $profile_data, array("id" => sanitize_text_field($_POST['feed_id'])));
        } else if ($result[0]['include_product'] != '' && $_POST['product_ids'] != '') {
          $include_product = explode(',', $result[0]['include_product']);
          if (($key = array_search($_POST['product_ids'], $include_product)) !== false) {
            unset($include_product[$key]);
          }
          $all_include = implode(',', $include_product);
          $profile_data = array(
            'include_product' => esc_sql($all_include),
            'total_product' => $totProdRem >= 0 ? $totProdRem : 0,
          );
          $CONV_Admin_DB_Helper->tvc_update_row("ee_product_feed", $profile_data, array("id" => sanitize_text_field($_POST['feed_id'])));
        } else {
          $profile_data = array(
            'exclude_product' => esc_sql(sanitize_text_field($_POST['product_ids'])),
            'total_product' => $totProdRem >= 0 ? $totProdRem : 0,
          );
          $CONV_Admin_DB_Helper->tvc_update_row("ee_product_feed", $profile_data, array("id" => sanitize_text_field($_POST['feed_id'])));
        }

        $CONV_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $CONV_Admin_Helper->get_ee_options_data();
        $merchantId = $CONV_Admin_Helper->get_merchantId();
        $data = array(
          "merchant_id" => $merchantId,
          "store_id" => $google_detail['setting']->store_id,
          "store_feed_id" => sanitize_text_field($_POST['feed_id']),
          "product_ids" => sanitize_text_field($_POST['product_ids'])
        );
        /**
         * Api Call to delete product from GMC
         */
        $convCustomApi = new CustomApi();
        $response = $convCustomApi->delete_from_channels($data);
        echo wp_json_encode($response);
        exit;
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to show Feed wise woocommerce product data
     * Hook used wp_ajax_ee_get_product_details_for_table
     * Request Post
     * DB used Woo commerce db
     */
    public function ee_get_product_details_for_table()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'product_details_nonce', FILTER_SANITIZE_STRING), 'conv_product_details-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $conv_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        global $wpdb;
        $p_id = sanitize_text_field($_POST['p_id']);
        $where = array();
        $search = isset($_POST['searchName']) ? sanitize_text_field($_POST['searchName']) : '';
        $conditionprod = '';
        $whereSKUJoin = '';
        $wherePriJoin = '';
        $whereStockJoin = '';
        $condition = $conditionSKU = $conditionContent = $conditionExcerpt = $conditionPrice = $conditionRegPrice = $conditionStock = '';
        $product_cat1 = $product_cat2 = $product_id1 = $product_id2 = $whereCond = $whereCondsku = $whereCondcontent = $whereExcerpt = $whereCondregPri = $whereCondPri = $wherestock = array();

        if ($_POST['productData'] == "") {
          $pagination_count = (new WP_Query(['post_type' => 'product', 'post_status' => 'publish']))->found_posts;
          wp_reset_query();
        } else {
          $productSearch = explode(',', sanitize_text_field($_POST['productData']));
          $conditionSearch = explode(',', sanitize_text_field($_POST['conditionData']));
          $valueSearch = explode(',', sanitize_text_field($_POST['valueData']));
          foreach ($productSearch as $key => $value) {
            switch ($value) {
              case 'product_cat':
                if ($conditionSearch[$key] == "=") {
                  $product_cat1[] = sanitize_text_field($valueSearch[$key]);
                  $where['IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . implode(",", $product_cat1) . ') )';
                } else if ($conditionSearch[$key] == "!=") {
                  $product_cat2[] = sanitize_text_field($valueSearch[$key]);
                  $where['NOT IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id NOT IN (' . implode(",", $product_cat2) . ') )';
                }
                break;
              case '_stock_status':
                if (!empty($conditionSearch[$key])) {
                  $wherestock[] = '(pm4.meta_key = "' . sanitize_text_field($value) . '" AND pm4.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . ' "' . sanitize_text_field($valueSearch[$key]) . '")';
                  $whereStockJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm4 ON pm4.post_id = ' . $wpdb->prefix . 'posts.ID';
                }
                break;
              case 'ID':
                if ($conditionSearch[$key] == "=") {
                  $product_id1[] = sanitize_text_field($valueSearch[$key]);
                  $where['IDIN'] = '(' . $wpdb->prefix . 'posts.ID IN (' . implode(",", $product_id1) . ') )';
                } else if ($conditionSearch[$key] == "!=") {
                  $product_id2[] = sanitize_text_field($valueSearch[$key]);
                  $where['IDNOTIN'] = '(' . $wpdb->prefix . 'posts.ID NOT IN (' . implode(",", $product_id2) . ') )';
                }
                break;
              case 'post_title':
                if ($conditionSearch[$key] == "Contains") {
                  $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "Start With") {
                  $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "End With") {
                  $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
                }
                break;
              case '_sku':
                if ($conditionSearch[$key] == "Contains") {
                  $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "Start With") {
                  $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "End With") {
                  $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
                }
                $whereSKUJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm2 ON pm2.post_id = ' . $wpdb->prefix . 'posts.ID';
                break;
              case '_regular_price':
                if (!empty($conditionSearch[$key])) {
                  $whereCondPri[] = '(pm3.meta_key = "' . sanitize_text_field($value) . '" AND pm3.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . sanitize_text_field($valueSearch[$key]) . ')';
                  $wherePriJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm3 ON pm3.post_id = ' . $wpdb->prefix . 'posts.ID';
                }
                break;
              case '_sale_price':
                if (!empty($conditionSearch[$key])) {
                  $whereCondregPri[] = '(pm1.meta_key = "' . sanitize_text_field($value) . '" AND pm1.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . sanitize_text_field($valueSearch[$key]) . ')';
                }
                break;

              case 'post_content':
                if ($conditionSearch[$key] == "Contains") {
                  $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "Start With") {
                  $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "End With") {
                  $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
                }
                break;
              case 'post_excerpt':
                if ($conditionSearch[$key] == "Contains") {
                  $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "Start With") {
                  $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
                } else if ($conditionSearch[$key] == "End With") {
                  $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
                }
                break;
            }
          }
          $conditionprod = (!empty($where)) ? 'AND (' . implode(' AND ', $where) . ')' : '';
          $condition = (!empty($whereCond)) ? 'AND (' . implode(' OR ', $whereCond) . ')' : '';
          $conditionSKU = (!empty($whereCondsku)) ? 'AND (' . implode(' OR ', $whereCondsku) . ')' : '';
          $conditionContent = (!empty($whereCondcontent)) ? 'AND (' . implode(' OR ', $whereCondcontent) . ')' : '';
          $conditionExcerpt = (!empty($whereExcerpt)) ? 'AND (' . implode(' OR ', $whereExcerpt) . ')' : '';
          $conditionPrice = (!empty($whereCondregPri)) ? 'AND (' . implode(' OR ', $whereCondregPri) . ')' : '';
          $conditionRegPrice = (!empty($whereCondPri)) ? 'AND (' . implode(' OR ', $whereCondPri) . ')' : '';
          $conditionStock = (!empty($wherestock)) ? 'AND (' . implode(' OR ', $wherestock) . ')' : '';
          $countSql = "SELECT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.post_title, " . $wpdb->prefix . "posts.post_excerpt, " . $wpdb->prefix . "posts.post_content
                        FROM " . $wpdb->prefix . "posts 
                        LEFT JOIN " . $wpdb->prefix . "postmeta pm1 ON pm1.post_id = " . $wpdb->prefix . "posts.ID
                        " . $whereSKUJoin . " " . $wherePriJoin . " " . $whereStockJoin . "
                        LEFT JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) 
                        JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tt.taxonomy = 'product_cat' AND tt.term_taxonomy_id = " . $wpdb->prefix . "term_relationships.term_taxonomy_id 
                        JOIN " . $wpdb->prefix . "terms AS t ON t.term_id = tt.term_id
                        
                        WHERE 1=1  " . $conditionprod . " " . $condition . " " . $conditionSKU . " " . $conditionContent . " " . $conditionExcerpt . " " . $conditionPrice . " " . $conditionRegPrice . " " . $conditionStock . "
                        AND " . $wpdb->prefix . "posts.post_type = 'product' AND ((" . $wpdb->prefix . "posts.post_status = 'publish')) 
                        GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.post_date DESC";

          if ($search != "") {
            $countSql = "SELECT * FROM (" . $countSql . ")A where 1=1 AND post_title LIKE ('%%" . $search . "%%') 
                OR post_excerpt LIKE ('%%" . $search . "%%') 
                OR post_content LIKE ('%%" . $search . "%%')
                ORDER BY post_title LIKE ('%%" . $search . "%%')";
          }

          $allResult = $wpdb->get_results($countSql, ARRAY_A);
          $pagination_count = $wpdb->num_rows;
          wp_reset_query();
        }

        $length = sanitize_text_field($_POST['length']);
        $limit = sanitize_text_field($_POST['start']);

        $query = "SELECT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.post_title, " . $wpdb->prefix . "posts.post_excerpt, " . $wpdb->prefix . "posts.post_content
                    FROM " . $wpdb->prefix . "posts
                    LEFT JOIN " . $wpdb->prefix . "postmeta pm1 ON pm1.post_id = " . $wpdb->prefix . "posts.ID
                    " . $whereSKUJoin . " " . $wherePriJoin . " " . $whereStockJoin . "
                    LEFT JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) 
                    JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tt.taxonomy = 'product_cat' AND tt.term_taxonomy_id = " . $wpdb->prefix . "term_relationships.term_taxonomy_id 
                    JOIN " . $wpdb->prefix . "terms AS t ON t.term_id = tt.term_id
                    WHERE 1=1
                    AND " . $wpdb->prefix . "posts.post_type='product' AND " . $wpdb->prefix . "posts.post_status='publish' 
                    " . $conditionprod . " " . $condition . " " . $conditionSKU . " " . $conditionContent . " " . $conditionExcerpt . " " . $conditionPrice . " " . $conditionRegPrice . " " . $conditionStock . "
                    GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.ID ";

        if ($search != "") {
          $query = "SELECT * FROM (" . $query . ")A where 1=1 AND post_title LIKE ('%%" . $search . "%%') 
              OR post_excerpt LIKE ('%%" . $search . "%%') 
              OR post_content LIKE ('%%" . $search . "%%')
              ORDER BY post_title LIKE ('%%" . $search . "%%')";
        }
        $query .= "DESC LIMIT %d, %d";
        $sql = $wpdb->prepare($query, [$limit, $length]);
        $allResult = $wpdb->get_results($sql, ARRAY_A);
        wp_reset_query();

        $syncProductList = array();
        foreach ($allResult as $key => $value) {
          $action = sanitize_text_field('Feed Product');
          $class = sanitize_text_field('btn btn-primary');
          $product_data = wc_get_product($value['ID']);
          $quantity = sanitize_text_field('-');
          if (!empty($product_data->get_stock_quantity())) {
            $quantity = $product_data->get_stock_quantity();
          }
          $status = get_post_status($product_data->get_id());
          if (!empty(get_the_post_thumbnail_url($product_data->get_id()))) {
            $img = get_the_post_thumbnail_url($product_data->get_id());
          } else {
            $img = esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/No-Image-Placeholder.svg");
          }
          $status = "";
          $issues = "";
          $children = $product_data->product_type;

          $price = number_format_i18n($product_data->get_regular_price() ? esc_html($product_data->get_regular_price()) : 0);
          $sale_price = $product_data->get_sale_price() ? number_format_i18n(esc_html($product_data->get_sale_price())) : $price;
          $terms = get_the_terms($product_data->get_id(), 'product_cat');
          if ($p_id == '_sku') {
            $proId = $product_data->get_sku();
          } elseif ($p_id == 'ID') {
            $proId = sanitize_text_field($value['ID']);
          } else {
            $proId = sanitize_text_field($value['ID']);
          }
          if ($proId == '') {
            $proId = sanitize_text_field($value['ID']);
          }
          $without_prefix = $proId;
          if (!empty($_POST['prefix'])) {
            $proId = sanitize_text_field($_POST['prefix']) . $proId;
          }
          $prd = wc_get_product($value['ID']);
          $type = $prd->get_type();
          $terms = get_the_terms($product_data->get_id(), 'product_cat');
          //$terms = wp_get_post_terms( $product_data->get_id(), 'product_cat' );
          $categories = '';
          foreach ($terms as $term) {
            $categories .= '<label class="fs-12 fw-400 defaultPointer">' . $term->name . '</label><br/>';
          }
          $syncProductList[] = array(
            'checkbox' => '<input class="checkbox" hidden type="checkbox" name="attrProduct"  id="attr_' . esc_html($value['ID']) . '" checked value="' . esc_html($proId) . '">
                              <div class="form-check form-check-custom">
                              <input class="form-check-input checkbox fs-17 syncProduct syncProduct_' . esc_html($without_prefix) . '" name="syncProduct" type="checkbox" value="' . esc_html($value['ID']) . '" id="sync_' . esc_html($value['ID']) . '" checked>
                              </div>',
            'product' => '<div class="d-flex flex-row bd-highlight">
                              <div class="p-2 pt-0 ps-0 bd-highlight image ">
                                <img class="rounded image-w-h" src="' . esc_url($img) . '" />
                              </div>
                              <div class="p-3 pt-0 pb-0 bd-highlight">
                              <div class="text-truncate text-dark fs-12 fw-400" style="max-width: 200px;">' . sprintf(esc_html('%s'), esc_html($product_data->get_title())) . '</div>
                              <div class="fs-12 fw-400">Price: ' . get_woocommerce_currency_symbol() . " " . $price . '</div>
                              <div class="fs-12 fw-400">Sale Price: ' . get_woocommerce_currency_symbol() . " " . $sale_price . '</div>
                              <div class="fs-12 fw-400">Product ID: ' . esc_html($product_data->get_id()) . '</div>
                              <!--<div class="mt-1 text-dark"><abbr title="Get More Information" style="cursor: pointer;">More Info<abbr>
                              </div>-->
                              </div>
                              </div>',
            'category' => $categories,
            'availability' => '<label class="fs-12 fw-400 ' . esc_attr(ucfirst($product_data->get_stock_status())) . '">' . esc_html(ucfirst($product_data->get_stock_status())) . '</label>',
            'quantity' => '<label class="fs-12 fw-400">' . esc_html($quantity) . '</label>',
            'channelstatus' => '<div class="channelStatus_' . $proId . '"><div>
                <button type="button" class="rounded-pill approved fs-7 ps-3 pe-0 pt-0 pb-0 mb-2 approvedChannel"
                    data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover"
                    data-bs-trigger="hover focus">
                    Approved <span class="badge bg-light rounded-circle fs-7 approved-text ms-2 margin-badge approved_count_' . $proId . '"
                        style="top:0px;">0</span>
                </button>
                <div class="hidden approvedDivContent">
                    <div class="card custom-width rounded-5">
                        <div class="card-header bg-white channel_logo_' . $proId . '">                        

                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button type="button"
                    class="rounded-pill pending fs-7 ps-3 pe-0 pt-0 pb-0 mb-2 pendingIssues"
                    data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover"
                    data-bs-trigger="hover focus">
                    Pending&nbsp; <span class="badge bg-light rounded-circle fs-7 pending-text ms-2 margin-badge pending_count_' . $proId . '"
                        style="top:0px;">0</span>
                </button>
                <div class="hidden pendingDivContent">
                    <div class="card rounded-5">
                        <div class="card-header bg-warning-soft text-white">Pending Issues</div>
                        <div class="card-body pending_issue_text_' . $proId . '">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="button"
                    class="rounded-pill rejected fs-7 ps-3 pe-0 pt-0 pb-0 mb-2 rejectIssues"
                    data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover"
                    data-bs-trigger="hover focus">
                    Rejected <span class="badge bg-light rounded-circle fs-7 rejected-text ms-2 margin-badge rejected_count_' . $proId . '"
                        style="top:0px;">0</span>
                </button>
                <div class="hidden rejectDivContent">
                    <div class="card rounded-5">
                        <div class="card-header bg-danger-soft text-white">Rejected Issues</div>
                        <div class="card-body rejected_issue_text_' . $proId . '">                        
                        </div>
                    </div>
                </div>
            </div></div>',
            'action' => '<div class="fs-12 channel_' . $type . '_' . $proId . '" id="channel_action_' . $proId . '"></div><div class="innerSpinner action_" id="action_' . $value['ID'] . '"><div class="call_both_verification-spinner tvc-nb-spinner"></div><p class="centered">Fetching...</p></div>',
          );
        }
        wp_reset_query();
        $result = array(
          'draw' => sanitize_text_field($_POST['draw']),
          'recordsTotal' => sanitize_text_field($pagination_count),
          'recordsFiltered' => sanitize_text_field($pagination_count),
          'data' => $syncProductList
        );

        echo wp_json_encode($result);
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * function to get Product wise categories
     * Hook used wp_ajax_ee_syncProductCategory
     * Request Post
     */
    public function ee_syncProductCategory()
    {
      global $wpdb;
      $where = array();
      $search = isset($_POST['searchName']) ? sanitize_text_field($_POST['searchName']) : '';
      $productSearch = explode(',', sanitize_text_field($_POST['productData']));
      $conditionSearch = explode(',', sanitize_text_field($_POST['conditionData']));
      $valueSearch = explode(',', sanitize_text_field($_POST['valueData']));
      $conditionprod = '';
      $condition = $conditionSKU = $conditionContent = $conditionExcerpt = $conditionPrice = $whereSKUJoin = $wherePriJoin = $whereStockJoin = $conditionStock = '';
      $product_cat1 = $product_cat2 = $product_id1 = $product_id2 = $whereCond = $whereCondsku = $whereCondcontent = $whereExcerpt = $whereCondregPri = $wherestock = array();
      foreach ($productSearch as $key => $value) {
        switch ($value) {
          case 'product_cat':
            if ($conditionSearch[$key] == "=") {
              $product_cat1[] = $valueSearch[$key];
              $where['IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . implode(",", $product_cat1) . ') )';
            } else if ($conditionSearch[$key] == "!=") {
              $product_cat2[] = $valueSearch[$key];
              $where['NOT IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id NOT IN (' . implode(",", $product_cat2) . ') )';
            }
            break;
          case '_stock_status':
            if (!empty($conditionSearch[$key])) {
              $wherestock[] = '(pm4.meta_key = "' . sanitize_text_field($value) . '" AND pm4.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . ' "' . sanitize_text_field($valueSearch[$key]) . '")';
              $whereStockJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm4 ON pm4.post_id = ' . $wpdb->prefix . 'posts.ID';
            }
            break;
          case 'ID':
            if ($conditionSearch[$key] == "=") {
              $product_id1[] = $valueSearch[$key];
              $where['IDIN'] = '(' . $wpdb->prefix . 'posts.ID IN (' . implode(",", $product_id1) . ') )';
            } else if ($conditionSearch[$key] == "!=") {
              $product_id2[] = $valueSearch[$key];
              $where['IDNOTIN'] = '(' . $wpdb->prefix . 'posts.ID NOT IN (' . implode(",", $product_id2) . ') )';
            }
            break;
          case 'post_title':
            if ($conditionSearch[$key] == "Contains") {
              $whereCond[] = '' . $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "Start With") {
              $whereCond[] = '' . $wpdb->prefix . 'posts.' . $value . ' LIKE ("' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "End With") {
              $whereCond[] = '' . $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '")';
            }
            break;
          case '_sku':
            if ($conditionSearch[$key] == "Contains") {
              $whereCondsku[] = 'pm1.meta_key = ' . $value . ' AND meta_value ' . ' LIKE ("%' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "Start With") {
              $whereCondsku[] = 'pm1.meta_key = ' . $value . ' AND meta_value ' . ' LIKE ("' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "End With") {
              $whereCondsku[] = 'pm1.meta_key = ' . $value . ' AND meta_value ' . ' LIKE ("%' . $valueSearch[$key] . '")';
            }
            $whereSKUJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm2 ON pm2.post_id = ' . $wpdb->prefix . 'posts.ID';
            break;
          case '_regular_price':
            if (!empty($conditionSearch[$key])) {
              $whereCondPri[] = '(pm3.meta_key = "' . $value . '" AND pm3.meta_value  ' . $conditionSearch[$key] . $valueSearch[$key] . ')';
              $wherePriJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm3 ON pm3.post_id = ' . $wpdb->prefix . 'posts.ID';
            }
            break;
          case '_sale_price':
            if (!empty($conditionSearch[$key])) {
              $whereCondregPri[] = '(pm1.meta_key = "' . $value . '" AND pm1.meta_value  ' . $conditionSearch[$key] . $valueSearch[$key] . ')';
            }
            break;
          case 'post_content':
            if ($conditionSearch[$key] == "Contains") {
              $whereCondcontent[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "Start With") {
              $whereCondcontent[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "End With") {
              $whereCondcontent[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '")';
            }
            break;
          case 'post_excerpt':
            if ($conditionSearch[$key] == "Contains") {
              $whereExcerpt[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "Start With") {
              $whereExcerpt[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("' . $valueSearch[$key] . '%")';
            } else if ($conditionSearch[$key] == "End With") {
              $whereExcerpt[] = $wpdb->prefix . 'posts.' . $value . ' LIKE ("%' . $valueSearch[$key] . '")';
            }
            break;
        }
      }
      $conditionprod = (!empty($where)) ? 'AND (' . implode(' AND ', $where) . ')' : '';
      $condition = (!empty($whereCond)) ? 'AND (' . implode(' OR ', $whereCond) . ')' : '';
      $conditionSKU = (!empty($whereCondsku)) ? 'AND (' . implode(' OR ', $whereCondsku) . ')' : '';
      $conditionContent = (!empty($whereCondcontent)) ? 'AND (' . implode(' OR ', $whereCondcontent) . ')' : '';
      $conditionExcerpt = (!empty($whereExcerpt)) ? 'AND (' . implode(' OR ', $whereExcerpt) . ')' : '';
      $conditionPrice = (!empty($whereCondregPri)) ? 'AND (' . implode(' OR ', $whereCondregPri) . ')' : '';
      $conditionRegPrice = (!empty($whereCondPri)) ? 'AND (' . implode(' OR ', $whereCondPri) . ')' : '';
      $conditionStock = (!empty($wherestock)) ? 'AND (' . implode(' OR ', $wherestock) . ')' : '';
      $query = "SELECT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.post_title, " . $wpdb->prefix . "posts.post_excerpt, " . $wpdb->prefix . "posts.post_content, 
                    GROUP_CONCAT(DISTINCT " . $wpdb->prefix . "term_relationships.term_taxonomy_id ORDER BY " . $wpdb->prefix . "term_relationships.term_taxonomy_id  SEPARATOR', ') as  term_taxonomy_id
                    FROM " . $wpdb->prefix . "posts
                    LEFT JOIN " . $wpdb->prefix . "postmeta pm1 ON pm1.post_id = " . $wpdb->prefix . "posts.ID
                    LEFT JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) 
                  
                    JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tt.taxonomy = 'product_cat' AND tt.term_taxonomy_id = " . $wpdb->prefix . "term_relationships.term_taxonomy_id 
                    JOIN " . $wpdb->prefix . "terms AS t ON t.term_id = tt.term_id
                    " . $whereSKUJoin . " " . $wherePriJoin . " " . $whereStockJoin . "
                    WHERE 1=1
                    AND " . $wpdb->prefix . "posts.post_type='product' AND " . $wpdb->prefix . "posts.post_status='publish' 
                    " . $conditionprod . " " . $condition . " " . $conditionSKU . " " . $conditionContent . " " . $conditionExcerpt . " " . $conditionPrice . " " . $conditionRegPrice . " " . $conditionStock . "
                    GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.ID ";

      if( isset($_POST['productArray']) )
        $conv_productArray = is_array($_POST['productArray']) ? array_map('sanitize_text_field', $_POST['productArray']) : sanitize_text_field($_POST['productArray']);
      else
        $conv_productArray = "";
      
      if(isset($_POST['exclude']) )
        $conv_productexclude = is_array($_POST['exclude']) ? array_map('sanitize_text_field', $_POST['exclude']) : sanitize_text_field($_POST['exclude']);
      else
        $conv_productexclude = "";

      if(isset($_POST['include']))
        $conv_productinclude = is_array($_POST['include']) ? array_map('sanitize_text_field', $_POST['include']) : sanitize_text_field($_POST['include']);
      else
        $conv_productinclude = "";

      if (!empty($conv_productArray) && is_array($conv_productArray)) {
        $a = array_filter($conv_productArray, function ($v) {
          return $v != "syncAll";
        });
        $whereProduct = '(ID IN (' . implode(",", $a) . ') )';
        $query = "SELECT * FROM (" . $query . ")AA where 1=1 AND " . $whereProduct;
      } else if (!empty($conv_productexclude) && is_array($conv_productexclude) && $_POST['inculdeExtraProduct'] == "") {
        $b = array_filter($conv_productexclude, function ($v) {
          return $v != "syncAll";
        });
        $whereProduct = '(ID NOT IN (' . implode(",", $b) . ') )';
        $query = "SELECT * FROM (" . $query . ")AA where 1=1 AND " . $whereProduct;
      } else if (!empty($conv_productinclude) && is_array($conv_productinclude) && $_POST['inculdeExtraProduct'] == "") {
        $c = array_filter($conv_productinclude, function ($v) {
          return $v != "syncAll";
        });
        $whereProduct = '(ID IN (' . implode(",", $c) . ') )';
        $query = "SELECT * FROM (" . $query . ")AA where 1=1 AND " . $whereProduct;
      }

      $allResult = $wpdb->get_results($query, ARRAY_A);
      foreach ($allResult as $key => $value) {
        $terms = get_the_terms($value['ID'], 'product_cat');
        foreach ($terms as $term) {
          $cat[$term->term_id] = $term->term_id;
        }
      }
      wp_reset_query();
      echo wp_json_encode(array_values($cat));
      exit;
    }

    /************************************ All function for Feed Wise Product Sync Start ******************************************************************/
    /**
     * Ajax Call Feed wise product sync
     * Store category/attribute into options
     * Store Feed setting data into DB
     * initiated by ajax
     * Database Table used `ee_product_feed` 
     */
    function ee_feed_wise_product_sync_batch_wise()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_nonce', FILTER_SANITIZE_STRING), 'conv_ajax_product_sync_bantch_wise-nonce')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $TVC_Admin_Helper->plugin_log("Start", 'product_sync');
        $conv_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        try {
          $selecetedCat = [];
          $feed_MappedCat = [];
          $mappedCats = [];
          $mappedAttrs = [];
          $mappedCatsDB = [];
          $product_batch_size = isset($_POST['product_batch_size']) ? sanitize_text_field($_POST['product_batch_size']) : "25"; // barch size for inser product in GMC
          $product_id_prefix = isset($_POST['product_id_prefix']) ? sanitize_text_field($_POST['product_id_prefix']) : "";
          $data = sanitize_text_field(urldecode($_POST['conv_data']));

          $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();

          parse_str($data, $formArray);
          if (!empty($formArray)) {
            foreach ($formArray as $key => $value) {
              //$formArray[$key] = sanitize_text_field($value);
              if ($key == 'additional_attr_') {
                $additional_attr = $value;
                unset($formArray['additional_attr_']);
              }
              if ($key == 'additional_attr_value_') {
                $additional_attr_value = $value;
                unset($formArray['additional_attr_value_']);
              }
              if (is_array($value) !== 1) {
                $formArray[$key] = sanitize_text_field($value);
              }
            }
            unset($formArray['additional_attr_']);
            unset($formArray['additional_attr_value_']);
            if (isset($additional_attr)) {
              foreach ($additional_attr as $key => $value) {
                $formArray[$value] = $additional_attr_value[$key];
              }
            }
          }
          /**
           * Filter data
           */
          $productFilter = isset($_POST['productData']) && $_POST['productData'] != '' ? explode(',', sanitize_text_field($_POST['productData'])) : '';
          $conditionFilter = isset($_POST['conditionData']) && $_POST['conditionData'] != '' ? explode(',', sanitize_text_field($_POST['conditionData'])) : '';
          $valueFilter = isset($_POST['valueData']) && $_POST['valueData'] != '' ? explode(',', sanitize_text_field($_POST['valueData'])) : '';
          $filters = array();
          if (!empty($productFilter)) {
            foreach ($productFilter as $key => $val) {
              $filters[$key]['attr'] = sanitize_text_field($val);
              $filters[$key]['condition'] = sanitize_text_field($conditionFilter[$key]);
              $filters[$key]['value'] = sanitize_text_field($valueFilter[$key]);
            }
          }
          $selecetedCat = explode(',', $formArray['selectedCategory']);
          /*
           * Collect Attribute/Categories Mapping
           */
          foreach ($formArray as $key => $value) {
            if (preg_match("/^category-name-/i", $key)) {
              if ($value != '') {
                $keyArray = explode("name-", $key);
                $mappedCatsDB[$keyArray[1]]['name'] = sanitize_text_field($value);
                if (in_array($keyArray[1], $selecetedCat)) {
                  $feed_MappedCat[$keyArray[1]]['name'] = sanitize_text_field($value);
                }
              }
              unset($formArray[$key]);
            } else if (preg_match("/^category-/i", $key)) {
              if ($value != '' && $value > 0) {
                $keyArray = explode("-", $key);
                $mappedCats[$keyArray[1]] = sanitize_text_field($value);
                $mappedCatsDB[$keyArray[1]]['id'] = sanitize_text_field($value);
                if (in_array($keyArray[1], $selecetedCat)) {
                  $feed_MappedCat[$keyArray[1]]['id'] = sanitize_text_field($value);
                  $w_cat_id = $keyArray[1];
                  $g_cat_id = $value;
                }
              }
              unset($formArray[$key]);
            } else {
              if ($value && $key != 'selectedCategory') {
                $mappedAttrs[$key] = sanitize_text_field($value);
              }
            }
          }

          //add/update data in default profile
          $profile_data = array("profile_title" => esc_sql("Default"), "g_attribute_mapping" => wp_json_encode($mappedAttrs), "update_date" => gmdate('Y-m-d H:i:s', current_time('timestamp')));
          if ($TVC_Admin_DB_Helper->tvc_row_count("ee_product_sync_profile") == 0) {
            $TVC_Admin_DB_Helper->tvc_add_row("ee_product_sync_profile", $profile_data, array("%s", "%s", "%s"));
          } else {
            $TVC_Admin_DB_Helper->tvc_update_row("ee_product_sync_profile", $profile_data, array("id" => 1));
          }

          // Update settings Product Mapping
          update_option("ee_prod_mapped_cats", serialize($mappedCatsDB));
          update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));

          // Batch settings
          $conv_additional_data['is_mapping_update'] = true;
          $conv_additional_data['is_process_start'] = false;
          $conv_additional_data['is_auto_sync_start'] = false;
          $conv_additional_data['product_sync_batch_size'] = sanitize_text_field($product_batch_size);
          $conv_additional_data['product_id_prefix'] = sanitize_text_field($product_id_prefix);
          $conv_additional_data['product_sync_alert'] = sanitize_text_field("Product sync settings updated successfully");
          $TVC_Admin_Helper->set_ee_additional_data($conv_additional_data);
          $google_detail = $TVC_Admin_Helper->get_ee_options_data();
          $CustomApi = new CustomApi();
          if (!class_exists('TVCProductSyncHelper')) {
            include ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php';
          }
          $TVCProductSyncHelper = new TVCProductSyncHelper();
          //Update Product Feed Table          
          if ($TVC_Admin_DB_Helper->tvc_check_row("ee_product_feed", "id =" . sanitize_text_field($_POST['feedId']))) {

            /***Single product sync for already synced product feed ******/
            if ($_POST['inculdeExtraProduct'] != '') {
              $feed_datas = array(
                "attributes" => wp_json_encode($mappedAttrs),
              );

              //update attribute in ee_product_feed table
              $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_datas, array("id" => sanitize_text_field($_POST['feedId'])));

              global $wpdb;
              $product_batch_size = (isset($conv_additional_data['product_sync_batch_size']) && $conv_additional_data['product_sync_batch_size']) ? $conv_additional_data['product_sync_batch_size'] : 100;
              $tvc_currency = sanitize_text_field($TVC_Admin_Helper->get_woo_currency());
              $merchantId = sanitize_text_field($TVC_Admin_Helper->get_merchantId());
              $accountId = sanitize_text_field($TVC_Admin_Helper->get_main_merchantId());
              $subscriptionId = sanitize_text_field(sanitize_text_field($TVC_Admin_Helper->get_subscriptionId()));
              $product_batch_size = esc_sql(intval($product_batch_size));
              $products[0]['w_product_id'] = sanitize_text_field($_POST['inculdeExtraProduct']);
              $tiktok_catalog_id = '';
              $tiktok_business_id = sanitize_text_field($TVC_Admin_Helper->get_tiktok_business_id());
              $object = array(
                '0' => (object) array(
                  'w_product_id' => sanitize_text_field($_POST['inculdeExtraProduct']),
                  'w_cat_id' => $w_cat_id,
                  'g_cat_id' => $g_cat_id
                )
              );

              //map each product with category and attribute
              $p_map_attribute = $TVCProductSyncHelper->conv_get_feed_wise_map_product_attribute($object, $tvc_currency, $merchantId, $product_batch_size, $mappedAttrs, $product_id_prefix);

              $TVC_Admin_Auto_Product_sync_Helper = new TVC_Admin_Auto_Product_sync_Helper();
              //update ee_product_sync_data
              $TVC_Admin_Auto_Product_sync_Helper->update_last_sync_in_db_batch_wise($p_map_attribute['valid_products'], sanitize_text_field($_POST['feedId'])); //Add data in sync product database
              if (!empty($p_map_attribute) && isset($p_map_attribute['items']) && !empty($p_map_attribute['items'])) {
                // call product sync API
                $data = [
                  'merchant_id' => sanitize_text_field($accountId),
                  'account_id' => sanitize_text_field($merchantId),
                  'subscription_id' => sanitize_text_field($subscriptionId),
                  'store_feed_id' => sanitize_text_field($_POST['feedId']),
                  'is_on_gmc' => strpos(sanitize_text_field($_POST['channel_ids']), '1') !== false ? true : false,
                  'is_on_tiktok' => strpos(sanitize_text_field($_POST['channel_ids']), '3') !== false ? true : false,
                  'tiktok_catalog_id' => sanitize_text_field($_POST['tiktok_catalog_id']),
                  'tiktok_business_id' => sanitize_text_field($tiktok_business_id),
                  'is_on_facebook' => false,
                  'business_id' => '',
                  'catalog_id' => '',
                  'entries' => $p_map_attribute['items']
                ];
                /**************************** API Call to GMC ****************************************************************************/
                $response = $CustomApi->feed_wise_products_sync($data);

                $endTime = new DateTime();
                $startTime = new DateTime();
                $diff = $endTime->diff($startTime);
                $responseData['time_duration'] = $diff;
                update_option("ee_prod_response", serialize($responseData));

                if ($response->error == false) {
                  $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['feedId']));
                  $filed = ['total_product'];
                  $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
                  $totalProduct = 0;
                  if ($result[0]['total_product'] !== NULL) {
                    $totalProduct = $result[0]['total_product'] + 1;
                  }
                  $feed_data = array(
                    "exclude_product" => $_POST['include'] == '' ? esc_sql(sanitize_text_field($_POST['exclude'])) : '',
                    "include_product" => $_POST['exclude'] == '' ? esc_sql(sanitize_text_field($_POST['include'])) : '',
                    "product_sync_alert" => NULL,
                    'total_product' => $totalProduct,
                  );
                  $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => sanitize_text_field($_POST['feedId'])));

                  $syn_data = array(
                    'status' => 1
                  );
                  $TVC_Admin_DB_Helper->tvc_update_row("ee_product_sync_data", $syn_data, array("feedId" => sanitize_text_field($_POST['feedId'])));
                  $sync_message = esc_html__("Initiated, products are being synced to Merchant Center.Do not refresh..", "enhanced-e-commerce-for-woocommerce-store");
                  $sync_message = sprintf(esc_html('%s'), esc_html($sync_message));
                  $sync_progressive_data = array("sync_message" => $sync_message);
                  echo wp_json_encode(array('status' => 'success', "sync_progressive_data" => $sync_progressive_data));
                  exit;
                } else {
                  return wp_json_encode(array('error' => true, 'message' => esc_attr('Error in Sync...')));
                }
              }
            } else {
              /*******Update feed data in DB start**********************/
              $feed_data = array(
                "categories" => wp_json_encode($feed_MappedCat),
                "attributes" => wp_json_encode($mappedAttrs),
                "filters" => wp_json_encode($filters),
                "include_product" => esc_sql(sanitize_text_field($_POST['include'])),
                "exclude_product" => $_POST['include'] == '' ? esc_sql(sanitize_text_field($_POST['exclude'])) : '',
                "is_mapping_update" => true,
                "is_process_start" => false,
                "is_auto_sync_start" => false,
                "product_sync_batch_size" => esc_sql($product_batch_size),
                "product_id_prefix" => esc_sql($product_id_prefix),
                "product_sync_alert" => sanitize_text_field("Product sync settings updated successfully"),
                "status" => strpos(sanitize_text_field($_POST['channel_ids']), '1') !== false ? esc_sql('In Progress') : null,
                "is_default" => esc_sql(0),
                "tiktok_status" => strpos(sanitize_text_field($_POST['channel_ids']), '3') !== false ? esc_sql('In Progress') : null,
              );
              $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => sanitize_text_field($_POST['feedId'])));
              /*******Update feed data in DB end**********************/

              /*******Update feed data in laravel start**********************/
              $feed_data_api = array(
                "store_id" => $google_detail['setting']->store_id,
                "store_feed_id" => sanitize_text_field($_POST['feedId']),
                "map_categories" => wp_json_encode($feed_MappedCat),
                "map_attributes" => wp_json_encode($mappedAttrs),
                "filter" => wp_json_encode($filters),
                "include" => esc_sql(sanitize_text_field($_POST['include'])),
                "exclude" => $_POST['include'] == '' ? esc_sql(sanitize_text_field($_POST['exclude'])) : '',
                "channel_ids" => sanitize_text_field($_POST['channel_ids']),
                "interval" => sanitize_text_field($_POST['autoSyncInterval']),
                "tiktok_catalog_id" => sanitize_text_field($_POST['tiktok_catalog_id']),
              );
              $TVC_Admin_Helper->plugin_log("mapping saved and product sync process scheduled", 'product_sync'); // Add logs
              $CustomApi = new CustomApi();
              $CustomApi->ee_create_product_feed($feed_data_api);
              /*******Update feed data in laravel End *********************/

              /********Manual Product sync Start ******************/
              as_unschedule_all_actions('init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => sanitize_text_field($_POST['feedId'])));
              $isSyncComplete = $TVCProductSyncHelper->manualProductSync(sanitize_text_field($_POST['feedId']));
              if ($isSyncComplete['status'] === 'success') {

                $where = '`id` = ' . esc_sql(sanitize_text_field($_POST['feedId']));
                $filed = ['auto_sync_interval', 'auto_schedule'];
                $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
                $last_sync_date = gmdate('Y-m-d H:i:s', current_time('timestamp'));
                $next_schedule_date = NULL;
                if ($result[0]['auto_schedule'] == 1) {
                  $next_schedule_date = gmdate('Y-m-d H:i:s', strtotime('+' . $result[0]['auto_sync_interval'] . 'day', current_time('timestamp')));
                  $time_space = strtotime($result[0]['auto_sync_interval'] . " days", 0);
                  $timestamp = strtotime($result[0]['auto_sync_interval'] . " days");
                  $TVC_Admin_Helper->plugin_log("recurring cron set", 'product_sync'); // Add logs 
                  as_schedule_recurring_action(esc_attr($timestamp), esc_attr($time_space), 'init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => sanitize_text_field($_POST['feedId'])), "product_sync");
                }

                $feed_data = array(
                  "product_sync_alert" => NULL,
                  "is_process_start" => false,
                  "is_auto_sync_start" => false,
                  "last_sync_date" => esc_sql($last_sync_date),
                  "next_schedule_date" => $next_schedule_date,
                );
                $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => sanitize_text_field($_POST['feedId'])));
              } else {
                $feed_data = array(
                  "product_sync_alert" => $isSyncComplete['message'],
                  "is_process_start" => false,
                  "is_auto_sync_start" => false,
                  "is_mapping_update" => false,
                );
                $TVC_Admin_Helper->plugin_log("error", 'product_sync'); // Add logs
                $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => sanitize_text_field($_POST['feedId'])));
              }

              /********Manual Product sync End ******************/
            }
          } else {
            echo wp_json_encode(array("error" => true, "message" => esc_html__("Feed data is missing.", "enhanced-e-commerce-for-woocommerce-store")));
            exit;
          }
          $sync_message = esc_html__("Initiated, products are being synced to Merchant Center.Do not refresh..", "enhanced-e-commerce-for-woocommerce-store");
          $sync_message = sprintf(esc_html('%s'), esc_html($sync_message));
          $sync_progressive_data = array("sync_message" => $sync_message);
          echo wp_json_encode(array('status' => 'success', "sync_progressive_data" => $sync_progressive_data));
          exit;
        } catch (Exception $e) {
          $conv_additional_data['product_sync_alert'] = $e->getMessage();
          $TVC_Admin_Helper->set_ee_additional_data($conv_additional_data);
          $TVC_Admin_Helper->plugin_log($e->getMessage(), 'product_sync');
          $feed_data = array(
            "product_sync_alert" => $e->getMessage(),
            "is_process_start" => false,
            "is_auto_sync_start" => false,
            "is_mapping_update" => false,
          );
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => sanitize_text_field($_POST['feedId'])));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * Cron used for Feed wise product sync
     * Store data into Database 
     * hook used init_feed_wise_product_sync_process_scheduler
     * initiated by init_feed_wise_product_sync_process_scheduler_ee hook
     * Database Table used `ee_prouct_pre_sync_data` 
     * parameter int $feedId
     */
    function ee_call_start_feed_wise_product_sync_process($feedId)
    {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $TVC_Admin_Helper->plugin_log("Process to store data into ee_prouct_pre_sync_data table at " . gmdate('Y-m-d H:i:s', current_time('timestamp')) . " feed Id " . $feedId, 'product_sync'); // Add logs 
      $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
      try {
        $subscriptionId = $TVC_Admin_Helper->get_subscriptionId();
        $customApiObj = new CustomApi();
        $googledetail = $customApiObj->getGoogleAnalyticDetail($subscriptionId);
        $googleDetail = $googledetail->data;
        if (isset($googleDetail->google_merchant_center_id) === TRUE && $googleDetail->google_merchant_center_id !== "") {
          global $wpdb;
          $where = '`id` = ' . esc_sql($feedId);
          $filed = ['feed_name', 'channel_ids', 'auto_sync_interval', 'auto_schedule', 'categories', 'attributes', 'filters', 'include_product', 'exclude_product', 'is_mapping_update', 'tiktok_catalog_id'];
          $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
          if (!empty($result) && isset($result) && $result[0]['is_mapping_update'] == '1') {
            $prouct_pre_sync_table = esc_sql("ee_prouct_pre_sync_data");
            if ($TVC_Admin_DB_Helper->tvc_row_count($prouct_pre_sync_table) > 0) {
              $TVC_Admin_DB_Helper->tvc_safe_truncate_table($wpdb->prefix . $prouct_pre_sync_table);
            }

            $product_db_batch_size = 200; // batch size to insert in database
            $batch_count = 0;
            $values = array();
            $place_holders = array();

            if ($result) {
              $TVC_Admin_Helper->plugin_log("Fetched feed data by ID", 'product_sync'); // Add logs       
              $filters = json_decode($result[0]['filters']);
              $filters_count = is_array($filters) ? count($filters) : '';
              $categories = json_decode($result[0]['categories']);
              $attributes = json_decode($result[0]['attributes']);
              $include = $result[0]['include_product'] != '' ? explode(",", $result[0]['include_product']) : '';
              $exclude = explode(",", $result[0]['exclude_product']);
              $where = array();
              $conditionprod = '';
              $whereSKUJoin = '';
              $wherePriJoin = '';
              $condition = $conditionSKU = $conditionContent = $conditionExcerpt = $conditionPrice = $conditionRegPrice = $whereStockJoin = $conditionStock = '';
              $product_cat1 = $product_cat2 = $product_id1 = $product_id2 = $whereCond = $whereCondsku = $whereCondcontent = $whereExcerpt = $whereCondregPri = $whereCondPri = $wherestock = array();
              if ($filters_count != '') {
                for ($i = 0; $i < $filters_count; $i++) {
                  switch ($filters[$i]->attr) {
                    case 'product_cat':
                      if ($filters[$i]->condition == "=") {
                        $product_cat1[] = sanitize_text_field($filters[$i]->value);
                        $where['IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . implode(",", $product_cat1) . ') )';
                      } else if ($filters[$i]->condition == "!=") {
                        $product_cat2[] = sanitize_text_field($filters[$i]->value);
                        $where['NOT IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id NOT IN (' . implode(",", $product_cat2) . ') )';
                      }
                      break;
                    case '_stock_status':
                      if (!empty($filters[$i]->condition)) {
                        $wherestock[] = '(pm4.meta_key = "' . sanitize_text_field($filters[$i]->attr) . '" AND pm4.meta_value  ' . sanitize_text_field($filters[$i]->condition) . ' "' . sanitize_text_field($filters[$i]->value) . '")';
                        $whereStockJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm4 ON pm4.post_id = ' . $wpdb->prefix . 'posts.ID';
                      }
                      break;
                    case 'ID':
                      if ($filters[$i]->condition == "=") {
                        $product_id1[] = sanitize_text_field($filters[$i]->value);
                        $where['IDIN'] = '(' . $wpdb->prefix . 'posts.ID IN (' . implode(",", $product_id1) . ') )';
                      } else if ($filters[$i]->condition == "!=") {
                        $product_id2[] = sanitize_text_field($filters[$i]->value);
                        $where['IDNOTIN'] = '(' . $wpdb->prefix . 'posts.ID NOT IN (' . implode(",", $product_id2) . ') )';
                      }
                      break;
                    case 'post_title':
                      if ($filters[$i]->condition == "Contains") {
                        $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "Start With") {
                        $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "End With") {
                        $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '")';
                      }
                      break;
                    case '_sku':
                      if ($filters[$i]->condition == "Contains") {
                        $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($filters[$i]->attr) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "Start with") {
                        $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($filters[$i]->attr) . '" AND pm2.meta_value ' . ' LIKE ("' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "End With") {
                        $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($filters[$i]->attr) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '")';
                      }
                      $whereSKUJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm2 ON pm2.post_id = ' . $wpdb->prefix . 'posts.ID';
                      break;
                    case '_regular_price':
                      if (!empty($filters[$i]->condition)) {
                        $whereCondPri[] = '(pm3.meta_key = "' . sanitize_text_field($filters[$i]->attr) . '" AND pm3.meta_value  ' . sanitize_text_field($filters[$i]->condition) . sanitize_text_field($filters[$i]->value) . ')';
                        $wherePriJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm3 ON pm3.post_id = ' . $wpdb->prefix . 'posts.ID';
                      }
                      break;
                    case '_sale_price':
                      if (!empty($filters[$i]->condition)) {
                        $whereCondregPri[] = '(pm1.meta_key = "' . $filters[$i]->attr . '" AND pm1.meta_value  ' . sanitize_text_field($filters[$i]->condition) . sanitize_text_field($filters[$i]->value) . ')';
                      }
                      break;
                    case 'post_content':
                      if ($filters[$i]->condition == "Contains") {
                        $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "Start With") {
                        $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "End With") {
                        $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '")';
                      }
                      break;
                    case 'post_excerpt':
                      if ($filters[$i]->condition == "Contains") {
                        $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "Start With") {
                        $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("' . sanitize_text_field($filters[$i]->value) . '%%")';
                      } else if ($filters[$i]->condition == "End With") {
                        $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($filters[$i]->attr) . ' LIKE ("%%' . sanitize_text_field($filters[$i]->value) . '")';
                      }
                      break;
                  }
                }
              }

              if ($include == '') {
                $conditionprod = (!empty($where)) ? 'AND (' . implode(' AND ', $where) . ')' : '';
                $condition = (!empty($whereCond)) ? 'AND (' . implode(' OR ', $whereCond) . ')' : '';
                $conditionSKU = (!empty($whereCondsku)) ? 'AND (' . implode(' OR ', $whereCondsku) . ')' : '';
                $conditionContent = (!empty($whereCondcontent)) ? 'AND (' . implode(' OR ', $whereCondcontent) . ')' : '';
                $conditionExcerpt = (!empty($whereExcerpt)) ? 'AND (' . implode(' OR ', $whereExcerpt) . ')' : '';
                $conditionPrice = (!empty($whereCondregPri)) ? 'AND (' . implode(' OR ', $whereCondregPri) . ')' : '';
                $conditionRegPrice = (!empty($whereCondPri)) ? 'AND (' . implode(' OR ', $whereCondPri) . ')' : '';
                $conditionStock = (!empty($wherestock)) ? 'AND (' . implode(' OR ', $wherestock) . ')' : '';
                $query = "SELECT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.post_title, " . $wpdb->prefix . "posts.post_excerpt, " . $wpdb->prefix . "posts.post_content
                        FROM " . $wpdb->prefix . "posts
                        LEFT JOIN " . $wpdb->prefix . "postmeta pm1 ON pm1.post_id = " . $wpdb->prefix . "posts.ID
                        " . $whereSKUJoin . " " . $wherePriJoin . " " . $whereStockJoin . "
                        LEFT JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) 
                        JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tt.taxonomy = 'product_cat' AND tt.term_taxonomy_id = " . $wpdb->prefix . "term_relationships.term_taxonomy_id 
                        JOIN " . $wpdb->prefix . "terms AS t ON t.term_id = tt.term_id
                        WHERE 1=1
                        AND " . $wpdb->prefix . "posts.post_type='product' AND " . $wpdb->prefix . "posts.post_status='publish' AND pm1.meta_key LIKE '_stock_status'
                        AND pm1.meta_value LIKE 'instock' 
                        " . $conditionprod . " " . $condition . " " . $conditionSKU . " " . $conditionContent . " " . $conditionExcerpt . " " . $conditionPrice . " " . $conditionRegPrice . " " . $conditionStock . "
                        GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.ID ";

                $allResult = $wpdb->get_results($query, ARRAY_A);
              } else {
                $TVC_Admin_Helper->plugin_log("Only include product", 'product_sync'); // Add logs               
                foreach ($include as $val) {
                  $allResult[]['ID'] = $val;
                }
              }
              if (!empty($allResult)) {
                $all_cat = array();

                foreach ($categories as $cat_key => $cat_val) {
                  $all_cat[$cat_key] = $cat_key;
                }
                $product_count = 0;
                $a = 0;
                foreach ($allResult as $postvalue) {
                  $have_cat = false;
                  if (!in_array($postvalue['ID'], $exclude)) {
                    $terms = get_the_terms(sanitize_text_field($postvalue['ID']), 'product_cat');
                    foreach ($terms as $key => $term) {
                      $cat_id = $term->term_id;
                      if ($term->term_id == $all_cat[$cat_id] && $have_cat == false) {
                        $cat_matched_id = $term->term_id;
                        $have_cat = true;
                      }
                    }

                    if ($have_cat == true) {
                      $product_count++;
                      $batch_count++;
                      array_push($values, esc_sql($postvalue['ID']), esc_sql($cat_matched_id), esc_sql($categories->$cat_matched_id->id), 1, gmdate('Y-m-d H:i:s', current_time('timestamp')), $feedId);
                      $place_holders[] = "('%d', '%d', '%d', '%d', '%s', '%d')";
                      if ($batch_count >= $product_db_batch_size) {
                        $query = "INSERT INTO `$wpdb->prefix$prouct_pre_sync_table` (w_product_id, w_cat_id, g_cat_id, product_sync_profile_id, create_date, feedId) VALUES ";
                        $query .= implode(', ', $place_holders);
                        $wpdb->query($wpdb->prepare($query, $values));
                        $batch_count = 0;
                        $values = array();
                        $place_holders = array();
                      }
                    } else {
                      $product_count++;
                      $batch_count++;
                      array_push($values, esc_sql($postvalue['ID']), esc_sql($cat_id), '', 1, gmdate('Y-m-d H:i:s', current_time('timestamp')), $feedId);
                      $place_holders[] = "('%d', '%d', '%d', '%d', '%s', '%d')";
                      if ($batch_count >= $product_db_batch_size) {
                        $query = "INSERT INTO `$wpdb->prefix$prouct_pre_sync_table` (w_product_id, w_cat_id, g_cat_id, product_sync_profile_id, create_date, feedId) VALUES ";
                        $query .= implode(', ', $place_holders);
                        $wpdb->query($wpdb->prepare($query, $values));
                        $batch_count = 0;
                        $values = array();
                        $place_holders = array();
                      }
                    }
                  }
                } //end product list loop

                // Add products in database
                if ($batch_count > 0) {
                  $query = "INSERT INTO `$wpdb->prefix$prouct_pre_sync_table` (w_product_id, w_cat_id, g_cat_id, product_sync_profile_id, create_date, feedId) VALUES ";
                  $query .= implode(', ', $place_holders);
                  $wpdb->query($wpdb->prepare($query, $values));
                }

                $TVC_Admin_Helper->plugin_log("All Data stored in ee_prouct_pre_sync_data table at " . gmdate('Y-m-d H:i:s', current_time('timestamp')) . " feed Id " . $feedId, 'product_sync'); // Add logs 
                // add scheduled cron job                    
                as_schedule_single_action(time() + 5, 'auto_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $feedId));
              } // end products if

              $TVC_Admin_Helper->plugin_log("is_process_start", 'product_sync'); // Add logs
              $feed_data = array(
                "total_product" => $product_count,
                "is_process_start" => true,
                "product_sync_alert" => sanitize_text_field("Product sync process is ready to start"),
              );
              $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));
            } else {
              $TVC_Admin_Helper->plugin_log("Data is missing for feed id = " . $feedId, 'product_sync'); // Add logs 
            }
          } else {
            $TVC_Admin_Helper->plugin_log("Empty result for feed id = " . $feedId, 'product_sync'); // Add logs 
          }
        } else {
          $feed_data = array(
            "product_sync_alert" => 'GMC Id missing',
            "is_process_start" => false,
            "is_auto_sync_start" => false,
            "is_mapping_update" => false,
          );
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));
          $TVC_Admin_Helper->plugin_log('GMC Id missing', 'product_sync');
        }
      } catch (Exception $e) {
        $feed_data = array(
          "product_sync_alert" => $e->getMessage(),
          "is_process_start" => false,
          "is_auto_sync_start" => false,
          "is_mapping_update" => false,
        );
        $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));
        $TVC_Admin_Helper->plugin_log($e->getMessage(), 'product_sync');
      }

      return true;
    }

    /**
     * Cron used for Feed wise product sync
     * Store data into Database 
     * hook used auto_feed_wise_product_sync_process_scheduler_ee
     * initiated by init_feed_wise_product_sync_process_scheduler hook
     * Database Table used `ee_prouct_pre_sync_data`, `conv_product_sync_data`
     * parameter int $feedId
     */
    function ee_call_auto_feed_wise_product_sync_process($feedId)
    {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $TVC_Admin_Helper->plugin_log("EE Feed wise product sync process Start", 'product_sync');
      $conv_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
      $conv_additional_data['product_sync_alert'] = NULL;
      $TVC_Admin_Helper->set_ee_additional_data($conv_additional_data);
      $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
      $feed_data = array(
        "product_sync_alert" => NULL,
      );
      $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));

      try {
        global $wpdb;
        $where = '`id` = ' . esc_sql($feedId);
        $filed = array(
          'feed_name',
          'channel_ids',
          'is_process_start',
          'auto_sync_interval',
          'auto_schedule',
          'categories',
          'attributes',
          'filters',
          'include_product',
          'exclude_product',
          'is_mapping_update'
        );
        $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
        $TVC_Admin_Helper->plugin_log("EE auto feed wise product sync process start", 'product_sync');
        if (!empty($result) && isset($result[0]['is_process_start']) && $result[0]['is_process_start'] == true) {
          $TVC_Admin_Helper->plugin_log("EE call_batch_wise_auto_sync_product_feed", 'product_sync');
          if (!class_exists('TVCProductSyncHelper')) {
            include ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php';
          }
          $TVCProductSyncHelper = new TVCProductSyncHelper();
          $response = $TVCProductSyncHelper->call_batch_wise_auto_sync_product_feed_ee($feedId);
          if (!empty($response) && isset($response['message'])) {
            $TVC_Admin_Helper->plugin_log("EE Batch wise auto sync process response " . $response['message'], 'product_sync');
          }

          $tablename = esc_sql($wpdb->prefix . "ee_prouct_pre_sync_data");
          $total_pending_pro = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) as a FROM %i where `feedId` = %d AND `status` = 0", $tablename, $feedId));
          if ($total_pending_pro == 0) {
            // Truncate pre sync table
            $TVC_Admin_DB_Helper->tvc_safe_truncate_table($tablename);

            $conv_additional_data['is_process_start'] = false;
            $conv_additional_data['is_auto_sync_start'] = true;
            $conv_additional_data['product_sync_alert'] = NULL;
            $TVC_Admin_Helper->set_ee_additional_data($conv_additional_data);
            $last_sync_date = gmdate('Y-m-d H:i:s', current_time('timestamp'));
            $next_schedule_date = NULL;
            as_unschedule_all_actions('init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $feedId));
            if ($result[0]['auto_schedule'] == 1) {
              $next_schedule_date = gmdate('Y-m-d H:i:s', strtotime('+' . $result[0]['auto_sync_interval'] . 'day', current_time('timestamp')));
              // add scheduled cron job      
              /***
               * Add recurring cron here
               *  
               * */
              $time_space = strtotime($result[0]['auto_sync_interval'] . " days", 0);
              $timestamp = strtotime($result[0]['auto_sync_interval'] . " days");
              //as_schedule_single_action($next_schedule_date, 'set_recurring_auto_sync_product_feed_wise', array("feedId" => $feedId));
              as_schedule_recurring_action(esc_attr($timestamp), esc_attr($time_space), 'init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $feedId), "product_sync");
            }
            $feed_data = array(
              "product_sync_alert" => NULL,
              "is_process_start" => false,
              "is_auto_sync_start" => true,
              "last_sync_date" => esc_sql($last_sync_date),
              "next_schedule_date" => $next_schedule_date,
            );
            $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));
            $TVC_Admin_Helper->plugin_log("EE product sync process done", 'product_sync');
          } else {
            // add scheduled cron job            
            as_schedule_single_action(time() + 5, 'auto_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $feedId));
            $TVC_Admin_Helper->plugin_log("EE recall product sync process", 'product_sync');
          }
        } else {
          // add scheduled cron job
          as_unschedule_all_actions('auto_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $feedId));
        }
        echo wp_json_encode(array('status' => 'success', "message" => esc_html__("Feed wise product sync process started successfully", "enhanced-e-commerce-for-woocommerce-store")));
        return true;
      } catch (Exception $e) {
        $feed_data = array(
          "product_sync_alert" => $e->getMessage(),
        );
        $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $feedId));
        $conv_additional_data['product_sync_alert'] = $e->getMessage();
        $TVC_Admin_Helper->set_ee_additional_data($conv_additional_data);
        $TVC_Admin_Helper->plugin_log($e->getMessage(), 'product_sync');
        return true;
      }
    }

    function ee_super_AI_feed()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'create_superFeed_nonce', FILTER_SANITIZE_STRING), 'create_superFeed_nonce_val')) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $TVC_Admin_Helper->plugin_log('Super AI Feed start', 'product_sync');
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $CustomApi = new CustomApi();
        if (!class_exists('TVCProductSyncHelper')) {
          include(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php');
        }
        $TVCProductSyncHelper = new TVCProductSyncHelper();

        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        $mappedAttrs = unserialize(get_option('ee_prod_mapped_attrs'));
        $categories = is_array(unserialize(get_option('ee_prod_mapped_cats'))) ? unserialize(get_option('ee_prod_mapped_cats')) : '';
        if ($mappedAttrs == '') {
          $sel_val = get_locale();
          if (strlen($sel_val) > 0) {
            $sel_val = explode('_', $sel_val)[0];
          }
          $mappedAttrs = [
            'id' => 'ID',
            'title' => 'post_title',
            'description' => 'post_excerpt',
            'price' => '_regular_price',
            'sale_price' => '_sale_price',
            'brand' => '',
            'gtin' => '',
            'mpn' => '',
            'age_group' => '',
            'color' => '',
            'sizes' => '',
            'gender' => '',
            'product_weight' => '',
            'shipping_weight' => '',
            'shipping' => '',
            'tax' => '',
            'content_language' => $sel_val,
            'target_country' => $TVC_Admin_Helper->get_woo_country(),
            'availability' => '_stock_status',
            'condition' => 'new',
          ];
          update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));
        }
        $TVC_Admin_Helper->plugin_log('Attribute is mapped', 'product_sync');
        $feed_data = array(
          'feed_name' => esc_sql('Super AI Feed'),
          'channel_ids' => esc_sql(1),
          'auto_sync_interval' => esc_sql(25),
          'auto_schedule' => esc_sql(0),
          'created_date' => esc_sql(gmdate('Y-m-d H:i:s', current_time('timestamp'))),
          'status' => esc_sql('Draft'),
          'target_country' => esc_sql($TVC_Admin_Helper->get_woo_country()),
          "categories" => wp_json_encode($categories),
          "attributes" => wp_json_encode($mappedAttrs),
          "is_mapping_update" => true,
          "is_process_start" => false,
          "is_auto_sync_start" => false,
          "product_sync_batch_size" => esc_sql(100),
          "product_sync_alert" => sanitize_text_field("Product sync settings updated successfully"),
          "is_default" => esc_sql(0),
          "is_super_feed" => esc_sql(1)
        );


        $TVC_Admin_DB_Helper->tvc_add_row("ee_product_feed", $feed_data, array("%s", "%s", "%s", "%d", "%s", "%s", "%s", "%s", "%s", "%d", "%d", "%d", "%s", "%s", "%d", "%d"));
        $result = $TVC_Admin_DB_Helper->tvc_get_last_row("ee_product_feed", array("id"));
        $TVC_Admin_Helper->plugin_log("Feed data stored in DB", 'product_sync'); // Add logs

        /*******Update feed data in DB end**********************/

        /*******Update feed data in laravel start**********************/
        $feed_data_api = array(
          "store_id" => $google_detail['setting']->store_id,
          "store_feed_id" => $result['id'],
          "map_categories" => wp_json_encode($categories),
          "map_attributes" => wp_json_encode($mappedAttrs),
          "filter" => '',
          "include" => '',
          "exclude" => '',
          "channel_ids" => 1,
          "interval" => 25,
          "is_superfeed" => 1,
        );
        $TVC_Admin_Helper->plugin_log("Feed Data stored in Laravel", 'product_sync'); // Add logs

        $CustomApi->ee_create_product_feed($feed_data_api);
        /*****************Super Feed Product Sync ***************************************/
        $TVC_Admin_Helper->plugin_log('Call laravel API', 'product_sync');
        $isSyncComplete = $TVCProductSyncHelper->superFeedProductSync($result['id']);
        $totalProduct = 0;
        if ($isSyncComplete['status'] == 'success') {
          $last_sync_date = gmdate('Y-m-d H:i:s', current_time('timestamp'));
          $next_schedule_date = NULL;

          $feed_data = array(
            "product_sync_alert" => NULL,
            "is_process_start" => false,
            "is_auto_sync_start" => false,
            "last_sync_date" => esc_sql($last_sync_date),
            "next_schedule_date" => $next_schedule_date,
          );
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $result['id']));
          $where = '`id` = ' . esc_sql($result['id']);
          $filed = array(
            'total_product'
          );
          $data = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
          $totalProduct = $data[0]['total_product'];
          echo wp_json_encode(array('status' => 'success', 'message' => 'Product Sync Done!!!!', 'total_product' => $totalProduct));
        } else {
          $feed_data = array(
            "product_sync_alert" => $isSyncComplete['message'],
            "is_process_start" => false,
            "is_auto_sync_start" => false,
            "is_mapping_update" => false,
          );
          $TVC_Admin_Helper->plugin_log("error", 'product_sync'); // Add logs
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $result['id']));
          echo wp_json_encode(array('status' => 'error', 'message' => 'Error in Product Sync'));
        }

        exit;
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * Function used for to get TikTok Business Account by subcription id
     * hook used wp_ajax_get_tiktok_business_account
     * Type POST
     * parameter $subcriptionid
     */
    function get_tiktok_business_account()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conversios_onboarding_nonce', FILTER_SANITIZE_STRING), 'conversios_onboarding_nonce')) {
        if (isset($_POST['subscriptionId']) === TRUE && $_POST['subscriptionId'] !== '') {
          $customer_subscription_id['customer_subscription_id'] = sanitize_text_field($_POST['subscriptionId']);
          $customObj = new CustomApi();
          $result = $customObj->get_tiktok_business_account($customer_subscription_id);
          if ($result->status === 200 && is_array($result->data) && $result->data != '') {
            $tikTokData = [];
            foreach ($result->data as $value) {
              if ($value->bc_info->status === 'ENABLE') {
                $tikTokData[$value->bc_info->bc_id] = $value->bc_info->name;
              }
            }

            echo wp_json_encode(array("error" => false, "data" => $tikTokData));
          }
        } else {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Error: Business Account not found", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * Function used for to get TikTok Catalog Id business id
     * hook used wp_ajax_get_tiktok_user_catalogs
     * Type POST
     * parameter $businessId
     */
    function get_tiktok_user_catalogs()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conversios_onboarding_nonce', FILTER_SANITIZE_STRING), 'conversios_onboarding_nonce')) {
        if (isset($_POST['customer_subscription_id']) === TRUE && $_POST['customer_subscription_id'] !== '' && $_POST['business_id'] !== '') {
          $customer_subscription_id['customer_subscription_id'] = sanitize_text_field($_POST['customer_subscription_id']);
          $customer_subscription_id['business_id'] = sanitize_text_field($_POST['business_id']);
          $customObj = new CustomApi();
          $result = $customObj->get_tiktok_user_catalogs($customer_subscription_id);
          if ($result->status === 200 && is_array($result->data) && $result->data != '') {
            $tikTokData = [];
            foreach ($result->data as $key => $value) {
              $tikTokData[$value->catalog_conf->country][$value->catalog_id] = $value->catalog_name;
            }

            foreach ($tikTokData as &$subArray) {
              arsort($subArray);
            }

            echo wp_json_encode(array("error" => false, "data" => $tikTokData));
          }
        } else {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Error: Business Account not found", "enhanced-e-commerce-for-woocommerce-store")));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function conv_save_tiktokmiddleware($post)
    {
      if (isset($post['customer_subscription_id']) === TRUE && $post['customer_subscription_id'] !== '' && $post['conv_options_data']['tiktok_setting']['tiktok_business_id'] !== '') {
        $customer_subscription_id['customer_subscription_id'] = sanitize_text_field($_POST['customer_subscription_id']);
        $customer_subscription_id['business_id'] = $post['conv_options_data']['tiktok_setting']['tiktok_business_id'];
        $customObj = new CustomApi();
        $result = $customObj->store_business_center($customer_subscription_id);
        return $result;
      }
    }
    public function conv_save_tiktokcatalog($post)
    {
      $catArr = [];
      $i = 0;
      $values = array();
      $place_holders = array();

      foreach ($post['conv_catalogData'] as $key => $value) {
        $catArr[$i]["region_code"] = $key;
        $catArr[$i++]["catalog_id"] = $value[0];
        array_push($values, esc_sql($key), esc_sql($value[0]), esc_sql($value[1]), gmdate('Y-m-d H:i:s', current_time('timestamp')));
        $place_holders[] = "('%s', '%s', '%s','%s')";
      }

      $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
      global $wpdb;
      $ee_tiktok_catalog = esc_sql($wpdb->prefix . "ee_tiktok_catalog");

      if ($TVC_Admin_DB_Helper->tvc_row_count("ee_tiktok_catalog") > 0) {
        $TVC_Admin_DB_Helper->tvc_safe_truncate_table($ee_tiktok_catalog);
      }
      //Insert tiktok catalog data into db
      $query = "INSERT INTO `$ee_tiktok_catalog` (country, catalog_id, catalog_name, created_date) VALUES ";
      $query .= implode(', ', $place_holders);
      $wpdb->query($wpdb->prepare($query, $values));
      if (isset($post['customer_subscription_id']) === TRUE && $post['customer_subscription_id'] !== '' && $post['conv_options_data']['tiktok_setting']['tiktok_business_id'] !== '') {
        $customer_subscription_id['customer_subscription_id'] = sanitize_text_field($_POST['customer_subscription_id']);
        $customer_subscription_id['business_id'] = $post['conv_options_data']['tiktok_setting']['tiktok_business_id'];
        $customer_subscription_id['catalogs'] = $catArr;
        $customObj = new CustomApi();
        $result = $customObj->store_user_catalog($customer_subscription_id);
        return $result;
      }
    }

    public function ee_getCatalogId()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_country_nonce', FILTER_SANITIZE_STRING), 'conv_country_nonce')) {
        if (isset($_POST['countryCode']) === TRUE && $_POST['countryCode'] !== '') {
          $country_code = sanitize_text_field($_POST['countryCode']);
          $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
          $where = '`country` = "' . esc_sql($country_code) . '"';
          $filed = array('catalog_id');
          $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_tiktok_catalog", $where, $filed);
          $catalog_id['catalog_id'] = isset($result[0]['catalog_id']) === TRUE && isset($result[0]['catalog_id']) !== '' ? $result[0]['catalog_id'] : '';
          echo wp_json_encode(array("error" => false, "data" => $catalog_id));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }

    /**
     * Function used for to create Pmax Campaign
     * hook used wp_ajax_ee_createPmaxCampaign
     * Type POST
     * parameter POST value
     */
    public function ee_createPmaxCampaign()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        if ($_POST['subscription_id'] == '') {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Subscription Id is missing. Contact plugin Admin", "enhanced-e-commerce-for-woocommerce-store")));
          exit;
        }
        if ($_POST['google_merchant_id'] == '') {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Google Merchant Id is missing. Please map Google Merchant Id.", "enhanced-e-commerce-for-woocommerce-store")));
          exit;
        }
        if ($_POST['google_ads_id'] == '') {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Google Ads Id is missing. Please map Google Ads Id.", "enhanced-e-commerce-for-woocommerce-store")));
          exit;
        }
        if ($_POST['store_id'] == '') {
          echo wp_json_encode(array("error" => true, "message" => esc_html__("Store Id is missing. Contact plugin Admin.", "enhanced-e-commerce-for-woocommerce-store")));
          exit;
        }
        $customObj = new CustomApi();
        $post = array(
          "campaign_name" => sanitize_text_field($_POST['campaign_name']),
          "budget" => sanitize_text_field($_POST['budget']),
          "target_country" => sanitize_text_field($_POST['target_country']),
          "start_date" => sanitize_text_field($_POST['start_date']),
          "end_date" => sanitize_text_field($_POST['end_date']),
          "target_roas" => sanitize_text_field($_POST['target_roas']),
          "status" => sanitize_text_field($_POST['status']),
          "subscription_id" => sanitize_text_field($_POST['subscription_id']),
          "google_merchant_id" => sanitize_text_field($_POST['google_merchant_id']),
          "google_ads_id" => sanitize_text_field($_POST['google_ads_id']),
          "sync_item_ids" => sanitize_text_field($_POST['sync_item_ids']),
          "domain" => sanitize_text_field($_POST['domain']),
          "store_id" => sanitize_text_field($_POST['store_id']),
          "sync_type" => sanitize_text_field($_POST['sync_type']),
        );
        $result = $customObj->createPmaxCampaign($post);
        if (isset($result->data->request_id) && $result->data->request_id !== '') {
          $values = array();
          $place_holders = array();
          global $wpdb;
          $ee_pmax_campaign = esc_sql($wpdb->prefix . "ee_pmax_campaign");
          $place_holders[] = "('%s', '%s', '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s')";
          array_push($values, esc_sql(sanitize_text_field($_POST['campaign_name'])), esc_sql(sanitize_text_field($_POST['budget'])), esc_sql(sanitize_text_field($_POST['target_country'])), esc_sql(sanitize_text_field($_POST['target_roas'])), esc_sql(sanitize_text_field($_POST['start_date'])), esc_sql(sanitize_text_field($_POST['end_date'])), esc_sql(sanitize_text_field($_POST['status'])), esc_sql(sanitize_text_field($_POST['sync_item_ids'])), esc_sql(sanitize_text_field($result->data->request_id)), gmdate('Y-m-d H:i:s', current_time('timestamp')));
          //Insert Campaign data into db
          $query = "INSERT INTO `$ee_pmax_campaign` (campaign_name, daily_budget, target_country_campaign, target_roas, start_date, end_date, status, feed_id, request_id, created_date) VALUES ";
          $query .= implode(', ', $place_holders);
          $wpdb->query($wpdb->prepare($query, $values));
          echo wp_json_encode(array("error" => false, "data" => $result->data));
        } else {
          echo wp_json_encode(array("error" => true, "message" => esc_html($result->error_data[411]->errors[0])));
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function get_pf_accordian_data()
    {
      $ee_prod_mapped_attrs = get_option("ee_prod_mapped_attrs");
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $TVC_Admin_Helper->get_feed_status();
      $feed_data = $TVC_Admin_Helper->ee_get_result_limit('ee_product_feed', 2);
      $feed_count = !empty($feed_data) ? count($feed_data) : 0;
      echo wp_json_encode(array("feed_count" => $feed_count, "ee_prod_mapped_attrs" => unserialize($ee_prod_mapped_attrs), 'feed_data' => $feed_data));
      exit;
    }
    public function get_category_for_filter()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'get_category_for_filter', FILTER_SANITIZE_STRING), 'get_category_for_filter-nonce')) {
        switch ($_POST['type']) {
          case 'category':
            $tvc_admin_helper = new TVC_Admin_Helper();
            $category = $tvc_admin_helper->get_tvc_product_cat_list_with_name();
            echo wp_json_encode($category);
            break;
          case 'all_product':
            $total_products = (new WP_Query(['post_type' => 'product', 'post_status' => 'publish']))->found_posts;
            echo wp_json_encode($total_products);
            break;
          case "getAllChannel":
            $arrData = array();
            $ee_options = unserialize(get_option("ee_options"));
            $fb_catalog_id = isset($ee_options['facebook_setting']['fb_catalog_id']) === TRUE ? $ee_options['facebook_setting']['fb_catalog_id'] : '';
            $google_merchant_center_id = isset($ee_options['google_merchant_center_id']) === TRUE ? $ee_options['google_merchant_center_id'] : '';
            $arrData['fb_catalog_id'] = $fb_catalog_id;
            $arrData['google_merchant_center_id'] = $google_merchant_center_id;
            $arrData['tiktok_setting'] = 0;
            if (isset($ee_options['tiktok_setting']['tiktok_business_id'])) {
              $country_code = sanitize_text_field($_POST['target_country']);
              $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
              $where = '`country` = "' . esc_sql($country_code) . '"';
              $filed = array('catalog_id');
              $arrData['tiktok_setting'] = 1;
              $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_tiktok_catalog", $where, $filed);
              $arrData['titkok_catalog_id'] = isset($result[0]['catalog_id']) === TRUE && isset($result[0]['catalog_id']) !== '' ? $result[0]['catalog_id'] : '';
            }
            echo wp_json_encode(array("error" => false, "data" => $arrData));
            break;
          case "getProgressCount":
            $tvc_admin_helper = new TVC_Admin_Helper();
            $feed_data = $tvc_admin_helper->ee_get_result_limit('ee_product_feed', 1);
            $feed_count = !empty($feed_data) ? count($feed_data) : 0;
            $ee_mapped_attrs = unserialize(get_option('ee_prod_mapped_attrs'));
            $isAttrMapped = 0;
            if ($ee_mapped_attrs) {
              $isAttrMapped = 1;
            }
            $ee_options = unserialize(get_option("ee_options"));
            $is_channel_connected = 0;
            if (isset($ee_options['google_merchant_center_id']) && $ee_options['google_merchant_center_id'] !== '') {
              $is_channel_connected = 1;
            }
            if (isset($ee_options['facebook_setting']) && $ee_options['facebook_setting']['fb_business_id'] !== '' && $is_channel_connected == 0) {
              $is_channel_connected = 1;
            }
            if (isset($ee_options['tiktok_setting']) && $ee_options['tiktok_setting']['tiktok_business_id'] !== '' && $is_channel_connected == 0) {
              $is_channel_connected = 1;
            }
            echo wp_json_encode(array("error" => false, "feed_count" => $feed_count, "isAttrMapped" => $isAttrMapped, "is_channel_connected" => $is_channel_connected));
            break;
          case "getFeedList":
            $tvc_admin_helper = new TVC_Admin_Helper();
            $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
            $where = '`status` IN ("Synced","Draft", "In Progress","Failed")';
            $filed = array(
              'id',
              'feed_name',
              'total_product',
              'status'
            );
            $result = $TVC_Admin_DB_Helper->tvc_get_results_in_array("ee_product_feed", $where, $filed);
            $ee_options = unserialize(get_option("ee_options"));
            $google_ads_id = '';
            $currency_symbol = '';
            if (isset($ee_options['google_ads_id']) === TRUE && $ee_options['google_ads_id'] !== '') {
              $google_ads_id = $ee_options['google_ads_id'];
              $PMax_Helper = new Conversios_PMax_Helper();
              $currency_code_rs = $PMax_Helper->get_campaign_currency_code($google_ads_id);
              if (isset($currency_code_rs->data->currencyCode)) {
                $currency_code = $currency_code_rs->data->currencyCode;
                $currency_symbol = $tvc_admin_helper->get_currency_symbols($currency_code);
              }
            }

            if ($ee_options['subscription_id'] % 2 == 0) {
              $href = esc_url('admin.php?page=conversios&wizard=productFeedEven');  //Even
            } else {
              $href = esc_url('admin.php?page=conversios&wizard=productFeedOdd'); //Odd
            }
            echo wp_json_encode(array("data" => $result, "currency_symbol" => $currency_symbol, "href" => $href));
            break;
          case "get_campaign_accordan":
            $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
            $count = $TVC_Admin_DB_Helper->tvc_row_count('ee_pmax_campaign');
            echo wp_json_encode(array("count" => $count));
            break;
          case "getProgressCount_campaign":
            $ee_options = unserialize(get_option("ee_options"));
            $is_channel_connected = false;
            if (isset($ee_options['google_merchant_center_id']) && $ee_options['google_merchant_center_id'] !== '' && isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== '') {
              $is_channel_connected = true;
            }
            $tvc_admin_helper = new TVC_Admin_Helper();
            $feed_data = $tvc_admin_helper->ee_get_result_limit('ee_product_feed', 2);
            $feed_count = !empty($feed_data) ? count($feed_data) : 0;
            echo wp_json_encode(array("feed_count" => $feed_count, "is_channel_connected" => $is_channel_connected));
            break;
        }
        exit;
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function get_attribute_mappingv_div()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'fb_business_nonce', FILTER_SANITIZE_STRING), 'fb_business_nonce')) {
        if (!class_exists('TVCProductSyncHelper')) {
          include(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php');
        }
        $TVCProductSyncHelper = new TVCProductSyncHelper();
        $wooCommerceAttributes = array_map("unserialize", array_unique(array_map("serialize", $TVCProductSyncHelper->wooCommerceAttributes())));
        $ee_mapped_attrs = unserialize(get_option('ee_prod_mapped_attrs'));
        $tempAddAttr = $ee_mapped_attrs;
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $gmcAttributes = $TVC_Admin_Helper->get_gmcAttributes();
        $html = '';
        $path = ENHANCAD_PLUGIN_DIR . 'includes/setup/json/iso_lang.json';
        $str = file_get_contents($path);
        $countries_list = $str ? json_decode($str, true) : [];
        foreach ($gmcAttributes as $key => $attribute) {
          unset($tempAddAttr[$attribute["field"]]);
          $html .= '<div class="col-6 mt-2" style="text-align: left">
                  <span class="ps-3 fw-400 text-color fs-12">
                      ' . esc_attr($attribute["field"]) . '' . (isset($attribute["required"]) === TRUE && esc_attr($attribute["required"]) === '1' ? '<span class="text-color fs-6"> *</span>' : "") . '
                      <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                          data-bs-placement="right"
                          title="' . (isset($attribute['desc']) === TRUE ? esc_attr($attribute['desc']) : '') . '">
                          info
                      </span>
                  </span>
              </div><div class="col-5 mt-2">';
          $sel_val = "";
          $ee_select_option = $TVC_Admin_Helper->add_additional_option_in_tvc_select($wooCommerceAttributes, $attribute["field"]);
          $require = FALSE;
          if (isset($attribute['required']) === TRUE) {
            $require = TRUE;
          }
          $sel_val_def = "";
          if (isset($attribute['wAttribute']) === TRUE) {
            $sel_val_def = $attribute['wAttribute'];
          }
          if ($attribute["field"] === 'link') {
            "product link";
          } else if ($attribute["field"] === 'shipping') {
            $sel_val = esc_attr($sel_val_def);
            if (isset($ee_mapped_attrs[$attribute["field"]]) === TRUE) {
              $sel_val = esc_attr($ee_mapped_attrs[$attribute["field"]]);
            }
            $html .= '<input style="width:100%;" type="number" min="0" name="' . esc_attr($attribute["field"]) . '" class="form-control from-control-overload fw-light text-secondary fs-6 tvc-text" id="" placeholder="' . esc_attr('Add shipping flat rate') . '" value="' . esc_attr($sel_val) . '">';
          } else if ($attribute["field"] === 'tax') {
            $sel_val = esc_attr($sel_val_def);
            if (isset($ee_mapped_attrs[$attribute["field"]]) === TRUE) {
              $sel_val = esc_attr($ee_mapped_attrs[$attribute["field"]]);
            }
            $html .= '<input style="width:100%;" type="number" min="0" name="' . esc_attr($attribute["field"]) . '" class="form-control from-control-overload fw-light text-secondary fs-6 tvc-text" id="" placeholder="' . esc_attr('Add TAX flat (%)') . '" value="' . esc_attr($sel_val) . '">';
          } else if ($attribute["field"] === 'content_language') {
            $sel_val = get_locale();
            if (strlen($sel_val) > 0) {
              $sel_val = explode('_', $sel_val)[0];
            }

            $requiredtext = '';
            if ($require == true) {
              $requiredtext = 'field-required';
            }
            $html .= '<select style="width: 100%" class="fw-light text-secondary fs-6 form-control form-select-sm select_modal content_language ' . $requiredtext . '" name="' . $attribute["field"] . '" id="content_language">
                          <option value="">Please Select Attribute</option>';
            foreach ($countries_list as $Key => $val) {
              $selText = '';
              if ($val["code"] == $sel_val) {
                $selText = 'selected';
              }
              $html .= '<option value="' . esc_attr($val["code"]) . '" ' . $selText . ' > ' . esc_html($val["name"]) . " (" . esc_attr($val["native_name"]) . ")" . '</option>';
            }
            $html .= '</select>';
          } else if ($attribute["field"] === 'target_country') {
            $store_raw_country = get_option('woocommerce_default_country');
            $country = explode(":", $store_raw_country);
            $sel_val = (isset($country[0])) ? $country[0] : "";
            $requiredtext = '';
            if ($require == true) {
              $requiredtext = 'field-required';
            }
            //$countries_list = $this->get_gmc_countries_list();
            $path = ENHANCAD_PLUGIN_DIR . 'includes/setup/json/countries.json';
            $str = file_get_contents($path);
            $countries_list = $str ? json_decode($str, true) : [];
            //$sel_val = $this->get_woo_country();
            $html .= '<select style="width: 100%" class="fw-light text-secondary fs-6 form-control form-select-sm select_modal target_country ' . $requiredtext . '" name="' . $attribute["field"] . '" id="target_country">
                          <option value="">Please Select Attribute</option>';
            foreach ($countries_list as $Key => $val) {
              $selText = '';
              if ($val["code"] == $sel_val) {
                $selText = 'selected';
              }
              $html .= '<option value="' . esc_attr($val["code"]) . '" ' . $selText . '>' . esc_html($val["name"]) . '</option>';
            }
            $html .= '</select>';
          } else {
            if (isset($attribute['fixed_options']) === TRUE && $attribute['fixed_options'] !== "") {
              $ee_select_option_t = explode(",", $attribute['fixed_options']);
              $ee_select_option = [];
              foreach ($ee_select_option_t as $o_val) {
                $ee_select_option[]['field'] = esc_attr($o_val);
              }

              $sel_val = $sel_val_def;
              $requiredtext = '';
              if ($require == true) {
                $requiredtext = 'field-required';
              }
              if (!empty($ee_select_option) && $attribute["field"]) {
                $html .= '<select style="width: 100%" class="fw-light text-secondary fs-6 form-control form-select-sm select_modal ' . $attribute["field"] . ' ' . $requiredtext . '" name="' . $attribute["field"] . '" id="' . $attribute["field"] . '">';
                $html .= '<option value="">Please Select Attribute</option>';
                foreach ($ee_select_option as $Key => $val) {
                  $selText = '';
                  if ($val["field"] == $sel_val) {
                    $selText = 'selected';
                  }
                  $html .= '<option value="' . esc_attr($val["field"]) . '" ' . $selText . '>' . esc_html($val["field"]) . '</option>';
                }
                if ($attribute["field"] == 'brand') {
                  $html .= '<option value="product_cat" ' . ("product_cat" == $sel_val) ? "selected" : "" . '>product_cat</option>';
                }
              }
              $html .= '</select>';
            } else {
              $sel_val = esc_attr($sel_val_def);
              if (isset($ee_mapped_attrs[$attribute["field"]]) === TRUE) {
                $sel_val = esc_attr($ee_mapped_attrs[$attribute["field"]]);
              }
              $requiredtext = '';
              if ($require == true) {
                $requiredtext = 'field-required';
              }
              if (!empty($ee_select_option) && $attribute["field"]) {
                $html .= '<select style="width: 100%" class="fw-light text-secondary fs-6 form-control form-select-sm select_modal ' . $attribute["field"] . ' ' . $requiredtext . '" name="' . $attribute["field"] . '" id="' . $attribute["field"] . '">';
                $html .= '<option value="">Please Select Attribute</option>';
                foreach ($ee_select_option as $Key => $val) {
                  $selText = '';
                  if ($val["field"] == $sel_val) {
                    $selText = 'selected';
                  }
                  $html .= '<option value="' . esc_attr($val["field"]) . '" ' . $selText . '>' . esc_html($val["field"]) . '</option>';
                }
                if ($attribute["field"] == 'brand') {
                  $html .= '<option value="product_cat" ' . ("product_cat" == $sel_val) ? "selected" : "" . '>product_cat</option>';
                }
              }
              $html .= '</select>';
            }
          }
          $html .= '</div>';
        }
        $html .= '<div class="col-12 m-0 p-0 additinal_attr_main_div">';
        $cnt = 0;
        if (!empty($tempAddAttr)) {
          $additionalAttribute = array(
            'condition', 'shipping_weight', 'product_weight', 'gender', 'sizes', 'color', 'age_group', 'additional_image_links', 'sale_price_effective_date', 'material',
            'pattern', 'product_types', 'availability_date', 'expiration_date', 'adult', 'ads_redirect',
            'shipping_length', 'shipping_width', 'shipping_height', 'custom_label_0',
            'custom_label_1', 'custom_label_2',
            'custom_label_3', 'custom_label_4', 'mobile_link', 'energy_efficiency_class',
            'is_bundle', 'loyalty_points', 'unit_pricing_measure', 'unit_pricing_base_measure',
            'promotion_ids', 'shipping_label', 'excluded_destinations', 'included_destinations', 'tax_category',
            'multipack', 'installment', 'min_handling_time', 'max_handling_time', 'min_energy_efficiency_class',
            'max_energy_efficiency_class', 'identifier_exists', 'cost_of_goods_sold'
          );
          $count_arr = count($additionalAttribute);
          foreach ($tempAddAttr as $key => $value) {
            $options = '<option>Please Select Attribute</option>';
            foreach ($additionalAttribute as $val) {
              $selected = "";
              $disabled = "";
              if ($val == $key) {
                $selected = "selected";
              } else {
                if (array_key_exists($val, $tempAddAttr)) {
                  $disabled = "disabled";
                }
              }

              $options .= '<option value="' . $val . '" ' . $selected . ' ' . $disabled . '>' . $val . '</option>';
            }
            $option1 = '<option>Please Select Attribute</option>';
            $fixed_att_select_list = ["gender", "age_group", "condition"];
            if (in_array($key, $fixed_att_select_list)) {
              if ($key == 'gender') {
                $gender = ['male' => 'Male', 'female' => 'Female', 'unisex' => 'Unisex'];
                foreach ($gender as $genKey => $genVal) {
                  $selected = "";
                  if ($genKey == $value) {
                    $selected = "selected";
                  }
                  $option1 .= '<option value="' . $genKey . '" ' . $selected . '>' . $genVal . '</option>';
                }
              }
              if ($key == 'condition') {
                $conArr = ['new' => 'New', 'refurbished' => 'Refurbished', 'used' => 'Used'];
                foreach ($conArr as $conKey => $conVal) {
                  $selected = "";
                  if ($conKey == $value) {
                    $selected = "selected";
                  }
                  $option1 .= '<option value="' . $conKey . '" ' . $selected . '>' . $conVal . '</option>';
                }
              }
              if ($key == 'age_group') {
                $ageArr = ['newborn' => 'Newborn', 'infant' => 'Infant', 'toddler' => 'Toddler', 'kids' => 'Kids', 'adult' => 'Adult'];
                foreach ($ageArr as $ageKey => $ageVal) {
                  $selected = "";
                  if ($ageKey == $value) {
                    $selected = "selected";
                  }
                  $option1 .= '<option value="' . $ageKey . '" ' . $selected . '>' . $ageVal . '</option>';
                }
                $option1 .= '<option value="newborn">Newborn</option>';
                $option1 .= '<option value="infant">Infant</option>';
                $option1 .= '<option value="toddler">Toddler</option>';
                $option1 .= '<option value="kids">Kids</option>';
                $option1 .= '<option value="adult">Adult</option>';
              }
            } else {
              foreach ($wooCommerceAttributes as $valattr) {
                $selected = "";
                if ($valattr['field'] == $value) {
                  $selected = "selected";
                }
                $option1 .= '<option value="' . $valattr['field'] . '" ' . $selected . '>' . $valattr['field'] . '</option>';
              }
            }
            $html .= '<div class="row additinal_attr_div m-0 p-0" ><div class="col-6 mt-2">';
            $html .= '<select style="width:100%" id="' . $cnt++ . '" name="additional_attr_[]" class="additinal_attr fw-light text-secondary fs-6 form-control form-select-sm select_modal select2-hidden-accessible">';
            $html .= $options;
            $html .= '</select></div>';
            $html .= '<div class="col-5 mt-2">';
            $html .= '<select style="width:100%" id="" name="additional_attr_value_[]" class="additional_attr_value fw-light text-secondary fs-6 form-control form-select-sm select_modal select2-hidden-accessible">';
            $html .= $option1;
            $html .= '</select></div>';
            $html .= '<div class="col-1 mt-2"><span class="material-symbols-outlined text-danger remove_additional_attr fs-5 mt-2" title="Add Additional Attribute" style="cursor: pointer; margin-right:35px;">delete</span></div></div>';
          }
        }
        $cntTxt = '';
        if (isset($count_arr) && $count_arr == $cnt) {
          $cntTxt = 'd-none';
        }
        $html .= '</div><div class="row add_additional_attr_div m-0 p-0" >
                <div class="add_additional_attr_div mt-2" style="display: flex; justify-content: start">
                <button type="button" class="px-5 fs-12 btn btn-primary add_additional_attr ' . $cntTxt . '" title="Add Attribute"> Add Attributes
                </button><input type="hidden" name="cnt" id="cnt" value="' . $cnt . '"></div></div>';
        //echo $html;

        echo wp_kses($html, array(
          "div" => array(
            'class' => array(),
            'style' => array(),
            'id' => array(),
            'title' => array(),
          ),
          "button" => array(
            'type' => array(),
            'class' => array(),
            'style' => array(),
            'id' => array(),
            'title' => array(),
          ),
          "option" => array(
            'value' => array(),
            'selected' => array(),
          ),
          "span" => array(
            'class' => array(),
            'style' => array(),
            'id' => array(),
            'title' => array(),
            'data-bs-toggle' => array(),
            'data-bs-placement' => array(),
          ),
          "input" => array(
            'type' => array(),
            'name' => array(),
            'class' => array(),
            'id' => array(),
            'placeholder' => array(),
          ),
          "select" => array(
            'name' => array(),
            'class' => array(),
            'id' => array(),
          ),
          "form" => array(
            'method' => array(),
            'class' => array(),
            'id' => array(),
            'name' => array(),
          ),
        ));
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function get_product_filter_count()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'getFilterCount', FILTER_SANITIZE_STRING), 'getFilterCount-nonce')) {
        global $wpdb;
        $where = array();
        $wherePriJoin = $whereSKUJoin = $conditionprod = $condition = $conditionSKU = $conditionContent = $conditionExcerpt = $conditionPrice = $conditionRegPrice = $whereStockJoin = $conditionStock = '';
        $product_cat1 = $product_cat2 = $product_id1 = $product_id2 = $whereCond = $whereCondsku = $whereCondcontent = $whereExcerpt = $whereCondregPri = $whereCondPri = $wherestock = array();


        $productSearch = explode(',', sanitize_text_field($_POST['productVal']));
        $conditionSearch = explode(',', sanitize_text_field($_POST['conditionVal']));
        $valueSearch = explode(',', sanitize_text_field($_POST['valueVal']));
        foreach ($productSearch as $key => $value) {
          switch ($value) {
            case 'product_cat':
              if ($conditionSearch[$key] == "=") {
                $product_cat1[] = sanitize_text_field($valueSearch[$key]);
                $where['IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . implode(",", $product_cat1) . ') )';
              } else if ($conditionSearch[$key] == "!=") {
                $product_cat2[] = sanitize_text_field($valueSearch[$key]);
                $where['NOT IN'] = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id NOT IN (' . implode(",", $product_cat2) . ') )';
              }
              break;
            case '_stock_status':
              if (!empty($conditionSearch[$key])) {
                $wherestock[] = '(pm4.meta_key = "' . sanitize_text_field($value) . '" AND pm4.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . ' "' . sanitize_text_field($valueSearch[$key]) . '")';
                $whereStockJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm4 ON pm4.post_id = ' . $wpdb->prefix . 'posts.ID';
              }
              break;
            case 'ID':
              if ($conditionSearch[$key] == "=") {
                $product_id1[] = sanitize_text_field($valueSearch[$key]);
                $where['IDIN'] = '(' . $wpdb->prefix . 'posts.ID IN (' . implode(",", $product_id1) . ') )';
              } else if ($conditionSearch[$key] == "!=") {
                $product_id2[] = sanitize_text_field($valueSearch[$key]);
                $where['IDNOTIN'] = '(' . $wpdb->prefix . 'posts.ID NOT IN (' . implode(",", $product_id2) . ') )';
              }
              break;
            case 'post_title':
              if ($conditionSearch[$key] == "Contains") {
                $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "Start With") {
                $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "End With") {
                $whereCond[] = '' . $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
              }
              break;
            case '_sku':
              if ($conditionSearch[$key] == "Contains") {
                $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "Start With") {
                $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "End With") {
                $whereCondsku[] = 'pm2.meta_key = "' . sanitize_text_field($value) . '" AND pm2.meta_value ' . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
              }
              $whereSKUJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm2 ON pm2.post_id = ' . $wpdb->prefix . 'posts.ID';
              break;
            case '_regular_price':
              if (!empty($conditionSearch[$key])) {
                $whereCondPri[] = '(pm3.meta_key = "' . sanitize_text_field($value) . '" AND pm3.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . sanitize_text_field($valueSearch[$key]) . ')';
                $wherePriJoin = 'LEFT JOIN ' . $wpdb->prefix . 'postmeta pm3 ON pm3.post_id = ' . $wpdb->prefix . 'posts.ID';
              }
              break;
            case '_sale_price':
              if (!empty($conditionSearch[$key])) {
                $whereCondregPri[] = '(pm1.meta_key = "' . sanitize_text_field($value) . '" AND pm1.meta_value  ' . sanitize_text_field($conditionSearch[$key]) . sanitize_text_field($valueSearch[$key]) . ')';
              }
              break;

            case 'post_content':
              if ($conditionSearch[$key] == "Contains") {
                $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "Start With") {
                $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "End With") {
                $whereCondcontent[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
              }
              break;
            case 'post_excerpt':
              if ($conditionSearch[$key] == "Contains") {
                $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "Start With") {
                $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("' . sanitize_text_field($valueSearch[$key]) . '%%")';
              } else if ($conditionSearch[$key] == "End With") {
                $whereExcerpt[] = $wpdb->prefix . 'posts.' . sanitize_text_field($value) . ' LIKE ("%%' . sanitize_text_field($valueSearch[$key]) . '")';
              }
              break;
          }
        }
        $conditionprod = (!empty($where)) ? 'AND (' . implode(' AND ', $where) . ')' : '';
        $condition = (!empty($whereCond)) ? 'AND (' . implode(' OR ', $whereCond) . ')' : '';
        $conditionSKU = (!empty($whereCondsku)) ? 'AND (' . implode(' OR ', $whereCondsku) . ')' : '';
        $conditionContent = (!empty($whereCondcontent)) ? 'AND (' . implode(' OR ', $whereCondcontent) . ')' : '';
        $conditionExcerpt = (!empty($whereExcerpt)) ? 'AND (' . implode(' OR ', $whereExcerpt) . ')' : '';
        $conditionPrice = (!empty($whereCondregPri)) ? 'AND (' . implode(' OR ', $whereCondregPri) . ')' : '';
        $conditionRegPrice = (!empty($whereCondPri)) ? 'AND (' . implode(' OR ', $whereCondPri) . ')' : '';
        $conditionStock = (!empty($wherestock)) ? 'AND (' . implode(' OR ', $wherestock) . ')' : '';
        $countSql = "SELECT " . $wpdb->prefix . "posts.ID, " . $wpdb->prefix . "posts.post_title, " . $wpdb->prefix . "posts.post_excerpt, " . $wpdb->prefix . "posts.post_content
                        FROM " . $wpdb->prefix . "posts 
                        LEFT JOIN " . $wpdb->prefix . "postmeta pm1 ON pm1.post_id = " . $wpdb->prefix . "posts.ID
                        " . $whereSKUJoin . " " . $wherePriJoin . " " . $whereStockJoin . "
                        LEFT JOIN " . $wpdb->prefix . "term_relationships ON (" . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "term_relationships.object_id) 
                        JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tt.taxonomy = 'product_cat' AND tt.term_taxonomy_id = " . $wpdb->prefix . "term_relationships.term_taxonomy_id 
                        JOIN " . $wpdb->prefix . "terms AS t ON t.term_id = tt.term_id
                        
                        WHERE 1=1  " . $conditionprod . " " . $condition . " " . $conditionSKU . " " . $conditionContent . " " . $conditionExcerpt . " " . $conditionPrice . " " . $conditionRegPrice . " " . $conditionStock . "
                        AND " . $wpdb->prefix . "posts.post_type = 'product' AND ((" . $wpdb->prefix . "posts.post_status = 'publish')) 
                        GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.post_date DESC";


        $allResult = $wpdb->get_results($countSql, ARRAY_A);
        $pagination_count = $wpdb->num_rows;
        wp_reset_query();
        echo wp_json_encode($pagination_count);
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
    public function create_dashboard_feed_data()
    {
      if ($this->safe_ajax_call(filter_input(INPUT_POST, 'conv_onboarding_nonce', FILTER_SANITIZE_STRING), 'conv_onboarding_nonce')) {
        //$this->ee_call_start_feed_wise_product_sync_process(4);
        $TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
        $channel_id = array();
        if (isset($_POST['google_merchant_center']) && $_POST['google_merchant_center'] == 1) {
          $channel_id['google_merchant_center'] = sanitize_text_field($_POST['google_merchant_center']);
        }
        if (isset($_POST['tiktok_id']) && $_POST['tiktok_id'] == 3) {
          $channel_id['tiktok_id'] = sanitize_text_field($_POST['tiktok_id']);
        }

        // if (isset($_POST['fb_catalog_id']) && $_POST['fb_catalog_id'] == 2) {
        //   $channel_id['fb_catalog_id'] = $_POST['fb_catalog_id'];
        // }
        $channel_ids = implode(',', $channel_id);

        $tiktok_catalog_id = '';
        if (isset($_POST['tiktok_catalog_id']) === TRUE && $_POST['tiktok_catalog_id'] !== '') {
          $tiktok_catalog_id = sanitize_text_field($_POST['tiktok_catalog_id']);
        }
        /**
         * Check catalog id available
         */
        if (isset($_POST['tiktok_catalog_id']) === TRUE && $_POST['tiktok_catalog_id'] === 'Create New') {
          /**
           * Create catalog id
           */
          $getCountris = @file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/countries_currency.json");
          $contData = json_decode($getCountris);
          $currency_code = '';
          foreach ($contData as $key => $data) {
            if ($data->countryCode === $_POST['target_country']) {
              $currency_code = $data->currencyCode;
            }
          }
          $customer['customer_subscription_id'] = sanitize_text_field($_POST['customer_subscription_id']);
          $customer['business_id'] = sanitize_text_field($_POST['tiktok_business_account']);
          $customer['catalog_name'] = sanitize_text_field($_POST['feedName']);
          $customer['region_code'] = sanitize_text_field($_POST['target_country']);
          $customer['currency'] = sanitize_text_field($currency_code);
          $customObj = new CustomApi();
          $result = $customObj->createCatalogs($customer);
          if (isset($result->error_data) === TRUE) {
            foreach ($result->error_data as $key => $value) {
              echo wp_json_encode(array("error" => true, "message" => $value->errors[0], "errorType" => "tiktok"));
              exit;
            }
          }

          if (isset($result->status) === TRUE && $result->status === 200) {
            $tiktok_catalog_id = $result->data->catalog_id;
            $values = array();
            $place_holders = array();
            global $wpdb;
            $ee_tiktok_catalog = esc_sql($wpdb->prefix . "ee_tiktok_catalog");
            array_push($values, esc_sql(sanitize_text_field($_POST['target_country'])), esc_sql($tiktok_catalog_id), esc_sql(sanitize_text_field($_POST['feedName'])), gmdate('Y-m-d H:i:s', current_time('timestamp')));
            $place_holders[] = "('%s', '%s', '%s','%s')";
            $query = "INSERT INTO `$ee_tiktok_catalog` (country, catalog_id, catalog_name, created_date) VALUES ";
            $query .= implode(', ', $place_holders);
            $wpdb->query($wpdb->prepare($query, $values));
          }
        }

        $mappedAttrs = false;
        $categories = is_array(unserialize(get_option('ee_prod_mapped_cats'))) ? unserialize(get_option('ee_prod_mapped_cats')) : false;
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        if ($_POST['product_selection'] !== 'specific_product') {
          $mappedAttrs = unserialize(get_option('ee_prod_mapped_attrs'));
          if ($mappedAttrs == '') {
            $sel_val = get_locale();
            if (strlen($sel_val) > 0) {
              $sel_val = explode('_', $sel_val)[0];
            }
            $mappedAttrs = [
              'id' => 'ID',
              'title' => 'post_title',
              'description' => 'post_excerpt',
              'price' => '_regular_price',
              'sale_price' => '_sale_price',
              'gtin' => '',
              'mpn' => '',
              'tax' => '',
              'content_language' => $sel_val,
              'availability' => '_stock_status',
              'condition' => 'new',
            ];
            update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));
          }
        }

        $productFilter = isset($_POST['productVal']) && $_POST['productVal'] != '' ? explode(',', sanitize_text_field($_POST['productVal'])) : '';
        $conditionFilter = isset($_POST['conditionVal']) && $_POST['conditionVal'] != '' ? explode(',', sanitize_text_field($_POST['conditionVal'])) : '';
        $valueFilter = isset($_POST['valueVal']) && $_POST['valueVal'] != '' ? explode(',', sanitize_text_field($_POST['valueVal'])) : '';
        $filters = array();
        if (!empty($productFilter)) {
          foreach ($productFilter as $key => $val) {
            $filters[$key]['attr'] = sanitize_text_field($val);
            $filters[$key]['condition'] = sanitize_text_field($conditionFilter[$key]);
            $filters[$key]['value'] = sanitize_text_field($valueFilter[$key]);
          }
        }
        $profile_data = array(
          'feed_name' => esc_sql(sanitize_text_field($_POST['feedName'])),
          'channel_ids' => esc_sql($channel_ids),
          'auto_sync_interval' => esc_sql(sanitize_text_field($_POST['autoSyncIntvl'])),
          'auto_schedule' => esc_sql(sanitize_text_field($_POST['autoSync'])),
          'created_date' => esc_sql(gmdate('Y-m-d H:i:s', current_time('timestamp'))),
          'status' => strpos($channel_ids, '1') !== false ? esc_sql('Draft') : '',
          'target_country' => esc_sql(sanitize_text_field($_POST['target_country'])),
          'categories' => $categories !== false ? wp_json_encode($categories) : null,
          'attributes' => $mappedAttrs !== false ? wp_json_encode($mappedAttrs) : $mappedAttrs,
          'filters' => wp_json_encode($filters),
          'tiktok_catalog_id' => esc_sql($tiktok_catalog_id),
          'tiktok_status' => strpos($channel_ids, '3') !== false ? esc_sql('Draft') : '',
          'is_mapping_update' => $_POST['product_selection'] == 'all_product' || $_POST['product_selection'] == 'filter_product' ? true : false,
          'is_process_start' => false,
          'is_auto_sync_start' => false,
          'product_sync_batch_size' => esc_sql(100),
        );
        $TVC_Admin_DB_Helper->tvc_add_row("ee_product_feed", $profile_data, array("%s", "%s", "%s", "%d", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%d", "%d", "%d", "%s"));
        $result = $TVC_Admin_DB_Helper->tvc_get_last_row("ee_product_feed", array("id"));
        $profile_data = array("profile_title" => esc_sql("Default"), "g_attribute_mapping" => wp_json_encode($mappedAttrs), "update_date" => gmdate('Y-m-d'));
        if ($TVC_Admin_DB_Helper->tvc_row_count("ee_product_sync_profile") == 0) {
          $TVC_Admin_DB_Helper->tvc_add_row("ee_product_sync_profile", $profile_data, array("%s", "%s", "%s"));
        } else {
          $TVC_Admin_DB_Helper->tvc_update_row("ee_product_sync_profile", $profile_data, array("id" => 1));
        }
        switch ($_POST['product_selection']) {
          case 'all_product':
          case 'filter_product':
            $feed_data_api = array(
              "store_id" => $google_detail['setting']->store_id,
              "store_feed_id" => $result['id'],
              "map_categories" => $categories !== false ? wp_json_encode($categories) : null,
              "map_attributes" => wp_json_encode($mappedAttrs),
              "filter" => wp_json_encode($filters),
              "include" => '',
              "exclude" => '',
              "channel_ids" => esc_sql($channel_ids),
              "interval" => esc_sql(sanitize_text_field($_POST['autoSyncIntvl'])),
              "tiktok_catalog_id" => esc_sql($tiktok_catalog_id),
            );
            $CustomApi = new CustomApi();
            $CustomApi->ee_create_product_feed($feed_data_api);
            /********Manual Product sync Start ******************/
            as_unschedule_all_actions('init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $result['id']));
            if (!class_exists('TVCProductSyncHelper')) {
              include(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php');
            }
            $TVCProductSyncHelper = new TVCProductSyncHelper();
            $isSyncComplete = $TVCProductSyncHelper->manualProductSync($result['id']);
            if (isset($isSyncComplete['status']) && $isSyncComplete['status'] === 'success') {
              $last_sync_date = gmdate('Y-m-d H:i:s', current_time('timestamp'));
              $next_schedule_date = NULL;
              if ($_POST['autoSync'] == 1) {
                $autoSyncIntvl = isset($_POST['autoSyncIntvl']) ? absint($_POST['autoSyncIntvl']) : 0;
                $next_schedule_date = gmdate('Y-m-d H:i:s', strtotime('+' . $autoSyncIntvl . 'day', current_time('timestamp')));
                $time_space = strtotime($autoSyncIntvl . " days", 0);
                $timestamp = strtotime($autoSyncIntvl . " days");
                $TVC_Admin_Helper->plugin_log("recurring cron set", 'product_sync'); // Add logs 
                as_schedule_recurring_action(esc_attr($timestamp), esc_attr($time_space), 'init_feed_wise_product_sync_process_scheduler_ee', array("feedId" => $result['id']), "product_sync");
              }

              $feed_data = array(
                "product_sync_alert" => NULL,
                "is_process_start" => false,
                "is_auto_sync_start" => false,
                "last_sync_date" => esc_sql($last_sync_date),
                "next_schedule_date" => $next_schedule_date,
              );
              $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $result['id']));
            } else {
              $feed_data = array(
                "product_sync_alert" => $isSyncComplete['message'],
                "is_process_start" => false,
                "is_auto_sync_start" => false,
                "is_mapping_update" => false,
              );
              $TVC_Admin_Helper->plugin_log("error", 'product_sync'); // Add logs
              $TVC_Admin_DB_Helper->tvc_update_row("ee_product_feed", $feed_data, array("id" => $result['id']));
            }

            /********Manual Product sync End ******************/
            echo wp_json_encode($result);
            break;
          case 'specific_product':
            echo wp_json_encode($result);
            break;
        }
      } else {
        echo wp_json_encode(array("error" => true, "message" => esc_html__("Admin security nonce is not verified.", "enhanced-e-commerce-for-woocommerce-store")));
      }
      exit;
    }
  }
// End of TVC_Ajax_File_Class
endif;
$tvcajax_file_class = new TVC_Ajax_File();
