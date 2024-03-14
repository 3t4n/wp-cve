<?php

class ShoppingApi {

    private $customerId;
    private $merchantId;
    private $apiDomain;
    private $token;
    protected $TVC_Admin_Helper;

    public function __construct() {
        $this->TVC_Admin_Helper = new TVC_Admin_Helper();
        $this->customApiObj = new CustomApi();
        //$queries = new TVC_Queries();
        $this->apiDomain = TVC_API_CALL_URL;
        $this->token = 'MTIzNA==';
        $this->merchantId = sanitize_text_field($this->TVC_Admin_Helper->get_merchantId());
        $this->customerId = sanitize_text_field($this->TVC_Admin_Helper->get_currentCustomerId());
        $this->subscriptionId = sanitize_text_field($this->TVC_Admin_Helper->get_subscriptionId());
    }

    public function getCampaigns() {
        try {
            $url = $this->apiDomain . '/campaigns/list';

            $data = [
                'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId)
            ];
            $args = array(
                'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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

    public function getCategories($country_code) {
        try {
            $url = $this->apiDomain . '/products/categories';

            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                'country_code' => sanitize_text_field($country_code)
            ];

            $args = array(
              'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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

    public function accountPerformance( $from_date = '', $to_date = '') {
        try {
           /* $days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }*/

            $url = $this->apiDomain . '/reports/account-performance';
            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                /*'graph_type' => sanitize_text_field(($date_range_type == 2 && $days_diff > 31) ? 'month' : 'day'),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),*/
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date),
                'subscription_id' => sanitize_text_field($this->subscriptionId)
            ];
            $args = array(
              'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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

    public function accountPerformance_for_dashboard( $from_date = '', $to_date = '') {
        try {
            /*$days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }*/
            $url = $this->apiDomain . '/reports/account-performance';
            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                /*'graph_type' => sanitize_text_field(($date_range_type == 2 && $days_diff > 61) ? 'month' : 'day'),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),*/
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date),
                'subscription_id' => sanitize_text_field($this->subscriptionId)
            ];

            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
              'timeout' => 300,
              'headers' =>$header,
              'method' => 'POST',
              'body' => wp_json_encode($data)
            );
            // Send remote request
            $request = wp_remote_post(esc_url_raw($url), $args);

            // Retrieve information
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $result = json_decode(wp_remote_retrieve_body($request));
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function campaign_performance($date_range_type, $days = 0, $from_date = '', $to_date = '') {
        try {
            $url = $this->apiDomain . '/reports/campaign-performance';
            $days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }
            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                'graph_type' => sanitize_text_field(($date_range_type == 2 && $days_diff > 61) ? 'month' : 'day'),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date),
                'subscription_id' => sanitize_text_field($this->subscriptionId)
            ];
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
              'headers' =>$header,
              'method' => 'POST',
              'body' => wp_json_encode($data)
            );
            // Send remote request
            $request = wp_remote_post(esc_url_raw($url), $args);

            // Retrieve information
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $result = json_decode(wp_remote_retrieve_body($request));
            $return = new \stdClass();
            if ((isset($result->error) && $result->error == '')) {
              $return->data = $result->data;
              //$return->data->graph_type = isset($data['graph_type'])?$data['graph_type']:"";
              $return->error = false;
              return $return;
            }else{
              $return->error = true;
              $return->data = $result->data;
              $return->errors = $result->errors;
              $return->status = $response_code;
              return $return;
            }
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function campaignPerformance($date_range_type, $days = 0, $from_date = '', $to_date = '') {
        try {
            $url = $this->apiDomain . '/reports/campaign-performance';
            $days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }
            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                'graph_type' => sanitize_text_field(($date_range_type == 2 && $days_diff > 31) ? 'month' : 'day'),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date)
            ];

            $args = array(
                'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                // Change ordring base on status
                $active_list = array(); $deactive_list = array();
                foreach ($response_body->data as $key => $value) {
                   if(isset($value->active) && $value->active == 1){
                    $active_list[] = $value;
                   }else{
                    $deactive_list[] = $value;
                   }
                }
                $response_body->data = array_merge($active_list, $deactive_list);
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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
    public function order_performance($from_date = '', $to_date = '', $limit = 5, $offset = 0, $dimensionNamefilter = '', $domain = '')
    {
        try {
            $url = $this->apiDomain . '/actionable-dashboard/order-performance-report-ga4';
           
            $data = [
                'start_date' => sanitize_text_field($from_date),
                'end_date' => sanitize_text_field($to_date),
                'subscription_id' => sanitize_text_field($this->subscriptionId),
                'limit' => sanitize_text_field($limit),
                'offset' => sanitize_text_field($offset),
                'dimensionNamefilter' => $dimensionNamefilter,
                'domain' => $domain,
            ];
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
                'headers' => $header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
            );
            // Send remote request
            $request = wp_remote_post(esc_url_raw($url), $args);

            // Retrieve information
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $result = json_decode(wp_remote_retrieve_body($request));
            //print_r($result); die;
            $return = new \stdClass();
            if ((isset($result->error) && $result->error == '')) {
                $return->data = $result->data;
                //$return->data->graph_type = isset($data['graph_type'])?$data['graph_type']:"";
                $return->error = false;
                return $return;
            } else {
                $return->error = true;
                $return->data = $result->data;
                $return->errors = $result->errors;
                $return->status = $response_code;
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function ecommerce_checkout_funnel($from_date = '', $to_date = '', $domain = '')
    {
        try {
            $url = $this->apiDomain . '/actionable-dashboard/ecomm-checkout-funnel-ga4';
            //$url = 'http://127.0.0.1:8000/api/actionable-dashboard/ecomm-checkout-funnel-ga4';
            $data = [
                'start_date' => sanitize_text_field($from_date),
                'end_date' => sanitize_text_field($to_date),
                'subscription_id' => sanitize_text_field($this->subscriptionId),
                'domain' => $domain
            ];
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
                'headers' => $header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
            );
            // Send remote request
            $request = wp_remote_post(esc_url_raw($url), $args);

            // Retrieve information
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $result = json_decode(wp_remote_retrieve_body($request));
            //print_r($result); die;
            $return = new \stdClass();
            if ((isset($result->error) && $result->error == '')) {
                $return->data = $result->data;
                $return->error = false;
                return $return;
            } else {
                $return->error = true;
                $return->data = $result->data;
                $return->errors = $result->errors;
                $return->status = $response_code;
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function productPerformance($campaign_id = '', $date_range_type='', $days = 30, $from_date = '', $to_date = '', $adGroupId = '') {
        try {
            $url = $this->apiDomain . '/reports/product-performance';

            /*$data = [
                'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_id' => sanitize_text_field($campaign_id),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date)
            ];*/
            $data = [
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_id' => sanitize_text_field($campaign_id),
                "adgroup_id" => sanitize_text_field($adGroupId),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date)
            ];

            $args = array(
                'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            //print_r($response_body);
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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

    public function productPartitionPerformance($campaign_id = '', $date_range_type='', $days = 0, $from_date = '', $to_date = '', $adGroupId = '') {
        try {
            $url = $this->apiDomain . '/reports/product-partition-performance';

            $data = [
                //'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_id' => sanitize_text_field($campaign_id),
                "adgroup_id" => sanitize_text_field($adGroupId),
                'date_range_type' => sanitize_text_field($date_range_type),
                'days' => sanitize_text_field($days),
                'from_date' => sanitize_text_field($from_date),
                'to_date' => sanitize_text_field($to_date)
            ];

            $args = array(
                'timeout' => 300,
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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

    public function getCampaignDetails($campaign_id = '') {
        try {
            $url = $this->apiDomain . '/campaigns/detail';

            $data = [
                'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_id' => sanitize_text_field($campaign_id)
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                $response_body->data->category_id = (isset($response_body->data->category_id)) ? $response_body->data->category_id : '0';
                $response_body->data->category_level = (isset($response_body->data->category_level)) ? $response_body->data->category_level : '0';
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => esc_attr($response_message),
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
    public function createCampaign($campaign_name = '', $budget = 0, $target_country = 'US', $all_products = 0, $category_id = '', $category_level = '') {
        try {
            $header = array(
                "Authorization: Bearer MTIzNA==",
                "Content-Type" => "application/json"
            );
            $curl_url = $this->apiDomain . "/campaigns/create";  
            $data = [
                'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_name' => sanitize_text_field($campaign_name),
                'budget' => sanitize_text_field($budget),
                'target_country' => sanitize_text_field($target_country),
                'all_products' => sanitize_text_field($all_products),
                'filter_by' => 'category',
                'filter_data' => ["id" => sanitize_text_field($category_id), "level" => sanitize_text_field($category_level)]
            ];          
            
            $args = array(
              'timeout' => 300,
                'headers' =>$header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
              );
            $request = wp_remote_post(esc_url_raw($curl_url), $args);
           
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $response = json_decode(wp_remote_retrieve_body($request));
            
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = esc_attr($response->message); 
                $return->data = $response->data;
                return $return;
            } else {                
                $return->error = true;
                $return->errors = $response->errors;            
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateCampaign($campaign_name = '', $budget = 0, $campaign_id = '', $budget_id='', $target_country = '', $all_products = 0, $category_id = '', $category_level = '', $ad_group_id = '', $ad_group_resource_name = '') {
        try {
            $header = array(
                "Authorization: Bearer MTIzNA==",
                "Content-Type" => "application/json"
            );
            $curl_url = $this->apiDomain . '/campaigns/update';
            $data = [
                'merchant_id' => sanitize_text_field($this->merchantId),
                'customer_id' => sanitize_text_field($this->customerId),
                'campaign_id' => sanitize_text_field($campaign_id),
                'account_budget_id' => sanitize_text_field($budget_id),
                'campaign_name' => sanitize_text_field($campaign_name),
                'target_country' => sanitize_text_field($target_country),
                'budget' => sanitize_text_field($budget),
                'status' => 2, // ENABLE => 2, PAUSED => 3, REMOVED => 4
                'all_products' => sanitize_text_field($all_products),
                'ad_group_id' => sanitize_text_field($ad_group_id),
                'ad_group_resource_name' => sanitize_text_field($ad_group_resource_name),
                'filter_by' => 'category',
                'filter_data' => ["id" => sanitize_text_field($category_id), "level" => sanitize_text_field($category_level)]
            ];        
            
            $args = array(
              'timeout' => 300,
                'headers' =>$header,
                'method' => 'PATCH',
                'body' => wp_json_encode($data)
              );
            $request = wp_remote_post(esc_url_raw($curl_url), $args);
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $response = json_decode(wp_remote_retrieve_body($request));
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = esc_attr($response->message); 
                $return->data = $response->data;
                return $return;
            } else {                
                $return->error = true;
                $return->errors = $response->errors;            
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /*set configuration for Schedule email ga4*/
    public function set_email_configurationGA4($subscription_id, $is_disabled, $custom_email = '', $email_frequency = '')
    {
        try {
            
             $data = array('is_disabled' => $is_disabled, 'subscription_id' => $subscription_id, 'custom_email' => $custom_email, 'emailFrequency' => $email_frequency);
            
            $curl_url = $this->apiDomain . '/actionable-dashboard/update-ga4-email-schedule';
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
                'headers' => $header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
            );
            $request = wp_remote_post(esc_url_raw($curl_url), $args);
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $response = json_decode(wp_remote_retrieve_body($request));
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = esc_attr($response->message);
                $return->data = $response->data;
                return $return;
            } else {
                $return->error = true;
                $return->errors = $response->errors;
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } 
    /* Save all reports data for ai module */
    public function save_all_reports($subscription_id,$start_date,$end_date, $domain, $ga4_analytic_account_id ='', $ga4_property_id ='', $google_ads_id='', $measurement_id='') 
    {
        try {
            $data = array('subscription_id' => $subscription_id, 'start_date' => $start_date, 'end_date'=> $end_date, 'domain'=> $domain,'ga4_analytic_account_id' => $ga4_analytic_account_id, 'ga4_property_id' => $ga4_property_id, 'google_ads_id'=> $google_ads_id, 'measurement_id'=> $measurement_id);
            $curl_url = $this->apiDomain . '/aireporting/save_all_reports';
            //$curl_url = 'http://127.0.0.1:8000/api/aireporting/save_all_reports';
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
                'headers' => $header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
            );
            $request = wp_remote_post(esc_url_raw($curl_url), $args);
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $response = json_decode(wp_remote_retrieve_body($request));
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = esc_attr($response->message);
                $return->data = $response->data;
                return $return;
            } else {
                $return->error = true;
                $return->errors = $response->errors;
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /*Generate AI response from middleware*/
    public function generate_ai_response($subscription_id, $key, $domain,$ai_flag = '0')
    {
        try {
            $data = array('promptIndex' => $key, 'subscription_id' => $subscription_id, 'domain'=> $domain, 'ai_flag'=> $ai_flag);
    
            $curl_url = $this->apiDomain . '/aireporting/get-prompt-response';
            //$curl_url = 'http://127.0.0.1:8000/api/aireporting/get-prompt-response';
            $header = array(
                "Authorization: Bearer $this->token",
                "Content-Type" => "application/json"
            );
            $args = array(
                'timeout' => 300,
                'headers' => $header,
                'method' => 'POST',
                'body' => wp_json_encode($data)
            );
            $request = wp_remote_post(esc_url_raw($curl_url), $args);
            $response_code = wp_remote_retrieve_response_code($request);
            $response_message = wp_remote_retrieve_response_message($request);
            $response = json_decode(wp_remote_retrieve_body($request));
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = esc_attr($response->message);
                $return->data = $response->data;
                return $return;
            } else {
                $return->error = true;
                $return->errors = $response->errors;
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
        /*Save Prompt Suggestions*/
        public function save_suggestions($subscription_id, $suggestions, $domain)
        {
            try {
                $data = array('suggestions' => $suggestions, 'subscription_id' => $subscription_id, 'domain'=> $domain);
        
                $curl_url = $this->apiDomain . '/aireporting/suggest-prompt';
                //$curl_url = 'http://127.0.0.1:8000/api/aireporting/suggest-prompt';
                $header = array(
                    "Authorization: Bearer $this->token",
                    "Content-Type" => "application/json"
                );
                $args = array(
                    'timeout' => 300,
                    'headers' => $header,
                    'method' => 'POST',
                    'body' => wp_json_encode($data)
                );
                $request = wp_remote_post(esc_url_raw($curl_url), $args);
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response = json_decode(wp_remote_retrieve_body($request));
                $return = new \stdClass();
                if (isset($response->error) && $response->error == false) {
                    $return->error = false;
                    $return->message = esc_attr($response->message);
                    $return->data = $response->data;
                    return $return;
                } else {
                    $return->error = true;
                    $return->errors = $response->errors;
                    return $return;
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
  
}