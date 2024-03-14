<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_css{

    public $plugin_name;
    
    function __construct(){
        $this->plugin_name = 'pi-woocommerce-order-date-time-and-type';

        add_action( 'wp_enqueue_scripts', array($this,'addCss') );

        add_action('woocommerce_thankyou', array($this,'removeSavedDateTime'));

        add_action('wp_head', array($this, 'topMarginFix'),1000);
    }

    function addCss(){
        if(function_exists('is_checkout') && is_checkout()){
            $this->addCssFile();
            $this->inlineCss();
        }
    }

    function cdnUrl(){
        $theme = 'ui-lightness';
        $cdn_url = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/{$theme}/jquery-ui.css";
        return $cdn_url;
    }

    function addCssFile(){
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css',array(), PISOL_DTT_PLUGIN_VERSION);
        $jquery_ui = apply_filters('pisol_dtt_jquery_ui_theme',$this->cdnUrl());
        wp_deregister_style( 'jquery-ui' );
        wp_register_style( 'jquery-ui',  $jquery_ui);
        wp_enqueue_style('jquery-ui');
    }

    function inlineCss(){
        $css = "";
        $css .= $this->deliveryTypeButton();
        wp_add_inline_style( $this->plugin_name, $css);
    }

    function deliveryTypeButton(){
        
        $pi_button_bg_color = pisol_dtt_get_setting('pi_button_bg_color','#cccccc');
        $pi_active_button_bg_color = pisol_dtt_get_setting('pi_active_button_bg_color','#000000');
        $pi_button_text_color = pisol_dtt_get_setting('pi_button_text_color','#000000');
        $pi_active_button_text_color = pisol_dtt_get_setting('pi_active_button_text_color','#ffffff');
        $css = '
            .pi_delivery_type .woocommerce-input-wrapper label, .pi_delivery_type .woocommerce-input-wrapper .woocommerce-radio-wrapper label{
                background-color:'.$pi_button_bg_color.';
                color:'.$pi_button_text_color.';
            }

            .pi_delivery_type .input-radio:checked + label, 
            .pi_delivery_type .woocommerce-input-wrapper label.active_type,  .pi_delivery_type .woocommerce-input-wrapper .woocommerce-radio-wrapper input:checked + label{
                background-color:'.$pi_active_button_bg_color.';
                color:'.$pi_active_button_text_color.';
            }
        ';
       return $css;
    }
    
    function removeSavedDateTime($order_id){
        echo '<script>';
            echo 'jQuery(function($){
                
                var fields = ["pi_system_delivery_date", "pi_delivery_time", "billing_email", "billing_first_name", "billing_last_name", "billing_phone", "billing_company", "shipping_first_name", "shipping_last_name", "shipping_company", "order_comments", "createaccount", "ship-to-different-address-checkbox"];
                var length = fields.length;
                for (var i = 0; i < length; i++) {
                    var field = fields[i];
                    var name = "pisol_"+field;
                    localStorage.removeItem(name);
                }
            });';
        echo '</script>';
    }

    function topMarginFix(){
        if(function_exists('is_admin_bar_showing') && is_admin_bar_showing() && is_checkout()){
            echo '
                <style>
                html{
                    margin-top:0 !important;
                }
                </style>
            ';
        }
    }
}

add_action('wp_loaded',function(){
    /**
     * This filter allow you to hide all the fields added by this plugin 
     * so you can use this to disable the plugin when you have virtual product in
     * your cart
     */
    $pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
    if($pisol_disable_dtt_completely){
        return ;
    }
    
    new pi_dtt_css();
});
