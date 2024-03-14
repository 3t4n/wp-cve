<?php
/**
* Template for add to compare button
*
* @package Wishlist-and-compare
* @link    https://themehigh.com
*/

use \THWWC\base\THWWC_Utils;
use \THWWC\thpublic\THWWC_Public_Settings;

global $product;
global $woocommerce_loop;
$product_id = $product->get_id();
$variable_pdct = $product->get_type() == 'variable' ? true : false;
$compare_class = $variable_pdct ? 'thwwc-bottom-right-thumb-variable-pdct' : 'thwwc-bottom-right-thumb-simple-pdct';

if (apply_filters('thwwc_show_compare_button', true, $product)) {
    $compare_options = THWWC_Utils::thwwc_get_compare_settings();
    if ($compare_options) {
        $compare_type = isset($compare_options['compare_type']) ? $compare_options['compare_type'] : '';
        $compare_icon = isset($compare_options['cmp_icon']) ? $compare_options['cmp_icon'] : 'compare';
        $cmp_icon_color = isset($compare_options['cmp_icon_color']) ? $compare_options['cmp_icon_color'] : '';
        $compare_text = isset($compare_options['compare_text']) ? $compare_options['compare_text'] : '';
        $added_text = isset($compare_options['added_text']) ? $compare_options['added_text'] : '';
        $open_popup = isset($compare_options['open_popup']) ? $compare_options['open_popup'] : '';
        $button_action = isset($compare_options['button_action']) ? $compare_options['button_action'] : '';
        $icon_upload = isset($compare_options['icon_upload']) ? $compare_options['icon_upload'] : '';
        $position = isset($compare_options['shoppage_position']) ? $compare_options['shoppage_position'] : 'after';
        $thumbnail_position = isset($compare_options['thumb_position']) ? $compare_options['thumb_position'] : 'bottom_right';
        $prodposition = isset($compare_options['productpage_position']) ? $compare_options['productpage_position'] : 'after';
        $prodthumbnail_position = isset($compare_options['product_thumb_position']) ? $compare_options['product_thumb_position'] : 'bottom_left';
        if ($button_action == 'page') {
            $compare_table = THWWC_Utils::thwwc_get_compare_table_settings();
            $page_id = isset($compare_table['compare_page']) ? $compare_table['compare_page'] : '';
            $permalink = $page_id == '' ? 'false' : get_permalink($page_id);
        } else {
            $permalink = 'false';
        }
        $position_class_shop = '';
        $related_class = '';
        $position_class_prdct = '';
        if ($position == 'above_thumb' || $prodposition == 'above_thumb') {
            if(is_shop() && $position == 'above_thumb'){
                $position_class_shop = $thumbnail_position == 'bottom_right' ? 'thwwc-above-thumb thwwc-thumb-bottom-right-compare' : '';
            }elseif($woocommerce_loop['name'] == 'related' && $position == 'above_thumb'){
                $related_class = $thumbnail_position == 'bottom_right' ? 'thwwc-thumb-bottom-right-compare-related' : '';
            }elseif($prodposition == 'above_thumb'){
                $position_class_prdct = $prodthumbnail_position == 'bottom_left' ? $compare_class : '';
            }
        }

        $thwwac_products = THWWC_Public_Settings::get_compare_products();
        $compare_id = 'compare-btn'.$product_id;
        $added_id = 'compare-added'.$product_id;
        $img_icon = $compare_icon == 'custom' && $icon_upload != '' ? '<img src="'. esc_url($icon_upload) .'" class="thwwac-iconimg">' : '';
        $button_clr_class = $cmp_icon_color == 'white' ? 'iconwhite' : ($cmp_icon_color == 'black' ? 'iconblack' : '');
        $cmp_icon = $compare_icon == 'compare' ? '<i class="thwwac-icon thwwac-compare-icon '.$button_clr_class.'"></i>' : $img_icon;
        $cmp_icon_added = $compare_icon == 'compare' ? '<i class="thwwac-icon thwwac-added-icon '.$button_clr_class.'"></i>' : $img_icon;
        $button_class = $compare_type == 'button' ? 'button' : 'thwwc_link';
        $items_html = '';
        
        if($woocommerce_loop['name'] == 'related'){
            if (!array_key_exists($product_id, $thwwac_products)) {
                $items_html .= '<div class="thwwc-compare-btn '.esc_attr($related_class).'" id="'. esc_attr($compare_id) .'"><a onclick="compare_add('.esc_js($product_id).','.esc_js($open_popup).')" class="'. esc_attr($button_class) .'">'.$cmp_icon. '<span>' . esc_html(stripcslashes($compare_text)) .'</span></a></div>';
            } else {
                $items_html .= '<div class="thwwc-compare-btn '.esc_attr($related_class).'" id="'. esc_attr($added_id) .'"><a onclick="openmodal(\''.esc_js($permalink).'\')" class="'. esc_attr($button_class) .'">'.$cmp_icon_added . '<span>' . esc_html(stripcslashes($added_text)) .'</span></a></div>';
            }
            $items_html .= '<div class="added_btn thwwc-compare-btn '.esc_attr($related_class).'" id="'. esc_attr($added_id) .'"><a onclick="openmodal(\''.esc_js($permalink).'\')" class="'. esc_attr($button_class) .'">'.$cmp_icon_added . '<span>' . esc_html(stripcslashes($added_text)).'</span></a></div>'; 
        }else{
            if (!array_key_exists($product_id, $thwwac_products)) {
                if(is_shop()){
                    $items_html .= '<div class="thwwc-compare-btn '.esc_attr($position_class_shop).'" id="'. esc_attr($compare_id) .'"><a onclick="compare_add('.esc_js($product_id).','.esc_js($open_popup).')" class="'. esc_attr($button_class) .'">'.$cmp_icon. '<span>' . esc_html(stripcslashes($compare_text)) .'</span></a></div>';
                }else{
                    $items_html .= '<div class="thwwc-compare-btn '.esc_attr($position_class_prdct).'" id="'. esc_attr($compare_id) .'"><a onclick="compare_add('.esc_js($product_id).','.esc_js($open_popup).')" class="'. esc_attr($button_class) .'">'.$cmp_icon. '<span>' . esc_html(stripcslashes($compare_text)) .'</span></a></div>';
                }
            } else { 
                if(is_shop()){
                    $items_html .= '<div class="thwwc-compare-btn '.esc_attr($position_class_shop).'" id="'. esc_attr($compare_id) .'"><a onclick="compare_add('.esc_js($product_id).','.esc_js($open_popup).')" class="'. esc_attr($button_class) .'">'.$cmp_icon. '<span>' . esc_html(stripcslashes($compare_text)) .'</span></a></div>';
                }else{
                    $items_html .= '<div class="thwwc-compare-btn '.esc_attr($position_class_prdct).'" id="'. esc_attr($compare_id) .'"><a onclick="compare_add('.esc_js($product_id).','.esc_js($open_popup).')" class="'. esc_attr($button_class) .'">'.$cmp_icon. '<span>' . esc_html(stripcslashes($compare_text)) .'</span></a></div>';
                }
            }
            if(is_shop()){
                $items_html .= '<div class="added_btn thwwc-compare-btn '.esc_attr($position_class_shop).'" id="'. esc_attr($added_id) .'"><a onclick="openmodal(\''.esc_js($permalink).'\')" class="'. esc_attr($button_class) .'">'.$cmp_icon_added . '<span>' . esc_html(stripcslashes($added_text)).'</span></a></div>';    
            }else{
                $items_html .= '<div class="added_btn thwwc-compare-btn '.esc_attr($position_class_prdct).'" id="'. esc_attr($added_id) .'"><a onclick="openmodal(\''.esc_js($permalink).'\')" class="'. esc_attr($button_class) .'">'.$cmp_icon_added . '<span>' . esc_html(stripcslashes($added_text)).'</span></a></div>';
            }
        }
        
        echo $items_html;
    }
}
?>