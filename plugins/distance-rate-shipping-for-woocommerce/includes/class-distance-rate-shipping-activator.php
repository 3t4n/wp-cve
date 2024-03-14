<?php
/**
 * Distance_Rate_Shipping_Activator
 * Fired during plugin activation
 * This class defines all the required code to run during plugin activation
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Activator{

    /**
     * plugin activation function.
     * function containing code that will be executed during activation of plugin
     * @since 1.0.0
     */
    public function activate(){
        $this->check_dependent_plugin_installed();
    }

    public function check_dependent_plugin_installed(){
        /**
         * check if woocommerce is installed and activated else show them message to do so
         */
        if(is_multisite()){
            if(!class_exists('WooCommerce') || !is_plugin_active_for_network( 'woocommerce/woocommerce.php' ))
                die($this->admin_notice_install_woocommerce());
            }
        else{
            if(!class_exists('WooCommerce') || !is_plugin_active( 'woocommerce/woocommerce.php' )){
                die($this->admin_notice_install_woocommerce());
            }
        }
    }
    public function admin_notice_install_woocommerce(){
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-distance-rate-shipping-config.php';
        $plugin_config = new Distance_Rate_Shipping_Config();
        $class = 'notice notice-warning';
        $url = home_url() . "/wp-admin/plugin-install.php?s=WooCommerce&tab=search&type=term";
        $message = __('WooCommerce Distance Based Shipping plugin require WooCommerce. 
            Please install and activate it.', $plugin_config->get_text_domain());
        $alert = sprintf(
            '<div class="%1$s"><p>%2$s</p><a href="%3$s" target="_blank">Click Here</a></div>',
            esc_attr( $class ),
            esc_html( $message ),
            esc_url( $url ),
        );
        return $alert;
    }
}