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
class Presets_Loader
{
    use App;
    public $template_configs = [];

   /**:::::::::::: ::::::::::::
   * Auto run From service.php
   * Presets Override
   * @return void
   *:::::::::::: ::::::::::::*/
  public function register()
  {
    
     // Product Page 
    add_filter('wc_get_template_part', [$this, 'wc_get_template_part'], 202, 2);
    add_filter('wc_get_template_part', [$this, 'product_thumb_template_part'], 202, 2);
    //sticky cart
    add_filter('wc_get_template', [$this, 'sticky_cart_template_part'], 202, 5);
    
    // Shop Page
    add_filter('wc_get_template', [$this, 'shop_templates_override'], 1200 ,5);
    add_filter('wc_get_template_part', [$this, 'wc_get_shop_prodduct_content_part'], 90, 2);
    
    add_action( 'widgets_init', [$this,'woo_register_sidebars'] );

    add_action( 'woocommerce_before_main_content', [$this,'woocommerce_before_main_content'],100 );
    add_action( 'woocommerce_after_main_content', [$this,'woocommerce_after_main_content'],100 );

    // ThankYou / Order Recieved
     add_filter('wc_get_template', [$this, 'thankyou_templates' ], 300,5);

    // Checkout
    add_filter('wc_get_template', [$this, 'checkout_templates' ], 299,5);
    add_filter('wc_get_template', [$this, 'review_order' ], 299,5);

    // cart 
    add_filter('wc_get_template', [$this, 'cart' ], 299,5);

    // Login account 
    add_filter('wc_get_template', [$this, 'login_register' ], 299,5);
    // Myaccount
    add_filter('wc_get_template', [$this, 'myaccount' ], 299,5);

  }

  public function myaccount( $located,  $template_name,  $args, $template_path, $default_path ){
   
    if( is_account_page() ) {

      $configs             = self::get_template_config()->get('my_account');
      $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'myaccount';;
      $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
      $exceptional         = [];
      $exceptional[]       = 'my-account.php';
      $template_path       = basename( $located );
      
      if ( !in_array( $template_path , $exceptional ) ) {
        return $located;
      }

      if( $presets_active_path == '' ){
        return $located; 
      }
 
      if ( isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
        return $located;
      }

      $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" .$presets_active_path.'.php');

      if ( file_exists( $plugin_path ) ) {
        return $plugin_path;
      }
      
    }
    return $located;
  }
  public function login_register( $located,  $template_name,  $args,  $template_path,  $default_path){

    
    if( shop_ready_is_account_login() ) {

      $configs             = self::get_template_config()->get('my_account_login_register');
      $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'login';;
      $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
      $exceptional         = [];
      $exceptional[]       = 'form-login.php';
      $template_path       = basename( $located );
      
      if ( !in_array( $template_path , $exceptional ) ) {
        return $located;
      }

      if( $presets_active_path == '' ){
        return $located; 
      }
 
      if ( isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
        return $located;
      }

      $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" .$presets_active_path.'.php');

      if ( file_exists( $plugin_path ) ) {
        return $plugin_path;
      }
      
    }
    
    return $located;
  }

  public function cart( $located,  $template_name,  $args,  $template_path,  $default_path){

    if( is_cart() ) {

      $configs             = self::get_template_config()->get('cart');
      $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'cart';;
      $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
      $exceptional         = [];
      $exceptional[]       = 'cart.php';
      $template_path       = basename( $located );
        
      if ( !in_array( $template_path , $exceptional ) ) {
        return $located;
      }

      if( $presets_active_path == '' ){
        return $located; 
      }
 
      if ( isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
        return $located;
      }

      $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" .'cart.php');
      
      if ( file_exists( $plugin_path ) ) {
        return $plugin_path;
      }
      
    }
    
    return $located;
  }

  public function review_order( $located,  $template_name,  $args,  $template_path,  $default_path){

    if( is_checkout() && !is_wc_endpoint_url('order-received') ) {

      $configs             = self::get_template_config()->get('checkout');
      $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'checkout';;
      $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
      $exceptional         = [];
      $exceptional[]       = 'review-order.php';
      $template_path       = basename( $located );
        
      if ( !in_array( $template_path , $exceptional ) ) {
        return $located;
      }

      if( $presets_active_path == '' ){
        return $located; 
      }

      $presets_active_path = 'review-order';
      if ( isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
        return $located;
      }

      $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" . $presets_active_path.'.php');
      
      if ( file_exists( $plugin_path ) ) {
        return $plugin_path;
      }
      
    }
    
    return $located;
  }

