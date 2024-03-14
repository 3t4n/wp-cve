<?php

namespace Pagup\Pctag\Controllers;

use  Pagup\Pctag\Core\Option ;
class TrackingController
{
    public function __construct()
    {
        add_action( 'wp_head', array( &$this, 'pinterest_pixel' ) );
    }
    
    public function pinterest_pixel()
    {
        // echo $this->product_event();
        if ( Option::check( 'enable_pctag' ) && Option::check( 'pctag_id' ) ) {
            echo  $this->pinterest( Option::get( 'pctag_id' ) ) ;
        }
        if ( Option::check( 'pctag_website' ) ) {
            echo  "<!-- Pinterest Claim Website --!>\n" . Option::get( 'pctag_website' ) . "\n" ;
        }
    }
    
    protected function pinterest( $tag )
    {
        return "<!-- Pinterest Pixel Base Code --!>\n<script>!function(e){if(!window.pintrk){window.pintrk = function () { window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;n.queue=[],n.version='3.0';var t=document.createElement('script');t.async=!0,t.src=e;var r=document.getElementsByTagName('script')[0]; r.parentNode.insertBefore(t,r)}}('https://s.pinimg.com/ct/core.js'); pintrk('load', '{$tag}'); pintrk('page'); </script> <noscript><img height='1' width='1' style='display:none;' alt='' src='https://ct.pinterest.com/v3/?tid={$tag}&noscript=1' /></noscript><!-- End Pinterest Pixel Base Code --!>\n<script> pintrk('track', 'pagevisit'); </script>\n";
    }
    
    protected function search_event( $search_query, $tag )
    {
        return "<!-- Pinterest Search Event --!>\n<script>pintrk('track', 'search', { search_query: '{$search_query}' }); </script><noscript><img height='1' width='1' style='display:none;' alt='' src='https://ct.pinterest.com/v3/?tid={$tag}&event=search&ed[search_query]={$search_query}&noscript=1' /></noscript><!-- End Pinterest Search Event --!>\n";
    }
    
    protected function pinterest_event( string $type, string $tag, array $data = array() )
    {
        $event_data = "";
        $noscript_data = "";
        
        if ( count( $data ) > 0 ) {
            $event_data = ", " . json_encode( $data );
            foreach ( $data as $key => $value ) {
                $noscript_data .= "&ed[{$key}]={$value}";
            }
            // Replace quotes around object keys with unquoted keys
            $event_data = preg_replace( '/"([\\w]+)":/i', '$1:', $event_data );
        }
        
        return "<!-- Pinterest {$type} code --!>\n<script>pintrk('track', '{$type}'{$event_data});</script>\n<noscript><img height='1' width='1' style='display:none;' alt='' src='https://ct.pinterest.com/v3/?tid={$tag}&event={$type}{$noscript_data}&noscript=1' /></noscript>\n";
    }
    
    protected function product_event( string $type, string $tag )
    {
    }
    
    protected function view_category( string $tag )
    {
        return "<!-- Pinterest ViewCategory Code --!>\n<script>pintrk('track', 'viewcategory');</script> <noscript><img height='1' width='1' style='display:none;' alt='' src='https://ct.pinterest.com/v3/?tid={$tag}&event=viewcategory&noscript=1' /></noscript>\n";
    }
    
    function flattenArray( array $multiArray ) : array
    {
        $flattenedArray = [];
        foreach ( $multiArray as $array ) {
            if ( !empty($array['type']) && !empty($array['value']) ) {
                $flattenedArray[$array['type']] = $array['value'];
            }
        }
        return $flattenedArray;
    }

}
$TrackingControllers = new TrackingController();