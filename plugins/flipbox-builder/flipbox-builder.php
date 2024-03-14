<?php
/*
plugin name: Flipbox Builder
plugin URI: http://testerwp.com/
Description: This is our Flipbox Builder Plugin.
Author: wptexture
Author URI: http://testerwp.com/
version: 1.5
Text Domain:flipbox-builder-text-domain
*/
if (!defined('ABSPATH')) exit;
if (!defined("FLIPBOXBUILDER_URL")) define("FLIPBOXBUILDER_URL", plugin_dir_url(__FILE__));
if (!defined("FLIPBOXBUILDER_DIR_PATH")) define("FLIPBOXBUILDER_DIR_PATH", plugin_dir_path(__FILE__));

function flipbox_builder_Flipbox_default_data()
{
    $settings_array = serialize(array(
        "flip_fliptype" => "Left to Right",
		"flip_itemperrow" => "4",
		"flip_linkopen" => "New Tab",
		"flip_icon_size" => "50",
        "flipfrontcolor" => "#e91e63",
        "flipbackgcolor" => "#0274be",       
        "flip_title_font" => "21",
        "fliptitlecolor" => "#ffffff",
        "flip_title_fontfamily" => "sans-serif",
        "flip_desc_font_size" => "14",
        "flipdesccolor" => "#ffffff",
        "flip_desc_font" => "sans-serif",
        "flip_custom_css" => "",
        "flipbuttoncolor" => "#ffffff",
        "flipbuttonbackccolor" => "#0274be",
        "templates" => "1",
        "flipbuttonbackhcolor" => "#0274be",
        "flipbuttonhcolor" => "#ffffff",
        "flipiconcolor" => "#ffffff",
        "flipbackcolor" => "#000000",
        "flip_textalign" => "Center",
		"flipbuttonborderccolor" => "#ffffff",
		"flipbuttonhbordercolor" => "#ffffff",
    ));

    add_option('flipbox_builder_Flipbox_default_Settings', $settings_array);

}
register_activation_hook(__FILE__, 'flipbox_builder_Flipbox_default_data');

add_image_size('flipbox_image_size',400,400,true);
add_image_size( 'flipbox_image_size1',1024,768,true);

require_once (FLIPBOXBUILDER_DIR_PATH . 'admin/reg_post.php');




