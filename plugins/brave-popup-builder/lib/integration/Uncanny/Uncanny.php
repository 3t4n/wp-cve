<?php

/**
 * Class Brave_Uncanny_Integration
 */

 if( !class_exists('Brave_Uncanny_Integration') ){
   class Brave_Uncanny_Integration {

      public $integration_code = 'bravepop';
      public $directory;
   
      /**
       * Add_Integration constructor.
       */
      public function __construct() {
         $this->directory = __DIR__ . DIRECTORY_SEPARATOR . 'bravepop';
         add_action( 'plugins_loaded', array( $this, 'setup_integration' ) );
         add_action( 'automator_configuration_complete', array( $this, 'add_this_integration' ) );
      }
   
      public function setup_integration() {
         if ( function_exists( 'automator_add_integration' ) ) {
             $this->integration_dir = automator_add_integration( $this->directory );
         }
     }
   
      public function add_this_integration() {
         if ( empty( $this->integration_code ) || empty( $this->directory ) ) {
            return false;
         }
         if(function_exists('automator_add_integration_directory')){
            automator_add_integration_directory( $this->integration_code, $this->directory );
         }
      }
   }
 }

new Brave_Uncanny_Integration();