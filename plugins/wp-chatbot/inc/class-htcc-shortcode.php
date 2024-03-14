<?php
/**
* shortcodes
* base shorcode name is [chat]
* for list of attribute support check  -> shortcode_atts ( $a )
*
* @package ccw
* @since 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Shortcode' ) ) :

class HTCC_Shortcode {


    function shortcode($atts = [], $content = null, $shortcode = '') {

        // let the script add - when shortcode added
        // ~ any how fb won't load the sdk second time ..

		$htcc_js_options = get_option('htcc_fb_js_src');

        $is_mobile = ht_cc()->device_type->is_mobile;


        $a = shortcode_atts(
            array(
                'hide_mobile' => '',
                'hide_desktop' => '',
            ), $atts, $shortcode );

        // hide based on device
        $hide_mobile = $a["fb_hide_mobile"];
        $hide_desktop = $a["fb_hide_desktop"];
        // if set to true then hide. - here shortcode wont use main options
        if ( 'yes' == $is_mobile ) {
            if ( "true" == $hide_mobile ) {
                return;
            }
        } else {
            if ( "true" == $hide_desktop ) {
                return;
            }
        }

		$o="<script async='async' src=$htcc_js_options></script>";

        return $o;
    }

    //  Register shortcode
    function htcc_shortcodes_init() {

        $htcc_options = get_option('htcc_options');

        $shortcode_name = esc_attr( $htcc_options['shortcode'] );

        add_shortcode( $shortcode_name, array( $this, 'shortcode' ) );
    }

}

$shortcode = new HTCC_Shortcode();

add_action('init', array( $shortcode, 'htcc_shortcodes_init' ) );

endif; // END class_exists check