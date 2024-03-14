<?php
/**
 * Plugin Name: Wiser Notify
 * Plugin URI: https://wisernotify.com
 * Description: Wiser Notify plugin will make webhook remote calls to Wiser Notify backend server on each signup & new order happening in WooCommerce store. Data sent via webhook to Wiser Notifyâ€™s backend server is limited to the few anonymous pieces of information , also synced last 30 ordered with WiserNotify, Easy digital downloads support added
 * Version: 2.6
 * Author: Wiser Notify
 * Author URI: https://wisernotify.com
 * */
$client_ip = false;
if (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
    $client_ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
$forward_for = false;
if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
    $forward_for = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
$server_data = false;
if (array_key_exists('HTTP_HOST', $_SERVER))
    $server_data = sanitize_text_field($_SERVER['HTTP_HOST']);
$remote_addr = false;
if (array_key_exists('REMOTE_ADDR', $_SERVER))
    $remote_addr = sanitize_textarea_field($_SERVER['REMOTE_ADDR']);
class Wiser {
    /* Class Constructer */
    function __construct() {
        $this->actions();
        $this->src = get_option('pixelcode');
    }
    /* Single Function For All Action Hooks */
    function actions() {
        add_action('admin_menu', array($this, 'wiser_page_create'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_admin'));
        add_action('wp_footer', array($this, 'enqueue_scripts_front'),'','',true);
        add_action('wp_ajax_varify_api', array($this, 'varify_api'));
        add_action('wp_ajax_nopriv_varify_api', array($this, 'varify_api'));
        // add_action( 'woocommerce_order_details', array($this,'wiser_get_order_details') );
        if(get_option('wiser_enable_for_wp')){
            add_action( 'transition_comment_status',array($this,'wiser_approve_comment_callback'), 10, 3);
        }
        if(!get_option('comment_moderation')){
            add_action( 'comment_post', array($this,'wporg_comment_inserted'), 99, 2);
        }
         /** For before Thank you page hook- "woocommerce_order_status_pending", "woocommerce_order_status_failed","woocommerce_order_status_on-hold","woocommerce_order_status_processing","woocommerce_order_status_completed" */
        add_action( 'woocommerce_thankyou', array($this,'wiser_woocommerce_order_status_completed'), 10, 1 );
        add_filter( 'script_loader_tag', array($this,'wiser_make_script_async'), 10, 3 );
        add_action( 'edd_complete_download_purchase', array($this,'wiser_action_edd_complete_download_purchase'), 10, 5 );
        register_activation_hook(__FILE__, array($this, 'wiser_pluginprefix_activation'));
        register_deactivation_hook(__FILE__, array($this, 'wiser_pluginprefix_deactivation'));

    }

    /* Create Settings Page For Plugin */
    function wiser_page_create() {
        add_menu_page('Wiser', 'Wiser', 'manage_options', 'wiser', array($this, 'wiser_page_html'),  plugin_dir_url(__FILE__).'/assets/images/wiser-notifly-favi.png', 24);
    }
    /* Enqueue Scripts And Styles For Admin Only */
    function enqueue_scripts_admin() {
        wp_enqueue_script('wiserjs', plugin_dir_url(__FILE__) . 'assets/js/wiser.js');
        wp_enqueue_style('wisercss', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        wp_enqueue_style('google-popins-fonts','https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');
        wp_enqueue_style('google-roboto-fonts','https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap');
        wp_localize_script('wiserjs', 'ajaxVar', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    /*Enqueue Scripts For Front*/
    function enqueue_scripts_front(){
        wp_enqueue_script('jquery');
        if($this->src != ''){
             _e("<script>".$this->src."</script>"); 
         }
    }
    /*Async Pixel Code*/
    function wiser_make_script_async( $tag, $handle, $src ){
        if ( 'pixelcode' != $handle ) {
            return $tag;
        }
        return str_replace( '<script', '<script async', $tag );
    }
    /* UI For Wiser Form */
    function wiser_page_html() {
        $apiKey = get_option('apikey');
        $pixelCode = get_option('pixelcode');
        ?>
        <div class="wiser-content-box" style="padding-top:20px">
            <div class="wiser-top-headerbar">
                <div class="wiser-logo">
                    <img src="<?php _e(plugin_dir_url(__FILE__)); ?>/assets/images/wiser-notifly.png" alt="wisernotifly" />
                </div>
            </div>
            <div class="wiser-install-box wiser-install-details">
                <div class="wiser-card-title">
                    <h3>Welcome to Wiser notify </h3>
                </div>
                <div class="wiser-card wiser-card-sign">
                    <h4> Don't have account? <a target="blank" href="https://app.wisernotify.com/signup?utm_source=WordPress&utm_medium=WithinPlugin">Signup now. </a>
                        We have free & paid plan. <a target="blank" href="https://wisernotify.com/pricing?utm_source=WordPress&utm_medium=WithinPlugin">Click here. </a>
                    </h4>
                </div>
            </div>
            <div class="wiser-install-box wiser-api-block">
                <div class="wiser-card-title">
                    <h3> Must Required </h3>
                </div>
                <div class="wiser-card wiser-card-api">
                    <div class="nf-group-input">
                        <form method="POST" id="api_form">
                            <?php wp_nonce_field('wiser_form_action', 'wiser_form_nonce'); ?>

                            <label>Enter your API Key. </label>
                            <div class="line-input-btn">
                                <input type="hidden" name="action" value="varify_api"/>
                                <input type="text" name="api_key" value="<?php _e($apiKey); ?>" id="api_key" class="nf-input nf-input-sm">
                                <button type="submit" name="submit" class="nf-btn nf-btn-default"> Submit </button>
                                <!--<input type="submit" name="submit" value="Save" class="button button-primary button-large">-->
                            </div>
                        </form>
                    </div>
                    <div class="nf-group-bottom">
                        <a target="blank"
                           href="https://support.wisernotify.com/your-account-api-key-amp-quota-history/">Know
                            about your API KEY </a>
                    </div>
                    <div class="nt-msg-text">
                        <p class="red-text">Your API key is wrong & Please, Enter valid API key.</p>
                        <p class="success-msg wn-success">
                             Congratulation! Your API key is verified & Also, Pixel tag is added successfully on your site.
                            <a target="blank"
                               href="https://support.wisernotify.com/">
                                Click here to know about "how to add Notification?"</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="nf-highlight-btm">
                <h3><a target="blank" href="https://app.wisernotify.com/login" >
                         Here
                    </a> is WiserNotify dashboard link where you can create & manage notifications.
                </h3>
            </div>
        </div>
        <?php
    }

    /* Function To Verify API Plugins */
/* Function To Verify API Plugins */
function wiser_varify_api_for_plugins($dataArr, $apikey) {
    // Ensure that the API key is provided and is not empty.
    if (empty($apikey)) {
        return "Invalid API key.";
    }

    $url = 'https://is.wisernotify.com/api/verifyAPI';
    $response = wp_remote_post($url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $dataArr,
            'cookies' => array()
        )
    );

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return "Something went wrong: $error_message";
    } else {
        $body = wp_remote_retrieve_body($response);
        $bodyArr = json_decode($body);
        if ($bodyArr->msg == '' || $bodyArr->msg == false) {
            return false;
        } else {
            $this->src = $bodyArr->pt;
            $this->wiser_update_data_save_option($this->src, $apikey, $bodyArr->ti);
            return true;
        }
    }
}

function wiser_update_data_save_option($wiser_pixelcode='', $wiser_apikey='', $wiser_pixeltag='') {
    update_option('pixelcode',  ($wiser_pixelcode));
    update_option('apikey',  ($wiser_apikey));
    update_option('pixeltag', ($wiser_pixeltag));
}
/* Function To Verify Api */
function varify_api() {
    global $server_data;

    // 1. Nonce Verification
    if (!isset($_POST['wiser_form_nonce']) || !wp_verify_nonce($_POST['wiser_form_nonce'], 'wiser_form_action')) {
        _e(json_encode(['success' => false, 'message' => 'Nonce verification failed!']));
        die();
    }

    // 2. Permission Check
    if (!current_user_can('manage_options')) {
        _e(json_encode(['success' => false, 'message' => 'You do not have sufficient permissions to access this function.']));
        die();
    }

    $key = sanitize_text_field($_POST['api_key']);
    $apikey = isset($key) ? $key : "";
    $host = $server_data;
    $dataArr = array(
        'ak' => $apikey,
        'fa' => 'wp',
        'status' => 1,
        'ht' => $host
    );

    $wiser_varify_api_for_plugins = $this->wiser_varify_api_for_plugins($dataArr, $apikey);
    if ($wiser_varify_api_for_plugins) {
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $order_json = $this->wiser_get_order_details();
            $order_json = $this->wiser_get_comment();
            update_option('wiser_enable_for_wp', 1);
        }
        if (in_array('easy-digital-downloads/easy-digital-downloads.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $dataArr = array(
                'ak' => $apikey,
                'ht' => $server_data,
                'fa' => 'edd',
            );
            $wiser_varify_api_for_plugins = $this->wiser_varify_api_for_plugins($dataArr, $apikey);
            if ($wiser_varify_api_for_plugins == 1) {
                update_option('wiser_enable_for_edd', 1);
            } else {
                update_option('wiser_enable_for_edd', 0);
            }
        }
        $ajaxRes['success'] = true;
        _e(json_encode($ajaxRes));
    } else {
        $ajaxRes['success'] = false;
        _e(json_encode($ajaxRes));
    }
    die();
}

	// Function to Get Last 30 Order Records
    function wiser_get_order_details() {
        global $woocommerce;
        $key = get_option('apikey');
        $pixelTag = get_option('pixeltag');
        $headers = array('ak'=>$key,'ti'=>$pixelTag);
        $orders_data = [];
            $wcOrders = wc_get_orders(array(
                'limit' => 30,
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            foreach($wcOrders as $wco) {
                if(empty($wco->get_parent_id()))
                {
                    array_push($orders_data, $this->wiser_get_order_billing_details($wco));
                }
            }
            $apiurl = 'https://is.wisernotify.com/api/wp/data';
            $response = wp_remote_post($apiurl, array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => $headers,
                    'body' => $orders_data,
                    'cookies' => array()
                )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
            } else {
                $body = wp_remote_retrieve_body($response);
                $bodyArr = json_decode($body);
            }
            _e(json_encode($orders_data));
    }

    function wiser_get_comment(){
        global $server_data;
        $args = array(
            'number'      => 30,
            'status'      => 'approve',
            'post_status' => 'publish',
            'post_type'   => 'product'
        );

        $comments = get_comments( $args );
        $comment_array_final = array();

        foreach($comments as $key => $comment){
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $comment->comment_post_ID ), 'single-post-thumbnail' );
            $image_url = $image[0];
            $comment_array = array(
                'orderId' => $comment->comment_ID,
                'un' => $comment->comment_author,
                'e' => $comment->comment_author_email,
                'i' => wiser_getIPAddress(),
                'ht' => $server_data,
                'rtxt' => $comment->comment_content,
                'rtng' => get_comment_meta( $comment->comment_ID,'rating',true),
                'pu' => get_permalink($comment->comment_post_ID),
                'pn' => get_the_title($comment->comment_post_ID),
                'piu' => $image_url,
                'fa' => 'wordpress',
                'insdt' => strtotime($comment->comment_date) * 1000,
            );

            $comment_array_final[] = $comment_array;
        }

        $key = get_option('apikey');
        $pixelTag = get_option('pixeltag');
        $headers = array('ak'=>$key,'ti'=>$pixelTag);
        $apiurl = 'https://is.wisernotify.com/api/wp/data';
        $response = wp_remote_post($apiurl, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
                'body' => $comment_array_final,
                'cookies' => array()
            )
        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }

    }

    function wiser_approve_comment_callback($new_status, $old_status, $comment) {
        global $server_data;
        if($old_status != $new_status) {
            if($new_status == 'approved' && get_post_type( $comment->comment_post_ID )=="product") {

                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $comment->comment_post_ID ), 'single-post-thumbnail' );
                $image_url = $image[0];

                $comment_array = array();
                $comment_array['orderId'] = $comment->comment_ID;
                $comment_array['un'] = $comment->comment_author;
                $comment_array['e'] = $comment->comment_author_email;
                $comment_array['i'] = wiser_getIPAddress();
                $comment_array['ht'] = $server_data;
                $comment_array['rtxt'] = $comment->comment_content;
                $comment_array['rtng'] = get_comment_meta( $comment->comment_ID,'rating',true);
                $comment_array['pu'] = get_permalink($comment->comment_post_ID);
                $comment_array['pn'] = get_the_title($comment->comment_post_ID);
                $comment_array['piu'] = $image_url;
                $comment_array['fa'] = 'wordpress';
                $comment_array['insdt'] = strtotime($comment->comment_date) * 1000;

                $key = get_option('apikey');
                $pixelTag = get_option('pixeltag');
                $headers = array('ak'=>$key,'ti'=>$pixelTag);
                $apiurl = 'https://is.wisernotify.com/api/wp/data';
                $response = wp_remote_post($apiurl, array(
                        'method' => 'POST',
                        'timeout' => 45,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => $headers,
                        'body' => $comment_array,
                        'cookies' => array()
                    )
                );
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                } else {
                    $body = wp_remote_retrieve_body($response);
                    $bodyArr = json_decode($body);
                }
            }
        }
    }

    function wporg_comment_inserted($comment_ID, $comment_approved) {
        global $server_data;
        $comment_object = get_comment($comment_ID);

        if( 1 === $comment_approved ){

            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $comment_object->comment_post_ID ), 'single-post-thumbnail' );
            $image_url = $image[0];

            $comment_array = array();
            $comment_array['orderId'] = $comment_object->comment_ID;
            $comment_array['un'] = $comment_object->comment_author;
            $comment_array['e'] = $comment_object->comment_author_email;
            $comment_array['i'] = wiser_getIPAddress();
            $comment_array['ht'] = $server_data;
            $comment_array['rtxt'] = $comment_object->comment_content;
            $comment_array['rtng'] = get_comment_meta( $comment_object->comment_ID,'rating',true);
            $comment_array['pu'] = get_permalink($comment_object->comment_post_ID);
            $comment_array['pn'] = get_the_title($comment_object->comment_post_ID);
            $comment_array['piu'] = $image_url;
            $comment_array['fa'] = 'wordpress';
            $comment_array['insdt'] = strtotime($comment_object->comment_date) * 1000;

            if(get_comment_meta( $comment_object->comment_ID,'rating',true)){

                $key = get_option('apikey');
                $pixelTag = get_option('pixeltag');
                $headers = array('ak'=>$key,'ti'=>$pixelTag);
                $apiurl = 'https://is.wisernotify.com/api/wp/data';
                $response = wp_remote_post($apiurl, array(
                        'method' => 'POST',
                        'timeout' => 45,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => $headers,
                        'body' => $comment_array,
                        'cookies' => array()
                    )
                );
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                } else {
                    $body = wp_remote_retrieve_body($response);
                    $bodyArr = json_decode($body);
                }
            }
        }
    }

    function wiser_get_order_billing_details($order) {
        global $server_data;
        $billing_details = array(
            'orderId' => $order->get_id(),
            'un' => $order->get_billing_first_name() . " " . $order->get_billing_last_name(),
            'e' => $order->get_billing_email(),
            'ct' => $order->get_billing_city(),
            'st' => $order->get_billing_state(),
            'cn' => $order->get_billing_country(),
            'i' => $order->get_customer_ip_address(),
            'ht' => $server_data,
            'insdt' => $order->get_date_created()->getTimestamp() * 1000,
            'products' => $this->wiser_get_products_details($order),
        );
        if(method_exists($order, 'get_date_created')) {
            $date = $order->get_date_created();
            if(!empty($date) && method_exists($date, 'getTimestamp')) {
                $billing_details['date'] = $order->get_date_created()->getTimestamp() * 1000;
            }
        }
       return $billing_details;
    }
    function wiser_get_products_details($order)
    {
        $items = $order->get_items();
        $products = array();
        foreach ($items as $item) {
            $quantity = $item->get_quantity();
            $product = $item->get_product();
            $images_arr = wp_get_attachment_image_src($product->get_image_id(), array('72', '72'), false);
            $image = null;
            if ($images_arr !== null && $images_arr[0] !== null) {
                $image = $images_arr[0];
            }
            $p = array(
                'pn' => $product->get_title(),
                'pu' => get_permalink($product->get_id()),
                'piu' => $image,
                'pdid'=> $product->get_id(),
                'fa' => 'wordpress',
                'insdt' => date(DATE_ISO8601, strtotime('now')),
            );
            array_push($products, $p);
        }
        return $products;
    }
     /*Api Call on Product Purchase And User Signup*/
     /** For before Thank you page - "woocommerce_order_status_pending", "woocommerce_order_status_failed","woocommerce_order_status_on-hold","woocommerce_order_status_processing","woocommerce_order_status_completed" */
    function wiser_woocommerce_order_status_completed( $order_id ) {
        global $server_data;
        $wiserArr = array();
        $key = get_option('apikey');
        $pixelTag = get_option('pixeltag');
        $headers = array('ak'=>$key,'ti'=>$pixelTag);
        $order = wc_get_order( $order_id );
        $wiserArr['orderId'] = $order->get_id();
        $wiserArr['un'] = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
        $wiserArr['e'] = $order->get_billing_email();
        $wiserArr['ct'] = $order->get_billing_city();
        $wiserArr['st'] = $order->get_billing_state();
        $wiserArr['cn'] = $order->get_billing_country();
        $wiserArr['i'] = $order->get_customer_ip_address();
        $wiserArr['ht'] = $server_data;
        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product_name = $item->get_name();
            $product_id = $item->get_product_id();
            $url = get_permalink( $product_id ) ;
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
            $image_url = $image[0];
            $wiserArr['pn'] = $product_name;
            $wiserArr['pu'] = $url;
            $wiserArr['pdid'] = $product_id;
            $wiserArr['piu'] = $image_url;
            $wiserArr['fa'] = 'wordpress';
            $wiserArr['insdt'] = date(DATE_ISO8601, strtotime('now'));
            $apiurl = 'https://is.wisernotify.com/api/wp/data';
            $response = wp_remote_post($apiurl, array(
                                        'method' => 'POST',
                                        'timeout' => 45,
                                        'redirection' => 5,
                                        'httpversion' => '1.0',
                                        'blocking' => true,
                                        'headers' => $headers,
                                        'body' => $wiserArr,
                                        'cookies' => array()
                                    )
                        );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
            } else {
                $body = wp_remote_retrieve_body($response);
                $bodyArr = json_decode($body);
            }
        }
		// Get TOTAL number of orders for customer
		$customer_orders = wc_get_customer_order_count( get_current_user_id() );

		if($customer_orders == 1){
         
            $userDetailArr = array();
            $userDetailArr['un'] = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
            $userDetailArr['e'] = $order->get_billing_email();
            $userDetailArr['ct'] = $order->get_billing_city();
            $userDetailArr['st'] = $order->get_billing_state();
            $userDetailArr['cn'] = $order->get_billing_country();
            $userDetailArr['i'] = $order->get_customer_ip_address();
            $userDetailArr['ht'] = $server_data;
            $userDetailArr['fa'] = 'wordpress';
            $userDetailArr['insdt'] = date(DATE_ISO8601, strtotime('now'));
            $apiurl = 'https://is.wisernotify.com/api/wp/data';
            $response = wp_remote_post($apiurl, array(
                                        'method' => 'POST',
                                        'timeout' => 45,
                                        'redirection' => 5,
                                        'httpversion' => '1.0',
                                        'blocking' => true,
                                        'headers' => $headers,
                                        'body' => $userDetailArr,
                                        'cookies' => array()
                                    )
                        );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
            } else {
                $body = wp_remote_retrieve_body($response);
                $bodyArr = json_decode($body);
            }
        }
    }

    // define the edd_complete_download_purchase callback
    function wiser_action_edd_complete_download_purchase( $download_id, $payment_id, $download_type, $download, $cart_index ) {
        $key = get_option('apikey');
        $pixelTag = get_option('pixeltag');
        $headers = array('ak'=>$key,'ti'=>$pixelTag);
        $downloadProd = edd_get_download($download_id);
        $product_name = $downloadProd->post_title;
        $url =  get_the_permalink($download_id);
        $piu = get_the_post_thumbnail_url($download_id);
        $payment_meta = edd_get_payment_meta( $payment_id );
        $userinfo = $payment_meta['user_info'];
        $username = $userinfo['first_name'] ." ".$userinfo['last_name'];
        $emailAddress = $userinfo['email'];
        $wiserArr['pn'] = $product_name;
        $wiserArr['pu'] = $url;
        $wiserArr['piu'] = $piu;
        $wiserArr['pdid'] = $download_id;
        $wiserArr['fa'] = 'wordpress';
        $wiserArr['un'] = $username;
        $wiserArr['e'] = $emailAddress;
        $wiserArr['ht'] = $server_data;
        $wiserArr['insdt'] = date(DATE_ISO8601, strtotime('now'));
        $apiurl = 'https://is.wisernotify.com/api/wp/data';
        $response = wp_remote_post($apiurl, array(
                                        'method' => 'POST',
                                        'timeout' => 45,
                                        'redirection' => 5,
                                        'httpversion' => '1.0',
                                        'blocking' => true,
                                        'headers' => $headers,
                                        'body' => $wiserArr,
                                        'cookies' => array()
                                    )
                        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }
    }
    // Function to get the client IP address
    function wiser_get_client_ip() {
            $http_forwarded = sanitize_text_field($_SERVER['HTTP_X_FORWARDED']);
            $forwaeded_for_http = sanitize_text_field($_SERVER['HTTP_FORWARDED_FOR']);
            $http_forwarded = sanitize_text_field($_SERVER['HTTP_FORWARDED']);
            $ipaddress = '';
            if (isset($client_ip))
                $ipaddress = $client_ip;
            else if(isset($forward_for))
                $ipaddress = $forward_for;
            else if(isset($http_forwarded))
                $ipaddress = $http_forwarded;
            else if(isset($forwaeded_for_http))
                $ipaddress = $forwaeded_for_http;
            else if(isset($http_forwarded))
                $ipaddress = $http_forwarded;
            else if(isset($remote_addr))
                $ipaddress = $remote_addr;
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
    }
     function wiser_pluginprefix_activation() {
        $dataArr = array();
        $date = new DateTime();
        $dataArr['ipa'] = $this->wiser_get_client_ip();
        $dataArr['wbs'] = get_site_url();
        $dataArr['ad'] =  $date->getTimestamp();
        $apiurl = 'https://is.wisernotify.com/api/mg/wpStatus';
        $response = wp_remote_post($apiurl, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'body' => $dataArr,
            'cookies' => array()
          )
        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }
    }
    function wiser_pluginprefix_deactivation() {
        $dataArr = array();
        $date = new DateTime();
        $dataArr['ipa'] = $this->wiser_get_client_ip();
        $dataArr['wbs'] = get_site_url();
        $dataArr['dad'] =  $date->getTimestamp();;
        $apiurl = 'https://is.wisernotify.com/api/mg/wpStatus';
        $response = wp_remote_post($apiurl, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'body' => $dataArr,
            'cookies' => array()
          )
        );
       if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }
    }
}
$WiserObj = new Wiser();