function flipbox_builder_flipbox_include_assets()
{

    wp_enqueue_style("flipbox_builder_add-flip", FLIPBOXBUILDER_URL . "/admin/assets/css/add-flipbox.css", '');
	
	wp_enqueue_style("flipbox_builder_fb-css", FLIPBOXBUILDER_URL . "admin/assets/css/fb-css.css", '');
	
    wp_enqueue_style("flipbox_builder_flip-icon-picker-css", FLIPBOXBUILDER_URL . "admin/assets/css/fontawesome-iconpicker.css", '');
	
	wp_enqueue_style("flipbox_builder_flip-gridlayout-css", FLIPBOXBUILDER_URL . "admin/assets/css/gridlayout.css", '');

    wp_enqueue_style("flipbox_builder_flip_bootstrap_css", FLIPBOXBUILDER_URL . "admin/assets/css/bootstrap.css", '');

    wp_enqueue_style("flipbox_builder_flip_font_osm", FLIPBOXBUILDER_URL . "admin/assets/css/font-awesome/css/font-awesome.min.css", '');

    wp_enqueue_script("jquery");
    wp_enqueue_script("flipbox_builder_flip_bootstrap_js", FLIPBOXBUILDER_URL . "admin/assets/js/bootstrap.js", '', true);

    wp_enqueue_script("flipbox_builder_flip-icon-picker-js", FLIPBOXBUILDER_URL . "admin/assets/js/fontawesome-iconpicker.js", '', true);   

    wp_enqueue_style("flipbox_builder_range-slider_css", FLIPBOXBUILDER_URL . "admin/assets/css/range-slider.css", '');     

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('flipbox_builder_flip-color-pic', FLIPBOXBUILDER_URL . 'admin/assets/js/color-picker.js', array(
        'wp-color-picker'
    ) , false, true);
    wp_enqueue_style('flipbox_builder_flip_design-1', FLIPBOXBUILDER_URL . "template/css/design/design-1.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-2', FLIPBOXBUILDER_URL . "template/css/design/design-2.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-3', FLIPBOXBUILDER_URL . "template/css/design/design-3.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-4', FLIPBOXBUILDER_URL . "template/css/design/design-4.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-5', FLIPBOXBUILDER_URL . "template/css/design/design-5.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-6', FLIPBOXBUILDER_URL . "template/css/design/design-6.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-7', FLIPBOXBUILDER_URL . "template/css/design/design-7.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-8', FLIPBOXBUILDER_URL . "template/css/design/design-8.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-9', FLIPBOXBUILDER_URL . "template/css/design/design-9.css", '');
    wp_enqueue_style('flipbox_builder_flip_design-10', FLIPBOXBUILDER_URL . "template/css/design/design-10.css", '');

    $wp_version = get_bloginfo('version');
    if ($wp_version >= '5.9') {
        wp_enqueue_script( 'fix-jquery-ui', FLIPBOXBUILDER_URL. 'admin/assets/js/jquery-ui.js', array('jquery-ui-sortable') );
    }
}
add_action("admin_enqueue_scripts", "flipbox_builder_flipbox_include_assets");
function flipbox_builder_flipbox_frontend_include_assets()
{

    wp_enqueue_style("flipbox_builder_flip_bootstrap_css", FLIPBOXBUILDER_URL . "admin/assets/css/bootstrap.css", '');
    wp_enqueue_style("flipbox_builder_flip_font_osm", FLIPBOXBUILDER_URL . "admin/assets/css/font-awesome/css/font-awesome.min.css", '');
    wp_register_style('flipbox_builder_flip_design-1', FLIPBOXBUILDER_URL . "template-front/css/design/design-1.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-2', FLIPBOXBUILDER_URL . "template-front/css/design/design-2.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-3', FLIPBOXBUILDER_URL . "template-front/css/design/design-3.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-4', FLIPBOXBUILDER_URL . "template-front/css/design/design-4.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-5', FLIPBOXBUILDER_URL . "template-front/css/design/design-5.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-6', FLIPBOXBUILDER_URL . "template-front/css/design/design-6.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-7', FLIPBOXBUILDER_URL . "template-front/css/design/design-7.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-8', FLIPBOXBUILDER_URL . "template-front/css/design/design-8.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-9', FLIPBOXBUILDER_URL . "template-front/css/design/design-9.css", '', rand());
    wp_register_style('flipbox_builder_flip_design-10', FLIPBOXBUILDER_URL . "template-front/css/design/design-10.css", '');
    wp_enqueue_script("jquery");

}

add_action("wp_enqueue_scripts", "flipbox_builder_flipbox_frontend_include_assets");
//Add Custom Meta Box
add_action('add_meta_boxes', 'flipbox_builder_wp_add_flipbox_meta_box');
function flipbox_builder_wp_add_flipbox_meta_box()
{

    add_meta_box('flipbox_id', //id
	__('Select Design Here', 'flipbox-builder-text-domain'),//Title
    'flipbox_builder_wp_flipbox_callback', //Callback
    'fb', //Screen
    'normal', 'high');
}
function flipbox_builder_wp_flipbox_callback($post)
{
    require_once FLIPBOXBUILDER_DIR_PATH . "admin/design.php";
}
add_action('save_post', 'flipbox_builder_Flipbox_save_form_data', 10, 2);
function flipbox_builder_Flipbox_save_form_data($postid, $post)
{
    require_once FLIPBOXBUILDER_DIR_PATH . "admin/flipbox_data_save.php";
}
require_once FLIPBOXBUILDER_DIR_PATH . "template-front/shortcode.php";
?>
