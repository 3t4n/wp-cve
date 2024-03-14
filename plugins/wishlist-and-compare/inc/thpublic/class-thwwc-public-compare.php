<?php
/**
 * The public compare functionality of the plugin.
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

if (!class_exists('THWWC_Public_Compare')) :
    /**
     * Public compare class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Compare extends THWWC_Base_Controller
    {
        public $compare_products = array();

        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            $this->controller = new THWWC_Base_Controller();
            $theme = wp_get_theme();            
            $compare_options = THWWC_Utils::thwwc_get_compare_settings();
            $show_in_shop = isset($compare_options['show_in_shop']) ? $compare_options['show_in_shop'] : '';
            $thumbnail_position = isset($compare_options['thumb_position']) ? $compare_options['thumb_position'] : 'bottom_right';
            $shopposition = isset($compare_options['shoppage_position']) ? $compare_options['shoppage_position'] : '';
            $show_in_product = isset($compare_options['show_in_product']) ? $compare_options['show_in_product'] : '';
            $product_position = isset($compare_options['productpage_position']) ? $compare_options['productpage_position'] : '';
            $hook_name_thumb_bottom = $theme->name == 'OceanWP' ? 'ocean_after_archive_product_image' : 'woocommerce_before_shop_loop_item_title';
            $thwwc_hook_thumbnail_bottom = apply_filters('thwwc_hook_name_thumbnail_bottom', $hook_name_thumb_bottom);
            $hook_name_thumb_bottom_pdct = 'woocommerce_product_thumbnails';
            $thwwc_hook_thumbnail_bottom_pdct = apply_filters('thwwc_hook_thumbnail_bottom_pdct', $hook_name_thumb_bottom_pdct);

            if ($show_in_shop == 'true') {
                if ($shopposition == 'after') {
                    add_action('woocommerce_after_shop_loop_item', array($this, 'add_button' ), 12);
                } elseif ($shopposition == 'before') {
                    $theme = wp_get_theme();
                    if ($theme->name == 'OceanWP' || $theme->name == 'Astra') {
                        add_filter('woocommerce_loop_add_to_cart_link', array($this,'th_replace_add_to_cart_button'), 10, 2);
                    } elseif ($theme->name == 'Avada') {
                        add_action('woocommerce_after_shop_loop_item', array($this, 'add_button'),10);
                    } else {
                        add_action('woocommerce_after_shop_loop_item', array($this, 'add_button'), 8);
                    }
                }  elseif ($shopposition == 'above_thumb' && $thumbnail_position == 'bottom_right') {
                    add_action($thwwc_hook_thumbnail_bottom, array($this, 'add_button'), 11);
                }
            }
            if ($show_in_product == 'true') {
                if ($product_position == 'after') {
                    add_action('woocommerce_after_add_to_cart_form', array($this, 'add_button'), 12);
                } elseif ($product_position == 'above_thumb') {
                    add_action($thwwc_hook_thumbnail_bottom_pdct, array($this, 'add_button'), 8);
                } else {
                    add_action('woocommerce_before_add_to_cart_form', array($this, 'add_button'), 12);
                }
            }
            add_action('wp_ajax_add_compare', array($this, 'add_compare'));
            add_action('wp_ajax_nopriv_add_compare', array($this, 'add_compare'));

            add_action('wp_ajax_update_compare_on_back', array($this, 'update_compare_on_back'));
            add_action('wp_ajax_nopriv_update_compare_on_back', array($this, 'update_compare_on_back'));

            add_action('wp_ajax_remove_compare', array($this, 'remove_compare'));
            add_action('wp_ajax_nopriv_remove_compare', array($this, 'remove_compare'));

            add_action('wp_ajax_compare_addtocart', array($this, 'compare_add_to_cart'));
            add_action('wp_ajax_nopriv_compare_addtocart', array($this, 'compare_add_to_cart'));

            add_action('wp_ajax_hide_show', array($this, 'hide_show'));
            add_action('wp_ajax_nopriv_hide_show', array($this, 'hide_show'));

            add_shortcode('thwwac_compare_list', array($this,'compare_page'));

            add_filter('the_content', array($this, 'shortcode_content_filter_compare'), 10);
        }

         /**
         * Function to replace add to cart button if theme is Astra, Avada or OceanWp.
         *
         * @param string $button  is button anchor
         * @param object $product is product details
         *
         * @return button html
         */
        public function th_replace_add_to_cart_button($button, $product)
        {
            if (is_shop()) {
                $button_link = $product->get_permalink();
                $button = $this->add_button().$button;
                return $button;
            }
        }

        /**
         * Function to show add to compare button.
         *
         * @return void
         */
        public function add_button()
        {
            include THWWC_PATH . "/templates/thwwac-addtocompare.php";
        }

        /**
         * Function to show add to compare button.
         *
         * @return void
         */
        public function add_button_single_page()
        {
            global $product;
            if ($product) {
                if (!$product->is_in_stock() || $product->get_price_html() == null) {
                    include THWWC_PATH . "/templates/thwwac-addtocompare.php";
                }
            }
        }

        /**
         * Function to show compare page by shortcode.
         *
         * @return html with compare page contents
         */
        public function compare_page()
        {
            if (function_exists('wc_print_notices') && isset(WC()->session)) {
                wc_print_notices();
            }
            $thwwac_products = THWWC_Public_Settings::get_compare_products();
            $items_html = '';
            $table_options = THWWC_Utils::thwwc_get_compare_table_settings();
            $hide_attribute_btn = $table_options && isset($table_options['hide_show']) ? $table_options['hide_show']: '';
            if ($hide_attribute_btn == 'true' && !empty($thwwac_products)) {
                $items_html .= '<div class="thwwac_hide_show"><input type="checkbox" onclick="hide_show()" name="differences" value="hide"> '.esc_html__(' Show only differences', 'wishlist-and-compare').'</div>';
            }
            $items_html .= '<div class="thwwc-compare-page" id="thwwc-compare-page"><input type="hidden" id="thwwac_is_page" value="1">';
            $items_html .= $this->compare_contents($thwwac_products);
            return $items_html;
        }

        /**
         * Function after adding product to compare(ajax-response).
         *
         * @return json
         */
        public function add_compare()
        {
            if (!isset($_POST['addcmpnonce']) || !wp_verify_nonce($_POST['addcmpnonce'], 'thwwac_addcmp_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $table_options = THWWC_Utils::thwwc_get_compare_table_settings();
            $hide_attribute_btn = $table_options && isset($table_options['hide_show']) ? $table_options['hide_show'] : false;
            $hide_default = $table_options && isset($table_options['hide_attribute']) ? $table_options['hide_attribute'] : false;
            $thwwac_products = THWWC_Public_Settings::get_compare_products();
            $return['items_html'] = '<div id="added-msg" class="thwwac-compare-msg"></div><input type="hidden" id="thwwac_is_page" value="0">';

            if ($product_id > 0) {
                if (!array_key_exists($product_id, $thwwac_products)) {
                    $thwwac_products = array($product_id => time()) + $thwwac_products;
                    THWWC_Public_Settings::update_compare_products($thwwac_products);

                    $return['items_html'] .= $this->compare_contents($thwwac_products);
                }
                if ($hide_default == 'true' && $hide_attribute_btn != 'true') {
                    $return['hide_default'] = $this->difference_fields();
                } else {
                    $return['hide_default']['fields'] = array();
                }
            }
            wp_send_json($return);
        }

        /**
         * Function to show compare contents in compare page.
         *
         * @return html contents
         */
        public function compare_contents(array $thwwac_products)
        {
            $table_options = THWWC_Utils::thwwc_get_compare_table_settings();
            $show_fields = ($table_options && isset($table_options['fields'])) ? $table_options['fields'] : array('show_image' => 'true', 'show_title' => 'true', 'show_price' => 'true', 'show_description' => 'true' ,'show_addtocart' => 'true');
            $show_remove = ($table_options && isset($table_options['remove_button'])) ? $table_options['remove_button']: '';
            $hide_attribute_btn = ($table_options && isset($table_options['hide_show'])) ? $table_options['hide_show']: 'false';
            $hide_default = ($table_options && isset($table_options['hide_attribute'])) ? $table_options['hide_attribute'] : 'false';
            $field_count = 0;
            foreach ($show_fields as $key => $value) {
                if ($value == 'true') {
                    $field_count++;
                }
            }
            $thwwac_products = empty($thwwac_products) ? array() : $thwwac_products;
            $hide_default_values = array();
            if ($hide_default == 'true' && $hide_attribute_btn != 'true') {
                $hide_default_values = $this->hide_attribute_default($thwwac_products);
            }

            $price_display = in_array('show_price', $hide_default_values) ? 'display: none' : '';
            $desc_display = in_array('show_description', $hide_default_values) ? 'display: none' : '';
            $items_html = '';
            if (!empty($thwwac_products)) {
                foreach ($thwwac_products as $pkey => $pvalue) {
                    $pdct_exists = wc_get_product($pkey);
                    $pdct_not_exists[] = !$pdct_exists ? 'empty' : 'not empty';
                }
                $pdct_deleted = (!in_array('not empty', $pdct_not_exists) && in_array('empty', $pdct_not_exists)) ? true : false;
            } else {
                $pdct_deleted = true;
            }
            $image_field_only = 0;
            if (array_key_exists('show_image', $show_fields) && $show_fields['show_image'] == 'true') {
                foreach ($show_fields as $key1 => $value1) {
                    if($key1 == 'show_image' && $value1 == 'true'){
                        $image_field_only++;
                    } elseif($key1 != 'show_image' && $value1 == 'true'){
                        $image_field_only++;
                    }
                }
            }
            $image_display = $image_field_only == 1 ? 'width: 0' : '';
            $width_class = $image_field_only == 1 ? 'thwwc_remove_width' : '';

            if (!empty($thwwac_products) && !$pdct_deleted ) {
                $items_html .= '<div class="thwwc-compare-table">';
                $items_html .= '<div class="thwwc-compare-row"><div class="thwwac_headcol thwwc_first_head_row '.$width_class.'"></div>';
                foreach ($thwwac_products as $key => $value) {
                    $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields thwwc_compare_first_row"><div class="thwwac-compare-cell"></div></div>';
                }
                $items_html .= '</div>';
                if ($show_remove == 'true') {
                    $items_html .= '<div class="thwwc-compare-row thwwc-remove-row"><div class="thwwac_headcol '.$width_class.'"></div>';
                    foreach ($thwwac_products as $key => $value) {
                        $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields thwwac_compare_mgn"><div class="thwwac-compare-cell"><div class="thwwac_remove"><i class="thwwac-bin thwwac-remove-icon" aria-hidden="true" onclick="compare_remove('. esc_js($key) .')"></i></div></div></div>';
                    }
                    $items_html .= '</div>';
                }
                foreach ($show_fields as $key1 => $value1) {
                    switch ($key1) {
                    case 'show_image':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, '', $key1, $image_display);
                        }
                        break;
                    case 'show_title':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Title', $key1, false);
                        }
                        break;
                    case 'show_price':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Price', $key1, $price_display);
                        }
                        break;
                    case 'show_description':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Description', $key1, $desc_display);
                        }
                        break;
                    case 'show_addtocart':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content_adc($thwwac_products, 'Add to cart', $key1);
                        }
                        break;
                    case 'show_sku':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'SKU', $key1, false);
                        }
                        break;
                    case 'show_available':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Availability', $key1, false);
                        }
                        break;
                    case 'show_weight':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Weight', $key1, false);
                        }
                        break;
                    case 'show_dimension':
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content($thwwac_products, 'Dimensions', $key1, false);
                        }
                        break;
                    default:
                        if ($value1 == 'true') {
                            $items_html .= $this->compare_row_content_attributes($thwwac_products, $key1);
                        }
                        break;
                    }
                }
                $items_html .= '<div class="thwwc-compare-row"><div class="thwwac_headcol '.$width_class.'thwwc_last_head_row"></div>';
                foreach ($thwwac_products as $key => $value) {
                    $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields thwwc_compare_last_row"><div class="thwwac-compare-cell"></div></div>';
                }
                $items_html .= '</div></div></div>';
            } else {
                $items_html .= $this->thwwc_empty_compare();
            }
            return $items_html;
        }

        public function hide_attribute_default(array $thwwac_products)
        {
            $pdct_count = empty($thwwac_products) ? 0 : count($thwwac_products);
            $price_count = 1;
            $desc_count = 1;
            $price = array();
            $description_arr = array();
            $hide_default = array();
            $price_html = '';
            foreach ($thwwac_products as $key => $value) {
                $product = wc_get_product($key);
                if ($product) {
                    if ($product->is_on_sale()) {
                        $price_html = $product->get_sale_price();
                    } else {
                        $price_html = $product->get_regular_price();
                    }
                    if (in_array($price_html, $price)) {
                        $price_count++;
                        if ($price_count == $pdct_count) {
                            $hide_default[] = 'show_price';
                        }
                    }
                    $description = $product->get_description();
                    if (in_array($description, $description_arr)) {
                        $desc_count++;
                        if ($desc_count == $pdct_count) {
                            $hide_default[] = 'show_description';
                        }
                    }
                    $price[] = $price_html;
                    $description_arr[] = $product->get_description();
                }
            }
            return $hide_default;
        }

        private function compare_row_content(array $thwwac_products, $heading, $show, $display) {
            $style = $show != 'show_image' && $display ? 'style="'. esc_attr($display) .'"' : '';
            $class = ($show == 'show_image') ? 'thwwac_compare_mgn' : '';
            $image_style = $show == 'show_image' && $display ? 'style="'. esc_attr($display) .'"' : '';
            $items_html = '<div class="thwwc-compare-row '.esc_attr($show).'"><div class="thwwac_headcol" '.$style.$image_style.'>'. esc_html__($heading, 'wishlist-and-compare') .'</div>';
            foreach ($thwwac_products as $key => $value) {
                $pdct_compare = wc_get_product($key);
                if ($pdct_compare) {
                    $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields '.$class.'" '.$style.'><div class="thwwac-compare-cell">';
                    if ($show == 'show_image') {
                        $permalink = (get_permalink($key)) ? get_permalink($key) : '#';
                        $items_html .= '<a href="' . esc_url($permalink) . '">'.$pdct_compare->get_image() .'</a>';
                    } elseif ($show == 'show_title') {
                        $items_html .= esc_html($pdct_compare->get_title());
                    } elseif ($show == 'show_price') {
                        $items_html .= $pdct_compare->get_price_html();
                    } elseif ($show == 'show_description') {
                        $string = strip_tags($pdct_compare->get_short_description());
                        $link = '';
                        if (strlen($string) > 80) {
                            // truncate string
                            $stringCut = substr($string, 0, 50);
                            $endPoint = strrpos($stringCut, ' ');
                            //if the string doesn't contain any space then it will cut without word basis.
                            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                            $link = '<br><a href="'. esc_url($pdct_compare->get_permalink()) .'"> '.esc_html('Read more').'</a>';
                        }
                        $items_html .= wordwrap(esc_html($string), 30, "<br>\n"). $link;
                    } elseif ($show == 'show_sku') {
                        $items_html .= esc_html($pdct_compare->get_sku());
                    } elseif ($show == 'show_available') {
                        $stock = ($pdct_compare->get_stock_quantity() ? '<p class="stock in-stock">'.$pdct_compare->get_stock_quantity().' in stock</p>' : ($pdct_compare->is_in_stock() ? '<p class="stock in-stock">'.esc_html__('In stock', 'wishlist-and-compare').'</p>' : '<p class="stock out-of-stock">'.esc_html__('Out of stock', 'wishlist-and-compare').'</p>'));
                        if ($pdct_compare->get_stock_status() == 'onbackorder') {
                            $stock = esc_html__('Available on backorder', 'wishlist-and-compare');
                        }
                        $items_html .= $stock;
                    } elseif ($show == 'show_weight') {
                        $items_html .= $pdct_compare->get_weight() ? $pdct_compare->get_weight().' '. get_option('woocommerce_weight_unit') : 'N/A';
                    } elseif($show == 'show_dimension') {
                        $items_html .= function_exists('wc_format_dimensions') ? wc_format_dimensions($pdct_compare->get_dimensions(false)) : $product->get_dimensions();
                    }

                    $items_html .= '</div></div>';
                }
            }
            $items_html .= '</div>';
            return $items_html;
        }

        private function compare_row_content_adc(array $thwwac_products, $heading, $show) {
            $select_wepo_text = THWWC_Utils::is_wepo_active();
            $select_option_text = $select_wepo_text['simple'] ? $select_wepo_text['simple'] : 'Add to cart';
            $select_option_text_variable = $select_wepo_text['variable'] ? $select_wepo_text['variable'] : 'Select options';
            $items_html = '<div class="thwwc-compare-row '.esc_attr($show).'"><div class="thwwac_headcol">'. esc_html__($heading, 'wishlist-and-compare') .'</div>';
            foreach ($thwwac_products as $key => $value) {
                $pdct_compare = wc_get_product($key);
                if ($pdct_compare) {
                    $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields"><div class="thwwac-compare-cell">';
                    $redirect_url = $pdct_compare->add_to_cart_url();
                    $add_to_cart_url = '?add-to-cart='.$key;
                    $current_page = (is_shop() || is_product()) ? false : true;
                    $disabled = ($pdct_compare->is_in_stock()) ? '' : 'disabled';
                    $stock_status = $pdct_compare->is_in_stock();
                    if ($pdct_compare->is_type('grouped')) {
                        $items_html .= '<a class="addcart-btn" href="'. esc_url($redirect_url) .'">'. esc_html('View Products') .'</a>';
                    } elseif ($disabled == 'disabled' || $redirect_url != $add_to_cart_url) {
                        $items_html .= '<a id="compare-addcart-btn'.esc_attr($key).'" href="'. esc_url($redirect_url) .'" class="addcart-btn">';
                        if($pdct_compare->is_type('variable') && isset($select_option_text_variable)) {
                            $items_html .= esc_html($select_option_text_variable);
                        } else {
                            $items_html .= ($pdct_compare->get_price_html() == null || $stock_status == false) ? esc_html('Read more') : (isset($select_option_text) ? esc_html($select_option_text) : esc_html('Read more'));
                        }
                        $items_html .= '</a>';
                    } else {
                        $items_html .= '<a id="compare-addcart-btn'. esc_attr($key) .'" onclick="add_to_cart('. esc_js($key) .','. esc_js($current_page) .')" class="addcart-btn">'.esc_html__('Add to cart', 'wishlist-and-compare').'';
                        $items_html .= '</a><a id="thwwc-view-cart'. esc_attr($key) .'" class="thwwc-view-cart" href="'.get_permalink(get_option('woocommerce_cart_page_id')).'">View cart</a>';
                    }
                    $items_html .= '</div></div>';
                }
            }
            $items_html .= '</div>';
            return $items_html;
        }

        private function compare_row_content_attributes(array $thwwac_products, $key)
        {
            $heading = THWWC_Utils::thwwc_get_attribute_label_from_name($key);
            $items_html = '<div class="thwwc-compare-row"><div class="thwwac_headcol">'. esc_html__($heading, 'wishlist-and-compare') .'</div>';
            foreach ($thwwac_products as $key => $value) {
                $pdct_compare = wc_get_product($key);
                if ($pdct_compare) {
                    $items_html .= '<div class="thwwc_col_'. esc_attr($key) .' thwwac_compare_fields"><div class="thwwac-compare-cell">';
                    $item_empty = true;
                    if ($pdct_compare->get_attributes()) {
                        foreach ($pdct_compare->get_attributes() as $taxonomy => $term_names) {
                            $options = $term_names->get_options();
                            $all_terms = '';
                            $term_name_simple = array();
                            foreach ($options as $option){
                                $term_obj = get_term_by('id', $option, $taxonomy);
                                $term_name_simple[] = is_object($term_obj) ? $term_obj->name : '';
                            }
                            $all_terms = implode(', ', $term_name_simple);
                            $attribute_label_name = wc_attribute_label($taxonomy);
                            if ($attribute_label_name == $heading) {
                                $items_html .= '<span>'.$all_terms.'</span>';
                                $item_empty = false;
                                break;
                            } else {
                                $item_empty = true;
                            }
                        }
                    }
                    if ($item_empty) {
                        $items_html .= '<span>N/A</span>';
                    }
                    $items_html .= '</div></div>';
                }
            }
            $items_html .= '</div>';
            return $items_html;
        }

        /**
         * Function to add product to cart in compare page and popup(ajax-response).
         *
         * @return json
         */
        public function compare_add_to_cart()
        {
            if (!isset($_POST['cmpcartnonce']) || !wp_verify_nonce($_POST['cmpcartnonce'], 'thwwac_cmpcart_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $current_page = isset($_POST['current_page']) ? absint($_POST['current_page']) : false;
            $product = wc_get_product($product_id);
            $return['product_name'] = $product->get_title();
            $return['carturl'] = get_permalink(get_option('woocommerce_cart_page_id'));
            $stock = $product->get_stock_quantity();
            $return['cart_quantity'] = 0;
            foreach (WC()->cart->get_cart() as $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    $return['cart_quantity'] =  $cart_item['quantity'];
                }
            }
            if ($stock && $stock == $return['cart_quantity']) {
                $return['stock_status'] = 'out_of_stock';
            } else {
                $return['stock_status'] = 'in_stock';
                WC()->cart->add_to_cart($product_id);
                $return['cart_contents'] = $this->mini_cart();
                $return['cart_count'] = WC()->cart->cart_contents_count;
                $return['cart_total'] = WC()->cart->get_cart_total();
            }
            if ($current_page == true) {
                wc_add_to_cart_message($product_id, true);
            }
            wp_send_json($return);
        }

        /**
         * Function for update minicart on product add to cart.
         *
         * @return html
         */
        public function mini_cart()
        {
            ob_start();
            woocommerce_mini_cart();
            $output = ob_get_clean();
            return $output;
        }

        /**
         * Function to remove product from compare(ajax-response).
         *
         * @return json
         */
        public function remove_compare()
        {
            if (!isset($_POST['remcmpnonce']) || !wp_verify_nonce($_POST['remcmpnonce'], 'thwwac_remcmp_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $return = array();
            if ($product_id > 0) {
                $thwwac_products = array();
                $thwwac_products = THWWC_Public_Settings::get_compare_products();
                if (array_key_exists($product_id, $thwwac_products)) {
                    unset($thwwac_products[ $product_id ]);
                    THWWC_Public_Settings::update_compare_products($thwwac_products);
                }
                $return = $this->difference_fields();
                $return['count'] = count($thwwac_products);
                $return['empty_compare'] = $this->thwwc_empty_compare();
            }
            wp_send_json($return);
        }

        private function thwwc_empty_compare(){
            $image_url = THWWC_URL.'assets/libs/icons/empty-compare-01.svg';
            $items_html = '<div class="th-empty-msg"><img src="'.esc_url($image_url).'"><p>"'.__('No products added to the comparison table.','wishlist-and-compare').'"</p><a class="button thwwc-cart-url" href="'. wc_get_page_permalink('shop').'">'. esc_html__('Add products', 'wishlist-and-compare') .'</a></div>';
            return $items_html;
        }

        /**
         * Function to hide or show fields(ajax-response).
         *
         * @return json
         */
        public function hide_show()
        {
            if (!isset($_POST['cmphsnonce']) || !wp_verify_nonce($_POST['cmphsnonce'], 'thwwac_cmphs_security')) {
                die('Sorry, your nonce did not verify');
            }
            $return = $this->difference_fields();
            wp_send_json($return);
        }

        /**
         * Function to check same field contents for hide and show.
         *
         * @return array
         */
        public function difference_fields()
        {
            $compare_products = THWWC_Public_Settings::get_compare_products();
            $return = array();
            $return['fields'] = array();
            if (empty($compare_products)) {
                $return['count'] = 0;
            } else {
                $return['count'] = count($compare_products);
            }
            $table_options = THWWC_Utils::thwwc_get_compare_table_settings();
            if ($table_options) {
                $hide_attribute_btn = isset($table_options['hide_show']) ? $table_options['hide_show'] : '';
                $hide_default = isset($table_options['hide_attribute']) ? $table_options['hide_attribute'] : '';
            } else {
                $hide_attribute_btn = false;
                $hide_default = false;
            }
            if ($hide_default == 'true' && $hide_attribute_btn != 'true') {
                $return['hide_by_default'] = true;
            } else {
                $return['hide_by_default'] = false;
            }
            $return['fields'] = $this->hide_attribute_default($compare_products);
            return $return;
        }

        /**
         * Function to render shortcode
         *
         * @param content $content is page content
         *
         * @return string
         */
        public function shortcode_content_filter_compare($content)
        {
            global $post;
            if ($post) {
                $page_id = $post->ID;
                $compare = THWWC_Utils::thwwc_get_compare_table_settings();
                $compare_page = isset($compare['compare_page']) ? $compare['compare_page'] : false;
                if ($compare_page && $page_id && $page_id == $compare_page) {
                    $content = str_replace('[thwwac_compare_list]', ' ', $content);
                    if (apply_filters('thwwc_compare_shortcode_position_first', false)) {
                        return '[thwwac_compare_list]'. $content;
                    } else {
                        return $content .'[thwwac_compare_list]';
                    }
                }
            }
            return $content;
        }

        /**
         * Function to get all compare products list for compare widget.
         *
         * @return html
         */
        // public function product_list()
        // {
        //     $thwwac_key = THWWC_Utils::get_key();
        //     $compare_products = get_option('thwwac_compare_' . $thwwac_key);
        //     if (empty($compare_products)) {
        //         $return = '<p>'.esc_html__('No products in compare table', 'wishlist-and-compare').'</p>';
        //     } else {
        //         $return = '<div id="compare_widget">';
        //         foreach ($compare_products as $key => $value) {
        //             $product = wc_get_product($key);
        //             if ($product) {
        //                 $return .= '<div class="compare_widget_row" id="thc_row'. esc_attr($key) .'"><p>'.$product->get_title().'</p><div class="thwwac_remove"><span onclick="compare_remove('.esc_js($key).')"></span></div></div>';
        //             }
        //         }
        //         $return .= '<button onclick="openmodal(false)" class="button">'.esc_html('Compare').'</button></div>';
        //     }
        //     $this->controller = new BaseController();
        //     $GLOBALS['thwwac_key'] = THWWC_Utils::get_key();
        //     include THWWC_PATH . "/templates/thwwac-comparepopup.php";
        //     echo $return;
        // }
        /**
         * Function to get all products list for wishlist when browser back button.(ajax-response)
         *
         * @return html
         */
        public function update_compare_on_back()
        {
            if (!isset($_POST['upcmpnonce']) || !wp_verify_nonce($_POST['upcmpnonce'], 'thwwac_upcmp_security')) {
                die('Sorry, your nonce did not verify');
            }
            $thwwac_products = array();
            $result = array();
            $result['items_html'] = '<div id="added-msg" class="thwwac-compare-msg"></div>';
            $thwwac_products = THWWC_Public_Settings::get_compare_products();
            $result['all_products'] = array();
            if(!empty($thwwac_products)) {
                foreach ($thwwac_products as $key => $value) {
                    $result['all_products'][] = $key;
                }
            }
            $result['items_html'] .= $this->compare_contents($thwwac_products);
            wp_send_json($result);
        }
    }
endif;