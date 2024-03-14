<?php 
namespace Shop_Ready\base\config;

/**
 * Plugin Basic configuration 
 * file exist in config folder
 * @since 1.0
 */

 
trait App {
   
   public static function get_app_config(){
       return shop_ready_app_config();
   }
   public static function get_template_config(){
    return shop_ready_templates_config();
   }

   public static function get_template_view_config(){
    return shop_ready_app_config()['views'];
   }

}