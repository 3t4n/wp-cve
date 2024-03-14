<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes_Validation_Util' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Validation_Util {
        
        public static function validate_date( $validate_type, $value, $rule_value, $value_format, $rule_value_format ) {

            if ( is_string( $value ) ) {
                $value = DateTime::createFromFormat( $value_format, $value );
            }

            if ( is_string( $rule_value ) ) {
                $rule_value = DateTime::createFromFormat( $rule_value_format, $rule_value );
            }


            $c_date = $value->format( 'U' );
            $rule_c_date = $rule_value->format( 'U' );

            return self::validate_value( $validate_type, $c_date, $rule_c_date );
        }
        
        public static function validate_yes_no( $value, $rule_yes_no ) {
            $yes_value = 'no';
            if ( $value == true ) {
                $yes_value = 'yes';
            }
            return ($yes_value == $rule_yes_no);
        }
        
        public static function validate_value( $validate_type, $value, $rule_value = '' ) {

  
            if ( $validate_type == '>=' ) {
                if ( $value === '' ) {
                    return false;
                }
                return ($value >= $rule_value);
            }
            if ( $validate_type == '>' ) {
                if ( $value === '' ) {
                    return false;
                }
                return ($value > $rule_value);
            }
            if ( $validate_type == '<=' ) {
                if ( $value === '' ) {
                    return false;
                }
                return ($value <= $rule_value);
            }
            if ( $validate_type == '<' ) {
                if ( $value === '' ) {
                    return false;
                }
                return ($value < $rule_value);
            }
            
            return false;
        }

        public static function validate_value_list( $value, $rule_list, $validate_type ) {
            
            $found = in_array( $value, $rule_list );

            $is_equals = ('in_list' == $validate_type );

            return ($found == $is_equals);
        }

        public static function validate_list_list( $list, $rule_list, $validate_type ) {
            if ( $validate_type == 'in_list' || $validate_type == 'none' ) {
                return self::validate_list_in_list( $list, $rule_list, $validate_type );
            }

            if ( $validate_type == 'in_all_list' ) {
                return self::validate_list_all_in_list( $list, $rule_list );
            }

            if ( $validate_type == 'in_list_only' ) {
                return self::validate_list_only_in_list( $list, $rule_list );
            }

            if ( $validate_type == 'in_all_list_only' ) {
                $all_list = self::validate_list_all_in_list( $list, $rule_list );
                $only_list = self::validate_list_only_in_list( $list, $rule_list );
                return ($all_list == true && $only_list == true);
            }


            return false;
        }

        private static function validate_list_in_list( $list, $rule_list, $validate_type ) {
            $found = false;

            $is_equals = ('in_list' == $validate_type );

            foreach ( $list as $lst ) {

                if ( true == $found ) {

                    break;
                }

                if ( self::validate_value_list( $lst, $rule_list, 'in_list' ) ) {

                    $found = true;
                }
            }

            return ($found == $is_equals);
        }

        private static function validate_list_all_in_list( $list, $rule_list ) {
            
            $found_count = 0;

            $rule_list_count = count( $rule_list );

            foreach ( $list as $lst ) {

                if ( self::validate_value_list( $lst, $rule_list, 'in_list' ) ) {

                    $found_count++;
                }
            }

            return ($found_count == $rule_list_count);
        }

        private static function validate_list_only_in_list( $list, $rule_list ) {
            
            $found_count = 0;

            $list_count = count( $list );

            foreach ( $rule_list as $lst ) {

                if ( self::validate_value_list( $lst, $list, 'in_list' ) ) {

                    $found_count++;
                }
            }

            return ($found_count == $list_count);
        }

    }

}