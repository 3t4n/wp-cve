<?php

class CRP_Css {

    public function __construct()
    {
        add_action( 'wp_head', array( $this, 'custom_css' ), 20 );
    }

    public function custom_css()
    {
        $custom_css = CustomRelatedPosts::setting( 'custom_code_public_css' );
        if( $custom_css ) {
            echo '<style type="text/css">';
            echo $custom_css;
            echo '</style>';
        }
    }
}