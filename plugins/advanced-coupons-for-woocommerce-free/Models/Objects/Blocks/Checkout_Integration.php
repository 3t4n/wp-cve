<?php
namespace ACFWF\Models\Objects\Blocks;

use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Models\Objects\Vite_App;
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 *
 * @since 4.5.8
 */
class Checkout_Integration extends Base_Model implements IntegrationInterface {
    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * The name of the integration.
     * This is used internally to identify the integration and should be unique.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string
     */
    public function get_name() {
        return 'acfwf-wc-checkout-block';
    }

    /**
     * When called invokes any initialization/setup for the integration.
     *
     * @since 4.5.8
     * @access public
     */
    public function initialize() {
        $this->register_scripts();
    }

    /**
     * Register Scripts and Styles.
     * - This is where you will register custom scripts for your block
     *
     * @since 4.5.8
     * @access private
     */
    private function register_scripts() {
        $vite_app = new Vite_App(
            'acfwf-wc-checkout-block-integration', // Don't forget to register this handle in the get_script_handles() or get_editor_script_handles() method.
            'packages/acfwf-checkout-block/index.tsx',
            array()
        );
        $vite_app->register();
    }

    /**
     * Returns an array of script handles to enqueue in the frontend context.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string[]
     */
    public function get_script_handles() {
        return array(
            'acfwf-wc-checkout-block-integration',
        );
    }

    /**
     * Returns an array of script handles to enqueue in the editor context.
     *
     * @since 4.5.8
     * @access public
     *
     * @return string[]
     */
    public function get_editor_script_handles() {
        return array();
    }

    /**
     * An array of key, value pairs of data made available to the block on the client side.
     * - To access this data in the block see code sample here : https://github.com/agungsundoro/woocommerce-blocks-test/blob/0a520112707e7e09af67e3fbbbb8876a846e6c56/packages/acfw-wc-blocks/cart/data.tsx#L19-L26
     *
     * @since 4.5.8
     * @access public
     *
     * @return array
     */
    public function get_script_data() {
        $store_credit_apply_type = get_option( Plugin_Constants::STORE_CREDIT_APPLY_TYPE, 'coupon' );

        /**
         * Detect if setting changes from `Apply store credit on checkout after tax and shipping.` to `Apply store credit on checkout before tax and shipping.`
         * - This is to clear the session if the setting changes.
         */
        if ( 'coupon' === $store_credit_apply_type && \WC()->session && \WC()->session->get( Plugin_Constants::STORE_CREDITS_SESSION, null ) ) {
            \ACFWF()->Store_Credits_Checkout->clear_store_credit_session();
        }

        // Return data.
        return array(
            'store_credits' => array(
                'apply_type'                         => $store_credit_apply_type,
                'labels'                             => \ACFWF()->Checkout->get_store_credits_redeem_form_labels(),
                'button_text'                        => __( 'Apply', 'advanced-coupons-for-woocommerce-free' ),
                'pay_with_store_credits_text'        => __( 'Pay with Store Credits', 'advanced-coupons-for-woocommerce-free' ),
                'notice_store_credits_text'          => __( 'The total of your order changed, please click here to <a class="acfw-reapply-sc-discount" href="#">reapply the store credit discount</a>.', 'advanced-coupons-for-woocommerce-free' ),
                'redeem_nonce'                       => wp_create_nonce( 'acfwf_redeem_store_credits_checkout' ),
                'hide_store_credits_on_zero_balance' => get_option( Plugin_Constants::STORE_CREDITS_HIDE_CHECKOUT_ZERO_BALANCE, 'no' ),
                'display_store_credits_redeem_form'  => get_option( Plugin_Constants::DISPLAY_STORE_CREDITS_REDEEM_FORM, 'yes' ), // Display store credits redeem form.
                'store_credits_module'               => \ACFWF()->Helper_Functions->is_module( Plugin_Constants::STORE_CREDITS_MODULE ), // Check if store credits module is enabled.
            ),
            'caret_img_src' => $this->_constants->IMAGES_ROOT_URL . 'caret.svg',
        );
    }
}
