<?php

namespace Shop_Ready\extension\elewidgets\document;

use Shop_Ready\base\elementor\Document_Settings;
use \Elementor\Controls_Manage;
use Elementor\Plugin;
use Elementor\Core\Settings\Manager as SettingsManager;

/**
* Payment Related Global Settings
* Settings Exist in Elementor Editor->site settings Payment Section 
* @see https://prnt.sc/1aptmeu
* @since 1.0
* @author @quomodosoft.com
*/
Class Payment_Hooks extends Document_Settings{
    
    public function register(){

        add_filter( 'woocommerce_cart_needs_payment', [$this,'needs_payment'] );
    }
    
    /** 
    * Disble WooCommerce Payment Option
    * Direct Sell Without Payment
    * Settings Exist in Elementor Site Settings and global_settings.php
    * @since 1.0
    * @author quomodosoft.com
    */
    public function needs_payment(){
        
        if(shop_ready_gl_get_setting('wr_disable_payment_gateway') === 'yes'){
            return false;
        }
    
        return true;
        
    }
 
}