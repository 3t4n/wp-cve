<?php

if ( !class_exists( 'WModes_Admin_Condition_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/condition-types-customer.php');
    require_once (dirname( __FILE__ ) . '/condition-types-datetime.php');
    require_once (dirname( __FILE__ ) . '/condition-types-cart.php');
                       
    class WModes_Admin_Condition_Types {

        public static function get_groups( $args ) {

            return apply_filters( 'wmodes-admin/get-condition-groups', array(), $args );
        }

        public static function get_conditions( $group_id, $args ) {

            return apply_filters( 'wmodes-admin/get-' . $group_id . '-group-conditions', array(), $args );
        }

        public static function get_condition_fields( $condition_id, $args ) {

            return apply_filters( 'wmodes-admin/get-' . $condition_id . '-condition-fields', array(), $args );
        }

    }

}
