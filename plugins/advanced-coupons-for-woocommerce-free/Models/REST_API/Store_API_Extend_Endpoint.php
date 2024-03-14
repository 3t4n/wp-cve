<?php
namespace ACFWF\Models\REST_API;

use ACFWF\Helpers\Plugin_Constants;
use Automattic\WooCommerce\StoreApi\StoreApi;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;

/**
 * WooCommerce Extend Store API for Cart Endpoint.
 *
 * @since 3.5.7
 */
class Store_API_Extend_Endpoint {
    /**
     * Stores Rest Extending instance.
     *
     * @since 4.5.8
     * @var ExtendSchema
     */
    private static $extend;

    /**
     * Plugin Identifier.
     *
     * @since 4.5.8
     * @var string
     */
    const IDENTIFIER = 'acfwf_block';

    /**
     * Bootstraps the class and hooks required data.
     *
     * @since 4.5.8
     * @access public
     */
    public static function init() {
        self::$extend = StoreApi::container()->get( ExtendSchema::class );
        self::extend_store();
    }

    /**
     * Registers the actual data into each endpoint.
     * - To see available endpoints to extend please go to : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/rest-api/available-endpoints-to-extend.md
     *
     * @since 4.5.8
     * @access public
     */
    public static function extend_store() {
        // Register into `cart`.
        if ( is_callable( array( self::$extend, 'register_endpoint_data' ) ) ) {
            self::$extend->register_endpoint_data(
                array(
                    'endpoint'      => CartSchema::IDENTIFIER,
                    'namespace'     => self::IDENTIFIER,
                    'data_callback' => array( 'ACFWF\Models\REST_API\Store_API_Extend_Endpoint', 'extend_data' ),
                    'schema_type'   => ARRAY_A,
                )
            );
        }
    }

    /**
     * Extend endpoint data.
     * - This data will be available in Redux Data Store `cartData.acfwf_block.extension`.
     * - To learn more you can visit : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/rest-api/extend-rest-api-add-data.md
     *
     * @since 4.5.8
     * @access public
     *
     * @return array $item_data Registered data or empty array if condition is not satisfied.
     */
    public static function extend_data() {
        return array(
            'couponSummaries' => self::get_applied_coupons_summaries(),
            'bogo_deals'      => \ACFWF()->BOGO_Frontend->get_eligible_deal_notices_message_wc_blocks(),
            'store_credits'   => self::calculate_store_credit_discounts(),
        );
    }

    /**
     * Calculate store credits discount.
     *
     * @since 4.6.0
     * @access public
     *
     * @return array
     */
    public static function calculate_store_credit_discounts() {
        $store_credit_balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id() ) );
        $store_credit_session = \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null );
        $store_credit_amount  = ( $store_credit_session['amount'] ?? 0 ) * -1;
        $store_credit_notice  = \ACFWF()->Store_Credits_Checkout->is_store_credit_discount_removed();

        return array(
            'balance'      => $store_credit_balance,
            'balance_text' => wc_price( $store_credit_balance ),
            'amount'       => $store_credit_amount,
            'amount_text'  => wc_price( $store_credit_amount ),
            'notice'       => $store_credit_notice,
        );
    }

    /**
     * Get summary content for all applied coupons to be displayed in the cart/checkout block.
     *
     * @since 4.6.0
     * @access public
     *
     * @return array
     */
    public static function get_applied_coupons_summaries() {
        $data = array();
        foreach ( \WC()->cart->get_coupons() as $coupon ) {
            $coupon_code = $coupon->get_code();
            $content     = apply_filters( 'acfwf_cart_checkout_block_coupon_summary', '', $coupon );

            if ( ! empty( $content ) ) {
                $data[] = compact( 'coupon_code', 'content' );
            }
        }

        return $data;
    }
}
