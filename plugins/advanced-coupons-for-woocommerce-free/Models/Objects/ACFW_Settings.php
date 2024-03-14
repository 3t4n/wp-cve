<?php
namespace ACFWF\Models\Objects;

use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the ACFW Settings module logic.
 *
 * @since 1.0
 */
class ACFW_Settings extends \WC_Settings_Page {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * ACFW_Settings constructor.
     *
     * @since 1.0
     * @access public
     *
     * @param Plugin_Constants $constants        Plugin constants object.
     * @param Helper_Functions $helper_functions Helper functions object.
     */
    public function __construct( Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;
        $this->id                = 'acfw_settings';
        $this->label             = __( 'Advanced Coupons', 'advanced-coupons-for-woocommerce-free' );
    }

    /**
     * Get sections.
     *
     * @since 1.0
     * @access public
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            ''                              => __( 'Modules', 'advanced-coupons-for-woocommerce-free' ),
            'acfw_setting_general_section'  => __( 'General', 'advanced-coupons-for-woocommerce-free' ),
            'acfw_setting_checkout_section' => __( 'Checkout', 'advanced-coupons-for-woocommerce-free' ),
        );

        if ( $this->_helper_functions->is_module( Plugin_Constants::BOGO_DEALS_MODULE ) ) {
            $sections['acfw_setting_bogo_deals_section'] = __( 'BOGO Deals', 'advanced-coupons-for-woocommerce-free' );
        }

        if ( $this->_helper_functions->is_module( Plugin_Constants::SCHEDULER_MODULE ) ) {
            $sections['acfw_setting_scheduler_section'] = __( 'Scheduler', 'advanced-coupons-for-woocommerce-free' );
        }

        if ( $this->_helper_functions->is_module( Plugin_Constants::ROLE_RESTRICT_MODULE ) ) {
            $sections['acfw_setting_role_restrictions_section'] = __( 'Role Restrictions', 'advanced-coupons-for-woocommerce-free' );
        }

        if ( $this->_helper_functions->is_module( Plugin_Constants::URL_COUPONS_MODULE ) ) {
            $sections['acfw_setting_url_coupons_section'] = __( 'URL Coupons', 'advanced-coupons-for-woocommerce-free' );
        }

        if ( $this->_helper_functions->is_module( Plugin_Constants::STORE_CREDITS_MODULE ) ) {
            $sections['acfw_setting_store_credits_section'] = __( 'Store Credits', 'advanced-coupons-for-woocommerce-free' );
        }

        $sections['acfw_setting_advanced_section'] = __( 'Advanced', 'advanced-coupons-for-woocommerce-free' );
        $sections['acfw_setting_help_section']     = __( 'Help', 'advanced-coupons-for-woocommerce-free' );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }

    /**
     * Output the settings.
     *
     * @since 1.0
     * @access public
     */
    public function output() {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }

    /**
     * Save settings.
     *
     * @since 1.0
     * @access public
     */
    public function save() {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }

