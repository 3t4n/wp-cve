<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Condition_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/condition-types-customer.php');
    require_once (dirname( __FILE__ ) . '/condition-types-datetime.php');
    require_once (dirname( __FILE__ ) . '/condition-types-cart.php');

    class WModes_Condition_Types {

        private static $instance;
        private $condition_type_customer;
        private $condition_type_datetime;
        private $condition_type_cart;

        private function __construct() {

            $this->condition_type_customer = new WModes_Condition_Type_Customer();
            $this->condition_type_datetime = new WModes_Condition_Type_DateTime();
            $this->condition_type_cart = new WModes_Condition_Type_Cart();
        }

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function validate( $conditions, $data ) {

            if ( !isset( $data[ 'contex' ] ) ) {

                return false;
            }

            // empty conditions will apply in all cases

            if ( !isset( $conditions[ 'conditions' ] ) ) {

                return true;
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Condition_Types::validate( $conditions, $data );
            }

            $this_obj = self::get_instance();

            return $this_obj->validate_conditions( $conditions, $data );
        }

        public function validate_conditions( $conditions, $data ) {

            // go through each conditions and validate them
            foreach ( $this->get_conditions( $conditions ) as $condition ) {

                if ( false == $this->validate_condition( $condition, $data ) ) {

                    return false;
                }
            }

            return true;
        }

        private function validate_condition( $condition, $data ) {

            if ( $this->condition_type_customer->can_validate( $condition[ 'condition_type' ] ) ) {

                return $this->condition_type_customer->validate( $condition, $data );
            }
            
            if ( $this->condition_type_datetime->can_validate( $condition[ 'condition_type' ] ) ) {

                return $this->condition_type_datetime->validate( $condition, $data );
            }
            
            if ( $this->condition_type_cart->can_validate( $condition[ 'condition_type' ] ) ) {

                return $this->condition_type_cart->validate( $condition, $data );
            }

            // allows other plugins to validate condition
            return apply_filters( 'wmodes/validate-condition', true, $condition, $data );
        }

        private function get_conditions( $in_conditions ) {

            $module_args = $this->get_module_args( $in_conditions );

            $conditions = array();

            foreach ( $in_conditions[ 'conditions' ] as $in_condition ) {

                $condition_type = $in_condition[ 'condition_type' ];

                $args_key = 'condition_type_' . $condition_type;

                if ( !isset( $in_condition[ $args_key ] ) ) {

                    continue;
                }

                $conditions[] = $this->prepare_condition_args( $in_condition[ $args_key ], $condition_type, $module_args );
            }

            return $conditions;
        }

        private function prepare_condition_args( $condition_args, $condition_type, $module_args ) {

            $condition = array(
                'condition_type' => $condition_type
            );

            foreach ( $condition_args as $key => $value ) {

                $condition[ $key ] = $value;
            }

            $condition[ 'module_args' ] = $module_args;

            return $condition;
        }

        private function get_module_args( $in_conditions ) {

            $module_args = array(
                'id' => 0,
                'module' => '',
                'is_global' => 1,
                'context' => 'edit',
            );

            if ( isset( $in_conditions[ 'id' ] ) ) {

                $module_args[ 'id' ] = $in_conditions[ 'id' ];
            }

            if ( isset( $in_conditions[ 'module' ] ) ) {

                $module_args[ 'module' ] = $in_conditions[ 'module' ];
            }

            if ( isset( $in_conditions[ 'is_global' ] ) ) {

                $module_args[ 'is_global' ] = $in_conditions[ 'is_global' ];
            }

            if ( isset( $in_conditions[ 'context' ] ) ) {

                $module_args[ 'context' ] = $in_conditions[ 'context' ];
            }

            return $module_args;
        }

    }

}


