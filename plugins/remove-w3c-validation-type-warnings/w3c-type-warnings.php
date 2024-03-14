<?php
   /*
   Plugin Name: Type Attribute Warnings Removal 
   Plugin URI: https://www.webfriendy.com/
   Description: This plugin will removes all the w3c validations type attribute warnings (type="text/css, type="text/javascript, type="application/javascript")
   Version: 1.0
   Author: Sumit Malviya
   Author URI: https://www.webfriendy.com/
   */

   
 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'template_redirect', 'wcerr_typeno_error' );

function wcerr_typeno_error(){
    ob_start( function( $validator ){
        $validator = str_replace( array( 'type="text/javascript"', 'type="application/javascript"', "type='text/javascript'" ), '', $validator );
        
        // Also works with other attributes...
        $validator = str_replace( array( 'type="text/css"', "type='text/css'" ), '', $validator );
        $validator = str_replace( array( 'frameborder="0"', "frameborder='0'" ), '', $validator );
        $validator = str_replace( array( 'scrolling="no"', "scrolling='no'" ), '', $validator );
        
        return $validator;
    });
}


?>