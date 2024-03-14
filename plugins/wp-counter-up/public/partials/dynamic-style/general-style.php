<?php
if (!defined('WPINC')) {
    die;
}


// Style

$lgx_item_brand_value_color          = $lgx_generator_meta['lgx_item_value_color'];
$lgx_item_brand_value_font_size      = $lgx_generator_meta['lgx_item_value_font_size'];
$lgx_item_brand_value_font_weight    = $lgx_generator_meta['lgx_item_value_font_weight'];

$lgx_item_top_margin_value          = $lgx_generator_meta['lgx_item_top_margin_value'];
$lgx_item_bottom_margin_value       = $lgx_generator_meta['lgx_item_bottom_margin_value'];



$lgx_item_brand_name_color          = $lgx_generator_meta['lgx_item_title_color'];
$lgx_item_brand_name_font_size      = $lgx_generator_meta['lgx_item_title_font_size'];
$lgx_item_brand_name_font_weight    = $lgx_generator_meta['lgx_item_title_font_weight'];

$lgx_item_desc_font_size            = $lgx_generator_meta['lgx_item_desc_font_size'];
$lgx_item_desc_color                = $lgx_generator_meta['lgx_item_desc_color'];
$lgx_item_desc_font_weight          = $lgx_generator_meta['lgx_item_desc_font_weight'];

$lgx_img_border_color_en            = $lgx_generator_meta['lgx_img_border_color_en'];
$lgx_img_border_color               = $lgx_generator_meta['lgx_img_border_color'];
$lgx_img_border_color_hover         = $lgx_generator_meta['lgx_img_border_color_hover'];
$lgx_img_border_width               = $lgx_generator_meta['lgx_img_border_width'];
$lgx_img_border_radius              = $lgx_generator_meta['lgx_img_border_radius'];


$lgx_border_color_en                = $lgx_generator_meta['lgx_border_color_en'];
$lgx_item_border_color              = $lgx_generator_meta['lgx_item_border_color'];
$lgx_item_border_color_hover        = $lgx_generator_meta['lgx_item_border_color_hover'];
$lgx_item_border_width              = $lgx_generator_meta['lgx_item_border_width'];
$lgx_item_border_radius             = $lgx_generator_meta['lgx_item_border_radius'];

$lgx_item_bg_color_en               = $lgx_generator_meta['lgx_item_bg_color_en'];
$lgx_item_bg_color                  = $lgx_generator_meta['lgx_item_bg_color'];
$lgx_item_bg_color_hover            = $lgx_generator_meta['lgx_item_bg_color_hover'];


$lgx_icon_bg_color_en               = $lgx_generator_meta['lgx_icon_bg_color_en'];
$lgx_icon_bg_color                  = $lgx_generator_meta['lgx_icon_bg_color'];
$lgx_icon_bg_color_hover            = $lgx_generator_meta['lgx_icon_bg_color_hover'];

$lgx_item_padding                   = $lgx_generator_meta['lgx_item_padding'];
$lgx_item_margin                    = $lgx_generator_meta['lgx_item_margin'];


$lgx_item_top_margin_title          = (isset($lgx_generator_meta['lgx_item_top_margin_title']) ? $lgx_generator_meta['lgx_item_top_margin_title'] : '5px');
$lgx_item_bottom_margin_title       = $lgx_generator_meta['lgx_item_bottom_margin_title'];


$lgx_item_top_margin_desc           = (isset($lgx_generator_meta['lgx_item_top_margin_desc']) ? $lgx_generator_meta['lgx_item_top_margin_desc'] : '0px');
$lgx_item_bottom_margin_desc        = $lgx_generator_meta['lgx_item_bottom_margin_desc'];





//Style Settings

$lgx_lsw_dynamic_style_general = '';
$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_counter_up{
        background-attachment: '. $lgx_section_bg_img_attachment.';
        background-size: '. $lgx_section_bg_img_size.';
        width:'.$lgx_section_width.';
    }';

