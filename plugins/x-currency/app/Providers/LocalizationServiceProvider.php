<?php

namespace XCurrency\App\Providers;

use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\Contracts\Provider;

class LocalizationServiceProvider implements Provider {
    public function boot() {
        add_action( 'wp_head', [ $this, 'action_wp_head' ] );
    }

    /**
     * Prints scripts or data in the head tag on the front end.
     *
     */
    public function action_wp_head() : void {
        global $x_currency;

        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        $currencies          = $currency_repository->get_geo();
        $remove_query_string = ( isset( $x_currency['global_settings']['no_get_data_in_link'] ) && $x_currency['global_settings']['no_get_data_in_link'] == 'true' ) ? 'yes' : 'no';
        $selected_currency   = x_currency_selected()->code;
        $is_pro              = function_exists( 'x_currency_pro' );

        ?>
        <script data-cfasync="false" type="text/javascript">
            if(!window.x_currency_data) {
                window.x_currency_data = {};
            }

            window.x_currency_data['isPro']               = '<?php x_currency_render( $is_pro ? 'yes' : 'no' ) ?>';
            window.x_currency_data['mode']                = 'preview';
            window.x_currency_data['currencies']          = <?php x_currency_render( wp_json_encode( $currencies ) ) ?>;
            window.x_currency_data['selectedCurrency']    = '<?php x_currency_render( $selected_currency ) ?>';
            window.x_currency_data['removeQueryString']   = '<?php x_currency_render( $remove_query_string ) ?>';
        </script>
        <?php
    }
}