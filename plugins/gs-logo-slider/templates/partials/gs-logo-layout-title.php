<?php
namespace GSLOGO;

/**
 * GS Logo Slider - Logo Title Layout
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/partials/gs-logo-layout-title.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

if ( $title == "on" ) {
    $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'div', 'p'];
    $logo_title_tag = (string) apply_filters( 'gs_logo_title_tag', 'h3' );
    
    if ( ! in_array( $logo_title_tag, $allowed_tags ) ) {
        $logo_title_tag = 'h3';
    }

    printf( '<%1$s class="gs_logo_title">%2$s</%1$s>', $logo_title_tag, get_the_title() );

}