<?php
/*
  Plugin Name: Novamodule : WooCommerce - NetSuite Integration
  Description: Novamodule plug-in helpful to integrate with IO. Woocommerce should be installed and active to use this plugin.
  Version: 2.4.3
  Author: Nova Module
  Author URI: http://www.novamodule.com/
 */
add_action('admin_init', 'nova_plugin_has_woocomerce_plugin');

/**
 * Plugin Activation/deactivation
 */
function nova_plugin_has_woocomerce_plugin() {
    if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php')) {
        add_action('admin_notices', 'nova_plugin_notice');

        deactivate_plugins(plugin_basename(__FILE__));

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }
}

/**
 * Display plugin dependent notice
 */
function nova_plugin_notice() {
    ?><div class="error" style="background-color: red;"><p>Sorry, Novamodule Plugin requires the Woocommerce plugin to be installed and active.</p></div><?php
}

add_action('woocommerce_api_loaded', 'wpc_register_wp_api_endpoints');

/**
 * Legacy API calls 
 */
function wpc_register_wp_api_endpoints() {
    include_once 'Api/class-wc-api-bulkfulfillment.php';
    include_once 'Api/class-wc-api-bulkorderidupdate.php';
    add_filter('woocommerce_api_classes', 'filter_woocommerce_api_classes', 10, 1);
}

function filter_woocommerce_api_classes($array) {
    $cart = array(
        'WC_API_Bulkfulfillment',
        'WC_API_Bulkorderidupdate'
    );
    return array_merge($array, $cart);
}


add_action('rest_api_init', 'register_api_hooks');

/**
 * V3 API enpoints initialization 
 */
function register_api_hooks() {
    define('NOVA_PLUGIN_PATH', plugin_dir_path(__FILE__));
    include_once 'Rest-W3-Api/class-wc-rest-bulkproducts-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-bulkinventory-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-listitemids-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-bulkimage-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-order-statuses-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-update-import-status-controller.php';
    include_once 'Rest-W3-Api/class-wc-rest-import-fulfillments-controller.php';

    $WC_REST_Bulkproducts_Controller = new WC_REST_Bulkproducts_Controller;
    $WC_REST_Bulkproducts_Controller->register_routes();

    $WC_REST_Bulkinventory_Controller = new WC_REST_Bulkinventory_Controller;
    $WC_REST_Bulkinventory_Controller->register_routes();

    $WC_REST_ListItemIds_Controller = new WC_REST_ListItemIds_Controller;
    $WC_REST_ListItemIds_Controller->register_routes();

    $WC_REST_Bulkimage_Controller = new WC_REST_Bulkimage_Controller;
    $WC_REST_Bulkimage_Controller->register_routes();

    $WC_REST_Orderstatuses_Controller = new WC_REST_Orderstatuses_Controller;
    $WC_REST_Orderstatuses_Controller->register_routes();

    $WC_REST_Orderimportstatuse_Controller = new WC_REST_Orderimportstatuse_Controller;
    $WC_REST_Orderimportstatuse_Controller->register_routes();

    $WC_REST_Orderimportfulfillments_Controller = new WC_REST_Orderimportfulfillments_Controller;
    $WC_REST_Orderimportfulfillments_Controller->register_routes();
}

##### Custom Api End ######
#####Add additional filters for customers list to get Latest Modified ##########
add_filter('woocommerce_rest_customer_query', 'novamodule_woocommerce_rest_customer_query', 100, 2);

/**
 * Add datefilter on customers
 * @param Array $prepared_args
 * @param Object $request
 * @return Array
 */
