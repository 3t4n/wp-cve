<?php
namespace ACFWF\Models\Third_Party_Integrations;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Interfaces\Initializable_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the WPML_Support module.
 *
 * @since 4.6.0
 */
class WC_Payments extends Base_Model implements Model_Interface, Initializable_Interface {

    /**
     * Property that holds the instance of WooCommerce Payments multicurrency class instance.
     *
     * @since 4.6.0
     * @access private
     * @var \WCPay\MultiCurrency\MultiCurrency
     */
    private $_multicurrency;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.6.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Remove all filters related to currency settings when the "acfw_rest_api_context" action hook is triggered.
     *
     * @since 4.6.0
     * @access public
     *
     * @param \WP_REST_Request $request Full details about the request.
     */
    public function remove_currency_setting_filters( $request ) {
        if ( $request->get_header( 'X-ACFW-Context' ) === 'admin' ) {
            remove_all_filters( 'pre_option_woocommerce_currency' );
            remove_all_filters( 'woocommerce_currency' );
            remove_all_filters( 'pre_option_woocommerce_price_thousand_sep' );
            remove_all_filters( 'pre_option_woocommerce_price_decimal_sep' );
            remove_all_filters( 'pre_option_woocommerce_price_num_decimals' );
            remove_all_filters( 'pre_option_woocommerce_currency_pos' );
            remove_all_filters( 'acfw_filter_amount' );
        }
    }

    /**
     * Convert amount to from base currency to user selected currency (or reverse).
     *
     * @since 4.6.0
     * @access public
     *
     * @param float $amount Amount to convert.
     * @param bool  $is_reverse Convert from user to base currency if true.
     * @return float Converted amount.
     */
    public function convert_amount_to_user_selected_currency( $amount, $is_reverse = false ) {
        if ( $this->_multicurrency->get_default_currency() === $this->_multicurrency->get_selected_currency() ) {
            return $amount;
        }

        $from_currency = $is_reverse ? $this->_multicurrency->get_selected_currency() : $this->_multicurrency->get_default_currency();
        $to_currency   = $is_reverse ? $this->_multicurrency->get_default_currency() : $this->_multicurrency->get_selected_currency();

        try {
            $converted_amount = $this->_multicurrency->get_raw_conversion( $amount, $to_currency->get_code(), $from_currency->get_code() );
        } catch ( \Exception $e ) {
            $converted_amount = $amount;
        }

        return $converted_amount;
    }

    /*
    |--------------------------------------------------------------------------
    | Store Credits
    |--------------------------------------------------------------------------
    */

    /**
     * Save user currency to store credits discount session.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array $sc_discount Session data.
     * @return array Filtered session data.
     */
    public function save_user_currency_to_store_credits_discount_session( $sc_discount ) {
        $sc_discount['currency'] = $this->_multicurrency->get_selected_currency()->get_code();
        return $sc_discount;
    }

    /**
     * Validate the store credits discount currency on cart totals calculation.
     * When the currency saved in session is different from the users currency in WC Payments, then we convert the currency from
     * session to the new value, and update the session data as well.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array  $sc_discount Session data.
     * @param string $session_name Session option name.
     * @return float Filtered discount amount.
     */
    public function validate_user_currency_on_apply_store_credits_discount( $sc_discount, $session_name ) {
        $to_currency   = $this->_multicurrency->get_selected_currency()->get_code();
        $from_currency = $sc_discount['currency'] ?? false;

        if ( $from_currency && $to_currency !== $from_currency ) {

            $amount = $this->_multicurrency->get_raw_conversion( $sc_discount['amount'], $to_currency, $from_currency );

            if ( 0 >= $amount ) {
                \WC()->session->set( $session_name, null );
            } else {

                $sc_discount['amount']   = $amount;
                $sc_discount['currency'] = $to_currency;

                \WC()->session->set( $session_name, $sc_discount );
            }
        }

        return $sc_discount;
    }

    /**
     * Convert the amount for the store credit coupon data.
     *
     * @since 4.6.0
     * @access public
     *
     * @param array $coupon_data Coupon data.
     * @param array $sc_data     Store credit session data.
     * @return array Filtered coupon data.
     */
    public function convert_override_store_credit_coupon_amount( $coupon_data, $sc_data ) {
        $from_currency = $sc_data['currency'] ?? $this->_multicurrency->get_selected_currency()->get_code();
        $to_currency   = $this->_multicurrency->get_default_currency()->get_code();

        if ( isset( $coupon_data['amount'] ) && $from_currency !== $to_currency ) {
            $coupon_data['amount'] = $this->_multicurrency->get_raw_conversion( $coupon_data['amount'], $to_currency, $from_currency );
        }

        return $coupon_data;
    }

    /*
    |--------------------------------------------------------------------------
    | BOGO Deals
    |--------------------------------------------------------------------------
     */

