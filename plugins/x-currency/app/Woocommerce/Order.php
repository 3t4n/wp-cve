<?php

namespace XCurrency\App\Woocommerce;

defined( 'ABSPATH' ) || exit;

use XCurrency\App\Repositories\CurrencyRepository;

class Order {
    const META_KEY = 'x-currency-order';

    public function boot() {
        add_action( 'add_meta_boxes', [$this, 'order_meta_boxes'] );
        add_action( 'woocommerce_new_order', [$this, 'save_order_meta'] );
    }

    public function save_order_meta( $order_id ) {
        $order               = wc_get_order( $order_id );
        $order_currency_code = $order->get_currency();

        /**
         * @var CurrencyRepository $currency_repository
         */
        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        $currency            = $currency_repository->get_by_first( 'code', $order_currency_code );

        $order_x_currency = [
            'base_currency'      => x_currency_base_code(),
            $order_currency_code => $currency->rate
        ];

        add_post_meta( $order_id, self::META_KEY, serialize( $order_x_currency ) );
    }

    public function order_meta_boxes() {
        add_meta_box( 'x_currency_order_info', 'X-Currency Order Info', [$this, 'order_info'], 'shop_order', 'side' );
    }

    /**
     * @param $post
     */
    public function order_info( $post ) {
        global $x_currency;

        if ( empty( $x_currency['global_settings']['base_currency'] ) ) {
            return;
        }

        $order               = wc_get_order( $post->ID );
        $order_x_currency    = get_post_meta( $post->ID, self::META_KEY, true );
        $order_currency_code = $order->get_currency();

        /**
         * @var CurrencyRepository $currency_repository
         */
        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        $currency            = $currency_repository->get_by_first( 'code', $order_currency_code );
        
        if ( empty( $order_x_currency ) ) {
            $order_x_currency = [
                'base_currency'      => x_currency_base_code(),
                $order_currency_code => $currency->rate
            ];
        } else {
            $order_x_currency = unserialize( $order_x_currency );
        }

        ?>
        <table>
            <tbody>
                <tr>
                    <th style="text-align: left;"><?php esc_html_e( 'Base Currency', 'x-currency' )?>:</th>
                    <td><?php echo esc_html( $order_x_currency['base_currency'] ); esc_html_e( ' (ordered time)', 'x-currency' )?></td>
                </tr>
                <tr>
                    <th style="text-align: left;"><?php esc_html_e( 'Order Currency', 'x-currency' )?>:</th>
                    <td><?php echo esc_html( $order_currency_code );?></td>
                </tr>
                <tr>
                    <th style="text-align: left;"><?php esc_html_e( 'Currency Rate:', 'x-currency' )?></th>
                    <td><?php echo esc_html( $order_x_currency[$order_currency_code] . get_woocommerce_currency_symbol( $order_currency_code ) );?></td>
                </tr>
                <?php if ( ! empty( $order->get_date_created() ) ) :?>
                <tr>
                    <th style="text-align: left;"><?php esc_html_e( 'Created At:', 'x-currency' )?></th>
                    <td><?php echo esc_html( $order->get_date_created()->date( "Y-m-d h:i:sa" ) )?></td>
                </tr>
                <?php endif;?>
            </tbody>
        </table>
        <?php
    }
}
