<?php
/**
 * Distance_Rate_Shipping_i18n
 * This class defines all the required code 
 * to load and define internationlization files.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_i18n{
    /**
     * Store the text domain
     * @access protected
     * @var string $text_domain
     */
    private $text_domain;
    /**
     * Store the text domain path
     * @access protected
     * @var string $text_domain_path
     */
    private $text_domain_path;
    /**
     * __constructor function
     * To initiate class variables and functions.
     * It runs on creation of class instance/object.
     * @return void
     * @since 1.0.0
     */
    public function __construct($text_domain, $text_domain_path){
        $this->text_domain = $text_domain;
        $this->text_domain_path = $text_domain_path;
    }

    /**
     * load_textdomain function 
     * function to load and define textdomain of plugin.
     * it adds internationalization functionality by
     * adding language ".pot" files.
     * 
     * @since 1.0.0
     * @param string $domain type of hooks either action, filter or shortcode
     * @param string $path name of the hook
     */
    public function load_textdomain(){
        load_plugin_textdomain($this->text_domain, false, $this->text_domain_path);
    }
}