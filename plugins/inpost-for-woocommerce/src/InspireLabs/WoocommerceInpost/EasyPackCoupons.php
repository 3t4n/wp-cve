<?php


namespace InspireLabs\WoocommerceInpost;

use WC_Order;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;

use InspireLabs\WoocommerceInpost\EmailFilters\NewOrderEmail;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_C2C;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette_COD;
use InspireLabs\WoocommerceInpost\shipping\Easypack_Shipping_Rates;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Weekend;
use InspireLabs\WoocommerceInpost\shipx\services\courier_pickup\ShipX_Courier_Pickup_Service;
use InspireLabs\WoocommerceInpost\shipx\services\organization\ShipX_Organization_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Price_Calculator_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Status_Service;

use WC_Shipping_Method;


class EasyPackCoupons
{

    const META_ID = EasyPack::ATTRIBUTE_PREFIX . '_coupon_shipping_methods_allowed';

    public function hooks() {
        add_filter( 'woocommerce_coupon_discount_types', array( $this, 'easypack_custom_discount_type' ), 10, 1 );

        add_action( 'woocommerce_coupon_options_save', array( $this, 'easypack_save_coupon_allowed_methods' ), 10, 2 );
        add_action( 'woocommerce_coupon_options', array( $this, 'easypack_add_list_configured_inpost_methods' ), 10, 2 );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_coupon_scripts' ), 75 );

        add_filter( 'woocommerce_package_rates', array( $this, 'easypack_coupons' ), 10, 2 );

        add_filter( 'woocommerce_coupon_discount_amount_html', array( $this,'easypack_coupon_discount_amount_html_filter' ), 10, 2 );

    }

    function easypack_custom_discount_type( $discount_types ) {
        $discount_types['easypack_inpost_discount'] = __( 'Inpost', 'woocommerce' );
        return $discount_types;
    }


    function easypack_save_coupon_allowed_methods( $post_id, $coupon ) {

        $allowed_methods = [];
        foreach ( $_POST as $key => $value ) {

            if ( 0 === strpos( $key, 'easypack_') && $value === 'yes') {

                $allowed_methods[] = $key;
            }
        }

        if( ! empty( $allowed_methods) && $_POST['discount_type'] === 'easypack_inpost_discount' ) {
            //clear shipping cache
            \WC_Cache_Helper::get_transient_version( 'shipping', true );

            update_post_meta($post_id, self::META_ID, $allowed_methods );
        }
    }


    function easypack_add_list_configured_inpost_methods( $coupon_id, $coupon ) {

        echo '<div id="easypack_list_configured_inpost_methods" class="panel woocommerce_options_panel" style="margin-top:20px;">';

            $meta = get_post_meta( $coupon_id, self::META_ID, true );
            if( ! $meta || ! is_array( $meta ) ) {
                $meta = [];
            }

            foreach ( EasyPack_Helper()->get_inpost_methods() as $key => $method ) {

                woocommerce_wp_checkbox( [
                    'id'    => $method['method_title_with_id'],
                    'label' => $method['user_title'],
                    'value' => in_array($method['method_title_with_id'], $meta) ? 'yes' :'',

                ] );
            }
        echo '</div>';
    }


    function enqueue_admin_coupon_scripts() {

        $current_screen = get_current_screen();

        // only on edit coupon page
        if ( is_a( $current_screen, 'WP_Screen' ) && 'shop_coupon' === $current_screen->id ) {
            $plugin_data = new EasyPack();

            wp_enqueue_script('easypack-coupons', $plugin_data->getPluginJs() . 'easypack-coupons.js', ['jquery']);
            /*wp_localize_script(
                'easypack-coupons',
                'easypack_coupons',
                array(
                    'description' => __( 'Check this box if the coupon grants free shipping for selected Inpost methods', 'woocommerce-inpost' )
                )
            );*/
        }
    }


    /**
     * For coupons
     */
    public function easypack_coupons( $rates, $package ) {
        $has_free_shipping = false;
        $allowed_methods = [];
        $inpost_coupon_amount = 0;

        $applied_coupons = WC()->cart->get_applied_coupons();
        foreach( $applied_coupons as $coupon_code ){
            $coupon = new \WC_Coupon($coupon_code);

            if($coupon->get_discount_type() === 'easypack_inpost_discount'){
                $allowed_methods = get_post_meta($coupon->get_id(), 'woo_inpost_coupon_shipping_methods_allowed', true);

                if ($coupon->get_free_shipping() ) {
                    $has_free_shipping = true;
                    break;
                } else {
                    $inpost_coupon_amount = (float) $coupon->get_amount();
                }
            }
        }

        if( ! empty( $allowed_methods ) ) {

            foreach ( $rates as $rate_key => $rate ) {
                if ($has_free_shipping) {
                    // For "free shipping" method (enabled), remove it
                    if ($rate->method_id == 'free_shipping') {
                        unset($rates[$rate_key]);
                    } // For other shipping methods
                    else {

                        if( in_array($rate->id, $allowed_methods )) {

                            // Append rate label titles (free)
                            $rates[$rate_key]->label .= ' (' . __('Free!', 'woocommerce') . ')';

                            // Set rate cost
                            $rates[$rate_key]->cost = 0;

                            // Set taxes rate cost (if enabled)
                            $taxes = array();
                            foreach ($rates[$rate_key]->taxes as $key => $tax) {
                                if ($rates[$rate_key]->taxes[$key] > 0) {
                                    $taxes[$key] = 0;
                                }
                            }
                            $rates[$rate_key]->taxes = $taxes;
                        }
                    }
                } else {
                    if( $inpost_coupon_amount > 0 ) {

                        if( in_array($rate->id, $allowed_methods )) {

                            if ( $inpost_coupon_amount > (float)$rate->cost ) {

                                // Append rate label titles (free)
                                $rates[$rate_key]->label .= ' (' . __('Free!', 'woocommerce') . ')';

                                // Set rate cost
                                $rates[$rate_key]->cost = 0;

                                // Set taxes rate cost (if enabled)
                                $taxes = array();
                                foreach ($rates[$rate_key]->taxes as $key => $tax) {
                                    if ($rates[$rate_key]->taxes[$key] > 0) {
                                        $taxes[$key] = 0;
                                    }
                                }
                                $rates[$rate_key]->taxes = $taxes;


                            } else {

                                $rates[$rate_key]->cost = (float)$rate->cost - $inpost_coupon_amount;
                            }
                        }

                    }

                }
            }
        }

        return $rates;
    }


    public function easypack_coupon_discount_amount_html_filter( $discount_amount_html, $coupon ){

        if($coupon->get_discount_type() === 'easypack_inpost_discount'){
            if ($coupon->get_free_shipping() ) {
                return __( 'Free shipping coupon', 'woocommerce' );
            }

            $inpost_coupon_amount = (float) $coupon->get_amount();
            if( $inpost_coupon_amount > 0 ) {
                return '-' . wc_price( $inpost_coupon_amount );
            }
        }

        return $discount_amount_html;
    }



}