<?php
/**
 * Plugin Name: Product Options for WooCommerce
 * Description: Adds drop-down, radio button and text field options on the product page. So a user can customize product before adding it to the shopping cart.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://www.hottons.com
 * License: GPLv2   
 * Requires at least: 4.7
 * Tested up to: 6.3.2
 *
 * Text Domain: product-options-for-woocommerce
 * Domain Path: /i18n/languages 
 *
 * WC requires at least: 3.0
 * WC tested up to: 8.2.0
 * 
 * @package ProductOptions
 * @author Pektsekye
 */
if (!defined('ABSPATH')) exit;

final class Pektsekye_ProductOptions {


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


  public function initApp(){
    $this->includes();
    $this->init_hooks();
    $this->init_controllers();
  }
  
  
  public function includes(){  
    include_once('Model/Observer.php');              
  }
  

  private function init_hooks(){ 

    new Pektsekye_ProductOptions_Model_Observer();

    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') , 15);// 15 to call it after WooCommerce                         
  }    


  private function init_controllers(){
		if ($this->is_request('frontend')){
      include_once('Controller/Product.php');
      new Pektsekye_ProductOptions_Controller_Product();    	
    } elseif ($this->is_request('admin')){     
      include_once('Block/Adminhtml/Product/Edit/Tab/CustomOptions.php');
      new Pektsekye_ProductOptions_Block_Adminhtml_Product_Edit_Tab_CustomOptions();             
    }     	  
  }


  public function enqueue_admin_scripts(){
    global $pagenow;
    if ((isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit')
     || ('post-new.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product')){  
      wp_enqueue_script('pofw_product_options', $this->_pluginUrl . 'view/adminhtml/web/product/edit/main.js', array('jquery', 'jquery-ui-widget', 'wp-util'));
      wp_enqueue_style('pofw_product_options_old', $this->_pluginUrl . 'view/adminhtml/web/product/edit/styles-old.css');
      wp_enqueue_style('pofw_product_options', $this->_pluginUrl . 'view/adminhtml/web/product/edit/main.css');		    
    }    
  }


  public function enqueue_frontend_scripts(){
    if (is_singular('product')){  
      wp_enqueue_script('pofw_product_options', $this->_pluginUrl . 'view/frontend/web/product/main.js', array('jquery', 'jquery-ui-widget'));
      wp_enqueue_style('pofw_product_options', $this->_pluginUrl . 'view/frontend/web/product/main.css');		  		  			
    }
  }


  public function is_request($type){
    switch ($type) {
      case 'admin' :
        return is_admin();
      case 'ajax' :
        return defined('DOING_AJAX');
      case 'cron' :
        return defined('DOING_CRON');
      case 'frontend' :
        return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
    }
  }
  
  
  public function getPluginUrl(){
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath(){
    return $this->_pluginPath;
  }  
    
}


function Pektsekye_PO(){
	return Pektsekye_ProductOptions::instance();
}

include_once('Setup/Install.php');  
register_activation_hook(__FILE__, array('Pektsekye_ProductOptions_Setup_Install', 'install'));

// If WooCommerce plugin is installed and active.
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array())) || in_array('woocommerce/woocommerce.php', array_keys((array) get_site_option('active_sitewide_plugins', array())))){
  Pektsekye_PO();
}




