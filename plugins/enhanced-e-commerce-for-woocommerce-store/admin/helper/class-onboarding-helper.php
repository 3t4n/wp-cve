<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * Woo Order Reports
 */

if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('Conversios_Onboarding_Helper')):
  class Conversios_Onboarding_Helper{
    protected $apiDomain;
    protected $token;
    public function __construct(){
      $this->req_int();
      //analytics
      add_action('wp_ajax_get_analytics_account_list', array($this,'get_analytics_account_list') );
      add_action('wp_ajax_get_analytics_web_properties', array($this,'get_analytics_web_properties') );
      add_action('wp_ajax_save_analytics_data', array($this,'save_analytics_data') );
      //googl_ads
      add_action('wp_ajax_list_googl_ads_account', array($this,'list_googl_ads_account') );
      add_action('wp_ajax_create_google_ads_account', array($this,'create_google_ads_account') );      
      add_action('wp_ajax_link_analytic_to_ads_account', array($this,'link_analytic_to_ads_account') );
      add_action('wp_ajax_get_conversion_list', array($this,'get_conversion_list') );
      
      //google_merchant
      add_action('wp_ajax_list_google_merchant_account', array($this,'list_google_merchant_account') );
      add_action('wp_ajax_create_google_merchant_center_account', array($this,'create_google_merchant_center_account') );
      add_action('wp_ajax_save_merchant_data', array($this,'save_merchant_data') );
      add_action('wp_ajax_link_google_ads_to_merchant_center', array($this,'link_google_ads_to_merchant_center') );

      //get subscription details
      add_action('wp_ajax_get_subscription_details', array($this,'get_subscription_details') );
      add_action('wp_ajax_update_setup_time_to_subscription', array($this,'update_setup_time_to_subscription') );

      add_action('admin_init',array($this,'add_schedule_ut'));
      add_action('ee_ut_cron',array($this,'ee_ut_crons'));
    }

    public function add_schedule_ut(){
      $options_val = get_option('ee_ut');
       if(!empty($options_val)){
          if (false === as_next_scheduled_action( 'ee_ut_cron' ) ) {
            // 86400
             as_schedule_recurring_action(time(), 259200, 'ee_ut_cron');
          }
      }
    }

    public function ee_ut_crons() {
        $google_detail = unserialize(get_option('ee_api_data'));
        if(isset($google_detail['setting']->refresh_token)){
            $refresh_token = sanitize_text_field(base64_decode($google_detail['setting']->refresh_token));
        }
        if((isset($google_detail['setting']->access_token))){         
           $access_token = sanitize_text_field(base64_decode($google_detail['setting']->access_token));
        }
        $api_obj = new Conversios_Onboarding_ApiCall($access_token,$refresh_token);
        echo wp_json_encode($api_obj->createUserTracking());
    }


    public function req_int(){
      if (!class_exists('CustomApi.php')) {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
      }
    }
    protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
      // only return results when the user is an admin with manage options
      if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
        return true;
      } else {
        return false;
      }
    }
    
    /**
     * Ajax code for get analytics web properties.
     * @since    4.0.2
     */
    public function get_analytics_web_properties(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $form_data = array(
          "type" => isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '',
          "account_id" => isset($_POST['account_id']) ? sanitize_text_field($_POST['account_id']) : ''
        );
        echo wp_json_encode($api_obj->getAnalyticsWebProperties($form_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }

    /**
     * Ajax code for get analytics account list.
     * @since    4.0.2
     */
    public function get_analytics_account_list(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $from_data = array("page" => isset($_POST['page']) ? sanitize_text_field($_POST['page']) : '');
        echo wp_json_encode($api_obj->getAnalyticsAccountList($from_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }


    /**
     * Ajax code for save analytics data.
     * @since    4.0.2
     */
    public function save_analytics_data(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));  
        $form_data = array(
          'subscription_id' => sanitize_text_field((isset($_POST['subscription_id']))?$_POST['subscription_id'] : ''),
          'tracking_option' => sanitize_text_field((isset($_POST['tracking_option']))?$_POST['tracking_option'] : ''),
          'web_measurement_id' => sanitize_text_field((isset($_POST['web_measurement_id']))?$_POST['web_measurement_id'] : ''),
          'ga4_account_id' => sanitize_text_field((isset($_POST['ga4_account_id']))?$_POST['ga4_account_id'] : ''),
          'web_property_id' => sanitize_text_field((isset($_POST['web_property_id'])) ? $_POST['web_property_id'] : ''),
          'ua_account_id' => sanitize_text_field((isset($_POST['ua_account_id'])) ? $_POST['ua_account_id'] : ''),
          'enhanced_e_commerce_tracking' => sanitize_text_field((isset($_POST['enhanced_e_commerce_tracking']) && $_POST['enhanced_e_commerce_tracking'] == 'true') ? 1 : 0),
          'user_time_tracking' => sanitize_text_field((isset($_POST['user_time_tracking']) && $_POST['user_time_tracking']=='true')?1:0),
          'add_gtag_snippet' => sanitize_text_field((isset($_POST['add_gtag_snippet']) && $_POST['add_gtag_snippet'] == 'true')? 1:0),
          'client_id_tracking' => sanitize_text_field((isset($_POST['client_id_tracking']) && $_POST['client_id_tracking']=='true')?1:0),
          'exception_tracking' => sanitize_text_field((isset($_POST['exception_tracking']) && $_POST['exception_tracking']=='true')?1:0),
          'enhanced_link_attribution_tracking' => sanitize_text_field((isset($_POST['enhanced_link_attribution_tracking']) && $_POST['enhanced_link_attribution_tracking'] == 'true')? 1 : 0),
          'google_ads_id' => sanitize_text_field((isset($_POST['google_ads_id']))? $_POST['google_ads_id'] : ''),
          'remarketing_tags' => sanitize_text_field((isset($_POST['remarketing_tags']) && $_POST['remarketing_tags'] == 'true') ? 1 : 0),
          'dynamic_remarketing_tags' => sanitize_text_field((isset($_POST['dynamic_remarketing_tags']) && $_POST['dynamic_remarketing_tags'] == 'true') ? 1 : 0),
          'google_ads_conversion_tracking' => sanitize_text_field((isset($_POST['google_ads_conversion_tracking']) && $_POST['google_ads_conversion_tracking'] == 'true') ? 1 : 0),
          'link_google_analytics_with_google_ads' => sanitize_text_field((isset($_POST['link_google_analytics_with_google_ads']) && $_POST['link_google_analytics_with_google_ads'] == 'true') ? 1 : 0)
        );
        echo wp_json_encode($api_obj->saveSubscriptionsData($form_data));

        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }

    /**
     * Ajax code for list googl ads account.
     * @since    4.0.2
     */
    public function list_googl_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $customApiObj = new CustomApi();
        $google_detail = $customApiObj->getGoogleAnalyticDetail($tvc_data->subscription_id);
        $access_token = isset($google_detail->data->access_token) ? base64_encode($google_detail->data->access_token) : '';
        $refresh_token = isset($google_detail->data->refresh_token) ? base64_encode($google_detail->data->refresh_token) : '';
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($access_token), sanitize_text_field($refresh_token));
        echo wp_json_encode($api_obj->getGoogleAdsAccountList());
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    /**
     * Ajax code for create google ads account.
     * @since    4.0.2
     */
    public function create_google_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $form_data = [
          'tvc_data' => $data,
        ];
        echo wp_json_encode($api_obj->createGoogleAdsAccount($form_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }    

    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function link_analytic_to_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $postType = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        if ($postType == "UA") {
          $form_data = [
            'type' => isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '',
            'ads_customer_id' => isset($_POST['ads_customer_id']) ? sanitize_text_field($_POST['ads_customer_id']) : '', 
            'analytics_id' => isset($_POST['analytics_id']) ? sanitize_text_field($_POST['analytics_id']) : '', 
            'web_property_id' => isset($_POST['web_property_id']) ? sanitize_text_field($_POST['web_property_id']) : '', 
            'profile_id' => isset($_POST['profile_id']) ? sanitize_text_field($_POST['profile_id']) : '',
          ];
        } else {
          $form_data = [
            'type' => isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '',
            'ads_customer_id' => isset($_POST['ads_customer_id']) ? sanitize_text_field($_POST['ads_customer_id']):'', 
            'analytics_id' => '', 
            'web_property_id' => isset($_POST['web_property_id']) ? sanitize_text_field($_POST['web_property_id']) : '', 
            'profile_id' => '', 
            'web_property' => isset($_POST['web_property'])? sanitize_text_field($_POST['web_property']) : '',
          ];
        }
        echo wp_json_encode($api_obj->linkAnalyticToAdsAccount($form_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }

    /**
     * Ajax code for list google merchant account.
     * @since    4.0.2
     */
    public function list_google_merchant_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $customApiObj = new CustomApi();
        $google_detail = $customApiObj->getGoogleAnalyticDetail($tvc_data->subscription_id);
        $access_token = isset($google_detail->data->access_token) ? base64_encode($google_detail->data->access_token) : '';
        $refresh_token = isset($google_detail->data->refresh_token) ? base64_encode($google_detail->data->refresh_token) : '';
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($access_token), sanitize_text_field($refresh_token));
        echo wp_json_encode($api_obj->listMerchantCenterAccount());
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function create_google_merchant_center_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $customApiObj = new CustomApi();
        $google_detail = $customApiObj->getGoogleAnalyticDetail($tvc_data->subscription_id);
        $access_token = isset($google_detail->data->access_token) ? base64_encode($google_detail->data->access_token) : '';
        $refresh_token = isset($google_detail->data->refresh_token) ? base64_encode($google_detail->data->refresh_token) : '';
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($access_token), sanitize_text_field($refresh_token));
        $from_data = array("store_name" => isset($_POST['store_name']) ? sanitize_text_field($_POST['store_name']) : '', 
                      "website_url" => isset($_POST['website_url'])? sanitize_text_field($_POST['website_url']) : '', 
                      "customer_id" => isset($_POST['customer_id']) ? sanitize_text_field($_POST['customer_id']) : '', 
                      "adult_content" => isset($_POST['adult_content']) ? sanitize_text_field($_POST['adult_content']) : '', 
                      "country" => isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '', 
                      "email_address" => isset($_POST['email_address']) ? sanitize_text_field($_POST['email_address']) : ''
                      ) ;
        echo wp_json_encode($api_obj->createMerchantAccount($from_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }

    /**
     * Ajax code for save merchant data.
     * @since    4.0.2
     */
    public function save_merchant_data(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));

        $merchant_id  = isset($_POST['merchant_id']) ? sanitize_text_field($_POST['merchant_id']) : '';
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : '';
        $google_merchant_center_id = isset($_POST['google_merchant_center_id']) ? sanitize_text_field($_POST['google_merchant_center_id']) : '';
        $website_url = isset($_POST['website_url']) ? sanitize_text_field($_POST['website_url']) : '';
        $customer_id = isset($_POST['customer_id']) ? sanitize_text_field($_POST['customer_id']) : '';

        $save_data = array( "merchant_id" => $merchant_id,
                            "subscription_id" => $subscription_id,
                            "google_merchant_center_id" => $google_merchant_center_id,
                            "website_url" => $website_url,
                            "customer_id" => $customer_id,
                          );
        $result_merchant = $api_obj->saveMechantData($save_data);
        $result_linkAd = '';
        $adwords_id = isset($_POST['adwords_id']) ? sanitize_text_field($_POST['adwords_id']) : '';
        if($adwords_id != '') {
          $data = isset($_POST['tvc_data']) ? sanitize_text_field($_POST['tvc_data']) : "";
          $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
          $from_data = array( "merchant_id" => $merchant_id,
                              "account_id" => isset($_POST['account_id']) ? sanitize_text_field($_POST['account_id']) : '',
                              "adwords_id" => $adwords_id,
                              "subscription_id" => $subscription_id,
                            );
          $result_linkAd = $api_obj->linkGoogleAdsToMerchantCenter($from_data);
        }
        echo wp_json_encode(array('result_merchant' => $result_merchant, 'result_linkAd' => $result_linkAd ));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function get_conversion_list(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        unset($_POST['tvc_data']);
        unset($_POST['conversios_onboarding_nonce']);
        $form_data = [];
        foreach ($_POST as $key => $value) {
          $form_data[$key] = sanitize_text_field($value);
        }

        echo wp_json_encode($api_obj->getConversionList($form_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    
    /**
     * Ajax code for link google ads to merchant center.
     * @since    4.0.2
     */
    public function link_google_ads_to_merchant_center(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $merchant_id = isset($_POST['merchant_id']) ?  sanitize_text_field($_POST['merchant_id']) : '';
        $account_id = isset($_POST['account_id']) ? sanitize_text_field($_POST['account_id']) : '';
        $adwords_id = isset($_POST['adwords_id']) ? sanitize_text_field($_POST['adwords_id']) : '';
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : '';
        $from_data = array( "merchant_id" => $merchant_id,
                              "account_id" => $account_id,
                              "adwords_id" => $adwords_id,
                              "subscription_id" => $subscription_id,
                            );
        echo wp_json_encode($api_obj->linkGoogleAdsToMerchantCenter($from_data));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    /**
     * Ajax code for link google ads to merchant center.
     * @since    4.0.2
     */
    public function get_subscription_details(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : '';
        echo wp_json_encode($api_obj->getSubscriptionDetails($tvc_data, $subscription_id ));
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }
    
    /**
     * Ajax code for update setup time to subscription.
     * @since    4.0.2
     */
    public function update_setup_time_to_subscription(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?sanitize_text_field($_POST['conversios_onboarding_nonce']):"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $api_obj = new Conversios_Onboarding_ApiCall(sanitize_text_field($tvc_data->access_token), sanitize_text_field($tvc_data->refresh_token));
        $data_value = [];
        foreach ($_POST as $key => $value) {
          $data_value[$key] = sanitize_text_field($value);
        }
        $subscription_id = isset($_POST['subscription_id']) ? sanitize_text_field($_POST['subscription_id']) : '';

        $return_url = $this->save_wp_setting_from_subscription_api($api_obj, $tvc_data, $subscription_id , $data_value);

        $form_data = array ("subscription_id" => $subscription_id);
        $return_rs = $api_obj->updateSetupTimeToSubscription($form_data);
        $return_rs->return_url = $return_url;
        echo wp_json_encode($return_rs);
        wp_die();
      }else{
        echo esc_html__("Admin security nonce is not verified.","enhanced-e-commerce-for-woocommerce-store");
      }
    }

    /**
     * save wp setting from subscription api
     * @since    4.0.2
     */
    public function save_wp_setting_from_subscription_api($api_obj, $tvc_data, $subscription_id, $data){ 
      $old_setting = unserialize(get_option('ee_options')); 
      $TVC_Admin_Helper = new TVC_Admin_Helper(); 
      $google_detail = $api_obj->getSubscriptionDetails($tvc_data, $subscription_id);
      /**
       * active licence key while come from server page
       */
      $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
      if(isset($ee_additional_data['temp_active_licence_key']) && $ee_additional_data['temp_active_licence_key'] != ""){
        $licence_key = $ee_additional_data['temp_active_licence_key'];
        $subscription_id = isset($_GET['subscription_id']) ? sanitize_text_field($_GET['subscription_id']) : '';
        $TVC_Admin_Helper->active_licence($licence_key,$subscription_id);
        unset($ee_additional_data['temp_active_licence_key']);
        $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
      }
      if(property_exists($google_detail,"error") && $google_detail->error == false){
        $googleDetail = $google_detail->data;
        /**
         * for site verifecation
         */
        /*if(isset($googleDetail->google_merchant_center_id) && sanitize_text_field($googleDetail->google_merchant_center_id)){
          $this->site_verification_and_domain_claim($googleDetail);
        }*/

        $settings['subscription_id'] = sanitize_text_field($googleDetail->id);
        $settings['ga_eeT'] = (isset($googleDetail->enhanced_e_commerce_tracking) && sanitize_text_field($googleDetail->enhanced_e_commerce_tracking) == "1") ? "on" : "";
        
        $settings['ga_ST'] = (isset($googleDetail->add_gtag_snippet) && sanitize_text_field($googleDetail->add_gtag_snippet) == "1") ? "on" : "";           
        $settings['gm_id'] = sanitize_text_field($googleDetail->measurement_id);
        $settings['ga_id'] = sanitize_text_field($googleDetail->property_id);
        $settings['google_ads_id'] = sanitize_text_field($googleDetail->google_ads_id);
        $settings['google_merchant_id'] = sanitize_text_field($googleDetail->google_merchant_center_id);
        $settings['tracking_option'] = sanitize_text_field($googleDetail->tracking_option);
        $settings['ga_gUser'] = 'on';
        $settings['ga_Impr'] = 6;
        $settings['ga_IPA'] = 'on';
        //$settings['ga_OPTOUT'] = 'on';
        $settings['ga_PrivacyPolicy'] = 'on';
        $settings['google-analytic'] = '';        
        $settings['ga4_api_secret'] = isset($old_setting['ga4_api_secret'])?$old_setting['ga4_api_secret']:"";
        $settings['ga_CG'] = isset($old_setting['ga_CG'])?$old_setting['ga_CG']:"";
        $settings['ga_optimize_id'] = isset($old_setting['ga_optimize_id'])?$old_setting['ga_optimize_id']:"";
        
        $tracking_integration = array("tracking_method", "tvc_product_list_data_collection_method", "tvc_product_detail_data_collection_method", "tvc_checkout_data_collection_method", "tvc_thankyou_data_collection_method", "tvc_product_detail_addtocart_selector", "tvc_product_detail_addtocart_selector_type", "tvc_product_detail_addtocart_selector_val", "tvc_checkout_step_2_selector", "tvc_checkout_step_2_selector_type", "tvc_checkout_step_2_selector_val", "tvc_checkout_step_3_selector", "tvc_checkout_step_3_selector_type", "tvc_checkout_step_3_selector_val");
        foreach($tracking_integration as $val){
         $settings[$val] = isset($old_setting[$val])?sanitize_text_field($old_setting[$val]):"";
        }

         //remove old conversion label if google_ads_id changed
         $google_ads_id_old = isset($old_setting['google_ads_id'])?$old_setting['google_ads_id']:"";
        if($google_ads_id_old != $settings['google_ads_id']){
          update_option( 'ee_conversio_send_to', null );
        }
        

        $ga_ec = (isset($data["ga_ec"]) && $data["ga_ec"] == "1")?1:0;
        update_option('ga_EC', sanitize_text_field($ga_ec) );
        
        //onboarding settings
        $setting_integration = array("tracking_method", "want_to_use_your_gtm", "use_your_gtm_id", "fb_pixel_id", "microsoft_ads_pixel_id", "twitter_ads_pixel_id", "pinterest_ads_pixel_id", "snapchat_ads_pixel_id", "tiKtok_ads_pixel_id", "fb_conversion_api_token");
        foreach($setting_integration as $val){
          $settings[$val] = isset($data[$val])?sanitize_text_field($data[$val]):"";
        }

        $settings['want_to_use_your_gtm'] = isset($old_setting["want_to_use_your_gtm"])?$old_setting["want_to_use_your_gtm"]:"0";

        //update option in wordpress local database
        update_option('google_ads_conversion_tracking', $googleDetail->google_ads_conversion_tracking);
        update_option('ads_tracking_id', $googleDetail->google_ads_id);
        update_option('ads_ert', $googleDetail->remarketing_tags);
        update_option('ads_edrt', $googleDetail->dynamic_remarketing_tags);
        
        $TVC_Admin_Helper->save_ee_options_settings($settings);
        $TVC_Admin_Helper->update_app_status();
        /**
         * for save conversion send to in WP DB
         */      
        /*
         * function call for save API data in WP DB
         */
        
        $TVC_Admin_Helper->set_update_api_to_db($googleDetail);  
               
        /**
         * function call for save remarketing snippets in WP DB
         */
        $TVC_Admin_Helper->update_remarketing_snippets();
        
        /*if($googleDetail->plan_id != 1 && sanitize_text_field($googleDetail->google_ads_conversion_tracking) == 1){
          //$TVC_Admin_Helper->update_conversion_send_to();
        }*/
        /**
         * save gmail and view ID in WP DB
         */
        if(property_exists($tvc_data,"g_mail") && sanitize_email($tvc_data->g_mail)){
          update_option('ee_customer_gmail', $tvc_data->g_mail);
        }     
        //is not work for existing user && $ee_additional_data['con_created_at'] != "" 
        if(isset($ee_additional_data['con_created_at'])){
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();         
          $ee_additional_data['con_updated_at'] = gmdate('Y-m-d');  
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        }else{
          $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
          $ee_additional_data['con_created_at'] = gmdate('Y-m-d');
          $ee_additional_data['con_updated_at'] = gmdate('Y-m-d');  
          $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        }

        $return_url = "admin.php?page=conversios-google-shopping-feed&tab=gaa_config_page";
        if(isset($googleDetail->google_merchant_center_id) || isset($googleDetail->google_ads_id) ){
          if( sanitize_text_field($googleDetail->google_merchant_center_id) != "" && sanitize_text_field($googleDetail->google_ads_id) != ""){      
            $return_url = esc_url("admin.php?page=conversios-google-shopping-feed&welcome_msg=true");            
          }else{
            $return_url = esc_url("admin.php?page=conversios-google-shopping-feed&tab=gaa_config_page&welcome_msg=true");
          }          
        }
        return $return_url;
      }
    }
    /**
     * site verification and_domain claim code
     * @since    4.0.2
     */
    public function site_verification_and_domain_claim($googleDetail){
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
      $customApiObj = new CustomApi();
      $postData = [
          'merchant_id' => sanitize_text_field($googleDetail->merchant_id),          
          'website_url' => esc_url(get_site_url()),
          'subscription_id' => sanitize_text_field($googleDetail->id),
          'account_id' => sanitize_text_field($googleDetail->google_merchant_center_id)
      ];
      //is site verified
      if ($googleDetail->is_site_verified == '0') {
        $postData['method']="file";
        $siteVerificationToken = $customApiObj->siteVerificationToken($postData);
        if (isset($siteVerificationToken->error) && !empty($siteVerificationToken->errors)) {
            goto call_method_tag;
        } else {
          $myFile =ABSPATH.$siteVerificationToken->data->token; 
          if (!file_exists($myFile)) {
              $fh = fopen($myFile, 'w+');
              chmod($myFile,0777);
              $stringData = "google-site-verification: ".$siteVerificationToken->data->token;
              fwrite($fh, $stringData);
              fclose($fh);
          }
          $postData['method']="file";
          $siteVerification = $customApiObj->siteVerification($postData);
          if (isset($siteVerification->error) && !empty($siteVerification->errors)) {
            call_method_tag:
            //methd using tag
            $postData['method']="meta";
            $siteVerificationToken_tag = $customApiObj->siteVerificationToken($postData);
            if(isset($siteVerificationToken_tag->data->token) && $siteVerificationToken_tag->data->token){
              $ee_additional_data["add_site_varification_tag"]=1;
              $ee_additional_data["site_varification_tag_val"]=base64_encode($siteVerificationToken_tag->data->token);
              $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
              sleep(1);
              $siteVerification_tag = $customApiObj->siteVerification($postData);
              if(isset($siteVerification_tag->error) && !empty($siteVerification_tag->errors)){
              }else{
                  $googleDetail->is_site_verified = '1';
              }
            }
          } else {
              $googleDetail->is_site_verified = '1';
          }
        }
      }
      //is domain claim
      if ($googleDetail->is_domain_claim == '0') {
          $claimWebsite = $customApiObj->claimWebsite($postData);
          if (isset($claimWebsite->error) && !empty($claimWebsite->errors)) {    
          } else {
              $googleDetail->is_domain_claim = '1';
          }
      }

      /**
       * function call for save API data in WP DB
       */
      $TVC_Admin_Helper->set_update_api_to_db($googleDetail);
    }   
  }
endif; // class_exists
new Conversios_Onboarding_Helper();

if(!class_exists('Conversios_Onboarding_ApiCall') ){
  class Conversios_Onboarding_ApiCall {
    protected $apiDomain;
    protected $token;
    protected $merchantId;
    protected $access_token;
    protected $refresh_token;
    public function __construct($access_token, $refresh_token) {
      $merchantInfo = json_decode(file_get_contents(ENHANCAD_PLUGIN_DIR.'includes/setup/json/merchant-info.json'), true);
      $this->refresh_token = $refresh_token;
      $this->access_token = base64_encode( $this->generateAccessToken( base64_decode($access_token), base64_decode($this->refresh_token) ) );
      $this->apiDomain = TVC_API_CALL_URL;
      $this->token = 'MTIzNA==';
      $this->merchantId = sanitize_text_field($merchantInfo['merchantId']);
    }
    public function tc_wp_remot_call_post($url, $args){
      try {
        if(!empty($args)){    
          // Send remote request
          $args['timeout']= "1000";
          $request = wp_remote_post($url, $args);

          // Retrieve information
          $response_code = wp_remote_retrieve_response_code($request);

          $response_message = wp_remote_retrieve_response_message($request);
          $response_body = json_decode(wp_remote_retrieve_body($request));

          if ((isset($response_body->error) && $response_body->error == '')) {
            return new WP_REST_Response($response_body->data);
          } else {
              return new WP_Error($response_code, $response_message, $response_body);
          }
        }
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }
    
    public function getSubscriptionDetails($tvc_data, $subscription_id){
      try{
        $tvc_data = (object)$tvc_data;
        $access_token = sanitize_text_field(base64_decode($this->access_token));
        $url = $this->apiDomain . '/customer-subscriptions/subscription-detail';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json", "AccessToken: $access_token");
        $data = [
          'subscription_id' => sanitize_text_field($subscription_id),//$this->subscription_id,
          'domain' => sanitize_text_field($tvc_data->user_domain)
        ];
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);

        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      }catch(Exception $e){
        return $e->getMessage();
      }
    }

    public function getAnalyticsWebProperties($postData) {
      try {
        $url = $this->apiDomain . '/google-analytics/wep-details/account-id';
        $access_token = sanitize_text_field(base64_decode($this->access_token)); 
        $data = [
          'type' => sanitize_text_field($postData['type']),
          'account_id' => sanitize_text_field($postData['account_id'])
        ];
        $args = array(
          'timeout' => 300,
          'headers' => array(
            'Authorization' => "Bearer MTIzNA==",
            'Content-Type' => 'application/json',
            'AccessToken' => $access_token
          ),
          'body' => wp_json_encode($data)
        );
        $request = wp_remote_post(esc_url($url), $args);
        
        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response = json_decode(wp_remote_retrieve_body($request));
        $return = new \stdClass();
        if (isset($response->error) && $response->error == '') {
          $return->status = $response_code;
          $return->data = $response->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = ($response->data)?$response->data:"";
          $return->status = $response_code;
          $return->errors = wp_json_encode($response->errors);
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function getAnalyticsAccountList($postData) {
      try {
        $url = $this->apiDomain . '/google-analytics/ga-account-list';
        
        $access_token = sanitize_text_field(base64_decode($this->access_token));
        $max_results = 50; 
        $page = (isset($postData['page']) && sanitize_text_field($postData['page']) >1)?sanitize_text_field($postData['page']):"1";
        if($page > 1){
          //set index
          $page = (($page-1) * $max_results)+1;
        }       
        $data = [
          'page'=>sanitize_text_field($page),
          'max_results'=>sanitize_text_field($max_results)
        ];
        $args = array(
          'timeout' => 300,
          'headers' => array(
            'Authorization' => "Bearer MTIzNA==",
            'Content-Type' => 'application/json',
            'AccessToken' => $access_token
          ),
          'body' => wp_json_encode($data)
        );
        $request = wp_remote_post(esc_url($url), $args);
        
        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response = json_decode(wp_remote_retrieve_body($request));
        $return = new \stdClass();
        if (isset($response->error) && $response->error == '') {
          $return->status = $response_code;
          $return->data = $response->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = isset($response->data)?$response->data:"";
          $return->status = $response_code;
          $return->errors = wp_json_encode($response->errors);
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function getGoogleAdsAccountList() {
      try {
        if($this->refresh_token != ""){
          $url = $this->apiDomain . '/adwords/list';
          $refresh_token = sanitize_text_field(base64_decode($this->refresh_token));
          $args = array(
            'timeout' => 300,
            'headers' => array(
              'Authorization' => "Bearer MTIzNA==",
              'Content-Type' => 'application/json',
              'RefreshToken' => $refresh_token
            ),
            'body' => ""
          );
          $request = wp_remote_post(esc_url($url), $args);
          
          // Retrieve information
          $response_code = wp_remote_retrieve_response_code($request);
          $response_message = wp_remote_retrieve_response_message($request);
          $response = json_decode(wp_remote_retrieve_body($request));
          $return = new \stdClass();
          if (isset($response->error) && $response->error == '') {
            $return->status = $response_code;
            $return->data = $response->data;
            $return->error = false;
            return $return;
          }else{
            $return->error = true;
            //$return->data = $response->data;
            $return->status = $response_code;
            $return->errors = wp_json_encode($response->errors);
            return $return;
          }       
        }else{
          return wp_json_encode(array("error" => true));
        }
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }

    public function listMerchantCenterAccount() {
      try {
        $url = $this->apiDomain . '/gmc/user-merchant-center/list';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = [
          'access_token' => sanitize_text_field(base64_decode($this->access_token)),
        ];
        $args = array(
          'timeout' => 300,
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);
        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          $return->errors = wp_json_encode($result->errors);
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function createGoogleAdsAccount($postData) {
      try {
        $data = isset($_POST['tvc_data'])?sanitize_text_field($_POST['tvc_data']):"";
        $tvc_data = json_decode(str_replace("&quot;", "\"", $data));
        $url = $this->apiDomain . '/adwords/create-ads-account';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = [
          'subscription_id' => sanitize_text_field($tvc_data->subscription_id),
          'email' => sanitize_email($tvc_data->g_mail),
          'currency' => sanitize_text_field($tvc_data->currency_code),
          'time_zone' => sanitize_text_field($tvc_data->timezone_string), //'Asia/Kolkata',
          'domain' => sanitize_text_field($tvc_data->user_domain)
        ];
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);
        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          //admin notice when user created new google ads account.
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $link_title = "Create Performance max campaign now.";
          $content = "Create your first Google Ads performance max campaign using the plugin and get $500 as free credits.";
          $status = "1";
          $link = "admin.php?page=conversios-pmax";
          $created_google_ads_id = $result->data->adwords_id;
          $TVC_Admin_Helper->tvc_add_admin_notice("created_googleads_account", $content, $status, $link_title, $link, $created_google_ads_id,"","6","created_googleads_account");
          return $return;
        }else{
          $return->error = true;
          $return->error = $result->errors;
          $return->errors = wp_json_encode($result->errors);
          //$return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }
    public function createMerchantAccount($postData) {
      try {
        $url = $this->apiDomain . '/gmc/create';
        $header = array(
            "Authorization: Bearer MTIzNA==",
            "Content-Type" => "application/json"
        );
        $data = [
          'merchant_id' => sanitize_text_field($this->merchantId), //'256922349',
          'name' => sanitize_text_field($postData['store_name']),
          'website_url' => esc_url(sanitize_text_field($postData['website_url'])),
          'customer_id' => sanitize_text_field($postData['customer_id']),
          'adult_content' => isset($postData['adult_content']) && sanitize_text_field($postData['adult_content']) == 'true' ? true : false,
          'country' => sanitize_text_field($postData['country']),
          'users' => [
            [
              "email_address" => sanitize_email($postData['email_address']), //"sarjit@pivotdrive.ca"
              "admin" => true
            ]
          ],
          'business_information' => [
            'address' => [
                'country' => sanitize_text_field($postData['country'])
            ]
          ]
        ];
        $args = array(
          'timeout' => 300,
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $args['timeout']= "1000";
        $request = wp_remote_post(esc_url($url), $args);
        
        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response_body = json_decode(wp_remote_retrieve_body($request));
        if ((isset($response_body->error) && $response_body->error == '') || (!isset($response_body->error)) ) {
          //create merchant account admin notices
          $TVC_Admin_Helper = new TVC_Admin_Helper();
          $link_title = "Create Performance max campaign now.";
          $content = "Create your first Google Ads performance max campaign using the plugin and get $500 as free credits.";
          $status = "1";
          $created_merchant_id = $response_body->account->id;
          $link = "admin.php?page=conversios-pmax";
          $TVC_Admin_Helper->tvc_add_admin_notice("created_merchant_account", $content, $status, $link_title, $link, $created_merchant_id,"","7","created_merchant_account");
          return $response_body;
        } else {
          $return = new \stdClass();
          $return->error = true;
          $return->errors = isset($response_code->errors) ? wp_json_encode($response_code->errors) : '';
          //$return->data = $result->data;
          $return->status = $response_code;
          return $return;
          //return new WP_Error($response_code, $response_message, $response_body);
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    
    public function saveSubscriptionsData($postData = array()) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = array(
          'subscription_id' => sanitize_text_field((isset($postData['subscription_id']))?$postData['subscription_id'] : ''),
          'tracking_option' => sanitize_text_field((isset($postData['tracking_option']))?$postData['tracking_option'] : ''),
          'measurement_id' => sanitize_text_field((isset($postData['web_measurement_id']))?$postData['web_measurement_id'] : ''),
          'ga4_analytic_account_id' => sanitize_text_field((isset($postData['ga4_account_id']))?$postData['ga4_account_id'] : ''),
          'property_id' => sanitize_text_field((isset($postData['web_property_id'])) ? $postData['web_property_id'] : ''),
          'ua_analytic_account_id' => sanitize_text_field((isset($postData['ua_account_id'])) ? $postData['ua_account_id'] : ''),
          'enhanced_e_commerce_tracking' => sanitize_text_field((isset($postData['enhanced_e_commerce_tracking']) && $postData['enhanced_e_commerce_tracking'] == 'true') ? 1 : 0),
          'user_time_tracking' => sanitize_text_field((isset($postData['user_time_tracking']) && $postData['user_time_tracking']=='true')?1:0),
          'add_gtag_snippet' => sanitize_text_field((isset($postData['add_gtag_snippet']) && $postData['add_gtag_snippet'] == 'true')? 1:0),
          'client_id_tracking' => sanitize_text_field((isset($postData['client_id_tracking']) && $postData['client_id_tracking']=='true')?1:0),
          'exception_tracking' => sanitize_text_field((isset($postData['exception_tracking']) && $postData['exception_tracking']=='true')?1:0),
          'enhanced_link_attribution_tracking' => sanitize_text_field((isset($postData['enhanced_link_attribution_tracking']) && $postData['enhanced_link_attribution_tracking'] == 'true')? 1 : 0),
          'google_ads_id' => sanitize_text_field((isset($postData['google_ads_id']))? $postData['google_ads_id'] : ''),
          'remarketing_tags' => sanitize_text_field((isset($postData['remarketing_tags']) && $postData['remarketing_tags'] == 'true') ? 1 : 0),
          'dynamic_remarketing_tags' => sanitize_text_field((isset($postData['dynamic_remarketing_tags']) && $postData['dynamic_remarketing_tags'] == 'true') ? 1 : 0),
          'google_ads_conversion_tracking' => sanitize_text_field((isset($postData['google_ads_conversion_tracking']) && $postData['google_ads_conversion_tracking'] == 'true') ? 1 : 0),
          'link_google_analytics_with_google_ads' => sanitize_text_field((isset($postData['link_google_analytics_with_google_ads']) && $postData['link_google_analytics_with_google_ads'] == 'true') ? 1 : 0)
        );        
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );          
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);        
        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          $return->errors = wp_json_encode($result->errors);
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function saveMechantData($postData = array()) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = [
          'merchant_id' => sanitize_text_field(($postData['merchant_id'] == 'NewMerchant') ? $this->merchantId: $postData['merchant_id']),
          'subscription_id' => sanitize_text_field((isset($postData['subscription_id']))?$postData['subscription_id'] : ''),
          'google_merchant_center_id' => sanitize_text_field((isset($postData['google_merchant_center']))? $postData['google_merchant_center'] : ''),
          'website_url' => sanitize_text_field($postData['website_url']),
          'customer_id' => sanitize_text_field($postData['customer_id'])
        ];
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);
        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          $return->errors = wp_json_encode($result->errors);
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function linkAnalyticToAdsAccount($postData) {
      try {
        $url = $this->apiDomain . '/google-analytics/link-ads-to-analytics';
        $access_token = sanitize_text_field(base64_decode($this->access_token));
        $refresh_token = sanitize_text_field(base64_decode($this->refresh_token));
        if ($postData['type'] == "UA") {
          $data = [
            'type' => sanitize_text_field($postData['type']),
            'ads_customer_id' => sanitize_text_field($postData['ads_customer_id']), 
            'analytics_id' => sanitize_text_field($postData['analytics_id']), 
            'web_property_id' => sanitize_text_field($postData['web_property_id']), 
            'profile_id' => sanitize_text_field($postData['profile_id']),
          ];
        } else {
          $data = [
            'type' => sanitize_text_field($postData['type']),
            'ads_customer_id' => sanitize_text_field($postData['ads_customer_id']), 
            'analytics_id' => '', 
            'web_property_id' => sanitize_text_field($postData['web_property_id']), 
            'profile_id' => '', 
            'web_property' => sanitize_text_field($postData['web_property']),
          ];
        }
        
        $args = array(
          'timeout' => 300,
          'headers' => array(
              'Authorization' => "Bearer $this->token",
              'Content-Type' => 'application/json',
              'AccessToken' => $access_token,
              'RefreshToken' => $refresh_token
          ),
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $request = wp_remote_post(esc_url($url), $args);

        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $result = json_decode(wp_remote_retrieve_body($request));
        $return = new \stdClass();
        if($response_code == 200 && isset($result->error) && $result->error == ''){
          $return->status = $response_code;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->errors = $result->errors;
          $return->status = $response_code;
          return $return;
        }
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }
    public function linkGoogleAdsToMerchantCenter($postData) {
      try {
        $url = $this->apiDomain . '/adwords/link-ads-to-merchant-center';
        $access_token = sanitize_text_field(base64_decode($this->access_token));
        $data = [
          'merchant_id' => sanitize_text_field(($postData['merchant_id']) == 'NewMerchant' ?  $this->merchantId: $postData['merchant_id']),
          'account_id' => sanitize_text_field($postData['account_id']),
          'adwords_id' => sanitize_text_field($postData['adwords_id']),
          'subscription_id' => sanitize_text_field($postData['subscription_id'])
        ];
        $args = array(
          'timeout' => 300,
            'headers' => array(
                'Authorization' => "Bearer $this->token",
                'Content-Type' => 'application/json',
                'AccessToken' => $access_token
            ),
            'method' => 'POST',
            'body' => wp_json_encode($data)
        );

        // Send remote request
        $request = wp_remote_post(esc_url($url), $args);
        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $result = json_decode(wp_remote_retrieve_body($request));
        $return = new \stdClass();
        if($response_code == 200){
          $return->status = $response_code;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->errors = $result->errors;
          $return->status = $response_code;
          return $return;
        }
        
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function updateSetupTimeToSubscription($postData) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-setup-time';
        $data = [
          'subscription_id' => sanitize_text_field((isset($postData['subscription_id']))?$postData['subscription_id'] : ''),
          'setup_end_time' => gmdate('Y-m-d H:i:s')
        ];
        $args = array(
            'timeout' => 300,
            'headers' => array(
                'Authorization' => "Bearer $this->token",
                'Content-Type' => 'application/json'
            ),
            'method' => 'POST',
            'body' => wp_json_encode($data)
        );

        // Send remote request
        $request = wp_remote_post(esc_url($url), $args);

        // Retrieve information
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $result = json_decode(wp_remote_retrieve_body($request));
        $return = new \stdClass();
        if($response_code == 200){
          $return->status = $response_code;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          // $return->errors = $result->errors;
          $return->status = $response_code;
          $return->errors = json_decode($result->errors[0]);
          $return->errors = json_decode($result->errors[0]);
          return $return;
        }
        
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function getConversionList($postData) {
      try {
        if(!empty($postData)){
          foreach ($postData as $key => $value) {
            $postData[$key] = sanitize_text_field($value); 
          }
        }
        $url = $this->apiDomain . '/google-ads/conversion-list';
        $header = array(
            "Authorization: Bearer MTIzNA==",
            "Content-Type" => "application/json"
        );
        $args = array(
          'timeout' => 300,
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $request = wp_remote_post(esc_url($url), $args);
        $response_code = wp_remote_retrieve_response_code($request);
        $response_message = wp_remote_retrieve_response_message($request);
        $response = json_decode(wp_remote_retrieve_body($request));

        $return = new \stdClass();
        if ((isset($response->error) && $response->error == '')) {
          $return->status = $response_code;
          $return->data =$response->data;
          $return->error = false;
          if(isset($response->data) && count($response->data) > 0){
            $return->message = esc_html__("Google Ads conversion tracking setting success.","enhanced-e-commerce-for-woocommerce-store");
          }else{
             $response = $this->createConversion($postData); 
            if(isset($response->error) && $response->error == false){         
              $return->error = false;
              $return->message = esc_html__("Google Ads conversion tracking setting success.","enhanced-e-commerce-for-woocommerce-store"); 
            }else{
             $return->error = true;
             $errors = json_decode($response->errors[0]);
             $return->errors = $errors->message;
            }
          }  
          return $return;
        }else{
          $return->error = true;
          if(!empty($response))
          {
            $return->errors = $response->errors[0];
            //$return->data = $result->data;
            $return->status = $response_code;
          }
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function createConversion($postData) {
      try {
        $url = $this->apiDomain . '/google-ads/create-conversion';
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = [
          'customer_id' => sanitize_text_field((isset($postData['customer_id']))?$postData['customer_id'] : ''),
          'name' => "Order Conversion"
        ];
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);
        $return = new \stdClass();
        if($result->status == 200){
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function generateAccessToken($access_token, $refresh_token) {
      $url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=" . $access_token;
      $request =  wp_remote_get(esc_url($url), array('timeout' => 300));
      $response_code = wp_remote_retrieve_response_code($request);

      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      
      if (isset($result->error) && $result->error) {
          $credentials = json_decode(file_get_contents(ENHANCAD_PLUGIN_DIR . 'includes/setup/json/client-secrets.json'), true);
          $url = 'https://www.googleapis.com/oauth2/v4/token';
          $header = array("Content-Type" => "application/json");
          $clientId = $credentials['web']['client_id'];
          $clientSecret = $credentials['web']['client_secret'];
          
          $data = [
              "grant_type" => 'refresh_token',
              "client_id" => sanitize_text_field($clientId),
              'client_secret' => sanitize_text_field($clientSecret),
              'refresh_token' => sanitize_text_field($refresh_token),
          ];
          $args = array(
            'timeout' => 300,
            'headers' =>$header,
            'method' => 'POST',
            'body' => wp_json_encode($data)
          );
          $request = wp_remote_post(esc_url($url), $args);
          // Retrieve information
          $response_code = wp_remote_retrieve_response_code($request);
          $response_message = wp_remote_retrieve_response_message($request);
          $response = json_decode(wp_remote_retrieve_body($request));
          if(isset($response->access_token)){
            $TVC_Admin_Helper = new TVC_Admin_Helper();
            $google_detail = $TVC_Admin_Helper->get_ee_options_data();
            $google_detail["setting"]->access_token = base64_encode(sanitize_text_field($response->access_token));
            $TVC_Admin_Helper->set_ee_options_data($google_detail);
            return $response->access_token; 
          }else{
              //return $access_token;
          }
      } else {
        return $access_token;
      }
    }//generateAccessToken

    public function createUserTracking() {
      try {
        $url = $this->apiDomain . '/usertracking';
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $subscriptionId =  $TVC_Admin_Helper->get_subscriptionId();
        $options_val = get_option('ee_ut');
        $header = array("Authorization: Bearer MTIzNA==", "Content-Type" => "application/json");
        $data = [
          'subscription_id' => sanitize_text_field((isset($subscriptionId))?$subscriptionId : ''),
          'site_url' => esc_url(get_site_url()),
          'ee_ut' => $options_val
        ];
        $args = array(
          'headers' =>$header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url($url), $args);
        $return = new \stdClass();
        if($result->status == 200){
          update_option("ee_ut",'' );
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        }else{
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
  }
}