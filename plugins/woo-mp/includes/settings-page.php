<?php

namespace Woo_MP;

defined( 'ABSPATH' ) || die;

/**
 * Manual Payment settings.
 *
 * This class extends `WC_Settings_Page`. Therefore, care must be taken to
 * ensure that this class is only loaded when `WC_Settings_Page` is loaded.
 */
class Settings_Page extends \WC_Settings_Page {

    /**
     * The ID of this settings page.
     *
     * @var string
     */
    const ID = 'manual_payment';

    /**
     * A list of all payment gateways with gateway IDs as keys and instances of the main gateway objects as values.
     *
     * @var array
     */
    private $payment_gateways = [];

    /**
     * Gateway titles keyed by gateway ID.
     *
     * @var array
     */
    private $payment_gateway_titles = [];

    public function __construct() {
        $this->id    = self::ID;
        $this->label = __( 'Manual Payment', 'woo-mp' );

        $this->payment_gateways = Payment_Gateways::get_all();

        foreach ( $this->payment_gateways as $id => $gateway ) {
            $this->payment_gateway_titles[ $id ] = $gateway->get_title();
        }
    }

    /**
     * Initialize the settings page.
     *
     * This is to avoid side effects in the constructor.
     *
     * @return void
     */
    public function init() {
        parent::__construct();
    }

    /**
     * Get pages.
     *
     * @param  array $settings The settings pages.
     * @return array           The updated settings pages.
     */
    public static function get_pages( $settings ) {
        $page = new static();

        $page->init();

        $settings[] = $page;

        return $settings;
    }

    public function get_sections() {
        $sections = [ '' => __( 'General', 'woo-mp' ) ] + $this->payment_gateway_titles;

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }

    public function get_settings() {
        global $current_section;

        $text_style = 'width: 400px;';

        $settings = [];

        if ( $current_section === '' ) {
            $settings = [
                [
                    'title' => __( 'Settings', 'woo-mp' ),
                    'type'  => 'title',
                    'desc'  => WOO_MP_CONFIG_HELP,
                ],
                [
                    'title'             => __( 'Payment Gateway', 'woo-mp' ),
                    'desc'              => __( 'This payment gateway will be used for all manual payments.', 'woo-mp' ),
                    'id'                => 'woo_mp_payment_processor',
                    'type'              => 'select',
                    'class'             => 'wc-enhanced-select',
                    'custom_attributes' => [
                        'data-placeholder' => __( 'Select a payment gateway...', 'woo-mp' ),
                    ],
                    'desc_tip'          => true,
                    'options'           => [ '' => '' ] + $this->payment_gateway_titles,
                ],
                [
                    'title'    => __( 'Transaction Description', 'woo-mp' ),
                    'desc'     => __( 'This description may be displayed in your payment gateway dashboard.', 'woo-mp' ),
                    'id'       => 'woo_mp_transaction_description',
                    'type'     => 'text',
                    'default'  => get_option( 'blogname' ),
                    'desc_tip' => true,
                    'css'      => $text_style,
                ],
                [
                    'title'   => __( 'Capture Payments', 'woo-mp' ),
                    'desc'    => __( 'Capture payments immediately. If unchecked, payments will only be authorized.', 'woo-mp' ),
                    'id'      => 'woo_mp_capture_payments',
                    'default' => 'yes',
                    'type'    => 'checkbox',
                ],
                [
                    'title'    => __( 'Update Order Status When', 'woo-mp' ),
                    'desc'     => __( 'Choose when you want order statuses to be updated.', 'woo-mp' ),
                    'id'       => 'woo_mp_update_order_status_when',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'desc_tip' => true,
                    'options'  => [
                        ''                     => __( "Don't update order statuses", 'woo-mp' ),
                        'any_transaction'      => __( 'A payment or authorization is made', 'woo-mp' ),
                        'total_amount_charged' => __( 'The total amount has been paid or authorized', 'woo-mp' ),
                    ],
                ],
                [
                    'title'    => __( 'Update Order Status To', 'woo-mp' ),
                    'desc'     => __( 'Choose which status orders should be updated to when the above condition is fulfilled.', 'woo-mp' ),
                    'id'       => 'woo_mp_update_order_status_to',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'desc_tip' => true,
                    'default'  => 'wc-completed',
                    'options'  => wc_get_order_statuses(),
                ],
                [
                    'title'    => __( 'Save WooCommerce Payment Record When', 'woo-mp' ),
                    'desc'     => __( 'Choose when you want an official (native) WooCommerce payment record to be saved to an order. Please note that WooCommerce only supports one official payment per order. This means that if you choose to save a record any time a payment or authorization is made, previous payment information will be overwritten. You will still be able to see past payments in the <em>Order notes</em> section.', 'woo-mp' ),
                    'id'       => 'woo_mp_save_wc_payment_when',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'desc_tip' => true,
                    'default'  => 'first_payment',
                    'options'  => [
                        'first_payment' => __( 'The first payment or authorization is made', 'woo-mp' ),
                        'every_payment' => __( 'Any payment or authorization is made (see help tip)', 'woo-mp' ),
                        'never'         => __( "Don't save WooCommerce payment records", 'woo-mp' ),
                    ],
                ],
                [
                    'title'    => __( 'Reduce Stock Levels When', 'woo-mp' ),
                    'desc'     => __( 'Choose when you want order item stock levels to be reduced. Stock levels will never be reduced more than once. Please note that this option only applies when stock management is enabled at both the global and product level.', 'woo-mp' ),
                    'id'       => 'woo_mp_reduce_stock_levels_when',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'desc_tip' => true,
                    'default'  => 'any_charge',
                    'options'  => [
                        'any_charge'           => __( 'A payment or authorization is made', 'woo-mp' ),
                        'total_amount_charged' => __( 'The total amount has been paid or authorized', 'woo-mp' ),
                        'never'                => __( "Don't reduce stock levels", 'woo-mp' ),
                    ],
                ],
                [
                    'type' => 'sectionend',
                ],
            ];
        } else {
            if ( isset( $this->payment_gateways[ $current_section ] ) ) {
                $settings = $this->payment_gateways[ $current_section ]->get_settings_section()->get_settings();
            }
        }

        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
    }

}
