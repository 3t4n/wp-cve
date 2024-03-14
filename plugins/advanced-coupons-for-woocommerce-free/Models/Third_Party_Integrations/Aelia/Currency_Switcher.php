<?php
namespace ACFWF\Models\Third_Party_Integrations\Aelia;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Currency_Switcher module.
 *
 * @since 1.4
 */
class Currency_Switcher extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */
    /**
     * Coupon endpoint set.
     *
     * @since 1.4
     * @access private
     * @var string
     */
    private $_coupon_endpoint;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.4
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
     * Get the Aelia Currency Switcher main plugin object.
     *
     * @since 1.4
     * @access public
     *
     * @return WC_Aelia_CurrencySwitcher
     */
    public function aelia_obj() {
        return $GLOBALS['woocommerce-aelia-currencyswitcher'];
    }

    /**
     * Remove all filters related to currency settings when the "acfw_rest_api_context" action hook is triggered.
     *
     * @since 4.0
     * @access public
     *
     * @param WP_REST_Request $request Full details about the request.
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
     * @since 1.4
     * @access public
     *
     * @param float $amount     Amount to convert.
     * @param bool  $is_reverse Convert from user to base currency if true.
     * @param array $settings   Settings.
     * @return float Converted amount.
     */
    public function convert_amount_to_user_selected_currency( $amount, $is_reverse = false, $settings = array() ) {
        $user_currency = isset( $settings['user_currency'] ) ? $settings['user_currency'] : $this->aelia_obj()->get_selected_currency();
        $site_currency = isset( $settings['site_currency'] ) ? $settings['site_currency'] : $this->aelia_obj()->base_currency();

        if ( $site_currency === $user_currency ) {
            return $amount;
        }

        // convert from user to base.
        if ( $is_reverse ) {
            return $this->aelia_obj()->convert( $amount, $user_currency, $site_currency );
        } else { // convert from base to user.
            return $this->aelia_obj()->convert( $amount, $site_currency, $user_currency );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Store Credits
    |--------------------------------------------------------------------------
     */

    /**
     * Save user currency to store credits discount session.
     *
     * @since 4.0
     * @access public
     *
     * @param array $sc_discount Session data.
     * @return array Filtered session data.
     */
    public function save_user_currency_to_store_credits_discount_session( $sc_discount ) {
        $sc_discount['currency'] = $this->aelia_obj()->get_selected_currency();
        return $sc_discount;
    }

    /**
     * Validate the store credits discount currency on cart totals calculation.
     * When the currency saved in session is different from the users currency in Aelia, then we convert the currency from
     * session to the new value, and update the session data as well.
     *
     * @since 4.0
     * @access public
     *
     * @param array  $sc_discount  Session data.
     * @param string $session_name Session option name.
     * @return float Filtered discount amount.
     */
    public function validate_user_currency_on_apply_store_credits_discount( $sc_discount, $session_name ) {
        if ( isset( $sc_discount['currency'] ) && $sc_discount['currency'] !== $this->aelia_obj()->get_selected_currency() ) {

            $amount = $this->aelia_obj()->convert( $sc_discount['amount'], $sc_discount['currency'], $this->aelia_obj()->get_selected_currency() );

            if ( 0 >= $amount ) {
                \WC()->session->set( $session_name, null );
            } else {

                $sc_discount['amount']   = $amount;
                $sc_discount['currency'] = $this->aelia_obj()->get_selected_currency();

                \WC()->session->set( $session_name, $sc_discount );
            }
        }

        return $sc_discount;
    }

    /**
     * Convert the amount for the store credit coupon data.
     *
     * @since 4.5.2
     * @access public
     *
     * @param array $coupon_data Coupon data.
     * @param array $sc_data     Store credit session data.
     * @return array Filtered coupon data.
     */
    public function convert_override_store_credit_coupon_amount( $coupon_data, $sc_data ) {

        if ( isset( $coupon_data['amount'] ) ) {
            $current_currency      = $sc_data['currency'] ?? $this->aelia_obj()->get_selected_currency();
            $coupon_data['amount'] = $this->aelia_obj()->convert( $coupon_data['amount'], $current_currency, $this->aelia_obj()->base_currency() );
        }

        return $coupon_data;
    }

    /**
     * Get discount
     * - Aelia store currency in metadata discount_amount_base_currency
     *
     * @since 4.5.8
     * @access public
     *
     * @param float                 $discount Discount amount.
     * @param \WC_Order_Item_Coupon $item Order item coupon object.
     * @return float
     */
    public function query_report_get_discount( $discount, $item ) {
        $discount_amount_base_currency = $item->get_meta( 'discount_amount_base_currency' );
        return $discount_amount_base_currency ? $discount_amount_base_currency : $discount;
    }

    /**
     * Get discount tax
     * - Aelia store currency in metadata discount_amount_tax_base_currency
     *
     * @since 4.5.8
     * @access public
     *
     * @param float                 $discount_tax Discount amount.
     * @param \WC_Order_Item_Coupon $item Order item coupon object.
     * @return float
     */
    public function query_report_get_discount_tax( $discount_tax, $item ) {
        $discount_amount_tax_base_currency = $item->get_meta( 'discount_amount_tax_base_currency' );
        return $discount_amount_tax_base_currency ? $discount_amount_tax_base_currency : $discount_tax;
    }

    /**
     * Filter query_report_data_order_total for coupon dashboard (discounted order revenue).
     * - Aelia store order total currency in metadata _order_total_base_currency
     *
     * @since 4.5.8
     * @access public
     *
     * @param float                                         $order_total Order total.
     * @param \Automattic\WooCommerce\Admin\Overrides\Order $order WooCommerce Order object.
     * @return float
     */
    public function query_report_data_order_total( $order_total, $order ) {
        $order_total_base_currency = $order->get_meta( '_order_total_base_currency' );
        return $order_total_base_currency ? $order_total_base_currency : $order_total;
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute Currency_Switcher class.
     *
     * @since 1.4
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        if (
            ! $this->_helper_functions->is_plugin_active( 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' ) ||
            ! $this->_helper_functions->is_plugin_active( 'wc-aelia-foundation-classes/wc-aelia-foundation-classes.php' )
        ) {
            return;
        }

        add_action( 'acfw_rest_api_context', array( $this, 'remove_currency_setting_filters' ) );
        add_filter( 'acfw_filter_amount', array( $this, 'convert_amount_to_user_selected_currency' ), 20, 3 );
        add_filter( 'acfw_store_credits_discount_session', array( $this, 'save_user_currency_to_store_credits_discount_session' ) );
        add_filter( 'acfw_before_apply_store_credit_discount', array( $this, 'validate_user_currency_on_apply_store_credits_discount' ), 10, 2 );
        add_filter( 'acfw_override_store_credit_coupon_data', array( $this, 'convert_override_store_credit_coupon_amount' ), 10, 2 );

        // Dashboard.
        add_filter( 'acfw_query_report_data_order_total', array( $this, 'query_report_data_order_total' ), 10, 2 );
        add_filter( 'acfw_query_report_get_discount', array( $this, 'query_report_get_discount' ), 10, 2 );
        add_filter( 'acfw_query_report_get_discount_tax', array( $this, 'query_report_get_discount_tax' ), 10, 2 );
    }

}