function novamodule_woocommerce_rest_customer_query($prepared_args, $request) {

    if (isset($_GET["type"]) && $_GET["type"] === "delta" && isset($_GET["io_last_run"]) && $_GET["io_last_run"] != "") {
        $io_last_run = filter_var(wp_unslash($_GET["io_last_run"]), FILTER_SANITIZE_NUMBER_INT);
        $lastrunDateTime = $io_last_run / 1000;
        if (isset($_GET["io_date_field"]) && $_GET["io_date_field"] == "modified") {
            if (isset($_GET["io_customer_timezone_diff"]) && $_GET["io_customer_timezone_diff"] != "") {
                $io_customer_timezone_diff = filter_var(wp_unslash($_GET["io_customer_timezone_diff"]), FILTER_SANITIZE_STRING);

                $new_updated_date = date("Y-m-d H:i:s", strtotime($io_customer_timezone_diff, $lastrunDateTime));
                $lastrunDateTime = strtotime($new_updated_date);
            }
            $prepared_args["meta_query"][] = array(
                'key' => 'last_update',
                'value' => $lastrunDateTime,
                'compare' => '>='
            );
        } else {
            $prepared_args['date_query'] = array(
                "after" => date("Y-m-d H:i:s", $lastrunDateTime)
            );
        }
    }
	if(isset($_GET["novamodule"]) && $_GET["novamodule"] == 1 && isset($_GET["billing_phone"]) && $_GET["billing_phone"] != "") {
		$billing_phone = filter_var(wp_unslash($_GET["billing_phone"]), FILTER_SANITIZE_STRING);
		
		$prepared_args["meta_query"][] = array(
                'key' => 'billing_phone',
                'value' => $billing_phone,
                'compare' => '=='
            );
	}

    return $prepared_args;
}

################################END#############################################
#####Add additional filters for Orders list to get Latest Modified ##########
add_filter('woocommerce_rest_shop_order_object_query', 'novamodule_woocommerce_rest_shop_order_object_query', 100, 2); //V3 Version

/**
 * Add date filter on Sales Orders
 * @global type $wpdb
 * @param Array $prepared_args
 * @param Object $request
 * @return Array
 */
function novamodule_woocommerce_rest_shop_order_object_query($prepared_args, $request) {

    if (isset($_GET["meta_query"]) && is_array($_GET["meta_query"])) {
        $meta_filters = $_GET["meta_query"];
        foreach ($meta_filters as $metaData) {
            $prepared_args['meta_query'][] = $metaData;
        }
    }
    if (isset($_GET["type"]) && $_GET["type"] === "delta" && isset($_GET["io_last_run"]) && $_GET["io_last_run"] != "") {
        $io_last_run = filter_var(wp_unslash($_GET["io_last_run"]), FILTER_SANITIZE_NUMBER_INT);

        $lastrunDateTime = $io_last_run / 1000;
        $prepared_args['date_query'] = array(
            "after" => date("Y-m-d H:i:s", $lastrunDateTime)
        );
    }
    if (isset($_GET["type"]) && $_GET["type"] != "delta" && isset($_GET["io_last_run"]) && $_GET["io_last_run"] != "") {

        $io_last_run = filter_var(wp_unslash($_GET["io_last_run"]), FILTER_SANITIZE_NUMBER_INT);
        $lastrunDateTime = $io_last_run; // / 1000;        
        $prepared_args['date_query'] = array(
            "after" => date("Y-m-d H:i:s", $lastrunDateTime)
        );
    }
    if (isset($_GET["custom_date"]) && isset($_GET["filter"]["updated_at_min"]) && $_GET["filter"]["updated_at_min"] != "") {

        $updated_at_min = filter_var(wp_unslash($_GET["filter"]["updated_at_min"]), FILTER_SANITIZE_STRING);

        $updated_date = date("Y-m-d H:i:s", strtotime('-2 hours', $updated_at_min));
        if (isset($_GET["io_salesorder_timezone_diff"]) && $_GET["io_salesorder_timezone_diff"] != "") {

            $io_salesorder_timezone_diff = filter_var(wp_unslash($_GET["io_salesorder_timezone_diff"]), FILTER_SANITIZE_STRING);
            $updated_date = date("Y-m-d H:i:s", strtotime($io_salesorder_timezone_diff, $updated_at_min));
        }
        $prepared_args['date_query'][] = array(
            'column' => 'post_modified_gmt',
            'after' => $updated_date,
            'inclusive' => true
        );
    }
    if (isset($_GET["novamodule"]) && isset($_GET["refund_date"]) && $_GET["refund_date"] != "") {

        global $wpdb;       
		$refund_date = filter_var(wp_unslash($_GET["refund_date"]), FILTER_SANITIZE_STRING);
        $strTime = strtotime($refund_date);
        $strTime = filter_var($strTime, FILTER_SANITIZE_NUMBER_INT);
        $refund_date = date("Y-m-d H:i:s", $strTime);
        $query = $wpdb->prepare("SELECT post_parent FROM " . $wpdb->prefix . "posts  WHERE  post_date  >= '%s' AND post_parent > 0 AND post_type = 'shop_order_refund'", $refund_date);

        $results = $wpdb->get_results($query);
        $order_in = array(0);
        foreach ($results as $result) {
            $order_in[] = $result->post_parent;
        }
		if(isset($_GET["include_cancelled"])) {
			
			$query = $wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "posts  WHERE  post_modified  >= '%s' AND post_status ='wc-cancelled' AND post_type = 'shop_order'", $refund_date);
		    $results = $wpdb->get_results($query);	
 			
			foreach ($results as $result) {
				$order_in[] = $result->id;
			}
			
		}

        if (count($order_in) > 0) {
            $prepared_args["post__in"] = $order_in;
        }
    }
    return $prepared_args;
}

