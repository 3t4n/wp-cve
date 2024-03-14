<?php

if ( !class_exists( 'WModes_Admin_Logic_Types_Conditions' ) ) {

    class WModes_Admin_Logic_Types_Conditions {

        public static function init() {

            add_filter( 'wmodes-admin/get-panel-conditions-fields', array( new self(), 'get_fields' ), 20, 2 );

            add_filter( 'reon/get-simple-repeater-field-wmodes-conditions-templates', array( new self(), 'get_condition_templates' ), 10, 2 );
            add_filter( 'roen/get-simple-repeater-template-wmodes-conditions-condition-fields', array( new self(), 'get_condition_fields' ), 10, 2 );
        }

        public static function get_fields( $in_fields, $args ) {

            if ( !defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $in_fields[] = array(
                    'id' => 'match_mode',
                    'type' => 'select2',
                    'full_width' => true,
                    'center_head' => true,
                    'title' => esc_html__( 'Conditions', 'wmodes-tdm' ),
                    'desc' => sprintf( esc_html__( 'List of conditions in which this %s should apply, empty conditions will apply in all cases', 'wmodes-tdm' ), $args[ 'text' ] ),
                    'default' => 'match_all',
                    'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                    'options' => array(
                        'match_all' => esc_html__( 'All conditions should match', 'wmodes-tdm' ),
                    ),
                    'width' => '320px',
                );
            }

            $in_fields[] = array(
                'id' => 'conditions',
                'filter_id' => 'wmodes-conditions',
                'type' => 'simple-repeater',
                'new_field_args' => $args,
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
                    'button_text' => esc_html__( 'Add Condition', 'wmodes-tdm' ),
                ),
            );


            return $in_fields;
        }

        public static function get_condition_templates( $in_templates, $repeater_args ) {

            $in_templates[] = array(
                'id' => 'condition',
            );

            return $in_templates;
        }

        public static function get_condition_fields( $in_fields, $repeater_args ) {

            $args = $repeater_args[ 'field_args' ];

            $list = array();

            $groups = WModes_Admin_Condition_Types::get_groups( $args );

            foreach ( $groups as $group_id => $group_label ) {
                $list[ $group_id ][ 'label' ] = $group_label;
                $list[ $group_id ][ 'options' ] = WModes_Admin_Condition_Types::get_conditions( $group_id, $args );
            }

            $in_fields[] = array(
                'id' => 'condition_type',
                'type' => 'select2',
                'default' => '',
                'disabled_list_filter' => 'wmodes-admin/get-disabled-grouped-list',
                'options' => $list,
                'width' => '98%',
                'box_width' => '33%',
                'dyn_switcher_id' => 'condition_type',
            );


            $conds = array();

            foreach ( $list as $grp ) {
                if ( count( $grp[ 'options' ] ) > 0 ) {
                    $conds = array_merge( $conds, array_keys( $grp[ 'options' ] ) );
                }
            }

            $disabled_list = WModes_Admin_Page::get_disabled_list( array(), $conds );

            foreach ( $conds as $cond ) {

                if ( in_array( $cond, $disabled_list ) ) {
                    continue;
                }

                $in_fields[] = array(
                    'id' => 'condition_type_' . $cond,
                    'type' => 'group-field',
                    'dyn_switcher_target' => 'condition_type',
                    'dyn_switcher_target_value' => $cond,
                    'fluid-group' => true,
                    'width' => '67%',
                    'css_class' => array( 'rn-last' ),
                    'fields' => WModes_Admin_Condition_Types::get_condition_fields( $cond, $args ),
                );
            }

            return $in_fields;
        }

    }

    WModes_Admin_Logic_Types_Conditions::init();
}

