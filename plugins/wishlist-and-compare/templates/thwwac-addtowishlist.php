<?php
/**
* Template for add to wishlist button
*
* @package Wishlist-and-compare
* @link    https://themehigh.com
*/

use \THWWC\base\THWWC_Base_Controller;
use \THWWC\base\THWWC_Utils;

$this->controller = new THWWC_Base_Controller();

// if (!is_product()) {
    global $product;
    $stock = ($product->is_in_stock()) ? 'instock' : 'outofstock';
    if (apply_filters('thwwc_show_wishlist_button', true, $product)) {
        $product_id = $product->get_id();
        $options = THWWC_Utils::thwwc_get_general_settings();
        $page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : false;
        $permalink = $page_id ? get_permalink($page_id) : '#';
        $ajax_loading_set = isset($options['ajax_loading']) ? $options['ajax_loading'] : 'true';
        $ajax_loading = $ajax_loading_set == 'true' ? 'true' : 'false';
        $logged_in = (!is_user_logged_in() && isset($options['require_login']) && $options['require_login'] == 'true') ? 'no' : 'yes';
        $myaccount_page = get_option('woocommerce_myaccount_page_id');
        $link = isset($myaccount_page) ? get_permalink($myaccount_page) : '#';
        $login_redirect = (isset($options['redirect_login']) && $options['redirect_login'] == 'true') ? $link : 'false';
        $successnotice = isset($options['success_notice']) && $options['success_notice'] == 'true' ? 'true' : 'false';

        $shopoptions = THWWC_Utils::thwwc_get_shop_page_settings();
        $shopicon = isset($shopoptions['wish_icon']) ? $shopoptions['wish_icon'] : 'heart';
        $color = isset($shopoptions['wish_icon_color']) ? $shopoptions['wish_icon_color'] : '';
        $button_type = isset($shopoptions['shop_btn_type']) ? $shopoptions['shop_btn_type'] : ($shopoptions ? 'link' : 'button');
        $thumbnail_position = isset($shopoptions['thumb_position']) ? $shopoptions['thumb_position'] : 'top_left';
        $showtext = isset($shopoptions['button_text']) ? $shopoptions['button_text'] : 'true';
        $addtext = isset($shopoptions['add_wishlist_text']) ? $shopoptions['add_wishlist_text'] : 'Wishlist';
        $showalready = isset($shopoptions['already_text_show']) ? $shopoptions['already_text_show'] : 'true';
        $alreadytext = isset($shopoptions['already_wishlist_text']) ? $shopoptions['already_wishlist_text'] : 'Wishlisted';
        $preloader = isset($shopoptions['preloader']) ? $shopoptions['preloader'] : '';
        $button_clr_background = isset($shopoptions['shop_btn_background']) ? $shopoptions['shop_btn_background'] : '';
        $button_clr_font = isset($shopoptions['shop_btn_font']) ? $shopoptions['shop_btn_font'] : '';
        $button_clr_border = isset($shopoptions['shop_btn_border']) ? $shopoptions['shop_btn_border'] : '';
        $link_clr_font = isset($shopoptions['shop_link_font']) ? $shopoptions['shop_link_font'] : '';
        $link_font_size = isset($shopoptions['wishlist_font_size']) ? $shopoptions['wishlist_font_size'] : '';
        $icon_upload = isset($shopoptions['icon_upload']) ? $shopoptions['icon_upload'] : '';
        $position = isset($shopoptions['wishlist_position']) ? $shopoptions['wishlist_position'] : 'after';
        $button_class = $button_type == 'button' ? 'button' : 'thwwc_link';
        $icon_name = $shopicon == 'heart' ? 'thwwac-heart ' : 'thwwac-bookmark ';
        $icon_name_added = $shopicon == 'heart' ? 'thwwac-heart-o ' : 'thwwac-bookmark-o ';
        $button_clr_class = $color == 'white' ? 'iconwhite' : ($color == 'black' ? 'iconblack' : '');
        $position_class = '';
        $compare_shop_option = THWWC_Utils::thwwc_get_compare_settings();
        $cmpr_shoppage_position = isset($compare_shop_option['shoppage_position']) ? $compare_shop_option['shoppage_position'] : 'after';
        if ($position == 'above_thumb') {
            $position_class = $thumbnail_position == 'bottom_right' ? 'thwwc-above-thumb thwwc-thumb-bottom-right' : ($thumbnail_position == 'top_right' ? 'thwwc-above-thumb thwwc-thumb-top-right' : 'thwwc-above-thumb thwwc-thumb-top-left');
            $position_class = ($thumbnail_position == 'bottom_right' && $cmpr_shoppage_position == 'above_thumb') ? 'thwwc-above-thumb thwwc-thumb-bottom-right-wishlist' : $position_class; 
        }
        $wishlist_id = 'add'.$product_id;
        $browse_id = 'browse'.$product_id;
        $custom_css = '';
        if($button_type == 'button'){
            $button_clr_border = empty($button_clr_border) ? '' : '2px solid'.$button_clr_border;
            $custom_css = "background-color:$button_clr_background; color:$button_clr_font; border: $button_clr_border;";
            
        }else{
            $custom_css = "color:$link_clr_font; font-size:$link_font_size;";
        }
        ?>
        <div id="<?php echo esc_attr($wishlist_id) ?>" class="wishlist-btn thwwc-wishlist-btn <?php echo esc_attr($position_class); ?>">
            <a onclick="openpopup('<?php echo esc_js($product_id) ?>','<?php echo esc_js($logged_in) ?>','<?php echo esc_js($login_redirect) ?>','<?php echo esc_js($successnotice) ?>','<?php echo esc_js($ajax_loading) ?>','shop')" class="thwwac-add-btn <?php echo esc_attr($button_class) ?>" style="<?php echo esc_attr($custom_css)?>">
                <?php
            if ($shopicon != 'custom') { ?>
                <i class="thwwac-icon <?php echo esc_attr($icon_name) ?> <?php echo esc_attr($button_clr_class) ?>"></i><?php 
            } elseif ($shopicon == 'custom') {
                if ($icon_upload != '') { ?>
                    <img src="<?php echo esc_url($icon_upload) ?>" class="thwwac-iconimg"> <?php
                }
            }
            if ($addtext && ($showtext == 'true')) { 
                echo '<span>'.esc_html(stripcslashes($addtext)).'</span>'; 
            } 
            echo '</a>';
            if ($preloader == 'true') { 
                $loader_url = THWWC_URL.'/assets/libs/gif/loader.gif';
                $preloader_id = 'preloader'.$product_id; ?>
                <div class="preloader" id="<?php echo esc_attr($preloader_id) ?>">
                <img src="<?php echo esc_url($loader_url) ?>"></div>
            <?php } ?>
        </div>

        <div class="browse-btn browse-display thwwc-wishlist-btn <?php echo esc_attr($position_class); ?>" id="<?php echo esc_attr($browse_id) ?>">
            <a onclick="thwwc_browse_action(this)" class="thwwac-browse-btn <?php echo esc_attr($button_class) ?>" style="<?php echo esc_attr($custom_css) ?>" data-product_id="<?php echo esc_attr($product_id) ?>" data-redirect_link="<?php echo esc_attr($permalink) ?>">
            <?php 
            if ($shopicon != 'custom') { ?>
                <i class="thwwac-icon <?php echo esc_attr($icon_name_added); ?> <?php echo esc_attr($button_clr_class); ?>"></i><?php
            } elseif ($shopicon == 'custom') { 
                if ($icon_upload != '') { ?>
                    <img src="<?php echo esc_url($icon_upload) ?>" class="thwwac-iconimg"><?php
                }
            }
            if (isset($alreadytext) && ($showalready == 'true')) {
                echo '<span>'.esc_html(stripcslashes($alreadytext)).'</span>';
            }
            echo '</a>';
            ?>
        </div>

        <div class="thwwac-modal thwwac-modal-open" id="loginmodal<?php echo esc_attr($product_id) ?>">
            <div class="thwwac-overlay"></div>
            <div class="thwwac-table">
                <div class="thwwac-cell">
                    <div class="thwwac-modal-inner thwwac-login-popup">
                        <div class="thwwc-confirm-close"><span onclick="closelogin('<?php echo esc_js($product_id) ?>')"></span></div>
                        <?php $img_url = THWWC_URL.'assets/libs/icons/login-to-continue-01.svg'; ?>
                        <img src="<?php echo esc_url($img_url) ?>" height="45" width="45">
                        <div class="thwwac-txt"><p><?php esc_html_e('Please login to continue', 'wishlist-and-compare'); ?></p></div>
                        <div class="thwwacwl-buttons-group thwwac-wishlist-clear">
                            <?php global $wp;
                                $current_url = home_url( $wp->request );
                                $nonce = wp_create_nonce('redirect_nonce');
                                $link = add_query_arg(array('url'=>$current_url,'id'=>$product_id,'_wpnonce'=>$nonce),$link); ?>
                            <a class="button thwwacwl_button_view" href="<?php echo esc_url($link); ?>"><?php esc_html_e('Click here to login', 'wishlist-and-compare'); ?></a>
                        </div>
                        <div class="thwwac-wishlist-clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } 
 ?>