<?php

if( class_exists( 'FI_Filter' ) ){

   class FvFilledInAntispamFilter extends FI_Filter {

      function filter( &$value, $all_data ){
         return 'Answer not correct';
      }

      function name(){
         return "Filled in Antispam automatic extension";
      }

      function show(){
         parent::show ();
      }

   }

}
//$this->register ('Filter_Email');
