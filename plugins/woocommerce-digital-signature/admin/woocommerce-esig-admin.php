<?php
/**
 *
 * @package ESIG_WOOCOMMERCE_Admin
 * @author  Abu Shoaib <team@approveme.com>
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('ESIG_WOOCOMMERCE_Admin')) :

    class ESIG_WOOCOMMERCE_Admin {

        protected static $instance = null;
        private $plugin_slug = null;
        private $esig_sad = null;
        

        const PRODUCT_AGREEMENT = 'esig_product_agreement';
        const GLOBAL_AGREEMENT = 'esig_global_agreement';
        const TEMP_ORDER_ID = 'esig_temp_order_id';

        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         * @since     0.1
         */
        private function __construct() {

            /*
             * Call $plugin_slug from public plugin class.
             */
            $plugin = ESIG_WOOCOMMERCE::get_instance();

            $this->plugin_slug = $plugin->get_plugin_slug();

            $this->esig_sad = new esig_woocommerce_sad();
            // Add an action link pointing to the options page.

            add_filter('esig_misc_more_document_actions', array($this, 'esig_misc_page_more_acitons'), 10, 1);



            add_filter('woocommerce_get_settings_checkout', array($this, 'esignature_all_settings'), 10, 1);

            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));

            // initilizing all action hooks 
            add_action("init", array($this, "woo_init"));
            add_filter("esig_access_control_allow", array($this, "is_esig_wocommerce_agreement"), 10, 2);
        }

        public function woo_init() {

            add_action('woocommerce_before_checkout_form', array($this, 'esig_before_checkout_form'), 10);
            add_action('kco_wc_before_checkout_form', array($this, 'esig_before_checkout_form'), 10);
            add_action('woocommerce_checkout_before_customer_details', array($this, 'esig_before_checkout_form'), 10);
            add_action('woocommerce_checkout_order_processed', array($this, 'esig_after_checkout_form'), 100, 3);
            add_action('woocommerce_thankyou', array($this, 'esig_checkout_after'), 100, 1);

            $after_checkout_logic = esig_woo_logic::get_after_checkout_condition();
            add_action('woocommerce_order_status_pending_to_' . $after_checkout_logic, array($this, 'esig_new_woo_order'), 100);

            add_action('esig_document_before_closing', array($this, 'esig_signature_after'), 10, 1);
            add_action('esig_document_basic_closing', array($this, 'esig_signature_after'), 10, 1);
            add_action('esig_after_sad_process_done', array($this, 'signature_process_done'), 11, 1);
            add_action('esig_approval_signer_added', array($this, 'signature_process_done'), 11, 1);
            add_action('esig_signature_loaded', array($this, 'signature_process_done'), 99999, 1);

            // add_action('esig_signature_pre_loaded', array($this, 'esig_signature_after'), 10, 1);
            add_action('woocommerce_cart_emptied', array($this, 'esig_woo_cart_empty'));

            add_filter('woocommerce_add_cart_item', array($this, 'woo_add_to_cart'), 10, 2);

            add_filter('esig_invite_not_sent', array($this, 'invite_not_sent'), 10, 2);
        }

        public function is_esig_wocommerce_agreement($ret, $document_id) {

            global $wpdb;
            $table = $table = _get_meta_table('post');
            $sadPageId = esig_woo_logic::get_sad_page_id($document_id);
            $post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $table WHERE meta_key=%s and meta_value=%d", '_esig_woo_meta_sad_page', $sadPageId));

            if ($post_id) {

                $meta = get_post_meta($post_id, '_esig_woo_meta_product_agreement', true);
                if ($meta) {
                    $ret = false;
                }
            }

            if (esig_woo_logic::is_global_agreement_enabled()) {
                $globalAgreementId = esig_woo_logic::get_global_agreement_id();
                if ($globalAgreementId == $document_id) {
                    $ret = false;
                }
            }

            return $ret;
        }

        public function invite_not_sent($ret, $document_id) {
            $docCheckSum = WP_E_Sig()->meta->get($document_id, 'esig-woo-document-checksum');
            $inviteHash = WP_E_Sig()->meta->get($document_id, 'esig-woo-invite-hash');
            if (empty($docCheckSum) && empty($inviteHash)) {
                return $ret;
            } else {
                return true;
            }
        }

        public function woo_add_to_cart($cart_item_data, $cart_item_key) {
            
       
            if (esig_woo_logic::is_signature_required($cart_item_data['product_id']))
             {
                 $productAgreementId  = esig_woo_logic::get_agreement_id($cart_item_data['product_id']);
                
                 if($productAgreementId && $productAgreementId != "pleaseslc")
                 {
                    $cart_item_data[self::PRODUCT_AGREEMENT]['agreement_id'] = $productAgreementId;
                    $cart_item_data[self::PRODUCT_AGREEMENT]['agreement_logic'] = esig_woo_logic::get_agreement_logic($cart_item_data['product_id']);
                    $cart_item_data[self::PRODUCT_AGREEMENT]['signed'] = 'no';
                 }
                
            }

            // check if global agreement enabled 
            if (esig_woo_logic::is_global_agreement_enabled()) {

                $sadArray  = esig_woo_logic::set_global_agreement();
                // check for is array  . 
                if(is_array($sadArray))
                {
                    $cart_item_data[self::GLOBAL_AGREEMENT] = $sadArray;
                }

                // update_option("rupom", json_encode($cart_item_data)); 
            }
            // WC()->cart->cart_contents[self::GLOBAL_AGREEMENT] = array("rupm"=>"yes"); //esig_woo_logic::set_global_agreement();

            return $cart_item_data;
        }

         public function esig_after_checkout_form($order_id, $posted_data, $order) {
            
           

            $agreement_list = array();
            // check for renewal agreement allowed or not if not allowed abort if allowed proceed. 
            
            // if (sizeof($woocommerce->cart->cart_contents) > 0) {
            //$cart = WC()->session->get('cart', null);

            if (!is_null(WC()->cart)) {
                if (WC()->cart->get_cart_contents_count() > 0) {

                    foreach (WC()->cart->get_cart() as $cart_item) {
                        
                        $productId = esig_woocommerce_get("product_id",$cart_item);

                        if (!esig_woo_logic::renewalContractAllowed()) {
                            if (is_array($cart_item) && array_key_exists("subscription_renewal", $cart_item)) {
                                break;
                            }
                        }
                       
                        $esig_agreement = isset($cart_item[self::PRODUCT_AGREEMENT]) ? $cart_item[self::PRODUCT_AGREEMENT] : null;
                        $agreement_logic = isset($esig_agreement['agreement_logic']) ? $esig_agreement['agreement_logic'] : null;
                        $agreement_signed = isset($esig_agreement['signed']) ? $esig_agreement['signed'] : null;
                        
                        // if bypass add to cart and product direclty added to cart
                        if(empty($agreement_logic) && empty($agreement_signed)){
                             if(esig_woo_logic::is_signature_required($product_id)){
                                 $agreement_logic = esig_woo_logic::get_agreement_logic($product_id);
                                 $agreement_signed ="no";
                                 $sad_document_id = esig_woo_logic::get_agreement_id($productId);
                                 $esig_agreement["agreement_id"] = $sad_document_id; 
                             }
                        }
                        
                        if ($agreement_logic == 'after_checkout' && $agreement_signed == 'no') {
                            $agreement_id = esig_woo_logic::clone_document($esig_agreement['agreement_id'], $order_id);
                            if ($agreement_id) {
                                $agreement_list[$agreement_id] = 'no';
                            }
                        }
                    }
                }
            }
            
            // global agreement 
            $global_document_id = esig_woo_logic::get_global_doc_id_from_session('after_checkout');

            //adding YITH WooCommerce Request A Quote Premium plugin support 
            /* if(empty($global_document_id) && class_exists('YITH_Request_Quote')){
                
                    $global_esig_logic = esig_woo_logic::get_global_logic(); 
                    if(esig_woo_logic::is_global_agreement_enabled() && $global_esig_logic=="after_checkout"){
                        update_post_meta($order_id,'have_esig_agreement',esig_woo_logic::get_global_agreement_id()); 
                        update_post_meta($order_id,'esig_agreement_type',$global_esig_logic);
                    }
            }*/
            if (!esig_woo_logic::renewalContractAllowed()) {
                 
                if(esig_woo_logic::isRenewal()) return false;
            }

            if ($global_document_id) {
                $agreement_id = esig_woo_logic::clone_document($global_document_id, $order_id);
                if ($agreement_id) {
                    $agreement_list[$agreement_id] = 'no';
                }
            }

            if(empty($agreement_list) && class_exists('YITH_Request_Quote')){
                        update_post_meta($order_id,'woo_esig_agreement','no');        
            }
            
            esig_woo_logic::save_after_checkout_doc_list($order_id, $agreement_list);
            
        }

        public function get_return_url($order = null) {

            if ($order) {
                $return_url = $order->get_checkout_order_received_url();
            } else {
                $return_url = wc_get_endpoint_url('order-received', '', wc_get_page_permalink('checkout'));
            }

            if (is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes') {
                $return_url = str_replace('http:', 'https:', $return_url);
            }

            return apply_filters('woocommerce_get_return_url', $return_url, $order);
        }

        public function esig_checkout_after($order_id) {
            
           
            if(!function_exists("WP_E_Sig"))
            {
                return false;
            }
            
            if (!esig_woo_logic::is_after_checkout_enable($order_id)) {

                return false;
            }

           
            esig_woo_logic::save_after_checkout_order_id($order_id);
            
            $noWooAgreement  = get_post_meta($order_id, 'woo_esig_agreement', true);
            
            // Logic for YITH WooCommerce Request A Quote Premium
            if($noWooAgreement=="no" && class_exists('YITH_Request_Quote')){
               
                esig_woo_logic::yith_quote_agreement($order_id,'after_checkout');
            }

            $doc_list = esig_woo_logic::get_after_checkout_doc_list($order_id);
            
           
            $sad_page = false;
            foreach ($doc_list as $document_id => $value) {
                if ($value == "no") {
                    $links = esig_woo_logic::inviteLinkAfterCheckout($document_id);
                    if ($links) {
                        $sad_page = $links;
                    } else {
                        $sad_page = get_permalink($document_id);
                    }
                    WP_E_Sig()->meta->add($document_id, "form-integration", "woo");
                    break;
                }
            }

            if ($sad_page) {

                //$permalink = get_permalink($sad_page);
                wp_safe_redirect($sad_page);
                exit;
            }

            esig_woo_logic::remove_after_checkout_order_id();
            return $order_id;
            //$order = wc_get_order($order_id);
            //$return_url = Esig_Woo_Setting::get_success_page_url($order); 
        }

        /**
         * Register and enqueue admin-specific style sheet.
         *
         * @since     0.1
         *
         * @return    null    Return early if no settings page is registered.
         */
        public function enqueue_admin_styles() {

            $screen = get_current_screen();
            $admin_screens = array(
                'dashboard_page_esign-woocommerce-about',
                'woocommerce_page_wc-settings',
                'e-signature_page_esign-woo',
                'e-signature_page_esign-woocommerce'
            );

            if (in_array(esig_woocommerce_get("id",$screen), $admin_screens)) {
                wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/esign-woocommerce.css', __FILE__), array());
            }
        }

        public function esignature_all_settings($settings_esignature) {
            /**
             * Check the current section is what we want
             * */
            if (!function_exists('WP_E_Sig'))
                return $settings_esignature;

            $img_link = ESIGN_ASSETS_DIR_URI . "/images/approveme-badge.png";

            // Add Title to the Settings
            $settings_esignature[] = array('name' => __('WooCommerce Digital Signature', 'esig'), 'type' => 'title', 'desc' => __('<div class="esign-woo-container"><div class="esign-woo-box notice-success"><h3><strong>Get Started:</strong></h3> <p>A Global Contract is pretty rad... it lets you set a “global contract” or "global agreement" for your entire e-commerce store. In short you can require ALL customers (regardless of the products they purchase) to sign a legal contract before completing their checkout.<br /><br />You can also attach a individual documents to individual products on the <a href="edit.php?post_type=product">product page</a>.</p><p>This section lets you customize the WP E-Signature & Woocommerce Global Settings<p><p><a href="https://www.approveme.com/profile" class="button-primary">Get My Approveme Downloads </a> <a href="index.php?page=esign-woocommerce-about" class="button">Need help getting started?</a></p></div><div class="esign-woo-box-right"><img src="' . $img_link . '"></div></div>', 'esig'), 'id' => 'wpesignature');

            // Add first checkbox option
            $settings_esignature[] = array(
                'name' => __('Woocommerce Agreement', 'esig'),
                'desc_tip' => __('This will automatically enable E-signature agreement', 'esig'),
                'id' => 'esign_woo_agreement_setting',
                'type' => 'checkbox',
                'css' => 'min-width:300px;padding:0px !important;',
                'desc' => __('Enable', 'esig'),
            );


            // checking for a subscription to display this option  
            if (esig_woo_logic::subscriptionPluginExists()) 
            {
                    $settings_esignature[] = array(
                        'name' => '',
                        'desc_tip' => __('This will automatically enable E-signature agreement it only works on WooCommerce subscription plugin.', 'esig'),
                        'id' => 'esign_woo_agreement_in_product_renewal',
                        'type' => 'checkbox',
                        'css' => 'min-width:300px;padding:0px !important;',
                        'desc' => __('Enable agreement in product renewal', 'esig'),
                    );
            }

            // adding action dropdown 
            $settings_esignature[] = array(
                'name' => __('Signing Logic', 'esig'),
                'desc_tip' => __('Select an option to trigger your document before or after checkout.', 'esig'),
                'id' => 'esign_woo_logic',
                'type' => 'select',
                'css' => 'min-width:300px;padding:0px !important;',
                'options' => $this->signing_logic(),
            );

            $settings_esignature[] = array(
                'name' => "",
                'id' => 'esign_woo_after_checkout_logic',
                'type' => 'select',
                'default' => 'on-hold',
                'css' => 'display:none;',
                'options' => $this->condition_logic(),
            );
            // adding sad dropdown. 
            $settings_esignature[] = array(
                'name' => __('Agreement Document', 'esig'),
                'desc_tip' => __('This WooCommerce settings page lets you specify a Stand Alone Document that all WooCommerce customers are required to sign in order to complete the checkout process. Once the document has been signed they will be redirected to the final checkout page.', 'esig'),
                'id' => 'esign_woo_sad_page',
                'type' => 'select',
                'css' => 'min-width:300px;padding:0px !important;',
                'options' => $this->esig_sad->esig_get_sad_pages(),
            );

            $settings_esignature[] = array('type' => 'sectionend', 'id' => 'wpesignature');

            return $settings_esignature;
        }

        public function signing_logic() {
            return array(
                "before_checkout" => "Redirect user to sign before checkout",
                "after_checkout" => "Redirect user to esign after checkout",
            );
        }

        public function condition_logic() {
            return array(
                "completed" => "When order status completed",
                "on-hold" => "When order status on-hold",
                "processing" => "When order status processing"
            );
        }

        /**
         * Create the section beneath the products tab
         * */
        public function esignature_add_section($sections) {

            $sections['wpesignature'] = __('WP E-signature', 'esig');
            return $sections;
        }

        public function esig_woo_cart_empty() {

            if (!function_exists('WP_E_Sig')) {
                return;
            }

            WC()->session->set(self::GLOBAL_AGREEMENT, NULL);
            WC()->session->set(self::TEMP_ORDER_ID, NULL);
        }

        public function esig_new_woo_order($order_id) {
            global $woocommerce;
            // order id temporary 
            $order_id = esig_woo_logic::orderIdValid($order_id);
            esig_woo_logic::save_temp_order_id($order_id);
            if (is_array($woocommerce->cart->cart_contents) && sizeof($woocommerce->cart->cart_contents) > 0) {

                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                    if (isset($cart_item[self::PRODUCT_AGREEMENT]) && is_array($cart_item[self::PRODUCT_AGREEMENT])) {
                        $esig_agreement = $cart_item[self::PRODUCT_AGREEMENT];
                        if (isset($esig_agreement['document_id']) && isset($esig_agreement['signed']) == 'yes') {
                            esig_woo_logic::save_document_meta($esig_agreement['document_id'], $order_id);
                        }
                    }
                }
            }

            if (esig_woo_logic::is_global_agreement_enabled()) {
                $global_agreement = esig_woo_logic::get_global_agreement();
                if (isset($global_agreement['signed']) == 'yes' and isset($global_agreement['document_id'])) {
                    esig_woo_logic::save_document_meta($global_agreement['document_id'], $order_id);
                }
            }

            //$this->esig_after_checkout_form($order_id);
        }

        public function signature_process_done($args) {

            $docId = esig_woocommerce_sanitize_init(esig_woocommerce_get("document_id", $args));
            $sad_doc_id = esig_woocommerce_sanitize_init(esig_woocommerce_get("sad_doc_id", $args));
            
            WP_E_Sig()->meta->add($docId, 'esig_woocommerce_ratting_doc_id',  $docId);

            if (esig_woo_logic::get_after_checkout_order_id()) {

                $order_id = esig_woo_logic::get_after_checkout_order_id();
                $afterCheckoutDocList = esig_woo_logic::get_after_checkout_doc_list($order_id);

                if(!is_array($afterCheckoutDocList)) return false;

                if (!array_key_exists($sad_doc_id, $afterCheckoutDocList)) {
                    return false;
                }

                $result = $this->esig_checkout_after($order_id);

                if ($result) {

                    $order = wc_get_order($result);
                    $return_url = $this->get_return_url($order);
                    wp_redirect($return_url);
                    exit;
                }
            }

            global $woocommerce;

            if (WC()->cart->cart_contents_count == 0) {
                return false;
            }

            if (is_null(WC()->session) && sizeof($woocommerce->cart->cart_contents) == 0) {
                return false;
            }
            $productAgreement = false;
            if (is_array($woocommerce->cart->cart_contents) && sizeof($woocommerce->cart->cart_contents) > 0) {

                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                    if (isset($cart_item[self::PRODUCT_AGREEMENT]) && is_array($cart_item[self::PRODUCT_AGREEMENT])) {

                        $esig_agreement = $cart_item[self::PRODUCT_AGREEMENT];
                        if ($sad_doc_id == $esig_agreement['agreement_id']) {
                            $productAgreement = true;
                            //esig_woo_logic::make_agreement_signed($cart_item_key, $document_id);
                        }
                    }
                }
            }

            if (esig_woo_logic::is_global_agreement_enabled()) {
                $global_id = esig_woo_logic::get_global_agreement_id();
                if ($sad_doc_id == $global_id) {
                    $productAgreement = true;
                    // esig_woo_logic::make_global_agreement_signed($document_id);
                }
            }

            if (!$productAgreement) {

                return false;
            }

            $this->esig_before_checkout_form();

            wp_redirect(wc_get_checkout_url());
            exit;
        }

        public function esig_signature_after($args) {

            if (!function_exists('WP_E_Sig')) {
                return;
            }

            $document_id = $args['invitation']->document_id;

            $sad_doc_id = $args['sad_doc_id'];

            if (esig_woo_logic::get_after_checkout_order_id()) {
                $order_id = esig_woo_logic::get_after_checkout_order_id();
                $afterCheckoutDocList = esig_woo_logic::get_after_checkout_doc_list($order_id);
                
                if(!is_array($afterCheckoutDocList)) return false;

                if (!array_key_exists($sad_doc_id, $afterCheckoutDocList)) {
                    return false;
                }

                $result = $this->after_checkout_signed_update($order_id, $sad_doc_id, $document_id);
                /* if ($result) {
                  $order = wc_get_order($result);
                  $return_url = $this->get_return_url($order);
                  wp_redirect($return_url);
                  exit;
                  } */
            }

            global $woocommerce;

            if (is_null(WC()->session) && sizeof($woocommerce->cart->cart_contents) == 0) {
                return;
            }
            $productAgreement = false;
            if (is_array($woocommerce->cart->cart_contents) && sizeof($woocommerce->cart->cart_contents) > 0) {

                foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                    if (isset($cart_item[self::PRODUCT_AGREEMENT]) && is_array($cart_item[self::PRODUCT_AGREEMENT])) {
                        $esig_agreement = $cart_item[self::PRODUCT_AGREEMENT];
                        if ($sad_doc_id == $esig_agreement['agreement_id']) {
                            $productAgreement = true;
                            esig_woo_logic::make_agreement_signed($cart_item_key, $document_id);
                          
                        }
                    }
                }
            }

            if (esig_woo_logic::is_global_agreement_enabled()) {
                $global_id = esig_woo_logic::get_global_agreement_id();
                if ($sad_doc_id == $global_id) {
                    esig_woo_logic::make_global_agreement_signed($document_id);
                } else {
                    if (!$productAgreement) {
                        return false;
                    }
                }
            } else {
                if (!$productAgreement) {
                    return false;
                }
            }


            /* $this->esig_before_checkout_form();

              wp_redirect(wc_get_checkout_url());
              exit; */
        }

        public function after_checkout_signed_update($order_id, $sad_doc_id, $document_id) {



            esig_woo_logic::update_after_checkout_doc_list($order_id, $sad_doc_id, $document_id);

            /* $result = $this->esig_checkout_after($order_id);
              if (!$result) {
              return $order_id;
              } */
        }

        public function esig_misc_page_more_acitons($misc_more_actions) {

            $class = (isset($_GET['page']) && esig_woocommerce_get('page') == 'esign-woocommerce') ? 'misc_current' : '';
            $misc_more_actions .= ' | <a class="misc_link ' . $class . '" href="admin.php?page=wc-settings&tab=checkout">' . __('WooCommerce', 'esig') . '</a>';

            return $misc_more_actions;
        }

        /*         * *
         * Adding success page content view 
         * @Since 1.1.3
         */

        public function esig_before_checkout_form() {

            if (!function_exists('WP_E_Sig')) {
                return;
            }


            $agreement_id = $this->is_signature_needs('before_checkout');

            $esign_woo_sad_page = esig_woo_logic::get_sad_page_id($agreement_id);

            if ($esign_woo_sad_page && get_post_status ( $esign_woo_sad_page )=="publish") {

                $permalink = get_permalink($esign_woo_sad_page);
                wp_redirect($permalink);
                exit;
            }
            return false;
        }

        /*         * *
         *  Return bolean 
         * 
         * */

        public function is_signature_needs($is_true) {

            global $woocommerce;

            $sad_document_id = false;

           

            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {

               
                // check for renewal agreement allowed or not if not allowed abort if allowed proceed. 
                if (!esig_woo_logic::renewalContractAllowed()) {
                    
                    if (is_array($cart_item) && array_key_exists("subscription_renewal", $cart_item)) {
                        break;
                    }
                }

                $esig_agreement = isset($cart_item[self::PRODUCT_AGREEMENT]) ? $cart_item[self::PRODUCT_AGREEMENT] : null;
                $agreement_logic = isset($esig_agreement['agreement_logic']) ? $esig_agreement['agreement_logic'] : null;
                $agreement_signed = isset($esig_agreement['signed']) ? $esig_agreement['signed'] : null;
                if ($agreement_logic == $is_true && $agreement_signed == 'no') {

                    $sad_document_id = $esig_agreement['agreement_id'];
                    break;
                } 
                
                $productId = esig_woocommerce_get("product_id",$cart_item);
                if(esig_woo_logic::is_signature_required($productId)){
                     $agreementLogic = esig_woo_logic::get_agreement_logic($productId);
                     if($agreementLogic == "before_checkout"){
                          
                         
                          if($agreement_signed=="yes"){
                              continue;
                          }
                          $sad_document_id = esig_woo_logic::get_agreement_id($productId);
                          
                          if($sad_document_id){ 
                             
                              WC()->cart->cart_contents[$cart_item_key][self::PRODUCT_AGREEMENT]['signed'] = 'no';
                              WC()->cart->cart_contents[$cart_item_key][self::PRODUCT_AGREEMENT]['agreement_id'] = $sad_document_id;
                              WC()->cart->cart_contents[$cart_item_key][self::PRODUCT_AGREEMENT]['agreement_logic'] = "before_checkout";
                              WC()->cart->set_session();
                               break; 
                              
                          }
                     }
                }
                
               
            }
            // if sad page is true then return 

            if ($sad_document_id) {
                return $sad_document_id;
            }
            // global agreement 
            $global_document_id = esig_woo_logic::get_global_doc_id_from_session($is_true);

            if ($global_document_id) {
                return $global_document_id;
            }
            return false;
        }

        /**
         * Return an instance of this class.
         * @since     0.1
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }

    }

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

endif;

