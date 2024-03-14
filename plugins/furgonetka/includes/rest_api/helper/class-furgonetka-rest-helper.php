<?php

/**
 * The file that defines adds helpler functions
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/helper
 */

/**
 * Class Furgonetka_rest_helper - adds helpers to REST API
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/helper
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_rest_helper
{
    /**
     * @param string $coupon
     * @param string $email
     *
     * @return mixed bool | WP_Error
     */
    public static function validate_coupon( string $coupon, string $email )
    {
        global $woocommerce;

        $couponObject = new WC_Coupon( $coupon );
        $emailRestrictions = $couponObject->get_email_restrictions();

        if ( !empty( $emailRestrictions ) && !in_array( $email, $couponObject->get_email_restrictions() ) ) {
            return new WP_Error(
                'furgonetka_coupon_unavailable',
                __( 'Coupon is not available for this email.', 'furgonetka' ),
                array( 'status' => 400 )
            );
        }

        $discounts = new WC_Discounts( $woocommerce->cart );

        return $discounts->is_coupon_valid( $couponObject );
    }

    public static function validate_and_add_coupon( string $coupon, string $email )
    {
        global $woocommerce;

        $couponValidationResult = self::validate_coupon($coupon, $email);

        if (is_wp_error($couponValidationResult)) {
            return $couponValidationResult;
        }

        if ( $woocommerce->cart->has_discount( $coupon ) ) {
            return new WP_Error(
                'furgonetka_coupon_already_applied',
                __( 'Coupon already applied.', 'furgonetka' ),
                array( 'status' => 400 )
            );
        }

        if ( !$woocommerce->cart->apply_coupon( $coupon ) ) {
            return new WP_Error(
                'furgonetka_coupon_not_applied',
                __( 'Coupon was not applied.', 'furgonetka' ),
                array( 'status' => 400 )
            );
        }

        return true;
    }
}