add_filter('woocommerce_rest_shop_order_query', 'novamodule_woocommerce_rest_shop_order_query', 100, 2);
add_filter('woocommerce_rest_orders_prepare_object_query', 'novamodule_woocommerce_rest_shop_order_query', 100, 2);

function search_order_since_id( $start_id, $args ) {

		global $wpdb;

		
		$limit          = intval( $args['posts_per_page'] );
        $custom_table = get_option('woocommerce_custom_orders_table_enabled' );
		 $status_list_new = [];
		if (strtolower($custom_table) == "yes") {
			if(isset($_GET["status"]) && $_GET["status"] != "" ) {
				$statuses =  filter_var($_GET["status"], FILTER_SANITIZE_STRING);
				$status_list = explode(",",$statuses );	
               				
				if(count($status_list) > 0) {
					foreach($status_list as $s) {
						$status_list_new[] = "wc-".$s;
					}
				}
				
				
				 
			}
			$order_table_name = $wpdb->prefix . 'wc_orders';
			$wp_wc_orders_meta = $wpdb->prefix . 'wc_orders_meta';		 
			 
			$count_query = "SELECT o.id FROM ".$wp_wc_orders_meta." as meta INNER JOIN ".$order_table_name." as o ON o.id = meta.order_id WHERE meta.meta_key = 'nm_ns_pushed' AND  meta.meta_value  = '0' ";
			if(count($status_list_new) > 0) {
				$count_query .=  "AND o.status IN ('" . implode("','", $status_list_new) . "')";
			}
			$count_query .= " AND o.id > %d LIMIT %d";		 
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $orders_table_name is hardcoded.
			$order_ids = $wpdb->get_col(
				 $wpdb->prepare(
					$count_query,
					$start_id,
					$limit
				)
			);
			// phpcs:enable
			
				// Force WP_Query return empty if don't found any order.
			$order_ids        = empty( $order_ids ) ? array( 0 ) : $order_ids;
			$args['post__in'] = $order_ids;
		} else {
			
			/*$order_ids = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT ID
				FROM {$wpdb->prefix}posts
				WHERE post_type = 'shop_order'
				AND ID > %d
				LIMIT %d",
					$start_id,
					$limit
				)
			);*/
		}
		 
        return $args;
	}
/**
 * Add date filter on Sales Orders
 * @global type $wpdb
 * @param Array $prepared_args
 * @param Object $request
 * @return Array
 */
