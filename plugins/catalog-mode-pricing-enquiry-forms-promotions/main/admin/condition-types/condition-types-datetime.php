<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Condition_Type_DateTime' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Condition_Type_DateTime {

        public static function init() {

            add_filter( 'wmodes-admin/get-condition-groups', array( new self(), 'get_groups' ), 30, 2 );

            add_filter( 'wmodes-admin/get-datetimes-group-conditions', array( new self(), 'get_conditions' ), 10, 2 );

            add_filter( 'wmodes-admin/get-date_time-condition-fields', array( new self(), 'get_date_time_fields' ), 10, 2 );
        }

        public static function get_groups( $in_groups, $args ) {

            $in_groups[ 'datetimes' ] = esc_html__( 'Dates &amp; Times', 'wmodes-tdm' );

            return $in_groups;
        }

        public static function get_conditions( $in_list, $args ) {

            $in_list[ 'date_time' ] = esc_html__( 'Date &amp; Time', 'wmodes-tdm' );
            
            return $in_list;
        }

        public static function get_date_time_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'date_type',
                'type' => 'select2',
                'default' => 'from',
                'options' => array(
                    'from' => esc_html__( 'From', 'wmodes-tdm' ),
                    'to' => esc_html__( 'To', 'wmodes-tdm' ),
                    'between' => esc_html__( 'Between', 'wmodes-tdm' ),
                ),
                'fold_id' => 'date_type',
                'width' => '98%',
                'box_width' => '26%',
            );

            $in_fields[] = array(
                'id' => 'from_date_time',
                'type' => 'datetime',
                'default' => '',
                'placeholder' => esc_html__( 'yy-mm-dd 00:00:00', 'wmodes-tdm' ),
                'date_format' => 'yy-mm-dd',
                'number_of_months' => 1,
                'change_month' => true,
                'change_year' => true,
                'first_day' => 0,
                'time_format' => 'HH:mm:ss',
                'one_line' => true,
                'fold' => array(
                    'target' => 'date_type',
                    'attribute' => 'value',
                    'value' => 'between',
                    'oparator' => 'eq',
                    'clear' => true,
                ),
                'width' => '98%',
                'box_width' => '37%',
            );

            $in_fields[] = array(
                'id' => 'to_date_time',
                'type' => 'datetime',
                'default' => '',
                'placeholder' => esc_html__( 'yy-mm-dd 00:00:00', 'wmodes-tdm' ),
                'date_format' => 'yy-mm-dd',
                'number_of_months' => 1,
                'change_month' => true,
                'change_year' => true,
                'first_day' => 0,
                'time_format' => 'HH:mm:ss',
                'one_line' => true,
                'fold' => array(
                    'target' => 'date_type',
                    'attribute' => 'value',
                    'value' => 'between',
                    'oparator' => 'eq',
                    'clear' => true,
                ),
                'width' => '100%',
                'box_width' => '37%',
            );

            $in_fields[] = array(
                'id' => 'date_time',
                'type' => 'datetime',
                'default' => '',
                'placeholder' => esc_html__( 'yy-mm-dd 00:00:00', 'wmodes-tdm' ),
                'date_format' => 'yy-mm-dd',
                'number_of_months' => 1,
                'change_month' => true,
                'change_year' => true,
                'first_day' => 0,
                'time_format' => 'HH:mm:ss',
                'one_line' => true,
                'fold' => array(
                    'target' => 'date_type',
                    'attribute' => 'value',
                    'value' => 'between',
                    'oparator' => 'neq',
                    'clear' => true,
                ),
                'width' => '100%',
                'box_width' => '74%',
            );

            return $in_fields;
        }

    }

    WModes_Admin_Condition_Type_DateTime::init();
}
