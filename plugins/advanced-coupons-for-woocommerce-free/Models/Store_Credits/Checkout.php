<?php
namespace ACFWF\Models\Store_Credits;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Store_Credit_Entry;
use Automattic\WooCommerce\Utilities\NumberUtil;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Store Credits Checkout module.
 *
 * @since 4.0
 */
class Checkout extends Base_Model implements Model_Interface, Initializable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that houses the model name to be used when calling publicly.
     *
     * @since 4.0
     * @access private
     * @var string
     */
    private $_model_name = 'Store_Credits_Checkout';

    /**
     * Property that holds the override data for the store credit dynamic coupon.
     *
     * @since 4.5.2
     * @access private
     * @var null|array
     */
    private $_coupon_override_data = null;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $main_plugin->add_to_all_plugin_models( $this, $this->_model_name );
        $main_plugin->add_to_public_models( $this, $this->_model_name );
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout displays.
    |--------------------------------------------------------------------------
     */

    /**
     * Display store credits redeem form in checkout page.
     *
     * @deprecated 4.5.7
     *
     * @since 4.0
     * @since 4.2 Hide when customer has no balance and setting is on.
     * @access public
     */
    public function display_store_credits_checkout_redeem_form() {
        wc_deprecated_function( __METHOD__, '1.8.4' );
    }

    /**
     * Display store credits discount row.
     *
     * @since 4.0
     * @access public
     */
    public function display_store_credits_discount_row() {
        if ( ! $this->is_allow_store_credits() ) {
            return;
        }

        $sc_data = \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null );

        // skip displaying discount if not yet applied.
        if ( ! $sc_data ) {
            return;
        }

        // detect option : `Store credit apply type` is set to `Apply store credit on checkout before tax and shipping.`
        // if the option is active then the checkout should clear store credit session.
        $store_credit_apply_type_option = get_option( Plugin_Constants::STORE_CREDIT_APPLY_TYPE, 'coupon' );
        if ( 'coupon' === $store_credit_apply_type_option ) {
            $this->clear_store_credit_session();
            return;
        }

        $user_balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id() ) );

        // load store credit discount row template.
        $this->_helper_functions->load_template(
            'acfw-store-credits/checkout-discount.php',
            array(
                'user_balance' => $user_balance,
                'amount'       => $sc_data['amount'] * -1,
                'order_total'  => $sc_data['cart_total'],
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Feature implementation.
    |--------------------------------------------------------------------------
     */

    /**
     * Apply store credits as payment in checkout.
     *
     * @since 4.0
     * @since 4.2.1 Wrap redeem amount with NumberUtil::round function to make sure its precise before comparing it with user's balance.
     * @since 4.5.1 Make method public.
     * @access public
     *
     * @param int   $user_id User ID.
     * @param float $amount Amount of credits to redeem.
     * @return bool|WP_Error True on success, error object on failure.
     */
    public function redeem_store_credits( $user_id, $amount ) {
        if ( ! $this->is_allow_store_credits() ) {
            return new \WP_Error(
                'acfw_cart_items_not_allowed_store_credits',
                __( 'Some of the items in your cart are not allowed to be paid via store credit.', 'advanced-coupons-for-woocommerce-free' ),
                array( 'status' => 400 )
            );
        }

        $amount  = NumberUtil::round( $amount, wc_get_price_decimals() );
        $balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( $user_id ) );

        if ( $amount < 0 || $amount > $balance ) {
            return new \WP_Error(
                'acfw_store_credits_insufficient_balance',
                __( 'The provided amount is invalid or the store credits balance is insufficient.', 'advanced-coupons-for-woocommerce-free' ),
                array(
                    'status' => 400,
                    'amount' => $amount,
                )
            );
        }

        $is_apply_coupon = 'coupon' === get_option( Plugin_Constants::STORE_CREDIT_APPLY_TYPE, 'coupon' );

        // get the cart total to compare the store credits discount based on the store credit apply type setting.
        if ( $is_apply_coupon ) {
            $cart_total  = \WC()->cart->get_subtotal();
            $cart_total += wc_prices_include_tax() ? \WC()->cart->get_subtotal_tax() : 0;
        } else {
            $cart_total = $this->get_cart_total_before_store_credit_discounts();
        }

        if ( 0 >= $cart_total ) {
            $this->clear_store_credit_session();
            return new \WP_Error(
                'acfw_store_credits_zero_cart_total',
                __( 'There was an error trying to apply the store credit discount. Please try again.', 'advanced-coupons-for-woocommerce-free' ),
                array(
                    'status'     => 400,
                    'amount'     => $amount,
                    'cart_total' => $cart_total,
                )
            );
        }

        $amount = min( $amount, $cart_total );

        /**
         * NOTE: When currency switcher is active, the amounts saved in session will always be in the user based currency.
         */
        if ( 0 >= $amount ) {
            $this->clear_store_credit_session();
        } else {
            \WC()->session->set(
                $is_apply_coupon ? Plugin_Constants::STORE_CREDITS_COUPON_SESSION : Plugin_Constants::STORE_CREDITS_SESSION,
                apply_filters(
                    'acfw_store_credits_discount_session',
                    array(
                        'amount'     => $amount,
                        'cart_total' => $cart_total,
                        'currency'   => get_woocommerce_currency(),
                    )
                )
            );
        }

        \WC()->cart->calculate_totals();

        return true;
    }

    /**
     * Get the cart total value before the store credit discount was applied.
     *
     * @since 4.0
     * @access public
     *
     * @return float Cart total.
     */
    public function get_cart_total_before_store_credit_discounts() {
        $sc_data = \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null );

        if ( is_array( $sc_data ) && isset( $sc_data['cart_total'] ) ) {
            return $sc_data['cart_total'];
        }

        return apply_filters( 'acfw_store_credits_get_cart_total', \WC()->cart->get_total( 'edit' ) );
    }

    /*
    |--------------------------------------------------------------------------
    | Apply store credits as coupon.
    |--------------------------------------------------------------------------
     */

    /**
     * Apply store credit discount on cart total calculation.
     *
     * @since 4.5.2
     * @access public
     */
    public function apply_store_credits_as_coupon() {
        $sc_data         = \WC()->session->get( Plugin_Constants::STORE_CREDITS_COUPON_SESSION, null );
        $applied_coupons = \WC()->cart->get_applied_coupons();
        $coupon_code     = $this->get_store_credit_coupon_code();

        // skip if data is not valid.
        if ( ! $sc_data
            || ! isset( $sc_data['amount'] )
            || get_woocommerce_currency() !== $sc_data['currency']
        ) {
            return;
        }

        // detect option : `Store credit apply type` is set to `Apply store credit on checkout after tax and shipping.`
        // if the option is active then the checkout shouldn't apply any store credit discount.
        $store_credit_apply_type_option = get_option( Plugin_Constants::STORE_CREDIT_APPLY_TYPE, 'coupon' );
        if (
            'after_tax' === $store_credit_apply_type_option && // check if the option is active.
            in_array( $coupon_code, $applied_coupons, true ) // check if the coupon is already applied.
        ) {
            \WC()->cart->remove_coupon( $coupon_code );
            $this->clear_store_credit_session();
            return;
        }

        // set coupon data for override.
        $this->_coupon_override_data = apply_filters( 'acfw_before_apply_store_credit_discount', $sc_data, Plugin_Constants::STORE_CREDITS_COUPON_SESSION );

        // don't proceed when coupon already applied on cart.
        if ( \WC()->cart->has_discount( $this->get_store_credit_coupon_code() ) ) {
            return;
        }

        // apply store credit coupon.

        $applied_coupons[] = $this->get_store_credit_coupon_code();

        // Silently apply the store credit discount to prevent the "coupon applied successfully" notice to show up.
        \WC()->cart->set_applied_coupons( $applied_coupons );
    }

    /**
     * Override store credits coupon object data.
     *
     * @since 4.5.2
     * @access public
     *
     * @param bool|array $filter_value Explicit false or list of coupon properties.
     * @param mixed      $data         Coupon code, ID or object.
     * @param \WC_Coupon $coupon       Coupon object.
     */
    public function override_store_credit_coupon_data( $filter_value, $data, $coupon ) {

        // skip if coupon code is not valid.
        if ( $data !== $this->get_store_credit_coupon_code() ) {
            return $filter_value;
        }

        if ( is_null( $this->_coupon_override_data ) && is_object( \WC()->session ) ) {
            $this->_coupon_override_data = apply_filters( 'acfw_before_apply_store_credit_discount', \WC()->session->get( Plugin_Constants::STORE_CREDITS_COUPON_SESSION, null ), Plugin_Constants::STORE_CREDITS_COUPON_SESSION );
        }

        // skip if store credits data is not valid.
        if ( ! is_array( $this->_coupon_override_data ) || ! isset( $this->_coupon_override_data['amount'] ) ) {
            return $filter_value;
        }

        // set store credits amount as coupon amount.
        $filter_value = apply_filters(
            'acfw_override_store_credit_coupon_data',
            array(
                'amount'        => $this->_coupon_override_data['amount'],
                'discount_type' => 'fixed_cart',
            ),
            $this->_coupon_override_data
        );

        // clear property data.
        $this->_coupon_override_data = null;

        return $filter_value;
    }

    /**
     * Apply the custom coupon label in the cart.
     *
     * @since 4.5.2
     * @access public
     *
     * @param string    $label  Coupon label.
     * @param WC_Coupon $coupon Coupon object.
     * @return string Filtered coupon label.
     */
    public function apply_store_credit_discount_coupon_label( $label, $coupon ) {

        if ( $coupon->get_code() === $this->get_store_credit_coupon_code() && $coupon->get_virtual() ) {
            return apply_filters( 'acfw_store_credit_coupon_discount_label', __( 'Store Credit Discount', 'advanced-coupons-for-woocommerce-free' ) );
        }

        return $label;
    }

    /**
     * Clear session data when store credit coupon is removed.
     *
     * @since 4.5.2
     * @access public
     *
     * @param string $coupon_code Coupon Code.
     */
    public function store_credit_coupon_discount_removed( $coupon_code ) {

        if ( $coupon_code !== $this->get_store_credit_coupon_code() ) {
            return;
        }

        $this->clear_store_credit_session();
    }

    /**
     * Get the coupon code displayed for store credits discount.
     *
     * @since 4.5.2
     * @access public
     */
    public function get_store_credit_coupon_code() {
        return apply_filters( 'acfw_store_credit_coupon_code', 'store credit' );
    }

    /**
     * Process order that has a store credit coupon discount applied.
     *
     * @since 4.5.2
     * @access public
     *
     * @param int       $order_id    Order ID.
     * @param array     $posted_data Posted data from checkout form.
     * @param \WC_Order $order       Order object.
     */
    public function process_order_with_store_credit_coupon( $order_id, $posted_data, $order ) {

        $sc_data = \WC()->session->get( Plugin_Constants::STORE_CREDITS_COUPON_SESSION, null );

        // skip if store credits data is not valid.
        if ( ! $sc_data ) {
            return;
        }

        // find coupon item object for store credit.
        $coupon_item = $this->_helper_functions->get_order_applied_coupon_item_by_code( $this->get_store_credit_coupon_code(), $order );

        // skip if there was no store credit coupon found in the order.
        if ( ! $coupon_item instanceof \WC_Order_Item_Coupon ) {
            return;
        }

        // don't proceed creating store credit entry when order status is failed/cancelled.
        if ( $order->has_status( array( 'failed', 'cancelled' ) ) ) {
            $order->remove_coupon( $this->get_store_credit_coupon_code() );
            return;
        }

        $sc_data['amount'] = min( (float) $sc_data['amount'], (float) $coupon_item->get_discount() );
        $amount            = apply_filters( 'acfw_filter_amount', $sc_data['amount'], true );

        // create store credit entry object.
        $store_credit_entry = $this->create_discount_store_credit_entry( $amount, $order );

        // update users cached balance value.
        $new_balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id(), true );

        // save session data as coupon item metadata.
        $sc_data['raw_amount'] = $amount;
        $coupon_item->add_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_COUPON_META, $sc_data );
        $coupon_item->save_meta_data();

        // clear session data.
        $this->clear_store_credit_session();

        do_action( 'acfw_after_order_with_store_credit_coupon', $amount, $new_balance, $order, $store_credit_entry );
    }

    /**
     * Checks if the store credit discount has been removed.
     *
     * @since 4.6.0
     * @access public
     *
     * @return bool The changed notice from the session.
     */
    public function is_store_credit_discount_removed() {
        // Get changed notice from session.
        $changed_notice = \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION_CHANGED_NOTICE, null ) ?? false;

        // Reset store credits session changed notice.
        \WC()->session->set( Plugin_Constants::STORE_CREDITS_SESSION_CHANGED_NOTICE, null );

        return $changed_notice;
    }

    /*
    |--------------------------------------------------------------------------
    | Apply store credits after tax.
    |--------------------------------------------------------------------------
     */

    /**
     * Apply store credit discount on cart total calculation.
     *
     * @since 4.0
     * @since 4.5.2 Update session cart total amount when original cart total amount is changed.
     * @access public
     *
     * @param float $cart_total Cart Total.
     * @return float Filtered cart total.
     */
    public function apply_store_credit_discount( $cart_total ) {
        /**
         * NOTE: When currency converter is active, the cart total and the discount amount is based on user currency.
         *       When the currency is switched by the user, the filter allows the currency converter plugin to convert
         *       the saved discount amount from the previous currency to the new selected currency.
         */
        $sc_data = apply_filters( 'acfw_before_apply_store_credit_discount', \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null ), Plugin_Constants::STORE_CREDITS_SESSION );

        /**
         * Skip when currency in session is different with currency. This means that the newly selected currency hasn't
         * propagated yet. The currency converter plugin will recalculate the cart again and would then correctly apply the discount.
         */
        if ( ! $sc_data || ! isset( $sc_data['amount'] ) || get_woocommerce_currency() !== $sc_data['currency'] ) {
            return $cart_total;
        }

        // Remove the store credit discount when the new calculated cart total value is less then the applied discount value.
        if ( $sc_data['amount'] > $cart_total ) {
            \WC()->session->set( Plugin_Constants::STORE_CREDITS_SESSION_CHANGED_NOTICE, true );
            \WC()->session->set( Plugin_Constants::STORE_CREDITS_SESSION, null );

            // Only trigger on Regular Checkout.
            if ( ! $this->_helper_functions->is_current_request_using_wpjson_wc_api() ) {
                wc_add_notice(
                    __( 'The total of your order changed, please click here to <a class="acfw-reapply-sc-discount" href="#">reapply the store credit discount</a>.', 'advanced-coupons-for-woocommerce-free' ),
                    'error'
                );
            }

            return $cart_total;
        }

        // update session cart total value when the cart total has been changed.
        if ( $sc_data['cart_total'] !== $cart_total ) {
            $sc_data['cart_total'] = $cart_total;
            \WC()->session->set( Plugin_Constants::STORE_CREDITS_SESSION, $sc_data );
        }

        // return the original cart total when viewing it in the cart page.
        return is_cart() ? $cart_total : $cart_total - $sc_data['amount'];
    }

    /**
     * Deduct store credits discount from user's balance when order is processed.
     *
     * @since 4.0
     * @since 4.2 Add hook to trigger actions based on user's new balance after an order was paid with store credits.
     * @access public
     *
     * @param int       $order_id    Order ID.
     * @param array     $posted_data Posted data from checkout form.
     * @param \WC_Order $order       Order object.
     */
    public function deduct_store_credits_discount_from_balance( $order_id, $posted_data, $order ) {
        $sc_data = \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null );

        // don't proceed when store credit session data is not available or when the order status is failed/cancelled.
        if ( ! $sc_data || $order->has_status( array( 'failed', 'cancelled' ) ) ) {
            return;
        }

        /**
         * Save the discount amount the user/order based currency so we don't need to convert them on the backend.
         */
        $meta_data = array(
            'amount'     => $sc_data['amount'], // user currency based amount.
            'raw_amount' => apply_filters( 'acfw_filter_amount', $sc_data['amount'], true ), // site currency based amount.
            'cart_total' => $sc_data['cart_total'],
            'currency'   => $order->get_currency(),
        );

        // save session data as order meta.
        $order->update_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_PAID, $meta_data );
        $order->save_meta_data();

        // recalculate order totals so the store credit payment is deducted from the order total.
        $order->calculate_totals( true );

        $amount = floatval( $meta_data['raw_amount'] );

        // create store credit entry object.
        $store_credit_entry = $this->create_discount_store_credit_entry( $amount, $order );

        // update users cached balance value.
        $new_balance = \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id(), true );

        if ( is_object( \WC()->session ) ) {

            // clear session data.
            $this->clear_store_credit_session();

            // set order ID on session so we can recalculate the order totals when the page is reloaded.
            \WC()->session->set( 'acfw_calculate_order_totals', $order->get_id() );
        }

        do_action( 'acfw_after_order_paid_with_store_credits', $amount, $new_balance, $order, $store_credit_entry );
    }

    /**
     * Deduct store credits discount from user's balance when order is processed via Store API.
     * - This function is required because checkout block uses different hooks to process the order.
     * - Learn more about the change here : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/hooks/actions.md#woocommerce_store_api_checkout_order_processed
     *
     * @since 4.6.0
     * @access public
     *
     * @param \WC_Order $order       Order object.
     */
    public function deduct_store_credits_discount_from_balance_via_store_api( $order ) {
        // store credit as coupon support (beforet tax).
        $this->process_order_with_store_credit_coupon( $order->get_id(), array(), $order );

        // store credit after tax support.
        $this->deduct_store_credits_discount_from_balance( $order->get_id(), array(), $order );
    }

    /**
     * Recalculate the order totals for an order that was paid via Store Credits after the checkout process.
     *
     * @since 4.2.1
     * @access public
     */
    public function recalculate_order_totals_after_checkout_complete() {
        // skip when session object is not available, or when currently viewing the checkout payment page.
        if ( ! \WC()->session || is_checkout_pay_page() ) {
            return;
        }

        $order_id = \WC()->session->get( 'acfw_calculate_order_totals', null );
        $order    = $order_id ? \wc_get_order( $order_id ) : null;

        if ( $order instanceof \WC_Order ) {
            $order->calculate_totals( true );
        }

        \WC()->session->set( 'acfw_calculate_order_totals', null );
    }

    /*
    |--------------------------------------------------------------------------
    | Order review: Order received, email and frontend order view.
    |--------------------------------------------------------------------------
     */

    /**
     * Display store credits discount total in order review page.
     *
     * @since 4.0
     * @since 4.2.1 We're moving the Store Credit order implementation from being applied as a "discount" to applying it
     *              as a payment instead. We will still be keeping this function for backwards compatibility for old orders
     *              that has store credits discounts in them.
     *
     * @access public
     *
     * @param array    $total_rows Order review total rows.
     * @param WC_Order $order     Order object.
     * @return array Filtered order review total rows.
     */
    public function display_order_review_store_credits_discount_total( $total_rows, $order ) {
        $sc_data = $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_META, true );

        if ( ! is_array( $sc_data ) || empty( $sc_data ) ) {
            return $total_rows;
        }

        $filtered_rows = array();

        foreach ( $total_rows as $key => $row ) {
            if ( 'order_total' === $key ) {
                $filtered_rows['acfw_store_credits_discount'] = array(
                    'label' => __( 'Discount (Store Credit)', 'advanced-coupons-for-woocommerce-free' ),
                    'value' => wc_price(
                        $sc_data['amount'] * -1,
                        array( 'currency' => $order->get_currency() )
                    ),
                );
            }

            $filtered_rows[ $key ] = $row;
        }

        return $filtered_rows;
    }

    /**
     * Display store credits discount total in order review page.
     *
     * @since 4.2.1
     * @since 4.5.1.1 Display paid with store credits row as a discount (before total).
     * @access public
     *
     * @param array    $total_rows Order review total rows.
     * @param WC_Order $order     Order object.
     * @return array Filtered order review total rows.
     */
    public function display_order_review_paid_in_store_credits( $total_rows, $order ) {
        $sc_data    = $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_PAID, true );
        $sc_version = $order->get_meta( Plugin_Constants::STORE_CREDITS_VERSION, true );

        if ( ! is_array( $sc_data ) || empty( $sc_data ) ) {
            return $total_rows;
        }

        $filtered_rows = array();

        if ( $sc_version ) {

            foreach ( $total_rows as $key => $row ) {

                if ( 'discount' === $key ) {
                    $discount_total = wc_remove_number_precision( wc_add_number_precision( $order->get_discount_total() ) - wc_add_number_precision( $sc_data['amount'] ) );

                    if ( $discount_total <= 0 ) {
                        continue;
                    }

                    $row['value'] = '-' . wc_price( $discount_total );
                }

                if ( 'order_total' === $key ) {
                    $filtered_rows['acfw_store_credits_discount'] = array(
                        'label' => __( 'Paid with Store Credits', 'advanced-coupons-for-woocommerce-free' ) . ':',
                        'value' => '-' . wc_price(
                            $sc_data['amount'],
                            array( 'currency' => $order->get_currency() )
                        ),
                    );
                }

                $filtered_rows[ $key ] = $row;
            }
        } else {
            $filtered_rows = $this->_old_display_order_review_paid_in_store_credits( $total_rows, $order, $sc_data );
        }

        return $filtered_rows;
    }

    /**
     * Old way of displaying store credits discount total in order review page.
     *
     * NOTE: this is the old way of displaying the store credits payment line in the order review table,
     *       where the store credit payment amount was not yet deducted as a discount in the order.
     *
     * @since 4.5.1.1
     * @access private
     *
     * @param array    $total_rows Order review total rows.
     * @param WC_Order $order      Order object.
     * @param array    $sc_data    Store credits payment data for order.
     * @return array Filtered order review total rows.
     */
    private function _old_display_order_review_paid_in_store_credits( $total_rows, $order, $sc_data ) {
        $filtered_rows = array();
        foreach ( $total_rows as $key => $row ) {
            $filtered_rows[ $key ] = $row;

            if ( 'order_total' === $key ) {
                $filtered_rows['acfw_store_credits_discount'] = array(
                    'label' => __( 'Paid with Store Credits', 'advanced-coupons-for-woocommerce-free' ) . ':',
                    'value' => wc_price(
                        $sc_data['amount'],
                        array( 'currency' => $order->get_currency() )
                    ),
                );

                if ( ! in_array( $order->get_status(), array( 'processing', 'completed', 'refunded' ), true ) && empty( $order->get_date_paid() ) ) {
                    $filtered_rows['acfw_order_pending_amount'] = array(
                        'label' => __( 'Pending Amount', 'advanced-coupons-for-woocommerce-free' ) . ':',
                        'value' => wc_price(
                            $order->get_total() - $sc_data['amount'],
                            array( 'currency' => $order->get_currency() )
                        ),
                    );
                }
            }
        }

        return $filtered_rows;
    }

    /**
     * Update order refund item label when it was refunded via store credits.
     *
     * @since 4.5.1
     * @access public
     *
     * @param array    $total_rows Order review total rows.
     * @param WC_Order $order     Order object.
     * @return array Filtered order review total rows.
     */
    public function update_order_refunded_as_store_credits_labels( $total_rows, $order ) {

        // Get parent order if this is a refund.
        // This is needed because the $order object passed to this filter is the refund order.
        // We need the parent order to get the refund order object.
        if ( $order->get_parent_id() ) {
            $order = wc_get_order( $order->get_parent_id() );
        }

        $refunds = $order->get_refunds();

        foreach ( $total_rows as $key => $total_row ) {

            // skip if the row is not for refunds.
            if ( strpos( $key, 'refund_' ) === false ) {
                continue;
            }

            $refund_index = intval( str_replace( 'refund_', '', $key ) );
            $refund       = $refunds[ $refund_index ] ?? null;

            // Update refund row label when it was refunded via store credits and that the refund reason was not provided.
            if ( $refund && $refund->get_meta( Plugin_Constants::REFUND_ORDER_STORE_CREDIT_ENTRY, true ) && ! $refund->get_reason() ) {
                $total_rows[ $key ]['label'] = __( 'Refund (Store Credits):', 'advanced-coupons-for-woocommerce-free' );
            }
        }

        return $total_rows;
    }

    /**
     * Deduct store credits from the order total (discount behaviour).
     *
     * @since 4.5.1.1
     * @access public
     *
     * @param bool      $and_taxes Calc taxes if true.
     * @param \WC_Order $order Order object.
     */
    public function deduct_store_credits_discount_from_order_total( $and_taxes, $order ) {

        $sc_data = $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_PAID, true );

        // skip if order has no store credits data.
        if ( ! is_array( $sc_data ) || empty( $sc_data ) ) {
            return;
        }

        // apply the changes made by WC during the calculation so we can re-use those data.
        $order->apply_changes();

        $precision_discount = wc_add_number_precision( $order->get_discount_total( 'edit' ) ) + wc_add_number_precision( $sc_data['amount'] );
        $precision_total    = wc_add_number_precision( $order->get_total( 'edit' ) ) - wc_add_number_precision( $sc_data['amount'] );

        $order->set_discount_total( wc_remove_number_precision( $precision_discount ) );
        $order->set_total( wc_remove_number_precision( $precision_total ) );

        // save plugin version of store credits when order is recalculated.
        $order->add_meta_data( Plugin_Constants::STORE_CREDITS_VERSION, Plugin_Constants::VERSION, true );
        $order->save_meta_data();
    }

    /**
     * Re-add the customer's store credits when the order status has been changed to failed or cancelled.
     *
     * @since 4.5.2
     * @access public
     *
     * @param int       $order_id    Order ID.
     * @param string    $prev_status Previous status.
     * @param string    $new_status  New Status.
     * @param \WC_Order $order       Order object.
     */
    public function readd_customer_store_credits_for_failed_orders( $order_id, $prev_status, $new_status, $order ) {

        if ( ! in_array( $new_status, array( 'failed', 'cancelled' ), true ) ) {
            return;
        }

        $sc_paid     = $order->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_PAID, true );
        $coupon_item = $this->_helper_functions->get_order_applied_coupon_item_by_code( $this->get_store_credit_coupon_code(), $order );
        $is_updated  = false;

        if ( is_array( $sc_paid ) && ! empty( $sc_paid ) ) {
            $order->delete_meta_data( Plugin_Constants::STORE_CREDITS_ORDER_PAID );
            $is_updated = true;
        }

        if ( $coupon_item instanceof \WC_Order_Item_Coupon ) {
            $this->_coupon_override_data = $coupon_item->get_meta( Plugin_Constants::STORE_CREDITS_ORDER_COUPON_META, true );
            $order->remove_coupon( $this->get_store_credit_coupon_code() );
            $is_updated = true;
        }

        // skip if nothing was updated.
        if ( ! $is_updated ) {
            return;
        }

        // save order and metadata.
        $order->save();
        $order->save_meta_data();
        $order->calculate_totals( true );

        // create order cancelled store credit entry.
        $this->_create_order_cancelled_store_credit_entry( $order );

        // update users cached balance value.
        \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id(), true );
    }

    /**
     * Allow order to be placed when the order total is zero due to store credits payment (after tax store credits type).
     *
     * @since 4.5.9.1
     * @access public
     *
     * @param bool      $value True if allowed, false otherwise.
     * @param \WC_Order $order Order object.
     * @return bool Filtered value.
     */
    public function allow_placing_order_on_zero_total_with_store_credits( $value, $order ) {

        // Set the 'needs_payment' filter as `false` when the order total deducted with the store credits payment is equal or less than zero.
        $sc_data = \WC()->session ? \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null ) : null;
        if ( $sc_data && isset( $sc_data['amount'] ) && 0 >= $order->get_total() - $sc_data['amount'] ) {
            return false;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX redeem store credits.
     *
     * @since 4.0
     * @access public
     */
    public function ajax_redeem_store_credits() {
        $nonce       = isset( $_POST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) ) : '';
        $message     = ''; // Store notice message.
        $notice_type = 'success'; // Store notice type.

        // Validate request and prepare response.
        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
            $response = array(
                'status'    => 'fail',
                'error_msg' => __( 'Invalid AJAX call', 'advanced-coupons-for-woocommerce-free' ),
            );
        } elseif (
            ! isset( $_POST['wpnonce'] ) ||
            ! wp_verify_nonce( $nonce, 'acfwf_redeem_store_credits_checkout' )
        ) {
            $response = array(
                'status'    => 'fail',
                'error_msg' => __( 'You are not allowed to do this.', 'advanced-coupons-for-woocommerce-free' ),
            );
        } elseif ( ! isset( $_POST['amount'] ) ) {
            $response = array(
                'status'    => 'fail',
                'error_msg' => __( 'Missing required post data', 'advanced-coupons-for-woocommerce-free' ),
            );
        } else {
            $amount = floatval( $_POST['amount'] );
            $check  = $this->redeem_store_credits( get_current_user_id(), $amount );

            if ( is_wp_error( $check ) ) {
                $response = array(
                    'status'    => 'fail',
                    'error_msg' => $check->get_error_message(),
                );
            } else {
                $response = array( 'status' => 'success' );
                $message  = $amount > 0 ?
                    __( 'Store credit discount was applied successfully.', 'advanced-coupons-for-woocommerce-free' ) :
                    __( 'Store credit discount has been removed.', 'advanced-coupons-for-woocommerce-free' );
            }
        }

        // Change notice to error, if there's any.
        if ( 'fail' === $response['status'] ) {
            $message     = $response['error_msg'];
            $notice_type = 'error';
        }

        /**
         * Display store notice message, only if it exists and on regular cart and checkout page only.
         * - We validate is_cart_checkout_block via POST due to `global $post;` is not available in AJAX environment.
         */
        $is_cart_checkout_block = isset( $_POST['is_cart_checkout_block'] ) ? sanitize_text_field( wp_unslash( $_POST['is_cart_checkout_block'] ) ) : false;
        if ( ! empty( $message ) && ! $is_cart_checkout_block ) {
            wc_add_notice( $message, $notice_type );
        }

        // Send response.
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) ); // phpcs:ignore
        $response['message'] = $message;
        echo wp_json_encode( $response );
        wp_die();
    }

    /*
    |--------------------------------------------------------------------------
    | Utility Functions
    |--------------------------------------------------------------------------
     */

    /**
     * Set the coupon override data.
     *
     * @since 4.5.4
     * @access public
     *
     * @param array $data Coupon override data.
     */
    public function set_coupon_override_data( $data ) {
        $this->_coupon_override_data = wp_parse_args(
            $data,
            array(
                'amount'     => 0,
                'cart_total' => 'fixed_cart',
                'currency'   => \get_woocommerce_currency(),
            )
        );
    }

    /**
     * Check if store credits is allowed on checkout.
     *
     * @since 4.0
     * @since 4.5.7 Changed to public access.
     * @access public
     *
     * @return bool True if allowed, false otherwise.
     */
    public function is_allow_store_credits() {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        // Don't allow store credits when "Hide Store Credits when balance is zero" is enabled and the user has no balance.
        if ( 'yes' === get_option( Plugin_Constants::STORE_CREDITS_HIDE_CHECKOUT_ZERO_BALANCE, 'no' ) &&
            \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id() ) <= 0 ) {
            return false;
        }

        $check = true;

        // disallow store credits when advanced gift card product is present in cart.
        foreach ( \WC()->cart->get_cart_contents() as $cart_item ) {
            if ( isset( $cart_item['agcfw_data'] ) ) {
                $check = false;
                break;
            }
        }

        return apply_filters( 'acfw_is_allow_store_credits', $check );
    }

    /**
     * Create discount store credit entry.
     *
     * @since 4.5.2
     * @access public
     *
     * @param float     $amount Store credits amount.
     * @param \WC_Order $order Order object.
     * @return Store_Credit_Entry Store credit entry object.
     */
    public function create_discount_store_credit_entry( float $amount, \WC_Order $order ) {
        $store_credit_entry = new Store_Credit_Entry();

        $store_credit_entry->set_prop( 'amount', $amount );
        $store_credit_entry->set_prop( 'user_id', $order->get_customer_id() );
        $store_credit_entry->set_prop( 'object_id', $order->get_id() );
        $store_credit_entry->set_prop( 'type', 'decrease' );
        $store_credit_entry->set_prop( 'action', 'discount' );

        // save store credit entry to db.
        $store_credit_entry->save();

        return $store_credit_entry;
    }

    /**
     * Create an order cancelled store credit entry.
     *
     * @since 4.5.2
     * @access private
     *
     * @param \WC_Order $order Order object.
     * @return Store_Credit_Entry Store credit entry object.
     */
    private function _create_order_cancelled_store_credit_entry( \WC_Order $order ) {
        $store_credit_entry = new Store_Credit_Entry();
        $amount             = \ACFWF()->Store_Credits_Calculate->get_total_store_credits_discount_for_order( $order->get_id() );

        $store_credit_entry->set_prop( 'amount', $amount );
        $store_credit_entry->set_prop( 'user_id', $order->get_customer_id() );
        $store_credit_entry->set_prop( 'object_id', $order->get_id() );
        $store_credit_entry->set_prop( 'type', 'increase' );
        $store_credit_entry->set_prop( 'action', 'cancelled_order' );

        // save store credit entry to db.
        $store_credit_entry->save();

        return $store_credit_entry;
    }

    /**
     * Clear store credit session data.
     *
     * @since 4.5.2
     * @access public
     */
    public function clear_store_credit_session() {
        \WC()->session->set( Plugin_Constants::STORE_CREDITS_SESSION, null );
        \WC()->session->set( Plugin_Constants::STORE_CREDITS_COUPON_SESSION, null );
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 4.0
     * @access public
     * @implements ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {
        if ( ! $this->_helper_functions->is_module( Plugin_Constants::STORE_CREDITS_MODULE ) ) {
            return;
        }

        add_action( 'wp_ajax_acfwf_redeem_store_credits', array( $this, 'ajax_redeem_store_credits' ) );
    }

    /**
     * Execute Store_Credits class.
     *
     * @since 4.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        if ( ! $this->_helper_functions->is_module( Plugin_Constants::STORE_CREDITS_MODULE ) ) {
            return;
        }

        // store credit as coupon (before tax).
        add_action( 'woocommerce_before_calculate_totals', array( $this, 'apply_store_credits_as_coupon' ) );
        add_filter( 'woocommerce_get_shop_coupon_data', array( $this, 'override_store_credit_coupon_data' ), 10, 3 );
        add_action( 'woocommerce_removed_coupon', array( $this, 'store_credit_coupon_discount_removed' ) );
        add_action( 'woocommerce_checkout_order_processed', array( $this, 'process_order_with_store_credit_coupon' ), 10, 3 );
        add_action( 'woocommerce_cart_totals_coupon_label', array( $this, 'apply_store_credit_discount_coupon_label' ), 10, 2 );

        // store credit after tax.
        add_filter( 'woocommerce_calculated_total', array( $this, 'apply_store_credit_discount' ) );
        add_action( 'woocommerce_checkout_order_processed', array( $this, 'deduct_store_credits_discount_from_balance' ), 10, 3 );
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'display_order_review_store_credits_discount_total' ), 10, 2 );
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'display_order_review_paid_in_store_credits' ), 10, 2 );
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'update_order_refunded_as_store_credits_labels' ), 10, 2 );
        add_action( 'woocommerce_review_order_before_order_total', array( $this, 'display_store_credits_discount_row' ) );
        add_filter( 'woocommerce_order_needs_payment', array( $this, 'allow_placing_order_on_zero_total_with_store_credits' ), 10, 2 );
        add_action( 'woocommerce_after_register_post_type', array( $this, 'recalculate_order_totals_after_checkout_complete' ) );
        add_action( 'woocommerce_order_after_calculate_totals', array( $this, 'deduct_store_credits_discount_from_order_total' ), 10, 2 );

        // Misc tasks.
        add_action( 'woocommerce_after_register_post_type', array( $this, 'recalculate_order_totals_after_checkout_complete' ) );
        add_action( 'woocommerce_order_status_changed', array( $this, 'readd_customer_store_credits_for_failed_orders' ), 10, 4 );

        // Checkout blocks support.
        add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'deduct_store_credits_discount_from_balance_via_store_api' ), 10, 1 );
    }
}
