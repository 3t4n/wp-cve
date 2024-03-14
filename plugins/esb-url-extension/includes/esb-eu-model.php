<?php

/**
 * Model File
 * Handles to database functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
* Escape Attr
*/
function esb_eu_escape_attr($data){

    if( !empty( $data ) ) {
        $data = esc_attr(stripslashes_deep($data));
    }
    return $data;
}

/**
* Strip Slashes From Array
*/
function esb_eu_escape_slashes_deep($data = array(),$flag=true){

    if($flag != true) {
            $data = esb_eu_nohtml_kses($data);
    }
    $data = stripslashes_deep($data);
    return $data;
}

/**
* Strip Html Tags 
* 
* It will sanitize text input (strip html tags, and escape characters)
*/
function esb_eu_nohtml_kses($data = array()) {

    if ( is_array($data) ) {

            $data = array_map(array($this,'esb_eu_nohtml_kses'), $data);

    } elseif ( is_string( $data ) ) {

            $data = wp_filter_nohtml_kses($data);
    }

    return $data;
}

/**
 * Convert Object To Array
 */
function esb_eu_object_to_array($result) {

    $array = array();
    foreach ($result as $key=>$value)
    {	
        if (is_object($value)) {
            $array[$key]=esb_eu_object_to_array($value);
        } else {
            $array[$key]=$value;
        }
    }
    return $array;
}
?>