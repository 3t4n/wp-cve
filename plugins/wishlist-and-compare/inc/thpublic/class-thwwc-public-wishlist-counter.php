<?php
/**
 * The public wishlist settings functionality of the plugin.
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

if (!class_exists('THWWC_Public_Wishlist_Counter')) :
    /**
     * Public wishlist counter settings class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Wishlist_Counter extends THWWC_Base_Controller
    {
        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            $this->controller = new THWWC_Base_Controller();
            add_shortcode('thwwac_wishlist_count', array($this, 'count_shortcode'));

            $countersetting = THWWC_Utils::thwwc_get_counter_settings();
            $menu = isset($countersetting['add_countr_to_menu']) ? $countersetting['add_countr_to_menu'] : '';
            if ($menu != "") {
                add_filter('wp_nav_menu_items', array($this, 'custom_wishlist_menu_item'), 10, 2);
            }
        }

        /**
         * Function to get the no.of products in wishlist for shortcode.
         *
         * @return html
         */
        public function count_shortcode()
        {
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            $countersetting = THWWC_Utils::thwwc_get_counter_settings();
            if ($countersetting) {
                $counter = $this->thwwc_wishlist_counter($countersetting);
                return wp_kses_post($counter);
            }
        }

        /**
         * Function to add wishlist count to menu
         *
         * @param string $items is menu items
         * @param array  $args  is menu arguments
         *
         * @return url
         */
        public function custom_wishlist_menu_item($items, $args)
        {
            $countersetting = THWWC_Utils::thwwc_get_counter_settings();
            if ($countersetting) {
                $menuid = isset($countersetting['add_countr_to_menu']) ? $countersetting['add_countr_to_menu'] : '';
                $counterposition = isset($countersetting['counter_position']) ? $countersetting['counter_position']  : 10;
                $counter = '<li class="menu-item thwwc-menu-item">';
                $counter .= $this->thwwc_wishlist_counter($countersetting);
                $counter .= '</li>';
                
                $theme_locations = get_nav_menu_locations();
                foreach ($theme_locations as $key => $theme_location) {
                    if ($theme_location == $menuid) {
                        $menu = $key;
                        break;
                    } else {
                        $menu = '';
                    }
                }

                $items_array = array();
                if (!empty($items)) {
                    while (false !== ($item_pos = strpos($items, '<li', 10))) { // Add the position where the menu item is placed
                            $items_array[] = substr($items, 0, $item_pos);
                            $items = substr($items, $item_pos);
                    }
                    $items_array[] = $items;
                    if ($args->theme_location == $menu) {
                        array_splice($items_array, $counterposition-1, 0, $counter); // insert custom item after 9th item one
                    }
                    $items = implode('', $items_array);
                } else {
                    $items_array[] = '';
                    if ($args->theme_location == $menu) {
                        array_splice($items_array, $counterposition-1, 0, $counter);
                    }
                    $items = implode('', $items_array);
                }
                return wp_kses_post($items);
            }
        }
        private function thwwc_wishlist_counter($countersetting)
        {
            $thwwac_products = THWWC_Public_Settings::get_wishlist_products();
            $showcounter = isset($countersetting['num_pdcts_counter']) ? $countersetting['num_pdcts_counter'] : '';
            $hidezero = isset($countersetting['hide_zero_value']) ? $countersetting['hide_zero_value'] : '';
            if (empty($thwwac_products)) {
                $count = 0;
            } else {
                $count = count($thwwac_products);
            }
            if (empty($thwwac_products) && $hidezero != 'true') {
                $count = 0;
            } elseif ($count == 0 && $hidezero == 'true') {
                $count = '';
            } else {
                $count = count($thwwac_products);
            }
            $countericon = isset($countersetting['counter_icon']) ? $countersetting['counter_icon'] : '';
            $color = isset($countersetting['counter_icon_color']) ? $countersetting['counter_icon_color'] : '';
            $show_counter_text = isset($countersetting['show_counter_text']) ? $countersetting['show_counter_text'] : 'false';
            if ($show_counter_text == 'true') {
                $countertext = isset($countersetting['whlst_counter_text']) ? stripcslashes($countersetting['whlst_counter_text']) : '';
            } else {
                $countertext = '';
            }
            
            $class = $color == 'white' ? 'iconwhite' : ($color == 'black' ? 'iconblack' : '');

            $options = THWWC_Utils::thwwc_get_general_settings();
            $page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : '';
            $permalink = $page_id == '' ? '#' : get_permalink($page_id);

            $counter = '<a href="'.esc_url($permalink).'" class="thwwac-counter-btn">';
            if ($countericon == "heart") {
                $counter .= "<i class='thwwac-icon thwwac-counter-icon thwwac-heart ".esc_attr($class)."'></i>";
            } elseif ($countericon == "bookmark") {
                $counter .= "<i class='thwwac-icon thwwac-counter-icon thwwac-bookmark ".esc_attr($class)."'></i>";
            } elseif ($countericon == "custom") {
                $counter .= "<img src='".esc_url($countersetting['iconc_upload'])."' class='thwwac-counter-icon thwwac-iconimg'>";
            }
            if ($show_counter_text == 'true') {
                $counter .= "<span class='counter-text'>".esc_html($countertext)."</span>";
            }
            if ($showcounter == 'true') {
                if ($count == 0 && $hidezero == 'true') {

                } else {
                    $counter .= "<span id='thwwac_count' class='button'> ".esc_html($count)."</span>";
                }
            }
            $counter .= "</a>";
            return $counter;
        }
    }
endif;