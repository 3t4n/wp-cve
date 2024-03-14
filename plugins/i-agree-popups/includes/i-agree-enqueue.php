<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Enqueue Stylesheet
 *
 * @package I_Agree_Popups
**/
 
class I_Agree_Enqueue {
    
    // Initialise function
    public function init() {
        
        add_action( 'wp_enqueue_scripts',  array( $this, 'head_stuff' ) );
        
    }

    // Inject the header with stylesheet specified below
    function head_stuff() { 
    
        wp_register_style( 'i-agree-popups', plugin_dir_url( __FILE__ ) . 'assets/css/i-agree-popups.css','','', 'screen' );
        wp_enqueue_style( 'i-agree-popups' );
        
    }

}