    /**
     * Convert the BOGO get item (discounted) price from user to store currency.
     *
     * @since 4.6.0
     * @access public
     *
     * @param string $new_price BOGO Deal get item new price.
     * @return string Filtered new price.
     */
    public function convert_bogo_get_item_price_to_store_currency( $new_price ) {
        if ( $this->_multicurrency->get_default_currency()->get_code() === $this->_multicurrency->get_selected_currency()->get_code() ) {
            return $new_price;
        }

        return $this->_multicurrency->get_raw_conversion(
            $new_price,
            $this->_multicurrency->get_default_currency()->get_code(),
            $this->_multicurrency->get_selected_currency()->get_code(),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
     */

    /**
     * Get discount.
     *
     * @since 4.6.0
     * @access public
     *
     * @param float                 $discount Discount amount.
     * @param \WC_Order_Item_Coupon $item Order item coupon object.
     * @param \WC_Order             $order WooCommerce Order object.
     * @return float
     */
    public function query_report_get_discount( $discount, $item, $order ) {
        return $this->convert_amount_to_default_based_on_order_data( $discount, $order );
    }

    /**
     * Get discount tax.
     *
     * @since 4.6.0
     * @access public
     *
     * @param float                 $discount_tax Discount amount.
     * @param \WC_Order_Item_Coupon $item Order item coupon object.
     * @param \WC_Order             $order WooCommerce Order object.
     * @return float
     */
    public function query_report_get_discount_tax( $discount_tax, $item, $order ) {
        return $this->convert_amount_to_default_based_on_order_data( $discount_tax, $order );
    }

    /**
     * Filter query_report_data_order_total for coupon dashboard (discounted order revenue).
     *
     * @since 4.6.0
     * @access public
     *
     * @param float                                         $order_total Order total.
     * @param \Automattic\WooCommerce\Admin\Overrides\Order $order WooCommerce Order object.
     * @return float
     */
    public function query_report_data_order_total( $order_total, $order ) {
        return $this->convert_amount_to_default_based_on_order_data( $order_total, $order );
    }

    /**
     * Convert an amount to the default currency based on the order data.
     *
     * @since 4.6.0
     * @access public
     *
     * @param float     $amount Amount to convert.
     * @param \WC_Order $order WooCommerce Order object.
     */
    public function convert_amount_to_default_based_on_order_data( $amount, $order ) {
        $default_currency       = $this->_multicurrency->get_default_currency();
        $order_default_currency = $order->get_meta( '_wcpay_multi_currency_order_default_currency' );
        $to_currency            = null;

        // Skip if the amount is negative, or the order currency is the same as the default currency, or the order default currency is not set.
        if ( 0 > $amount || $order->get_currency() === $default_currency->get_code() || ! $order_default_currency ) {
            return $amount;
        }

        $enabled_currencies = $this->_multicurrency->get_enabled_currencies();
        $from_currency      = isset( $enabled_currencies[ $order->get_currency() ] ) ? $enabled_currencies[ $order->get_currency() ] : null;
        $to_currency        = isset( $enabled_currencies[ $order_default_currency ] ) ? $enabled_currencies[ $order_default_currency ] : null;
        $from_currency_rate = $order->get_meta( '_wcpay_multi_currency_order_exchange_rate' ) ?? $from_currency->get_rate();

        // Convert the amount from the order's currency to the default currency saved in the order.
        if ( $from_currency && 0 < $from_currency_rate ) {
            $amount = $amount * ( $to_currency->get_rate() / $from_currency_rate );
        }

        // When the default currency saved in the order's meta is different from the actual default currency set, the we convert again to the actual default currency.
        if ( $default_currency->get_code() !== $to_currency->get_code() ) {
            $amount = $this->_multicurrency->get_raw_conversion( $amount, $default_currency->get_code(), $to_currency->get_code() );
        }

        return $amount;
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Initialize WC_Payments class.
     */
    public function initialize() {
        if ( ! $this->_helper_functions->is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ) {
            return;
        }

        if ( function_exists( 'WC_Payments_Multi_Currency' ) ) {
            $this->_multicurrency = WC_Payments_Multi_Currency();
        }

        add_action( 'acfw_rest_api_context', array( $this, 'remove_currency_setting_filters' ) );
        add_filter( 'acfw_filter_amount', array( $this, 'convert_amount_to_user_selected_currency' ), 10, 2 );
        add_filter( 'acfw_store_credits_discount_session', array( $this, 'save_user_currency_to_store_credits_discount_session' ) );
        add_filter( 'acfw_before_apply_store_credit_discount', array( $this, 'validate_user_currency_on_apply_store_credits_discount' ), 10, 2 );
        add_filter( 'acfw_override_store_credit_coupon_data', array( $this, 'convert_override_store_credit_coupon_amount' ), 10, 2 );
        add_filter( 'acfw_bogo_get_item_new_price', array( $this, 'convert_bogo_get_item_price_to_store_currency' ) );

        // Dashboard.
        add_filter( 'acfw_query_report_data_order_total', array( $this, 'query_report_data_order_total' ), 10, 2 );
        add_filter( 'acfw_query_report_get_discount', array( $this, 'query_report_get_discount' ), 10, 3 );
        add_filter( 'acfw_query_report_get_discount_tax', array( $this, 'query_report_get_discount_tax' ), 10, 3 );
        add_filter( 'acfw_query_report_extra_discount', array( $this, 'query_report_get_discount' ), 10, 3 );
    }

    /**
     * Execute WC_Payments class.
     *
     * @since 4.6.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
    }
}