$lgx_lsw_dynamic_style_general .= ' #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_inner {
    '. (('yes' == $lgx_section_bg_color_en) ? 'background-color: '.$lgx_section_bg_color.';' : '').'
        margin: '. $lgx_section_top_margin.' 0 '. $lgx_section_bottom_margin.';
    }';


$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_title  {
        color: '. $lgx_item_brand_name_color.';
        font-size: '. $lgx_item_brand_name_font_size.';
        font-weight: '. $lgx_item_brand_name_font_weight.';
        margin-top:'. $lgx_item_top_margin_title .';
        margin-bottom: '. $lgx_item_bottom_margin_title .';
    }';

    $lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_counter_value  {
            color: '. $lgx_item_brand_value_color.';
            font-size: '. $lgx_item_brand_value_font_size.';
            font-weight: '. $lgx_item_brand_value_font_weight.';
            margin-top:'. $lgx_item_top_margin_value.';
            margin-bottom: '. $lgx_item_bottom_margin_value.';
        }';

$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_desc  {
        font-size: '. $lgx_item_desc_font_size.';
        color: '. $lgx_item_desc_color.';
        font-weight: '. $lgx_item_desc_font_weight.';
        margin-top:'. $lgx_item_top_margin_desc .';
         margin-bottom: '. $lgx_item_bottom_margin_desc .';
    }';
$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_figure {
      padding:'.$lgx_icon_padding.';    
    '. (('yes' == $lgx_img_border_color_en) ? 'border: '.$lgx_img_border_width.' solid '.$lgx_img_border_color.';' : '').'
    '. (('yes' == $lgx_img_border_color_en) ? 'border-radius:'.$lgx_img_border_radius.';' : '').'
    '. (('yes' == $lgx_icon_bg_color_en) ? 'background-color:'.$lgx_icon_bg_color.';' : '').'
    }';
$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_inner:hover .lgx_app_item_figure{
        transition: background-color 0.5s ease;
    '. (('yes' == $lgx_img_border_color_en) ? 'border: '.$lgx_img_border_width.' solid '.$lgx_img_border_color_hover.';' : '').'
    '. (('yes' == $lgx_icon_bg_color_en) ? 'background-color:'.$lgx_icon_bg_color_hover.';' : '').'
    }';

    //Newly added
    $lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_counter_value {
        width:'.$lgx_value_width.';
        height:'.$lgx_value_height.';   
        '. (('yes' == $lgx_value_border_color_en) ? 'border: '.$lgx_value_border_width.' solid '.$lgx_value_border_color.';' : '').'
        '. (('yes' == $lgx_value_border_color_en) ? 'border-radius:'.$lgx_value_border_radius.';' : '').'
        }';
    $lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_inner:hover .lgx_counter_value{
            transition: background-color 0.5s ease;
        '. (('yes' == $lgx_value_border_color_en) ? 'border-color:'.$lgx_value_border_color.';' : '').'
        }';
    

 /*  $lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item {
            padding: '. $lgx_item_padding.';
        }';*/
$lgx_lsw_dynamic_style_general .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_inner  {
    '. (('yes' == $lgx_border_color_en) ? 'border: '.$lgx_item_border_width.' solid '.$lgx_item_border_color.';' : '').'
    '. (('yes' == $lgx_border_color_en) ? 'border-radius:'.$lgx_item_border_radius.';' : '').'
        margin: '. $lgx_item_margin.';
        padding: '. $lgx_item_padding.';
       '.(('yes' == $lgx_item_bg_color_en) ? 'background-color:'.$lgx_item_bg_color.';' : '').'
    }';
$lgx_lsw_dynamic_style_general .= ' #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_item .lgx_app_item_inner:hover  {
    '. (('yes' == $lgx_border_color_en) ? 'border-color: '.$lgx_item_border_color_hover.';' : '').'
    '. (('yes' == $lgx_item_bg_color_en) ? 'background-color:'.$lgx_item_bg_color_hover.';' : '').'

    }';
    

/**
 *  Inline Style
 */

wp_add_inline_style( 'lgx-counter-up-style', $lgx_lsw_dynamic_style_general );