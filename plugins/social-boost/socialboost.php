<?php
/**
 * @package Social Boost by Appsmav
 * @version 3.2.19
 */
/*
 Plugin Name: Social Boost
 Plugin URI: http://appsmav.com
 Description: Get leads & customers. Boost social media followers.
 Version: 3.2.19
 Author: Appsmav
 Author URI: http://appsmav.com
 License: GPL2
*/
/*  Copyright 2015  Appsmav  (email : support@appsmav.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('SB_PLUGIN_BASE_PATH', dirname(__FILE__));

class Social_Boost
{
    public static $_plugin_version  = '3.2.19';
    public static $_callback_url = 'https://social.appsmav.com/';
    public static $_api_version  = 'api/v1/';
    protected static $_api_url      = 'https://clients.appsmav.com/api_v1.php';
    protected static $_c_sdk_url    = '//cdn.appsmav.com/sb/assets/js/widget-sdk.js?v=3.2.19';

    /**
     * Construct the plugin object
     */
    public function __construct()
    {
        // register actions
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'add_menu'));
        add_action('wp_footer', array( &$this,'sb_widget') );
        add_action('admin_enqueue_scripts', array(&$this,'sb_font_styles'));
        add_action('parse_request', array(&$this,'apmsb_create_discount'));
        add_action('save_post', array(&$this,'sb_save_post'), 10, 3);
        add_action('delete_post', array(&$this,'sb_delete_post'), 10, 3);
        // register actions for Blog Comments
        add_action('plugins_loaded', array( &$this, 'commenthook_init' ) );

        //Create new page and embed campaign
        add_action('rest_api_init', array($this, 'register_rest_routes'), 10);
    } // END public function __construct

    /**
     * Activate the plugin
     */
    public static function activate()
    {
        try {
            // Do nothing
            update_option( 'socialboost_register', 2 );
        } catch (Exception $ex) {}
    }

    /**
     * Deactivate the plugin
     */
    public static function deactivate()
    {
        // Delete the stored informations
        delete_option('socialboost_plgtyp');

        // Order related hooks for Purchase campaign
        remove_action('woocommerce_checkout_order_processed', array('Social_Boost', 'send_connect_init'));
        remove_action('woocommerce_order_status_changed', array('Social_Boost', 'send_status_init'));
        remove_action('woocommerce_order_refunded', array('Social_Boost', 'send_refund_init'));
        remove_action('before_delete_post', array('Social_Boost', 'send_refund_delete_post_init'));

        // Blog Comments
        remove_action('comment_post', array('Social_Boost','send_comment_to_appsmav'));
        remove_action('init', array('Social_Boost', 'init_page_load'));

        // Deactivate shop
        $id_shop = get_option('socialboost_shop_id', 0);
        $id_site = get_option('socialboost_appid', 0);
        $payload = get_option('socialboost_payload', 0 );

        $plugin_type = 'WP';
        if (class_exists('WC_Integration')) {
            $plugin_type = 'WOO';
        }

        $param = array('app' => 'grvlsw', 'plugin_type' => $plugin_type, 'status' => 'deactivate', 'id_shop' => $id_shop, 'id_site' => $id_site, 'payload'=>$payload);
        $url = self::$_callback_url.self::$_api_version.'pluginStatus';

        wp_remote_post($url, array('body' => $param));

    } // END public static function deactivate

    public static function social_boost_show_func($atts)
    {
        $id = isset($atts['id'])? trim($atts['id']) : '';
		$patternAlphaNum = '/^[a-zA-Z0-9_]+$/';
		if (empty($id) || !preg_match($patternAlphaNum, $id))
			return '';

        $url = self::$_callback_url . 'promo/' . $id;
        if (isset($atts['type']) && $atts['type'] == 'link') {
            $content = '<a class="socialboost-widget sb-widget" href="' . $url . '">Rewards</a>';
        } else {
            $content = '<div class="SBEmbedContainer"><iframe data-sbclass="sb_iframe_widget" class="sb_iframe_widget" width="100%" height="700px" src="' . $url . '" frameborder="0" allow="clipboard-read; clipboard-write">Rewards</iframe></div>
			<script type="text/javascript">
                        try{if("URLSearchParams"in window){var mavtoken,params={},searchParams=new URLSearchParams(window.location.search);searchParams.has("id_ref")&&(params.id_ref=searchParams.get("id_ref"),searchParams.has("mavtoken")&&(params.mavtoken=searchParams.get("mavtoken"))),"undefined"==typeof Storage||void 0!==(mavtoken=localStorage.SBmavtoken)&&""!=mavtoken&&null!=mavtoken&&"null"!=mavtoken&&"NULL"!=mavtoken&&(params.mavtoken=mavtoken);for(var app_url,elems=document.querySelectorAll("[data-sbclass]"),sParams=new URLSearchParams(params),i=0;i<elems.length;i++)elems[i].id="ec_iframe_"+i,0<Object.keys(params).length&&(app_url=elems[i].src,app_url+=(-1==app_url.indexOf("?")?"?":"&")+sParams,elems[i].src=app_url),void 0!==elems[i].className&&""!=elems[i].className||(elems[i].className="sb_iframe_widget")}}catch(a){}
                        </script>';
        }

        return $content;
    }

    /**
     * hook into WP's admin_init action hook
     */
    public function admin_init()
    {
        // Set up the settings for this plugin
        $this->init_settings();
        // Possibly do additional admin_init tasks
    } // END public static function activate

    /**
     * Initialize some custom settings
     */
    public function init_settings()
    {
        // register the settings for this plugin
        add_action( 'wp_ajax_create_grvlsw_account', array(&$this,'sb_ajax_create_grvlsw_account' ));
        add_action( 'wp_ajax_check_grvlsw_settings', array(&$this,'sb_ajax_check_grvlsw_settings' ));
        add_action( 'wp_ajax_code_grvlsw_settings', array(&$this,'sb_ajax_code_grvlsw_settings' ));
        add_action( 'wp_ajax_sendcode_grvlsw_settings', array(&$this,'sb_ajax_sendcode_grvlsw_settings' ));
        add_action( 'wp_ajax_check_grvlsw_login', array(&$this,'sb_ajax_check_grvlsw_login' ));

    } // END public function init_custom_settings()

    function sb_widget()
    {
        $app_id = get_option('socialboost_appid', 0);

        if(empty($app_id))
            return false;

        $id_site        = get_option('socialboost_appid');
        $arr['id_site'] = $id_site;
        $arr['error']   = 0;
        $cid            = $cemail = $cname = $first_name = $last_name = '';

        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $cid        = $current_user->ID;
            $cemail     = $current_user->user_email;
            $cname      = $current_user->display_name;
            $first_name = $current_user->user_firstname;
            $last_name  = $current_user->user_lastname;
        }

        $orderConfig = '';
        if (class_exists('WC_Integration')) {
            if (is_order_received_page()) {
                $orderConfig = ', is_thankyou_page: "true"';
                $order_id = self::_getOrderID();
                if (!empty($order_id)) {
                    $orderConfig .= ', order_id: "'.$order_id.'"';
                }
            }
        }

        echo '<script>var AMSBConfig = {user : {name : "'.$cname.'", first_name : "'.$first_name.'", last_name : "'.$last_name.'", email : "'.$cemail.'", id : "'.$cid.'", country : ""'.$orderConfig.'}, site : {id : "'.$id_site.'", domain : "'.get_option('siteurl').'", platform : "wp"}};
        (function(d, s, id) {
                var js, amjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id; js.async = true;
                js.src = "'.self::$_c_sdk_url.'";
                amjs.parentNode.insertBefore(js, amjs);
        }(document, "script", "socialboost-sdk"));
        </script>';

        return;
    }

    function _getOrderID()
    {
        try
        {
            global $wp;
            $order_id = '';
            if (isset($wp->query_vars['order-received']) && !empty($wp->query_vars['order-received'])) {
                $order_id = $wp->query_vars['order-received'];
            } else if(isset($_GET['view-order']) && !empty($_GET['view-order'])) {
                //check if on view-order page and get parameter is available
                $order_id = $_GET['view-order'];
            } else if(isset($_GET['order-received']) && !empty($_GET['order-received'])) {
                //check if on view order-received page and get parameter is available
                $order_id = $_GET['order-received'];
            } elseif (isset($_GET['key']) && !empty($_GET['key']) && version_compare( WC_VERSION, '5.9', '>=' )) {
                $order_id = wc_get_order_id_by_order_key( $_GET['key'] );
            } else {
                $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                $template_name = strpos($url,'/order-received/') === false ? '/view-order/' : '/order-received/';
                if (strpos($url,$template_name) !== false) {
                    $start = strpos($url,$template_name);
                    $first_part = substr($url, $start+strlen($template_name));
                    $order_id = substr($first_part, 0, strpos($first_part, '/'));
                }
            }
        }
        catch(Exception $e)
        { }

        return $order_id;
    }

    /**
     * add a menu
     */
    public function add_menu()
    {
        add_options_page('Social Boost Settings', 'Social Boost', 'manage_options', 'socialboost', array(&$this, 'plugin_settings_page'));
    } // END public function add_menu()

    public function sb_font_styles($hook)
    {
        if('settings_page_socialboost' != $hook)
            return;

        // register styles
        wp_register_style('bootstrap_css', plugins_url('/css/bootstrap-3.2.0.min.css', __FILE__) );
        wp_register_style('social_boost_css', plugins_url('/css/socialboost.css', __FILE__) );

        // enqueue styles
        wp_enqueue_style('bootstrap_css');
        wp_enqueue_style('social_boost_css');

        // enqueue scripts
        wp_enqueue_script( 'bootstrap_script', plugins_url( '/js/bootstrap.min.js',__FILE__ ),array(), self::$_plugin_version, true );
        wp_enqueue_script( 'jquery_validity_script', plugins_url( '/js/jquery.validity.js',__FILE__ ),array(), self::$_plugin_version, true );
        wp_enqueue_script( 'social_boost_script', plugins_url( '/js/socialboost.js',__FILE__ ),array(), self::$_plugin_version, true );
    }

    /**
     * Menu Callback
     */
    public function plugin_settings_page()
    {
        if(!current_user_can('manage_options'))
            wp_die(__('You do not have sufficient permissions to access this page.'));

        // Render the settings template
        $frame_url	= 'about:blank';
        if ( class_exists( 'WC_Integration' )  &&  get_option('socialboost_plgtyp', 0 ) != 'WOO')
            update_option( 'socialboost_register', 2 );
        else if(!class_exists( 'WC_Integration' )  && get_option('socialboost_plgtyp', 0 ) == 'WOO')
            update_option( 'socialboost_register', 2 );

        if(get_option('socialboost_register', 0 ) == 1)
        {
            $arr['id_shop']     =   get_option('socialboost_shop_id', 0 );
            $arr['admin_email'] =   get_option('socialboost_admin_email', '');
            $arr['payload']     =   get_option('socialboost_payload', 0 );
            $frame_url          =   self::$_callback_url.'autologin?id_shop='.$arr['id_shop'].'&admin_email='.urlencode($arr['admin_email']).'&payload='.$arr['payload'].'&autoredirect=auto';
        }

        include(sprintf("%s/templates/settings.php", dirname(__FILE__)));

    } // END public function plugin_settings_page()

    public function register_rest_routes()
    {
        try
        {
            $route = new Socialboost_API();
            $route->register_apis();
        } catch (Exception $ex) {

        }
    }

    function sb_save_post($post_id, $post, $update) {
        try
        {
            // Only want to set if this is a old post!
            if (!$update || 'page' !== $post->post_type) {
                return;
            }

            $is_embed_landing_url = get_post_meta($post->ID, 'is_embed_landing_url', true);
            if ($is_embed_landing_url != 1) {
                return;
            }

            $url     = self::$_callback_url . self::$_api_version . 'wooInstallTabChange';
            $app_id  = get_option('socialboost_appid');
            $payload = get_option('socialboost_payload', 0);

            if(empty($app_id) || empty($payload)) {
                throw new Exception('IntegrationMissing');
            }

            $param = array(
                'id_site'   => $app_id,
                'payload'   => $payload,
                'id'        => $post->ID,
                'title'     => $post->post_title,
                'url'       => get_permalink($post->ID),
                'publish'   => $post->post_status == 'publish' ? 1 : 0,
                'is_embed_landing_url' => $is_embed_landing_url
            );

            $res = self::_curlResp($param, $url);
            if(empty($res) || $res['error'] == 1) {
                throw new Exception('VerificationFailed');
            }
        }
        catch (Exception $ex)
        {
            $resp['error'] = 1;
            $resp['msg']   = $ex->getMessage();
        }
    }

    function sb_delete_post($post_id)
    {
        try
        {
            $is_embed_landing_url = get_post_meta($post_id, 'is_embed_landing_url', true);
            if ($is_embed_landing_url != 1) {
                return;
            }

            $url     = self::$_callback_url . self::$_api_version . 'wooInstallTabDelete';
            $app_id  = get_option('socialboost_appid');
            $payload = get_option('socialboost_payload', 0);

            if(empty($app_id) || empty($payload)) {
                throw new Exception('IntegrationMissing');
            }

            update_post_meta($post_id, 'is_embed_landing_url', 0);

            $param = array(
                'id_site' => $app_id,
                'payload' => $payload,
                'id'      => $post_id
            );

            $res = self::_curlResp($param, $url);
            if(empty($res) || $res['error'] == 1)
                throw new Exception('VerificationFailed');
        }
        catch (Exception $ex)
        {
            $resp['error'] = 1;
            $resp['msg']   = $ex->getMessage();
        }
    }

    public function apmsb_create_discount()
    {
        global $wpdb;

        try
        {
            if(is_admin())
                return;

            $useragent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            if( ! strpos($useragent,'Appsmav'))
                return;

            //user email verification
            if( ! empty($_POST['verify_user']))
            {
                $email         = sanitize_email( $_POST['verify_user'] );
                $user          = get_user_by('email', $email );
                $resp['error'] = 1;
                $resp['msg']   = 'No User Exist';

                if(!empty($user))
                {
                    $resp['error'] = 0;
                    $resp['msg']   = 'User Exist';
                    $resp['name']  = $user->first_name . ' ' . $user->last_name;
                    $resp['id']    = $user->ID ;
                }

                header("Content-Type: application/json; charset=UTF-8");
                die(json_encode($resp));
            }

            if(empty($_POST['cpn_type']) || empty($_POST['sbcpn_code']))
                return;

            if( ! isset($_POST['cpn_value']) || ! isset($_POST['free_ship']) || ! isset($_POST['min_order']) || ! isset($_POST['cpn_descp']))
                throw new Exception('InvalidRequest2');

            if(empty($_POST['id_coupon']) || empty($_POST['hash']))
                throw new Exception('InvalidRequest');

            if( ! class_exists( 'WC_Integration'))
                throw new Exception('WooPluginNotFound');

            if( ! in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins'))))
                throw new Exception('PluginDeactivated');

                // Validate coupon types
            if ( ! in_array( wc_clean( $_POST['cpn_type'] ), array_keys( wc_get_coupon_types())))
                throw new Exception( 'woocommerce_cli_invalid_coupon_type'.sprintf( __( 'Invalid coupon type - the coupon type must be any of these: %s', 'woocommerce' ), implode( ', ', array_keys( wc_get_coupon_types() ) ) ) );

            $assoc_args = array(
                'code'                    => sanitize_text_field($_POST['sbcpn_code']),
                'type'                    => sanitize_text_field($_POST['cpn_type']),
                'amount'                  => empty($_POST['cpn_value']) ? 0 : sanitize_text_field($_POST['cpn_value']),
                'individual_use'          => true,
                'usage_limit'             => 1,
                'usage_limit_per_user'    => 1,
                'enable_free_shipping'    => sanitize_text_field($_POST['free_ship']),
                'minimum_amount'          => sanitize_text_field($_POST['min_order']),
                'description'             => sanitize_text_field($_POST['cpn_descp'])
            );

            if(!empty($_POST['usage_limit_per_user']))
                $assoc_args['usage_limit']  =   '';

            if(get_option( 'woocommerce_enable_coupons' )	!== 'yes')
                update_option( 'woocommerce_enable_coupons', 'yes' );

            $coupon_code    =   apply_filters('woocommerce_coupon_code', $assoc_args['code']);

            // Check for duplicate coupon codes.
            $coupon_found = $wpdb->get_var( $wpdb->prepare( "
                    SELECT $wpdb->posts.ID
                    FROM $wpdb->posts
                    WHERE $wpdb->posts.post_type = 'shop_coupon'
                    AND $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_title = '%s'
             ", $coupon_code ) );

            if($coupon_found)
                throw new Exception('DuplicateCoupon');

            $url        =   self::$_callback_url . self::$_api_version . 'wooCpnValidate';
            $app_id     =   get_option('socialboost_appid');
            $payload    =   get_option('socialboost_payload', 0);

            if(empty($app_id) || empty($payload))
                throw new Exception('IntegrationMissing');

            $param      =   array(
                'id_coupon'  => sanitize_text_field($_POST['id_coupon']),
                'sbcpn_code' => sanitize_text_field($_POST['sbcpn_code']),
                'hash'       => sanitize_text_field($_POST['hash']),
                'amount'     => sanitize_text_field($_POST['cpn_value']),
                'type'       => sanitize_text_field($_POST['cpn_type']),
                'minimum_amount' => sanitize_text_field($_POST['min_order']),
                'id_site'    => $app_id,
                'payload'    => $payload,
            );

            $response = wp_remote_post($url, array('body' => $param, 'timeout' => 10));
            if (!is_array($response) || empty($response['body']))
                throw new Exception('Verification request failed');

            $res = json_decode($response['body'], true);
            if(empty($res) || $res['error'] == 1)
                throw new Exception('Verification Failed');

            $defaults = array(
                    'type'                         => 'fixed_cart',
                    'amount'                       => 0,
                    'individual_use'               => false,
                    'product_ids'                  => array(),
                    'exclude_product_ids'          => array(),
                    'usage_limit'                  => '',
                    'usage_limit_per_user'         => '',
                    'limit_usage_to_x_items'       => '',
                    'usage_count'                  => '',
                    'expiry_date'                  => '',
                    'enable_free_shipping'         => false,
                    'product_category_ids'         => array(),
                    'exclude_product_category_ids' => array(),
                    'exclude_sale_items'           => false,
                    'minimum_amount'               => '',
                    'maximum_amount'               => '',
                    'customer_emails'              => array(),
                    'description'                  => ''
            );

            $coupon_data = wp_parse_args( $assoc_args, $defaults );

            $new_coupon = array(
                'post_title'   => $coupon_code,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
                'post_type'    => 'shop_coupon',
                'post_excerpt' => $coupon_data['description']
            );

            $id = wp_insert_post( $new_coupon, $wp_error = false );

            if(is_wp_error($id))
                throw new Exception('woocommerce_cli_cannot_create_coupon'. $id->get_error_message());

            // Set coupon meta
            update_post_meta( $id, 'discount_type', $coupon_data['type'] );
            update_post_meta( $id, 'coupon_amount', wc_format_decimal( $coupon_data['amount'] ) );
            update_post_meta( $id, 'individual_use', ( !empty( $coupon_data['individual_use'] ) ) ? 'yes' : 'no' );
            update_post_meta( $id, 'product_ids', implode( ',', array_filter( array_map( 'intval', $coupon_data['product_ids'] ) ) ) );
            update_post_meta( $id, 'exclude_product_ids', implode( ',', array_filter( array_map( 'intval', $coupon_data['exclude_product_ids'] ) ) ) );
            update_post_meta( $id, 'usage_limit', absint( $coupon_data['usage_limit'] ) );
            update_post_meta( $id, 'usage_limit_per_user', absint( $coupon_data['usage_limit_per_user'] ) );
            update_post_meta( $id, 'limit_usage_to_x_items', absint( $coupon_data['limit_usage_to_x_items'] ) );
            update_post_meta( $id, 'usage_count', absint( $coupon_data['usage_count'] ) );

            if('' !== wc_clean( $coupon_data['expiry_date'] ))
                   $coupon_data['expiry_date'] = date( 'Y-m-d', strtotime($coupon_data['expiry_date']));

            update_post_meta( $id, 'expiry_date',  wc_clean( $coupon_data['expiry_date'] ) );
            update_post_meta( $id, 'free_shipping', ( !empty( $coupon_data['enable_free_shipping'] ) ) ? 'yes' : 'no' );
            update_post_meta( $id, 'product_categories', array_filter( array_map( 'intval', $coupon_data['product_category_ids'] ) ) );
            update_post_meta( $id, 'exclude_product_categories', array_filter( array_map( 'intval', $coupon_data['exclude_product_category_ids'] ) ) );
            update_post_meta( $id, 'exclude_sale_items', ( !empty( $coupon_data['exclude_sale_items'] ) ) ? 'yes' : 'no' );
            update_post_meta( $id, 'minimum_amount', wc_format_decimal( $coupon_data['minimum_amount'] ) );
            update_post_meta( $id, 'maximum_amount', wc_format_decimal( $coupon_data['maximum_amount'] ) );
            update_post_meta( $id, 'customer_email', array_filter( array_map( 'sanitize_email', $coupon_data['customer_emails'] ) ) );

            $resp['error']	= 0;
            $resp['code'] 	= $coupon_code;
            $resp['id'] 	= $id;
            $resp['msg']	= 'Success';

        }
        catch (Exception $ex)
        {
            $resp['error']  =   1;
            $resp['msg']    =   $ex->getMessage();
        }

        header("Content-Type: application/json; charset=UTF-8");
        die(json_encode($resp));
    }

    public function sb_ajax_check_grvlsw_settings()
    {
        $raffd = isset($_POST['raffd']) ? sanitize_text_field($_POST['raffd']) : '';
        $email = get_option('socialboost_admin_email');

        if(isset($_POST['admin_email']))
            $email = sanitize_email($_POST['admin_email']);

        $param['email']    = $email;
        $param['raffd']    = $raffd;
        $param['shop_url'] = get_option('siteurl');
        $param["app"]      = 'grvlsw';
        $param["action"]   = 'verifyShopExists';
        $param['payload']  = get_option('socialboost_payload', 0);
        $param["version"]  = 'new';
        $param['plugin_type']  =   'WP';
        $param['campaign_only ']   = 1;
        if(class_exists('WC_Integration'))
            $param['plugin_type'] = 'WOO';

        $res = array();
        $res = self::_curlResp($param,self::$_api_url);

        if( !empty($res['is_shop']) && $res['is_shop'] == 1)
        {
            update_option( 'socialboost_admin_email', $email);
            update_option( 'socialboost_shop_id', $res['id_shop']);
            update_option('socialboost_appid', $res['id_site']);
            update_option( 'socialboost_payload', $res['pay_load']);
            update_option( 'socialboost_register', 1 );

            $res['sb_reg']    = 0;
            $res['frame_url'] = self::$_callback_url.'autologin?id_shop='.$res['id_shop'].'&admin_email='.urlencode($email).'&payload='.$res['pay_load'].'&autoredirect=auto';

            // Update WP plugin status
            $param = array('app' => 'grvlsw', 'plugin_type' => $param['plugin_type'], 'status' => 'activate', 'id_shop' => $res['id_shop'], 'id_site' => $res['id_site'], 'payload'=>$res['pay_load']);
            $url = self::$_callback_url.self::$_api_version.'pluginStatus';
            wp_remote_post($url, array('body' => $param));

        }
        else if (!empty($res['is_shop']) && $res['is_shop'] == 2)
        {
            $ip_info                = self::_getIPDetails();
            $current_user           = wp_get_current_user();

            $params                 = array();
            $params['action']       = 'createaccount';
            $params['firstname']    = $current_user->user_firstname;
            $params['lastname']     = $current_user->user_lastname;
            $params["companyname"]  = get_bloginfo('name');
            $params['address1']     = '***'; //Dummy
            $params['city']         = empty($ip_info['city']) ? '***' : $ip_info['city'];
            $params['state']        = empty($ip_info['region_name']) ? '***' : $ip_info['region_name'];
            $params['postcode']     = '1'; //Dummy;
            $params['country']      = empty($ip_info["country_code"]) ? 'US' : $ip_info["country_code"];
            $params['currency']     = ($params["country"] === 'AU') ? 3 : 1;
            $params['phonenumber']  = '1234567890'; //Dummy
            $params['notes']        = 'Wordpress';
            $params["app"]          = 'grvlsw';
            $params['email']        = $email;
            $params['raffd']        = $raffd;
            $params['url']          = get_option('siteurl');
            $params['name']         = get_bloginfo('name');
            $params['type']         = 'url';
            $params['plugin_type']  = 'WP';
            if (class_exists('WC_Integration')) {
                $params['plugin_type'] = 'WOO';
                $params['wp_login_url'] = get_permalink( get_option('woocommerce_myaccount_page_id') );
                $params['wp_registration_url'] = get_permalink( get_option('woocommerce_myaccount_page_id') );
            } else {
                $params['wp_login_url'] = wp_login_url();
                $params['wp_registration_url'] = wp_registration_url();
            }

            $params['shop_url']     = get_option('siteurl');
            $params['shop_name']    = get_option('blogname');
            $params['campaign_name']= 'REWARDS';
            $params['timezone']     = 'America/Chicago'; //Dummy $p['grappsmav_reg_timezone'];
            $params['date_format']  = 'd/m/Y'; //Dummy$p['grappsmav_reg_date_format'];
            $params['exclusion_period'] = 0;
            $params['login_url']        = get_option('siteurl');
            $params['payload']  = get_option('socialboost_payload', 0);
            $params['users_can_register']   = get_option('users_can_register', 0);

            $res = array();
            $res = self::_curlResp($params, self::$_api_url);

            if ($res['error'] == 0)
            {
                $res['sb_reg'] = 0;
                update_option('socialboost_shop_id', $res['id_shop']);
                update_option('socialboost_appid', $res['id_site']);
                update_option('socialboost_payload', $res['pay_load']);
                update_option('socialboost_admin_email', $email);
                update_option('socialboost_register', 1 );
                update_option('socialboost_plgtyp', $params['plugin_type']);

                $res['appid']     = $res['id_site'];
                $res['frame_url'] = self::$_callback_url.'autologin?id_shop='.$res['id_shop'].'&admin_email='.urlencode($params['email']).'&payload='.$res['pay_load'].'&autoredirect=auto';
            }
            else if ($res['error'] == 1)
                $res['sb_reg'] = 1;
            else if ($res['error'] == 2 || $res['error'] == 3)
            {
                update_option( 'socialboost_register', 3 );
                $res['sb_reg'] = 2;
            }
            else
                $res['sb_reg'] = 4;
        }
        else
        {
            $res['sb_reg'] = 1;
        }

        die(json_encode($res));
    }

    public function sb_ajax_check_grvlsw_login()
    {
        try
        {
            if(empty($_POST['socialboost_login_email']) || !filter_var($_POST['socialboost_login_email'], FILTER_VALIDATE_EMAIL))
                throw new Exception("Please enter valid email");

            if(empty($_POST['socialboost_login_pwd']))
                throw new Exception("Please enter password");

            $res = array();
            $params = array();
            $email                 = sanitize_email( $_POST['socialboost_login_email'] );
            $adminEmailTemp        = get_option('socialboost_admin_email');
            $adminEmail            = empty($adminEmailTemp) ? $email : $adminEmailTemp;
            $params["action"]      = 'login';
            $params["app"]         = 'grvlsw';
            $params['email']       = $email;
            $params['admin_email'] = $adminEmail;
            $params['password']    = sanitize_text_field( $_POST['socialboost_login_pwd'] );
            $params['shop_url']    = get_option('siteurl');

            $url = self::$_api_url;
            $response = wp_remote_post($url, array('body' => $params, 'timeout' => 10));
            if (!is_array($response) || empty($response['body']))
                throw new Exception("Invalid Email / Password.");

            $resCurl = json_decode($response['body'], true);

            if ($resCurl['error'] == 0)
            {
                $plugin_type = 'WP';
                if (class_exists('WC_Integration')) {
                    $plugin_type = 'WOO';
                }

                update_option( 'socialboost_admin_email', $adminEmail);
                update_option( 'socialboost_shop_id', $resCurl['id_shop']);
                update_option('socialboost_appid', $resCurl['id_site']);
                update_option( 'socialboost_payload', $resCurl['pay_load']);
                update_option( 'socialboost_register', 1 );

                $res['error']     = 0;
                $res['frame_url'] = self::$_callback_url.'autologin?id_shop='.$resCurl['id_shop'].'&admin_email='.urlencode($adminEmail).'&payload='.$resCurl['pay_load'].'&autoredirect=auto';

                // Update WP plugin status
                $param = array('app' => 'grvlsw', 'plugin_type' => $plugin_type, 'status' => 'activate', 'id_shop' => $resCurl['id_shop'], 'id_site' => $resCurl['id_site'], 'payload'=>$resCurl['pay_load']);
                $url = self::$_callback_url.self::$_api_version.'pluginStatus';
                wp_remote_post($url, array('body' => $param));

            }
            else
            {
                $res['error']   = 1;
                $res['message'] = (!empty($resCurl['message'])) ? $resCurl['message'] : "Invalid Email / Password";
            }

        }
        catch (Exception $ex)
        {
            $res['error'] = 1;
            $res['message'] = $ex->getMessage();
        }

        die(json_encode($res));
    }

    public function sb_ajax_create_grvlsw_account()
    {
        self::callAcctRegister($_POST);
    }

    protected static function _curlResp($param,$url)
    {
        $response = wp_remote_post($url,array('body'=> $param,'timeout' => 10));
        if (is_array($response) && !empty($response['body'])) {
           $resp = json_decode($response['body'], true);
        } else {
           $resp['error']  = 1;
        }

        return $resp;
    }

    protected static function _getIPDetails()
    {
        // Default return value for failure case of API request
        $ip  = $_SERVER['REMOTE_ADDR'];
        $ip_details = array('ip'=>$ip, 'city'=>'', 'region_name'=>'', 'country_code'=>'US');

        try {

            $url = 'http://www.geoplugin.net/json.gp?ip='.$ip;
            $response = wp_remote_get( $url );

            if (is_array($response) && !empty($response['body']))
            {
                $ipLocArr = json_decode($response['body'], TRUE);

                /*
                * 200 - Full data return from IP REST webservice
                * 206 - Country data return from IP REST webservice
                * 404 - No Data exists (@Todo: Need to check more on this)
                * https://stackoverflow.com/questions/28038278/getting-visitors-city-from-their-ip-not-working-geoplugin
                */
                if (!empty($ipLocArr['geoplugin_request']) && $ipLocArr['geoplugin_request'] == $ip && in_array($ipLocArr['geoplugin_status'], array(200, 206))) {
                    $ip_details['ip']            = empty($ipLocArr['geoplugin_request']) ? $ip : $ipLocArr['geoplugin_request'];
                    $ip_details['city']          = empty($ipLocArr['geoplugin_city']) ? null : $ipLocArr['geoplugin_city'];
                    $ip_details['region_name']   = empty($ipLocArr['geoplugin_regionName']) ? null : $ipLocArr['geoplugin_regionName'];
                    $ip_details['country_code']  = empty($ipLocArr['geoplugin_countryCode']) ? 'US' : $ipLocArr['geoplugin_countryCode'];
                }
            }

        } catch (Exception $e) { }

        return $ip_details;
    }

    private function callAcctRegister($p)
    {
        if (empty($p['socialboost_reg_email_user']))
        {
            $resArr = array('sb_reg'=>4, 'message'=>'Enter valid email address');
            die(json_encode($resArr));
        }

        $ip_info = self::_getIPDetails();

        $params['action']       = 'createaccount';
        $params['firstname']    = sanitize_text_field($p['socialboost_reg_firstname']);
        $params['lastname']     = sanitize_text_field($p['socialboost_reg_lastname']);
        $params["raffd"]        = sanitize_text_field($p['raffd']);
        $params['companyname']  = get_bloginfo('name');
        $params['email']        = sanitize_email($p['socialboost_reg_email_user']);
        $params['email_user']   = sanitize_email($p['socialboost_reg_email_user']);
        $params['address1'] = '***'; //Dummy
        $params['city'] = empty($ip_info['city']) ? '***' : $ip_info['city'];
        $params['state'] = empty($ip_info['region_name']) ? '***' : $ip_info['region_name'];
        $params['postcode'] = '1'; //Dummy
        $params['country'] = empty($ip_info["country_code"]) ? 'US' : $ip_info["country_code"];
        $params['currency'] = ($params['country'] === 'AU')?3:1;
		$params["currency_code"] = get_option('woocommerce_currency', 'USD');
        $params['phonenumber'] = '1234567890'; //Dummy
        $params["notes"] = 'Wordpress';
        $params["app"] = 'grvlsw';
        $params['url'] = get_option('siteurl');

        $params['type'] = 'url';
        $params['plugin_type'] = 'WP';
        if (class_exists('WC_Integration')) {
            $params['plugin_type'] = 'WOO';
            $params['wp_login_url'] = get_permalink( get_option('woocommerce_myaccount_page_id') );
            $params['wp_registration_url'] = get_permalink( get_option('woocommerce_myaccount_page_id') );
        } else {
            $params['wp_login_url'] = wp_login_url();
            $params['wp_registration_url'] = wp_registration_url();
        }

        $params['shop_url'] = get_option('siteurl');
        $params['shop_name'] = get_option('blogname');

        $params['campaign_name']    = "REWARDS";
        $params['timezone']         = 'America/Chicago';
        $params['date_format']      = 'm/d/Y';
        $params['exclusion_period'] = 0;
        $params['campaign_only ']   = 1;
        $params['login_url']        = get_option('siteurl');
        $params['users_can_register'] = get_option('users_can_register', 0);

        $resArr = array();
        $resArr = self::_curlResp($params, self::$_api_url);

        if (isset($resArr['error']) && $resArr['error'] == 0)
        {
            update_option('socialboost_shop_id', $resArr['id_shop']);
            update_option('socialboost_admin_email', $params['email']);
            update_option('socialboost_appid', $resArr['id_site']);
            update_option('socialboost_payload', $resArr['pay_load']);
            update_option('socialboost_register', 1);
            update_option('socialboost_plgtyp', $params['plugin_type']);

            $resArr['appid'] = $resArr['id_site'];
            $resArr['frame_url'] = self::$_callback_url.'autologin?id_shop='.$resArr['id_shop'].'&admin_email='.urlencode($params['email']).'&payload='.$resArr['pay_load'].'&autoredirect=auto';
            $resArr['sb_reg']    = 0;
        }
        else if (isset($resArr['error']) && $resArr['error'] == 1)
        {
            $resArr['sb_reg'] = 1;
        }
        else if (isset($resArr['error']) && $resArr['error'] == 2)
        {
            update_option( 'socialboost_register', 3 );
            $resArr['sb_reg'] = 2;
        }
        else
        {
            $resArr['sb_reg'] = 4;
        }

        die(json_encode($resArr));
    }

    public function init_page_load()
    {
        if(isset($_REQUEST['grc']))
        {
            if( !session_id())
               session_start();

           $_SESSION['grc']     = sanitize_text_field($_REQUEST['grc']);
           $_SESSION['gre']     = sanitize_text_field($_REQUEST['gre']);
           $_SESSION['typ']     = isset($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : 'gr';
           $_SESSION['scopeid'] = sanitize_text_field($_REQUEST['scopeid']);
           $_SESSION['mavtoken']= isset($_REQUEST['mavtoken']) ? sanitize_text_field($_REQUEST['mavtoken']) : '';
        }

    }
    /**
     * hook into WP's woocommerce payment made action hook
     */
    public function send_comment_to_appsmav($comment_ID)
    {
        if( !session_id())
            session_start();

        if(isset($_SESSION['grc'])){

            $mavtoken = '';
            if (!empty($_SESSION['mavtoken']))
                $mavtoken = "&mavtoken=" . $_SESSION['mavtoken'];

            switch($_SESSION['typ'])
            {
                case 'sb':
                    $params = '?grc='.$_SESSION['grc'].'&gre='.$_SESSION['gre'].'&scopeid='.$_SESSION['scopeid'].'&cid='.$comment_ID.$mavtoken;
                    wp_redirect(self::$_callback_url . 'contest/play/'.$_SESSION['grc'].'/'.$params);
                    exit();
                default:
                    $params = '?grc='.$_SESSION['grc'].'&gre='.$_SESSION['gre'].'&scopeid='.$_SESSION['scopeid'].'&cid='.$comment_ID.$mavtoken;
                    wp_redirect(self::$_callback_url . 'contest/play/'.$_SESSION['grc'].'/'.$params);
                    exit();
            }

        }
    }

    /**
     * hook into WP's admin_init action hook
     */
    public function commenthook_init()
    {
        // Set up the settings for this plugin
        add_action('comment_post', array(&$this, 'send_comment_to_appsmav'));
        add_action('wp', array(&$this, 'init_page_load'));

        // Order related hooks for Purchase campaign
        add_action('woocommerce_checkout_order_processed', array(&$this, 'send_connect_init'));
        add_action('woocommerce_order_status_changed', array(&$this, 'send_status_init'));
        add_action('woocommerce_order_refunded', array(&$this, 'send_refund_init'));
        add_action('before_delete_post', array(&$this, 'send_refund_delete_post_init'));

        // Possibly do additional admin_init tasks
    } // END public static function activate


    /*
     * This function will call when new user place an order
     */
    public function send_connect_init($order_id)
    {
        try {

            global $wpdb;

            // Check purchase campaign is enabled
            $is_enabled = self::is_purchase_camp_enabled();
            if (!$is_enabled)
                return;

            $order = new WC_Order($order_id);

            $user_email = '';
            $ordered_user = $order->get_user();
            if(!empty($ordered_user))
                $user_email = $ordered_user->get('user_email');

            $status = $order->get_status();
            $param['order_status'] = strtolower($status);

            if(strtolower($status) != 'processing' && strtolower($status) != 'paid' && strtolower($status) != 'completed')
                $param['order_status'] = 'pending';

            $param['user'] = $ordered_user;
            if (version_compare( WC_VERSION, '3.7', '<' ))
                $couponsArr = $order->get_used_coupons();
            else
                $couponsArr = $order->get_coupon_codes();

            $param['discount'] = $order->get_total_discount();
            $param['subtotal'] = $order->get_subtotal() - $order->get_total_discount();
            $param['total'] = $order->get_total() - $order->get_total_refunded();
            $param['shipping'] = $order->get_shipping_total();
            $param['shipping_tax'] = $order->get_shipping_tax();
            $param['tax'] = $order->get_total_tax();

            if(strtolower($status) == 'pending')
               return;

            if(!empty($couponsArr))
                $param['coupon'] = $couponsArr[0];

            if(version_compare( WC_VERSION, '3.0', '<' )) {
                $param['name'] = $order->get_billing_first_name();
            }
            else
            {
                $order_data = $order->get_data();
                $param['name'] = empty($order_data['billing']['first_name']) ? '' : $order_data['billing']['first_name'];
            }

            $param['email'] = !empty($user_email) ? $user_email : $order->get_billing_email();
            $param['customer_id'] = $order->get_user_id();
            $param['order'] = 1;
            $param['createaccount'] = 0;
            $param['id_order'] = $order_id;
            $param['comment'] = 'Order Id - ' . $order_id . ' From ' . get_option('siteurl');
            $param['status'] = 'Add';
            $param['created_date'] = $order->get_date_created()->format('c');
            $param['user_ip'] = $order->get_customer_ip_address();

            if(version_compare( WC_VERSION, '3.0', '<' ))
                $curOrder = $order->get_order_currency();
            else
                $curOrder = $order->get_currency();

            $curShop = get_option('woocommerce_currency', 'USD');
            $param['plugin_version'] = self::$_plugin_version;

            if($curOrder != $curShop)
            {
                $param['currency_notmatch'] = 1;

                $prodArr = $order->get_items();
                $total = 0;

                foreach($prodArr as $prod)
                {
                    $product = new WC_Product($prod['product_id']);
                    $get_items_sql = $wpdb->prepare("select * from {$wpdb->prefix}postmeta WHERE meta_key = %s AND post_id = %d", '_price', $prod['product_id']);
                    $line_item = $wpdb->get_row($get_items_sql);
                    $price = $line_item->meta_value;

                    if(empty($price))
                        $price = $product->price;

                    $total += $price * $prod['qty'];
                }

                $ratio = $param['subtotal'] / $total;
                $param['total'] = $param['total'] / $ratio;
                $param['subtotal'] = $param['subtotal'] / $ratio;
                $param['shipping'] = $param['shipping'] / $ratio;
                $param['shipping_tax'] = $param['shipping_tax'] / $ratio;
                $param['tax'] = $param['tax'] / $ratio;

                $param['currency_conversion'] = array(
                    'ratio' => $ratio,
                    'curOrder' => $curOrder,
                    'curShop' => $curShop,
                    'total' => $param['total'],
                    'subtotal' => $param['subtotal'],
                    'shipping' => $param['shipping'],
                    'shipping_tax' => $param['shipping_tax'],
                    'tax' => $param['tax'],
                );
            }

            try {
                //We are skipping parent order id if it has sub orders
                if(class_exists('WCMp'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_wcmp_sub_order', true ) == '1'){
                        $param['comment'] = 'Main WCMp Order Id ' . str_replace('wc-', '', sanitize_text_field($_REQUEST['order_status'])) . ' - ' . $order_id . ' From ' . get_option('socialboost_shop_id', 0).' total '.$param['total'];
                        $param['total'] = 0;
                        $param['subtotal'] = 0;
                        $param['shipping'] = 0;
                        $param['tax'] = 0;
                    }
                }

                if(class_exists('WeDevs_Dokan'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_sub_order', true ) == '1'){
                        $param['comment'] = 'Main Dokan Order Id ' . str_replace('wc-', '', sanitize_text_field($_REQUEST['order_status'])) . ' - ' . $order_id . ' From ' . get_option('socialboost_shop_id', 0).' total '.$param['total'];
                        $param['total'] = 0;
                        $param['subtotal'] = 0;
                        $param['shipping'] = 0;
                        $param['tax'] = 0;
                    }
                }
            }
            catch(Exception $e){ }

            $urlApi = self::$_callback_url . self::$_api_version . 'addPurchaseEntry';
            $this->callSbConnectApi($param, $urlApi);
        }
        catch(Exception $e)
        { }
    }

    /*
     * This function will call when any order details get updated
     */
    public function send_status_init($order_id)
    {
        try {

            global $wpdb;

            // Check purchase campaign is enabled
            $is_enabled = self::is_purchase_camp_enabled();
            if (!$is_enabled) {
                return;
            }

            $order = new WC_Order($order_id);
            $status = $order->get_status();
            $arrayAdd = array('processing', 'completed');
            $param['order_status'] = $status;
            $param['plugin_version'] = self::$_plugin_version;

            $user_email = '';
            $ordered_user = $order->get_user();

            if(!empty($ordered_user))
                $user_email = $ordered_user->get('user_email');

            if(in_array($status, $arrayAdd))
            {
                $urlApi = self::$_callback_url . self::$_api_version . 'addPurchaseEntry';
                $param['status'] = 'Add';
            }
            else
            {
                $urlApi = self::$_callback_url . self::$_api_version . 'removePurchaseEntry';
                $param['status'] = ($param['order_status'] == 'refunded') ? 'refunded' : 'Cancel';
            }

            if (version_compare( WC_VERSION, '3.7', '<' ))
                $couponsArr = $order->get_used_coupons();
            else
                $couponsArr = $order->get_coupon_codes();

            if(!empty($couponsArr))
                $param['coupon'] = $couponsArr[0];

            $param['discount'] = $order->get_total_discount();
            $param['subtotal'] = $order->get_subtotal() - $order->get_total_discount();
            $param['total'] = $order->get_total() - $order->get_total_refunded();

            // Full refund, set total amount for points deduction.
            if ($param['total'] <= 0)
                $param['total'] = $order->get_total();

            $param['refunded'] = $order->get_total_refunded();
            $param['shipping'] = $order->get_shipping_total();
            $param['shipping_tax'] = $order->get_shipping_tax();
            $param['tax'] = $order->get_total_tax();

            if(version_compare( WC_VERSION, '3.0', '<' ))
                $curOrder = $order->get_order_currency();
            else
                $curOrder = $order->get_currency();

            $curShop = get_option('woocommerce_currency', 'USD');

            if($curOrder != $curShop)
            {
                $param['currency_notmatch'] = 1;

                $prodArr = $order->get_items();
                $subtotal = 0;

                foreach($prodArr as $prod)
                {
                    $product = new WC_Product($prod['product_id']);
                    $get_items_sql = $wpdb->prepare("select * from {$wpdb->prefix}postmeta WHERE meta_key = %s AND post_id = %d", '_price', $prod['product_id']);
                    $line_item = $wpdb->get_row($get_items_sql);
                    $price = $line_item->meta_value;

                    if(empty($price))
                        $price = $product->price;

                    $subtotal += $price * $prod['qty'];
                }

                $ratio = $order->get_subtotal() / $subtotal;
                $param['total'] = $param['total'] / $ratio;
                $param['subtotal'] = $param['subtotal'] / $ratio;
                $param['shipping'] = $param['shipping'] / $ratio;
                $param['shipping_tax'] = $param['shipping_tax'] / $ratio;
                $param['tax'] = $param['tax'] / $ratio;
                $param['refunded'] = $param['refunded'] / $ratio;
                $param['discount'] = $param['discount'] / $ratio;

                $param['currency_conversion'] = array(
                    'ratio'    => $ratio,
                    'curOrder' => $curOrder,
                    'curShop'  => $curShop,
                    'total'    => $param['total'],
                    'subtotal' => $param['subtotal'],
                    'shipping' => $param['shipping'],
                    'shipping_tax' => $param['shipping_tax'],
                    'tax'      => $param['tax'],
                    'refunded' => $param['refunded'],
                    'discount' => $param['discount']
                );
            }

            if(empty($_REQUEST['order_status']))
                $_REQUEST['order_status'] = '';

            if(version_compare( WC_VERSION, '3.0', '<' ))
            {
                $param['name'] = $order->get_billing_first_name();
            }
            else
            {
                $order_data = $order->get_data();
                $param['name'] = empty($order_data['billing']['first_name']) ? '' : $order_data['billing']['first_name'];
            }

            $param['created_date'] = $order->get_date_created()->format('c');
            $param['user_ip'] = $order->get_customer_ip_address();
            $param['email'] = !empty($user_email) ? $user_email : $order->get_billing_email();
            $param['customer_id'] = $order->get_user_id();
            $param['comment'] = 'Order Id ' . str_replace('wc-', '', sanitize_text_field($_REQUEST['order_status'])) . ' - ' . $order_id . ' From ' . get_option('siteurl');
            $param['order'] = 0;
            $param['id_order'] = $order_id;

            try {
                //We are skipping parent order id if it has sub orders
                if(class_exists('WCMp'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_wcmp_sub_order', true ) == '1'){
                        $param['comment'] = 'Main WCMp Order Id ' . str_replace('wc-', '', sanitize_text_field($_REQUEST['order_status'])) . ' - ' . $order_id . ' From ' . get_option('socialboost_shop_id', 0).' total '.$param['total'];
                        $param['total'] = 0;
                        $param['subtotal'] = 0;
                        $param['shipping'] = 0;
                        $param['tax'] = 0;
                    }
                }

                if(class_exists('WeDevs_Dokan'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_sub_order', true ) == '1'){
                        $param['comment'] = 'Main Dokan Order Id ' . str_replace('wc-', '', sanitize_text_field($_REQUEST['order_status'])) . ' - ' . $order_id . ' From ' . get_option('socialboost_shop_id', 0).' total '.$param['total'];
                        $param['total'] = 0;
                        $param['subtotal'] = 0;
                        $param['shipping'] = 0;
                        $param['tax'] = 0;
                    }
                }
            }
            catch(Exception $e){ }

            $this->callSbConnectApi($param, $urlApi);
        }
        catch(Exception $e)
        { }
    }

    /*
     * This function will call when order refunded
     */
    public function send_refund_init($order_id)
    {
        try
        {
            global $wpdb;
            $order = new WC_Order($order_id);

            // Check purchase campaign is enabled
            $is_enabled = self::is_purchase_camp_enabled();
            if (!$is_enabled)
                return;

            try {
                //We are skipping parent order id if it has sub orders
                if(class_exists('WCMp'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_wcmp_sub_order', true ) == '1')
                        return;
                }
                if(class_exists('WeDevs_Dokan'))
                {
                    if( $order->get_parent_id() === 0 && get_post_meta( $order_id, 'has_sub_order', true ) == '1')
                        return;
                }
            }
            catch(Exception $e){ }

            $email = '';
            $ordered_user = $order->get_user();

            if(!empty($ordered_user))
                $email = $ordered_user->get('user_email');

            if(empty($_REQUEST['refund_amount']))
                return;

            $refunded = $order->get_total_refunded();
            $amt = sanitize_text_field($_REQUEST['refund_amount']);

            $param['amt_old'] = $amt;

            $param['refunded'] = $refunded;
            $param['total'] = $amt;

            $param['curShop'] = get_option('woocommerce_currency', 'USD');
            if(version_compare( WC_VERSION, '3.0', '<' ))
                $param['curOrder'] = $order->get_order_currency();
            else
                $param['curOrder'] = $order->get_currency();

            $refundData = array();
            foreach($order->get_refunds() as $refunds) {
                $refundData['discount_total'] = $refunds->discount_total;
                $refundData['discount_tax'] = $refunds->discount_tax;
                $refundData['shipping_total'] = $refunds->shipping_total;
                $refundData['shipping_tax'] = $refunds->shipping_tax;
                $refundData['cart_tax'] = $refunds->cart_tax;
                $refundData['total'] = $refunds->total;
                $refundData['total_tax'] = $refunds->total_tax;
                $refundData['amount'] = $refunds->amount;

                $refundData['product_total'] = 0;
                foreach($refunds->get_items(array('line_item')) as $key => $lineItemObj) {
                    $refundData['product_total'] += $lineItemObj->get_subtotal();
                }

                break; // Since it itereate all refund, we need current one.
            }

            // Currency conversion starts here
            if($param['curOrder'] != $param['curShop'])
            {
                $prodArr = $order->get_items();
                $subtotal = 0;

                foreach($prodArr as $prod)
                {
                    $product = new WC_Product($prod['product_id']);
                    $get_items_sql = $wpdb->prepare("select * from {$wpdb->prefix}postmeta WHERE meta_key = %s AND post_id = %d", '_price', $prod['product_id']);
                    $line_item = $wpdb->get_row($get_items_sql);
                    $price = $line_item->meta_value;

                    if(empty($price))
                        $price = $product->price;

                    $subtotal += $price * $prod['qty'];
                }

                $ratio = $order->get_subtotal() / $subtotal;
                $refundData['discount_total'] = $refundData['discount_total'] / $ratio;
                $refundData['discount_tax']   = $refundData['discount_tax'] / $ratio;
                $refundData['shipping_total'] = $refundData['shipping_total'] / $ratio;
                $refundData['shipping_tax']   = $refundData['shipping_tax'] / $ratio;
                $refundData['cart_tax']       = $refundData['cart_tax'] / $ratio;
                $refundData['total']          = $refundData['total'] / $ratio;
                $refundData['total_tax']      = $refundData['total_tax'] / $ratio;
                $refundData['amount']         = $refundData['amount'] / $ratio;
                $refundData['product_total']  = $refundData['product_total'] / $ratio;

                $param['currency_conversion'] = array(
                    'ratio'         => $ratio,
                    'curOrder'      => $param['curOrder'],
                    'curShop'       => $param['curShop'],
                    'discount_total'=> $refundData['discount_total'],
                    'discount_tax'  => $refundData['discount_tax'],
                    'shipping_total'=> $refundData['shipping_total'],
                    'shipping_tax'  => $refundData['shipping_tax'],
                    'cart_tax'      => $refundData['cart_tax'],
                    'total'         => $refundData['total'],
                    'total_tax'     => $refundData['total_tax'],
                    'amount'        => $refundData['amount'],
                    'product_total' => $refundData['product_total'],
                );
            }

            $param['refund_data'] = $refundData;

            $param['refund_amount'] = $_REQUEST['refund_amount'];
            $param['discount'] = $order->get_total_discount();
            $param['subtotal'] = $order->get_subtotal() - $order->get_total_discount();
            $param['shipping'] = $order->get_shipping_total();
            $param['shipping_tax'] = $order->get_shipping_tax();
            $param['tax'] = $order->get_total_tax();

            $param['created_date'] = $order->get_date_created()->format('c');
            $param['user_ip'] = $order->get_customer_ip_address();
            $param['email'] = !empty($email) ? $email : $order->get_billing_email();
            $param['customer_id'] = $order->get_user_id();
            $param['order'] = 0;
            $param['id_order'] = $order_id;
            $param['plugin_version'] = self::$_plugin_version;
            $urlApi = self::$_callback_url . self::$_api_version . 'removePurchaseEntry';

            if(version_compare( WC_VERSION, '3.0', '<' ))
            {
                $param['name'] = $order->get_billing_first_name();
            }
            else
            {
                $order_data = $order->get_data();
                $param['name'] = empty($order_data['billing']['first_name']) ? '' : $order_data['billing']['first_name'];
            }

            $param['comment'] = 'Order Id Refunded - ' . $order_id . ' From ' . get_option('siteurl');
            $param['status'] = 'partial_refund';
            $param['order_status'] = $order->get_status();

            $this->callSbConnectApi($param, $urlApi);
        }
        catch(Exception $ex)
        { }
    }

    /**
     * This function will call when delete post action hook
     */
    public function send_refund_delete_post_init($refund_id)
    {
        try
        {
            global $wpdb;

            if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'woocommerce_delete_refund')
            {
                // Check purchase campaign is enabled
                $is_enabled = self::is_purchase_camp_enabled();
                if (!$is_enabled)
                    return;

                $refund = new WC_Order_Refund($refund_id);
                $order = new WC_Order($refund->post->post_parent);

                $param['discount'] = $order->get_total_discount();
                $param['subtotal'] = $order->get_subtotal() - $order->get_total_discount();
                $param['total'] = $order->get_total() - $order->get_total_refunded();
                $param['shipping'] = $order->get_shipping_total();
                $param['shipping_tax'] = $order->get_shipping_tax();
                $param['tax'] = $order->get_total_tax();

                if(version_compare( WC_VERSION, '3.0', '<' ))
                    $curOrder = $order->get_order_currency();
                else
                    $curOrder = $order->get_currency();

                $curShop = get_option('woocommerce_currency', 'USD');

                $email = '';
                $ordered_user = $order->get_user();

                if(!empty($ordered_user))
                    $email = $ordered_user->get('user_email');

                if($curOrder != $curShop)
                {
                    $param['currency_notmatch'] = 1;

                    $prodArr = $order->get_items();
                    $total = 0;

                    foreach($prodArr as $prod)
                    {
                        $product = new WC_Product($prod['product_id']);
                        $get_items_sql = $wpdb->prepare("select * from {$wpdb->prefix}postmeta WHERE meta_key = %s AND post_id = %d", '_price', $prod['product_id']);
                        $line_item = $wpdb->get_row($get_items_sql);
                        $price = $line_item->meta_value;

                        if(empty($price))
                            $price = $product->price;

                        $total += $price * $prod['qty'];
                    }

                    $ratio = $param['subtotal'] / $total;
                    $param['total'] = $param['total'] / $ratio;
                }

                $param['created_date'] = $order->get_date_created()->format('c');
                $param['user_ip'] = $order->get_customer_ip_address();
                $param['email'] = !empty($email) ? $email : $order->get_billing_email();
                $param['customer_id'] = $order->get_user_id();
                $param['order'] = 0;
                $param['id_order'] = $refund->post->post_parent;
                $urlApi = self::$_callback_url . self::$_api_version . 'addPurchaseEntry';

                if(version_compare( WC_VERSION, '3.0', '<' ))
                {
                    $param['name'] = $order->get_billing_first_name();
                }
                else
                {
                    $order_data = $order->get_data();
                    $param['name'] = empty($order_data['billing']['first_name']) ? '' : $order_data['billing']['first_name'];
                }

                $param['comment'] = 'Order Id Refund Restore - ' . $refund->post->post_parent . ' From ' . get_option('siteurl');
                $param['status'] = 'Add';
                $param['order_status'] = $order->get_status();
                $param['plugin_version'] = self::$_plugin_version;

                $this->callSbConnectApi($param, $urlApi);
            }
        }
        catch(Exception $e)
        { }
    }

    private function callSbConnectApi($param, $urlApi)
    {
        $msg = '';
        try
        {
            $shop_id = get_option('socialboost_shop_id', 0);

            if($shop_id == 0)
                return;

            $sbAppIdArr = get_option('socialboost_appid');
            $sbAppId = !empty($sbAppIdArr) ? $sbAppIdArr : '';
            $paramSalt = array();
            $paramSalt['id_site'] = $params['id_site'] = $sbAppId;
            $paramSalt['email'] = $params['email'] = $param['email'];

            $params['app'] = 'WP';
            if (class_exists('WC_Integration'))
                $params['app'] = 'WOO';

			$params['name'] = isset($param['name']) ? $param['name'] : '';
			$params['comment'] = isset($param['comment']) ? $param['comment'] : '';
            $params["app_lang"] = str_replace('-', '_', get_bloginfo('language'));
            $allparam = implode('#'.$params['app'].'#', $paramSalt);
            $params['salt'] = md5($allparam);
            $params['id_shop'] = $shop_id;
            $params['coupon'] = isset($param['coupon']) ? $param['coupon'] : '';
			$params['id_order'] = isset($param['id_order']) ? $param['id_order'] : 0;			
			$params['amount'] = isset($param['total']) ? $param['total'] : 0;
			$params['subtotal'] = isset($param['subtotal']) ? $param['subtotal'] : 0;
			$params['total'] = isset($param['total']) ? $param['total'] : 0;
			$params['shipping'] = isset($param['shipping']) ? $param['shipping'] : 0;
			$params['shipping_tax'] = isset($param['shipping_tax']) ? $param['shipping_tax'] : 0;
			$params['tax'] = isset($param['tax']) ? $param['tax'] : 0;
			$params['discount'] = isset($param['discount']) ? $param['discount'] : 0;
            $params['customer_id'] = !empty($param['customer_id']) ? $param['customer_id'] : 0;
            $params['refund_amount'] = !empty($param['refund_amount']) ? $param['refund_amount'] : 0;
            $params['refunded'] = !empty($param['refunded']) ? $param['refunded'] : 0;
            $params['refund_data'] = !empty($param['refund_data']) ? $param['refund_data'] : array();
            $params['plugin_version'] = self::$_plugin_version;

            $params['currency'] = get_option('woocommerce_currency', 'USD');
            $params['status'] = isset($param['status']) ? $param['status'] : '';
            $params['order_status'] = !empty($param['order_status']) ? $param['order_status'] : '';
            $params['payload'] = get_option('socialboost_payload', 0);
            $params['created_date'] = !empty($param['created_date']) ? $param['created_date'] : '';
            $params['user_ip'] = !empty($param['user_ip']) ? $param['user_ip'] : '';

            if (!empty($param['currency_conversion']))
                $params['currency_conversion'] = $param['currency_conversion'];

            if($sbAppId != '')
            {
            	$res = self::_curlResp($params, $urlApi);
                if(!empty($res['error']))
                    $msg = 'Unexpected error occur. Please check with administrator.';
            }
            else
            {
                $msg = 'SB app id or secret is missing';
            }
        } catch (Exception $ex) {
            $msg = 'Error : '. $ex->getMessage();
        }

        return $msg;
    }

    private function is_purchase_camp_enabled()
    {
        try
        {
            $is_enabled = false;
            $app_config = sb_get_app_config();
            if (!isset($app_config['date_updated']) || empty($app_config['date_updated']) || $app_config['date_updated'] == null)
            {
                $app_config = self::get_site_config_api();
            }

            if (!empty($app_config['is_purchase_campaign']) && $app_config['is_purchase_campaign'] == 1)
            {
                $is_enabled = true;
            }

        } catch (Exception $ex) {}

        return $is_enabled;
    }

    private function get_site_config_api()
    {
        try
        {
            $config = array();
            $app_config = sb_get_app_config();

            $id_site = get_option('socialboost_appid', 0);
            $param = array('id_site' => $id_site);
            $url = self::$_callback_url.self::$_api_version.'getSiteConfig';

            $response = wp_remote_post($url, array('body' => $param));
            if (!is_array($response) || empty($response['body']))
                throw new Exception('Verification request failed');

            $resp_data = json_decode($response['body'], true);

            if (isset($resp_data['error']) && $resp_data['error'] == 0 && isset($resp_data['config'])) {
                $config = $resp_data['config'];

                if (!empty($app_config) && is_array($app_config))
                    $config = array_merge($app_config, $config);

                $config['date_updated'] = time();

                if(sb_set_app_config($config) == FALSE)
                    throw new Exception(__('Config file is not created'));
            } else {
                throw new Exception('API Error');
            }

        } catch (Exception $ex) {
            $config = $app_config;
        }

        return $config;
    }

    public function include_files()
    {
        try
        {
            include(sprintf("%s/includes/socialboost-http-request-handler.php", SB_PLUGIN_BASE_PATH));
            include(sprintf("%s/includes/socialboost-functions.php", SB_PLUGIN_BASE_PATH));
            include(sprintf("%s/includes/socialboost-api.php", SB_PLUGIN_BASE_PATH));
        }
        catch (Exception $ex) { }
    }

} // END class Social_Boost

if(class_exists('Social_Boost'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('Social_Boost', 'activate'));
    register_deactivation_hook(__FILE__, array('Social_Boost', 'deactivate'));

    // instantiate the plugin class
    $social_boost = new Social_Boost();

    // Add the settings link to the plugins page
    function plugin_settings_sbboost_link($links)
    {
        $settings_link = '<a href="options-general.php?page=socialboost">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'plugin_settings_sbboost_link');
    add_shortcode('sb-campaign', array( 'Social_Boost', 'social_boost_show_func' ) );

    $social_boost->include_files();

}
