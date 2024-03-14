<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the Notices module logic.
 * Public Model.
 *
 * @since 4.5.7
 */
class Checkout extends Base_Model implements Model_Interface, Initializable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.5.7
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
     * Register store credit redeem form field in checkout page.
     *
     * @since 4.5.7
     * @access public
     *
     * @param string $field Field HTML.
     * @param string $key Field key.
     * @param array  $args Field args.
     * @param string $value Field value.
     * @return string Filtered field HTML.
     */
    public function register_redeem_store_credit_form_field( $field, $key, $args, $value ) {

        $class_prefix        = apply_filters( 'acfw_store_credits_form_field_class_prefix', 'acfw' );
        $args['button_text'] = $args['button_text'] ?? __( 'Apply', 'advanced-coupons-for-woocommerce-free' );

        ob_start();
        include $this->_constants->VIEWS_ROOT_PATH . 'store-credits/redeem-store-credits-form-checkout-field.php';

        return ob_get_clean();
    }

    /**
     * Display the tabbed box UI in the checkout page (normal).
     *
     * @since 4.5.7
     * @access public
     */
    public function display_checkout_tabbed_box() {

        $accordions = apply_filters( 'acfw_checkout_accordions_data', array() );
        $args       = apply_filters(
            'acfw_checkout_template_args',
            array(
				'id'            => 'acfw-checkout-ui-block',
				'classnames'    => array( 'acfw-checkout-ui-block' ),
                'caret_img_src' => $this->_constants->IMAGES_ROOT_URL . 'caret.svg',
            )
        );

        $args['accordions'] = $accordions;

        $this->_helper_functions->load_template(
            'acfw-checkout.php',
            $args
        );
    }

    /**
     * Register the store credits checkout accordion.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array $accordions Accordions data.
     * @return array Accordions data.
     */
    public function register_store_credit_checkout_accordion( $accordions ) {

        if ( 'yes' === get_option( Plugin_Constants::DISPLAY_STORE_CREDITS_REDEEM_FORM, 'yes' ) && \ACFWF()->Store_Credits_Checkout->is_allow_store_credits() ) {
            $labels       = $this->get_store_credits_redeem_form_labels();
            $accordions[] = array(
                'key'       => 'store_credit',
                'title'     => $labels['toggle_text'],
                'classname' => 'acfw-store-credits-checkout-ui',
            );
        }

        return $accordions;
    }

    /**
     * Display the store credits form in the checkout accordion.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array $data Accordion data.
     */
    public function display_store_credits_form_checkout_accordion( $data ) {
        if ( ! isset( $data['key'] ) || 'store_credit' !== $data['key'] ) {
            return;
        }

        $labels       = $this->get_store_credits_redeem_form_labels();
        $user_balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id() ) );

        $this->_helper_functions->load_template(
            'acfw-store-credits/accordion.php',
            array(
                'user_balance' => $user_balance,
                'labels'       => $labels,
            )
        );
    }

    /**
     * Update the user's store credits amount every time the checkout order review is reloaded.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array $fragments Order review fragments.
     * @return array Filtered order review fragments.
     */
    public function update_store_credits_amount_on_checkout_refresh( $fragments ) {
        $selector     = '.acfw-store-credit-user-balance';
        $user_balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id(), true ) );

        $content = wp_kses_post(
            sprintf(
                /* Translators: %s User store credit balance */
                __( '%s available store credits.', 'advanced-coupons-for-woocommerce-free' ),
                '<strong>' . wc_price( $user_balance ) . '</strong>'
            )
        );

        $fragments[ $selector ] = sprintf( '<p class="acfw-store-credit-user-balance">%s</p>', $content );

        return $fragments;
    }

    /**
     * Get the store credits redeem form labels.
     *
     * @since 4.5.7
     * @access public
     *
     * @return array Store credits redeem form labels.
     */
    public function get_store_credits_redeem_form_labels() {
        return apply_filters(
            'acfw_funnelkit_store_credit_field_labels',
            array(
                'toggle_text'  => __( 'Apply store credit discounts?', 'advanced-coupons-for-woocommerce-free' ),
                'placeholder'  => __( 'Enter amount', 'advanced-coupons-for-woocommerce-free' ),
                /* Translators: %s: Available store credits amount. */
                'balance_text' => __( '%s available store credits.', 'advanced-coupons-for-woocommerce-free' ),
                'instructions' => __( 'Enter the amount of store credits you want to apply as discount for this order.', 'advanced-coupons-for-woocommerce-free' ),
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 4.5.7
     * @access public
     * @implements ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {
    }

    /**
     * Execute Notices class.
     *
     * @since 4.5.7
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {

        add_action( 'woocommerce_checkout_order_review', array( $this, 'display_checkout_tabbed_box' ), 11 );

        if ( $this->_helper_functions->is_module( Plugin_Constants::STORE_CREDITS_MODULE ) ) {
            add_filter( 'woocommerce_form_field_acfw_redeem_store_credit', array( $this, 'register_redeem_store_credit_form_field' ), 10, 4 );
            add_filter( 'acfw_checkout_accordions_data', array( $this, 'register_store_credit_checkout_accordion' ), 20 );
            add_action( 'acfw_checkout_accordion_content', array( $this, 'display_store_credits_form_checkout_accordion' ) );
            add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'update_store_credits_amount_on_checkout_refresh' ) );
        }
    }
}
