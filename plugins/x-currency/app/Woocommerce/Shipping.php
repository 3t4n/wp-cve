<?php

namespace XCurrency\App\Woocommerce;

defined( 'ABSPATH' ) || exit;

use XCurrency\App\Repositories\CurrencyRepository;

class Shipping {
    const META_KEY = 'x_currency_';

    public function boot() {
        add_filter( 'woocommerce_shipping_instance_form_fields_flat_rate', [$this, 'flat_rate_fields'], 9999 );
        add_filter( 'woocommerce_shipping_instance_form_fields_local_pickup', [$this, 'local_pickup_fields'], 9999 );
        add_filter( 'woocommerce_shipping_instance_form_fields_free_shipping', [$this, 'free_shipping_fields'], 9999 );
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function flat_rate_fields( $fields ) {
        $data = [
            'base_amount_field' => esc_html__( 'Cost Field Amount', 'x-currency' ),
            'title'             => esc_html__( 'Specific flat rate amount for each currencies.', 'x-currency' ),
            'description'       => esc_html__( 'If you want to automatically exchange flat rate amounts, leave the field blank.', 'x-currency' )
        ];
        return $this->shipping_inputs( $fields, $data );
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function local_pickup_fields( $fields ) {
        $data = [
            'base_amount_field' => esc_html__( 'Cost Field Amount', 'x-currency' ),
            'title'             => esc_html__( 'Specific local pickup amount for each currencies.', 'x-currency' ),
            'description'       => esc_html__( 'If you want to automatically exchange local pickup amounts, leave the field blank.', 'x-currency' )
        ];
        return $this->shipping_inputs( $fields, $data );
    }

    /**
     * @param $fields
     */
    public function free_shipping_fields( $fields ) {
        $data = [
            'base_amount_field' => esc_html__( 'Minimum Order Amount Field', 'x-currency' ),
            'title'             => esc_html__( 'Specific order amount for each currencies.', 'x-currency' ),
            'description'       => esc_html__( 'If you want to automatically exchange order amounts, leave the field blank.', 'x-currency' )
        ];

        return $this->shipping_inputs( $fields, $data );
    }

    /**
     * @param $fields
     * @param $data
     * @return mixed
     */
    public function shipping_inputs( $fields, $data ) {
        global $x_currency;
        /**
         * @var CurrencyRepository $currency_repository
         */
        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        $currencies          = $currency_repository->get();
        $base_currency_id    = x_currency_base_id();

        if ( empty( $x_currency['global_settings']['specific_shipping_amount'] ) || $x_currency['global_settings']['specific_shipping_amount'] != 'true' ) {
            $data['title'] .= ' ' . sprintf( esc_html__( 'To use this feature, %s this option.', 'x-currency' ), '<a href="' . admin_url( 'admin.php?page=' . 'x-currency' ) . '#/global-settings">' . esc_html__( 'Enable', 'x-currency' ) . '</a>' );
        }
        $fields['x_currency'] = [
            'title'   => '<h3 class="cursor-text" style="margin: 5px 0 17px 0; color: var(--wp-admin-theme-color); font-size:30px"><i class="xc-icon-logo" style="margin-right: 10px; font-size:30px;"></i>X-Currency</h3>
			<p class="cursor-text" style="margin-top: 0px; font-weight: normal; font-size:16px">' . $data['title'] . '</p>',
            'class'   => 'x-currency-shipping-input',
            'default' => ''
        ];

        if ( isset( $x_currency['global_settings']['specific_shipping_amount'] ) && $x_currency['global_settings']['specific_shipping_amount'] == 'true' ) {

            foreach ( $currencies as $currency ) {
                $attr = [
                    'title'     => $currency->code,
                    'type'      => 'number',
                    'data_type' => 'price',
                    'class'     => 'x-currency-shipping-input',
                    'default'   => ''
                ];

                if ( $base_currency_id == $currency->id ) {
                    $attr['title']      .= ' (Base Currency)';
                    $attr['class']      .= ' pointer-events-none';
                    $attr['placeholder'] = $data['base_amount_field'];
                } else {
                    $attr['description'] = $data['description'];
                    $attr['desc_tip']    = true;
                    $attr['placeholder'] = 'auto';
                }
                $fields[self::META_KEY . strtolower( $currency->code )] = $attr;
            }
        }
        return $fields;
    }
}
