<?php
namespace ACFWF\Models\REST_API;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Abstract_Report_Widget;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Advanced_Coupon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the Settings module logic.
 * Public Model.
 *
 * @since 4.3
 */
class API_Emails implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of API_Emails.
     *
     * @since 4.3
     * @access private
     * @var Cart_Conditions
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 4.3
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 4.3
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /**
     * Custom REST API base.
     *
     * @since 4.3
     * @access private
     * @var string
     */
    private $_base = 'sendcoupon';

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.3
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 4.3
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Cart_Conditions
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /*
    |--------------------------------------------------------------------------
    | Routes.
    |--------------------------------------------------------------------------
     */

    /**
     * Register settings API routes.
     *
     * @since 4.3
     * @access public
     */
    public function register_routes() {
        \register_rest_route(
            Plugin_Constants::REST_API_NAMESPACE,
            '/' . $this->_base,
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'permission_callback' => array( $this, 'get_admin_permissions_check' ),
                    'callback'            => array( $this, 'send_coupon_email' ),
                ),
            )
        );

        do_action( 'acfw_after_register_report_routes' );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions.
    |--------------------------------------------------------------------------
     */

    /**
     * Checks if a given request has access to read list of settings options.
     *
     * @since 4.3
     * @access public
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function get_admin_permissions_check( $request ) {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return new \WP_Error( 'rest_forbidden_context', __( 'Sorry, you are not allowed to edit settings options.', 'advanced-coupons-for-woocommerce-free' ), array( 'status' => \rest_authorization_required_code() ) );
        }

        return apply_filters( 'acfw_get_emails_admin_permissions_check', true, $request );
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD methods.
    |--------------------------------------------------------------------------
     */

    /**
     * Send the coupon email.
     *
     * @since 4.5.3
     * @access public
     *
     * @param WP_REST_Request $request Send coupon email request data.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error on failure.
     */
    public function send_coupon_email( $request ) {

        $params = $this->_helper_functions->api_sanitize_query_parameters( $request->get_params() );

        // Invalidate request when the required parameters are missing.
        if ( ! isset( $params['coupon_id'] ) || ! isset( $params['customer'] ) ) {
            return new \WP_Error(
                'acfw_missing_params',
                __( 'There was an error in the process of sending the email to the customer. Please try again.', 'advanced-coupons-for-woocommerce-free' ),
                array(
					'status' => 400,
					'data'   => $params,
                )
            );
        }

        $coupon = isset( $params['coupon_id'] ) ? new Advanced_Coupon( $params['coupon_id'] ) : null;

        // Invalidate request when the coupon is invalid.
        if ( ! $coupon instanceof Advanced_Coupon || ! $coupon->get_id() ) {
            return new \WP_Error(
                'acfw_invalid_coupon',
                __( 'Invalid coupon.', 'advanced-coupons-for-woocommerce-free' ),
                array(
					'status' => 400,
					'data'   => $params,
                )
            );
        }

        $customer = $this->_helper_functions->get_customer_object( $params['customer'] );

        // Don't proceed when customer is invalid.
        if ( is_wp_error( $customer ) ) {
            return $customer;
        }

        // Update customer name value for guest.
        if ( ! $customer->get_id() ) {
            $customer->set_display_name( $params['name'] ?? '' );
            $customer->apply_changes();
        }

        // Create account for guest customer.
        if ( ! $customer->get_id() && 'yes' === $params['create_account'] ) {
            $check = $this->_create_account_for_customer( $customer );

            if ( is_wp_error( $check ) ) {
                return $check;
            }
        }

        // Schedule coupon email.
        $check = \WC()->queue()->schedule_single(
            time(),
            Plugin_Constants::SEND_COUPON_ACTION_SCHEDULE,
            array(
                $coupon->get_id(),
                array(
                    'id'    => $customer->get_id(),
                    'email' => $customer->get_email(),
                    'name'  => $customer->get_display_name(),
                ),
            ),
            'ACFWF'
        );

        // Don't proceed when action wasn't scheduled.
        if ( is_wp_error( $check ) ) {
            return $check;
        }

        $sent_to_customer = sprintf( '%s &lt;%s>', $customer->get_display_name(), $customer->get_email() );

        return \rest_ensure_response(
            array(
                /* Translators: %s: Customer name and email. */
                'message' => sprintf( __( 'The coupon has been successfully emailed to %s.', 'advanced-coupons-for-woocommerce-free' ), $sent_to_customer ),
            )
        );
    }

    /**
     * Create an account for a given customer.
     *
     * @since 4.5.3
     * @access private
     *
     * @param \WC_Customer $customer Customer object.
     * @return bool|\WP_Error True when account is created, error object on failure.
     */
    private function _create_account_for_customer( \WC_Customer $customer ) {

        try {
            $customer->save();
        } catch ( \WC_Data_Exception $e ) {
            return new \WP_Error(
                'acfw_customer_email_exists',
                __( 'An account is already registered for the given email address.', 'advanced-coupons-for-woocommerce-free' ),
                array( 'status' => 400 )
            );
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute Settings class.
     *
     * @since 4.3
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }
}
