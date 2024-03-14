<?php
defined('ABSPATH') or exit;

require_once dirname(dirname(__FILE__)) . '/class-montonio-shipping-method.php';

class Montonio_Venipak_Courier extends Montonio_Shipping_Method {
    const MAX_DIMENSIONS = [80, 120, 170]; // lowest to highest (cm)

    public $default_title = 'Venipak courier';
    public $default_max_weight = 30; // kg

    /**
     * Called from parent's constructor
     * @return void
     */
    protected function init() {
        $this->id                 = 'montonio_venipak_courier';
        $this->method_title       = __( 'Montonio Venipak courier', 'montonio-for-woocommerce' );
        $this->method_description = __( 'Venipak courier', 'montonio-for-woocommerce' );
        $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
        );

        $this->provider_name = 'venipak';
        $this->type = 'courier';
        $this->logo = 'https://public.montonio.com/images/shipping_provider_logos/venipak-logo.svg';
        $this->title = __( $this->get_option( 'title', __( 'Venipak courier', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );
    }

    protected function validate_package_dimensions( $package ) {
        $package_dimensions = $this->get_package_dimensions( $package );

        return ( $package_dimensions[0] <= self::MAX_DIMENSIONS[0] ) && ( $package_dimensions[1] <= self::MAX_DIMENSIONS[1] ) && ( $package_dimensions[2] <= self::MAX_DIMENSIONS[2] );
    }
}
