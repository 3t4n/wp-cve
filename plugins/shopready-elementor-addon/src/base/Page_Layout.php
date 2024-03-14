<?php 

namespace Shop_Ready\base;

use Shop_Ready\base\config\Template_Settings;

Abstract Class Page_Layout extends Template_Settings {
  
    /**
     * service initializer
     * @return void
     * @since 1.0
     */
    abstract protected function register();
 
}