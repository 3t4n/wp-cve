<?php
/**
 * The wishlist api data for vue in js functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/admin
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\admin;

use \THWWC\base\THWWC_Utils;

if (!class_exists('THWWC_Vue_Api_Wishlist')) :
    /**
     * Vue api for wishlist class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Vue_Api_Wishlist
    {
        /**
         * Function to run api hook.
         *
         * @return void
         */
        public function register()
        {        
            add_action(
                'rest_api_init', function () {
                    register_rest_route(
                        'thwwac/v1', 'data', [
                        'methods' => 'GET',
                        'callback' => array($this,'thwwac_api_func'),
                        'permission_callback' => '__return_true',
                        ]
                    );
                }
            );
        }

        /**
         * Callback function that return with all api data.
         *
         * @return void
         */
        public function thwwac_api_func()
        { 
            $general_settings_data = $this->general_settings_data();

            $shop_settings_data = $this->shop_settings_data();

            $product_settings_data = $this->product_settings_data();
            
            $wishlist_page_settings_data = $this->wishlist_page_settings_data();

            $counter_settings_data = $this->counter_settings_data();

            $socialshare_settings_data = $this->socialshare_settings_data();

            $data = [];

            $data = [['name' => __('Settings', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/settings-01.svg','content' => __('Set the general settings for the wishlist here, including the wishlist page.', 'wishlist-and-compare'),'link' => __('VIEW GENERAL SETTINGS', 'wishlist-and-compare'),'active' => false, 'settings' => [['name' => __('General Settings', 'wishlist-and-compare'), 'click_open' => 'General Settings', 'fields'=> $general_settings_data]],],
                ['name' => __('Shop Page', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/shop page-01.svg','content' => __('Set the Shop page settings for the wishlist button. Also, choose options including the wishlist icon and the display position.', 'wishlist-and-compare'),'link' => __('VIEW SHOP PAGE SETTINGS', 'wishlist-and-compare'), 'active' => false,
                'settings' => [['name' => __('Shop Page', 'wishlist-and-compare'), 'click_open' => 'Shop Page','fields' => $shop_settings_data]]],
                ['name' => __('Product Page', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/product page-01.svg','content' => __('Set Product page settings for the wishlist here.', 'wishlist-and-compare'),'link' => __('VIEW PRODUCT PAGE SETTINGS', 'wishlist-and-compare'), 'active' => false,'settings' => [[
                    'name' => __('Product Page', 'wishlist-and-compare'), 'click_open' => 'Product Page','fields' => $product_settings_data]]],
                ['name' => __('Wishlist Page', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/wishlist page-01.svg','content' => __('Pick all the settings for wishlist page', 'wishlist-and-compare'),'link' => __('VIEW WISHLIST PAGE SETTINGS', 'wishlist-and-compare'), 'active' => false,'settings' => [[
                'name' => __('Wishlist Page', 'wishlist-and-compare'), 'click_open' => 'Wishlist Page','fields' => $wishlist_page_settings_data]]],
                ['name' => __('Wishlist Counter', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/wishlist counter-01.svg','content' => __('Configure the display of the wishlist item count along with Menu.', 'wishlist-and-compare'),'link' => __('VIEW WISHLIST COUNTER SETTINGS', 'wishlist-and-compare'), 'active' => false,'settings' => [[
                    'name' => __('Wishlist Counter', 'wishlist-and-compare'), 'click_open' => 'Wishlist Counter','fields' => $counter_settings_data]]],
                ['name' => __('Social Media Sharing', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/sharing-01.svg','content' => __('Set the social media sharing options for the wishlist here.', 'wishlist-and-compare'),'link' => __('VIEW SOCIAL MEDIA SHARING SETTINGS', 'wishlist-and-compare'), 'active' => false,'settings' => [[
                    'name' => __('Social Media Sharing', 'wishlist-and-compare'), 'click_open' => 'Social Media Sharing', 'fields' => $socialshare_settings_data]]],
            ];
            
            return $data;
        }

        private function general_settings_data()
        {
            $pages = get_pages();
            $title[] = array('value' => '','name' => 'Select');
            foreach ($pages as $page_data) {
                $title[] = array('value' => $page_data->ID,'name' => $page_data->post_title);
            }
            $datas = THWWC_Utils::thwwc_get_general_settings();
            if ($datas) {
                foreach ($datas as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
            }
            $ajax_loading = isset($ajax_loading) ? $ajax_loading : true;
            $remove_on_second_click = isset($remove_on_second_click) ? $remove_on_second_click : false;
            $remove_pdct = isset($remove_pdct) ? $remove_pdct : true;
            $require_login = isset($require_login) ? $require_login : '';
            $redirectlogindepend = ($require_login == true) ? true : false;
            $redirect_login = isset($redirect_login) ? $redirect_login : false;
            $success_notice = isset($success_notice) ? $success_notice : false;
            $wishlist_page = isset($wishlist_page) ? $wishlist_page : '';
            $wishlnk_myaccont = isset($wishlnk_myaccont) ? $wishlnk_myaccont : false;
            $chckut_redrct = isset($chckut_redrct) ? $chckut_redrct : false;
            $successdepend = ($success_notice == true) ? true : false;
            $view_button_text = isset($view_button_text) && $view_button_text != '' ? $view_button_text : 'View wishlist';
            $custom_css_wishlist = isset($custom_css_wishlist) ? $custom_css_wishlist : '';
            $wishlst_added_text = isset($wishlst_added_text) && $wishlst_added_text != '' ? $wishlst_added_text : '{product_name} added to wishlist';
            $redirect_wishlist = isset($redirect_wishlist) ? $redirect_wishlist : false;
            
            $settings_data = [['label' => __('Choose wishlist page', 'wishlist-and-compare'),'type' => 'select_field','options' => $title,'field_name' => 'wishlist_page','dependant' => true,'field_value' => esc_attr($wishlist_page)],
                    ['label' => __('Enable ajax loading', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'ajax_loading','dependant' => true,'field_value' => esc_attr($ajax_loading)],
                    ['label' => __('Remove from wishlist on second click', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'remove_on_second_click','dependant' => true,'field_value' => esc_attr($remove_on_second_click)],
                    ['label' => __('Allow wishlist option for logged-in users only', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'require_login','dependant' => true,'field_value' => esc_attr($require_login),'children' => true],
                    ['label' => __('Redirect to the login page on click of wishlist icon while not logged in', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'redirect_login','dependant' => $redirectlogindepend,'field_value' => esc_attr($redirect_login),'parent' => 'require_login'],
                    ['label' => __('Show wishlist link on my account page', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'wishlnk_myaccont','dependant' => true,'field_value' => esc_attr($wishlnk_myaccont)],
                    ['label' => __('Remove the product from wishlist upon adding to cart', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'remove_pdct','dependant' => true,'field_value' => esc_attr($remove_pdct)],
                    ['label' => __('Redirect to the checkout page from wishlist upon adding product to cart', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'chckut_redrct','dependant' => true,'field_value' => esc_attr($chckut_redrct)],
                    ['label' => __('Show alert in popup upon adding product to wishlist', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'success_notice','dependant' => true,'field_value' => esc_attr($success_notice),'children' => true],
                    ['label' => __('Product successfully added to wishlist display text.', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'wishlst_added_text','dependant' => $successdepend,'field_value' => $wishlst_added_text, 'parent' => 'success_notice'],
                    ['label' => __('Redirect to wishlist page upon adding product to wishlist', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'redirect_wishlist','dependant' => $successdepend,'field_value' => esc_attr($redirect_wishlist), 'parent' => 'success_notice'],
                    ['label' => __('View wishlist button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'view_button_text','dependant' => $successdepend,'field_value' => $view_button_text , 'parent' => 'success_notice'],
                    ['label' => __('Custom Css', 'wishlist-and-compare'),'type' => 'text_area','field_name' => 'custom_css_wishlist','dependant' => true, 'field_value' => $custom_css_wishlist ],
                ];
            return $settings_data;
        }

        private function shop_settings_data()
        {
            $shopdata = THWWC_Utils::thwwc_get_shop_page_settings();
            if ($shopdata) {
                foreach ($shopdata as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
                if (isset($icon_upload)) {
                    $path = parse_url($icon_upload, PHP_URL_PATH); 
                    $shopicon_name = basename($path);
                }
            }
            $show_loop = isset($show_loop) ? $show_loop : true;
            $shop_btn_type = isset($shop_btn_type) ? $shop_btn_type : ($shopdata ? 'link' : 'button');
            $shop_btn_value = isset($shop_btn_type) && $shop_btn_type == 'button' ? 'button' : '';
            $shop_link_value = isset($shop_btn_type) && $shop_btn_type == 'link'  ? 'link' : '';
            $wishlist_position = isset($wishlist_position) ? $wishlist_position : 'after';
            $thumb_position = isset($thumb_position) ? $thumb_position : 'top_left';
            $wish_icon = isset($wish_icon) ? $wish_icon : 'heart';
            $shopdepend = isset($show_loop) && $show_loop == true ? true : false;
            $positiondepend = isset($wishlist_position) && $wishlist_position == 'above_thumb' ? true : false;
            $icondepend = $shopdepend == true && $wish_icon == 'custom' ? true : false;
            $custom_position = $shopdepend == true && $wishlist_position == 'custom' ? true : false;
            $shop_preview = isset($icon_upload) ? ($icon_upload == null ? false : true) : true;
            $icon_upload = isset($icon_upload) ? $icon_upload : '';
            $shopicon_name = isset($shopicon_name) ? $shopicon_name : '';
            $colordepend = $show_loop == true && $wish_icon != 'custom' ? true : false;
            $wish_icon_color = isset($wish_icon_color) ? $wish_icon_color : '';
            $preloader = isset($preloader) ? $preloader : false;
            $button_text = isset($button_text) ?  $button_text : true;
            $buttonshopdepend = $shopdepend == true && $button_text == true ? true : false;
            $add_wishlist_text = isset($add_wishlist_text) ? ($add_wishlist_text == '' ? "Wishlist" : $add_wishlist_text) : "Wishlist";
            $wishlist_btn_height = isset($wishlist_btn_height) ? $wishlist_btn_height : '40';
            $wishlist_btn_style_shop = isset($wishlist_btn_style_shop) ? $wishlist_btn_style_shop : 'default';
            $wishlist_btn_dependent = isset($wishlist_btn_style_shop) && $wishlist_btn_style_shop == 'custom' ? true : false;
            $shop_btn_dependend = isset($shop_btn_type) && $shop_btn_type == 'button'  ? true : false;
            $shop_link_dependend = isset($shop_btn_type) && $shop_btn_type == 'link'   ? true : false;
            $wishlist_style_btn_dependent =  $wishlist_btn_dependent && $shop_btn_dependend  ? true : false;
            $wishlist_style_link_dependent = $wishlist_btn_dependent && $shop_link_dependend ? true : false;
            $shop_btn_background = isset( $shop_btn_background ) ? $shop_btn_background : '';
            $shop_btn_font = isset( $shop_btn_font ) ? $shop_btn_font : '';
            $shop_btn_border = isset( $shop_btn_border ) ? $shop_btn_border : '';
            $shop_link_font = isset( $shop_link_font ) ? $shop_link_font : '';
            $wishlist_font_size = isset($wishlist_font_size) ? $wishlist_font_size : '';
            $already_text_show = isset($already_text_show) ? $already_text_show : true;
            $alreadyshopdepend = $shopdepend == true && $already_text_show == true ? true : false;
            $already_wishlist_text = isset($already_wishlist_text) ? ($already_wishlist_text == '' ? "Wishlisted" : $already_wishlist_text) : "Wishlisted";
            $settings_data = [
                    ['label' => __('Show wishlist button on the shop page', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_loop', 'dependant' => true,'field_value' => esc_attr($show_loop),'children' => true],
                    ['label' => __('Link / Button', 'wishlist-and-compare'),'type' => 'select_change','field_name' => 'shop_btn_type','options' => [['value' => 'button','name' => __('Button', 'wishlist-and-compare')],['value' => 'link','name' => __('Link', 'wishlist-and-compare')]], 'dependant' => $shopdepend,'field_value' => esc_attr($shop_btn_type),'parent' => 'show_loop','children' => true],
                    ['label' => __('Choose the position of the wishlist button', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'after','name' => __('After "Add to Cart" Button', 'wishlist-and-compare')],['value' => 'before','name' => __('Before "Add to cart" Button', 'wishlist-and-compare')],['value' => 'above_thumb','name' => __('Above Thumbnail', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom position with code', 'wishlist-and-compare')]],'field_name' => 'wishlist_position','dependant' => $shopdepend,'field_value' => esc_attr($wishlist_position),'custom_position' => $custom_position,'custom_content' => __('Add this shortcode [thwwac_addtowishlist_loop] anywhere on shop page', 'wishlist-and-compare'),'parent' => 'show_loop'],
                    ['label' => __('Thumbnail position', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'thumb_position','dependant' => $positiondepend,'options' => [['value' => 'top_left','name' => __('Top Left', 'wishlist-and-compare')],['value' => 'top_right','name' => __('Top Right', 'wishlist-and-compare')],['value' => 'bottom_right','name' => __('Bottom Right', 'wishlist-and-compare')]],'field_value' => esc_attr($thumb_position),'parent' => 'wishlist_position','main_parent' => 'show_loop'],
                    ['label' => __('Choose wishlist icon', 'wishlist-and-compare'),'type' => 'select_change','field_name' => 'wish_icon','dependant' => $shopdepend,'options' => [['value' => 'heart','name' => __('Heart', 'wishlist-and-compare')],['value' => 'bookmark','name' => __('Bookmark', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom', 'wishlist-and-compare')]],'field_value' => esc_attr($wish_icon),'parent' => 'show_loop', 'subchild' => true,'children' => true, 'icon_field' => true],
                    ['label' => __('Upload wishlist icon', 'wishlist-and-compare'),'type' => 'file','parent' => 'wish_icon','dependant' => $icondepend,'field_name' => 'icon_upload','field_id' => 'icon_shop','span_id' => 'shop_filename','field_value' => esc_attr($icon_upload),'preview_id' => 'shop_preview','preview' => $shop_preview,"image_name" => $shopicon_name,'main_parent' => 'show_loop'],
                    ['label' => __('Choose wishlist icon color', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'wish_icon_color','dependant' => $colordepend,'options' => [['value' => '','name' => __('Default', 'wishlist-and-compare')],['value' => 'black','name' => __('Black', 'wishlist-and-compare')],['value' => 'white','name' => __('White', 'wishlist-and-compare')]],'field_value' => esc_attr($wish_icon_color),'parent' => 'wish_icon','main_parent' => 'show_loop'],
                    ['label' => __('Wishlist Button/Link Style', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'default','name' => __('Use default theme style', 'wishlist-and-compare')],['value' => 'custom','name' => __('Use custom style')]],'field_name' => 'wishlist_btn_style_shop','dependant' => true,'field_value' => esc_attr($wishlist_btn_style_shop),'parent'=>'shop_btn_type','main_parent'=>'show_loop','subparent_dependent'=> true,'parent_value' => true, 'children' => true],
                    ['type' => 'color_picker','field_name' => 'shop_btn_background','label' => 'Background color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($shop_btn_background),'parent' => 'shop_btn_type','main_parent' => 'show_loop','sub_parent' => 'wishlist_btn_style_shop','subparent_value' => $shop_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $shop_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'shop_btn_font','label' => 'Font color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($shop_btn_font),'sub_parent' => 'wishlist_btn_style_shop','parent'=>'shop_btn_type','main_parent' => 'show_loop','subparent_value' => $shop_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $shop_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'shop_btn_border','label' => 'Border color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($shop_btn_border),'sub_parent' => 'wishlist_btn_style_shop','parent'=>'shop_btn_type','main_parent' => 'show_loop','subparent_value' => $shop_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $shop_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'shop_link_font','label' => 'Font color','dependant' => $wishlist_style_link_dependent,'field_value' => esc_attr($shop_link_font),'sub_parent' => 'wishlist_btn_style_shop','parent'=>'shop_btn_type','main_parent' => 'show_loop','subparent_value' => $shop_link_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $shop_link_dependend],
                    ['label' => __('Font Size (in pixels)', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'wishlist_font_size','placeholder' => __('example: 20px', 'wishlist-and-compare'),'dependant' => $wishlist_style_link_dependent,'parent' => 'shop_btn_type','main_parent' => 'show_loop', 'field_value' => $wishlist_font_size,'sub_parent' => 'wishlist_btn_style_shop','subparent_value' => $shop_link_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $shop_link_dependend],
                    ['label' => __('Enable display of preloader while adding product to wishlist', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'preloader','dependant' => $shopdepend,'field_value' => esc_attr($preloader),'parent' => 'show_loop'],
                    ['label' => __('Display button text along with wishlist button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'button_text','dependant' => $shopdepend,'children' => true,'field_value' => esc_attr($button_text), 'subchild' => true,'parent' => 'show_loop'],
                    ['label' => __('Wishlist button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'add_wishlist_text','dependant' => $buttonshopdepend,'placeholder' => __('Add to wishlist', 'wishlist-and-compare'),'parent' => 'button_text','field_value'=>$add_wishlist_text,'main_parent' => 'show_loop','children' => true, 'subchild' => true],
                    ['label' => __('Display product already added to wishlist message', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'already_text_show','dependant' => $shopdepend,'parent' => 'show_loop','children' => true,'field_value' => esc_attr($already_text_show),'children' => true, 'subchild' => true],
                    ['label' => __('Product already added to wishlist message', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'already_wishlist_text','dependant' => $alreadyshopdepend,'placeholder' => __('Already in wishlist', 'wishlist-and-compare'),'parent' => 'already_text_show','main_parent' => 'show_loop', 'field_value' => $already_wishlist_text],];
            return $settings_data;
        }

        private function product_settings_data()
        {
            $productdata = THWWC_Utils::thwwc_get_product_page_settings();
            if ($productdata) {
                foreach ($productdata as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
                if (isset($iconp_upload)) {
                    $ppath = parse_url($iconp_upload, PHP_URL_PATH);
                    $pdcticon_name = basename($ppath);
                }
            }
            $show_in_pdctpage = isset($show_in_pdctpage) ? $show_in_pdctpage : true;
            $pdct_btn_type = isset($pdct_btn_type) ? $pdct_btn_type : ($productdata ? 'link' : 'button');
            $pdct_btn_value = isset($pdct_btn_type) && $pdct_btn_type == 'button' ? 'button' : '';
            $pdct_link_value = isset($pdct_btn_type) && $pdct_btn_type == 'link'  ? 'link' : '';
            $button_pstn_pdct_page = isset($button_pstn_pdct_page) ? $button_pstn_pdct_page : 'after';
            $icon_pdct_page = isset($icon_pdct_page) ? $icon_pdct_page : 'heart';
            $pdct_depend = $show_in_pdctpage == true ? true : false;
            $iconpdctdepend = $pdct_depend == true && $icon_pdct_page == 'custom' ? true : false;
            $pcolordepend = $pdct_depend == true && $icon_pdct_page != 'custom' ? true : false;
            $pdct_custom_position = $button_pstn_pdct_page == 'custom' ? true : false;
            $pdct_thumb_position = isset($pdct_thumb_position) ? $pdct_thumb_position : 'top_left';
            $pdct_positiondepend = isset($button_pstn_pdct_page) && $button_pstn_pdct_page == 'above_thumb' ? true : false;
            $iconp_upload = isset($iconp_upload) ? $iconp_upload : '';
            $pdct_preview = isset($iconp_upload) ? ($iconp_upload == null ? false : true) : true;
            $pdcticon_name = isset($pdcticon_name) ? $pdcticon_name : '';
            $wish_icon_color_pdctpage = isset($wish_icon_color_pdctpage) ? $wish_icon_color_pdctpage : '';
            $button_text_pdctpage = isset($button_text_pdctpage) ? $button_text_pdctpage : true;
            $wishlist_btn_style_pdct = isset($wishlist_btn_style_pdct) ? $wishlist_btn_style_pdct : 'default';
            $wishlist_btn_dependent = isset($wishlist_btn_style_pdct) && $wishlist_btn_style_pdct == 'custom' ? true : false;
            $pdct_btn_dependend = isset($pdct_btn_type) && $pdct_btn_type == 'button'  ? true : false;
            $pdct_link_dependend = isset($pdct_btn_type) && $pdct_btn_type == 'link'   ? true : false;
            $wishlist_style_btn_dependent =  $wishlist_btn_dependent && $pdct_btn_dependend  ? true : false;
            $wishlist_style_link_dependent = $wishlist_btn_dependent && $pdct_link_dependend ? true : false;
            $prdctpage_button_dependant = isset($button_style_prdctpage) && $button_style_prdctpage == 'custom' ? true : false;
            $pdct_btn_background = isset( $pdct_btn_background ) ? $pdct_btn_background : '';
            $pdct_btn_font = isset( $pdct_btn_font ) ? $pdct_btn_font : '';
            $pdct_btn_border = isset( $pdct_btn_border ) ? $pdct_btn_border : '';
            $pdct_link_font = isset( $pdct_link_font ) ? $pdct_link_font : '';
            $pdct_wishlist_font_size = isset( $pdct_wishlist_font_size ) ? $pdct_wishlist_font_size : '';
            $preloader_pdctpage = isset($preloader_pdctpage) ? $preloader_pdctpage : false;
            $productbuttondepend = $pdct_depend == true && $button_text_pdctpage == true ? true : false;
            $add_wishlist_text_pdctpage = isset($add_wishlist_text_pdctpage) ? ($add_wishlist_text_pdctpage == '' ? "Wishlist" : $add_wishlist_text_pdctpage) : "Wishlist";
            $already_text_show_pdctpage = isset($already_text_show_pdctpage) ? $already_text_show_pdctpage : true;
            $showalreadydepend = $pdct_depend == true && $already_text_show_pdctpage == true ? true : false;
            $already_wishlist_text_pdctpage = isset($already_wishlist_text_pdctpage) ? ($already_wishlist_text_pdctpage == '' ? "Wishlisted" : $already_wishlist_text_pdctpage) : "Wishlisted";
            $settings_data = [['label' => __('Show wishlist button on the product page', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_in_pdctpage', 'dependant' => true,'field_value' => esc_attr($show_in_pdctpage),'children' => true],
                    ['label' => __('Link / Button', 'wishlist-and-compare'),'type' => 'select_change','field_name' => 'pdct_btn_type','options' => [['value' => 'button','name' => __('Button', 'wishlist-and-compare')],['value' => 'link','name' => __('Link', 'wishlist-and-compare')]], 'dependant' => $pdct_depend,'field_value' => esc_attr($pdct_btn_type),'parent' => 'show_in_pdctpage'],
                    ['label' => __('Choose the position of the wishlist button', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'after','name' => __('After "Add to Cart" Button', 'wishlist-and-compare')],['value' => 'before','name' => __('Before "Add to cart" Button', 'wishlist-and-compare')],['value' => 'above_thumb','name' => __('Above Thumbnail', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom position with code', 'wishlist-and-compare')]],'field_name' => 'button_pstn_pdct_page','dependant' => $pdct_depend,'field_value' => esc_attr($button_pstn_pdct_page),'custom_position' => $pdct_custom_position,'custom_content' => __('Add this shortcode [thwwac_addtowishlist] anywhere on product page', 'wishlist-and-compare'),'parent' => 'show_in_pdctpage'],
                    ['label' => __('Thumbnail position', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'pdct_thumb_position','dependant' => $pdct_positiondepend,'options' => [['value' => 'top_left','name' => __('Top Left', 'wishlist-and-compare')]],'field_value' => esc_attr($pdct_thumb_position),'parent' => 'button_pstn_pdct_page','main_parent' => 'show_in_pdctpage'],
                    ['label' => __('Choose wishlist icon', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'heart','name' => __('Heart', 'wishlist-and-compare')],['value' => 'bookmark','name' => __('Bookmark', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom', 'wishlist-and-compare')]],'field_name' => 'icon_pdct_page','dependant' => $pdct_depend,'children' => true,'field_value' => esc_attr($icon_pdct_page), 'icon_field' => true,'parent' => 'show_in_pdctpage','subchild' => true],
                    ['label' => __('Upload wishlist icon', 'wishlist-and-compare'),'type' => 'file','parent' => 'icon_pdct_page','dependant' => $iconpdctdepend,'field_name' => 'iconp_upload','field_id' => 'icon_product','span_id' => 'pdct_filename','field_value' => esc_attr($iconp_upload),'preview_id' => 'pdct_preview','preview' => $pdct_preview,'image_name' => $pdcticon_name,'main_parent' => 'show_in_pdctpage'],
                    ['label' => __('Choose wishlist icon color', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'wish_icon_color_pdctpage','dependant' => $pcolordepend,'options' => [['value' => '','name' => __('Default', 'wishlist-and-compare')],['value' => 'black','name' => __('Black', 'wishlist-and-compare')],['value' => 'white','name' => __('White', 'wishlist-and-compare')]],'field_value' => esc_attr($wish_icon_color_pdctpage),'parent' => 'icon_pdct_page','main_parent' => 'show_in_pdctpage'],
                    ['label' => __('Wishlist Button/Link Style', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'default','name' => __('Use default theme style', 'wishlist-and-compare')],['value' => 'custom','name' => __('Use custom style')]],'field_name' => 'wishlist_btn_style_pdct','dependant' => true,'field_value' => esc_attr($wishlist_btn_style_pdct),'parent'=>'pdct_btn_type','main_parent'=>'show_in_pdctpage','subparent_dependent'=> true,'parent_value' => true, 'children' => true],
                    ['type' => 'color_picker','field_name' => 'pdct_btn_background','label' => 'Background color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($pdct_btn_background),'parent' => 'pdct_btn_type','main_parent' => 'show_in_pdctpage','sub_parent' => 'wishlist_btn_style_pdct','subparent_value' => $pdct_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $pdct_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'pdct_btn_font','label' => 'Font color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($pdct_btn_font),'sub_parent' => 'wishlist_btn_style_pdct','parent'=>'pdct_btn_type','main_parent' => 'show_in_pdctpage','subparent_value' => $pdct_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $pdct_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'pdct_btn_border','label' => 'Border color','dependant' => $wishlist_style_btn_dependent,'field_value' => esc_attr($pdct_btn_border),'sub_parent' => 'wishlist_btn_style_pdct','parent'=>'pdct_btn_type','main_parent' => 'show_in_pdctpage','subparent_value' => $pdct_btn_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $pdct_btn_dependend],
                    ['type' => 'color_picker','field_name' => 'pdct_link_font','label' => 'Font color','dependant' => $wishlist_style_link_dependent,'field_value' => esc_attr($pdct_link_font),'sub_parent' => 'wishlist_btn_style_pdct','parent'=>'pdct_btn_type','main_parent' => 'show_in_pdctpage','subparent_value' => $pdct_link_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $pdct_link_dependend],
                    ['label' => __('Font Size (in pixels)', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'pdct_wishlist_font_size','placeholder' => __('example: 20px', 'wishlist-and-compare'),'dependant' => $wishlist_style_link_dependent,'parent' => 'pdct_btn_type','main_parent' => 'show_in_pdctpage', 'field_value' => esc_attr($pdct_wishlist_font_size),'sub_parent' => 'wishlist_btn_style_pdct','subparent_value' => $pdct_link_value,'subparent_dependent'=>$wishlist_btn_dependent ,'parent_value' => $pdct_link_dependend],
                    
                    ['label' => __('Enable display of preloader while adding product to wishlist', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'preloader_pdctpage','dependant' => $pdct_depend,'field_value' => esc_attr($preloader_pdctpage),'parent' => 'show_in_pdctpage'],
                    ['label' => __('Display button text along with wishlist button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'button_text_pdctpage','dependant' => $pdct_depend,'children' => true,'field_value' => esc_attr($button_text_pdctpage),'parent' => 'show_in_pdctpage', 'subchild' => true],
                    ['label' => __('Wishlist button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'add_wishlist_text_pdctpage','placeholder' => 'Add to wishlist','dependant' => $productbuttondepend,'parent' => 'button_text_pdctpage','field_value' => $add_wishlist_text_pdctpage,'main_parent' => 'show_in_pdctpage'],
                    ['label' => __('Display product already added to wishlist message', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'already_text_show_pdctpage','dependant' => $pdct_depend,'parent' => 'show_in_pdctpage','field_value' => esc_attr($already_text_show_pdctpage),'children' => true, 'subchild' => true],
                    ['label' => __('Product already added to wishlist message', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'already_wishlist_text_pdctpage','placeholder' => __('Already in wishlist', 'wishlist-and-compare'),'dependant' => $showalreadydepend,'parent' => 'already_text_show_pdctpage','field_value' => $already_wishlist_text_pdctpage,'main_parent'=> 'show_in_pdctpage'],];
            return $settings_data;
        }

        private function wishlist_page_settings_data()
        {
            $wishlistdata = THWWC_Utils::thwwc_get_wishlist_page_settings();
            if ($wishlistdata) {
                foreach ($wishlistdata as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
            }
            $add_cart_text_show = isset($add_cart_text_show) ? $add_cart_text_show : true;
            $show_unit_price = isset($show_unit_price) ? $show_unit_price : true;
            $show_stock_status = isset($show_stock_status) ? $show_stock_status : true;
            $show_date_addition = isset($show_date_addition) ? $show_date_addition : true;
            $remove_icon_pstn = isset($remove_icon_pstn) ? $remove_icon_pstn : 'left';
            $show_checkboxes = isset($show_checkboxes) ? $show_checkboxes : true;
            $show_actions_button = isset($show_actions_button) ? $show_actions_button : true;
            $show_selectedto_cart = isset($show_selectedto_cart) ? $show_selectedto_cart : true;
            $add_cart_text_wshlstpage = isset($add_cart_text_wshlstpage) ? ($add_cart_text_wshlstpage == '' ? 'Add to cart' : $add_cart_text_wshlstpage) : 'Add to cart';
            $add_slct_to_cart_text = isset($add_slct_to_cart_text) ? ($add_slct_to_cart_text == '' ? 'Add selected to cart' : $add_slct_to_cart_text) : 'Add selected to cart';
            $add_all_to_cart_text = isset($add_all_to_cart_text) ? ($add_all_to_cart_text == '' ? 'Add all to cart' : $add_all_to_cart_text) : 'Add all to cart';
            $show_actions_button = isset($show_actions_button) ? $show_actions_button : false;
            $show_selectedto_cart = isset($show_selectedto_cart) ? $show_selectedto_cart : false;
            $show_addallto_cart = isset($show_addallto_cart) ? $show_addallto_cart : false;
            $grid_list_view = isset($grid_list_view) ? $grid_list_view : false;
            $show_wishlist_filter = isset($show_wishlist_filter) ? $show_wishlist_filter : false;
            $addcarttextdepend = ($add_cart_text_show == true) ? true : false;
            $checkboxdepend = ($show_checkboxes == true) ? true : false;
            $addalltextdepend = ($show_addallto_cart == true) ? true : false;
            $showselecteddepend = ($show_selectedto_cart == true) ? true : false;
            $settings_data = [
                    ['label' => __('Display add to cart button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'add_cart_text_show','dependant' => true,'children' => true,'field_value' => esc_attr($add_cart_text_show)],
                    ['label' => __('Add to cart text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'add_cart_text_wshlstpage','placeholder' => __('Add to cart', 'wishlist-and-compare'),'parent' => 'add_cart_text_show','dependant' => $addcarttextdepend,'field_value' => $add_cart_text_wshlstpage],
                    ['label' => __('Display unit price', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_unit_price','dependant' => true,'field_value' => esc_attr($show_unit_price)],
                    ['label' => __('Display stock status', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_stock_status','dependant' => true,'field_value' => esc_attr($show_stock_status)],
                    ['label' => __('Display date of addition', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_date_addition','dependant' => true,'field_value' => esc_attr($show_date_addition)],
                    ['label' => __('Choose display position of Icon to remove product from wishlist', 'wishlist-and-compare'),'type' => 'select_field','options' => [['value' => 'left','name' => 'Left'],['value' => 'right','name' => 'Right']],'field_name' => 'remove_icon_pstn','dependant' => true,'field_value' => esc_attr($remove_icon_pstn)],
                    ['label' => __('Display checkboxes for multiselecting products', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_checkboxes','dependant' => true,'children' => true,'field_value' => esc_attr($show_checkboxes)],
                    ['label' => __('Display actions button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_actions_button','dependant' => $checkboxdepend,'parent' => 'show_checkboxes','field_value' => esc_attr($show_actions_button)],
                    ['label' => __('Display add selected to cart button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_selectedto_cart','dependant' =>$checkboxdepend,'parent' => 'show_checkboxes','field_value' => esc_attr($show_selectedto_cart), 'children' => true, 'subchild' => true ],
                    ['label' => __('Add selected to cart button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'add_slct_to_cart_text','placeholder' => __('Add Selected to Cart', 'wishlist-and-compare'),'dependant' => $showselecteddepend,'parent' => 'show_selectedto_cart','main_parent'=> 'show_checkboxes','field_value' => $add_slct_to_cart_text],
                    ['label' => __('Display add all products to cart button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_addallto_cart','dependant' => true,'children' => true,'field_value' => esc_attr($show_addallto_cart)],
                    ['label' => __('Add all products to cart button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'add_all_to_cart_text','placeholder' => __('Add All to Cart', 'wishlist-and-compare'),'dependant' => $addalltextdepend,'parent' => 'show_addallto_cart','field_value' => $add_all_to_cart_text],
                    ['label' => __('Grid / List view', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'grid_list_view','dependant' => true,'children' => true,'field_value' => esc_attr($grid_list_view)],
                    ['label' => __('Show Wishlist filter', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_wishlist_filter','dependant' => true,'children' => true,'field_value' => esc_attr($show_wishlist_filter)],];
            return $settings_data;
        }

        private function counter_settings_data()
        {
            $get_menus = get_terms('nav_menu', array('hide_empty' => false));
            $menu[] = array( 'value' => '','name' => 'Select' );
            foreach ($get_menus as $get_menu) {
                $menu[] = array('value' => $get_menu->term_id,'name' => $get_menu->name);
            }
            $counterdata = THWWC_Utils::thwwc_get_counter_settings();
            if ($counterdata) {
                foreach ($counterdata as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
                if (isset($iconc_upload)) {
                    $cpath = parse_url($iconc_upload, PHP_URL_PATH);
                    $counticon_name = basename($cpath);
                }
            }
            $whlst_counter_text = isset($whlst_counter_text) ? ($whlst_counter_text == '' ? 'wishlist - ' : $whlst_counter_text) : 'wishlist - ';
            $countericondepend = isset($counter_icon) ? ($counter_icon == 'custom' ? true : false) : false;
            $ccolordepend = isset($counter_icon) ? ($counter_icon !='custom' ? true: false) : false;
            $counter_icon = isset($counter_icon) ? $counter_icon : '';
            $count_preview = isset($iconc_upload) ? ($iconc_upload == null ? false : true) : true;
            $iconc_upload = isset($iconc_upload) ? $iconc_upload : '';
            $counticon_name = isset($counticon_name)? $counticon_name: '';
            $show_counter_text = isset($show_counter_text) ? $show_counter_text : false;
            $add_countr_to_menu = isset($add_countr_to_menu) ? $add_countr_to_menu : '';
            $counter_position = isset($counter_position) ? $counter_position : '10';
            $num_pdcts_counter = isset($num_pdcts_counter) ? $num_pdcts_counter : false;
            $hide_zero_value = isset($hide_zero_value) ? $hide_zero_value : false;
            $counter_icon_color = isset($counter_icon_color)? $counter_icon_color : '';
            $countertextdepend = isset($show_counter_text) ? ($show_counter_text == true ? true : false) : false;
            $hidezerodepend = isset($num_pdcts_counter) ? ($num_pdcts_counter == true ? true : false) : false;
            $settings_data = [
                    ['label' => __('Choose wishlist counter icon', 'wishlist-and-compare'),'type' => 'select_change','field_name' => 'counter_icon','dependant' => true,'options' => [['value' => '','name' => __('Select', 'wishlist-and-compare')],['value' => 'heart','name' => __('Heart', 'wishlist-and-compare')],['value' => 'bookmark','name' => __('Bookmark', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom', 'wishlist-and-compare')]],'field_value' => esc_attr($counter_icon), 'icon_field' => true,'children' => true],
                    ['label' => __('Upload icon for wishlist counter', 'wishlist-and-compare'),'type' => 'file','parent' => 'counter_icon','dependant' => $countericondepend,'field_name' => 'iconc_upload','field_id' => 'icon_counter','span_id' => 'counter_filename','field_value' => esc_attr($iconc_upload),'preview_id' => 'count_preview','preview' => $count_preview,"image_name" => $counticon_name],
                    ['label' => __('Wishlist counter icon color', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'counter_icon_color','dependant' => $ccolordepend,'options' => [['value' => '','name' => __('Default', 'wishlist-and-compare')],['value' => 'black','name' => __('Black', 'wishlist-and-compare')],['value' => 'white','name' => __('White', 'wishlist-and-compare')]],'field_value' => esc_attr($counter_icon_color),'parent' => 'counter_icon'],
                    ['label' => __('Enable display of wishlist counter text', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_counter_text','dependant' => true,'children' => true,'field_value' => esc_attr($show_counter_text)],
                    ['label' => __('Wishlist counter text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'whlst_counter_text','dependant' => $countertextdepend,'parent' => 'show_counter_text','field_value' => $whlst_counter_text],
                    ['label' => __('Choose menu to display wishlist counter', 'wishlist-and-compare'),'type' => 'select_field','options' => $menu,'field_name' => 'add_countr_to_menu','dependant' => true, 'field_value' => esc_attr($add_countr_to_menu)],
                    ['label' => __('Counter position (Menu item order)', 'wishlist-and-compare'),'type' => 'number','field_name' => 'counter_position','dependant' => true,'field_value' => esc_attr($counter_position)],
                    ['label' => __('Display number of products in the counter', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'num_pdcts_counter','dependant' => true,'children' => true,'field_value' => esc_attr($num_pdcts_counter)],
                    ['label' => __('Hide zero value', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'hide_zero_value','dependant' => $hidezerodepend,'parent' => 'num_pdcts_counter','field_value' => esc_attr($hide_zero_value)],];
            return $settings_data;
        }

        private function socialshare_settings_data()
        {
            $socialdata = THWWC_Utils::thwwc_get_socialmedia_settings();
            if ($socialdata) {
                foreach ($socialdata as $key => $value) {
                    $$key  = $value == 'true' ? true : ($value == 'false' ? false : stripcslashes($value));
                }
            }
            $share_wishlist = isset($share_wishlist) ? $share_wishlist : false;
            $sharedepend = ($share_wishlist == true)? true : false;
            $fb_button = isset($fb_button) ? $fb_button : false;
            $twitter_button = isset($twitter_button) ? $twitter_button : false;
            $pi_button = isset($pi_button) ? $pi_button : false;
            $whtsp_button = isset($whtsp_button) ? $whtsp_button : false;
            $email_button = isset($email_button) ? $email_button : false;
            $clipboard_button = isset($clipboard_button) ? $clipboard_button : false;
            $share_on_text = isset($share_on_text) ? $share_on_text : '';
            $social_icon_color = isset($social_icon_color) ? $social_icon_color : '';
            $settings_data = [
                    ['label' => __('Enable social sharing for wishlist', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'share_wishlist','dependant' => true,'children' => true,'field_value' => esc_attr($share_wishlist)],
                    ['label' => __('Display Facebook button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'fb_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($fb_button)],
                    ['label' => __('Display Twitter button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'twitter_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($twitter_button)],
                    ['label' => __('Display Pinterest button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'pi_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($pi_button)],
                    ['label' => __('Display WhatsApp button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'whtsp_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($whtsp_button)],
                    ['label' => __('Display Email button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'email_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($email_button)],
                    ['label' => __('Display Copy to clipboard button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'clipboard_button','dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($clipboard_button)],
                    ['label' => __('Share on text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'share_on_text','placeholder' => __('Share on', 'wishlist-and-compare'),'dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($share_on_text)],
                    ['label' => __('Social icons color', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'social_icon_color','options' => [['value' => '','name' => __('Default', 'wishlist-and-compare')],['value' => 'dark','name' => __('Black', 'wishlist-and-compare')],['value' => 'white','name' => __('White', 'wishlist-and-compare')]],'dependant' => $sharedepend,'parent' => 'share_wishlist','field_value' => esc_attr($social_icon_color)],];
            return $settings_data;
        }
    }
endif;