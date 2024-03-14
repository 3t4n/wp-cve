<?php 
namespace Shop_Ready\extension\wpshortcode\countdown;
use Shop_Ready\extension\wpshortcode\ShortCode_Base;

Class Countdown extends ShortCode_Base {
   
    // [shop_ready_countdown foo='foo']
    public $slug = 'shop_ready_countdown';

    public function view( $atts , $content='' ){

        $settings =  $this->settings;
        include( plugin_dir_path( __FILE__ ) . 'view/style1.php');
        unset($settings);
    }

    
 
}