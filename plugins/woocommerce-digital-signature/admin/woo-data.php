<?php

class esigWooData {

    protected static $instance = null;

    public static function instance() {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_filter('esig_sif_buttons_filter', array($this, 'add_sif_gravity_buttons'), 10, 1);
        add_filter('esig_text_editor_sif_menu', array($this, 'add_sif_gf_text_menu'), 10, 1);
        add_filter('esig_admin_more_document_contents', array($this, 'document_add_data'), 10, 1);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts() {

        $screen = get_current_screen();
       
        $admin_screens = array(
            'admin_page_esign-add-document',
            'admin_page_esign-edit-document',
            'product',
            'woocommerce_page_wc-settings',
            'shop_order'
        );

        // Add/Edit Document scripts
        if (in_array(esig_woocommerce_get("id",$screen), $admin_screens)) {

            // wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'assets/css/esig_template.css', __FILE__ ));
            wp_enqueue_script('jquery');
            wp_enqueue_script('esig-woocommerce-admin-script', plugins_url('assets/js/esig-commerce.js', __FILE__), array('jquery', 'jquery-ui-dialog'), "" , true);
            
             wp_localize_script( 'esig-woocommerce-admin-script', 'esig_woo_params', array(
			'esig_woo_order_nonce'    => wp_create_nonce( 'esig-woo-order' )
		) );
        }
        
       
    }

    public function add_sif_gravity_buttons($sif_menu) {

        $esig_type = ESIG_GET('esig_type');
        $document_id = ESIG_GET('document_id');
        if (empty($esig_type) && !empty($document_id)) {
            $document_type = WP_E_Sig()->document->getDocumenttype($document_id);
            if ($document_type == "stand_alone") {
                $esig_type = "sad";
            }
        }
        if ($esig_type != 'sad') {
            return $sif_menu;
        }
        // $plugins['esig_sif'] = plugin_dir_url(__FILE__) . 'assets/js/esig-gravity-sif-buttons.js';
        $sif_menu .= '{text: "Insert Woo Details",value: "woocommerce",onclick: function () {  tb_show( "+ Insert Woo Details", "#TB_inline?width=500&height=300&inlineId=esig-woocommerce-option");esign.tbSize(450);}},';

        return $sif_menu;
    }

    public function add_sif_gf_text_menu($sif_menu) {

        $esig_type = ESIG_GET('esig_type');
        $document_id = ESIG_GET('document_id');

        if (empty($esig_type) && !empty($document_id)) {
            $document_type = WP_E_Sig()->document->getDocumenttype($document_id);
            if ($document_type == "stand_alone") {
                $esig_type = "sad";
            }
        }

        if ($esig_type != 'sad') {
            return $sif_menu;
        }
        $sif_menu['woocommerce'] = array('label' => "Insert Woo Details");
        return $sif_menu;
    }

    final function document_add_data($more_option_page) {


        // $more_option_page = '';
        $assets_dir = ESIGN_ASSETS_DIR_URI;

        $more_option_page .= '<div id="esig-woocommerce-option" class="esign-form-panel" style="display:none;">
        	        
        	        
                	               <div align="center"><img src="' . esc_url($assets_dir) . '/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
                    			
                                    
                    				<div id="esig-woo-form-first-step">
                        				
                                        	<h4 align="center" class="esign-form-header">' . __('Insert available woocommerce tag to contract.', 'esig-woo') . '</h4>
                                            	
                        				<p id="create_gravity" align="center">';

        $more_option_page .= '
                        			
                        				<p id="select-woo-form-list" align="center">
                                	    
                        		        <select data-placeholder="Choose a Option..." class="chosen-select" tabindex="2" id="esig-woocommerce-tag" name="esig-woocommerce-tag">
                        			     <option value="sddelect">' . __('Insert Woocommerce shortcode', 'esig-woo') . '</option>';

        $tags = $this->wooTag();

        foreach ($tags as $value => $label) {

            $more_option_page .= '<option value="' . $value . '">' . $label . '</option>';
        }

        $more_option_page .= '</select>
                                	    
                        				</p>
                         	  
                                	    </p>
                                	    
                                        <p id="upload_gravity_button" align="center">
                                           <a href="#" id="esig-insert-woo-tag" class="button-primary esig-button-large">' . __('Insert Details', 'esig-woo') . '</a>
                                         </p>
                                     
                                    </div>  <!-- Frist step end here  --> ';

        $more_option_page .= '</div><!--- gravity option end here -->';


        return $more_option_page;
    }

    private function wooTag() {
        
    
        $result = array(
            "woo-shortcode" => "Order details shortcode",
            "billing_address_1" => "Billing Address 1",
            "billing_address_2" => "Billing Address 2",
            "billing_city" => "Billling City",
            "billing_company" => "Billing Company",
            "billing_country" => "Billing Country",
            "billing_email" => "Billing Email",
            "billing_first_name" => "Billing First Name",
            "billing_last_name" => "Billing Last Name",
            "billing_phone" => "Billing Phone",
            "billing_postcode" => "Billing Postcode",
            "billing_state" => "Billing State",
            "cart_discount" => "Cart Discount",
            "cart_discount_tax" => "Cart Discount Tax",
            "customer_ip_address" => "Customer Ip Address",
            "customer_message" => "Customer Message",
            "customer_note" => "Customer Note",
            //"customer_user"=>"customer_user",
            "customer_user_agent" => "Customer User Agent",
            "display_cart_ex_tax" => "Display Cart Ex Tax",
            "display_totals_ex_tax" => "Display Totals Ex Tax",
            "order_id" => "Order Id",
            "order_currency" => "Order Currency",
            "order_date" => "Order Completed Date",
            "order_create_date" => "Order Create Date",
            "order_paid_date" => "Order Paid Date",
            "order_discount" => "Order Discount",
            "order_key" => "Order Key",
            "order_shipping" => "Order Shipping",
            "order_shipping_tax" => "Order Shipping Tax",
            "order_tax" => "Order Tax",
            "order_total" => "Order Total",
            "order_subtotal" => "Order Subtotal",
            "order_type" => "Order Type",
            "products_name_in_cart" => "Products Name in cart",
            "products_quantity_in_cart" => "Products Quantity in cart",
            "coupon_code" => "Coupon Code",
            "payment_method" => "Payment Method",
            "payment_method_title" => "Payment Method Title",
            "shipping_address_1" => "Shipping Address 1",
            "shipping_address_2" => "Shipping Address 2",
            "shipping_city" => "Shipping City",
            "shipping_company" => "Shipping Company",
            "shipping_country" => "Shipping Country",
            "shipping_first_name" => "Shipping First Name",
            "shipping_last_name" => "Shipping Last Name",
            "shipping_method_title" => "Shipping Method Title",
            "shipping_postcode" => "Shipping Postcode",
            "shipping_state" => "Shipping State",
        );


        // customer wordpress user details 


        $result['customer_wp_username'] = "Customer Username";
        $result['customer_wp_user_displayname'] = "Customer user Displayname";
        $result['customer_wp_user_email'] = "Customer user e-mail";
        $result['customer_wp_user_nicename'] = "Customer user nice name";
        $result['customer_wp_user_firstname'] = "Customer user first name";
        $result['customer_wp_user_lastname'] = "Customer user last name";




        // order product details .
        $items = get_posts(array('post_type' => 'product','numberposts'=>50));

        if ($items) {
            foreach ($items as $itemId => $itemData) {
                $result['product_' . $itemData->ID . '_name'] = $itemData->post_title;
                $result['product_' . $itemData->ID . '_quantity'] = $itemData->post_title . " quantity";
            }
        }

        return $result;
    }

}