    /**
     * Get settings array.
     *
     * @since 1.0
     * @access public
     *
     * @param  string $current_section Current settings section.
     * @return array  Array of options for the current setting section.
     */
    public function get_settings( $current_section = '' ) {
        $module = '';

        switch ( $current_section ) {

            case 'acfw_setting_help_section':
                $settings = apply_filters( 'acfw_setting_help_section_options', $this->_get_help_section_options() );
                break;

            case 'acfw_setting_bogo_deals_section':
                $module   = Plugin_Constants::BOGO_DEALS_MODULE;
                $settings = apply_filters( 'acfw_setting_bogo_deals_options', $this->_get_bogo_deals_section_options() );
                break;

            case 'acfw_setting_general_section':
                $settings = apply_filters( 'acfw_setting_general_options', $this->_get_general_section_options() );
                break;

            case 'acfw_setting_checkout_section':
                $settings = apply_filters( 'acfw_setting_checkout_options', $this->_get_checkout_section_options() );
                break;

            case 'acfw_setting_scheduler_section':
                $module   = Plugin_Constants::SCHEDULER_MODULE;
                $settings = apply_filters( 'acfw_setting_scheduler_options', $this->_get_scheduler_section_options() );
                break;

            case 'acfw_setting_url_coupons_section':
                $module   = Plugin_Constants::URL_COUPONS_MODULE;
                $settings = apply_filters( 'acfw_setting_url_coupons_options', $this->_get_url_coupons_section_options() );
                break;

            case 'acfw_setting_role_restrictions_section':
                $module   = Plugin_Constants::ROLE_RESTRICT_MODULE;
                $settings = apply_filters( 'acfw_setting_role_restrictions_options', $this->_get_role_restrictions_section_options() );
                break;

            case 'acfw_setting_store_credits_section':
                $module   = Plugin_Constants::STORE_CREDITS_MODULE;
                $settings = apply_filters( 'acfw_setting_store_credits_options', $this->_get_store_credits_section_options() );
                break;

            case 'acfw_setting_advanced_section':
                $settings = apply_filters( 'acfw_setting_advanced_options', $this->_get_advanced_section_options() );
                break;

            case 'acfw_setting_modules_section':
            default:
                $settings = apply_filters( 'acfw_setting_modules_section_options', $this->_get_modules_section_options() );
                break;
        }

        // if module is disabled then set settings to empty array.
        if ( $module && ! $this->_helper_functions->is_module( $module ) ) {
            $settings = array();
        }

        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
    }

    /*
    |--------------------------------------------------------------------------------------------------------------
    | Section Settings
    |--------------------------------------------------------------------------------------------------------------
     */

