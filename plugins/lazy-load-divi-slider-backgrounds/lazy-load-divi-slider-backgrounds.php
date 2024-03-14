<?php
/*
Plugin Name: Lazy Load Divi Slider Backgrounds
Description: Speed up your website by lazy loading slider backgrounds. This plugin works only with the <a href="https://www.elegantthemes.com/plugins/divi-builder/">Divi Builder</a>, usually part of the Divi Theme. No configuration required.
Version: 1.0
Plugin URI: https://wordpress.org/plugins/lazy-load-divi-slider-backgrounds/
Author: Danail Emandiev
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Check if there are any Divi Builder sliders on the page
function lazy_load_slider_bg_check_if_needed() {
    
    global $post;
    
    $regex = '/\[et_pb_slide[^\]]*background_image[^\]].*?\]/';
    
    if ( preg_match( $regex, $post->post_content ) ) {
        add_action( 'wp_print_styles', 'lazy_load_slider_bg_add_header_css' );
        add_action( 'wp_print_footer_scripts', 'lazy_load_slider_bg_add_footer_js' );
    }
}

// Print CSS in <head> to hide all slider backgrounds initially
function lazy_load_slider_bg_add_header_css() {
    
    $css_selector = 'html.js #et-boc .et_builder_inner_content .et_pb_slide:not(.et-pb-active-slide):not(.lazy-loaded-slide)';
    
    echo '<style id="lazy-load-divi-slider-backgrounds-css">' . $css_selector . ',' . $css_selector . ' > .et_parallax_bg' . '{background-image:none !important}</style>';
}

// Now we need to add the JS that will load the background images on scroll
function lazy_load_slider_bg_add_footer_js() {
?>
<script id="lazy-load-divi-slider-backgrounds-js">!function(){var a=jQuery.fn.addClass;jQuery.fn.addClass=function(){var e=a.apply(this,arguments);return"et-pb-active-slide"==arguments[0]&&setTimeout(function(){var a=jQuery(".et-pb-active-slide + .et_pb_slide");a.addClass("lazy-loaded-slide"),a.hasClass("et_pb_section_parallax")&&(a=jQuery(".et_parallax_bg",a)).css("background-image",a.css("background-image"))},2e3),e}}();</script>
<?php
}

// Add action
add_action( 'wp', 'lazy_load_slider_bg_check_if_needed' );

?>