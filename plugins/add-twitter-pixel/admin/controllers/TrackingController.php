<?php

namespace Pagup\Twitter\Controllers;

use  Pagup\Twitter\Core\Option ;
class TrackingController
{
    public function __construct()
    {
        add_action( 'wp_head', array( &$this, 'twitter_pixel' ) );
    }
    
    public function twitter_pixel()
    {
        if ( Option::check( 'twitter_id' ) ) {
            
            if ( class_exists( 'woocommerce' ) ) {
                if ( !is_singular( 'product' ) && !is_cart() && !is_checkout() ) {
                    echo  $this->twitter( Option::get( 'twitter_id' ) ) . "\n" ;
                }
            } else {
                echo  $this->twitter( Option::get( 'twitter_id' ) ) . "\n" ;
            }
        
        }
        if ( atp__fs()->can_use_premium_code__premium_only() && Option::check( 'twitter_id' ) ) {
            echo  $this->twitter_event() ;
        }
    }
    
    protected function twitter( $tag )
    {
        return "<!-- Twitter universal website tag code --><script>!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');twq('init','{$tag}');twq('track','PageView');</script><!-- End Twitter universal website tag code -->";
    }
    
    protected function twitter_event()
    {
        return;
    }

}
$TrackingControllers = new TrackingController();