function novamodule_woocommerce_rest_shop_order_query($prepared_args, $request) {

    if (isset($_GET["type"]) && $_GET["type"] == "delta" && isset($_GET["io_last_run"]) && $_GET["io_last_run"] != "") {
        $io_last_run = filter_var(wp_unslash($_GET["io_last_run"]), FILTER_SANITIZE_NUMBER_INT);

        $lastrunDateTime = $io_last_run / 1000;
        $prepared_args['date_query'] = array(
            "after" => date("Y-m-d H:i:s", $lastrunDateTime)
        );
    }
    if (isset($_GET["type"]) && $_GET["type"] != "delta" && isset($_GET["io_last_run"]) && $_GET["io_last_run"] != "") {
        $io_last_run = filter_var(wp_unslash($_GET["io_last_run"]), FILTER_SANITIZE_NUMBER_INT);

        $lastrunDateTime = $io_last_run; // / 1000;        
        $prepared_args['date_query'] = array(
            "after" => date("Y-m-d H:i:s", $lastrunDateTime)
        );
    }

    if (isset($_GET["custom_date"]) && isset($_GET["filter"]["updated_at_min"]) && $_GET["filter"]["updated_at_min"] != "") {

        $updated_at_min = filter_var(wp_unslash($_GET["filter"]["updated_at_min"]), FILTER_SANITIZE_STRING);

        $updated_date = date("Y-m-d H:i:s", strtotime('-2 hours', $updated_at_min));
        if (isset($_GET["io_salesorder_timezone_diff"]) && $_GET["io_salesorder_timezone_diff"] != "") {

            $io_salesorder_timezone_diff = filter_var(wp_unslash($_GET["io_salesorder_timezone_diff"]), FILTER_SANITIZE_STRING);
            $updated_date = date("Y-m-d H:i:s", strtotime($io_salesorder_timezone_diff, $updated_at_min));
        }
        $prepared_args['date_query'][] = array(
            'column' => 'post_modified_gmt',
            'after' => $updated_date,
            'inclusive' => true
        );
    }
    if (isset($_GET["nm_meta_key"]) && isset($_GET["nm_meta_key_value"])) {
		
        $nm_meta_key =  filter_var(wp_unslash($_GET["nm_meta_key"]), FILTER_SANITIZE_STRING);
		$nm_meta_key_value =  filter_var(wp_unslash($_GET["nm_meta_key_value"]), FILTER_SANITIZE_STRING);
        $prepared_args['meta_query'][] = array(
            'key' => $nm_meta_key,
            'value' => $nm_meta_key_value
        );
    }

    if (isset($_GET["start_order_id"]) && $_GET["start_order_id"] > 0) {
        $start_order_id = wp_unslash($_GET["start_order_id"]);

        $start_order_id = filter_var($start_order_id, FILTER_SANITIZE_NUMBER_INT);
          
         $prepared_args = search_order_since_id( $start_order_id, $prepared_args );
  	 
	}

 
    return $prepared_args;
}
add_action('woocommerce_update_order', 'add_novamodule_flag_new', 10, 2);
add_action('woocommerce_new_order', 'add_novamodule_flag_new', 10, 2);
function add_novamodule_flag_new($order_id, $order) {
     $previousValue = $order->get_meta ( 'nm_ns_pushed' );
	 $nm_ns_pushed_new = 0;
	 
	 if((int)$previousValue  > 0) {
		  return;
	 }
	 $order->update_meta_data( 'nm_ns_pushed', $nm_ns_pushed_new );
     return true;
}
add_action('save_post', 'add_novamodule_flag', 10, 3);

/**
 * Add NS pushed Meta key
 * @param Int $post_id
 * @param Object $post
 * @param Boolean $update
 * @return boolean
 */
function add_novamodule_flag($post_id, $post, $update) {

    $slug = 'shop_order';
    // If this isn't a 'woocommercer order' post, don't update it.
    if ($slug != $post->post_type) {
        return;
    }
    $order_id = $post->ID;

    $previousValue = get_post_meta($order_id, 'nm_ns_pushed', true);
	 $nm_ns_pushed = 0;
	 if ($update == false && isset($_GET["is_ns_order"]) && $_GET["is_ns_order"] === "1" ) {
	 	$is_ns_order = filter_var(wp_unslash($_GET["is_ns_order"]), FILTER_SANITIZE_NUMBER_INT);
		if($is_ns_order == 1) {
			 return true; 
		}
	 }
    if ($previousValue != 1) {        
        update_post_meta($order_id, 'nm_ns_pushed',  $nm_ns_pushed);
    }
    return true;
}