  public function checkout_templates( $located,  $template_name,  $args,  $template_path,  $default_path){

      if( is_checkout() && !is_wc_endpoint_url('order-received') ) {
        
       
        $configs             = self::get_template_config()->get('checkout');
        $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'checkout';;
        $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
        $exceptional         = [];
        $exceptional[]       = 'form-checkout.php';
        $template_path       = basename( $located );
        
        if ( !in_array( $template_path , $exceptional ) ) {
          return $located;
        }
  
        if( $presets_active_path == '' ){
          return $located; 
        }
       
        if ( isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
          return $located;
        }
        
        $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" . $presets_active_path.'.php');
      
        if ( file_exists( $plugin_path ) ) {
          return $plugin_path;
        }
  
    }

    return $located;
  }
  public function thankyou_templates( $located,  $template_name,  $args,  $template_path,  $default_path){
    
    if( is_checkout() && is_wc_endpoint_url('order-received') ) {
  
        $configs             = self::get_template_config()->get('order_received');
        $presets_path        = isset( $configs['presets_path'] ) ? $configs['presets_path'] : 'thankyou';;
        $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';
        $exceptional         = [];
        $exceptional[]       = 'thankyou.php';
        $exceptional[]       = 'order-details-customer.php';
        $exceptional[]       = 'order-again.php';
        $exceptional[]       = 'order-details-item.php';
        $exceptional[]       = 'order-details.php';
        $exceptional[]       = 'order-downloads.php';
        $template_path       = basename( $located );
        
        if ( !in_array( $template_path , $exceptional ) ) {
          return $located;
        }
    
        if( $presets_active_path == '' ){
          return $located; 
        }
       
        if (  isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
          return $located;
        }

        if($template_path == 'order-details-customer.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
        
        }elseif($template_path == 'order-details-customer.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
        
        }elseif($template_path == 'order-again.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
        
        }elseif($template_path == 'order-details-item.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
        
        }elseif($template_path == 'order-downloads.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
       
        }elseif($template_path == 'order-details.php'){

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/".'order/'.$template_path);
       
        }else{

          $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" . $presets_active_path.'.php');
        }
 
        if ( file_exists( $plugin_path ) ) {
          return $plugin_path;
        }
   
    }
  
    return $located;
  }

  public function get_template_locate( $template_name, $folder_path = '' ){

      $default_path = SHOP_READY_TEMPLATES_PATH . 'presets/'.$folder_path.'/'; 
     
      $template = locate_template([
          $default_path . $template_name,
          $template_name
      ] );
     
      if ( ! $template ) :
        $template = $default_path . $template_name;
      endif;

      if(file_exists($template.'.php')){
        return $template.'.php';
      }

      return false;
  }



  public function woocommerce_before_main_content()
  {
    echo wp_kses_post('<div class="shopready-shop-archive-before-main-content">');
  }

  public function woocommerce_after_main_content()
  {

    echo wp_kses_post('</div>');
    
  }

  

  function woo_register_sidebars() {
    /* Register the 'woo_register_sidebars' Shop sidebar. */
    register_sidebar(
        array(
            'id'            => 'shopready-woocommerce-sidebar',
            'name'          => __( 'ShopReady Shop Sidebar' ),
            'description'   => __( 'A short description of the sidebar.' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

  }

    /**
   * Loop Content grod style
   * @return %path | tpl->{preset}filepath 
   */
  public function wc_get_shop_prodduct_content_part($located, $template_name)
  {

    //if(is_shop() || is_product_category() || is_product_tag() || 1==1){
        $exceptional = [];
        $configs        = self::get_template_config()->get('shop');
        $presets_path   = isset( $configs['presets_path'] ) ? $configs['presets_path'] : '';; 
        $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : ''; 
        $exceptional = [];
        $exceptional[] =  'content-product.php';
        $template_path = basename( $located );
        
        if ( !in_array( $template_path , $exceptional ) ) {
     
            return $located;
        }
    
        if( $presets_active_path == '' ){
            return $located; 
        }

        if (  isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
          
            return $located;
        }
        
        $grid_layout = shop_ready_fix_path(SHOP_READY_TEMPLATES_PATH . 'tpl/content-product.php');
       
     
        if ( file_exists( $grid_layout ) ) {
          
          return $grid_layout;
        }
      

    //}
   

    return $located;
  }

  public function shop_templates_override( $located,  $template_name,  $args,  $template_path,  $default_path){
    
    if(is_shop() || is_product_category() || is_product_tag()){
        
        $configs        = self::get_template_config()->get('shop');
        $presets_path   = isset( $configs['presets_path'] ) ? $configs['presets_path'] : '';; 
        $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : ''; 
        $exceptional = [];
        $exceptional[] =  'archive-product.php';
        $template_path = basename( $located );
       
        if ( !in_array( $template_path , $exceptional ) ) {
     
            return $located;
        }
    
        if( $presets_active_path == '' ){
            return $located; 
        }
       
        if (  isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
          
            return $located;
        }
      
        $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" . $presets_active_path.'.php');
        
        if ( file_exists( $plugin_path ) ) {
          return $plugin_path;
        }
   
    }
  
    return $located;
  }

  
  public function sticky_cart_template_part( $located, $template_name, $args, $template_path, $default_path )
  {
   
    if( ! is_product() ){
      return $located; 
    }

    $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/tpl/" . $template_name);
  
    if ( file_exists( $plugin_path ) ) {
      return $plugin_path;
    }

    return $located;
   
  }
  /**
   * Single Product Template
   * @return %path | tpl->{preset}filepath 
   */
  public function product_thumb_template_part($located, $template_name){
    return $located; 
  }
  public function wc_get_template_part($located, $template_name)
  {
 
    if(!is_product()){
      return $located; 
    }
     
    $configs        = self::get_template_config()->get('single');
    $presets_path   = isset( $configs['presets_path'] ) ? $configs['presets_path'] : '';; 
    $presets_active_path = isset( $configs['presets_active_path'] ) ? $configs['presets_active_path'] : '';; 
    $exceptional = [];
    $exceptional[] =  'content-single-product.php';
    $template_path = basename( $located );
   
    if ( !in_array( $template_path , $exceptional ) ) {
     
        return $located;
    }

    if( $presets_active_path == '' ){
        return $located; 
    }

    if (  isset( $configs['presets_active'] ) && $configs['presets_active'] != 1 && in_array( $template_path, $exceptional ) ) {
      return $located;
    }
  
    $plugin_path = shop_ready_fix_path(untrailingslashit(SHOP_READY_TEMPLATES_PATH) . "/presets/". $presets_path. "/" . $presets_active_path.'.php');
    
    if ( file_exists( $plugin_path ) ) {
      return $plugin_path;
    }

    return $located;
   
  }

}
