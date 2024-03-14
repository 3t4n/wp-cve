<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_Panel_Modes' ) ) {

    class WModes_Admin_Catalog_Mode_Panel_Modes {

        private static $template_added = array();
        private static $require_products = array();

        public static function init() {
            add_filter( 'wmodes-admin/catalog-modes/get-panel-modes-fields', array( new self(), 'get_panel_fields' ), 10 );
            add_filter( 'wmodes-admin/catalog-modes/get-panels', array( new self(), 'get_panel' ), 10 );
            add_filter( 'reon/get-repeater-field-wmodes-modes-templates', array( new self(), 'get_option_templates' ), 10, 2 );
            add_filter( 'reon/get-repeater-field-wmodes-modes-template-groups', array( new self(), 'get_option_template_groups' ), 10, 2 );
        }

        public static function get_panel( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'css_class' => 'wmodes_option_list',
                'field_css_class' => array( 'wmodes_option_list_field' ),
                'last' => true,
                'fields' => apply_filters( 'wmodes-admin/catalog-modes/get-panel-modes-fields', array() ),
            );

            return $in_fields;
        }

        public static function get_panel_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'last' => true,
                'css_class' => 'wmodes_list_title',
                'fields' => array(
                    array(
                        'id' => 'any_id',
                        'type' => 'paneltitle',
                        'full_width' => true,
                        'center_head' => true,
                        'title' => esc_html__( 'Settings', 'wmodes-tdm' ),
                        'desc' => esc_html__( 'List of catalog modes settings to apply, empty list will not apply any catalog mode option', 'wmodes-tdm' ),
                    )
                ),
            );


            $in_fields[] = array(
                'id' => 'options',
                'filter_id' => 'wmodes-modes',
                'type' => 'repeater',
                'white_repeater' => true,
                'repeater_size' => 'smaller',
                'section_type_id' => 'mode_type',
                'collapsible' => false,
                'accordions' => false,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => false,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more options', 'wmodes-tdm' ),
                'css_class' => 'wmodes_list',
                'field_css_class' => array( 'wmodes_list_field' ),
                'width' => '100%',
                'auto_expand' => array(
                    'all_section' => true,
                    'new_section' => true,
                    'default_section' => true,
                    'cloned_section' => true,
                ),
                'sortable' => array(
                    'enabled' => true,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => true,
                    'list_icon' => 'fa fa-list',
                    'list_width' => '236px',
                    'button_text' => esc_html__( 'Add Settings', 'wmodes-tdm' ),
                ),
            );

            return $in_fields;
        }

        public static function get_option_templates( $in_templates, $repeater_args ) {

            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {

                $args = array(
                    'module' => 'catalog-modes',
                    'is_global' => true,
                );

                $options = WModes_Admin_Catalog_Mode_Types::get_types( $args );

                foreach ( $options as $key => $option ) {

                    if ( !isset( $option[ 'title' ] ) ) {
                        $option[ 'title' ] = $option[ 'list_title' ];
                    }

                    $template = array(
                        'id' => $key,
                        'list_label' => $option[ 'list_title' ],
                        'head' => array(
                            'title' => $option[ 'title' ],
                        )
                    );

                    if ( isset( $option[ 'group_id' ] ) ) {
                        $template[ 'group_id' ] = $option[ 'group_id' ];
                    }

                    if ( isset( $option[ 'tooltip' ] ) ) {
                        $template[ 'head' ][ 'tooltip' ] = $option[ 'tooltip' ];
                    }

                    $in_templates[] = $template;

                    if ( !in_array( $key, self::$template_added ) ) {
                        add_filter( 'roen/get-repeater-template-wmodes-modes-' . $key . '-fields', array( new self(), 'get_option_fields' ), 10, 2 );
                        self::$template_added[] = $key;
                    }
                }
            }

            return $in_templates;
        }

        public static function get_option_template_groups( $in_groups, $repeater_args ) {

            if ( $repeater_args[ 'screen' ] == 'option-page' && $repeater_args[ 'option_name' ] == WModes_Admin_Page::get_option_name() ) {

                $args = array(
                    'module' => 'catalog-modes',
                    'is_global' => true,
                );


                $in_groups = WModes_Admin_Catalog_Mode_Types::get_groups( $args );
            }


            return $in_groups;
        }

        public static function get_option_fields( $in_fields, $repeater_args ) {

            $template_id = $repeater_args[ 'id' ];

            $args = array(
                'module' => 'catalog-modes',
                'is_global' => true,
            );
            
            $in_fields[] = array(
                'id' => 'id',
                'type' => 'autoid',
                'autoid' => 'wmodes',
            );

            $in_flds = apply_filters( 'wmodes-admin/catalog-modes/get-mode-type-' . $template_id . '-fields', array(), $args );

            foreach ( $in_flds as $flds ) {
                $in_fields[] = $flds;
            }
            
            if ( in_array( $template_id, self::get_requires_products( $args ) ) ) {
                return WModes_Admin_Logic_Types_Product_Filters::get_fields( $in_fields, $args );
            }

            return $in_fields;
        }

        private static function get_requires_products( $args ) {

            if ( count( self::$require_products ) > 0 ) {
                return self::$require_products;
            }

            self::$require_products = apply_filters( 'wmodes-admin/catalog-modes/get-type-requires-products', array(), $args );

            return self::$require_products;
        }

    }

    WModes_Admin_Catalog_Mode_Panel_Modes::init();
}

