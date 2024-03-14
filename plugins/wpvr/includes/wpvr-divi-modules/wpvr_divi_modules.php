<?php

namespace WPVR\Builder\DIVI;

use WPVR\Builder\DIVI\Modules\WPVR_Modules;

 Class WPVR_Divi_modules {

     private static $_instance = null;

     public static function instance()
     {
         if (is_null(self::$_instance)) {
             self::$_instance = new self();
         }
         return self::$_instance;
     }


     private function __construct()
     {
         $this->init();
     }

     /**
      * initialize divi modules
      */
     private function init() {
         add_action( 'divi_extensions_init', array( $this,'wpvr_initialize_extension' ) );
     }

     /**
      * Creates the extension's main class instance.
      *
      * @since 8.1.2
      */

     function wpvr_initialize_extension() {
         new WPVR_Modules;
     }
 }