    /**
     * Get modules section options.
     *
     * @since 1.0
     * @access private
     *
     * @return array
     */
    private function _get_modules_section_options() {
        $modules = apply_filters(
            'acfw_modules_settings',
            array(

                array(
                    'title'   => __( 'URL Coupons', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Create apply links for your URLs for use in email campaigns, social media sharing, etc.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::URL_COUPONS_MODULE,
                    'default' => 'yes',
                ),

                array(
                    'title'   => __( 'Role Restrictions', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Restrict coupons to be used by certain user roles only.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::ROLE_RESTRICT_MODULE,
                    'default' => 'yes',
                ),

                array(
                    'title'   => __( 'Cart Conditions', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Create conditions that must be satisfied before a coupon is allowed to be applied.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::CART_CONDITIONS_MODULE,
                    'default' => 'yes',
                ),

                array(
                    'title'   => __( 'BOGO Deals', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Buy one, get one style deals where you can set conditions if a customer has a certain amount of a product in the cart, they get another product for a special deal.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::BOGO_DEALS_MODULE,
                    'default' => 'yes',
                ),

                array(
                    'title'   => __( 'Store Credits', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Adds a store credit account to your customer’s profile that you can credit for them to use toward purchases. Store credits can be used during checkout towards payment.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::STORE_CREDITS_MODULE,
                    'default' => 'yes',
                ),

                array(
                    'title'   => __( 'Scheduler', 'advanced-coupons-for-woocommerce-free' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Schedule exact start and end dates/times for your coupons. Choose specific days/times your coupon is valid (premium).', 'advanced-coupons-for-woocommerce-free' ),
                    'id'      => Plugin_Constants::SCHEDULER_MODULE,
                    'default' => 'yes',
                ),

            )
        );

        $settings = array_merge(
            array(
                array(
                    'title' => __( 'Modules', 'advanced-coupons-for-woocommerce-free' ),
                    'type'  => 'title',
                    'desc'  => __( "You can control which parts of the Advanced Coupons interface are shown in the Coupon edit screen. It can be helpful to users and better for overall performance to turn off features that aren't in use if you or your staff don't use them.", 'advanced-coupons-for-woocommerce-free' ),
                    'id'    => 'acfw_modules_main_title',
                ),
            ),
            $modules,
            array(
                array(
                    'type' => 'sectionend',
                    'id'   => 'acfw_modules_sectionend',
                ),
            )
        );

        return apply_filters( 'acfw_modules_section_options', $settings );
    }

    /**
     * Get BOGO deals section options.
     *
     * @since 1.0
     * @access private
     *
     * @return array
     */
    private function _get_bogo_deals_section_options() {
        return array(

            array(
                'title' => __( 'BOGO Deals', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_bogo_deals_main_title',
            ),

            array(
                'title'       => __( 'Global notice message', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Message of the notice to show customer when they have triggered the BOGO deal but the "Apply products" are not present in the cart.', 'advanced-coupons-for-woocommerce-free' ),
                'desc_tip'    => __( 'Custom variables available: {acfw_bogo_remaining_deals_quantity} to display the count of product deals that can be added to the cart, and {acfw_bogo_coupon_code} for displaying the coupon code that offered the deal.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::BOGO_DEALS_NOTICE_MESSAGE,
                'placeholder' => __( 'Your current cart is eligible to redeem deals', 'advanced-coupons-for-woocommerce-free' ),
                'css'         => 'width: 500px; display: block;',
            ),

            array(
                'title'       => __( 'Global notice button text', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'id'          => Plugin_Constants::BOGO_DEALS_NOTICE_BTN_TEXT,
                'placeholder' => __( 'View Deals', 'advanced-coupons-for-woocommerce-free' ),
                'css'         => 'width: 500px; display: block;',
            ),

            array(
                'title'       => __( 'Global notice button URL', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'url',
                'id'          => Plugin_Constants::BOGO_DEALS_NOTICE_BTN_URL,
                'placeholder' => get_permalink( wc_get_page_id( 'shop' ) ),
                'css'         => 'width: 500px; display: block;',
            ),

            array(
                'title'   => __( 'Global notice type', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'select',
                'id'      => Plugin_Constants::BOGO_DEALS_NOTICE_TYPE,
                'options' => array(
                    'notice'  => __( 'Info', 'advanced-coupons-for-woocommerce-free' ),
                    'success' => __( 'Success', 'advanced-coupons-for-woocommerce-free' ),
                    'error'   => __( 'Error', 'advanced-coupons-for-woocommerce-free' ),
                ),
            ),

            array(
                'title'    => __( 'Maximum number of BOGO Deal coupons allowed to be applied in the cart', 'advanced-coupons-for-woocommerce-free' ),
                'type'     => 'number',
                'id'       => $this->_constants->ALLOWED_BOGO_COUPONS_COUNT,
                'desc'     => __( 'This is the number of BOGO Deal coupons that can be applied to the cart. If the customer tries to add more than this number, they will get an error message.', 'advanced-coupons-for-woocommerce-free' ),
                'desc_tip' => __( 'By default the feature is limited to only allow 1 BOGO Deal coupon per order to ensure smooth server performance and a straightforward customer experience. You have the flexibility to increase this limit if needed, but be mindful that higher limits may impact server resources, especially during peak traffic.', 'advanced-coupons-for-woocommerce-free' ),
                'min'      => 1,
                'default'  => 1,
            ),

            array(
                'type' => 'acfw_bogo_deals_custom_js',
                'id'   => 'acfw_Bogo_deals_custom_js',
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_bogo_deals_sectionend',
            ),

        );
    }

    /**
     * Get general section options.
     *
     * @since 1.0.0
     * @access private
     *
     * @return array
     */
    private function _get_general_section_options() {
        return array(

            array(
                'title' => __( 'General', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_general_main_title',
            ),

            array(
                'title'       => __( 'Default coupon category', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'select',
                'desc_tip'    => __( 'If a coupon is saved without specifying a category, give it this default category. This is useful when third-party tools create coupons or for coupons created via API.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::DEFAULT_COUPON_CATEGORY,
                'class'       => 'wc-enhanced-select',
                'placeholder' => __( 'Select a category', 'advanced-coupons-for-woocommerce-free' ),
                'taxonomy'    => Plugin_Constants::COUPON_CAT_TAXONOMY,
                'options'     => $this->_helper_functions->get_all_coupon_categories_as_options(),
            ),

            array(
                'title'    => __( 'Always use regular price', 'advanced-coupons-for-woocommerce-free' ),
                'type'     => 'radio',
                'desc_tip' => __( 'Always ensure the Regular Price is used and ignore the Sale Price if present.', 'advanced-coupons-for-woocommerce-free' ),
                'id'       => Plugin_Constants::ALWAYS_USE_REGULAR_PRICE,
                'default'  => 'no',
                'options'  => array(
                    'no'        => __( 'Use the sale price, if present (default).', 'advanced-coupons-for-woocommerce-free' ),
                    'yes'       => __( 'Always use the regular price, but only for products discounted via BOGO and Add Products coupons.', 'advanced-coupons-for-woocommerce-free' ),
                    'all_valid' => __( 'Always use the regular price, for all coupon types.', 'advanced-coupons-for-woocommerce-free' ),
                ),
            ),

            array(
                'title'   => __( 'Automatically remove coupons for failed/cancelled orders', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'checkbox',
                'desc'    => __( 'If checked, removes coupons from orders that have failed or been cancelled.', 'advanced-coupons-for-woocommerce-free' ),
                'id'      => Plugin_Constants::REMOVE_COUPONS_FOR_FAILED_ORDERS,
                'default' => 'no',
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_general_sectionend',
            ),

        );
    }

    /**
     * Get checkout section options.
     *
     * @since 4.5.7
     * @access private
     *
     * @return array
     */
    private function _get_checkout_section_options() {
        return array(
            array(
                'title' => __( 'Checkout', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_checkout_main_title',
            ),
            array(
                'title'   => __( 'Display store credits redeem form', 'advanced-coupons-for-woocommerce-free' ),
                'id'      => Plugin_Constants::DISPLAY_STORE_CREDITS_REDEEM_FORM,
                'type'    => 'checkbox',
                'desc'    => __( 'When checked, the store credits redeem form will be displayed on the checkout page.', 'advanced-coupons-for-woocommerce-free' ),
                'default' => 'yes',
            ),
        );
    }

    /**
     * Get scheduler section options.
     *
     * @since 4.5
     * @access private
     *
     * @return array
     */
    private function _get_scheduler_section_options() {
        return array(

            array(
                'title' => __( 'Scheduler', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_scheduler_main_title',
            ),

            array(
                'title'       => __( 'Schedule Start Error Message (global)', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Optional. Message that will be displayed when the coupon being applied hasnt started yet. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::SCHEDULER_START_ERROR_MESSAGE,
                'css'         => 'width: 500px; display: block;',
                'placeholder' => __( 'This coupon has not started yet.', 'advanced-coupons-for-woocommerce-free' ),
            ),

            array(
                'title'       => __( 'Schedule Expire Error Message (global)', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Optional. Message that will be displayed when the coupon being applied has already expired. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::SCHEDULER_EXPIRE_ERROR_MESSAGE,
                'css'         => 'width: 500px; display: block;',
                'placeholder' => __( 'This coupon has expired.', 'advanced-coupons-for-woocommerce-free' ),
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_scheduler_sectionend',
            ),
        );
    }

    /**
     * Get URL coupons section options.
     *
     * @since 1.0
     * @access private
     *
     * @return array
     */
    private function _get_url_coupons_section_options() {
        $url_prefix  = get_option( Plugin_Constants::COUPON_ENDPOINT, 'coupon' );
        $coupon_name = __( '[coupon-name]', 'advanced-coupons-for-woocommerce-free' );
        $cart_url    = wc_get_cart_url();

        return array(

            array(
                'title' => __( 'URL Coupons', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_url_coupons_main_title',
            ),

            array(
                'title'       => __( 'URL prefix', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'desc'        => sprintf(
                    /* Translators: %s: Coupon url example. */
                    __( 'The prefix to be used before the coupon code. Eg. %s', 'advanced-coupons-for-woocommerce-free' ),
                    home_url( $url_prefix . '/' . $coupon_name )
                ),
                'id'          => Plugin_Constants::COUPON_ENDPOINT,
                'default'     => 'coupon', // Don't translate, its an endpoint.
                'placeholder' => 'coupon',
            ),

            array(
                'title'       => __( 'Redirect to URL after applying coupon', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'customurl',
                'desc'        => __( "Optional. This will redirect the user to the provided URL after the has been attempted to be applied. You can also pass query args to the URL for the following variables: {acfw_coupon_code}, {acfw_coupon_is_applied} or {acfw_coupon_error_message} and they will be replaced with proper data. Eg. ?foo={acfw_coupon_error_message}, then test the 'foo' query arg to get the message if there is one.", 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::AFTER_APPLY_COUPON_REDIRECT_URL_GLOBAL,
                'placeholder' => $cart_url,
                'css'         => 'width: 500px; display: block;',
            ),

            array(
                'title'       => __( 'Redirect to URL if invalid coupon is visited', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'customurl',
                'desc'        => __( "Optional. Will redirect the user to the provided URL when an invalid coupon has been attempted. You can also pass query args to the URL for the following variables {acfw_coupon_code} or {acfw_coupon_error_message} and it will be replaced with proper data. Eg. ?foo={acfw_coupon_error_message}, then test the 'foo' query arg to get the message if there is one.", 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::INVALID_COUPON_REDIRECT_URL,
                'placeholder' => $cart_url,
                'css'         => 'width: 500px; display: block;',
            ),

            array(
                'title'       => __( 'Custom success message', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Optional. Message that will be displayed when a coupon has been applied successfully. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::CUSTOM_SUCCESS_MESSAGE_GLOBAL,
                'css'         => 'width: 500px; display: block;',
                'placeholder' => __( 'Coupon applied successfully', 'advanced-coupons-for-woocommerce-free' ),
            ),

            array(
                'title'       => __( 'Custom disable message', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Optional. Message that will be displayed when the coupon url functionality is disabled. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::CUSTOM_DISABLE_MESSAGE,
                'css'         => 'width: 500px; display: block;',
                'placeholder' => __( 'Inactive coupon url', 'advanced-coupons-for-woocommerce-free' ),
            ),

            array(
                'title' => __( 'Hide coupon fields', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'checkbox',
                'desc'  => __( 'Hide the coupon fields from the cart and checkout pages on the front end.', 'advanced-coupons-for-woocommerce-free' ),
                'id'    => Plugin_Constants::HIDE_COUPON_UI_ON_CART_AND_CHECKOUT,
            ),

            array(
                'title' => __( 'Apply coupons via query string', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'subtitle',
                'desc'  => '',
                'id'    => 'acfw_apply_coupons_via_query_string_subtitle',
            ),

            array(
                'title' => __( 'Enable applying coupon via query string', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'checkbox',
                'desc'  => __( 'Enable this option to allow applying coupons via query string. Eg. ?coupon=COUPONCODE', 'advanced-coupons-for-woocommerce-free' ),
                'id'    => Plugin_Constants::APPLY_COUPON_VIA_QUERY_STRING,
            ),

            array(
                'title'   => __( 'Redirect after applying coupon via query string', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'select',
                'desc'    => __( 'Redirect to the cart or checkout page after applying a coupon via query string.', 'advanced-coupons-for-woocommerce-free' ),
                'options' => array(
                    'same_page' => __( 'Stay on the same page', 'advanced-coupons-for-woocommerce-free' ),
                    'cart'      => __( 'Redirect to cart', 'advanced-coupons-for-woocommerce-free' ),
                    'checkout'  => __( 'Redirect to checkout', 'advanced-coupons-for-woocommerce-free' ),
                ),
                'id'      => Plugin_Constants::REDIRECT_AFTER_APPLY_COUPON_VIA_QUERY_STRING,
                'default' => 'same_page',
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_url_coupons_sectionend',
            ),
        );
    }

    /**
     * Get role restrictions section options.
     *
     * @since 1.0.0
     * @access private
     *
     * @return array
     */
    private function _get_role_restrictions_section_options() {
        return array(

            array(
                'title' => __( 'Role Restriction', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_role_restrictions_main_title',
            ),

            array(
                'title'       => __( 'Invalid user role error message (global)', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'desc'        => __( 'Optional. Message that will be displayed when the coupon being applied is not valid for the current user. Leave blank to use the default message.', 'advanced-coupons-for-woocommerce-free' ),
                'id'          => Plugin_Constants::ROLE_RESTRICTIONS_ERROR_MESSAGE,
                'css'         => 'width: 500px; display: block;',
                'placeholder' => __( 'You are not allowed to use this coupon.', 'advanced-coupons-for-woocommerce-free' ),
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_role_restrictions_sectionend',
            ),
        );
    }

    /**
     * Get store credits section options.
     *
     * @since 4.2
     * @access private
     *
     * @return array
     */
    private function _get_store_credits_section_options() {

        $expiry_options = array(
            'noexpiry' => __( 'Never expire', 'advanced-coupons-for-woocommerce-free' ),
        );

        for ( $n = 1; $n <= 5; $n++ ) {
            /* Translators: %s: Number of years */
            $expiry_options[ (string) $n ] = sprintf( _n( '%s year', '%s years', $n, 'advanced-coupons-for-woocommerce-free' ), $n );
        }

        return array(

            array(
                'title' => __( 'Store Credits', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_store_credits_main_title',
            ),

            array(
                'title' => __( 'Hide store credits on checkout if zero balance', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'checkbox',
                'desc'  => __( 'Hides the store credit section on the checkout totals box if the logged in customer has zero balance.', 'advanced-coupons-for-woocommerce-free' ),
                'id'    => Plugin_Constants::STORE_CREDITS_HIDE_CHECKOUT_ZERO_BALANCE,
            ),

            array(
                'title'    => __( 'Store credit apply type', 'advanced-coupons-for-woocommerce-free' ),
                'type'     => 'radio',
                'desc_tip' => __( 'Lets you choose how Store Credit is applied on the checkout based on your local tax regulations. By default most countries are fine to apply Store Credit as a discount before taxes and shipping, but some countries may require you do apply it similar to a payment after taxes and shipping.', 'advanced-coupons-for-woocommerce-free' ),
                'id'       => Plugin_Constants::STORE_CREDIT_APPLY_TYPE,
                'default'  => 'coupon',
                'options'  => array(
                    'coupon'    => __( 'Apply store credit on checkout before tax and shipping.', 'advanced-coupons-for-woocommerce-free' ),
                    'after_tax' => __( 'Apply store credit on checkout after tax and shipping.', 'advanced-coupons-for-woocommerce-free' ),
                ),
            ),

            array(
                'title'    => __( 'Store credits expiry', 'advanced-coupons-for-woocommerce-free' ),
                'type'     => 'select',
                'desc_tip' => __( 'Expiry is set to "never expire" by default, which covers most countries. It is your responsibility to ensure you abide by your local store credit expiry laws.', 'advanced-coupons-for-woocommerce-free' ),
                'id'       => Plugin_Constants::STORE_CREDIT_EXPIRY,
                'options'  => $expiry_options,
                'default'  => 'noexpiry',
            ),

        );
    }

    /**
     * Get advanced section options.
     *
     * @since 4.5.9
     * @access private
     *
     * @return array Advanced section options.
     */
    private function _get_advanced_section_options() {

        return array(
            array(
                'title' => __( 'Advanced', 'advanced-coupons-for-woocommerce-free' ),
                'type'  => 'title',
                'desc'  => '',
                'id'    => 'acfw_advanced_main_title',
            ),

            array(
                'title'   => __( 'Clean up plugin data on un-installation', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'checkbox',
                'desc'    => __( 'If checked, removes all plugin data when this plugin is uninstalled. Warning: This process is irreversible.', 'advanced-coupons-for-woocommerce-free' ),
                'id'      => Plugin_Constants::CLEAN_UP_PLUGIN_OPTIONS,
                'default' => 'no',
            ),

            array(
                'title'   => __( 'Enable JS/CSS file integrity check', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'checkbox',
                'desc'    => __( 'Activate this setting to verify the integrity of JavaScript and CSS files, ensuring they are untampered and secure.', 'advanced-coupons-for-woocommerce-free' ),
                'id'      => ACFWF()->Plugin_Constants->ENABLE_ASSET_INTEGRITY_CHECK,
                'default' => 'no',
            ),

            array(
                'type' => 'sectionend',
                'id'   => 'acfw_advanced_sectionend',
            ),
        );
    }

    /**
     * Get help section options
     *
     * @since 1.0
     * @access private
     *
     * @return array
     */
    private function _get_help_section_options() {
        // hide save changes button.
        $GLOBALS['hide_save_button'] = true;

        return apply_filters(
            'acfw_settings_help_section_options',
            array(

                array(
                    'title' => __( 'Help', 'advanced-coupons-for-woocommerce-free' ),
                    'type'  => 'title',
                    'desc'  => 'Links to knowledge base and other helpful resources.',
                    'id'    => 'acfw_help_main_title',
                ),

                array(
                    'title' => __( 'Knowledge Base', 'advanced-coupons-for-woocommerce-free' ),
                    'type'  => 'acfw_divider_row',
                    'id'    => 'acfw_knowledge_base_divider_row',
                ),

                array(
                    'title'     => __( 'Documentation', 'advanced-coupons-for-woocommerce-free' ),
                    'type'      => 'acfw_help_resources_field',
                    'desc'      => __( 'Guides, troubleshooting, FAQ and more.', 'advanced-coupons-for-woocommerce-free' ),
                    'link_text' => __( 'Knowledge Base', 'advanced-coupons-for-woocommerce-free' ),
                    'link_url'  => 'http://advancedcouponsplugin.com/knowledge-base/?utm_source=Plugin&utm_medium=Help&utm_campaign=Knowledge%20Base%20Link',
                ),

                array(
                    'title'     => __( 'Our Blog', 'advanced-coupons-for-woocommerce-free' ),
                    'type'      => 'acfw_help_resources_field',
                    'desc'      => __( 'Learn & grow your store – covering coupon marketing ideas, strategies, management, tutorials & more.', 'advanced-coupons-for-woocommerce-free' ),
                    'id'        => 'acfw_help_blog_link',
                    'link_text' => __( 'Advanced Coupons Marketing Blog', 'advanced-coupons-for-woocommerce-free' ),
                    'link_url'  => 'https://advancedcouponsplugin.com/blog/?utm_source=Plugin&utm_medium=Help&utm_campaign=Blog%20Link',
                ),

                array(
                    'title' => __( 'Join the Community', 'advanced-coupons-for-woocommerce-free' ),
                    'type'  => 'acfw_social_links_field',
                    'id'    => 'acfw_social_links',
                ),

                array(
                    'type' => 'sectionend',
                    'id'   => 'acfw_help_sectionend',
                ),

            )
        );
    }

    /*
    |--------------------------------------------------------------------------------------------------------------
    | Custom Settings Fields
    |--------------------------------------------------------------------------------------------------------------
     */

    /**
     * Render ACFW divider row.
     *
     * @deprecated 4.5.7
     *
     * @since 1.0
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_divider_row( $value ) {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }

    /**
     * Render help resources controls.
     *
     * @deprecated 4.5.7
     *
     * @since 1.0
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_help_resources_field( $value ) {
    }

    /**
     * Render custom "social_links" field.
     *
     * @deprecated 4.5.7
     *
     * @since 1.0
     * @access public
     *
     * @param array $value Array of options data. May vary depending on option type.
     */
    public function render_acfw_social_links_option_field( $value ) {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }

    /**
     * BOGO Deals settings custom javascript.
     *
     * @since 1.0
     * @access public
     */
    public function render_acfw_bogo_deals_custom_js() {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }

    /**
     * Render hierarchical taxonomy terms as options list.
     *
     * @deprecated 4.5.7
     *
     * @since 1.10
     * @access public
     *
     * @param array $value Field value data.
     */
    public function render_acfw_taxonomy_terms_as_options_field( $value ) {
        wc_deprecated_function( __METHOD__, '4.5.7' );
    }
}
