<?php
/**
 * The public wishlist page settings functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/thpublic
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\thpublic;

use \THWWC\base\THWWC_Base_Controller;
use \THWWC\base\THWWC_Utils;
use THWWC\thpublic\THWWC_Public_Settings;

use WC_AJAX;

if (!class_exists('THWWC_Public_Wishlist_Page')) :
    /**
     * Public wishlist page settings class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Wishlist_Page extends THWWC_Base_Controller
    {
        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            add_shortcode('thwwac_wishlist', array($this,'wishlist_shortcode'));

            add_action('wp_ajax_add_all_to_cart', array($this, 'add_all_to_cart'));
            add_action('wp_ajax_nopriv_add_all_to_cart', array($this, 'add_all_to_cart'));

            add_action('wp_ajax_multiple_action', array($this, 'multiple_action'));
            add_action('wp_ajax_nopriv_multiple_action', array($this, 'multiple_action'));

            add_action('wp_ajax_add_to_cart_remove', array($this, 'add_to_cart_remove'));
            add_action('wp_ajax_nopriv_add_to_cart_remove', array($this, 'add_to_cart_remove'));

            add_action('wp_ajax_get_product_details', array($this, 'get_product_details'));
            add_action('wp_ajax_nopriv_get_product_details', array($this, 'get_product_details'));
            add_action('wp_ajax_filter_wishlisted_products', array($this, 'filter_wishlisted_products')); 
            add_action('wp_ajax_nopriv_filter_wishlisted_products', array($this, 'filter_wishlisted_products'));
            // add_action('wp_ajax_thwwac_drag_and_drop', array($this, 'drag_and_drop_function'));
            // add_action('wp_ajax_thwwac_drag_and_drop', array($this, 'drag_and_drop_function'));
        }

        /**
         * Function to get wishlist page contents for shortcode.
         *
         * @return html
         */
        public function wishlist_shortcode()
        {
            $return_html = $this->get_items();
            return $return_html;
        }

        /**
         * Function to get wishlist page contents.
         *
         * @param string $key is key
         *
         * @return url
         */
        public function get_items()
        {
            $items_html = '';
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            $listing_type = isset($_GET['thwwc_view']) ? sanitize_text_field($_GET['thwwc_view']) : '';
            $listing_type_class = $listing_type == 'grid' ? 'thwwc-wishlist-grid-view' : '';
            $grid_active_class = $listing_type == 'grid' ? '' : 'thwwc-listing-inactive';
            $list_active_class = $listing_type != 'grid' ? '' : 'thwwc-listing-inactive';

            if (!empty($thwwac_products)) {
                $cartlink = get_permalink(get_option('woocommerce_cart_page_id'));
                $wishlistoption = THWWC_Utils::thwwc_get_wishlist_page_settings();
                if ($wishlistoption) {
                    foreach ($wishlistoption as $key1 => $value) {
                        $$key1 = $value;
                    }
                }
                $show_checkboxes = isset($show_checkboxes) ? $show_checkboxes : 'true';
                $remove_icon_pstn = isset($remove_icon_pstn) ? $remove_icon_pstn : 'left';
                $show_unit_price = isset($show_unit_price) ? $show_unit_price : 'true';
                $show_date_addition = isset($show_date_addition) ? $show_date_addition : 'true';
                $show_stock_status = isset($show_stock_status) ? $show_stock_status : 'true';
                $add_cart_text_show = isset($add_cart_text_show) ? $add_cart_text_show : 'true';
                $add_cart_text_wshlstpage = isset($add_cart_text_wshlstpage) ? $add_cart_text_wshlstpage : __('Add to cart','wishlist-and-compare');
                $show_addallto_cart = isset($show_addallto_cart) ? $show_addallto_cart : 'false';
                $show_actions_button = isset($show_actions_button) ? $show_actions_button : 'true';
                $show_selectedto_cart = isset($show_selectedto_cart) ? $show_selectedto_cart : 'true';
                $add_slct_to_cart_text = isset($add_slct_to_cart_text) ? $add_slct_to_cart_text : __('Add selected to cart','wishlist-and-compare');
                $grid_list_view = isset($grid_list_view) ? $grid_list_view : false;
                $show_wishlist_filter = isset($show_wishlist_filter) ? $show_wishlist_filter : false;
                
                if ($add_cart_text_show != 'true') {
                    $add_cart_text_wshlstpage = __("Add to cart","wishlist-and-compare");
                }
                foreach ($thwwac_products as $pkey => $pvalue) {
                    $pdct_exists = wc_get_product($pkey);
                    $pdct_not_exists[] = !$pdct_exists ? 'empty' : 'not empty';
                }
                $pdct_deleted = (!in_array('not empty', $pdct_not_exists) && in_array('empty', $pdct_not_exists)) ?true : false;
                if (is_array($thwwac_products) && (count($thwwac_products) > 0) && !$pdct_deleted) {
                    if (function_exists( 'wc_print_notices' ) && isset(WC()->session)) {
                        wc_print_notices();
                    }
                    $items_html .='<div class="thwwc-wishlist">';
                    if($show_wishlist_filter == 'true'){
                        $items_html .= '<div class="thwwc-content-filter-option thwwac_filter_col"><select class="thwcc-wishlist-filter filter-select" id="thwcc-filter-select"><option>Choose Filter</option>';
                        $terms_cat = $this->load_product_category('product_cat',$thwwac_products);
                        if($terms_cat){
                            $items_html .='<optgroup label="Category">';
                            foreach ( $terms_cat as $term ) {
                                $items_html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
                            }
                        }                  
                        $terms_tags = $this->load_product_tags('product_tag',$thwwac_products);
                        if($terms_tags){
                            $items_html .='<optgroup label="Tags">';
                            foreach ( $terms_tags as $term ) {
                                $items_html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
                            }                    
                        }
                        $items_html .= '</optgroup></select></div>';
                    }
                    
                    if ($grid_list_view == 'true') {
                        $items_html .= '<div class="thwwc-listing-view"><a class="thwwc-listing '.esc_attr($list_active_class).'" onclick="list_view(this)"><i class="thwwac-icon thwwc-list"></i></a><a class="thwwc-listing '.esc_attr($grid_active_class).'" onclick="grid_view(this)"><i class="thwwac-icon thwwc-grid"></i></a></div>';
                    }
                    $items_html .= '<div class="thwwac-wishlist '.esc_attr($listing_type_class).'" id="thwwac-wishlist"><div id="message"></div><div id="thwwc-resp-table"><div id="thwwc-resp-table-header">';
                    if ($show_checkboxes == 'true') {
                        $items_html .= '<div class="thwwc-table-header-cell thwwac-content-checkbox"><input type="checkbox" id="select_all" onclick="select_all()"></div>';
                    }
                    if ($remove_icon_pstn == 'left') {
                        $items_html .= '<div class="thwwc-table-header-cell thwwac-content-item-remove"></div>';
                    }
                    $items_html .= '<div class="thwwc-table-header-cell thwwac-content-item-image"></div><div class="thwwc-table-header-cell thwwc-content-details">'.esc_html__('Product Details', 'wishlist-and-compare').'</div>';
                    if ($add_cart_text_show == 'true') {
                        $items_html .= '<div class="thwwc-table-header-cell thwwc-content-atc"></div>';
                    }

                    if ($remove_icon_pstn == 'right') {
                        $items_html .= '<div class="thwwc-table-header-cell thwwac-content-item-remove"></div>';
                    }
                    $items_html .= '</div><div id="thwwc-resp-table-body">';
                    // $items_html .= '<span class="handle"></span>';

                    foreach ($thwwac_products as $product_id => $product) {
                        $product_time = $product['time'];
                        $product_order = $product['order'];
                        $product = wc_get_product($product_id);
                        if ($product) {
                            $product_name = $product->get_name();
                            $items_html .= '<div class="thwwc-resp-table-row thwwac-content-item thwwac-content-item-' . esc_attr($product_id) . '" data-id="' . esc_attr($product_id) . '" id="thwwac-table-' . esc_attr($product_id) . '">';
                            $items_html .= '<div class="thwwc-table-body-cell thwwac-content-product-order"><input type="hidden" class="thwwac_product_order" value="'. esc_attr($product_order).'" name="thwwac_product_order" ></div>';
                            // $items_html .= '<i class="thwwac-icon thwwac-scroll"></i></div>';
                            if ($show_checkboxes == 'true') {
                                $items_html .= '<div class="thwwc-table-body-cell thwwac-content-checkbox"><input type="checkbox" class="thwwac_case" value="'. esc_attr($product_id).'" name="thwwac_case" onclick="unselect_all()"></div>';
                            }

                            if ($remove_icon_pstn == 'left') {
                                $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                            }
                            if ($remove_icon_pstn == 'right') {
                                $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell thwwc-remove-right-grid"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                            }
                            $permalink = (get_permalink($product_id)) ? get_permalink($product_id) : '#';
                            $items_html .= '<div class="thwwac-content-item-image thwwc-table-body-cell"><a href="' . esc_url($permalink) . '">' . $product->get_image() . '</a></div>';

                            $items_html .= '<div class="thwwac-table-contents thwwc-table-body-cell thwwc-content-details"><div class="thwwac-content-item-title"><a href="' . esc_url($permalink) . '" class="thwwc-title-shortened">';
                            $string = esc_html($product->get_name()); 
                            $string = strip_tags($string);
                            $link = '';
                            if (strlen($string) > 20) {
                                $string = substr($string, 0, 25);
                                $link = ' ...';
                            }
                            $items_html .= wordwrap(esc_html($string), 30, "<br>\n"). $link;
                            $items_html .= '</a><a href="' . esc_url($permalink) . '" class="thwwc-title-full">' .esc_html($product->get_name()). '</a></div>';

                            if ($show_unit_price == 'true') {
                                $items_html .= '<div class="thwwac-content-item-price">' . $product->get_price_html() . '</div>';
                            }
                            if ($show_date_addition == 'true') {
                                $items_html .= '<div class="thwwac-content-item-time">' . date_i18n(get_option('date_format'), esc_html($product_time)) . '</div>';
                            }
                            if ($show_stock_status == 'true') {
                                $stock = ($product->get_stock_quantity() ? '<p class="stock in-stock">'.$product->get_stock_quantity().' in stock</p>' : ($product->is_in_stock() ? '<p class="stock in-stock">'.esc_html__('In stock', 'wishlist-and-compare').'</p>' : '<p class="stock out-of-stock">'.esc_html__('Out of stock', 'wishlist-and-compare').'</p>'));
                                if ($product->get_stock_status() == 'onbackorder') {
                                    $stock = esc_html__('Available on backorder', 'wishlist-and-compare');
                                }
                                if($product->get_price_html() == '' && !$product->is_type('grouped')) {
                                    $stock = '<p class="stock out-of-stock">'.esc_html__('This product is currently out of stock and unavailable.', 'wishlist-and-compare').'</p>';
                                }

                                $items_html .= '<div class="thwwac-content-item-stock">' . $stock . '</div>';
                            }
                            $items_html .= '</div>';
                            if ($add_cart_text_show == 'true') {
                                $items_html .= $this->add_to_cart_button($product, $product_id, $add_cart_text_wshlstpage);
                            }
                            if ($remove_icon_pstn == 'right') {
                                $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell thwwc-remove-right"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                            }
                            $items_html .= '</div>';
                        }
                    }
                    if ($show_checkboxes == 'true' || $show_addallto_cart == 'true') {
                        $items_html .= '<div class="thwwc-resp-table-row">';
                    }
                    if ($show_checkboxes == 'true') {
                        if ($show_actions_button == 'true') {
                            $items_html .= '<div class="thwwac_actions_col thwwc_select_action"><select class="action-select" id="action-type"><option value="">'. esc_html__('Actions', 'wishlist-and-compare') .'</option><option value="remove">'. esc_html__('Remove', 'wishlist-and-compare') .'</option><option value="add to cart">'.esc_html(stripcslashes($add_cart_text_wshlstpage)).'</option></select><a class="action-select button" id="action-btn" onclick="action()">'. esc_html__('Apply Action', 'wishlist-and-compare') .'</a></div>';
                        }
                    }
                    if ($show_addallto_cart == 'true') {
                        $items_html .= '<div class="thwwac_actions_col thwwc_action_btn"><a id="addallcart" class="action-select button" onclick="addallcart()">'. esc_html(stripcslashes($add_all_to_cart_text)) .'</a></div>';
                    }
                    if ($show_checkboxes == 'true') {
                        if ($show_selectedto_cart == 'true') {
                            $items_html .= '<div class="thwwac_actions_col thwwc_action_btn"><a class="action-select button" id="addcart_selected" onclick="selecttocart()">'. esc_html(stripcslashes($add_slct_to_cart_text)) .'</a></div>';
                        }
                    }
                    if ($show_checkboxes == 'true' || $show_addallto_cart == 'true') {
                        $items_html .= '</div>';
                    }
                    $items_html .= '</div></div>';

                    $items_html .= '</div></div>';
                    $items_html .= $this->confirm_deletion();
                } else {
                    $items_html .= $this->empty_wishlist();
                }

                $all_keys = array();
                if (is_array($thwwac_products)) {
                    foreach($thwwac_products as $thwwac_keys => $thwwac_product){
                        $all_keys[] = $thwwac_keys;
                    }
                }
                $first_key = !empty($all_keys) ? $all_keys[0] : '';
                $items_html .= $this->social_media_icons($first_key);

            } else {
                $items_html .= $this->empty_wishlist();
            }
            return $items_html;
        }

        public function filter_wishlisted_products(){
            if (!isset($_POST['filternonce']) || !wp_verify_nonce($_POST['filternonce'], 'thwwac_filter_security')) {
                die('Sorry, your nonce did not verify');
            }
            $filter_option = isset($_POST['value']) ? wc_clean($_POST['value']) : '';
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            $filtered_product = array();
            $query_args_cat = array();
            $query_args_tag = array();
            $filter_html = '';
            $filter_tag = false;
            $filter_cat = false;
            $products = '';
            $tags = get_terms( 'product_tag' );
            $categories = get_terms( 'product_cat' );
            foreach ($tags as $key => $tag){
                if($filter_option === $tag->slug){
                    $filter_tag = true;
                }
            }
            foreach ($categories as $key => $category){
                if($filter_option === $category->slug){
                    $filter_cat = true;
                }
            }
            if($filter_cat){
                $query_args_cat = array(
                    'category' => array($filter_option),
                );
            }elseif($filter_tag){
                $query_args_tag = array(
                    'tag' => array($filter_option),
                );
            }
                   
            if($query_args_cat){
                $products = wc_get_products( $query_args_cat );
            }
            elseif($query_args_tag){
                $products = wc_get_products( $query_args_tag );
            }
            if($products){
                foreach ($products as $key => $product_data) {
                    $product_id = $product_data->get_id();
                    if(array_key_exists($product_id,$thwwac_products)){
                        $value = $thwwac_products[$product_id];
                        $filtered_product[$product_id] = $value;
                    }
                }
            }
            
            if( !empty($filtered_product)) {
                $filter_html = $this->get_filter_item_html($filtered_product);
                wp_send_json(array(
                    'success' => true,
                    'html' => $filter_html,
                ));
            }
            else {
                $filter_html = $this->get_filter_item_html($thwwac_products);
                    wp_send_json(array(
                    'success' => false,
                    'html' => $filter_html,
                ));
            }
            
            die();
        }

        // public function drag_and_drop_function(){
        //     $updated_data = $_POST['value'];
        //     $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
        //     $sorted_wishlist = array();

        //     if($updated_data && $thwwac_products){
        //         foreach ($updated_data as $key => $value) {
        //             foreach($thwwac_products as $product_id => $thwwac_product){
        //                 if($value['product_id'] == $product_id){
        //                     $thwwac_product['order'] = $value['order'];
        //                     $sorted_wishlist[$product_id] = $thwwac_product;
        //                 }
        //             }
        //         }   
        //     }
        //     THWWC_Public_Settings::update_wishlist_products($sorted_wishlist);
                
        // }

        public function get_filter_item_html($filtered_product){
            $items_html = '';
            if (!empty($filtered_product)) {
                $cartlink = get_permalink(get_option('woocommerce_cart_page_id'));
                $wishlistoption = THWWC_Utils::thwwc_get_wishlist_page_settings();
                if ($wishlistoption) {
                    foreach ($wishlistoption as $key1 => $value) {
                        $$key1 = $value;
                    }
                }
                $show_checkboxes = isset($show_checkboxes) ? $show_checkboxes : 'true';
                $remove_icon_pstn = isset($remove_icon_pstn) ? $remove_icon_pstn : 'left';
                $show_unit_price = isset($show_unit_price) ? $show_unit_price : 'true';
                $show_date_addition = isset($show_date_addition) ? $show_date_addition : 'true';
                $show_stock_status = isset($show_stock_status) ? $show_stock_status : 'true';
                $add_cart_text_show = isset($add_cart_text_show) ? $add_cart_text_show : 'true';
                $add_cart_text_wshlstpage = isset($add_cart_text_wshlstpage) ? $add_cart_text_wshlstpage : __('Add to cart','wishlist-and-compare');
                $show_addallto_cart = isset($show_addallto_cart) ? $show_addallto_cart : 'false';
                $show_actions_button = isset($show_actions_button) ? $show_actions_button : 'true';
                $show_selectedto_cart = isset($show_selectedto_cart) ? $show_selectedto_cart : 'true';
                $add_slct_to_cart_text = isset($add_slct_to_cart_text) ? $add_slct_to_cart_text : __('Add selected to cart','wishlist-and-compare');
                $grid_list_view = isset($grid_list_view) ? $grid_list_view : false;
                foreach ($filtered_product as $product_id => $product) {
                    $product_time = $product['time'];
                    $product_order = $product['order'];
                    $product = wc_get_product($product_id);
                    if ($product) {
                        $product_name = $product->get_name();
                        $items_html .= '<div class="thwwc-resp-table-row thwwac-content-item thwwac-content-item-' . esc_attr($product_id) . '" data-id="' . esc_attr($product_id) . '" id="thwwac-table-' . esc_attr($product_id) . '">';
                        $items_html .= '<div class="thwwc-table-body-cell thwwac-content-product-order"><input type="hidden" class="thwwac_product_order" value="'. esc_attr($product_order).'" name="thwwac_product_order" ></div>';
                        if ($show_checkboxes == 'true') {
                            $items_html .= '<div class="thwwc-table-body-cell thwwac-content-checkbox"><input type="checkbox" class="thwwac_case" value="'. esc_attr($product_id).'" name="thwwac_case" onclick="unselect_all()"></div>';
                        }

                        if ($remove_icon_pstn == 'left') {
                            $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                        }
                        if ($remove_icon_pstn == 'right') {
                            $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell thwwc-remove-right-grid"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                        }
                        $permalink = (get_permalink($product_id)) ? get_permalink($product_id) : '#';
                        $items_html .= '<div class="thwwac-content-item-image thwwc-table-body-cell"><a href="' . esc_url($permalink) . '">' . $product->get_image() . '</a></div>';

                        $items_html .= '<div class="thwwac-table-contents thwwc-table-body-cell thwwc-content-details"><div class="thwwac-content-item-title"><a href="' . esc_url($permalink) . '" class="thwwc-title-shortened">';
                        $string = esc_html($product->get_name()); 
                        $string = strip_tags($string);
                        $link = '';
                        if (strlen($string) > 20) {
                            $string = substr($string, 0, 25);
                            $link = ' ...';
                        }
                        $items_html .= wordwrap(esc_html($string), 30, "<br>\n"). $link;
                        $items_html .= '</a><a href="' . esc_url($permalink) . '" class="thwwc-title-full">' .esc_html($product->get_name()). '</a></div>';

                        if ($show_unit_price == 'true') {
                            $items_html .= '<div class="thwwac-content-item-price">' . $product->get_price_html() . '</div>';
                        }
                        if ($show_date_addition == 'true') {
                            $items_html .= '<div class="thwwac-content-item-time">' . date_i18n(get_option('date_format'), esc_html($product_time)) . '</div>';
                        }
                        if ($show_stock_status == 'true') {
                            $stock = ($product->get_stock_quantity() ? '<p class="stock in-stock">'.$product->get_stock_quantity().' in stock</p>' : ($product->is_in_stock() ? '<p class="stock in-stock">'.esc_html__('In stock', 'wishlist-and-compare').'</p>' : '<p class="stock out-of-stock">'.esc_html__('Out of stock', 'wishlist-and-compare').'</p>'));
                            if ($product->get_stock_status() == 'onbackorder') {
                                $stock = esc_html__('Available on backorder', 'wishlist-and-compare');
                            }
                            if($product->get_price_html() == '' && !$product->is_type('grouped')) {
                                $stock = '<p class="stock out-of-stock">'.esc_html__('This product is currently out of stock and unavailable.', 'wishlist-and-compare').'</p>';
                            }

                            $items_html .= '<div class="thwwac-content-item-stock">' . $stock . '</div>';
                        }
                        $items_html .= '</div>';
                        if ($add_cart_text_show == 'true') {
                            $items_html .= $this->add_to_cart_button($product, $product_id, $add_cart_text_wshlstpage);
                        }
                        if ($remove_icon_pstn == 'right') {
                            $items_html .= '<div class="thwwac-content-item-remove thwwc-table-body-cell thwwc-remove-right"><a onclick="delete_confirmation('. esc_js($product_id) .')"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true"></i></a></div>';
                        }
                        $items_html .= '</div>';
                    }
                }
                if ($show_checkboxes == 'true' || $show_addallto_cart == 'true') {
                    $items_html .= '<div class="thwwc-resp-table-row">';
                }
                if ($show_checkboxes == 'true') {
                    if ($show_actions_button == 'true') {
                        $items_html .= '<div class="thwwac_actions_col thwwc_select_action"><select class="action-select" id="action-type"><option value="">'. esc_html__('Actions', 'wishlist-and-compare') .'</option><option value="remove">'. esc_html__('Remove', 'wishlist-and-compare') .'</option><option value="add to cart">'.esc_html(stripcslashes($add_cart_text_wshlstpage)).'</option></select><a class="action-select button" id="action-btn" onclick="action()">'. esc_html__('Apply Action', 'wishlist-and-compare') .'</a></div>';
                    }
                }
                if ($show_addallto_cart == 'true') {
                    $items_html .= '<div class="thwwac_actions_col thwwc_action_btn"><a id="addallcart" class="action-select button" onclick="addallcart()">'. esc_html(stripcslashes($add_all_to_cart_text)) .'</a></div>';
                }
                if ($show_checkboxes == 'true') {
                    if ($show_selectedto_cart == 'true') {
                        $items_html .= '<div class="thwwac_actions_col thwwc_action_btn"><a class="action-select button" id="addcart_selected" onclick="selecttocart()">'. esc_html(stripcslashes($add_slct_to_cart_text)) .'</a></div>';
                    }
                }
                if ($show_checkboxes == 'true' || $show_addallto_cart == 'true') {
                    $items_html .= '</div>';
                }
                $items_html .= '</div></div>';

                $items_html .= '</div></div>';
                $items_html .= $this->confirm_deletion();
            }
            return $items_html;
        }

        public function filter_match_not_found(){
            $image_url = THWWC_URL.'assets/libs/icons/empty-wishlist-01.svg';
            $items_html = '<div class="thwwac-wishlist-empty"><img src="'.esc_url($image_url).'" height="100" width="100"/><h4>'.__('No Matches Found.', 'wishlist-and-compare') . '</h4></div>';
            return $items_html;
        }

        private function load_product_category($taxonomy,$thwwac_products){
            $filter_terms = array();
            $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'orderby' => 'name'
            ));
            foreach ($thwwac_products as $product_id => $thwwac_product) {
                $product = wc_get_product($product_id);
                $wishlisted_pdct_term_arr = get_the_terms($product_id,$taxonomy);
                    
                if($wishlisted_pdct_term_arr){
                    foreach ($wishlisted_pdct_term_arr as$key => $wishlisted_pdct_term){
                        foreach ($terms as $key => $term) {
                            if($term->term_id === $wishlisted_pdct_term->term_id){
                                if(!in_array($wishlisted_pdct_term, $filter_terms)) {
                                    $filter_terms[] = $wishlisted_pdct_term;
                                }
                            
                            }
                        }
                    }
                }

            }
            return $filter_terms;
        }

        private function load_product_tags($taxonomy,$thwwac_products){
            $filter_terms = array();
            $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'orderby' => 'name'
            ));
            foreach ($thwwac_products as $product_id => $thwwac_product) {
                $product = wc_get_product($product_id);
                $wishlisted_pdct_term_arr = get_the_terms($product_id,$taxonomy);
                if($wishlisted_pdct_term_arr){
                    foreach ($wishlisted_pdct_term_arr as$key => $wishlisted_pdct_term){
                        foreach ($terms as $key => $term) {
                            if($term->term_id === $wishlisted_pdct_term->term_id){
                                if(!in_array($wishlisted_pdct_term, $filter_terms)) {
                                    $filter_terms[] = $wishlisted_pdct_term;
                                }
                            
                            }
                        }
                    }
                }
                

            }
            return $filter_terms;
        }

        private function confirm_deletion() 
        {
            $select = '"remove"';
            $items_html = '<div class="thwwac-modal thwwac-modal-open" id="thwwc-delete-confirm">
                        <div class="thwwac-overlay"></div>
                        <div class="thwwac-table">
                            <div class="thwwac-cell">
                                <div class="thwwac-modal-inner thwwac-confirm-popup">
                                <div class="thwwc-confirm-close"><span onclick="close_confirm()"></span></div>
                                    <input type="hidden" value="" id="thwwc-delete-productid">
                                    <div id="thwwc-pdct-details">
                                    </div>
                                    <div class="thwwac-confirm-txt" id="thwwac-confirm-txt"><p>'. esc_html("Are you sure you want to remove this product?", "wishlist-and-compare") .'</p>
                                        <div class="thwwacwl-buttons-group thwwac-wishlist-clear">
                                            <button class="button thwwc_remove_cancel" type="button" onclick="close_confirm()">'. esc_html('Cancel', 'wishlist-and-compare') .'</button>
                                            <button class="button thwwacwl_button_close thwwac_remove_close" type="button" onclick="wishlist_remove()">'. esc_html('Yes, Remove', 'wishlist-and-compare') .'</button>
                                        </div>
                                    </div>

                                    <div class="thwwac-confirm-txt" id="thwwac-confirm-txt-all"><p>'. esc_html("Are you sure?", "wishlist-and-compare") .'</p>
                                        <div class="thwwacwl-buttons-group thwwac-wishlist-clear">
                                            <button class="button thwwc_remove_cancel" type="button" onclick="close_confirm()">'. esc_html('Cancel', 'wishlist-and-compare') .'</button>
                                            <button class="button thwwacwl_button_close thwwac_remove_close" type="button" onclick="selected_to_cart('.esc_js($select).')">'. esc_html('Yes, Remove', 'wishlist-and-compare') .'</button>
                                        </div>
                                    </div>
                                    <div class="thwwac-wishlist-clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>';
            return $items_html;
        }

        /**
         * Function to show social media icons on wishlist page
         *
         * @return html
         */
        private function social_media_icons($first_key)
        {
            $items_html = '';
            $options = THWWC_Utils::thwwc_get_general_settings();
            $page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : '';
            $permlink = $page_id != '' ? get_permalink($page_id) : '#';
            $img_url = urlencode(get_the_post_thumbnail_url($first_key));
            $thwwac_key = THWWC_Utils::get_key();
            $url = $permlink.'?thwwc_id='.urlencode($thwwac_key);

            $socialsettings = THWWC_Utils::thwwc_get_socialmedia_settings();
            if ($socialsettings) {
                foreach ($socialsettings as $key2 => $value2) {
                    $$key2 = $value2;
                }
                if (isset($share_wishlist) && $share_wishlist == 'true') {
                    $color = '';
                    if (isset($social_icon_color)) {
                        $color = $social_icon_color == 'dark' ? 'iconblack' : ($social_icon_color == 'white' ? 'iconwhite' : '');
                    }
                    $share_on_text = isset($share_on_text) ? $share_on_text : '';
                    $mail_img_url = THWWC_URL.'assets/libs/icons/mail-01.svg';
                    $copy_img_url = THWWC_URL.'assets/libs/icons/copy-01.svg';
                    $whatsp_img_url = THWWC_URL.'assets/libs/icons/whatsapp-01.svg';
                    $pin_img_url = THWWC_URL.'assets/libs/icons/pinterest-01.svg';
                    $twit_img_url = THWWC_URL.'assets/libs/icons/twitter-01.svg';
                    $fb_img_url = THWWC_URL.'assets/libs/icons/facebook-01.svg';
                    $fb_icon = $color != '' ? '<i class="thwwac-icon thwwac-facebook '. esc_attr($color) .'"></i>' : '<img src="'. esc_url($fb_img_url) .'" height="15" width="15">';
                    $twit_icon = $color != '' ? '<i class="thwwac-icon thwwac-twitter '.esc_attr($color).'"></i>' : '<img src="'. esc_url($twit_img_url) .'" height="15" width="15">';
                    $pin_icon = $color != '' ? '<i class="thwwac-icon thwwac-pinterest '. esc_attr($color) .'"></i>' : '<img src="'. esc_url($pin_img_url) .'" height="15" width="13">';
                    $whatsap_icon = $color != '' ? '<i class="thwwac-icon thwwac-whatsapp '. esc_attr($color) .'"></i>' : '<img src="'. esc_url($whatsp_img_url) .'" height="15" width="15">';
                    $mail_icon = $color != '' ? '<i class="thwwac-icon thwwac-envelope '. esc_attr($color) .'"></i>' : '<img src="'. esc_url($mail_img_url) .'" height="15" width="18">';
                    $clipboard_icon = $color != '' ? '<i class="thwwac-icon thwwac-clipboard '. esc_attr($color) .'"></i>' : '<img src="'. esc_url($copy_img_url) .'" height="15" width="15">';

                    $icon = '';
                    $icon .= isset($fb_button) && $fb_button == 'true' ? '<li><a href="https://www.facebook.com/sharer.php?u='.esc_url($url).'" target="_blank" title="facebook">'. $fb_icon .'</a></li>' : '';
                    $icon .= isset($twitter_button) && $twitter_button == 'true' ? '<li><a href="https://twitter.com/share?url='.esc_url($url).'" target="_blank" title="twitter">'. $twit_icon .'</a></li>' : '';
                    $icon .= isset($pi_button) && $pi_button == 'true' ? '<li><a href="https://pinterest.com/pin/create/button/?url='. esc_url($url) .'&media='. esc_attr($img_url) .'" target="_blank" title="pinterest">'. $pin_icon .'<a></li>' : '';
                    $icon .= isset($whtsp_button) && $whtsp_button == 'true' ? '<li><a href="https://web.whatsapp.com/send?text=My wishlist on - '. esc_url($url) .'" target="_blank" title="whatsapp">'. $whatsap_icon .'</a></li>' : '';
                    $icon .= isset($email_button) && $email_button == 'true' ? '<li><a href="mailto:?body=' . esc_url($url) . '" target="_blank" title="email">'.$mail_icon.'</a></li>' : '';
                    $icon .= isset($clipboard_button) && $clipboard_button == 'true' ? '<li><div class="thwwc_tooltip"><div id="secretInfo" style="display: none;">'. esc_url($url) .'</div><a title="clipboard"  id="btnCopy" onclick="copyclipboard()">'. $clipboard_icon .'<span class="thwwc_tooltiptext" id="myTooltip">Click here to copy</span></a></div></li>' : '';

                    $items_html .= '<div class="thwwac_socialshare">';
                    if ($share_on_text != null) {
                        $items_html .= '<p class="thwwac_share_text">'.esc_html($share_on_text).'</p><ul class="thwwac_icons">'.$icon.'</ul>';
                    } else {
                        $items_html .= '<i class="thwwac-icon thwwac-share" onclick="show_icons()"></i><div id="thwwc_share_icons" class="thwwc_share_icons"><ul class="thwwac_icons">'.$icon.'</ul></div>';
                    }
                    $items_html .= '</div>';
                }
            }
            return $items_html;
        }

         /**
         * Function to show empty message if wishlist is empty
         *
         * @return html
         */
        private function empty_wishlist(){
            $image_url = THWWC_URL.'assets/libs/icons/empty-wishlist-01.svg';
            $items_html = '<div class="thwwac-wishlist-empty"><img src="'.esc_url($image_url).'" height="100" width="100"/><h4>'.__('Your wishlist is empty', 'wishlist-and-compare') . '</h4><p>' . esc_html__('Add items to your wishlist now!', 'wishlist-and-compare') . '</p><a class="button thwwc-cart-url" href="'. wc_get_page_permalink('shop').'">'. esc_html__('Return to shop', 'wishlist-and-compare') .'</a></div>';
            return $items_html;
        }

         /**
         * Function to show add to cart button in wishlist page
         *
         * @return html
         */
        private function add_to_cart_button($product, $product_id, $add_cart_text_wshlstpage){
            $items_html = '';
            $select_wepo_text = THWWC_Utils::is_wepo_active();
            $select_option_text = $select_wepo_text['simple'] ? $select_wepo_text['simple'] : 'Add to cart';
            $select_option_text_variable = $select_wepo_text['variable'] ? $select_wepo_text['variable'] : 'Select options';
            $options = THWWC_Utils::thwwc_get_general_settings();
            $remove_if_added = isset($options['remove_pdct']) ? $options['remove_pdct'] : 'true';
            $remove = ($remove_if_added == 'true')? 1 : 0;
            $items_html .= '<div class="thwwac-table-contents thwwc-table-body-cell thwwc-content-atc"><div class="thwwac-content-item-add">';
            $redirect_url = $product->add_to_cart_url();
            $stock_status = $product->is_in_stock();
            $product_url = $product->get_permalink();
            if ($redirect_url == $product_url || $stock_status == false) {
                $items_html .= '<button class="addcart-btn button" onclick="redirect_to_page(\''. esc_js($redirect_url) .'\')">';
                if ($product->is_type('grouped')) {
                    $items_html .= esc_html('View Products');
                } elseif ($product->is_type('variable')) {
                    $items_html .= esc_html($select_option_text_variable);
                } else {
                    $items_html .= ($product->get_price_html() == null || $stock_status == false) ? esc_html('Read more') : (isset($select_option_text) ? esc_html($select_option_text) : esc_html('Read more'));
                }
                $items_html .= '</button>';
            } else {
                $items_html .= '<button class="addcart-btn button" onclick="addtocart_remove('. esc_js($product_id).','.esc_js($remove).');">'. esc_html(stripcslashes($add_cart_text_wshlstpage)) .'</button>';
            }
            $items_html .= '</div></div>';
            return $items_html;
        }

        /**
         * Function to add product to cart with remove or not
         * from wishlist on added to cart (ajax-response).
         *
         * @return json
         */
        public function add_to_cart_remove()
        {
            if (!isset($_POST['cartremovenonce']) || !wp_verify_nonce($_POST['cartremovenonce'], 'thwwac_cartremove_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $remove = isset($_POST['remove']) ? absint($_POST['remove']) : '';
            $product = wc_get_product($product_id);
            $result['product_name'] = $product->get_title();
            $result['carturl'] = get_permalink(get_option('woocommerce_cart_page_id'));
            $options = THWWC_Utils::thwwc_get_general_settings();
            $result['redirect_checkout'] = isset($options['chckut_redrct']) ? $options['chckut_redrct'] : 'false';
            $result['checkouturl'] = get_permalink(get_option('woocommerce_checkout_page_id'));
            $stock = $product->get_stock_quantity();
            $result['stock_by_quantity'] = 'instock';
            $redirect_url = $product->add_to_cart_url();
            $title = $product->get_title();
            $product_url = $product->get_permalink();
            foreach (WC()->cart->get_cart() as $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    $result['cart_quantity'] =  $cart_item['quantity'];
                }
            }
            if (isset($result['cart_quantity'])) {
                if ($stock && $stock == $result['cart_quantity']) {
                    $result['out_of_stock'] = 'outofstock';
                    $result['stock_by_quantity'] = 'outofstock';
                    wc_add_notice(sprintf(__('You cannot add that amount to the cart — we have %s in stock and you already have %s in your cart. <a href="%s" class="button wc-forward">View cart</a>.', 'wishlist-and-compare'), esc_html($result['cart_quantity']), esc_html($result['cart_quantity']), esc_url($result['carturl'])), 'error');
                }
            }
            if ($product->is_in_stock() == true && $redirect_url != $product_url && $result['stock_by_quantity'] != 'outofstock') {
                WC()->cart->add_to_cart($product_id);
                wc_add_notice(sprintf(__('<a href="%s" class="button wc-forward">View cart</a>%s has been added to your cart. ', 'wishlist-and-compare'),  esc_url($result['carturl']), esc_html($title)));
                if ($product_id > 0) {
                    if ($remove == 1) {
                        $this->removed_wishlist_update($product_id);
                    }
                }
            } elseif ($redirect_url == $product_url) {
                wc_add_notice(sprintf(__('%s cannot be added to cart because some requirements are not met.', 'wishlist-and-compare'), esc_html($title)), 'error');
                $result['extra_option'] = true;
            }
            wp_send_json($result);
        }

        /**
         * Function to add all product to cart with remove or not
         * from wishlist on added to cart (ajax-response).
         *
         * @return json
         */
        public function add_all_to_cart()
        {
            if (!isset($_POST['allcartnonce']) || !wp_verify_nonce($_POST['allcartnonce'], 'thwwac_allcart_security')) {
                die('Sorry, your nonce did not verify');
            }
            $options = THWWC_Utils::thwwc_get_general_settings();
            $remove_if_added = isset($options['remove_pdct']) ? $options['remove_pdct'] : '';
            $result['redirect_checkout'] = isset($options['chckut_redrct']) ? $options['chckut_redrct'] : '';
            $result['checkouturl'] = get_permalink(get_option('woocommerce_checkout_page_id'));
            $add_products = array();
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            if ($thwwac_products) {
                foreach ($thwwac_products as $pkey => $value) {
                    $add_products[] = $pkey;
                }
            }

            $result = $this->add_to_cart_with_notice($add_products, $remove_if_added, 'add to cart');
         
            wp_send_json($result);
        }

        /**
         * Function to add product to cart or remove from wishlist
         * according to selection (ajax-response).
         *
         * @return json
         */
        public function multiple_action()
        {
            if (!isset($_POST['multiactionnonce']) || !wp_verify_nonce($_POST['multiactionnonce'], 'thwwac_multiaction_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!isset($_POST['products'])) {
                die();
            }
            $action = isset($_POST['select']) ? sanitize_text_field($_POST['select']) : '';
            $options = THWWC_Utils::thwwc_get_general_settings();
            $remove_if_added = isset($options['remove_pdct']) ? $options['remove_pdct'] : '';
            $added['redirect_checkout'] = isset($options['chckut_redrct']) ? $options['chckut_redrct'] : '';
            $added['checkouturl'] = get_permalink(get_option('woocommerce_checkout_page_id'));
            $add_products = isset($_POST['products']) ? wc_clean($_POST['products']) : '';

            $added = $this->add_to_cart_with_notice($add_products, $remove_if_added, $action);

            wp_send_json($added);
        }

        private function add_to_cart_with_notice(array $add_products, $remove_if_added, $action)
        {
            $cart_url = get_permalink(get_option('woocommerce_cart_page_id'));
            foreach($add_products as $product_id){
                $product_id_each = isset($product_id) ? absint($product_id) : '';
                $added['product_id'][] = $product_id_each;
                $product = wc_get_product($product_id_each);
                if ($action == 'add to cart' && $product) {
                    $redirect_url = $product->add_to_cart_url();
                    $product_url = $product->get_permalink();
                    $stock = $product->get_stock_quantity();
                    foreach (WC()->cart->get_cart() as $cart_item) {
                        if ($cart_item['product_id'] == $product_id_each) {
                            $cart_quantity = $cart_item['quantity'];
                            $added['cart_quantity'][$product_id_each] = $cart_quantity;
                        }
                    }
                    $cart_quantity = isset($cart_quantity) ? $cart_quantity : 0;
                    if ($stock && $stock == $cart_quantity) {
                        $stock_error[] = $product_id_each;
                    } elseif ($redirect_url == $product_url && $product->is_in_stock() == true) {
                        $redirect_error[] = $product->get_title();
                    }
                    
                    if ($stock && $stock == $cart_quantity) {
                    } elseif ($product->is_in_stock() == true && $redirect_url != $product_url) {
                        $added['products'][] = $product->get_title();
                        WC()->cart->add_to_cart($product_id_each);
                        $cart_added[] =  $product->get_title();
                        if ($remove_if_added == 'true') {
                            $this->removed_wishlist_update($product_id_each);
                        }
                    } elseif ($product->is_in_stock() == false) {
                        $outofstock_error[] = $product->get_title();
                    }
                } elseif ($action == 'remove') {
                    $remove_products[] = $product->get_title();
                    $this->removed_wishlist_update($product_id_each);
                }
            }
            if (!empty($cart_added)) {
                $separated = $this->separate_array_products($cart_added);
                wc_add_notice(sprintf(__('<a href="%s" class="button wc-forward">View cart</a>%s has been added to your cart. '), esc_url($cart_url), esc_html($separated)));
                $added['added'] = true;
            }
            if (!empty($stock_error)) {
                for ($i=0; $i<count($stock_error); $i++) {
                    $product = wc_get_product($stock_error[$i]);
                    $title = $product->get_title();
                    $stock_quantity = $product->get_stock_quantity();
                    wc_add_notice(sprintf(__('You cannot add that amount to the cart for %s — we have %s in stock and you already have %s in your cart. <a href="%s" class="button wc-forward">View cart</a>', 'wishlist-and-compare'), esc_html($title), esc_html($stock_quantity), esc_html($stock_quantity), esc_url($cart_url)), 'error');
                }
            }
            if (!empty($redirect_error)) {
                $separated = $this->separate_array_products($redirect_error);
                wc_add_notice(sprintf(__('%s cannot be added to cart because some requirements are not met.', 'wishlist-and-compare'), esc_html($separated)), 'error');
            }
            if (!empty($outofstock_error)) {
                $separated = $this->separate_array_products($outofstock_error);
                wc_add_notice(sprintf(__('You cannot add %s to the cart because the product is out of stock.', 'wishlist-and-compare'), esc_html($separated)), 'error');
            }
            if (!empty($remove_products)) {
                $separated = $this->separate_array_products($remove_products);
                wc_add_notice(sprintf(__('%s removed from wishlist'), esc_html($separated)));
            }
            return $added;
        }

        public function get_product_details(){
            if (!isset($_POST['pdctdetailsnonce']) || !wp_verify_nonce($_POST['pdctdetailsnonce'], 'thwwac_pdctdetails_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $product = wc_get_product($product_id);
            $pdct_name = $product->get_title();
            $image = $product->get_image();
            $items_html = '<div class="thwwc-confirm-image">'.$image .'</div><div class="thwwc-txt-title"><p>'.$pdct_name .'</p></div>';
            wp_send_json($items_html);
        }

        private function removed_wishlist_update($product_id)
        {
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            if (is_array($thwwac_products)) {
                if (array_key_exists($product_id, $thwwac_products)) {
                    unset($thwwac_products[$product_id]);
                    THWWC_Public_Settings::update_wishlist_products($thwwac_products);
                }

                THWWC_Utils::check_product_exists($thwwac_products);
            }
        }

        private function separate_array_products(array $added)
        {
            $added = array_unique($added);
            $separated = implode(', ', $added);
            $portion = strrchr($separated, ',');
            $separated = str_replace($portion, (" and " . substr($portion, 1, strlen($portion))), $separated);
            return $separated;
        }
    }
endif;