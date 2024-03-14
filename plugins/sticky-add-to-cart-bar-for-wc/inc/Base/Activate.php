<?php
/**
 * @package  WooCart
 */

namespace WscInc\Base;

class Activate{
	public static function activate() {
		flush_rewrite_rules();

		//$default is used to create option while activating the plugin
		$default = array(
            "enable"                    =>"1",
            "desktop"                   =>"1",
            "mobile"                    =>"1",
            //"scroll"                    =>"",
            "position"                  =>"bottom",
            "height"                    =>"",
            "dis_products"              =>"",
            "ajaxcart"                  =>"1",
            "redirect"                  =>"none",
            "show_image"                =>"1",
            "show_bar_variable"         =>"1",
            "show_range_price_variable" =>"1",
            
            "add_cart_text"             =>"",
            "star"                      =>"1",
            "review"                    =>"1",
            "stock"                     =>"1",
            "stock_color"               =>"#000000",
            "review_count_color"        =>"#000000",
            "bg_color"                  =>"#ffffff", 
            "bg_image"                  =>"",
            "bg_image_size"             =>"contain",
            "bg_image_position"         =>"center",
            //"bg_repeat"                 =>"",
            "star_bg_color"             =>"", 
            "star_color"                =>"#000000",
            "border_color"              =>"",
            "border_shadow"             =>"1",
            "cart_btn_bg"               =>"#000000",
            "cart_btn_bg_hover"         =>"#444444",
            "btn_text_color"            =>"#ffffff",
            "btn_text_color_hover"      =>"#ffffff",
            "out_stock_color"           =>"#dd3333",
            "price_text_color"          =>"#000000",
            "price_text_bg_color"       =>"#000000",
            //"sale_badge"                =>"1",
            "sale_badge_text"           =>"",
            "sale_badge_text_color"     =>"#ffffff",
            "sale_badge_bg_color"       =>"#000000",
            "product_text_color"        =>"#000000",
            "animate_btn"               =>"wiggle",
            "image_shape"               =>"0%",
            "price_bg_shape"            =>"0px",
            "cart_icon_image"           =>"",
        );

		if ( ! get_option( 'woo_sticky_cart' ) ) {
			update_option( 'woo_sticky_cart', $default );
		}
	}
}