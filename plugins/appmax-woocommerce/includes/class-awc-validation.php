<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Validation
{
    public static function awc_unset_variables_post( $post, $fields )
    {
        foreach ( $fields as $field => $data ) {
            unset( $post[$field] );
        }
    }

    public static function awc_validation_fields( $post, $fields )
    {
        foreach ( $fields as $field => $data ) {

            $validations = explode( "|", $data['validation'] );

            foreach ( $validations as $validation ) {

                $message = $data['messages'][$validation];

                if ( $validation == AWC_Validation_Type::AWC_EMPTY ) {
                    if (! empty( $post[$field] ) ) {
                        continue;
                    }
                    return array( true, $message );
                }

                if ( $validation == AWC_Validation_Type::AWC_CPF ) {
                    if ( AWC_Helper::awc_validate_cpf( $post[$field] ) ) {
                        continue;
                    }
                    return array( ! AWC_Helper::awc_validate_cpf( $post[$field] ), $message );
                }

                if ( $validation == AWC_Validation_Type::AWC_CREDIT_CARD ) {
                    if ( AWC_Helper::awc_validate_card_number( $post[$field] ) ) {
                        continue;
                    }
                    return array( ! AWC_Helper::awc_validate_card_number( $post[$field] ), $message );
                }

                if ( $validation == AWC_Validation_Type::AWC_NUMBER ) {
                    if ( AWC_Helper::awc_is_digit( $post[$field] ) ) {
                        continue;
                    }
                    return array( ! AWC_Helper::awc_is_digit( $post[$field] ), $message );
                }

                if ( $validation == AWC_Validation_Type::AWC_CCV ) {
                    if ( AWC_Helper::awc_validate_ccv_credit_card( $post[$field] ) ) {
                        continue;
                    }
                    return array( ! AWC_Helper::awc_validate_ccv_credit_card( $post[$field] ), $message );
                }
            }
        }
    }
}