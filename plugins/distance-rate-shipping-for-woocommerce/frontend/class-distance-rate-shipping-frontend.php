<?php
/**
 * Distance_Rate_Shipping_Frontend
 * This class is core class of plugin's frontend.
 * It defines all the required code that will work in frontend.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Frontend{

    /**
     * Store the name of the plugin
     * @access protected
     * @var string $plugin_name
     */
    protected $plugin_name;

    /**
     * Store the version of the plugin
     * @access protected
     * @var string $plugin_version
     */
    protected $plugin_version;

    /**
     * __constructor function
     * To initiate class variables and functions.
     * It runs on creation of class instance/object.
     * @since 1.0.0
     */
    public function __construct($plugin_name, $plugin_version){
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
    }

    /**
     * enquque_styles function
     * To register and enqueue style files into plugin.
     * @since 1.0.0
     */
    public function enquque_styles(){
        wp_enqueue_style( $this->plugin_name.'-frontend', plugin_dir_url( __FILE__ ) .'css/distance-rate-shipping-frontend.css' , '', $this->plugin_version, 'all');
    }
    /**
     * enqueue_scripts function
     * To register and enqueue scripts files into plugin.
     * @since 1.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script( $this->plugin_name.'-frontend', plugin_dir_url( __FILE__ ) .'js/distance-rate-shipping-frontend.js', ['jquery'], $this->plugin_version, false );
    }

    /**
     * render_view function
     * render view of page.
     * 
     * @param string $slug file name/page slug
     * @since 1.0.0
     */
    public function render_view($slug){
        ob_start();
        include_once plugin_dir_path( dirname( __FILE__ ) ) . 'backend/src/' . $slug . '.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}