add_filter("woocommerce_rest_prepare_customer", "novamodule_woocommerce_rest_prepare_customer_additional_meta", 10, 3);
add_filter("woocommerce_rest_prepare_customer_object", "novamodule_woocommerce_rest_prepare_customer_additional_meta", 10, 3);

/**
 * Add memebership information in customer api
 * @param Object $response
 * @param Object $customer
 * @param Object $request
 * @return Object
 */
function novamodule_woocommerce_rest_prepare_customer_additional_meta($response, $customer, $request) {

    $extra_membership = get_option('extra_membership', '');
    if ($extra_membership != "" && isset($response->data["id"]) && $response->data["id"] > 0) {

        $customer_id = $response->data["id"];
        $memberShipInforData = getCustomeMemberShipInformation($customer_id);
        $response->data["customer_membership_data"] = $memberShipInforData;
    }

    if (isset($response->data["id"]) && $response->data["id"] > 0) {
        $response = getCustomerFullInfo($response->data["id"], $response);
    } else {
        $response->data["user_full_info"] = array();
    }


    return $response;
}

add_filter("woocommerce_rest_prepare_shop_order", "novamodule_woocommerce_order_additional_meta", 10, 3);

add_filter("woocommerce_rest_prepare_shop_order_object", "novamodule_woocommerce_order_additional_meta", 10, 3);

/**
 * Add additional metakeys in order API
 * @param Object  $response
 * @param Object $order
 * @param Object $request
 * @return Object
 */
function novamodule_woocommerce_order_additional_meta($response, $order, $request) {


    $extra_order_meta_keys = get_option('extra_order_meta_keys', '');
    $extra_order_meta_data = explode("\r\n", $extra_order_meta_keys);

    if (count($extra_order_meta_data) > 0) {
        foreach ($extra_order_meta_data as $newKey) {
            if (trim($newKey) !== "") {
                $getMetaData = get_post_meta($order->ID, $newKey, true);
                $response->data[$newKey] = $getMetaData;
            }
        }
    }

    $extra_membership = get_option('extra_membership', '');

    if ($extra_membership !== "" && isset($response->data["customer_id"]) && $response->data["customer_id"] > 0) {
        $customer_id = $response->data["customer_id"];

        $memberShipInforData = getCustomeMemberShipInformation($customer_id);
        $response->data["customer_membership_data"] = $memberShipInforData;
    }

    if (isset($response->data["customer_id"]) && $response->data["customer_id"] > 0) {
        $response = getCustomerFullInfo($response->data["customer_id"], $response);
    } else {
        $response->data["user_full_info"] = array();
    }



    return $response;
}

/**
 * Get customer information
 * @param Int $user_id
 * @param Object $response
 * @return Object
 */
function getCustomerFullInfo($user_id, $response) {
    try {
        $user_info = get_userdata($user_id);
        unset($user_info->user_pass);
        $response->data["user_full_info"]["basic_info"] = $user_info;
        if (isset($user_info->roles[0])) {
            $response->data["user_role"] = $user_info->roles[0];
        }
        if (!isset($response->data["first_name"])) {
            $response->data["first_name"] = get_user_meta($user_id, 'first_name', true);
        }
        if (!isset($response->data["last_name"])) {
            $response->data["last_name"] = get_user_meta($user_id, 'last_name', true);
        }

        $all_meta_for_user = array_map(function($a) {
            return $a[0];
        }, get_user_meta($user_id));

        $response->data["user_full_info"]["meta_data"] = $all_meta_for_user;
    } catch (Exception $e) {
        $response->data["user_full_error"] = wp_json_encode($e->getMessage());
    }
    return $response;
}

/**
 * Get customer membership Information
 * @param Int $customer_id
 * @return Array
 */
