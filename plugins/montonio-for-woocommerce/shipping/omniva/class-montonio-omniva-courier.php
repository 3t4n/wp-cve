<?php
defined('ABSPATH') or exit;

require_once dirname(dirname(__FILE__)) . '/class-montonio-shipping-method.php';

class Montonio_Omniva_Courier extends Montonio_Shipping_Method {
    const MAX_LENGHT = 150; // cm (longest side)
    const MAX_SUM_OF_LENGHT_AND_GIRTH = 300; // cm

    public $default_title = 'Omniva courier';
    public $default_max_weight = 30; // kg

    /**
     * Called from parent's constructor
     * @return void
     */
    protected function init()
    {
        $this->id                 = 'montonio_omniva_courier';
        $this->method_title       = __('Montonio Omniva courier', 'montonio-for-woocommerce');
        $this->method_description = __('Omniva courier', 'montonio-for-woocommerce');
        $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
        );

        $this->provider_name = 'omniva';
        $this->type = 'courier';
        $this->logo = 'https://public.montonio.com/images/shipping_provider_logos/omniva.png';
        $this->title = __( $this->get_option( 'title', __( 'Omniva courier', 'montonio-for-woocommerce') ), 'montonio-for-woocommerce' );
    }

    protected function validate_package_dimensions( $package ) {
        $package_dimensions = $this->get_package_dimensions( $package );

        if ( $package_dimensions[2] > self::MAX_LENGHT ) {
            return false;
        }

        $sum_of_lenght_and_girth = 2 * ( $package_dimensions[0] + $package_dimensions[1] ) + $package_dimensions[2];
        
        if ( $sum_of_lenght_and_girth > self::MAX_SUM_OF_LENGHT_AND_GIRTH ) {
            return false;
        }

        return true;
    }
}
