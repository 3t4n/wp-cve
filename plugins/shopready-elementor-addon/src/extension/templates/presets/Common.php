<?php

namespace Shop_Ready\extension\templates\presets;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
use Shop_Ready\base\config\App;
/** :::::::::::: ::::::::::::
 * 
 * WooCommerece Templates Presets
 * @since 1.0  
 * @author quomodosoft.com
 *
 * :::::::::::: ::::::::::::*/
class Common
{

  use App;
  public $path = "//";
  public $override_current_theme = true;

  /**:::::::::::: ::::::::::::
   * Auto run From service.php
   * Template Override
   * @return void
   *:::::::::::: ::::::::::::*/
  public function register()
  {

    $this->set_path();

    add_filter( 'woocommerce_locate_template' , [ $this, 'common_templates_override'], 13, 3);
    add_filter( 'woocommerce_locate_template' , [ $this, 'next_templates_override'], 90, 3);
    add_filter( 'wc_get_template_part' , [ $this, 'wc_get_template_part'], 50, 2);
    add_filter( 'wc_get_template' , [ $this, 'loop_template_part'], 100, 4);
    add_filter( 'wc_get_template' , [ $this, 'product_quantity'], 200, 4);
    add_filter( 'wc_get_template' , [ $this, 'cart_page'], 100, 4);
    add_filter( 'wc_get_template' , [ $this, 'shop_archive_page'], 300, 4);

  }

  public function shop_archive_page($located, $template_name, $args, $tpath){
    
 
    $exceptional         = [];
    $configs             = self::get_template_config()->get( 'shop' );
    $presets_path        = isset( $configs[ 'presets_path' ] ) ? $configs[ 'presets_path' ] : '';;
    $presets_active_path = isset( $configs[ 'presets_active_path' ] ) ? $configs[ 'presets_active_path' ] : '';
    $exceptional[]       = 'archive-product.php';
 
    $template_path = basename( $located );
   
   
    if (!in_array($template_path, $exceptional)) {
     
      return $located;
    }

    if (  isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
          
      return $located;
    }
    
    $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path();
     
    if (file_exists($plugin_path . $template_path)) {
      $template = $plugin_path . $template_path;

      return $template;
    }
    

    return $located;
  }


  public function cart_page($located, $template_name, $args, $tpath)
  {

    $exceptional              = [];
    $exceptional[]            = 'cart.php';
    $template_path            = basename($located);

    if ( !shop_ready_template_is_active_gl( 'cart' ) && in_array( $template_path, $exceptional ) ) {
      return $located;
    }
   
    if ( !in_array( $template_path , $exceptional ) ) {
      return $located;
    }
  
    if ($this->override_current_theme && in_array( $template_path, $exceptional )) {

      $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path() . 'cart/';
  
      if ( file_exists( $plugin_path . $template_path )) {
        $template = $plugin_path . $template_path;
        
        return $template;
      }
    }

    return $located;
  }

  public function product_quantity($located, $template_name, $args, $tpath)
  {

    $exceptional              = [];
    $exceptional[]            = 'quantity-input.php';
   
    $template_path            = basename($located);
 
    if (!in_array($template_path, $exceptional)) {
      return $located;
    }
    
    if (in_array($template_path, $exceptional)) {

      $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path() . 'global/';
     
      if (file_exists($plugin_path . $template_path)) {
        $template = $plugin_path . $template_path;
       
        return $template;
      }
    }

    return $located;
  }
  /** 
   * Some Global Exceptional Template Override
   * @return %path | tpl->{preset}filepath 
   */
  public function loop_template_part($located, $template_name, $args, $tpath)
  {
   
    $exceptional = [];
    $exceptional[] =  'loop-start.php';
    $exceptional[] =  'loop-end.php';
    $exceptional[] =  'rating.php';

    $template_path = basename($located);

    if (!in_array($template_path, $exceptional)) {
      return $located;
    }

    if ($this->override_current_theme && in_array($template_path, $exceptional)) {

      $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path() . 'loop/';

      if (file_exists($plugin_path . $template_path)) {
        $template = $plugin_path . $template_path;
        return $template;
      }
    }

    return $located;
  }
  /**
   * Some Exceptional Template Override
   * @return %path | tpl->{preset}filepath 
   */
  public function wc_get_template_part($located, $template_name)
  {

   
    $exceptional = [];
    $exceptional[] =  'content-product.php';
 
    $template_path = basename( $located );
    
    if ( !shop_ready_template_is_active_gl( 'shop' ) && in_array( $template_path, $exceptional ) ) {
      return $located;
    }
   
    if (!in_array($template_path, $exceptional)) {
     
      return $located;
    }
    
    $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path();
   
    if ($this->override_current_theme) {
      
      if (file_exists($plugin_path . $template_path)) {
        $template = $plugin_path . $template_path;

        return $template;
      }
    }

    return $located;
  }

  /** ::::::::::::* ::::::::::::
   * Skip Some templates from plugin and load from theme
   * @see config
   * @return array
   **:::::::::::: ::::::::::::*/
  public function skip_templates()
  {

    $tpl_list = [];

    return $tpl_list;
  }

  /**::::::::::::
   * Set path from config
   * @return void
   *::::::::::::*/
  public function set_path()
  {

    $this->path = "/tpl/";

    if( get_option( 'shop_ready_theme_template_override_enable' ) == 'yes' ){
       $this->override_current_theme = true;
    }

  }

  /**
   * Preset path 
   * @return string dir root path
   */
  public function get_path()
  {

    return $this->path;
  }

  /**
   * Override default Template  file
   * @since 1.0
   * @see https://docs.woocommerce.com/document/template-structure/
   * @path_ex $template_path - woocommerce/
   * @path_ex template_name - cart/cart.php
   * @path_ex $template plugins\woocommerce/templates/cart/cart.php
   */
  public function common_templates_override($template, $template_name, $template_path)
  {
   
    global $woocommerce;
    $template__path = basename($template);
    $exceptional = [
      'loop-start.php', 'loop-end.php', 'orderby.php', 'result-count.php', 'pagination.php'
    ];

    if ( $this->override_current_theme && in_array($template__path, $exceptional) ) {
      $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path() . 'loop/';

      if (file_exists($plugin_path . $template_path)) {
        $template = $plugin_path . $template_path;
        return $template;
      }
    }

    $_template = $template;

    if (!$template_path) {
      $template_path = $woocommerce->template_url;
    }

    $plugin_path = untrailingslashit(SHOP_READY_TEMPLATES_PATH) . $this->get_path();

    if (!$this->override_current_theme) {

      $template = locate_template(
        [
          $template_path . $template_name,
          $template_name
        ]
      );
    }

    if (!$template && file_exists($plugin_path . $template_name)) {

      $template = $plugin_path . $template_name;
    }

    if (!$template) {

      $template = $_template;
    }

    return $template;
  }

  /**
   * Override default Template  file
   * @since 1.0
   * @see https://docs.woocommerce.com/document/template-structure/
   * @path_ex $template_path - woocommerce/
   * @path_ex template_name - cart/cart.php
   * @path_ex $template plugins\woocommerce/templates/cart/cart.php
   */
  public function next_templates_override($template, $template_name, $template_path)
  {

    return $template;
  }
}
