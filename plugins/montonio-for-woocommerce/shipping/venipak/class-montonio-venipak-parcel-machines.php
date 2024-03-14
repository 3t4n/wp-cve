<?php
defined('ABSPATH') or exit;

require_once dirname( dirname(__FILE__) ) . '/class-montonio-shipping-method.php';

class Montonio_Venipak_Parcel_Machines extends Montonio_Shipping_Method {
    const MAX_DIMENSIONS = [39.5, 41, 61]; // lowest to highest (cm)

    public $default_title = 'Venipak parcel machine';
    public $default_max_weight = 30; // kg

    /**
     * Called from parent's constructor
     * @return void
     */
    protected function init() {
        $this->id                 = 'montonio_venipak_parcel_machines';
        $this->method_title       = __( 'Montonio Venipak parcel machines', 'montonio-for-woocommerce' );
        $this->method_description = __( 'Venipak parcel machines', 'montonio-for-woocommerce' );
        $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
        );

        $this->provider_name = 'venipak';
        $this->type = 'parcel_machine';
        $this->logo = 'https://public.montonio.com/images/shipping_provider_logos/venipak-logo.svg';
        $this->title = __( $this->get_option( 'title', __( 'Venipak parcel machine', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );
    }

    protected function validate_package_dimensions( $package ) {
        $package_dimensions = $this->get_package_dimensions( $package );

        return ( $package_dimensions[0] <= self::MAX_DIMENSIONS[0] ) && ( $package_dimensions[1] <= self::MAX_DIMENSIONS[1] ) && ( $package_dimensions[2] <= self::MAX_DIMENSIONS[2] );
    }

    /**
	 * Check if the shipping method is available for use.
	 *
	 * @return bool
	 */
    public function is_available( $package ) {
        foreach ( $package['contents'] as $item ) {
            if ( get_post_meta( $item['product_id'], '_montonio_no_parcel_machine', true ) === 'yes' ) {
                return false;
            }
        }

        return parent::is_available( $package );
    }
}
