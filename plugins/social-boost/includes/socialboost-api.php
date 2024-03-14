<?php

if( ! defined('ABSPATH'))
    exit;

class Socialboost_API extends WP_REST_Controller
{
    public function register_apis()
    {
        register_rest_route('socialboost/v1', '/getPage', array(
            array(
                'methods'               =>  WP_REST_Server::READABLE,
                'callback'              =>  array($this, 'get_page'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));
        register_rest_route('socialboost/v1', '/addPage', array(
            array(
                'methods'               =>  WP_REST_Server::CREATABLE,
                'callback'              =>  array($this, 'add_page'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));
        register_rest_route('socialboost/v1', '/editPage', array(
            array(
                'methods'               =>  WP_REST_Server::EDITABLE,
                'callback'              =>  array($this, 'edit_page'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));
        register_rest_route('socialboost/v1', '/deletePage', array(
            array(
                'methods'               =>  WP_REST_Server::EDITABLE,
                'callback'              =>  array($this, 'delete_page'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));

        register_rest_route('socialboost/v1', '/getversion', array(
            'methods'               => 'POST',
            'callback'              => array($this, 'getversion'),
            'permission_callback'   => array($this, 'check_api_permission'),
            'args'                  => array()
        ));
        register_rest_route('socialboost/v1', '/resetInstallation', array(
            array(
                'methods'               =>  'POST',
                'callback'              =>  array($this, 'reset_installation'),
                'permission_callback'   =>  array($this, 'check_api_permission_lite'),
                'args'                  =>  array()
            )
        ));
        register_rest_route('socialboost/v1', '/getorderdetails', array(
            'methods'                   => 'POST',
            'callback'                  =>  array($this, 'getorderdetails'),
            'permission_callback'       =>  array($this, 'check_api_permission'),
            'args'                      =>  array()
        ));
        register_rest_route('socialboost/v1', '/setSettings', array(
            array(
                'methods'               =>  'POST',
                'callback'              =>  array($this, 'set_settings'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));

        register_rest_route('socialboost/v1', '/createcustomer', array(
            'methods'                   => 'POST',
            'callback'                  =>  array($this, 'createcustomer'),
            'permission_callback'       =>  array($this, 'check_api_permission'),
            'args'                      =>  array()
        ));

        register_rest_route('socialboost/v1', '/getproductcategories', array(
            'methods' => 'POST',
            'callback'=>  array($this, 'getproductcategories'),
            'permission_callback'   =>  array($this, 'check_api_permission'),
            'args'                  =>  array()
        ));

        register_rest_route('socialboost/v1', '/createCouponSB', array(
            array(
                'methods'               =>  'POST',
                'callback'              =>  array($this, 'sb_create_coupon'),
                'permission_callback'   =>  array($this, 'check_api_permission'),
                'args'                  =>  array()
            )
        ));
    }

    public function check_api_permission($request)
    {
        if (strpos($request->get_header('user_agent'), 'Appsmav') === false) {
            return false;
        } else {
            $payload = get_option('socialboost_payload', 0);
            $post_payload = sanitize_text_field($_POST['payload']);

            if (empty($_POST['payload']) || $payload != $post_payload) {
                return false;
            }
        }
        return true;
    }

    public function check_api_permission_lite($request)
    {
        if (strpos($request->get_header('user_agent'), 'Appsmav') === false) {
            return false;
        }
        return true;
    }

    public function getversion($request)
    {
        try {
            $version = '';
            if (class_exists('Social_Boost')) {
                $version = Social_Boost::$_plugin_version;
            }

            $data = array('error' => 0, 'plugin_version' => $version);
        } catch (Exception $e) {
            $data['error'] = 1;
            $data['msg'] = $e->getMessage();
        }
        return new WP_REST_Response($data, 200);
    }

    public function getorderdetails()
    {
        $data = array();
        try
        {
            $order_id = sanitize_text_field($_POST['order_id']);
            $order = new WC_Order($order_id);
            if (empty($order)) {
                throw new Exception("Order not found");
            }

            $data = array(
                'error' => 0,
                'order' => $order->get_data()
            );
        }
        catch(Exception $e)
        {
            $data['error'] = 1;
            $data['msg']   = $e->getMessage();
        }

        $data['plugin_version'] = Social_Boost::$_plugin_version;

        return new WP_REST_Response($data, 200);
    }

    public function get_page($request)
    {
        $data = array('error' => 0);

        try
        {
            if (empty($_POST['id'])) {
                throw new Exception('Invalid Page');
            }

            $id_post = sanitize_text_field($_POST['id']);
            if (!get_post_status($id_post)) {
                throw new Exception('Invalid Page');
            }

            $page = get_post($id_post);
            if(is_wp_error($page)) {
                throw new Exception('cannot_update_page'. $page->get_error_message());
            }

            $data['error']	= 0;
            $data['id'] 	= $page->ID;
            $data['url'] 	= get_permalink($id);
            $data['is_embed_landing_url'] = get_post_meta(get_the_ID(), 'is_embed_landing_url');
            $data['msg']	= 'Success';
        }
        catch(Exception $e)
        {
            $data['error']          =   1;
            $data['error_message']  =   $e->getMessage();
        }

        return new WP_REST_Response($data, 200);
    }

    public function add_page($request)
    {
        $data   =   array('error' => 0);

        try
        {
            if (empty($_POST['title'])) {
                throw new Exception('Invalid Title');
            }

            if (empty($_POST['content'])) {
                throw new Exception('Invalid Content');
            }

            $new_page = array(
                'post_title'   => sanitize_text_field($_POST['title']),
                'post_content' => sanitize_text_field($_POST['content']),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'meta_input'   => array(
                    'is_embed_landing_url' => 1
                )
            );

            $id = wp_insert_post( $new_page, $wp_error = false );

            if(is_wp_error($id)) {
                throw new Exception('cannot_create_page'. $id->get_error_message());
            }

            $data['error'] = 0;
            $data['id']    = $id;
            $data['url']   = get_permalink($id);
            $data['msg']   = 'Success';
        }
        catch(Exception $e)
        {
            $data['error']         = 1;
            $data['error_message'] = $e->getMessage();
        }

        return new WP_REST_Response($data, 200);
    }

    public function edit_page($request)
    {
        $data = array('error' => 0);

        try
        {
            if (isset($_POST['title']) && empty($_POST['title']) && !isset($_POST['publish'])) {
                throw new Exception('Invalid Title');
            }

            if (empty($_POST['id'])) {
                throw new Exception('Invalid Page');
            }

            $params['ID'] = sanitize_text_field($_POST['id']);
            if (!get_post_status($params['ID'])) {
                throw new Exception('Invalid Page');
            }

            if (isset($_POST['publish']))
            {
                $publish_status = sanitize_text_field($_POST['publish']);
                $params['post_status'] = ($publish_status == 1) ? 'publish' : 'draft';
                update_post_meta($params['ID'], 'is_embed_landing_url', $publish_status);
            }
            else
            {
                $params['post_title'] = sanitize_text_field($_POST['title']);
            }

            $id = wp_update_post( $params, $wp_error = true );

            if(is_wp_error($id))
                throw new Exception('cannot_update_page'. $id->get_error_message());

            $page_info = get_post($id);

            $data['error'] = 0;
            $data['id']    = $page_info->ID;
            $data['title'] = $page_info->post_title;
            $data['url']   = get_permalink($page_info->ID);
            $data['msg']   = 'Success';
        }
        catch(Exception $e)
        {
            $data['error']         = 1;
            $data['error_message'] = $e->getMessage();
        }

        return new WP_REST_Response($data, 200);
    }

    public function delete_page($request)
    {
        $data   =   array('error' => 0);

        try
        {
            if (empty($_POST['id'])) {
                throw new Exception('Invalid Page');
            }

            $id_page = sanitize_text_field($_POST['id']);
            if (!get_post_status($id_page)) {
                throw new Exception('Invalid Page');
            }

            if(!wp_delete_post($id_page, true)) {
                throw new Exception('cannot_delete_page');
            }

            $data['error'] = 0;
            $data['msg']   = 'Success';
        }
        catch(Exception $e)
        {
            $data['error']         = 1;
            $data['error_message'] = $e->getMessage();
        }

        return new WP_REST_Response($data, 200);
    }

    public function reset_installation($request)
    {
        try
        {
            $data['error'] = 0;

            // Reset flags to show login screen
            update_option('socialboost_register', 3);

            $data['msg'] = 'yes';
        }
        catch(Exception $e) {
            $data['error'] = 1;
            $data['msg']   = $e->getMessage();
        }

        return new WP_REST_Response($data, 200);
    }

    public function set_settings($request)
    {
        $data = array('error' => 0);

        try
        {
            if(empty($_POST['data']))
                throw new Exception('No config to set');

            if(empty($_POST['data']) || !is_array($_POST['data']))
                throw new Exception('Invalid config to set');

            $config     = $_POST['data'];
            $app_config = sb_get_app_config();

            if(!empty($app_config) && is_array($app_config))
                $config = array_merge($app_config, $config);

            $config['date_updated'] = time();

            if(sb_set_app_config($config) == FALSE)
                throw new Exception(__('Config file is not created'));

            //$data['config'] =   $config;
            $data['msg'] = __('Settings updated successfully');
        }
        catch(Exception $e)
        {
            $data['error'] = 1;
            $data['msg']   = $e->getMessage();
        }

        $data['plugin_version'] = Social_Boost::$_plugin_version;

        return new WP_REST_Response($data, 200);
    }

    public function createcustomer()
    {
        $data = array();
        try
        {
            $email = sanitize_text_field(trim($_POST['email']));
            $user_name = sanitize_text_field(trim($_POST['user_name']));
            $first_name = sanitize_text_field(trim($_POST['first_name']));
            $last_name = sanitize_text_field(trim($_POST['last_name']));

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email address");
            }

            if (empty($user_name)) {
                throw new Exception("Invalid user name");
            }

            $user = get_user_by('email', $email);
            if (!empty($user)) {
                throw new Exception("Email id already exists");
            }

            $user = get_user_by('login', $user_name);
            if (!empty($user)) {
                $user_name = $email;
                $user = get_user_by('login', $user_name);
                if (!empty($user)) {
                    throw new Exception("Username already exists");
                }
            }            

            $user_details = array(
                'user_email' => $email,
                'user_login' => $user_name,
                'first_name' => $first_name,
                'last_name' => $last_name
            );

            $user_id = wp_insert_user($user_details);
            if (is_wp_error($user_id)) {
                throw new Exception($user_id->get_error_message());
            }

            $user = get_user_by('id', $user_id);
            if (!empty($user) && !empty($user->data) && !empty($user->data->user_email) && $user->data->user_email == $email) {
                $data = array(
                    'error' => 0,
                    'id' => $user_id,
                );
            } else {
                throw new Exception("User creation failed");
            }
        }
        catch(Exception $e)
        {
            $data['error'] = 1;
            $data['msg']   = $e->getMessage();
        }

        $data['plugin_version'] = Social_Boost::$_plugin_version;

        $result = new WP_REST_Response($data, 200);

        // Set headers.
        $result->set_headers(array('Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0'));
        return $result;
    }

    public function getproductcategories()
    {
        $data = array();
        try
        {
            $cat_args = array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => false,
            );
            $categories = get_terms( 'product_cat', $cat_args );

            $data = array(
                'error' => 0,
                'product_categories' => !empty($categories) ? $categories : array()
            );
        }
        catch(Exception $e)
        {
            $data['error'] = 1;
            $data['msg']   = "Something went wrong";
        }

        $data['plugin_version'] = Social_Boost::$_plugin_version;

        $result = new WP_REST_Response($data, 200);
        // Set headers.
        $result->set_headers(array('Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0'));
        return $result;
    }

    public function sb_create_coupon($request)
    {
        try
        {
            global $wp_rest_server;
            global $wpdb;

            if(is_admin())
                throw new Exception('Admin user');

            $useragent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            if(!strpos($useragent, 'Appsmav'))
                throw new Exception('Invalid access');

			if (!has_action('rest_api_init')){
				$wp_rest_server = new WP_REST_Server();
				do_action('rest_api_init', $wp_rest_server);
			}

            add_filter('wpss_misc_form_spam_check_bypass', FALSE, 10);

            if(empty($_POST['cpn_type']) || empty($_POST['sbcpn_code']))
                throw new Exception('InvalidRequest1');

            if(!isset($_POST['cpn_value']) || !isset($_POST['free_ship']) || !isset($_POST['min_order']) || !isset($_POST['cpn_descp']))
                throw new Exception('InvalidRequest2');

            if(!class_exists('WC_Integration'))
                throw new Exception('WooPluginNotFound');

            if(!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
                throw new Exception('PluginDeactivated');

            // Validate coupon types
            if(!in_array(wc_clean($_POST['cpn_type']), array_keys(wc_get_coupon_types())))
                throw new WC_CLI_Exception('woocommerce_cli_invalid_coupon_type', sprintf(__('Invalid coupon type - the coupon type must be any of these: %s', 'woocommerce'), implode(', ', array_keys(wc_get_coupon_types()))));

            $assoc_args = array(
                'code' => sanitize_text_field($_POST['sbcpn_code']),
                'type' => sanitize_text_field($_POST['cpn_type']),
                'amount' => empty($_POST['cpn_value']) ? 0 : sanitize_text_field($_POST['cpn_value']),
                'individual_use' => true,
                'usage_limit' => 1,
                'usage_limit_per_user' => 1,
                'enable_free_shipping' => sanitize_text_field($_POST['free_ship']),
                'minimum_amount' => sanitize_text_field($_POST['min_order']),
                'product_ids' => !empty($_POST['product_ids']) ? sanitize_text_field($_POST['product_ids']) : '',
                'exclude_product_ids' => !empty($_POST['exclude_product_ids']) ? sanitize_text_field($_POST['exclude_product_ids']) : '',
                'product_category_ids' => !empty($_POST['product_category_ids']) ? sanitize_text_field($_POST['product_category_ids']) : '',
                'exclude_product_category_ids' => !empty($_POST['exclude_product_category_ids']) ? sanitize_text_field($_POST['exclude_product_category_ids']) : '',
                'maximum_amount' => !empty($_POST['maximum_amount']) ? sanitize_text_field($_POST['maximum_amount']) : '',
                'exclude_sale_items' => !empty($_POST['exclude_sale_items']) ? sanitize_text_field($_POST['exclude_sale_items']) : '',
                'customer_emails' => !empty($_POST['email_restrictions']) ? sanitize_text_field($_POST['email_restrictions']) : '',
                'description' => sanitize_text_field($_POST['cpn_descp']),
                'expiry_date' => empty($_POST['expiry_date']) ? '' : sanitize_text_field($_POST['expiry_date'])
            );

            $assoc_args['product_ids'] = !empty($assoc_args['product_ids']) ? json_decode($assoc_args['product_ids'], true) : [];
            $assoc_args['exclude_product_ids'] = !empty($assoc_args['exclude_product_ids']) ? json_decode($assoc_args['exclude_product_ids'], true) : [];
            $assoc_args['product_category_ids'] = !empty($assoc_args['product_category_ids']) ? json_decode($assoc_args['product_category_ids'], true) : [];
            $assoc_args['exclude_product_category_ids'] = !empty($assoc_args['exclude_product_category_ids']) ? json_decode($assoc_args['exclude_product_category_ids'], true) : [];
            $assoc_args['customer_emails'] = !empty($assoc_args['customer_emails']) ? json_decode(stripslashes($assoc_args['customer_emails']), true) : [];

            if(!empty($_POST['usage_limit_per_user']))
                $assoc_args['usage_limit'] = '';

            if(get_option('woocommerce_enable_coupons') !== 'yes')
                update_option('woocommerce_enable_coupons', 'yes');

            $coupon_code = apply_filters('woocommerce_coupon_code', $assoc_args['code']);

            // Check for duplicate coupon codes.
            $coupon_found = $wpdb->get_var($wpdb->prepare("
                    SELECT $wpdb->posts.ID
                    FROM $wpdb->posts
                    WHERE $wpdb->posts.post_type = 'shop_coupon'
                    AND $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_title = '%s'
             ", $coupon_code));

            if($coupon_found)
                throw new Exception('DuplicateCoupon');

            $url = Social_Boost::$_callback_url . Social_Boost::$_api_version . 'wooCpnValidate';

            $app_id = get_option('socialboost_appid');
            $payload = get_option('socialboost_payload', 0);

            if(empty($app_id) || empty($payload))
                throw new Exception('IntegrationMissing');

            $param = array(
                'id_coupon' => sanitize_text_field( $_POST['id_coupon']),
                'sbcpn_code' => sanitize_text_field( $_POST['sbcpn_code']),
                'hash' => sanitize_text_field( $_POST['hash']),
                'amount' => sanitize_text_field( $_POST['cpn_value']),
                'type' => sanitize_text_field( $_POST['cpn_type']),
                'minimum_amount' => sanitize_text_field( $_POST['min_order']),
                'id_site' => $app_id,
                'payload' => $payload,
                'plugin_version' => Social_Boost::$_plugin_version
            );

            $httpObj = (new SocialHttpRequestHandler)
                            ->setPostData($param)
                            ->exec($url);
            $res = $httpObj->getResponse();
            if(!empty($res))
                $res = json_decode($res, true);

            if(empty($res) || !empty($res['error']))
                throw new Exception('VerificationFailed');

            $defaults = array(
                'type' => 'fixed_cart',
                'amount' => 0,
                'individual_use' => false,
                'product_ids' => array(),
                'exclude_product_ids' => array(),
                'usage_limit' => '',
                'usage_limit_per_user' => '',
                'limit_usage_to_x_items' => '',
                'usage_count' => '',
                'expiry_date' => '',
                'enable_free_shipping' => false,
                'product_category_ids' => array(),
                'exclude_product_category_ids' => array(),
                'exclude_sale_items' => false,
                'minimum_amount' => '',
                'maximum_amount' => '',
                'customer_emails' => array(),
                'description' => ''
            );

            $coupon_data = wp_parse_args($assoc_args, $defaults);

            $new_coupon = array(
                'post_title' => $coupon_code,
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
                'post_type' => 'shop_coupon',
                'post_excerpt' => $coupon_data['description']
            );

            $id = wp_insert_post($new_coupon, $wp_error = false);

            if(is_wp_error($id))
                throw new WC_CLI_Exception('woocommerce_cli_cannot_create_coupon', $id->get_error_message());

            // Set coupon meta
            update_post_meta($id, 'discount_type', $coupon_data['type']);
            update_post_meta($id, 'coupon_amount', wc_format_decimal($coupon_data['amount']));
            update_post_meta($id, 'individual_use', (!empty($coupon_data['individual_use']) ) ? 'yes' : 'no' );
            update_post_meta($id, 'product_ids', implode(',', array_filter(array_map('intval', $coupon_data['product_ids']))));
            update_post_meta($id, 'exclude_product_ids', implode(',', array_filter(array_map('intval', $coupon_data['exclude_product_ids']))));
            update_post_meta($id, 'usage_limit', absint($coupon_data['usage_limit']));
            update_post_meta($id, 'usage_limit_per_user', absint($coupon_data['usage_limit_per_user']));
            update_post_meta($id, 'limit_usage_to_x_items', absint($coupon_data['limit_usage_to_x_items']));
            update_post_meta($id, 'usage_count', absint($coupon_data['usage_count']));

            if('' !== wc_clean($coupon_data['expiry_date']))
                $coupon_data['expiry_date'] = date('Y-m-d', strtotime($coupon_data['expiry_date']));

            update_post_meta($id, 'expiry_date', wc_clean($coupon_data['expiry_date']));
            update_post_meta($id, 'free_shipping', (!empty($coupon_data['enable_free_shipping']) ) ? 'yes' : 'no' );
            update_post_meta($id, 'product_categories', array_filter(array_map('intval', $coupon_data['product_category_ids'])));
            update_post_meta($id, 'exclude_product_categories', array_filter(array_map('intval', $coupon_data['exclude_product_category_ids'])));
            update_post_meta($id, 'exclude_sale_items', (!empty($coupon_data['exclude_sale_items']) ) ? 'yes' : 'no' );
            update_post_meta($id, 'minimum_amount', wc_format_decimal($coupon_data['minimum_amount']));
            update_post_meta($id, 'maximum_amount', wc_format_decimal($coupon_data['maximum_amount']));
            update_post_meta($id, 'customer_email', array_filter(array_map('sanitize_email', $coupon_data['customer_emails'])));

            if (!empty($_POST['custom_attributes']))
            {
                $custom_attributes = stripslashes(sanitize_text_field($_POST['custom_attributes']));
                $custom_attributes = json_decode($custom_attributes, true);
                if (!empty($custom_attributes) && is_array($custom_attributes))
                {
                    foreach ($custom_attributes as $prop_name => $prop_value) {
                        update_post_meta($id, $prop_name, wc_clean($prop_value));
                    }
                }
            }

            $data['error'] = 0;
            $data['code'] = $coupon_code;
            $data['id'] = $id;
            $data['msg'] = 'Success';
        }
        catch(Exception $ex)
        {
            $data['error'] = 1;
            $data['msg'] = $ex->getMessage();
        }

        $result = new WP_REST_Response($data, 200);
        // Set headers.
        $result->set_headers(array('Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0'));
        return $result;
    }


}
