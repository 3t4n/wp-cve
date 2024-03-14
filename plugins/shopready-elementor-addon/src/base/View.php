<?php 
namespace Shop_Ready\base;

Class View {
    
    use config\App;

    public static $path = null;
    /*
    * template file name / class 
    */
    public static function is_template_file_exist($name){
      
        $configs = self::get_template_config();
        $view    = self::get_template_view_config();
       
        if( isset( $configs[ $name ] ) ){
         
           $template = $configs[$name];
          
           if( isset( $template['path'] ) && \file_exists($view['templating']).'/'.$template['path'] ) {

               self::$path = $view['templating'].'/'.$template['path'];
               
               return $view['templating'].'/'.$template['path'];
           }

        }

        return false;
    }
    
    /**
    * | get_template_file |
    *  Change default template path with for customization
    * override from theme / plugin
    * @return string | template-file.php
    */
    public static function get_template_file( $hook ){
       
        return apply_filters($hook , self::$path );
    }


    public static function get_template_id($id){
           
      return $id;
    }

    public static function get_elementor_content(){

    }

    /** 
    * | get_page_template_slug |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @_usage_    get page template layout setting from page
    * @return string | template_hook_name 
    */
    public static function get_page_template_slug(){
        
        if ( !class_exists( 'WooCommerce' ) ) {

            return false;
        }

        $default = 'elementor_header_footer';
        
        if( is_product() ){

            if(isset($_GET['shop-ready-iframe-quickview'])){
                return 'elementor_canvas';
            }
            
           return get_page_template_slug(get_the_id());
        }
        
        if( is_shop() || is_product_category() || is_product_tag() ){

          return self::get_default_slug($default,get_option( 'woocommerce_shop_page_id' ));
        }

        if( is_cart() ){

            return self::get_default_slug($default,get_option( 'woocommerce_cart_page_id' ));
        }
        
        if(is_wc_endpoint_url('order-received') && is_checkout()){

            return self::get_default_slug($default,get_option( 'woocommerce_view_order_page_id' ));
        }

        if( is_checkout() ){
                 
            return self::get_default_slug($default,get_option( 'woocommerce_checkout_page_id' ));
        }

        if(is_user_logged_in() && is_account_page()){
           
            return self::get_default_slug($default,get_option( 'woocommerce_myaccount_page_id' ));
        }

        if( shop_ready_is_account_login() || shop_ready_is_account_edit_account() || shop_ready_is_account_order_view() || shop_ready_is_account_downloads() || shop_ready_is_account_edit_address() ){
           
            return self::get_default_slug($default, false);
        }

       return false;   
    }

    public static function get_default_slug( $default , $id ){

        if( $id > 0 ){
               
            $slug = get_page_template_slug( $id );
           
            return $slug == '' ? $default : $slug;
         }
        
         return $default; 
    }
}