<?php

/**
 *
 * @package ESIG_WOOCOMMERCE_Shortcode
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('ESIG_WOOCOMMERCE_Shortcode')) :

    class ESIG_WOOCOMMERCE_Shortcode {

        /**
         * Instance of this class.
         * @since    1.0.1
         * @var      object
         */
        protected static $instance = null;
        private $esig_sad= null;
        private $esig_woo_document_id = null;
        private $plugin_slug = null;

        /**
         * Slug of the plugin screen.
         * @since    1.0.1
         * @var      string
         */
        protected $plugin_screen_hook_suffix = null;

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

            //adding shortcode
            add_shortcode('esig_woo_product_summary', array($this, 'esig_woo_product_summary'));    
            add_shortcode('esig-woo-order-details', array($this, 'esig_order_details'));
            add_filter('show_sad_invite_link', array($this, 'show_sad_invite_link'), 10, 3);
            //adding metabox hook here
            add_action('add_meta_boxes', array($this, 'esig_woo_add_meta_box'));
            // triggering when woo product saveed
            add_action('save_post', array($this, 'esig_woo_product_save'));
            add_filter('esignature_content', array($this, 'esignature_content'), 10, 2);
        }

        final function esignature_content($docContent, $docId) {


            $order_id = esig_woo_logic::get_after_checkout_order_id();
           
            if (!$order_id) {
                $invitation = WP_E_Sig()->invite->getInviteBy('document_id', $docId);
                if (!$invitation) {
                    return $docContent;
                }
                $order_id = $this->get_esig_order_id($invitation->document_id, $invitation->invitation_id);
            }

            if (!$order_id) {
                return $docContent;
            }
            $data = esig_woo_logic::orderDetails($order_id);
            $latestContent = WP_E_View::instance()->replace_variable($docContent, $data);
            return $latestContent;
        }

        final function show_sad_invite_link($show, $doc, $page_id) {
            if (!isset($doc->document_content)) {
                return $show;
            }
            $document_content = $doc->document_content;
            $document_raw = WP_E_Sig()->signature->decrypt(ENCRYPTION_KEY, $document_content);

            if (has_shortcode($document_raw, 'esig-woo-order-details')) {
                $show = false;
                return $show;
            }
            return $show;
        }

        /**
         * Save the meta when the woo product is saved.
         *
         * @param int $post_id The ID of the post being saved.
         */
        public function esig_woo_product_save($post_id) {

            // check for wp e-signature exists .  
            if(!function_exists("WP_E_Sig"))
            {
                return $post_id; 
            }
            // Check if our nonce is set.
            if (!ESIG_POST('esig_woo_product_box_nonce'))
                   return $post_id;

            $nonce = ESIG_POST('esig_woo_product_box_nonce');

            // Verify that the nonce is valid.
            if (!wp_verify_nonce($nonce, 'esig_woo_product_nonce'))
                return $post_id;

            // If this is an autosave, our form has not been submitted,
            // so we don't want to do anything.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;


            // Check if post type is not product page return 
            if ('product' != ESIG_POST('post_type'))
                return $post_id;

            // check the user permission 
            if (!current_user_can('edit_page', $post_id))
                return $post_id;


            // Sanitize the user input.
            $esig_product_agreement = sanitize_text_field(ESIG_POST('esig_product_agreement'));
            $esign_woo_sad_page = sanitize_text_field(ESIG_POST('esign_woo_sad_page'));
            $esign_woo_signing_logic = sanitize_text_field(ESIG_POST('esign_woo_sign_logic'));

            // Update the meta field.
            update_post_meta($post_id, '_esig_woo_meta_product_agreement', $esig_product_agreement);

            update_post_meta($post_id, '_esig_woo_meta_sad_page', $esign_woo_sad_page);
            update_post_meta($post_id, '_esign_woo_sign_logic', $esign_woo_signing_logic);
            // update_post_meta($post_id, '_esig_agreement_required', esigpost('esig_agreement_required'));
        }

        public function esig_woo_add_meta_box($post_type) {
            $post_types = array('product');     //limit meta box to certain post types
            if (in_array($post_type, $post_types)) {
                add_meta_box(
                        'E-signature Option'
                        , __('Esignature Option', 'esig-woocommerce')
                        , array($this, 'esig_woo_render_meta_box_content')
                        , $post_type
                        , 'side'
                        , 'low'
                );
            }
        }

        public function esig_woo_render_meta_box_content($post) {

            if (!function_exists('WP_E_Sig')) {
                __('<a href="admin.php?page=esign-not-core">E-signature</a>', 'esig-woocommerce');
                return;
            }
            $branding_template = dirname(__FILE__) . "/views/woocommerce-esig-product-view.php";

            $template_data = get_object_vars($post);

            WP_E_Sig()->view->renderPartial('', $template_data, true, '', $branding_template);
        }
        
        
        public function esig_woo_product_summary($atts){
            
             extract(shortcode_atts(array(
                            ), $atts, 'esig-woo-order-details'));
             
              $productName  = "" ; 
              if (!is_null(WC()->cart)) {  
                if (WC()->cart->get_cart_contents_count() > 0) {

                    foreach (WC()->cart->get_cart() as $cart_item) {
                        //echo $obj->name; 
                             $productName .= $cart_item['data']->name . ',';
                               
                    }
                }
            }
             
            
           return $productName;
             
        }

        public function esig_order_details($atts) {


            $api = WP_E_Sig();

            extract(shortcode_atts(array(
                            ), $atts, 'esig-woo-order-details'));

            if (ESIG_GET('invite')) {
                $invite_hash = ESIG_GET('invite');
                $invitation = $api->invite->getInviteBy('invite_hash', $invite_hash);
               
            }

            if (ESIG_GET('did')) {
                $document_id = $api->document->document_id_by_csum(ESIG_GET('did'));
                $invitation = $api->invite->getInviteBy('document_id', $document_id);
            }

            if (ESIG_GET('esigpreview')) {
                $document_id = ESIG_GET('document_id');
                $invitation = $api->invite->getInviteBy('document_id', $document_id);
            }

            if (get_option('esig_global_document_id')) {
                $document_id = get_option('esig_global_document_id');
                $invitation = $api->invite->getInviteBy('document_id', $document_id);
                
            }

            if (isset($invitation)) {
                $order_id = $this->get_esig_order_id($invitation->document_id, $invitation->invitation_id);
               
            } else {
                global $esig_woo_document_id, $esig_woo_order_id;
                if (!is_null($esig_woo_document_id) && !is_null($esig_woo_order_id)) {
                    $order_id = $esig_woo_order_id;
                } else {
                    $order_id = esig_woo_logic::get_after_checkout_order_id();
                } 
            }

            if (!$order_id) {
                return "";
            }
       
           if ( !get_post_status ( $order_id ) ) {
               
               return '<font color="red"><b>Error : Order not found<b></font>';
           }
   
            if ($order_id) {
                
                $template_data = array(
                    "order_id" => esig_woo_logic::orderIdValid($order_id)
                );

                $order_templates = dirname(__FILE__) . "/views/order-details.php";
                $html = $api->view->renderPartial('', $template_data, false, '', $order_templates);

                return $html;
            }


            return;
        }

        public function get_esig_order_id($document_id = null, $invitation_id = null) {

            $meta = WP_E_Sig()->meta->get($document_id, 'esig-order_id');
            if ($meta) {
                return $meta;
            } else {
                return WP_E_Sig()->setting->get_generic('esig-order-id' . $invitation_id);
            }
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