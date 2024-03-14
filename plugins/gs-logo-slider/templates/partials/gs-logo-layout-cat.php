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

if ( !empty($show_cat) && $show_cat == "on" ) {
    $allowed_tags = ['h3', 'h4', 'h5', 'h6', 'span', 'div', 'p'];
    $logo_cat_tag = (string) apply_filters( 'gs_logo_category_tag', 'div' );
    
    if ( ! in_array( $logo_cat_tag, $allowed_tags ) ) {
        $logo_cat_tag = 'div';
    }

    $cats = get_the_terms( get_the_ID(), 'logo-category' );

    $cats = wp_list_pluck( $cats, 'name' );

    printf( '<%1$s class="gs_logo_cats">%2$s</%1$s>', $logo_cat_tag, join(', ', $cats) );

}