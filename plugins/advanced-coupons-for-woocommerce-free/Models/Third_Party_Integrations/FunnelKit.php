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
 * @since 4.5.7
 */
class FunnelKit extends Base_Model implements Model_Interface, Initializable_Interface {
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
    }

    /**
     * Register custom checkout fields.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array $checkout_fields Checkout fields.
     * @return array Filtered checkout fields.
     */
    public function register_custom_checkout_fields( $checkout_fields ) {

        $checkout_fields['acfw_redeem_store_credit'] = array(
            'type'         => 'wfacp_html',
            'field_type'   => 'advanced',
            'id'           => 'acfw_redeem_store_credit',
            'default'      => false,
            'class'        => array( 'acfw_wfacp_redeem_store_credits' ),
            'label'        => __( 'Store Credits', 'advanced-coupons-for-woocommerce-free' ),
            'data_label'   => __( 'Store Credits', 'advanced-coupons-for-woocommerce-free' ),
            'coupon_style' => 'true',
            '',
        );

        return $checkout_fields;
    }

    /**
     * Display store credit redeem form field.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array  $field Field data.
     * @param string $key Field key.
     * @param array  $args Field args.
     */
    public function display_store_credit_redeem_form_field( $field, $key, $args ) {

        if ( ! \ACFWF()->Store_Credits_Checkout->is_allow_store_credits() ) {
            return;
        }

        $instance       = wfacp_template();
        $checkout_field = $instance->get_checkout_fields();

        if ( ! isset( $checkout_field['advanced']['acfw_redeem_store_credit'] ) || 'acfw_redeem_store_credit' !== $key ) {
            return;
        }

        if ( ! empty( $field ) ) {
            $args = WC()->session->get( 'acfw_redeem_store_credit' . \WFACP_Common::get_id(), $field );
        }

        $coupon_cls   = $instance->get_template_type() === 'embed_form' ? 'wfacp-col-full' : 'wfacp-col-left-half';
        $classes      = isset( $args['cssready'] ) ? implode( ' ', $args['cssready'] ) : '';
        $labels       = \ACFWF()->Checkout->get_store_credits_redeem_form_labels();
        $user_balance = apply_filters( 'acfw_filter_amount', \ACFWF()->Store_Credits_Calculate->get_customer_balance( get_current_user_id() ) );

        include $this->_constants->VIEWS_ROOT_PATH . 'integrations/funnelkit-store-credit-redeem-field.php';
    }

    /**
     * Set the html element class prefix for the store credits redeem form field.
     *
     * @since 4.5.7
     * @access public
     *
     * @param string $prefix Class prefix.
     * @return string Filtered class prefix.
     */
    public function set_store_credits_form_field_class_prefix( $prefix ) {
        return $this->is_funnelkit_checkout_enabled() ? 'wfacp' : $prefix;
    }

    /**
     * Check if the FunnelKit checkout is enabled or not.
     *
     * @since 4.5.7
     * @access public
     *
     * @return bool True if active, false otherwise.
     */
    public function is_funnelkit_checkout_enabled() {
        // Check if FunnelKit is active.
        if ( ! class_exists( '\WFFN_Common' ) || ! function_exists( 'WFFN_Core' ) ) {
            return false;
        }

        // Check if store checkout is enabled.
        $checkout_id = \WFFN_Common::get_store_checkout_id();
        if ( ! (bool) \WFFN_Core()->get_dB()->get_meta( $checkout_id, 'status' ) ) {
            return false;
        }

        // Check if there is any FunnelKit custom checkout page is published.
        $checkouts = get_posts(
            array(
                'post_type'   => 'wfacp_checkout',
                'numberposts' => 1,
                'post_status' => 'publish',
            )
        );
        if ( count( $checkouts ) < 1 ) {
            return false;
        }

        return true;
    }

    /**
     * FunnelKit Upsell Notice.
     *
     * @since 4.5.7
     * @access public
     *
     * @param array $fields Checkout setting fields.
     */
    public function register_funnelkit_upsell_notice( $fields ) {

        $fields[] = array(
            'type'       => 'funnelkit_upsell',
            'id'         => 'acfw_funnelkit_upsell_notice',
            'noticeData' => array(
                'classname'       => 'acfw-funnelkit-upsell-notice',
                'title'           => __( 'Did you know?', 'advanced-coupons-for-woocommerce-free' ),
                'description'     => sprintf(
                    /* translators: %s: FunnelKit Builder */
                    __( '%s lets you create a custom checkout that is optimized for conversions? Better yet, you can also easily add upsells, cross-sell and down sell funnels to increase the average order value of your customers. It’s fully compatible with Advanced Coupons as one of our first-tier integrations.', 'advanced-coupons-for-woocommerce-free' ),
                    '<strong>FunnelKit Builder</strong>' // not translated as this is a plugin name.
                ),
                'button_text'     => __( 'Install & Activate Free Plugin', 'advanced-coupons-for-woocommerce-free' ),
                'button_link'     => '#',
                'button_class'    => 'ant-btn ant-btn-primary',
                'image'           => $this->_constants->IMAGES_ROOT_URL . 'fk-logo.png',
                'nonce'           => wp_create_nonce( 'acfw_install_plugin' ),
                'success_message' => __( 'Plugin installed and activated successfully!', 'advanced-coupons-for-woocommerce-free' ),
            ),
        );

        return $fields;
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
        if ( $this->is_funnelkit_checkout_enabled() ) {
            remove_action( 'woocommerce_checkout_order_review', array( ACFWF()->Checkout, 'display_checkout_tabbed_box' ), 11 );
        }
    }

    /**
     * Execute WPML_Support class.
     *
     * @since 4.5.7
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        if ( ! $this->_helper_functions->is_plugin_active( 'funnel-builder/funnel-builder.php' ) ) {
            add_filter( 'acfw_setting_checkout_options', array( $this, 'register_funnelkit_upsell_notice' ) );
            return;
        }

        add_filter( 'wfacp_advanced_fields', array( $this, 'register_custom_checkout_fields' ) );
        add_filter( 'wfacp_html_fields_acfw_redeem_store_credit', '__return_false' );
        add_filter( 'acfw_store_credits_form_field_class_prefix', array( $this, 'set_store_credits_form_field_class_prefix' ) );
        add_action( 'process_wfacp_html', array( $this, 'display_store_credit_redeem_form_field' ), 10, 3 );
    }
}
