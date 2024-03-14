<?php
/*
Plugin Name: OptionTree Shortcode
Plugin URI: https://github.com/ethanpil/wp-bloginfo-shortcode
Description: Add an [ot_get_option] shortcode to the WordPress editor.
Version: 1.0
Author: Store Machine Inc.
Author URI: http://storemachine.com
License: GPL
Text Domain: wp-bloginfo-shortcode
*/

function wp_ot_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'option_id' => '',
		'default' => ''
    ), $atts));
    return ot_get_option( $option_id, $default );
}
add_shortcode('ot_get_option', 'wp_ot_shortcode');

?>