function getCustomerMemberShipData($customer_id) {
    $memberShipInformation = array();
    $lastposts = get_posts(array(
        'post_status' => "wcm-active",
        "post_type" => "wc_user_membership",
        "author" => $customer_id
    ));
    if (is_array($lastposts)) {

        foreach ($lastposts as $post) {
            $memberData = get_post($post->post_parent, 'ARRAY_A');
            if (count($memberData) > 0) {
                $memberShipInformation[] = $memberData;
            }
        }
    }
    return $memberShipInformation;
}

/**
 * Get customer membership Information
 * @param Int $customer_id
 * @return Array
 */
function getCustomeMemberShipInformation($user_id) {

    $memberShipInfo = array();
    $memberships = wc_memberships()->get_user_memberships_instance()->get_user_memberships($user_id);
    // count the memberships displayed
    $count = 0;
    if (!empty($memberships)) {
        foreach ($memberships as $membership) {
            $plan = $membership->get_plan();
            if ($plan && wc_memberships_is_user_active_member($user_id, $plan)) {
                $memberShipInfo[] = array(
                    "name" => $plan->name,
                    "id" => $membership->id,
                    "slug" => $plan->slug,
                    "fullinfo" => $plan->post
                );
            }
        }
    }
    return $memberShipInfo;
}

################################END#############################################


add_action('user_register', 'nova_update_profile_last_update_time', 100);

/**
 * Add last_update meta on customer
 * @param Int $user_id
 */
function nova_update_profile_last_update_time($user_id) {

    update_user_meta($user_id, 'last_update', time());
}

 

######################REGISTER NEW META FIELDS ###############################

add_filter('admin_init', 'nova_general_settings_register_fields');

/**
 * Settings fields for extra meta keys and memebership fields
 */
function nova_general_settings_register_fields() {
    register_setting('general', 'extra_order_meta_keys', 'esc_attr');
    add_settings_field('extra_order_meta_keys', '<label for="extra_order_meta_keys">' . __('Novamodule Additional Order Meta', 'extra_order_meta_keys') . '</label>', 'nova_general_settings_register_fields_html', 'general');

    register_setting('general', 'extra_membership', 'esc_attr');
    add_settings_field('extra_membership', '<label for="extra_membership">' . __('Novamodule Export Membership', 'extra_membership') . '</label>', 'nova_extra_membership_html', 'general');
 
}

function nova_general_settings_register_fields_html() {
    $value = get_option('extra_order_meta_keys', '');
    echo novagenerateField('extra_order_meta_keys', $value);
}

function nova_general_settings_register_fields_customer_html() {
    $value = get_option('extra_customer_meta_keys', '');
    echo novagenerateField('extra_customer_meta_keys', $value);
}

function nova_extra_membership_html() {
    $value = get_option('extra_membership', '');
    echo novagenerateFieldCheckBox('extra_membership', $value);
}

function novagenerateField($field, $value) {
    return '<textarea class="regular-text ltr"  id="' . esc_attr($field). '" name="' . esc_attr($field) . '">' . esc_textarea($value) . "</textarea>";
}

function novagenerateFieldCheckBox($field, $value) {
    $checked = '';
    if ($value != "") {
        $checked = "checked = 'checked'";
    }
    return '<input type="checkbox" class="regular-text ltr"  id="' . esc_attr($field). '" name="' . esc_attr($field). '"  ' . $checked . '/>';
}

add_filter('posts_clauses_request', 'novamodule_additional_filters_callback');

/**
 * Additional filters on salesorders
 * @global Object $wpdb
 * @param Array $args
 * @return Array
 */
