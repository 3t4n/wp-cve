<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Settings_Styles_Label_Settings' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Settings_Styles_Label_Settings {

        public static function init() {

            add_filter( 'wmodes-admin/get-settings-styles-section-panels', array( new self(), 'get_panel' ), 20 );

            add_filter( 'reon/get-repeater-field-wmodes_labels-templates', array( new self(), 'get_templates' ), 10, 2 );

            add_filter( 'roen/get-repeater-template-wmodes_labels-ui_default-fields', array( new self(), 'get_template_fields' ), 10, 2 );
            add_filter( 'roen/get-repeater-template-wmodes_labels-ui_option-fields', array( new self(), 'get_template_fields' ), 10, 2 );
        }

        public static function get_panel( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'last' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'fields' => self::get_fields( array() ),
            );

            return $in_fields;
        }

        public static function get_templates( $in_templates, $repeater_args ) {

            $in_templates[] = array(
                'id' => 'ui_default',
                'head' => array(
                    'title' => '',
                    'defaut_title' => esc_html__( 'Label', 'wmodes-tdm' ),
                    'title_field' => 'admin_note',
                ),
                'empy_button' => true,
            );

            $in_templates[] = array(
                'id' => 'ui_option',
                'head' => array(
                    'title' => '',
                    'defaut_title' => esc_html__( 'Label', 'wmodes-tdm' ),
                    'title_field' => 'admin_note',
                ),
            );

            return $in_templates;
        }

        public static function get_template_fields( $in_fields, $repeater_args ) {

            $in_fields[] = array(
                'id' => 'ui_id',
                'type' => 'autoid',
                'autoid' => 'wmodes_ui',
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 1,
                'full_width' => true,
                'merge_fields' => false,
                'fields' => self::admin_note_fields( array() ),
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 5,
                'full_width' => true,
                'center_head' => true,
                'merge_fields' => false,
                'title' => esc_html__( 'Design Settings', 'wmodes-tdm' ),
                'desc' => esc_html__( 'Use these settings to control the look and feel of the label', 'wmodes-tdm' ),
                'field_css_class' => array( 'wmodes_subtitles' ),
                'fields' => self::design_fields( array() ),
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 3,
                'full_width' => true,
                'merge_fields' => false,
                'fields' => self::design_two_fields( array() ),
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 5,
                'full_width' => true,
                'center_head' => true,
                'merge_fields' => false,
                'field_css_class' => array( 'wmodes_subtitles' ),
                'fields' => self::design_three_fields( array() ),
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 3,
                'merge_fields' => false,
                'full_width' => true,
                'center_head' => true,
                'title' => esc_html__( 'Text Settings', 'wmodes-tdm' ),
                'desc' => esc_html__( 'Use these settings to control the text settings', 'wmodes-tdm' ),
                'field_css_class' => array( 'wmodes_subtitles' ),
                'fields' => self::get_text_fields( array() ),
            );

            $in_fields[] = array(
                'id' => 'any_ids',
                'type' => 'columns-field',
                'columns' => 3,
                'merge_fields' => false,
                'full_width' => true,
                'center_head' => true,
                'title' => esc_html__( 'Icon Settings', 'wmodes-tdm' ),
                'desc' => esc_html__( 'Use these settings to control the icon settings', 'wmodes-tdm' ),
                'field_css_class' => array( 'wmodes_subtitles' ),
                'fields' => self::get_icon_fields( array() ),
            );

            return $in_fields;
        }

        private static function get_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'paneltitle',
                'full_width' => true,
                'center_head' => true,
                'title' => esc_html__( 'Label Designs', 'wmodes-tdm' ),
                'desc' => esc_html__( 'Use these settings to create and manage label designs', 'wmodes-tdm' ),
            );

            $in_fields[] = array(
                'id' => 'ui_labels',
                'filter_id' => 'wmodes_labels',
                'type' => 'repeater',
                'white_repeater' => true,
                'repeater_size' => 'smaller',
                'max_sections' => 2,
                'max_sections_msg' => esc_html__( 'Please upgrade to premium version in order to add more design options', 'wmodes-tdm' ),
                'accordions' => true,
                'buttons_sep' => false,
                'delete_button' => true,
                'clone_button' => false,
                'width' => '100%',
                'default' => self::get_default_ui(),
                'static_template' => 'ui_default',
                'section_type_id' => 'option_type',
                'auto_expand' => array(
                    'new_section' => true,
                    'cloned_section' => false,
                ),
                'sortable' => array(
                    'enabled' => false,
                ),
                'template_adder' => array(
                    'position' => 'right',
                    'show_list' => false,
                    'button_text' => esc_html__( 'New Design', 'wmodes-tdm' ),
                ),
            );

           
            return $in_fields;
        }

        private static function admin_note_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'admin_note',
                'type' => 'textbox',
                'tooltip' => esc_html__( 'Adds a private note for reference purposes', 'wmodes-tdm' ),
                'column_size' => 1,
                'column_title' => esc_html__( 'Admin Note', 'wmodes-tdm' ),
                'default' => '',
                'placeholder' => esc_html__( 'Type here...', 'wmodes-tdm' ),
                'width' => '100%',
            );

            return $in_fields;
        }

        private static function design_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'layout',
                'type' => 'select2',
                'column_size' => 1,
                'column_title' => esc_html__( 'Layout', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the text and icon layout mode', 'wmodes-tdm' ),
                'default' => 'inline',
                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                'options' => array(
                    'inline' => esc_html__( 'Inline', 'wmodes-tdm' ),
                ),
                'width' => '100%',
            );

            $in_fields[] = array(
                'id' => 'width',
                'type' => 'group-field',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_size' => 1,
                'column_title' => esc_html__( 'Width', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Determines the width of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_size_fields( array( 'default_size' => 110, 'placeholder' => 'auto' ) ),
            );

            $in_fields[] = array(
                'id' => 'height',
                'type' => 'group-field',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_size' => 1,
                'column_title' => esc_html__( 'Height', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Determines the height of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_size_fields( array( 'default_size' => 32, 'placeholder' => 'auto' ) ),
            );

            $in_fields[] = array(
                'id' => 'color',
                'type' => 'colorpicker',
                'column_size' => 1,
                'column_title' => esc_html__( 'Color', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the text and icon color of the label', 'wmodes-tdm' ),
                'default' => WModes_Admin_Utils::get_theme_value( 'color_2' ),
                'buton_text' => esc_html__( 'Pick color', 'wmodes-tdm' ),
                'width' => '100%',
            );

            $in_fields[] = array(
                'id' => 'bg_color',
                'type' => 'colorpicker',
                'column_size' => 1,
                'column_title' => esc_html__( 'Background Color', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the background color of the label', 'wmodes-tdm' ),
                'default' =>WModes_Admin_Utils::get_theme_value( 'color_4' ),
                'buton_text' => esc_html__( 'Pick color', 'wmodes-tdm' ),
                'width' => '100%',
            );

            return $in_fields;
        }

        private static function design_two_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'margin',
                'type' => 'group-field',
                'column_no_size' => true,
                'merge_fields' => true,
                'fluid-group' => true,
                'column_title' => esc_html__( 'Margin', 'wmodes-tdm' ),
                'column_classes' => array( 'wmodes_box_hint' ),
                'tooltip' => esc_html__( 'Determines the margins of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_boundary_sizes_fields( array( 'default_size' => '', 'placeholder' => '', 'default_unit' => 'px' ) ),
            );

            $in_fields[] = array(
                'id' => 'padding',
                'type' => 'group-field',
                'column_no_size' => true,
                'merge_fields' => true,
                'fluid-group' => true,
                'column_title' => esc_html__( 'Padding', 'wmodes-tdm' ),
                'column_classes' => array( 'wmodes_box_hint' ),
                'tooltip' => esc_html__( 'Determines the paddings of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_boundary_sizes_fields( array( 'default_size' => '', 'placeholder' => '', 'default_unit' => 'px' ) ),
            );



            return $in_fields;
        }

        private static function design_three_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'border_radius',
                'type' => 'group-field',
                'column_width' => '170px',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_title' => esc_html__( 'Border Radius', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Determines the border radius of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_size_fields( array( 'default_size' => '4', 'default_unit' => 'px' ) ),
            );

            $in_fields[] = array(
                'id' => 'border_style',
                'type' => 'select2',
                'column_no_size' => true,
                'column_title' => esc_html__( 'Border Style', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Determines the border style of the label', 'wmodes-tdm' ),
                'default' => 'inline',
                'options' => array(
                    'none' => esc_html__( 'None', 'wmodes-tdm' ),
                    'solid' => esc_html__( 'Solid', 'wmodes-tdm' ),
                    'dotted' => esc_html__( 'Dotted', 'wmodes-tdm' ),
                    'dashed' => esc_html__( 'Dashed', 'wmodes-tdm' )
                ),
                'width' => '100%',
                'column_attributes' => array(
                    'style' => 'min-width:170px;'
                ),
                'fold_id' => 'label_border_style',
            );

            $in_fields[] = array(
                'id' => 'border_color',
                'type' => 'colorpicker',
                'column_no_size' => true,
                'column_title' => esc_html__( 'Border Color', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the border color of the label', 'wmodes-tdm' ),
                'default' => WModes_Admin_Utils::get_theme_value( 'color_2' ),
                'buton_text' => esc_html__( 'Pick color', 'wmodes-tdm' ),
                'width' => '100%',
                'column_attributes' => array(
                    'style' => 'min-width:170px;'
                ),
                'fold' => array(
                    'target' => 'label_border_style',
                    'attribute' => 'value',
                    'value' => array( 'none' ),
                    'oparator' => 'neq',
                    'clear' => false,
                ),
            );

            $in_fields[] = array(
                'id' => 'border_width',
                'type' => 'group-field',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_no_size' => true,
                'column_title' => esc_html__( 'Border Width', 'wmodes-tdm' ),
                'column_classes' => array( 'wmodes_box_hint' ),
                'tooltip' => esc_html__( 'Controls the border thickness of the label', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_boundary_sizes_fields( array( 'default_size' => 1, 'default_unit' => 'px' ) ),
                'fold' => array(
                    'target' => 'label_border_style',
                    'attribute' => 'value',
                    'value' => array( 'none' ),
                    'oparator' => 'neq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

        private static function get_text_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'font_size',
                'type' => 'group-field',
                'column_width' => '170px',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_title' => esc_html__( 'Font Size', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the font size of the text', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_size_fields( array( 'default_size' => 12, 'default_unit' => 'px' ) ),
            );

            $in_fields[] = array(
                'id' => 'line_height',
                'type' => 'group-field',
                'column_width' => '170px',
                'merge_fields' => true,
                'fluid-group' => true,
                'column_title' => esc_html__( 'Line Height', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls the line height of the text', 'wmodes-tdm' ),
                'width' => '100%',
                'fields' => WModes_Admin_Utils::get_size_fields( array( 'default_size' => 12, 'default_unit' => 'px' ) ),
            );

            return $in_fields;
        }

        private static function get_icon_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'icon_type',
                'type' => 'select2',
                'column_width' => '250px',
                'column_title' => esc_html__( 'Icon Type', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Determines the type of icon', 'wmodes-tdm' ),
                'default' => 'no',
                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                'options' => array(
                    'no' => esc_html__( 'No icon', 'wmodes-tdm' ),
                ),
                'width' => '100%',
            );

            return $in_fields;
        }

        private static function get_default_ui() {

            return array(
                array(
                    'calc_option_type' => 'ui_default',
                    'ui_id' => '2234343',
                ),
            );
        }

    }

    WModes_Admin_Settings_Styles_Label_Settings::init();
}

