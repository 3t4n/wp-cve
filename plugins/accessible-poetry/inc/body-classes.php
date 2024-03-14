<?php

/**
 * Update body classes according
 * to our settings
 */
function acwp_update_body_classes( $classes ) {
    
    // Array to collect extra classes
    $more = array();

    // Keyboard navigation effect
    if( get_option('acwp_keyboard_effect') == 'red-full' )
        array_push($more, 'acwp-keyboard-redfull');
    elseif( get_option('acwp_keyboard_effect') == 'blue-border' )
        array_push($more, 'acwp-keyboard-blue');
    elseif( get_option('acwp_keyboard_effect') == 'blue-full' )
        array_push($more, 'acwp-keyboard-bluefull');
    
    // Keyboard navigation - No border
    if( get_option('acwp_keyboard_noborder') == 'yes' )
        array_push($more, 'acwp-keyboard-noborder');

    // Keyboard navigation - No arrows
    if( get_option('acwp_keyboard_noarrows') == 'yes' )
        array_push($more, 'acwp-keyboard-noarrows');

    // Contrast - Custom colors
    if( get_option('acwp_contrast_custom') == 'yes' )
        array_push($more, 'acwp-contrast-custom');

    // Contrast mode
    if( get_option('acwp_contrast_mode') == 'hard-css' )
        array_push($more, 'acwp-contrast-hardcss');
    elseif( get_option('acwp_contrast_mode') == 'js' )
        array_push($more, 'acwp-contrast-js');
        
    // Contrast - BG images
    if( get_option('acwp_contrast_bgimages') == 'yes' )
        array_push($more, 'acwp-contrast-bgimages');
        
    // Mark titles - custom colors
    if( get_option('acwp_titles_customcolors') == 'yes' )
        array_push($more, 'acwp-titles-custom');
        
    // Highlight links - custom colors
    if( get_option('acwp_links_customcolors') == 'yes' )
        array_push($more, 'acwp-links-custom');
        
    // Mark titles - mode
    if( get_option('acwp_titles_mode') == 'hard-css' )
        array_push($more, 'acwp-titles-hardcss');
        
    // Highlight links - mode
    if( get_option('acwp_underline_mode') == 'hard-css' )
        array_push($more, 'acwp-underline-hardcss');
    if( get_option('acwp_links_mode') == 'hard-css' )
        array_push($more, 'acwp-links-hardcss');
        
    // Readable font - mode
    if( get_option('acwp_readable_mode') == 'hard-css' )
        array_push($more, 'acwp-readable-hardcss');
        
    $fromside = (get_option('acwp_toggle_fromside') && get_option('acwp_toggle_fromside') != '') ? get_option('acwp_toggle_fromside') : '';
    $fromtop = (get_option('acwp_toggle_fromtop') && get_option('acwp_toggle_fromtop') != '') ? get_option('acwp_toggle_fromtop') : '';

    if($fromtop != '')
        array_push($more, 'acwp-fromtop');
    if($fromside != '')
        array_push($more, 'acwp-fromside');

    return array_merge( $classes, $more );
}
add_filter( 'body_class', 'acwp_update_body_classes');