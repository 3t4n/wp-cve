<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_MetaBox_Panel_Modes' ) ) {

    class WModes_Admin_Catalog_Mode_MetaBox_Panel_Modes {

        private static $template_added = array();
        private static $require_variations = array();

        public static function init() {
            add_filter( 'wmodes-admin/catalog-modes/get-mbx-panels', array( new self(), 'get_panel' ), 10, 2 );
            add_filter( 'wmodes-admin/catalog-modes/get-metabox-modes', array( new self(), 'get_fields' ), 10, 2 );
            add_filter( 'reon/get-repeater-field-options-templates', array( new self(), 'get_option_templates' ), 10, 2 );
            add_filter( 'reon/get-repeater-field-options-template-groups', array( new self(), 'get_option_template_groups' ), 10, 2 );
        }

        public static function get_panel( $in_fields, $product_id ) {
            $in_fields[] = array(
                'id' => 'wmodes_options_any_id',
                'type' => 'panel',
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'last' => true,
                'merge_fields' => false,
                'css_class' => array( 'wmodes_metabox_panel', 'mbx_last_panel' ),
                'fields' => apply_filters( 'wmodes-admin/catalog-modes/get-metabox-modes', array(), $product_id ),
                'fold' => array(
                    'target' => 'enable_catalog_mode',
                    'attribute' => 'value',
                    'value' => 'yes',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

        public static function get_fields( $in_fields, $product_id ) {


            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'last' => true,
                'css_class' => array( 'wmodes_list_title', 'wmodes_mbx_title' ),
                'fields' => array(
                    array(
                        'id' => 'any_id',
                        'type' => 'paneltitle',
                        'full_width' => true,
                        'center_head' => true,
                        'title' => esc_html__( 'Settings', 'wmodes-tdm' ),
                        'desc' => esc_html__( 'List of catalog modes settings to apply, empty list will not apply any catalog mode settings', 'wmodes-tdm' ),
                    )
                ),
            );



            $in_fields[] = array(
                'id' => 'options',
                'type' => 'repeater',
                'field_args' => $product_id,
                'full_width' => true,
                'center_head' => true,
                'white_repeater' => true,
                'repeater_size' => 'smaller',
                'section_type_id' => 'mode_type',
                'collapsible' => false,
                'accordions' => false,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => false,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more options', 'wmodes-tdm' ),
                'css_class' => array( 'wmodes_list', 'wmodes_mbx_list' ),
                'field_css_class' => array( 'wmodes_list_field', 'wmodes_mbx_list_field' ),
                'width' => '100%',
                'last' => true,
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

            if ( $repeater_args[ 'screen' ] == 'metabox-wc' && $repeater_args[ 'metabox_id' ] == WModes_Admin_Catalog_Mode_MetaBox_Tab::get_metabox_id() ) {

                $args = array(
                    'module' => 'catalog-modes',
                    'is_global' => false,
                    'product_id' => $repeater_args[ 'field_args' ],
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
                        add_filter( 'roen/get-repeater-template-options-' . $key . '-fields', array( new self(), 'get_option_fields' ), 10, 2 );
                        self::$template_added[] = $key;
                    }
                }
            }

            return $in_templates;
        }

        public static function get_option_template_groups( $in_groups, $repeater_args ) {

            if ( $repeater_args[ 'screen' ] == 'metabox-wc' && $repeater_args[ 'metabox_id' ] == WModes_Admin_Catalog_Mode_MetaBox_Tab::get_metabox_id() ) {



                $args = array(
                    'module' => 'catalog-modes',
                    'is_global' => false,
                    'product_id' => $repeater_args[ 'field_args' ],
                );

                $in_groups = WModes_Admin_Catalog_Mode_Types::get_groups( $args );
            }


            return $in_groups;
        }

        public static function get_option_fields( $in_fields, $repeater_args ) {

            $template_id = $repeater_args[ 'id' ];

            $args = array(
                'module' => 'catalog-modes',
                'is_global' => false,
                'product_id' => $repeater_args[ 'field_args' ],
                'text' => 'catalog mode',
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
            
            if ( in_array( $template_id, self::get_requires_variations( $args ) ) ) {

                return WModes_Admin_Logic_Types_Product_Variations::get_fields( $in_fields, $args );
            }

            return $in_fields;
        }

        private static function get_requires_variations( $args ) {

            if ( count( self::$require_variations ) > 0 ) {
                return self::$require_variations;
            }

            self::$require_variations = apply_filters( 'wmodes-admin/catalog-modes/get-type-requires-variations', array(), $args );

            return self::$require_variations;
        }

    }

    WModes_Admin_Catalog_Mode_MetaBox_Panel_Modes::init();
}
