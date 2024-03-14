<?php

namespace Shop_Ready\extension\elewidgets\document;

use Shop_Ready\base\elementor\Document_Settings;
use \Elementor\Controls_Manage;
use Elementor\Plugin;
use Elementor\Core\Settings\Manager as SettingsManager;
/*
* Login_Redirect
* @since 1.0
* Page Settings in Elementor Editor
* usege in login register widgets
*/

Class Login_Redirect extends Document_Settings{
    
    public function register(){
  
        add_filter( 'woocommerce_login_redirect', [ $this , '_customer_login_redirect' ], 9999, 2 ); 
    }
      
    /*
    * Login Redirect path
    * Login widget settings
    * return string path
    */
    public function _customer_login_redirect( $redirect, $user ){
       
        if ( wc_user_has_role( $user, 'customer' ) ) {

            $redirect_enable = get_option( 'wr_login_redirect_enable' );

            if( shop_ready_gl_get_setting('wr_login_redirect_enable') == 'yes' ){
                $path = oo_ready_gl_get_setting('wr_login_redirect');
                if( isset( $path[ 'url' ] ) ){
                
                    if( $path[ 'url' ] !='' ){
                    
                        return esc_url($path[ 'url' ]);
                    }

                }
            } // switch check
        } // role check

        return $redirect;
    }

}