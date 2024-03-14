<?php
/**
 * Distance_Rate_Shipping_Config
 * This class defines all the required code for plugin configuration.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Config{
    /**
     * Store the name of the plugin
     * @access private
     * @var string $plugin_name
     */
    private $plugin_name;

    /**
     * Store the version of the plugin
     * @access private
     * @var string $plugin_version
     */
    private $plugin_version;

    /**
     * Store the compatible WordPress version for the plugin
     * @access private
     * @var string $wordpress_version
     */
    private $wordpress_version;

    /**
     * Store the required php version for the plugin
     * @access private
     * @var string $php_version
     */
    private $php_version;

    /**
     * Store the text domain of the plugin
     * @access private
     * @var string $text_domain
     */
    private $text_domain;

    /**
     * Store the text domain path of the plugin
     * @access private
     * @var string $text_domain_path
     */
    private $text_domain_path;

    /**
     * Store the reference of the plugin's shipping method id
     * @access private
     * @var string $shipping_method_id
     */
    private $shipping_method_id;

    /**
     * Store the reference of the plugin's shipping method title
     * @access private
     * @var string $shipping_method_title
     */
    private $shipping_method_title;

    /**
     * Store the reference of the plugin's shipping method description
     * @access private
     * @var string $shipping_method_description
     */
    private $shipping_method_description;

    /**
     * Store the reference of the plugin's shipping method supports
     * @access private
     * @var array $shipping_method_supports
     */
    private $shipping_method_supports;

    /**
     * Store the reference of the distance matrix api key setting
     * @access private
     * @var array $gdm_apikey
     */
    private $gdm_apikey;

    /**
     * Store the reference of the measurement standard setting
     * @access private
     * @var array $measurement_standard
     */
    private $measurement_standard;

    /**
     * __constructor function
     * To initiate class variables.
     * It run on object creation of class.
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function __construct(){
        $this->plugin_name = "distance-rate-shipping-for-woocommerce";
        $this->plugin_version = "1.0.0";
        $this->wordpress_version = "6.0";
        $this->php_version = "7.4";
        $this->text_domain = "distance-rate-shipping-for-woocommerce";
        $this->text_domain_path = "languages";
        $this->shipping_method_id = "distance_based_shipping";
        $this->shipping_method_title = "Distance Based Shipping";
        $this->shipping_method_description = "Distance based shipping will calculate shipping rates based on the distance between shop address and customer's shipping addres";
        $this->shipping_method_supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );

        $prefix = str_replace('-', '_',$this->plugin_name);
        $this->gdm_apikey = (!empty(get_option( $prefix.'_options' ))) ? get_option( $prefix.'_options' )[$prefix.'_apikey'] : '';

        $this->measurement_standard = (!empty(get_option( $prefix.'_options' ))) ? get_option( $prefix.'_options' )[$prefix.'_measurement_standard'] : '';
    }

    /**
     * get_plugin_name function
     * return name of plugin.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_plugin_name(){
        return $this->plugin_name;
    }

    /**
     * get_plugin_version function
     * return version of plugin.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_plugin_version(){
        return $this->plugin_version;
    }

    /**
     * get_wordpress_version function
     * return WordPress version with which plugin is compatible and tested.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_wordpress_version(){
        return $this->wordpress_version;
    }

    /**
     * get_php_version function
     * return required PHP version on which plugin will run without issues.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_php_version(){
        return $this->php_version;
    }

    /**
     * get_text_domain function
     * return text domain of plugin.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_text_domain(){
        return $this->text_domain;
    }

    /**
     * get_text_domain_path function
     * return text domain path of plugin.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_text_domain_path(){
        return $this->text_domain;
    }

    /**
     * get_shipping_method_id function
     * return shipping method id.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_shipping_method_id(){
        return $this->shipping_method_id;
    }

    /**
     * get_shipping_method_title function
     * return shipping method title.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_shipping_method_title(){
        return $this->shipping_method_title;
    }

    /**
     * get_shipping_method_description function
     * return methos description.
     * @access public 
     * @return string
     * @since 1.0.0
     */
    public function get_shipping_method_description(){
        return $this->shipping_method_description;
    }

    /**
     * get_shipping_method_supports function
     * return features that method will support.
     * @access public
     * @return array
     * @since 1.0.0
     */
    public function get_shipping_method_supports(){
        return $this->shipping_method_supports;
    }

    /**
     * get_gdm_api_key function
     * return google distance matrix apikey setting value.
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_gdm_apikey(){
        return $this->gdm_apikey;
    }

    /**
     * get_measurement_standard function
     * return measurement standard setting value
     * @access public
     * @return string
     * @since 1.0.0
     */
    public function get_measurement_standard(){
        return $this->measurement_standard;
    }
}