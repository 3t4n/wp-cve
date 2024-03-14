<?php
defined('ABSPATH') or exit;

require_once dirname(dirname(__FILE__)) . '/class-montonio-shipping-method.php';

class Montonio_Venipak_Post_Offices extends Montonio_Shipping_Method {
    const MAX_DIMENSIONS = [80, 120, 170]; // lowest to highest (cm)

    public $default_title = 'Venipak pickup points';
    public $default_max_weight = 30; // kg

    /**
     * Called from parent's constructor
     * @return void
     */
    protected function init() {
        $this->id                 = 'montonio_venipak_post_offices';
        $this->method_title       = __( 'Montonio Venipak pickup points', 'montonio-for-woocommerce' );
        $this->method_description = __( 'Venipak pickup points', 'montonio-for-woocommerce' );
        $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
        );

        $this->provider_name = 'venipak';
        $this->type = 'post_office';
        $this->logo = 'https://public.montonio.com/images/shipping_provider_logos/venipak-logo.svg';
        $this->title = __( $this->get_option( 'title', __( 'Venipak pickup points', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );
    }

    protected function validate_package_dimensions( $package ) {
        $package_dimensions = $this->get_package_dimensions( $package );

        return ( $package_dimensions[0] <= self::MAX_DIMENSIONS[0] ) && ( $package_dimensions[1] <= self::MAX_DIMENSIONS[1] ) && ( $package_dimensions[2] <= self::MAX_DIMENSIONS[2] );
    }
}
