<?php

namespace Pagup\BetterRobots\Traits;

trait Sitemap
{
    public function yoast_sitemap()
    {
        $ch = curl_init( $this->yoast_sitemap_url );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_NOBODY, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
        $curl_output = curl_exec( $ch );
        //print curl_error($ch); // display error if curl is responding with 0
        $yoast_sitemap_header = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );
        return $yoast_sitemap_header;
    }
    
    public function xml_sitemap()
    {
        $ch = curl_init( $this->xml_sitemap_url );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_NOBODY, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
        $curl_output = curl_exec( $ch );
        //print curl_error($ch); // display error if curl is responding with 0
        $xml_sitemap_header = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );
        return $xml_sitemap_header;
    }
    
    public function sitemap_notification()
    {
        
        if ( class_exists( 'WPSEO_Sitemaps' ) && $this->yoast_sitemap() == "200" ) {
            // yoast is working, sitemap added
            $sitemap_output = '<div class="rt-alert rt-info"><span class="closebtn">&times;</span>' . sprintf( wp_kses( __( '<a href="%s">XML Sitemap</a> detected but not added.', 'better-robots-txt' ), array(
                'a' => array(
                'href' => array(),
            ),
            ) ), esc_url( $this->yoast_sitemap_url ) ) . " " . $this->get_pro . " " . __( 'sitemap feature', 'better-robots-txt' ) . '</div>';
        } elseif ( class_exists( 'WPSEO_Sitemaps' ) && $this->yoast_sitemap() == "404" ) {
            // yoast is enabled but sitemap is not
            $sitemap_output = '<div class="rt-alert rt-info"><span class="closebtn">&times;</span>' . __( 'Yoast SEO is installed.', 'better-robots-txt' ) . " " . $this->get_pro . " " . __( 'sitemap feature', 'better-robots-txt' ) . '</div>';
        } elseif ( $this->xml_sitemap() == "200" ) {
            $sitemap_output = '<div class="rt-alert rt-info"><span class="closebtn">&times;</span>' . sprintf( wp_kses( __( '<a href="%s">XML Sitemap</a> detected but not added.', 'better-robots-txt' ), array(
                'a' => array(
                'href' => array(),
            ),
            ) ), esc_url( $this->xml_sitemap_url ) ) . " " . $this->get_pro . " " . __( 'sitemap feature', 'better-robots-txt' ) . '</div>';
        } else {
            //yoast is not installed/enabled
            $sitemap_output = '<div class="rt-alert rt-warning"><span class="closebtn">&times;</span>' . $this->get_pro . " " . __( 'sitemap option', 'better-robots-txt' ) . '</div>';
        }
        
        // end yoast sitemap checking
        return $sitemap_output;
    }

}