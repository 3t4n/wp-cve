<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Advanced_Coupon;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of extending the coupon system of woocommerce.
 * It houses the logic of handling coupon url.
 * Public Model.
 *
 * @since 1.0
 */
class URL_Coupons implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 1.0
     * @access private
     * @var URL_Coupons
     */
    private static $_instance;

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

    /**
     * Coupon endpoint set.
     *
     * @since 1.0
     * @access private
     * @var string
     */
    private $_coupon_endpoint;

    /**
     * Coupon base url.
     *
     * @since 1.0
     * @access private
     * @var string
     */
    private $_coupon_base_url;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;
        $this->_coupon_endpoint  = $this->_helper_functions->get_coupon_url_endpoint();
        $this->_coupon_base_url  = home_url( '/' ) . $this->_coupon_endpoint;

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return URL_Coupons
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /*
    |--------------------------------------------------------------------------
    | URL Coupon implementation
    |--------------------------------------------------------------------------
     */

    /**
     * Implement URL Coupon.
     *
     * @since 1.0
     * @access public
     */
    public function implement_url_coupon() {
        global $wp_query;

        if ( ! isset( $wp_query->query['post_type'] ) || 'shop_coupon' !== $wp_query->query['post_type'] ) {
            return;
        }

        // Coupon codes are just post titles. So we pass it through 'sanitize_title' to get the slug and then fetch the title using it.
        $coupon_slug = isset( $wp_query->query['name'] ) ? sanitize_title( $wp_query->query['name'] ) : '';
        $coupon_id   = $this->_get_coupon_id_by_slug( $coupon_slug );

        $coupon_args = apply_filters(
            'acfw_extract_coupon_endpoint_args',
            array(
				'code' => $coupon_slug,
				'id'   => $coupon_id,
            )
        );
        $coupon      = new Advanced_Coupon( $coupon_id );

        // Initialize cart session.
        \WC()->session->set_customer_session_cookie( true );

        // if coupon is invalid, then don't proceed.
        if (
            ! $coupon->get_id() || // Coupon doesn't exists.
            ! $coupon->is_coupon_url_valid() // Coupon url is not valid.
        ) {
            $error_message = __( 'Invalid Coupon', 'advanced-coupons-for-woocommerce-free' );
            do_action( 'acfw_invalid_coupon', $coupon, $coupon_args, $error_message );
            $this->_after_apply_redirect_invalid( $coupon, $coupon_args, $error_message );
        }

        // modify the success message by adding a filter before applying the coupon.
        add_filter(
            'woocommerce_coupon_message',
            function ( $msg, $msg_code ) use ( $coupon ) {
                return $coupon->get_advanced_prop( 'success_message', __( 'Coupon applied successfully', 'advanced-coupons-for-woocommerce-free' ), true );
            },
            10,
            2
        );

        do_action( 'acfw_before_apply_coupon', $coupon, $coupon_args );

        // Apply coupon.
        $is_applied = WC()->cart->apply_coupon( $coupon->get_code() );

        do_action( 'acfw_after_apply_coupon', $coupon, $coupon_args, $is_applied );
        $this->_after_apply_redirect_success( $coupon, $coupon_args, $is_applied );
    }

    /**
     * Redirect after applying coupon successfully.
     *
     * @since 1.0
     * @since 4.2   append coupon URL attributes to the redirect URL
     * @since 4.5.1 Add redirect to origin URL feature.
     * @access private
     *
     * @param Advanced_Coupon $coupon      Advanced coupon object.
     * @param array           $coupon_args URL Coupon additional arguments.
     * @param bool            $is_applied  Flag if coupon was applied or not.
     */
    private function _after_apply_redirect_success( $coupon, $coupon_args, $is_applied ) {
        $redirect_url = $coupon->get_valid_redirect_url();
        if ( $redirect_url ) {

            $query_args = apply_filters(
                'acfw_after_apply_coupon_redirect_url_query_args',
                array( '{acfw_coupon_code}', '{acfw_coupon_is_applied}', '{acfw_coupon_error_message}' )
            );

            $coupon_code             = $coupon->get_code();
            $coupon_error_message    = rawurlencode( $this->_hackish_fetch_coupon_error_message() );
            $is_applied_response     = $is_applied ? 'true' : 'false';
            $query_args_replacements = apply_filters(
                'acfw_after_apply_coupon_redirect_url_query_args_replacements',
                array( $coupon_code, $is_applied_response, $coupon_error_message )
            );

            $redirect_url = str_replace( $query_args, $query_args_replacements, $redirect_url );

        } else {
            $redirect_url = wc_get_cart_url();
        }

        // redirect back to origin URL if it's available and the coupon setting is turned on.
        $referrer = isset( $_SERVER['HTTP_REFERER'] ) ? wp_sanitize_redirect( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
        if (
            'yes' === $coupon->get_advanced_prop( 'redirect_to_origin_url' ) &&
            isset( $_SERVER['HTTP_REFERER'] ) &&
            strpos( $referrer, home_url() ) !== false
        ) {
            $redirect_url = $referrer;
        }

        // append attributes that was added in the coupon to the redirect URL.
        if ( ! empty( $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $connector     = strpos( $redirect_url, '?' ) === false ? '?' : '&';
            $redirect_url .= $connector . http_build_query( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }

        // Clear notices when redirecting to an external URL.
        if ( strpos( $redirect_url, home_url() ) === false ) {
            wc_clear_notices();
        }

        wp_redirect( $redirect_url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
        exit();
    }

    /**
     * Redirect after applying invalid coupon.
     *
     * @since 1.0
     * @access private
     *
     * @param Advanced_Coupon $coupon        Advanced coupon object.
     * @param array           $coupon_args   URL Coupon additional arguments.
     * @param array           $error_message Invalid coupon error message.
     */
    private function _after_apply_redirect_invalid( $coupon, $coupon_args, $error_message ) {
        $redirect_url = get_option( Plugin_Constants::INVALID_COUPON_REDIRECT_URL, '' );

        if ( filter_var( $redirect_url, FILTER_VALIDATE_URL ) ) {
            $redirect_url = $this->_process_invalid_coupon_redirect_url_query_args( $coupon, $coupon_args, $error_message, $redirect_url );
        } else {
            $redirect_url = wc_get_cart_url();
        }

        // Display error notice if redirecting to an internal page.
        if ( strpos( $redirect_url, home_url() ) !== false ) {
            $adv_error_message = $coupon->get_advanced_error_message();
            wc_add_notice( $adv_error_message ? $adv_error_message : $error_message, 'error' );
        }

        wp_redirect( $redirect_url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
        exit();
    }

    /**
     * Process invalid coupon redirect url query vars. Replace em with actual data.
     *
     * @since 1.0
     * @access public
     *
     * @param Advanced_Coupon $coupon        WooCommerce coupon object. Could be valid or invalid coupon object.
     * @param array           $coupon_args   Coupon url additional arguments.
     * @param string          $error_message Coupon error message.
     * @param string          $redirect_url  URL to redirect.
     */
    private function _process_invalid_coupon_redirect_url_query_args( $coupon, $coupon_args, $error_message, $redirect_url ) {
        $query_args = apply_filters(
            'acfw_invalid_coupon_redirect_url_query_args',
            array( '{acfw_coupon_code}', '{acfw_coupon_error_message}' )
        );

        $coupon_code          = $coupon->get_code();
        $coupon_error_message = rawurlencode( $coupon->get_advanced_error_message() );

        $query_args_replacements = apply_filters(
            'acfw_invalid_coupon_redirect_url_query_args_replacements',
            array( $coupon_code, $coupon_error_message )
        );

        return str_replace( $query_args, $query_args_replacements, $redirect_url );
    }

    /**
     * Override the WooCommerce post type registration for shop_coupon.
     *
     * @since 1.0
     * @since 4.2 set `with_front` parameter to false in rewrites settings
     * @access public
     *
     * @param array $args shop_coupon post type registration args.
     * @return array Filtered shop_coupon post type registration args.
     */
    public function override_wc_coupon_registration( $args ) {
        $args['publicly_queryable'] = true;
        $args['rewrite']            = array(
            'slug'       => $this->_coupon_endpoint,
            'pages'      => false,
            'with_front' => false, // exclude any custom structures prepended in the permalink settings.
        );

        // flush rewrite rules when transient is set.
        if ( 'true' === get_transient( Plugin_Constants::COUPON_ENDPOINT . '_flush_rules' ) ) {

            flush_rewrite_rules( false );
            delete_transient( Plugin_Constants::COUPON_ENDPOINT . '_flush_rules' );
        }

        return $args;
    }

    /**
     * Sanitize coupon endpoint option value.
     *
     * @since 1.0
     * @access public
     *
     * @param string $value     Coupon endpoint value.
     * @param string $option    Option name.
     * @param string $raw_value Raw value.
     */
    public function sanitize_coupon_endpoint_option_value( $value, $option, $raw_value ) {
        return $value ? sanitize_title( $value ) : 'coupon';
    }

    /**
     * Hide coupon UI.
     *
     * @since 1.0
     * @since 2.2.3 Make sure at $wp_query global variable is availabe before running page conditional queries.
     * @access public
     *
     * @param bool $value Filter return value.
     * @return bool Filtered return value.
     */
    public function hide_coupon_fields( $value ) {
        global $wp_query;

        if ( $wp_query && ( is_cart() || is_checkout() ) && get_option( Plugin_Constants::HIDE_COUPON_UI_ON_CART_AND_CHECKOUT ) === 'yes' ) {
            return false;
        }

        return $value;
    }

    /**
     * Fetch coupon error message (hackish method).
     *
     * @since 1.0
     * @access private
     *
     * @return string Coupon error message.
     */
    private function _hackish_fetch_coupon_error_message() {
        // NOTE: This is the only way of retrieving what might be the error that caused the coupon to not be applied successfully.
        // This isn't reliable as we are only getting the latest added error notice from wc_notices, which might not be set by the coupon
        // but its better than nothing, its a bit ok too cause if coupon failed to apply, woocommerce add error notice about it anyways.
        $coupon_error_message = wc_get_notices( 'error' );
        $notice               = is_array( $coupon_error_message ) && ! empty( $coupon_error_message ) ? end( $coupon_error_message ) : null;

        if ( is_array( $notice ) ) {
            return $notice['notice'];
        } else {
            return $notice ? $notice : '';
        }
    }

    /**
     * Set transient to force flush rewrite rules when coupon endpoint value is changed.
     *
     * @since 1.2
     * @access public
     */
    public function flush_rewrite_rules_on_coupon_endpoint_change() {
        set_transient( Plugin_Constants::COUPON_ENDPOINT . '_flush_rules', 'true', 5 * 60 );
    }

    /*
    |--------------------------------------------------------------------------
    | Apply coupon via query string
    |--------------------------------------------------------------------------
     */

    /**
     * Main code implementation for applying the coupon via a query string.
     *
     * @since 4.5.6
     * @access public
     */
    public function apply_coupon_from_query_string() {

        // Skip if apply coupon via query string is disabled.
        if ( 'yes' !== get_option( Plugin_Constants::APPLY_COUPON_VIA_QUERY_STRING ) ) {
            return;
        }

        $coupon_code = sanitize_text_field( wp_unslash( $_GET['coupon'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        // skip if there is no coupon code provided in the query string.
        if ( ! $coupon_code ) {
            return;
        }

        $coupon = new Advanced_Coupon( $coupon_code );

        // Skip if coupon does not exist.
        if ( ! $coupon->get_id() ) {
            return;
        }

        // Initialize cart session.
        \WC()->session->set_customer_session_cookie( true );

        do_action( 'acfwf_before_apply_coupon_query_string', $coupon );

        // Apply coupon.
        $is_applied = WC()->cart->apply_coupon( $coupon->get_code() );

        do_action( 'acfwf_after_apply_coupon_query_string', $is_applied, $coupon );

        // Maybe redirect after coupon is applied.
        if ( $is_applied ) {
            $redirect_setting = get_option( Plugin_Constants::REDIRECT_AFTER_APPLY_COUPON_VIA_QUERY_STRING, 'same_page' );

            switch ( $redirect_setting ) {
                case 'checkout':
                    $redirect_url = wc_get_checkout_url();
                    break;
                case 'cart':
                    $redirect_url = wc_get_cart_url();
                    break;
                case 'same_page':
                default:
                    $redirect_url = home_url( $_SERVER['REQUEST_URI'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
                    $redirect_url = remove_query_arg( 'coupon', $redirect_url );
                    break;
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Utility functions
    |--------------------------------------------------------------------------
     */

    /**
     * Get page by path.
     *
     * @since 1.4.2
     * @access private
     *
     * @param string $coupon_slug Coupon post slug.
     * @return int Coupon ID.
     */
    private function _get_coupon_id_by_slug( $coupon_slug ) {
        $post      = $coupon_slug ? get_page_by_path( $coupon_slug, OBJECT, 'shop_coupon' ) : null;
        $coupon_id = $post ? $post->ID : 0;

        // check for coupon override value if coupon ID was not detected.
        if ( $coupon_slug && ! $coupon_id ) {
            $coupon_id = $this->_get_coupon_id_from_override( $coupon_slug );
        }

        return $coupon_id;
    }

    /**
     * Get the actual coupon code from the given override code.
     *
     * @since 1.4.2
     * @access private
     *
     * @param string $coupon_slug Coupon slug.
     * @return int Coupon ID.
     */
    private function _get_coupon_id_from_override( $coupon_slug ) {
        global $wpdb;

        return absint(
            $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ID FROM {$wpdb->posts} AS posts_table
                INNER JOIN {$wpdb->postmeta} AS post_meta_table
                ON posts_table.ID = post_meta_table.post_id
                WHERE posts_table.post_type = 'shop_coupon'
                    AND post_meta_table.meta_key = %s
                    AND post_meta_table.meta_value = %s
                LIMIT 1",
                    Plugin_Constants::META_PREFIX . 'code_url_override',
                    $coupon_slug
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute URL_Coupons class.
     *
     * @since 1.0
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        if ( ! $this->_helper_functions->is_module( Plugin_Constants::URL_COUPONS_MODULE ) ) {
            return;
        }

        add_filter( 'woocommerce_register_post_type_shop_coupon', array( $this, 'override_wc_coupon_registration' ), 10, 1 );
        add_filter( 'woocommerce_admin_settings_sanitize_option_' . Plugin_Constants::COUPON_ENDPOINT, array( $this, 'sanitize_coupon_endpoint_option_value' ), 10, 3 );
        add_filter( 'woocommerce_coupons_enabled', array( $this, 'hide_coupon_fields' ) );
        add_action( 'update_option_' . Plugin_Constants::COUPON_ENDPOINT, array( $this, 'flush_rewrite_rules_on_coupon_endpoint_change' ) );

        add_action( 'template_redirect', array( $this, 'implement_url_coupon' ) );
        add_action( 'wp', array( $this, 'apply_coupon_from_query_string' ) );
    }

}
