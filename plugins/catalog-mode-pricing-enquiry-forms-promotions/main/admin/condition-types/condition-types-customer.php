<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Condition_Type_Customer' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Condition_Type_Customer {

        public static function init() {

            add_filter( 'wmodes-admin/get-condition-groups', array( new self(), 'get_groups' ), 10, 2 );

            add_filter( 'wmodes-admin/get-customers-group-conditions', array( new self(), 'get_conditions' ), 10, 2 );

            add_filter( 'wmodes-admin/get-logged_in_user-condition-fields', array( new self(), 'get_logged_in_user_fields' ), 10, 2 );
            add_filter( 'wmodes-admin/get-users-condition-fields', array( new self(), 'get_users_fields' ), 10, 2 );
            add_filter( 'wmodes-admin/get-user_roles-condition-fields', array( new self(), 'get_user_roles_fields' ), 10, 2 );
        }

        public static function get_groups( $in_groups, $args ) {

            $in_groups[ 'customers' ] = esc_html__( 'Customers', 'wmodes-tdm' );

            return $in_groups;
        }

        public static function get_conditions( $in_list, $args ) {

            $in_list[ 'logged_in_user' ] = esc_html__( 'Customer Is Logged In', 'wmodes-tdm' );
            $in_list[ 'users' ] = esc_html__( 'Customers', 'wmodes-tdm' );
            $in_list[ 'user_roles' ] = esc_html__( 'User Roles', 'wmodes-tdm' );
            
            return $in_list;
        }

        public static function get_logged_in_user_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'is_logged_in',
                'type' => 'select2',
                'default' => 'yes',
                'options' => array(
                    'no' => esc_html__( 'No', 'wmodes-tdm' ),
                    'yes' => esc_html__( 'Yes', 'wmodes-tdm' ),
                ),
                'width' => '100%',
                'box_width' => '100%',
            );

            return $in_fields;
        }

        public static function get_users_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__( 'Any in the list', 'wmodes-tdm' ),
                    'none' => esc_html__( 'None in the list', 'wmodes-tdm' ),
                ),
                'width' => '98%',
                'box_width' => '25%',
            );

            $in_fields[] = array(
                'id' => 'user_emails',
                'type' => 'select2',
                'multiple' => true,
                'minimum_input_length' => 2,
                'placeholder' => esc_html__( 'Search users...', 'wmodes-tdm' ),
                'allow_clear' => true,
                'minimum_results_forsearch' => 10,
                'data' => array(
                    'source' => 'users',
                    'ajax' => true,
                    'value_col' => 'email',
                    'show_value' => true,
                ),
                'width' => '100%',
                'box_width' => '75%',
            );

            return $in_fields;
        }

        public static function get_user_roles_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__( 'Any in the list', 'wmodes-tdm' ),
                    'in_all_list' => esc_html__( 'All in the list', 'wmodes-tdm' ),
                    'in_list_only' => esc_html__( 'Only in the list', 'wmodes-tdm' ),
                    'in_all_list_only' => esc_html__( 'Only all in the list', 'wmodes-tdm' ),
                    'none' => esc_html__( 'None in the list', 'wmodes-tdm' ),
                ),
                'width' => '98%',
                'box_width' => '25%',
            );

            $in_fields[] = array(
                'id' => 'user_roles',
                'type' => 'select2',
                'multiple' => true,
                'minimum_input_length' => 1,
                'placeholder' => esc_html__( 'Search user roles...', 'wmodes-tdm' ),
                'allow_clear' => true,
                'minimum_results_forsearch' => 10,
                'data' => array(
                    'source' => 'roles',
                    'ajax' => true,
                ),
                'width' => '100%',
                'box_width' => '75%',
            );



            return $in_fields;
        }

    }

    WModes_Admin_Condition_Type_Customer::init();
}

