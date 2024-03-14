<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_Page' ) ) {

    class WModes_Admin_Catalog_Mode_Page {

        public static function init() {

            $option_name = WModes_Admin_Page::get_option_name();

            add_filter( 'get-option-page-' . $option_name . 'section-catalog_modes-fields', array( new self(), 'get_page_fields' ), 10, 2 );
            add_filter( 'reon/get-repeater-field-catalog_modes-templates', array( new self(), 'get_templates' ), 10, 2 );
            add_filter( 'roen/get-repeater-template-catalog_modes-catalog_mode-fields', array( new self(), 'get_fields' ), 10, 2 );
            add_filter( 'roen/get-repeater-template-catalog_modes-catalog_mode-head-fields', array( new self(), 'get_head_fields' ), 10, 2 );
        }

        public static function get_page_fields( $in_fields, $section_id ) {

            $in_fields[] = array(
                'id' => 'catalog_mode_settings',
                'type' => 'panel',
                'last' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'field_css_class' => array( 'wmodes_apply_mode' ),
                'fields' => array(
                    array(
                        'id' => 'catalog_modes_any_id',
                        'type' => 'columns-field',
                        'columns' => 1,
                        'merge_fields' => false,
                        'fields' => array(
                            array(
                                'id' => 'mode',
                                'type' => 'select2',
                                'column_size' => 3,
                                'column_title' => esc_html__( 'Apply Method', 'wmodes-tdm' ),
                                'tooltip' => esc_html__( 'Controls catalog modes apply method', 'wmodes-tdm' ),
                                'default' => 'all',
                                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                                'options' => self::get_apply_methods(),
                                'width' => '100%',
                            ),
                        ),
                    ),
                ),
            );

            $max_sections = 1;
            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $max_sections = 99999;
            }

            $in_fields[] = array(
                'id' => 'catalog_modes',
                'type' => 'repeater',
                'white_repeater' => false,
                'repeater_size' => 'small',
                'accordions' => true,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => true,
                'width' => '100%',
                'max_sections' => $max_sections,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more options', 'wmodes-tdm' ),
                'field_css_class' => array( 'wmodes_options' ),
                'css_class' => 'wmodes_extension_options',
                'auto_expand' => array(
                    'new_section' => true,
                    'cloned_section' => true,
                ),
                'sortable' => array(
                    'enabled' => true,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => false,
                    'button_text' => esc_html__( 'New Catalog Mode Settings', 'wmodes-tdm' ),
                ),
            );

            return $in_fields;
        }

        public static function get_templates( $in_templates, $repeater_args ) {
            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {

                $in_templates[] = array(
                    'id' => 'catalog_mode',
                    'head' => array(
                        'title' => '',
                        'defaut_title' => esc_html__( 'Catalog Mode Settings', 'wmodes-tdm' ),
                        'title_field' => 'admin_note',
                    )
                );
            }

            return $in_templates;
        }

        public static function get_fields( $in_fields, $repeater_args ) {
            
            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {
                return apply_filters( 'wmodes-admin/catalog-modes/get-panels', array() );
            }
            
            return $in_fields;
        }

        public static function get_head_fields( $in_fields, $repeater_args ) {
            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {
                $in_fields[] = array(
                    'id' => 'any_id',
                    'type' => 'group-field',
                    'position' => 'right',
                    'width' => '100%',
                    'merge_fields' => false,
                    'fields' => array(
                        array(
                            'id' => 'apply_mode',
                            'type' => 'select2',
                            'default' => 'with_others',
                            'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                            'options' => self::get_apply_modes(),
                            'width' => '310px',
                        ),
                        array(
                            'id' => 'enable',
                            'type' => 'select2',
                            'default' => 'yes',
                            'options' => array(
                                'yes' => esc_html__( 'Enable', 'wmodes-tdm' ),
                                'no' => esc_html__( 'Disable', 'wmodes-tdm' ),
                            ),
                            'width' => '95px',
                        ),
                    ),
                );
            }

            return $in_fields;
        }

        private static function get_apply_methods() {

            $apply_methods = array(
                'all' => esc_html__( 'Apply all valid catalog mode settings', 'wmodes-tdm' ),
            );

          

            $apply_methods = apply_filters( 'wmodes-admin/catalog-modes/get-apply-methods', $apply_methods );

            $apply_methods[ 'no' ] = esc_html__( 'Do not apply any catalog mode settings', 'wmodes-tdm' );

            return $apply_methods;
        }

        private static function get_apply_modes() {

            $apply_modes = array(
                'with_others' => esc_html__( 'Apply this and other catalog mode settings', 'wmodes-tdm' ),
            );

          
            return apply_filters( 'wmodes-admin/catalog-modes/get-apply-modes', $apply_modes );
        }

    }

}
