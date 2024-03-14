<?php
/**
 * The public wishlist functionality of the plugin.
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

if (!class_exists('THWWC_Public_Wishlist')) :
    /**
     * Public wishlist class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Wishlist extends THWWC_Base_Controller
    {
        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            $this->controller = new THWWC_Base_Controller();
            $theme = wp_get_theme();

            $shopoptions = THWWC_Utils::thwwc_get_shop_page_settings();
            $position = isset($shopoptions['wishlist_position']) ? $shopoptions['wishlist_position'] : 'after';
            $thumbnail_position = isset($shopoptions['thumb_position']) ? $shopoptions['thumb_position'] : 'top_left';
            $hook_name_thumb_bottom = $theme->name == 'OceanWP' ? 'ocean_after_archive_product_image' : 'woocommerce_before_shop_loop_item_title';
            $thwwc_hook_thumbnail_bottom = apply_filters('thwwc_hook_name_thumbnail_bottom', $hook_name_thumb_bottom);
            $hook_name_thumb_top = $theme->name == 'OceanWP' ? 'ocean_before_archive_product_image' : 'woocommerce_before_shop_loop_item';
            $thwwc_hook_thumbnail_top = apply_filters('thwwc_hook_name_thumbnail_top', $hook_name_thumb_top);

            if ($position == 'after') {
                add_action('woocommerce_after_shop_loop_item', array($this, 'add_button'), 12);
            } elseif ($position == 'before') {
                if ($theme->name == 'Astra' || $theme->name == 'OceanWP') {
                    add_filter('woocommerce_loop_add_to_cart_link', array($this,'th_replace_add_to_cart_button'), 10, 2);
                } elseif ($theme->name == 'Avada') {
                    add_action('woocommerce_after_shop_loop_item', array($this,'add_button'), 10);
                } else {
                    add_action('woocommerce_after_shop_loop_item', array($this, 'add_button'), 8);
                }
            } elseif ($position == 'above_thumb' && $thumbnail_position != 'bottom_right') {
                add_action($thwwc_hook_thumbnail_top, array($this, 'add_button'), 8);
            } elseif ($position == 'above_thumb') {
                add_action($thwwc_hook_thumbnail_bottom, array($this, 'add_button'), 11);
            } else {
                add_shortcode('thwwac_addtowishlist_loop', array($this, 'add_button'));
            }
            
            add_action('wp_ajax_add_wishlist', array($this, 'add_wishlist'));
            add_action('wp_ajax_nopriv_add_wishlist', array($this, 'add_wishlist'));

            add_action('wp_ajax_remove_wishlist', array($this, 'remove_wishlist'));
            add_action('wp_ajax_nopriv_remove_wishlist', array($this, 'remove_wishlist'));

            add_action('wp_ajax_selected_variation_action', array($this, 'selected_variation_action'));
            add_action('wp_ajax_nopriv_selected_variation_action', array($this, 'selected_variation_action'));

            add_action('wp_ajax_update_on_back_press', array($this, 'update_on_backpress'));
            add_action('wp_ajax_nopriv_update_on_back_press', array($this, 'update_on_backpress'));

            $options = THWWC_Utils::thwwc_get_general_settings();
            $wishlist_myaccount = isset($options['wishlnk_myaccont']) ? $options['wishlnk_myaccont'] : '';
            if ($wishlist_myaccount == 'true') {
                add_filter('woocommerce_account_menu_items', array($this,'thwwac_one_more_link'));
                add_filter('woocommerce_get_endpoint_url', array($this,'thwwac_hook_endpoint'), 10, 4);
            }
            add_filter('the_content', array($this, 'shortcode_content_filter'), 10);
        }

        /**
         * Function to update add to cart button for astra theme.
         *
         * @param string $button  is button anchor
         * @param object $product is product details
         *
         * @return html button
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
         * Function to update add to cart button for avada theme.
         *
         * @param string $button  is button anchor
         * @param object $product is product details
         *
         * @return html button
         */
        public function th_replace_add_to_cart_button_avada($button, $product)
        {
            if (is_shop()) {
                $button_link = $product->get_permalink();
                $button = $this->add_button().'<div class="thwwac-theme-elements">'.$button.'</div>';
                return $button;
            }
        }

        /**
         * Function to show add to wishlist button.
         *
         * @return void
         */ 
        public function add_button()
        {
            global $product;
            if ($product) {
                $product_id = $product->get_id();
                $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
                $shopoptions = THWWC_Utils::thwwc_get_shop_page_settings();
                $show_loop = isset($shopoptions['show_loop']) ? $shopoptions['show_loop'] : 'true';
                if ($show_loop == 'true') {
                    include THWWC_PATH . "/templates/thwwac-addtowishlist.php";
                }
            }
        }

        /**
         * Function to add product to wishlist(ajax-response).
         *
         * @return json
         */ 
        public function add_wishlist()
        {
            if (!isset($_POST['addwishnonce']) || !wp_verify_nonce($_POST['addwishnonce'], 'thwwac_addwish_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $product = wc_get_product($product_id);
            $logged_in = isset($_POST['logged_in']) ? sanitize_text_field($_POST['logged_in']) : '';
            $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : '';
            $result = array();
            if ($product_id > 0) {
                $thwwac_products = array();
                if ($logged_in != 'yes') {
                    $link = get_permalink(get_option('woocommerce_myaccount_page_id')) != '' ? get_permalink(get_option('woocommerce_myaccount_page_id')) : '#';
                    $current_url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : '';
                    $nonce = wp_create_nonce('redirect_nonce');
                    $result['redirect_url'] = add_query_arg(array('url'=>$current_url, 'id'=>$product_id, '_wpnonce'=>$nonce), $link);
                } else {
                    $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
                    if ($product->is_type('variable') && $variation_id) {
                        // $thwwac_products = array($variation_id => time()) + $thwwac_products;
                        $product_order = count($thwwac_products);
                        $thwwac_products = array($variation_id => array('time' => time(),'order'=> $product_order)) + $thwwac_products;
                        THWWC_Public_Settings::update_wishlist_products($thwwac_products);
                    }
                    elseif (!array_key_exists($product_id, $thwwac_products)) {
                        $product_order = count($thwwac_products);
                        $thwwac_products = array($product_id => array('time' => time(),'order'=> $product_order)) + $thwwac_products;
                        THWWC_Public_Settings::update_wishlist_products($thwwac_products);
                    }
                    $options = THWWC_Utils::thwwc_get_general_settings();
                    $addedtext = isset($options['wishlst_added_text']) ? $options['wishlst_added_text'] : '';
                    $result['product_name'] = str_replace('{product_name}', '"'.$product->get_name().'"', $addedtext);
                    $countersetting = THWWC_Utils::thwwc_get_counter_settings();
                    if ($countersetting) {
                        $result['show_count'] = isset($countersetting['num_pdcts_counter']) ? $countersetting['num_pdcts_counter'] : 'false';
                    } else {
                        $result['show_count'] = 'false';
                    }
                    $result['count'] = count($thwwac_products);
                    $result['count_with_element'] = '<span id="thwwac_count" class="button">'.count($thwwac_products).'</span>';
                }
            }
            wp_send_json($result);
        }

        /**
         * Function to remove product from wishlist(ajax-response).
         *
         * @return json
         */ 
        public function remove_wishlist()
        {
            if (!isset($_POST['removewishnonce']) || !wp_verify_nonce($_POST['removewishnonce'], 'thwwac_removewish_security')) {
                die('Sorry, your nonce did not verify');
            }
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : '';
            $wishlist_page = isset($_POST['wishlist_page']) ? rest_sanitize_boolean($_POST['wishlist_page']) : false;
            $product = wc_get_product($product_id);
            $product_name = $product->get_title();
            $result = array();
            if ($product_id > 0) {
                $thwwac_products = array();
                $general_settings = THWWC_Utils::thwwc_get_general_settings();
                $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
                if (array_key_exists($product_id, $thwwac_products)) {
                    unset($thwwac_products[ $product_id ]);
                    THWWC_Public_Settings::update_wishlist_products($thwwac_products);
                    if ($wishlist_page) {
                        wc_add_notice(sprintf(__('%s removed from wishlist'), esc_html($product_name)));   
                    }
                }

                THWWC_Utils::check_product_exists($thwwac_products);

                if (!$wishlist_page) {
                    $result = $this->count_update_on_remove();
                    $result['count'] = count($thwwac_products);
                }
            }
            wp_send_json($result);
        }

        private function count_update_on_remove()
        {
            $countersetting = THWWC_Utils::thwwc_get_counter_settings();
            $result['hide_zero'] = isset($countersetting['hide_zero_value']) ? $countersetting['hide_zero_value'] : 'false';
            $result['show_count'] = isset($countersetting['num_pdcts_counter']) ? $countersetting['num_pdcts_counter'] : 'false';
            return $result;
        }

        public function selected_variation_action()
        {
            if (!isset($_POST['variationnonce']) || !wp_verify_nonce($_POST['variationnonce'], 'thwwac_variation_security')) {
                die('Sorry, your nonce did not verify');
            }
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            $variation_id = isset($_POST['variation']) ? absint($_POST['variation']) : '';
            if (array_key_exists($variation_id, $thwwac_products)) {
                $result['show'] = 'browse_btn';
                $result['hide'] = 'add_btn';
            } else {
                $result['show'] = 'add_btn';
                $result['hide'] = 'browse_btn';
            }
            wp_send_json($result);

        }

        /**
         * Function to add wishlist link in myaccount page
         *
         * @param array $menu_links is all menu in my account
         *
         * @return array
         */    
        public function thwwac_one_more_link($menu_links)
        {
            $new = array('wishlist' => 'Wishlist');
            $menu_links = array_slice($menu_links, 0, 1, true)
            + $new
            + array_slice($menu_links, 1, null, true);
            return $menu_links;
        }

        /**
         * Function to add wishlist url to menu link in myaccount page
         *
         * @param url    $url       url
         * @param string $endpoint  endpoint
         * @param string $value     value
         * @param string $permalink permalink
         *
         * @return array
         */  
        public function thwwac_hook_endpoint($url, $endpoint, $value, $permalink)
        {
            if ($endpoint === 'wishlist') {
                $options = THWWC_Utils::thwwc_get_general_settings();
                $page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : '';
                $url = get_permalink($page_id);
            }
            return $url;
        }

        /**
         * Function to render shortcode
         *
         * @param content $content is page content
         *
         * @return string
         */
        public function shortcode_content_filter($content)
        {
            global $post;
            if ($post) {
                $page_id = $post->ID;
                $options = THWWC_Utils::thwwc_get_general_settings();
                $wishlist_page = isset($options['wishlist_page']) ? $options['wishlist_page'] : false;
                if($wishlist_page && $page_id && $page_id == $wishlist_page){
                    $content = str_replace('[thwwac_wishlist]', ' ', $content);
                    if (apply_filters('thwwc_wishlist_shortcode_position_first', false)) {
                        return '[thwwac_wishlist]'. $content;
                    } else {
                        return $content . '[thwwac_wishlist]';
                    }
                }
            }
            return $content;
        }

        /**
         * Function to get all wishlist products list for wishlist widget.
         *
         * @return html
         */
        // public function product_list()
        // {
        //     $thwwac_key = sanitize_key(THWWC_Utils::get_key();
        //     $thwwac_products = get_option('thwwac_wishlist_' . $thwwac_key);
        //     if (empty($thwwac_products)) {
        //         $return = '<p>'.esc_html__('No products in the wishlist', 'wishlist-and-compare').'</p>';
        //     } else {
        //         $return = '<div id="wishlist-widget">';
        //         foreach ($thwwac_products as $key => $value) {
        //             $product = wc_get_product($key);
        //             if ($product) {
        //                 $return .= '<div class="wishlist_widget_row" id="thw_row'. esc_attr($key) .'"><p>'.$product->get_title().'</p><div class="remove"><span onclick="wishlist_remove('.esc_js($key).')"></span></div></div>';
        //             }
        //         }
        //     }
        //     echo $return;
        // }

        /**
         * Function to get all products list for wishlist when browser back button.(ajax-response)
         *
         * @return html
         */
        public function update_on_backpress()
        {
            if (!isset($_POST['backclicknonce']) || !wp_verify_nonce($_POST['backclicknonce'], 'thwwac_backclick_security')) {
                die('Sorry, your nonce did not verify');
            }
            $result = array();
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            if(!empty($thwwac_products)) {
                foreach ($thwwac_products as $key => $value) {
                    $result['all_products'][] = $key;
                }
                $count = count($thwwac_products);
            } else {
                $result['all_products'] = array();
                $count = 0;
            }
            $countersetting = THWWC_Utils::thwwc_get_counter_settings();
            $result['show_count'] = isset($countersetting['num_pdcts_counter']) ? $countersetting['num_pdcts_counter'] : 'false';
            $hidezero = isset($countersetting['hide_zero_value']) ? $countersetting['hide_zero_value'] : 'false';
            $count = empty($thwwac_products) && $hidezero != 'true' ? $count = 0 : (($count == 0 && $hidezero == 'true') ? '' : count($thwwac_products));
            $result['count'] = $count;
            wp_send_json($result);
        }

        public static function twoway_encrypt($stringToHandle = "",$encryptDecrypt = 'e'){
            $output = null;
            $secret_key = 'Themehigh';
            $secret_iv = '1234567891011121';
            $key = hash('sha256',$secret_key);
            $iv = substr(hash('sha256',$secret_iv),0,16);
            // Check whether encryption or decryption
            if ($encryptDecrypt == 'e') {
                $output = openssl_encrypt($stringToHandle,"AES-128-CTR",$key,0,$iv);
            } else if ($encryptDecrypt == 'd') {
                $output = openssl_decrypt($stringToHandle,"AES-128-CTR",$key,0,$iv);
            }
            return $output;
        }
        public static function encrypt_user_id($user_id)
        {
            $encryption = openssl_encrypt($user_id, "AES-128-CTR", "Themehigh", 0, "1234567891011121");
            return sanitize_key($encryption);
        }

        public static function decrypt_user_id($encryption)
        {
            $decryption = openssl_decrypt($encryption, "AES-128-CTR", "Themehigh", 0, "1234567891011121");
            return sanitize_key($decryption);
        }
    }
endif;