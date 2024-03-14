<?php
/**
 * The compare api data for vue in js functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/admin
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\admin;

use \THWWC\base\THWWC_Utils;

if (!class_exists('THWWC_Vue_Api_Compare')) :
    /**
     * Vue api for compare class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Vue_Api_Compare
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
                        'thwwac/v1', 'compare', [
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

            $table_settings_data = $this->table_settings_data();

            $drag_fields = $this->attribute_drag_fields();

            $data = [['name' => __('General Settings', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/general setings-01.svg','content' => __('Set the general settings for the comparison list here, including the comparison page.', 'wishlist-and-compare'),'link' => __('VIEW GENERAL SETTINGS', 'wishlist-and-compare'),'active' => false,
                'settings' => [['name' => __('General Settings', 'wishlist-and-compare'), 'click_open' => 'General Settings',
                    'fields'=> $general_settings_data]],
            ],['name' => __('Table Settings', 'wishlist-and-compare'),'icon' => THWWC_URL.'assets/libs/icons/table settings-01.svg','content' => __('Set Table settings to the comparison list.', 'wishlist-and-compare'),'link' => __('VIEW TABLE SETTINGS', 'wishlist-and-compare'),'active' => false,
                'settings' => [['name' => __('Table Settings', 'wishlist-and-compare'), 'click_open' => 'Table Settings',
                    'fields'=> $table_settings_data,
                'drag_fields' => $drag_fields
                ]],
            ],];
            return $data;
        }

        private function general_settings_data()
        {
            $compare_general = THWWC_Utils::thwwc_get_compare_settings();
            if ($compare_general) {
                $array_keys = array_keys($compare_general);
                $array_values = array_values($compare_general);
                $length = count($array_keys);
                for ($i=0; $i<$length; $i++) {
                    $returnarray[] = array('key'=>$array_keys[$i],
                        'value'=>$array_values[$i]);
                }
                foreach ($returnarray as $return) {
                    $key = $return['key'];
                    $$key = $return['value'] == 'true' ? true : ($return['value'] == 'false' ? false : stripcslashes($return['value']));
                }

                if (isset($icon_upload)) {
                    $path = parse_url($icon_upload, PHP_URL_PATH); 
                    $shopicon_name = basename($path);
                }
            }
            $show_in_shop = isset($show_in_shop) ? $show_in_shop : false;
            $compare_type = isset($compare_type) ? $compare_type : 'button';
            $compare_text = isset($compare_text) && $compare_text != '' ? $compare_text : 'Compare';
            $added_text = isset($added_text) && $added_text != '' ? $added_text : 'Compare Now';
            $shoppage_position = isset($shoppage_position) ? $shoppage_position : 'after';
            $positiondepend = isset($shoppage_position) && $shoppage_position == 'above_thumb' ? true : false;
            $thumb_position = isset($thumb_position) ? $thumb_position : 'bottom_right';
            $cmp_icon_color = isset($cmp_icon_color) ? $cmp_icon_color : '';
            $cmp_icon = isset($cmp_icon) ? $cmp_icon : 'compare';
            $icon_upload = isset($icon_upload) ? $icon_upload : '';
            $shopicon_name = isset($shopicon_name) ? $shopicon_name: '';
            $shop_preview = isset($icon_upload) ? ($icon_upload == null ? false : true) : true;
            $show_in_product = isset($show_in_product) ? $show_in_product : false;
            $productpage_position = isset($productpage_position) ? $productpage_position : 'after';
            $cmp_icon_pdct_page = isset($cmp_icon_pdct_page) ? $cmp_icon_pdct_page : 'compare';
            $iconp_upload = isset($iconp_upload) ? $iconp_upload : '';
            $pdct_preview = isset($iconp_upload) ? ($iconp_upload == null ? false : true) : true;
            $pdcticon_name = isset($pdcticon_name)? $pdcticon_name : '';
            $open_popup = isset($open_popup) ? $open_popup : false;
            $button_action = isset($button_action) ? $button_action : 'popup';
            $cmp_icon_color_pdctpage = isset($cmp_icon_color_pdctpage) ? $cmp_icon_color_pdctpage : '';

            $shopdependant = ($show_in_shop == true) ? true : false;
            $icondepend = $shopdependant == true && $cmp_icon == 'custom' ? true : false;
            $colordepend = $cmp_icon != 'custom' ? true : false;
            $shoppage_position = isset($shoppage_position) ? $shoppage_position: 'after';
            $productdependant = ($show_in_product == true) ? true : false;
            $pcolordepend = $productdependant == true && $cmp_icon_pdct_page != 'custom' ? true : false;
            $productpage_position = isset($productpage_position) ? $productpage_position: 'after';
            $prodpositiondepend = isset($productpage_position) && $productpage_position == 'above_thumb' ? true : false;
            $product_thumb_position = isset($product_thumb_position) ? $product_thumb_position : 'bottom_left';
            $iconpdctdepend = $productdependant == true && $cmp_icon_pdct_page == 'custom' ? true : false;
            $settings_data = [
                    ['label' => __('Show compare button on the shop page', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_in_shop','dependant' => true,'field_value' => esc_attr($show_in_shop),'children' => true],
                    ['label' => __('Position on shop page', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'after','name' => __('After "Add to Cart" Button', 'wishlist-and-compare')],['value' => 'before','name' => __('Before "Add to cart" Button', 'wishlist-and-compare')],['value' => 'above_thumb','name' => __('Above Thumbnail', 'wishlist-and-compare')]],'field_name' => 'shoppage_position','dependant' => $shopdependant,'field_value' => esc_attr($shoppage_position),'parent' => 'show_in_shop'],
                    ['label' => __('Thumbnail position', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'thumb_position','dependant' => $positiondepend,'options' => [['value' => 'bottom_right','name' => __('Bottom Right', 'wishlist-and-compare')]],'field_value' => esc_attr($thumb_position),'parent' => 'shoppage_position','main_parent' => 'show_in_shop'],
                    ['label' => __('Link / Button', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'compare_type','dependant' => true,'options' => [['value' => 'button','name' => __('Button', 'wishlist-and-compare')],['value' => 'link','name' => __('Link', 'wishlist-and-compare')]],'field_value' => esc_attr($compare_type)],
                    ['label' => __('Link / Button text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'compare_text','dependant' => true,'field_value' => $compare_text],
                    ['label' => __('Added text', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'added_text','dependant' => true,'field_value' => $added_text],
                    ['label' => __('Choose compare icon', 'wishlist-and-compare'),'type' => 'select_change','field_name' => 'cmp_icon','dependant' => true,'options' => [['value' => 'compare','name' => __('Compare', 'wishlist-and-compare')],['value' => 'custom','name' => __('Custom', 'wishlist-and-compare')]],'field_value' => esc_attr($cmp_icon),'parent' => 'show_in_shop', 'subchild' => true,'children' => true, 'icon_field' => true],
                    ['label' => __('Upload compare icon', 'wishlist-and-compare'),'type' => 'file_compare','parent' => 'cmp_icon','dependant' => $icondepend,'field_name' => 'icon_upload','field_id' => 'icon_shop','span_id' => 'shop_filename','field_value' => esc_attr($icon_upload),'preview_id' => 'shop_preview','preview' => $shop_preview,'image_name' => $shopicon_name,'main_parent' => 'show_in_shop'],
                    ['label' => __('Choose compare icon color', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'cmp_icon_color','dependant' => $colordepend,'options' => [['value' => '','name' => __('Default', 'wishlist-and-compare')],['value' => 'black','name' => __('Black', 'wishlist-and-compare')],['value' => 'white','name' => __('White', 'wishlist-and-compare')]],'field_value' => esc_attr($cmp_icon_color),'parent' => 'cmp_icon','main_parent' => 'show_in_shop'],
                    ['label' => __('Show button in single product page', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_in_product','dependant' => true,'field_value' => esc_attr($show_in_product),'children' => true],
                    ['label' => __('Position on single product page', 'wishlist-and-compare'),'type' => 'select_change','options' => [['value' => 'after','name' => __('After "Add to Cart" Button', 'wishlist-and-compare')],['value' => 'before','name' => __('Before "Add to cart" Button', 'wishlist-and-compare')],['value' => 'above_thumb','name' => __('Above Thumbnail', 'wishlist-and-compare')]],'field_name' => 'productpage_position','dependant' => $productdependant,'field_value' => esc_attr($productpage_position),'parent' => 'show_in_product'],
                    ['label' => __('Thumbnail position', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'product_thumb_position','dependant' => $prodpositiondepend,'options' => [['value' => 'bottom_left','name' => __('Bottom Left', 'wishlist-and-compare')]],'field_value' => esc_attr($product_thumb_position),'parent' => 'productpage_position','main_parent' => 'show_in_shop'],
                    ['label' => __('Open popup on click of compare button', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'open_popup','dependant' => true,'field_value' => esc_attr($open_popup)],
                    ['label' => __('Button action of added button', 'wishlist-and-compare'),'type' => 'select_field','options' => [['value' => 'popup','name' => __('Open popup', 'wishlist-and-compare')],['value' => 'page','name' => __('Open page', 'wishlist-and-compare')]],'field_name' => 'button_action','dependant' => true,'field_value' => esc_attr($button_action)],];
            return $settings_data;
        }

        private function table_settings_data()
        {
            $pages = get_pages();
            $title[] = array('value' => '','name' => 'Select');
            foreach ($pages as $page_data) {
                $title[] = array('value' => $page_data->ID,
                    'name' => $page_data->post_title);
            }
            $compare_table = THWWC_Utils::thwwc_get_compare_table_settings();
            if ($compare_table == false) {
                $compare_page = '';
                $lightbox_title = 'Compare Products';
                $hide_attribute = false;
                $hide_show = false;
                $remove_button = false;
            }
            if (!empty($compare_table)) {
                $table_keys = array_keys($compare_table);
                $table_values = array_values($compare_table);
                $length = count($table_keys);
                for ($i=0; $i<$length; $i++) {
                    $tablearray[] = array('key'=>$table_keys[$i], 'value'=>$table_values[$i]);
                }
                foreach ($tablearray as $table) {
                    $key = $table['key'];
                    $$key = $table['value'] == 'true' ? true : ($table['value'] == 'false' ? false : $table['value']);
                    if ($key == 'lightbox_title') {
                        $$key = stripcslashes($table['value']);
                    }
                }
            }

            $hide_attribute = isset($hide_attribute) ? $hide_attribute : false;
            $hideshowdependant = isset($hide_attribute) && $hide_attribute == true ? true : false;
            $lightbox_title = isset($lightbox_title) && $lightbox_title != '' ? $lightbox_title : 'Compare products';
            $hide_show = isset($hide_show) ? $hide_show : false;
            $remove_button = isset($remove_button)? $remove_button : false;
            $settings_data = [
                    ['label' => __('Choose page to display the comparison list', 'wishlist-and-compare'),'type' => 'select_field','field_name' => 'compare_page','dependant' => true,'options' => $title,'field_value' => esc_attr($compare_page)],
                    ['label' => __('Display title for lightbox', 'wishlist-and-compare'),'type' => 'text_field','field_name' => 'lightbox_title','dependant' => true,'field_value' => $lightbox_title],
                    ['label' => __('Hide attributes with the same value', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'hide_attribute','dependant' => true,'field_value' => esc_attr($hide_attribute),'children' => true],
                    ['label' => __('Display a switch to show/hide the attributes with the same value', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'hide_show','dependant' => $hideshowdependant,'field_value' => esc_attr($hide_show),'parent' => 'hide_attribute'],
                    ['label' => __('Display a remove button to remove products from the comparison list', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'remove_button','dependant' => true,'field_value' => esc_attr($remove_button)],];
            return $settings_data;
        }

        private function attribute_drag_fields()
        {
            $compare_table = THWWC_Utils::thwwc_get_compare_table_settings();
            $drag_fields = array();
            if (!$compare_table) {
                $show_image = true;
                $show_title = true;
                $show_price = true;
                $show_description = true;
                $show_addtocart = true;
                $show_sku = false;
                $show_available = false;
                $show_weight = false;
                $show_dimension = false;
                $drag_fields = array(
                    array( 'label' => esc_html__('Image', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_image','dependant' => true,'field_value' => esc_attr($show_image), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Title', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_title','dependant' => true,'field_value' => esc_attr($show_title), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Price', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_price','dependant' => true,'field_value' => esc_attr($show_price), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Description', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_description','dependant' => true,'field_value' => esc_attr($show_description), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Add to cart', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_addtocart','dependant' => true,'field_value' => esc_attr($show_addtocart), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Sku', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_sku','dependant' => true,'field_value' => esc_attr($show_sku), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Availability', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_available','dependant' => true,'field_value' => esc_attr($show_available), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Weight', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_weight','dependant' => true,'field_value' => esc_attr($show_weight), 'drag_field' => 'drag' ),
                    array( 'label' => esc_html__('Dimensions', 'wishlist-and-compare'),'type' => 'checkbox','field_name' => 'show_dimension','dependant' => true,'field_value' => esc_attr($show_dimension), 'drag_field' => 'drag' )
                );
                $attribute_taxonomies = $this->thwwc_variation_attributes();
                $drag_fields = array_merge($drag_fields, $attribute_taxonomies);
            } else {
                $all_fields = $compare_table['fields'];
                foreach ($all_fields as $key => $value) {
                    switch ($key) {
                    case 'show_image':
                        $label = esc_html__('Image', 'wishlist-and-compare');
                        break;
                        
                    case 'show_price':
                        $label = esc_html__('Price', 'wishlist-and-compare');
                        break;

                    case 'show_title':
                        $label = esc_html__('Title', 'wishlist-and-compare');
                        break;

                    case 'show_description':
                        $label = esc_html__('Description', 'wishlist-and-compare');
                        break;

                    case 'show_addtocart':
                        $label = esc_html__('Add to cart', 'wishlist-and-compare');
                        break;

                    case 'show_sku':
                        $label = esc_html__('Sku', 'wishlist-and-compare');
                        break;

                    case 'show_available':
                        $label = esc_html__('Availability', 'wishlist-and-compare');
                        break;

                    case 'show_weight':
                        $label = esc_html__('Weight', 'wishlist-and-compare');
                        break;

                    case 'show_dimension':
                        $label = esc_html__('Dimensions', 'wishlist-and-compare');
                        break;

                    default:
                        $label = THWWC_Utils::thwwc_get_attribute_label_from_name($key);
                        break;
                    }
                    if ($label != '') {
                        $drag_fields[] = array('label' => $label, 'type' => 'checkbox', 'field_name' => esc_attr($key), 'dependant' => true, 'field_value' => esc_attr($value), 'drag_field' => 'drag');
                    }
                }
            }
            return $drag_fields;
        }

        private function thwwc_variation_attributes()
        {
            $attribute_taxonomies = wc_get_attribute_taxonomies(); 
            $attribute_terms = array();
            if ($attribute_taxonomies) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_label = isset($tax->attribute_label) ? $tax->attribute_label : false;
                        $attribute_name = isset($tax->attribute_name) ? $tax->attribute_name : false;
                        $attribute_value = isset($$attribute_name) ? $$attribute_name : false;
                        $attribute_terms[] = array('label' => $attribute_label,'type' => 'checkbox','field_name' => $attribute_name,'dependant' => true,'field_value' => esc_attr($attribute_value), 'drag_field' => 'drag');
                    }
                }
            }
            return $attribute_terms;
        }
    }
endif;