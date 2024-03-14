<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Logic_Types_Product_Filters' ) ) {

    class WModes_Admin_Logic_Types_Product_Filters {

        public static function init() {

            add_filter( 'reon/get-simple-repeater-field-wmodes-product_args-templates', array( new self(), 'get_templates' ), 10, 2 );
            add_filter( 'roen/get-simple-repeater-template-wmodes-product_args-filter-fields', array( new self(), 'get_template_fields' ), 10, 2 );
        }

        public static function get_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'product_args',
                'type' => 'simple-repeater',
                'filter_id' => 'wmodes-product_args',
                'new_field_args' => $args,
                'full_width' => true,
                'center_head' => true,
                'title' => esc_html__( 'Products', 'wmodes-tdm' ),
                'desc' => esc_html__( 'List of product, empty list will include all products', 'wmodes-tdm' ),
                'white_repeater' => false,
                'repeater_size' => 'smaller',
                'buttons_sep' => false,
                'buttons_box_width' => '65px',
                'width' => '100%',
                'sortable' => array(
                    'enabled' => true,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => false,
                    'button_text' => esc_html__( 'Add Product', 'wmodes-tdm' ),
                ),
            );

            return $in_fields;
        }

        public static function get_templates( $in_templates, $repeater_args ) {

            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {
                $in_templates[] = array(
                    'id' => 'filter',
                );
            }

            return $in_templates;
        }

        public static function get_template_fields( $in_fields, $repeater_args ) {

            $args = $repeater_args[ 'field_args' ];

            $list = array();

            $groups = WModes_Admin_Product_Filter_Types::get_groups( $args );

            foreach ( $groups as $group_id => $group_label ) {
                $list[ $group_id ][ 'label' ] = $group_label;
                $list[ $group_id ][ 'options' ] = WModes_Admin_Product_Filter_Types::get_product_filters( $group_id, $args );
            }

            $in_fields[] = array(
                'id' => 'filter_type',
                'type' => 'select2',
                'default' => '',
                'disabled_list_filter' => 'wmodes-admin/get-disabled-grouped-list',
                'options' => $list,
                'width' => '98%',
                'box_width' => '33%',
                'dyn_switcher_id' => 'filter_type',
            );

            $filters = array();
            foreach ( $list as $grp ) {
                if ( count( $grp[ 'options' ] ) > 0 ) {
                    $filters = array_merge( $filters, array_keys( $grp[ 'options' ] ) );
                }
            }


            $disabled_list = WModes_Admin_Page::get_grouped_disabled_list( array(), $filters );

            foreach ( $filters as $filter ) {

                if ( in_array( $filter, $disabled_list ) ) {
                    continue;
                }

                $in_fields[] = array(
                    'id' => 'filter_type_' . $filter,
                    'type' => 'group-field',
                    'dyn_switcher_target' => 'filter_type',
                    'dyn_switcher_target_value' => $filter,
                    'fluid-group' => true,
                    'width' => '67%',
                    'css_class' => array( 'rn-last' ),
                    'fields' => WModes_Admin_Product_Filter_Types::get_product_filter_fields( $filter, $args ),
                );
            }

            return $in_fields;
        }

    }

    WModes_Admin_Logic_Types_Product_Filters::init();
}