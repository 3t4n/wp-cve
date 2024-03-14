<?php 
/**
* Template for add to wishlist button in product page
*
* @package Wishlist-and-compare
* @link    https://themehigh.com
*/

use \THWWC\base\THWWC_Utils;

global $product; 
$variable_pdct = $product->get_type() == 'variable' ? true : false;
$disable_class = $variable_pdct ? 'thwwc_btn_disable' : '';
$cursor_class = $variable_pdct ? 'thwwc_cursor_na' : '';
$product_id = $product->get_id();
if (apply_filters('thwwc_show_wishlist_button', true, $product)) {
    $options = THWWC_Utils::thwwc_get_general_settings();
    $redirect_wishlist = isset($options['redirect_wishlist']) ? $options['redirect_wishlist'] : 'false';
    $ajax_loading = isset($options['ajax_loading']) ? $options['ajax_loading'] : 'true';
    $successnotice = isset($options['success_notice']) ? $options['success_notice'] : 'false';
    $logged_in = !is_user_logged_in() && isset($options['require_login']) && $options['require_login'] == 'true' ? 'no' : 'yes';
    $addedtext = isset($options['wishlst_added_text']) ? $options['wishlst_added_text'] : '';
    $added = $addedtext ? str_replace('{product_name}', '"'.$product->get_name().'"', $addedtext) : '';
    $page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : false;
    $redirect = isset($options['redirect_login']) ? $options['redirect_login'] : '';
    $myaccount_page = get_option('woocommerce_myaccount_page_id');
    $link = isset($myaccount_page) ? get_permalink($myaccount_page) : '#';
    $permalink = $page_id ? get_permalink($page_id) : '#';
    $login_redirect = isset($options['redirect_login']) && $options['redirect_login'] == 'true' ? $link : 'false';

    $productoptions = THWWC_Utils::thwwc_get_product_page_settings();
    $show_in_pdctpage =  isset($productoptions['show_in_pdctpage']) ? $productoptions['show_in_pdctpage'] : 'true';
    $button_type = isset($productoptions['pdct_btn_type']) ? $productoptions['pdct_btn_type'] : ($productoptions ? 'link' : 'button');
    $button_class = $button_type == 'button' ? 'button' : 'thwwc_link';
    $producticon = isset($productoptions['icon_pdct_page']) ? $productoptions['icon_pdct_page'] : 'heart';
    $color = isset($productoptions['wish_icon_color_pdctpage']) ? $productoptions['wish_icon_color_pdctpage'] : '';
    $showtext = isset($productoptions['button_text_pdctpage']) ? $productoptions['button_text_pdctpage'] : 'true';
    $addtext = isset($productoptions['add_wishlist_text_pdctpage']) ? $productoptions['add_wishlist_text_pdctpage'] : 'Wishlist';
    $button_clr_background = isset($productoptions['pdct_btn_background']) ? $productoptions['pdct_btn_background'] : '';
    $button_clr_font = isset($productoptions['pdct_btn_font']) ? $productoptions['pdct_btn_font'] : '';
    $button_clr_border = isset($productoptions['pdct_btn_border']) ? $productoptions['pdct_btn_border'] : '';
    $link_clr_font = isset($productoptions['pdct_link_font']) ? $productoptions['pdct_link_font'] : '';
    $link_font_size = isset($productoptions['pdct_wishlist_font_size']) ? $productoptions['pdct_wishlist_font_size'] : '';
    $showalready = isset($productoptions['already_text_show_pdctpage']) ? $productoptions['already_text_show_pdctpage'] : 'true';
    $alreadytext = isset($productoptions['already_wishlist_text_pdctpage']) ? $productoptions['already_wishlist_text_pdctpage'] : 'Wishlisted';
    $position = isset($productoptions['button_pstn_pdct_page']) ? $productoptions['button_pstn_pdct_page'] : 'after';
    $icon_upload = isset($productoptions['iconp_upload']) ? $productoptions['iconp_upload'] : '';
    $preloader = isset($productoptions['preloader_pdctpage']) ? $productoptions['preloader_pdctpage'] : '';
    $position_class = $position == 'above_thumb' ? 'thwwc-above-thumb-pdct' : '';

    if ($show_in_pdctpage == 'true') {
        $add_btn_id = 'th_add_btn_single'.$product_id;
        $preloader_id = 'preloader'.$product_id;
        $browse_btn_id = 'browse-btn-single'.$product_id;
        $icon_name = $producticon == 'heart' ? 'thwwac-heart ' : 'thwwac-bookmark ';
        $icon_name_added = $producticon == 'heart' ? 'thwwac-heart-o ' : 'thwwac-bookmark-o ';
        $button_classes = $cursor_class.' '.$position_class;
        $link_classes = $button_class.' '.$disable_class;
        $custom_css = '';
        if($button_type == 'button'){
            $button_clr_border = !empty($button_clr_border) ? '2px solid'.$button_clr_border : '';
            $custom_css = "background-color:$button_clr_background; color:$button_clr_font; border: $button_clr_border;";
            
        }else{
            $custom_css = "color:$link_clr_font; font-size:$link_font_size;";
        }
    ?>
    <div class="thwwac-wishlist-single <?php echo esc_attr($button_classes); ?>">
        <div class="wishlist-btn thwwc-wishlist-btn <?php echo esc_attr($add_btn_id); ?>" id="add_btn">
            <a onclick='openpopup("<?php echo esc_js($product_id) ?>","<?php echo esc_js($logged_in) ?>","<?php echo esc_js($login_redirect) ?>","<?php echo esc_js($successnotice) ?>","<?php echo esc_js($ajax_loading) ?>") ' class="thwwac-add-btn thwwc-wish-btn <?php echo esc_attr($link_classes) ?>" style="<?php echo esc_attr($custom_css)?>">
        <?php
            if ($producticon != 'custom') { ?>
                <i class="thwwac-icon <?php echo $icon_name; echo ($color=='white')?'iconwhite':(($color=='black')?'iconblack':'')?>"></i><?php 
            } elseif ($producticon == 'custom') {
                if ($icon_upload != '') {?>
                    <img src="<?php echo esc_url($icon_upload) ?>" class="thwwac-iconimg"><?php
                }
            }
            if ($addtext && ($showtext == 'true')) {
                echo '<span>'.esc_html(stripcslashes($addtext)).'</span>';
            }
            echo '</a>';
            if ($preloader == 'true') { 
                $loader_url = THWWC_URL.'/assets/libs/gif/loader.gif'; ?>
                <div class="preloader" id="<?php echo esc_attr($preloader_id) ?>">
                    <img src="<?php echo esc_url($loader_url) ?>">
                </div>
            <?php } ?>
        </div>

        <div class="browse-btn-single thwwc-wishlist-btn <?php echo esc_attr($browse_btn_id) ?>" id="browse_btn">
            <a onclick="thwwc_browse_action(this)" class="thwwac-browse-btn thwwc-wish-btn <?php echo esc_attr($link_classes) ?>" style="<?php echo esc_attr($custom_css) ?>" data-product_id="<?php echo esc_attr($product_id) ?>"  data-redirect_link="<?php echo esc_attr($permalink) ?>">
            <?php
            if ($producticon != 'custom') {?>
                <i class="thwwac-icon <?php echo $icon_name_added; echo ($color=='white')?'iconwhite':(($color=='black')?'iconblack':'')?>"></i><?php 
            } elseif ($producticon == 'custom') { 
                if ($icon_upload != '') {?>
                    <img src="<?php echo esc_url($icon_upload) ?>" class="thwwac-iconimg"> <?php 
                }
            } 
            if (isset($alreadytext) && ($showalready == 'true')) {
                echo '<span>'.esc_html(stripcslashes($alreadytext)).'</span>';
            }
            echo '</a>'; ?>
        </div>

        <div class="thwwac-modal thwwac-modal-open" id="modal<?php echo esc_attr($product_id) ?>">
            <div class="thwwac-overlay"></div>
            <div class="thwwac-table">
                <div class="thwwac-cell">
                    <div class="thwwac-modal-inner">
                        <div class="thwwac-txt"><?php echo esc_html(stripcslashes($added)); ?></div>
                        <div class="thwwacwl-buttons-group thwwac-wishlist-clear">

                            <?php if ($redirect_wishlist == 'true') { ?>
                            <a href="<?php echo esc_url($permalink) ?>" class="button thwwacwl_button_view thwwacwl-btn-onclick"><?php 
                                if (isset($options['view_button_text'])) {
                                    $button_text = stripcslashes($options['view_button_text']); 
                                    echo esc_html($button_text); 
                                }?>
                            </a>
                            <?php } ?>
                            
                            <button class="button thwwacwl_button_close" type="button" onclick='closepopup("<?php echo esc_js($product_id) ?>")'><?php esc_html_e('Close', 'wishlist-and-compare'); ?></button>
                        </div>
                        <div class="thwwac-wishlist-clear"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="thwwac-modal thwwac-modal-open" id="loginmodal<?php echo esc_attr($product_id) ?>">
            <div class="thwwac-overlay"></div>
            <div class="thwwac-table">
                <div class="thwwac-cell">
                    <div class="thwwac-modal-inner">
                        <div class="thwwac-txt"><?php esc_html_e('Please login to continue', 'wishlist-and-compare'); ?></div>
                        <div class="thwwacwl-buttons-group thwwac-wishlist-clear">
                            <?php global $wp;
                                $current_url = home_url($wp->request);
                                $nonce = wp_create_nonce('redirect_nonce');
                                $link = add_query_arg(array('url'=>$current_url,'id'=>$product_id,'_wpnonce'=>$nonce),$link); 
                                ?>
                            <a href="<?php echo esc_url($link); ?>"><?php esc_html_e('Click here to login', 'wishlist-and-compare'); ?></a>
                            <button class="button thwwacwl_button_close" type="button" onclick='closelogin("<?php echo esc_js($product_id) ?>")'><?php esc_html_e('Close', 'wishlist-and-compare'); ?></button>
                        </div>
                        <div class="thwwac-wishlist-clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
    } 
} ?>