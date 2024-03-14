<?php
/**
 * Plugin Name: Product Category Dropdowns
 * Description: Displays product categories as dependent drop-down selects.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://hottons.com
 * License: GPLv2     
 * Requires at least: 4.7
 * Tested up to: 6.3
 *
 * Text Domain: product-category-dropdowns
 *
 * WC requires at least: 3.0
 * WC tested up to: 8.0.2
 * 
 * @package ProductCategoryDropdowns
 * @author Pektsekye
 */
if (!defined('ABSPATH')) exit;

final class Pektsekye_ProductCategoryDropdowns {


  protected static $_instance = null;

  protected $_pluginUrl; 
  protected $_pluginPath;    
  

  public static function instance(){
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
      self::$_instance->initApp();
    }
    return self::$_instance;
  }


  public function __construct(){
    $this->_pluginPath = plugin_dir_path(__FILE__);
    $this->_pluginUrl  = plugins_url('/', __FILE__);
  }


  public function initApp() {
    $this->includes();
    $this->init_hooks();
  }
  
  
  public function includes() {    
    include_once('Widget/Selector.php');        
  }
  

  private function init_hooks(){ 
    add_action('wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts'));     
    add_action('widgets_init', array( $this, 'register_widgets'));  
      
    add_shortcode('product_category_selector', array( $this, 'show_selector_by_shortcode'));   
  }      


  public function enqueue_frontend_scripts() {
    wp_enqueue_script('product_category_dropdowns', $this->_pluginUrl . 'view/frontend/web/main.js', array('jquery', 'jquery-ui-widget'));
    wp_enqueue_style('product_category_dropdowns', $this->_pluginUrl . 'view/frontend/web/main.css' );	     	  		  			
  }
  
  
  public function register_widgets() {
    register_widget('Pektsekye_ProductCategoryDropdowns_Widget_Selector');      
  } 
  
  
  public function show_selector_by_shortcode($atts) {
  
    include_once($this->getPluginPath() . 'Block/Selector.php');
          
    $block = new Pektsekye_ProductCategoryDropdowns_Block_Selector();
    $block->setWidgetId('content');

    ob_start();

    $block->toHtml();

    $contents = ob_get_clean();
    
    return $contents;
  } 
    
  
  public function getPluginUrl(){
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath(){
    return $this->_pluginPath;
  }  
    
}


function Pektsekye_PCD(){
	return Pektsekye_ProductCategoryDropdowns::instance();
}

// If WooCommerce plugin is installed and active.
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array())) || in_array('woocommerce/woocommerce.php', array_keys((array) get_site_option('active_sitewide_plugins', array())))){
  Pektsekye_PCD();
}