if(get_option('wiser_enable_for_edd')){
    add_action( 'edd_complete_purchase','wiser_get_purchase_data_from_easy_digital_download');
}

function wiser_get_purchase_data_from_easy_digital_download($payment_id ){
    global $server_data;
    $payment_meta = edd_get_payment_meta( $payment_id );
    $cart_items = edd_get_payment_meta_cart_details( $payment_id );
    $user_info = $payment_meta['user_info'];

    $product_items = array();
    foreach ($cart_items as $key => $item) {

        if(get_the_post_thumbnail_url($item['id'],'full')){

            $image_url = get_the_post_thumbnail_url($item['id'],'full');
        }
        else
        {
            $image_url = '';
        }

        $easydiogital_data = array(
            'pid'=> $payment_id,
            'pdid' => $item['id'],
            'un' => $user_info['first_name'].' '.$user_info['last_name'],
            'e' => $user_info['email'],
            'ct' => $user_info['address']['city'],
            'st' => $user_info['address']['state'],
            'cn' => $user_info['address']['country'],
            'i' => wiser_getIPAddress(),
            'ht' => $server_data,
            'insdt' => $payment_meta['date'],
            'pn' => get_the_title($item['id']),
            'pu' => get_permalink($item['id']),
            'piu' => $image_url,
        );

        $key = get_option('apikey');
        $pixelTag = get_option('pixeltag');
        $headers = array('ak'=>$key,'ti'=>$pixelTag);
        $apiurl = 'https://is.wisernotify.com/api/edd/data';
        $response = wp_remote_post($apiurl, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
                'body' => $easydiogital_data,
                'cookies' => array()
            )
        );
        
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }
    }
}

function wiser_get_order_details() {
    global $woocommerce;
    $key = get_option('apikey');
    $pixelTag = get_option('pixeltag');
    $headers = array('ak'=>$key,'ti'=>$pixelTag);
    $orders_data = [];
        $wcOrders = wc_get_orders(array(
            'limit' => 30,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        foreach($wcOrders as $wco) {
            if(empty($wco->get_parent_id()))
            {
                array_push($orders_data, $this->wiser_get_order_billing_details($wco));
            }
        }
        $apiurl = 'https://is.wisernotify.com/api/wp/data';
        $response = wp_remote_post($apiurl, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
                'body' => $orders_data,
                'cookies' => array()
            )
        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
        } else {
            $body = wp_remote_retrieve_body($response);
            $bodyArr = json_decode($body);
        }
        _e(json_encode($orders_data));
}

function wiser_getIPAddress() {
    global $client_ip , $forward_for, $remote_addr;
    if(!empty($client_ip)) {
        $ip = $client_ip;
    }
    elseif (!empty($forward_for)) {
        $ip = $forward_for;
    }
    else{
        $ip = $remote_addr;
    }
    return $ip;
}