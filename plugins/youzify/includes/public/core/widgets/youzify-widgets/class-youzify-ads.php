<?php

class Youzify_Profile_Ads_Widget {

    public $ad_name;

    public function __construct( $ad_name ) {
        $this->ad_name = $ad_name;
    }

    /**
     * Content.
     */
    function widget() {

        // Get ADS.
        $ads = youzify_option( 'youzify_ads' );

        // Filter Ad Widget
        $ad = apply_filters( 'youzify_edit_ad', $ads[ $this->ad_name ] );

        // Get AD content.
        if ( 'banner' == $ad['type'] ) {
            $ad_content = "<a href='{$ad['url']}' target='_blank'><img loading='lazy' " . youzify_get_image_attributes_by_link( $ad['banner'] ) . " alt=''></a>";
        } elseif ( 'adsense' == $ad['type'] ) {
            $ad_content = urldecode( $ad['code'] );
        }

        // Display AD.
        echo "<div class='youzify-ad-box'>$ad_content</div>";
    }

}