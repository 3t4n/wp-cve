<?php 
namespace Shop_Ready\extension\wpshortcode\common;
use Shop_Ready\extension\wpshortcode\ShortCode_Base;

Class Notice extends ShortCode_Base {
   
    // [shop_ready_notice]
    public $slug = 'shop_ready_notice';

    public function view( $atts , $content='' ){

        $settings =  $this->settings;
        include( plugin_dir_path( __FILE__ ) . 'view/notice.php');
        unset($settings);
    }

    
 
}