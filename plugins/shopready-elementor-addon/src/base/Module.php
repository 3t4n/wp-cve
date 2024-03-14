<?php 

namespace Shop_Ready\base;

trait Module {

    public static function sr_module_live() { return shop_ready_sysytem_module_options_is_active(self::$ext_name); }
  
}