<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Product_Options_MetaBox')) {

    class WModes_Admin_Product_Options_MetaBox {

        public static function init() {
            add_filter('wmodes-admin/product-options/get-metabox-fields', array(new self(), 'get_metabox_fields'), 10, 2);
            add_filter('reon/get-repeater-field-product_options-templates', array(new self(), 'get_templates'), 10, 2);
            add_filter('roen/get-repeater-template-product_options-product_option-fields', array(new self(), 'get_panel'), 10, 2);
        }

        public static function get_metabox_fields($in_fields, $product_id) {

            $max_sections = 2;
            if (defined('WMODES_PREMIUM_ADDON')) {
                $max_sections = 99999;
            }

            $in_fields[] = array(
                'id' => 'wmodes_product_options',
                'filter_id'=>'product_options',
                'type' => 'repeater',
                'white_repeater' => false,
                'repeater_size' => 'smaller',
                'collapsible' => true,
                'accordions' => true,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => true,
                'max_sections' => $max_sections,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more settings', 'wmodes-tdm' ),
                'field_css_class' => array('wmodes_mbx_options'),
                'css_class' => array('wmodes_mbx_options_rp'),
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
                    'button_text' => esc_html__( 'New Product Settings', 'wmodes-tdm' ),
                ),
                'fold' => array(
                    'target' => 'enable_product_options',
                    'attribute' => 'value',
                    'value' => 'yes',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

        public static function get_templates($in_templates, $repeater_args) {

            if ($repeater_args['screen'] == 'metabox-wc' && $repeater_args['metabox_id'] == WModes_Admin_Product_Options_MetaBox_Tab::get_metabox_id()) {


                $in_templates[] = array(
                    'id' => 'product_option',
                    'head' => array(
                        'title' => '',
                        'defaut_title' => esc_html__( 'Product Settings', 'wmodes-tdm' ),
                        'title_field' => 'admin_note',
                    )
                );
            }
            return $in_templates;
        }

        public static function get_panel($in_fields, $repeater_args) {
            if ($repeater_args['screen'] == 'metabox-wc' && $repeater_args['metabox_id'] == WModes_Admin_Product_Options_MetaBox_Tab::get_metabox_id()) {

                $product_id = $repeater_args['post_id'];

                $in_fields[] = array(
                    'id' => 'option_id',
                    'type' => 'autoid',
                    'autoid' => 'wmodes',
                );

                $in_fields = apply_filters('wmodes-admin/product-options/get-mbx-panels', $in_fields, $product_id);
            }

            return $in_fields;
        }

    }

    WModes_Admin_Product_Options_MetaBox::init();
}
