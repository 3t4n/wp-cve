<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Models\Objects\Advanced_Coupon;
use ACFWF\Models\Objects\Emails\Coupon as Coupon_Email;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the Emails module.
 *
 * @since 4.5.3
 */
class Emails implements Model_Interface, Initializable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 4.5.3
     * @access private
     * @var Emails
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 4.5.3
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 4.5.3
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
     * Class constructor.
     *
     * @since 4.5.3
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
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 4.5.3
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Emails
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /**
     * Register email instances.
     *
     * @since 4.5.3
     * @access public
     *
     * @param array $emails List of email objects.
     * @return array Filtered list of email objects.
     */
    public function register_advanced_gift_card_email( $emails ) {
        $emails['acfw_coupon_email'] = new Coupon_Email();

        return $emails;
    }

    /**
     * Override template file check to make sure our custom email templates are found by WC.
     *
     * @since 1.0
     * @access public
     *
     * @param string $core_file     Core template file path.
     * @param string $template      Template file name.
     * @param string $template_base Template base path.
     * @param string $email_id      Email ID.
     */
    public function override_template_file_path_check( $core_file, $template, $template_base, $email_id ) {
        if ( 'acfw_coupon_email' === $email_id ) {
            return $this->_constants->TEMPLATES_ROOT_PATH . $template;
        }

        return $core_file;
    }

    /**
     * Register send coupon localized data.
     *
     * @since 4.5.3
     * @access public
     *
     * @param array $data Localized data.
     * @return array Filtered localized data.
     */
    public function register_send_coupon_localized_data( $data ) {

        $data['send_coupon'] = array(
            'labels'     => array(
                'title'                     => __( 'Send Coupon', 'advanced-coupons-for-woocommerce-free' ),
                'description'               => __( 'Deliver this coupon via email to a customer.', 'advanced-coupons-for-woocommerce-free' ),
                'send_coupon_to'            => __( 'Send coupon to', 'advanced-coupons-for-woocommerce-free' ),
                'existing_customer_account' => __( 'Existing customer account', 'advanced-coupons-for-woocommerce-free' ),
                'new_customer'              => __( 'New customer (no account)', 'advanced-coupons-for-woocommerce-free' ),
                'next'                      => __( 'Next', 'advanced-coupons-for-woocommerce-free' ),
                'customer_details'          => __( 'Customer details', 'advanced-coupons-for-woocommerce-free' ),
                'search'                    => __( 'Search', 'advanced-coupons-for-woocommerce-free' ),
                'name'                      => __( 'Name', 'advanced-coupons-for-woocommerce-free' ),
                'email'                     => __( 'Email', 'advanced-coupons-for-woocommerce-free' ),
                'create_new_user_account'   => __( 'Create new user account', 'advanced-coupons-for-woocommerce-free' ),
                'confirm_and_send'          => __( 'Confirm & send', 'advanced-coupons-for-woocommerce-free' ),
                'customer'                  => __( 'Customer', 'advanced-coupons-for-woocommerce-free' ),
                'preview_email'             => __( 'Preview email', 'advanced-coupons-for-woocommerce-free' ),
                'send_email'                => __( 'Send Email', 'advanced-coupons-for-woocommerce-free' ),
            ),
            'form_nonce' => wp_create_nonce( 'acfw_advanced_coupon_preview_email' ),
        );

        return $data;
    }

    /**
     * Display the advanced coupons email footer.
     *
     * @since 4.5.4.2
     * @access public
     *
     * @param string $email_heading Email heading.
     */
    public function display_advanced_coupons_email_header( $email_heading ) {
        $this->_helper_functions->load_template( 'emails/acfw-email-header.php', array( 'email_heading' => $email_heading ) );
    }

    /**
     * Display the advanced coupons email footer.
     *
     * @since 4.5.3
     * @access public
     */
    public function display_advanced_coupons_email_footer() {
        $this->_helper_functions->load_template( 'emails/acfw-email-footer.php' );
    }

    /*
    |--------------------------------------------------------------------------
    | Send email
    |--------------------------------------------------------------------------
     */

    /**
     * Trigger to send the coupon email.
     *
     * @since 4.5.3
     * @access public
     *
     * @param int   $coupon_id Coupon ID.
     * @param array $customer_details Customer id, email and name.
     */
    public function trigger_send_coupon_email( $coupon_id, $customer_details ) {
        $coupon   = new Advanced_Coupon( $coupon_id );
        $customer = new \WC_Customer( $customer_details['id'] ?? 0 );

        if ( ! $customer->get_id() ) {
            $customer->set_email( $customer_details['email'] ?? '' );
            $customer->set_display_name( $customer_details['name'] ?? '' );
            $customer->apply_changes();
        }

        // Load WC mailer instance as for some reason the action scheduler doesn't load the mailer instance on cron.
        \WC()->mailer();

        do_action( 'acfwf_send_advanced_coupon_email', $coupon, $customer );
    }

    /*
    |--------------------------------------------------------------------------
    | Email preview
    |--------------------------------------------------------------------------
     */

    /**
     * Get email preview content.
     *
     * @since 4.5.3
     * @access private
     *
     * @param string $email_id Email ID.
     * @param array  $args Email arguments.
     */
    private function _get_email_preview_content( $email_id, $args ) {
        $content = '';
        $mailer  = \WC()->mailer();

        switch ( $email_id ) {
            case 'acfw_coupon_email':
                $email = new Coupon_Email();
                $args  = wp_parse_args(
                    $args,
                    array(
						'coupon_id' => 0,
						'email'     => '',
						'user_id'   => 0,
                        'name'      => '',
                    )
                );

                $coupon   = new Advanced_Coupon( $args['coupon_id'] );
                $customer = $this->_helper_functions->get_customer_object( $args['user_id'] ? $args['user_id'] : $args['email'] );

                // Display an error page when customer is not valid.
                if ( is_wp_error( $customer ) ) {
                    wp_die( $customer ); // phpcs:ignore
                }

                if ( ! $args['user_id'] && $args['name'] ) {
                    $customer->set_display_name( $args['name'] );
                    $customer->apply_changes();
                }

                $email->set_coupon( $coupon );
                $email->set_customer( $customer );

                $content = $email->style_inline( $email->get_content_html() );
                break;

            default:
                $content = apply_filters( 'acfw_get_email_preview_content', $email_id, $args );
                break;
        }

        return $content;
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Functions
    |--------------------------------------------------------------------------
     */

    /**
     * AJAX advanced coupon email preview.
     *
     * @since 4.5.3
     * @access public
     */
    public function ajax_advanced_coupon_email_preview() {
        $error_msg = '';
        $post_data = wp_unslash( $_REQUEST );
        $email_id  = sanitize_text_field( wp_unslash( $post_data['email_id'] ?? '' ) );
        $nonce     = sanitize_key( $post_data['_wpnonce'] ?? '' );

        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
            $error_msg = __( 'Invalid AJAX call', 'advanced-coupons-for-woocommerce-free' );
        } elseif ( ! current_user_can( 'manage_woocommerce' ) || ! wp_verify_nonce( $nonce, 'acfw_advanced_coupon_preview_email' ) ) {
            $error_msg = __( 'You are not allowed to do this', 'advanced-coupons-for-woocommerce-free' );
        } elseif ( ! $email_id ) {
            $error_msg = __( 'Missing required parameters.', 'advanced-coupons-for-woocommerce-free' );
        } else {

            $args = $this->_helper_functions->api_sanitize_query_parameters( wp_unslash( $post_data['args'] ) );
            echo $this->_get_email_preview_content( $email_id, $args ); //phpcs:ignore
        }

        if ( $error_msg ) {
            include $this->_constants->VIEWS_ROOT_PATH . 'coupons/view-email-preview-error.php';
        }

        wp_die();
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin init.
     *
     * @since 4.5.3
     * @access public
     * @inherit ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {
        add_action( 'wp_ajax_acfw_advanced_coupon_preview_email', array( $this, 'ajax_advanced_coupon_email_preview' ) );
        add_action( 'wp_ajax_nopriv_acfw_advanced_coupon_preview_email', array( $this, 'ajax_advanced_coupon_email_preview' ) );
    }

    /**
     * Execute Emails class.
     *
     * @since 4.5.3
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        add_filter( 'woocommerce_email_classes', array( $this, 'register_advanced_gift_card_email' ) );
        add_filter( 'woocommerce_locate_core_template', array( $this, 'override_template_file_path_check' ), 10, 4 );
        add_filter( 'acfw_edit_advanced_coupon_localize', array( $this, 'register_send_coupon_localized_data' ) );
        add_action( 'acfw_email_header', array( $this, 'display_advanced_coupons_email_header' ) );
        add_action( 'acfw_email_footer', array( $this, 'display_advanced_coupons_email_footer' ) );
        add_action( Plugin_Constants::SEND_COUPON_ACTION_SCHEDULE, array( $this, 'trigger_send_coupon_email' ), 10, 3 );
    }
}
