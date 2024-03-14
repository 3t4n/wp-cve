<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Product_Prices_MetaBox_Box')) {

    class WModes_Admin_Product_Prices_MetaBox_Box {

        public static function init() {
            
            add_filter('wmodes-admin/product-pricing/get-metabox-fields', array(new self(), 'get_metabox_fields'), 10, 2);
            add_filter('reon/get-repeater-field-wmodes-mbx_product_pricings-templates', array(new self(), 'get_templates'), 10, 2);
            add_filter('roen/get-repeater-template-wmodes-mbx_product_pricings-product_pricing-fields', array(new self(), 'get_panel'), 10, 2);
        }

        public static function get_metabox_fields($in_fields, $product_id) {

            $max_sections = 2;
            if (defined('WMODES_PREMIUM_ADDON')) {
                $max_sections = 99999;
            }

            $in_fields[] = array(
                'id' => 'wmodes_product_pricings',
                'filter_id'=>'wmodes-mbx_product_pricings',
                'type' => 'repeater',
                'white_repeater' => false,
                'repeater_size' => 'smaller',
                'collapsible' => true,
                'accordions' => true,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => true,
                'max_sections' => $max_sections,
                'max_sections_msg' => esc_html__('Please upgrade to premium version in order to add more prices', 'wmodes-tdm'),
                'field_css_class' => array('wmodes_mbx_options'),
                'css_class'=>array('wmodes_mbx_options_rp'),
                'width' => '100%',
                'auto_expand' => array(
                    'all_section' => false,
                    'new_section' => true,
                    'default_section' => false,
                    'cloned_section' => true,
                ),
                'sortable' => array(
                    'enabled' => true,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => false,
                    'button_text' => esc_html__('New Product Pricing', 'wmodes-tdm'),
                ),
                'fold' => array(
                    'target' => 'enable_product_prices',
                    'attribute' => 'value',
                    'value' => 'yes',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

        public static function get_templates($in_templates, $repeater_args) {

            if ($repeater_args['screen'] == 'metabox-wc' && $repeater_args['metabox_id'] == WModes_Admin_Product_Prices_MetaBox_Tab::get_metabox_id()) {

                $in_templates[] = array(
                    'id' => 'product_pricing',
                    'head' => array(
                        'title' => '',                        
                        'defaut_title' => esc_html__('Product Pricing', 'wmodes-tdm'),
                        'title_field' => 'admin_note',
                        'subtitle_field' => 'mode',
                    )
                );
            }
            
            return $in_templates;
        }

        public static function get_panel($in_fields, $repeater_args) {

            $product_id = $repeater_args['post_id'];

            $in_fields[] = array(
                'id' => 'pricing_id',
                'type' => 'autoid',
                'autoid' => 'wmodes',
            );

            $in_fields = apply_filters('wmodes-admin/product-pricing/get-mbx-panels', $in_fields, $product_id);
            
            return $in_fields;
        }

    }

    WModes_Admin_Product_Prices_MetaBox_Box::init();
}
