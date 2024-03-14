<?php

namespace XCurrency\App\Woocommerce;

use XCurrency\App\Repositories\CurrencyRepository;

defined( 'ABSPATH' ) || exit;

class Report {
    /**
     * @return mixed
     */
    public function boot() {
        add_filter( 'woocommerce_reports_get_order_report_data_args', [$this, 'report_data_args'] );
        add_filter( 'woocommerce_currency_symbol', [$this, 'currency_symbol'] );
        add_filter( 'wc_reports_tabs', [$this, 'report_switcher'] );
    }

    /**
     * @param $symbol
     * @return mixed
     */
    public function currency_symbol( $symbol ) {
        if ( empty( $_REQUEST['x_currency_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['x_currency_nonce'] ) ), 'x_currency_nonce' ) ) {
            $symbol;
        }

        $symbols            = get_woocommerce_currency_symbols();
        $base_currency_code = x_currency_base_code();
    
        if ( ! empty( $_GET['x-currency'] ) ) {
            $symbol = isset( $symbols[$_GET['x-currency']] ) ? $symbols[sanitize_text_field( wp_unslash( $_GET['x-currency'] ) )] : $symbol;
        } else {
            $symbol = isset( $symbols[$base_currency_code] ) ? $symbols[$base_currency_code] : $symbol;
        }
        return $symbol;
    }

    public function report_switcher() {
        $selected_currency_code = x_currency_base_code();
        if ( ! empty( $_REQUEST['x_currency_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['x_currency_nonce'] ) ), 'x_currency_nonce' ) && ! empty( $_GET['x-currency'] ) ) {
            $selected_currency_code = sanitize_text_field( wp_unslash( $_GET['x-currency'] ) );
        }

        /**
         * @var CurrencyRepository $currency_repository
         */
        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        ?>
        <div style="float: right;">
            <select name="x-currency" id="x-currency">
                <?php foreach ( $currency_repository->get_all() as $currency ) :?>
                <option value="<?php echo esc_attr( $currency->code ) ?>" <?php selected( $currency->code, $selected_currency_code, true ) ?>><?php echo esc_html( $currency->name )?></option>
                <?php endforeach;?>
            </select>
        </div>
        <script data-cfasync="false" type="text/javascript">
            jQuery(document).ready(function($) {
                $('#x-currency').change(function() {
                    let currency = $(this).val();
                    let url = new URL(window.location.href);
                    url.searchParams.set('x-currency', currency);
                    url.searchParams.set('x_currency_nonce', "<?php x_currency_render( wp_create_nonce( 'x_currency_nonce' ) )?>");
                    window.location = url.href;
                });
            });
        </script>
        <?php
    }

    /**
     * @param $args
     * @return mixed
     */
    public function report_data_args( $args ) {
        $selected_currency_code = x_currency_base_code();
        if ( ! empty( $_GET['x_currency_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['x_currency_nonce'] ) ), 'x_currency_nonce' ) && ! empty( $_GET['x-currency'] ) ) {
            $selected_currency_code = sanitize_text_field( wp_unslash( $_GET['x-currency'] ) );
        }

        $args['where_meta'] = [
            [
                'meta_key'   => '_order_currency',
                'operator'   => 'in',
                'meta_value' => [$selected_currency_code]
            ]
        ];
        return $args;
    }
}
