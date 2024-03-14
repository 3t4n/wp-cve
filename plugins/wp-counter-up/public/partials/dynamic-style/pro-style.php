<?php
if (!defined('WPINC')) {
    die;
}


//Basic
$lgx_item_single_height = $lgx_generator_meta['lgx_item_single_height'];
$lgx_item_single_property_height = $lgx_generator_meta['lgx_item_single_property_height'];

$lgx_item_icon_height = $lgx_generator_meta['lgx_item_icon_height'];
$lgx_item_icon_width  = $lgx_generator_meta['lgx_item_icon_width'];
//print_r($lgx_item_icon_height);
// Height and width
$lgx_item_icon_property_height = (isset($lgx_generator_meta['lgx_item_icon_property_height']) ? $lgx_generator_meta['lgx_item_icon_property_height'] : 'max-height');
$lgx_item_icon_property_width = (isset($lgx_generator_meta['lgx_item_icon_property_width']) ? $lgx_generator_meta['lgx_item_icon_property_width'] : 'max-width');



$lgx_lsw_dynamic_style_pro = '';


// Basic
$lgx_lsw_dynamic_style_pro .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_inner  {
    '.(($lgx_item_single_height != 0) ? trim($lgx_item_single_property_height ).':'. $lgx_item_single_height.';' : '' ).'
}';


$lgx_lsw_dynamic_style_pro .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_img  {
        '.(($lgx_item_icon_width != 0) ? trim($lgx_item_icon_property_width ).':'. $lgx_item_icon_width.';' : '' ).'
        '.(($lgx_item_icon_height != 0) ? trim($lgx_item_icon_property_height ).':'. $lgx_item_icon_height.';' : '' ).'
        object-fit: scale-down;       
    }';


/**
 *  Inline Style
 */

wp_add_inline_style( 'lgx-counter-up-style', $lgx_lsw_dynamic_style_pro );