<?php

/*
* Widget [eli_option_value], show option value in raw
* atts list:
* 
* option (string) - option name, support values by priority from get_bloginfo(),get_option(),get_theme_mod();
                    Note: get option from get_bloginfo(), use special prefix like "blog", example blogdescription
                    
* reset (int) - set 1, for reset static var
*
*
*/


add_shortcode('eli_option_value', 'eli_option_value');
function eli_option_value($atts){
    $atts = shortcode_atts(array(
        'option'=>'',
        'reset'=>'',
    ), $atts);

    static $options_value = array();

    if($atts['reset'] == 1) {
        $options_value = array();
    }

    $value = false;

    /* first check from static var */
    if(isset($options_value [$atts['option']])) {
        $value = $options_value [$atts['option']];
    } else if(substr($atts['option'], 0, 4) == 'blog') {
        $options_value [$atts['option']] = $value = get_bloginfo( $atts['option']);
    } else if($value = get_option($atts['option'])) {
        $options_value [$atts['option']] = $value;
    } else if($value = get_theme_mod($atts['option'])) {
        $options_value [$atts['option']] = $value;
    }

    return $value;
}
