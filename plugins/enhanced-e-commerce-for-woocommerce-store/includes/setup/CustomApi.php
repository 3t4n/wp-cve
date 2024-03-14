<?php
class CustomApi
{
  private $apiDomain;
  private $token;
  protected $access_token;
  protected $refresh_token;
  public function __construct()
  {
    $this->apiDomain = TVC_API_CALL_URL;
    $this->token = 'MTIzNA==';
  }
  public function get_tvc_access_token()
  {
    if (!empty($this->access_token)) {
      return $this->access_token;
    } else {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $google_detail = $TVC_Admin_Helper->get_ee_options_data();
      if ((isset($google_detail['setting']->access_token))) {
        $this->access_token = sanitize_text_field(base64_decode($google_detail['setting']->access_token));
      }
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
      if (isset($google_detail['setting']->refresh_token)) {
        $this->refresh_token = sanitize_text_field(base64_decode($google_detail['setting']->refresh_token));
      }
      return $this->refresh_token;
    }
  }

  public function tc_wp_remot_call_post($url, $args)
  {
    try {
      if (!empty($args)) {
        // Send remote request
        $args['timeout'] = "1000";
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

  public function is_allow_call_api()
  {
    $ee_options_data = unserialize(get_option('ee_options'));
    if (isset($ee_options_data['subscription_id'])) {
      return true;
    } else {
      return false;
    }
  }

  public function update_app_status($status = 1)
  {
    try {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $subscription_id = sanitize_text_field($TVC_Admin_Helper->get_subscriptionId());
      if ($subscription_id != "") {
        $url = $this->apiDomain . '/customer-subscriptions/update-app-status';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $options = unserialize(get_option('ee_options'));
        $fb_pixel_enable = "0";
        if (isset($options['fb_pixel_id']) && $options['fb_pixel_id'] != "") {
          $fb_pixel_enable = "1";
        }
        $woocomm_version = "0";
        if (is_plugin_active_for_network('woocommerce/woocommerce.php') || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
          global $woocommerce;
          $woocomm_version = $woocommerce->version;
        }
        $store_country = get_option('woocommerce_default_country');
        $store_country = explode(":", $store_country);

        $attributes = unserialize(get_option('ee_prod_mapped_attrs'));
        $categories = unserialize(get_option('ee_prod_mapped_cats'));
        $countAttribute = is_array($attributes) ? count($attributes) : 0;
        $countCategories = is_array($categories) ? count($categories) : 0;

        $postData = array(
          "subscription_id" => $subscription_id,
          "domain" => esc_url(get_site_url()),
          "app_status_data" => array(
            "app_settings" => array(
              "app_status" => sanitize_text_field($status),
              "fb_pixel_enable" => $fb_pixel_enable,
              "app_verstion" => PLUGIN_TVC_VERSION,
              "domain" => esc_url(get_site_url()),
              "product_settings" => unserialize(get_option('ee_options')),
              "attributeMapping" => $countAttribute,
              "categoryMapping" => $countCategories,
              "update_date" => gmdate("Y-m-d")
            ),
            "store" => array(
              "country" => isset($store_country[0]) ? $store_country[0] : "",
              "state" => isset($store_country[1]) ? $store_country[1] : ""
            ),
            "walk_through" => isset($options['walk_through']) ? $options['walk_through'] : "",
            "woocomm_version" => $woocomm_version,
          )
        );
        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        wp_remote_post(esc_url_raw($url), $args);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function app_activity_detail($status)
  {
    try {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $subscription_id = sanitize_text_field($TVC_Admin_Helper->get_subscriptionId());
      if (isset($subscription_id) && $status != "") {
        $url = $this->apiDomain . '/customer-subscriptions/app_activity_detail';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );
        $postData = array(
          "subscription_id" => $subscription_id,
          "domain" => esc_url(get_site_url()),
          "app_status" => sanitize_text_field($status),
          "app_data" => array(
            "app_version" => PLUGIN_TVC_VERSION,
            "app_id" => CONV_APP_ID,
          )
        );
        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function getGoogleAnalyticDetail($subscription_id = null)
  {
    try {

      $url = $this->apiDomain . '/customer-subscriptions/subscription-detail';
      $header = array(
        "Authorization: Bearer " . $this->token,
        "Content-Type" => "application/json"
      );
      $ee_options_data = unserialize(get_option('ee_options'));
      if ($subscription_id == null && isset($ee_options_data['subscription_id'])) {
        $subscription_id = sanitize_text_field($ee_options_data['subscription_id']);
      }
      $data = [
        'subscription_id' => sanitize_text_field($subscription_id),
        'domain' => get_site_url()
      ];
      if ($subscription_id == "") {
        $return = new \stdClass();
        $return->error = true;
        return $return;
      }
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      $return = new \stdClass();
      if (isset($result->status)) {
        if ($result->status == 200) {
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        } else {
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      } else {
        $return->error = true;
        $return->data = 'something went wrong please try again';
        $return->status = '404';
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function updateTrackingOption($postData)
  {
    try {
      $url = $this->apiDomain . '/customer-subscriptions/tracking-options';

      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer $this->token",
          'Content-Type' => 'application/json'
        ),
        'method' => 'PATCH',
        'body' => wp_json_encode($postData)
      );

      // Send remote request
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response_body = json_decode(wp_remote_retrieve_body($request));

      if ((isset($response_body->error) && $response_body->error == '')) {

        return new WP_REST_Response(
          array(
            'status' => $response_code,
            'message' => $response_message,
            'data' => $response_body->data
          )
        );
      } else {
        return new WP_Error($response_code, $response_message, $response_body);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function add_survey_of_deactivate_plugin($postData)
  {
    try {
      $url = $this->apiDomain . "/customersurvey";
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $args = array(
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );
      $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);

      $return = new \stdClass();
      if ($result->status == 200) {
        $return->status = $result->status;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->data = $result->data;
        $return->status = $result->status;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function active_licence_Key($licence_key, $subscription_id)
  {
    try {
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/licence/activation";
      $data = [
        'key' => sanitize_text_field($licence_key),
        'domain' => get_site_url(),
        'subscription_id' => sanitize_text_field($subscription_id)
      ];
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($response->error) && $response->error == '')) {
        //$return->status = $result->status;
        $return->data = $response->data;
        $return->error = false;
        return $return;
      } else {
        if (isset($response->data)) {
          $return->error = false;
          $return->data = $response->data;
          $return->message = $response->message;
        } else {
          $return->error = true;
          $return->data = [];
          if (isset($response->errors->key[0])) {
            $return->message = $response->errors->key[0];
          } else {
            $return->message = esc_html__("Check your entered licese key.", "enhanced-e-commerce-for-woocommerce-store");
          }
        }
        return $return;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function get_remarketing_snippets($customer_id)
  {
    try {
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/google-ads/remarketing-snippets";
      $data = [
        'customer_id' => sanitize_text_field($customer_id)
      ];
      $args = array(
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);

      $return = new \stdClass();
      if ($result->status == 200) {
        $return->status = $result->status;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->data = $result->data;
        $return->status = $result->status;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function get_conversion_list($customer_id)
  {
    try {
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/google-ads/conversion-list";
      $data = [
        'customer_id' => sanitize_text_field($customer_id)
      ];
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );

      // $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      $request = wp_remote_post(esc_url_raw($url), $args);
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {
        $return->status = $response_code;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        //$return->errors = $result->errors;
        //$return->error = $result->data;
        $return->status = $response_code;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function conv_create_gads_conversion($customer_id, $conversionName, $conversionCategory = "")
  {
    try {
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/google-ads/create-conversion";
      $data = [
        'customer_id' => sanitize_text_field($customer_id),
        'name' => sanitize_text_field($conversionName),
        'conversionCategory' => sanitize_text_field($conversionCategory)
      ];
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );

      // $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      $request = wp_remote_post(esc_url_raw($url), $args);
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {
        $return->status = $response_code;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        //$return->errors = $result->errors;
        //$return->error = $result->data;
        $return->status = $response_code;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * @since 4.6.8
   * Get  google analytics reports call using reporting API
   */
  public function get_google_analytics_reports_ga4($postData)
  {
    try {
      $url = $this->apiDomain . "/actionable-dashboard/google-analytics-reports-ga4";
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );
      $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      $return = new \stdClass();
      if (isset($result->status) === TRUE && $result->status == 200) {
        $return->status = $result->status;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->data = isset($result->data) === TRUE ? $result->data : '';
        $return->status = isset($result->status) === TRUE ? $result->status : '';
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  /**
   * @since 4.6.8
   * Get Property ID for GA4 reporting API
   */
  public function analytics_get_ga4_property_id($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/actionable-dashboard/analytics-get-ga4-property-id";
      $postData['access_token'] = $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token());
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      return json_decode(wp_remote_retrieve_body($request));
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function setGmcCategoryMapping($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $url = $this->apiDomain . '/gmc/gmc-category-mapping';

      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer $this->token",
          'Content-Type' => 'application/json'
        ),
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );

      // Send remote request
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response_body = json_decode(wp_remote_retrieve_body($request));

      if ((isset($response_body->error) && $response_body->error == '')) {
        return new WP_REST_Response(
          array(
            'status' => $response_code,
            'message' => $response_message,
            'data' => $response_body->data
          )
        );
      } else {
        return new WP_Error($response_code, $response_message, $response_body);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function setGmcAttributeMapping($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $url = $this->apiDomain . '/gmc/gmc-attribute-mapping';

      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer $this->token",
          'Content-Type' => 'application/json'
        ),
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );

      // Send remote request
      $request = wp_remote_post($url, $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response_body = json_decode(wp_remote_retrieve_body($request));

      if ((isset($response_body->error) && $response_body->error == '')) {

        return new WP_REST_Response(
          array(
            'status' => $response_code,
            'message' => $response_message,
            'data' => $response_body->data
          )
        );
      } else {
        return new WP_Error($response_code, $response_message, $response_body);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function products_sync($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          if (in_array($key, array("merchant_id", "account_id", "subscription_id"))) {
            $postData[$key] = sanitize_text_field($value);
          }
        }
      }
      $url = $this->apiDomain . "/products/batch";
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if (isset($response->error) && $response->error == '') {
        $return->error = false;
        $return->products_sync = count($response->data->entries);
        return $return;
      } else {
        $return->error = true;
        $return->arges =  $args;
        if (isset($response->errors)) {
          foreach ($response->errors as $err) {
            $return->message = $err;
            break;
          }
        }
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function getSyncProductList($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $url = $this->apiDomain . "/products/list";
      $postData["maxResults"] = 50;
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response = json_decode(wp_remote_retrieve_body($request));

      $return = new \stdClass();
      if (isset($response->error) && $response->error == '') {
        $return->status = $response_code;
        $return->error = false;
        $return->data = $response->data;
        $return->message = $response->message;
        return $return;
      } else {
        $return->status = $response_code;
        $return->error = true;
        if (isset($response->errors)) {
          foreach ($response->errors as $err) {
            $return->message = $err;
            break;
          }
        }
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }


  public function getCampaignCurrencySymbol($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $url = $this->apiDomain . '/campaigns/currency-symbol';

      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer $this->token",
          'Content-Type' => 'application/json'
        ),
        'body' => wp_json_encode($postData)
      );

      // Send remote request
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response_body = json_decode(wp_remote_retrieve_body($request));
      if ((isset($response_body->error) && $response_body->error == '')) {

        return new WP_REST_Response(
          array(
            'status' => $response_code,
            'message' => $response_message,
            'data' => $response_body->data
          )
        );
      } else {
        return new WP_Error($response_code, $response_message, $response_body);
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function record_customer_featurereq($postData)
  {
    try {
      $url = $this->apiDomain . '/customerfeedback';
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
        ),
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {
        $return->message = "Your feedback was successfully recoded.";
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->errors = $result->errors;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function record_customer_feedback($postData)
  {
    try {
      $url = $this->apiDomain . '/customerfeedback';
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {
        $return->message = "Your feedback was successfully recoded.";
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->errors = $result->errors;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function check_if_basesixtyfour($data)
  {
    if (base64_encode(base64_decode($data, true)) === $data) {
      return true;
    } else {
      return false;
    }
  }

  public function generateAccessToken($access_token, $refresh_token)
  {

    if ($this->check_if_basesixtyfour($access_token) == true) {
      $access_token = base64_decode($access_token);
    }

    if ($this->check_if_basesixtyfour($refresh_token) == true) {
      $refresh_token = base64_decode($refresh_token);
    }

    $url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=" . $access_token;
    $request =  wp_remote_get(esc_url_raw($url), array('timeout' => 300));
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
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response = json_decode(wp_remote_retrieve_body($request));
      if (isset($response->access_token)) {
        $TVC_Admin_Helper = new TVC_Admin_Helper();
        $google_detail = $TVC_Admin_Helper->get_ee_options_data();
        $google_detail["setting"]->access_token = base64_encode(sanitize_text_field($response->access_token));
        $TVC_Admin_Helper->set_ee_options_data($google_detail);
        return $response->access_token;
      } else {
        //return $access_token;
      }
    } else {
      return $access_token;
    }
  } //generateAccessToken


  public function siteVerificationToken($postData)
  {
    try {
      $url = $this->apiDomain . '/gmc/site-verification-token';
      $data = [
        'merchant_id' => sanitize_text_field($postData['merchant_id']),
        'website' => sanitize_text_field($postData['website_url']),
        'account_id' => sanitize_text_field($postData['account_id']),
        'method' => sanitize_text_field($postData['method'])
      ];

      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
      $return = new \stdClass();
      if ($result->status == 200) {
        $return->status = $result->status;
        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        if (is_array($result->errors)) {
          if (count($result->errors) != count($result->errors, COUNT_RECURSIVE)) {
            $return->errors = implode("&", array_map(function ($a) {
              return implode("~", $a);
            }, $result->errors));
          } else {
            $return->errors = implode(" ", $result->errors);
          }
        } else {
          $return->errors = $result->errors;
        }
        $return->data = $result->data;
        $return->status = $result->status;
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function siteVerification($postData)
  {
    try {
      $url = $this->apiDomain . '/gmc/site-verification';
      $data = [
        'merchant_id' => sanitize_text_field($postData['merchant_id']),
        'website' => esc_url_raw($postData['website_url']),
        'subscription_id' => sanitize_text_field($postData['subscription_id']),
        'account_id' => sanitize_text_field($postData['account_id']),
        'method' => sanitize_text_field($postData['method'])
      ];

      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'method' => 'POST',
        'body' => wp_json_encode($data)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {

        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        if (is_array($result->errors)) {
          if (count($result->errors) != count($result->errors, COUNT_RECURSIVE)) {
            $return->errors = implode("&", array_map(function ($a) {
              return implode("~", $a);
            }, $result->errors));
          } else {
            $return->errors = implode(" ", $result->errors);
          }
        } else {
          $return->errors = $result->errors;
        }
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function claimWebsite($postData)
  {
    try {
      $url = $this->apiDomain . '/gmc/claim-website';
      $data = [
        'merchant_id' => sanitize_text_field($postData['merchant_id']),
        'account_id' => sanitize_text_field($postData['account_id']),
        'website' => esc_url_raw($postData['website_url']),
        'access_token' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token()),
        'subscription_id' => sanitize_text_field($postData['subscription_id']),
      ];
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'body' => wp_json_encode($data)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $result = json_decode(wp_remote_retrieve_body($request));

      $return = new \stdClass();
      if ((isset($result->error) && $result->error == '')) {

        $return->data = $result->data;
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        if (is_array($result->errors)) {
          if (count($result->errors) != count($result->errors, COUNT_RECURSIVE)) {
            $return->errors = implode("&", array_map(function ($a) {
              return implode("~", $a);
            }, $result->errors));
          } else {
            $return->errors = implode(" ", $result->errors);
          }
        } else {
          $return->errors = $result->errors;
        }
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function get_resource_center_data($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          $postData[$key] = sanitize_text_field($value);
        }
      }
      $header = array(
        "Authorization: Bearer MTIzNA==",
        "Content-Type" => "application/json"
      );
      $url = $this->apiDomain . "/resourceCenter/list";
      $args = array(
        'timeout' => 300,
        'headers' => $header,
        'method' => 'POST',
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      return json_decode(wp_remote_retrieve_body($request));
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function getProductStatusByFeedId($data)
  {
    try {
      if (isset($data)) {
        $url = $this->apiDomain . '/products/list';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json",
          "AccessToken" => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function get_feed_status_by_store_id($data)
  {
    try {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $subscription_id = sanitize_text_field($TVC_Admin_Helper->get_subscriptionId());
      if (isset($subscription_id) && $data != "") {
        $url = $this->apiDomain . '/products/feed-list';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function delete_from_channels($data)
  {
    try {
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $subscription_id = sanitize_text_field($TVC_Admin_Helper->get_subscriptionId());
      if (isset($subscription_id) && $data != "") {
        $url = $this->apiDomain . '/products/batch';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json",
          "AccessToken" => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        );

        $args = array(
          'headers' => $header,
          'method' => 'DELETE',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        $return = new \stdClass();
        if ($result->status == 200) {
          $return->status = $result->status;
          $return->data = $result->data;
          $return->error = false;
          return $return;
        } else {
          $return->error = true;
          $return->data = $result->data;
          $return->status = $result->status;
          return $return;
        }
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function ee_create_product_feed($data)
  {
    try {
      $CONV_Admin_Helper = new TVC_Admin_Helper();
      $subscription_id = sanitize_text_field($CONV_Admin_Helper->get_subscriptionId());
      if (isset($subscription_id) && $data != "") {
        $url = $this->apiDomain . '/products/feed';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function feed_wise_products_sync($postData)
  {
    try {
      if (!empty($postData)) {
        foreach ($postData as $key => $value) {
          if (in_array($key, array("merchant_id", "account_id", "subscription_id", "store_feed_id", "is_on_gmc", "is_on_facebook", "is_on_tiktok"))) {
            $postData[$key] = sanitize_text_field($value);
          }
        }
      }
      $url = $this->apiDomain . "/products/batch-all";
      $args = array(
        'timeout' => 300,
        'headers' => array(
          'Authorization' => "Bearer MTIzNA==",
          'Content-Type' => 'application/json',
          'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
        ),
        'body' => wp_json_encode($postData)
      );
      $request = wp_remote_post(esc_url_raw($url), $args);

      // Retrieve information
      $response_code = wp_remote_retrieve_response_code($request);
      $response_message = wp_remote_retrieve_response_message($request);
      $response = json_decode(wp_remote_retrieve_body($request));
      $return = new \stdClass();
      if (isset($response->error) && $response->error == '') {
        $return->error = false;
        return $return;
      } else {
        $return->error = true;
        $return->arges =  $args;
        if (isset($response->errors)) {
          foreach ($response->errors as $err) {
            $return->message = $err;
            break;
          }
        }
        return $return;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function get_tiktok_business_account($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/tiktok/getBusinessCenter';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function get_tiktok_user_catalogs($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/tiktok/getUserCatalogs';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function store_business_center($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/tiktok/storeBusinessCenter';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function store_user_catalog($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/tiktok/storeUserCatalog';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function createCatalogs($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/tiktok/createCatalogs';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function conv_create_ec_row_apicall($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/usertracking/ec-journey';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function createPmaxCampaign($postData)
  {
    try {
      if ($postData != "") {
        $url = $this->apiDomain . '/pmax/retail';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($postData)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function pMaxRetailStatus($data)
  {
    try {
      if ($data != "") {
        $url = $this->apiDomain . '/pmax/retailStatus';
        $header = array(
          "Authorization: Bearer " . $this->token,
          "Content-Type" => "application/json"
        );

        $args = array(
          'headers' => $header,
          'method' => 'POST',
          'body' => wp_json_encode($data)
        );
        $result = $this->tc_wp_remot_call_post(esc_url_raw($url), $args);
        return $result;
      }
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
}