function novamodule_additional_filters_callback($args) {
    global $wpdb;
    if (isset($_GET["start_order_id"]) && $_GET["start_order_id"] > 0) {
        $start_order_id = wp_unslash($_GET["start_order_id"]);

        $start_order_id = filter_var($start_order_id, FILTER_SANITIZE_NUMBER_INT);


        $args["where"] = $args["where"] . " AND " . $wpdb->prefix . "posts.ID > " . $start_order_id;
    }
    if (!isset($_GET["status"]) && isset($_GET["multi_order_status"]) && $_GET["multi_order_status"] != "") {

        $multi_order_status = filter_var(wp_unslash($_GET["multi_order_status"]), FILTER_SANITIZE_STRING);
        $multi_order_status = explode(",", $multi_order_status);
        $multiStatues = array();
        foreach ($multi_order_status as $val) {
            $multiStatues[] = "wc-" . $val;
        }
        if (count($multiStatues) > 0) {
            $args["where"] = $args["where"] . " AND " . $wpdb->prefix . "posts.post_status IN('" . implode("','", $multiStatues) . "')";
        }
    }
    if (isset($_GET["novamodule"]) && isset($_GET["modified_date"]) && $_GET["modified_date"] != "") {    
		$modified_date = filter_var(wp_unslash($_GET["modified_date"]), FILTER_SANITIZE_STRING);
        $strTime = strtotime($modified_date);
        $strTime = filter_var($strTime, FILTER_SANITIZE_NUMBER_INT);
        $modified_date = date("Y-m-d H:i:s", $strTime);

        $args["where"] = $args["where"] . " AND " . $wpdb->prefix . "posts.post_modified >= '" . $modified_date . "'";
    }

    return $args;
}

######################REGISTER NEW META FIELDS - END ###############################

add_filter('woocommerce_rest_pre_insert_shop_order_object',
'update_insert_rest_api_order', 10, 3);

function update_insert_rest_api_order($order, $request, $creating) {
    // Get customer data from Order Object and add customer credit details.

     $orderId = $order->get_id();

     #$order->set_prop('customer_note',  'Call center discount update:'); # . $discText;
     #return $order;

     // if "discount rate" is used in Netsuite (not coupon code), spread order discount across all of the products
	 if( isset($request->data['meta_data']) ): 
     foreach ($request->data['meta_data'] as $meta):

         if ($meta->key === 'ns_order_discount'):

             $discText = $meta->value;


             if (strpos($discText, '%') !== false):
                 $discPercent = abs(intval($discText)) / 100;
             else:
                 // convert dollar discount into a percent that can be applied to each item

                 // get item total
                 $itemTotal = 0;
                 foreach ($request->data['line_items'] as $item)
                     $itemTotal += $item['total'];

                 // turn $ discount into a percent, based on the order total ( percent = $disc / $itemTotal)
                 $discPercent = abs(intval($discText)) / $itemTotal;
             endif;

             foreach ($request->data['line_items'] as $key => $item):
                 $request->data['line_items'][$key]['total'] =
round($item['total'] *  (1 - $discPercent), 2);
$request->data['line_items'][$key]['subtotal'] = round($item['total'] * 
(1 - $discPercent), 2);

                 foreach
($request->data['line_items'][$key]['meta_data'] as $metakey => $itemmeta):
                     if ($itemmeta->key === '_line_total_base_currency'):
$request->data['line_items'][$key]['meta_data'][$metakey] = array('key'
=> '_line_total_base_currency', 'value' => round($item['total'] *  (1 -
$discPercent), 2));
                     endif;
                 endforeach;
             endforeach;

             if (trim($request->data['customer_note']))
$request->data['customer_note'] .= ' | ';
             $order->customer_note .= 'Call center discount: ' . $discText;

         endif;

     endforeach;
	 
	  endif;
	//$order->set_customer_note ("NOVA TESTING");
 

     return $order;
}

add_action('woocommerce_rest_insert_customer', 'nova_woocommerce_rest_insert_customer', 10,3);

 
function nova_woocommerce_rest_insert_customer($user_data, $request, $isCreating) {   
   
   if( !( $user_data->ID > 0) ) {
	   return;
   }
   $params = $request->get_params();
   
   if(isset($params['nova_role']) ) {
	   $novaroles = $params['nova_role'];
	   if(!is_array($params['nova_role'])) {
		   $novaroles  = explode(",",$params['nova_role']);
	   }
	   $user = new WP_User( $user_data->ID);	    
	 	 
	  foreach($novaroles as $role) {
				  if(isset($params['nova_role_keep_existing'])  && $params['nova_role_keep_existing'] == 1) {
					$user->add_role($role);
					}
				  else {
					 $user->set_role($role);
				 }
		 
	  }
	   
	   
   }
 
}