<?php

namespace XCurrency\App\Providers;

use Exception;
use XCurrency\App\Repositories\CurrencyRateRepository;
use XCurrency\App\Repositories\SettingRepository;
use XCurrency\WpMVC\Contracts\Provider;

class ScheduleServiceProvider implements Provider {
    public function boot() {
        add_action( 'init', [ $this, 'action_init' ], 9 );
    }

    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init() : void {
        $this->refresh_currency_rates();
    }

    public function refresh_currency_rates() {

        $global_settings = x_currency_global_settings();

        if ( empty( $global_settings['currency_rate_auto_update'] ) || $global_settings['currency_rate_auto_update'] != true || get_transient( 'x-currency-currency-update' ) ) {
            return;
        }

        try {
            /**
             * @var CurrencyRateRepository $currency_rate_repository
             */
            $currency_rate_repository = x_currency_singleton( CurrencyRateRepository::class );
            $currency_rate_repository->exchange();

            /**
             * @var SettingRepository $setting_repository
             */
            $setting_repository = x_currency_singleton( SettingRepository::class );
            $transient_time     = $setting_repository->calculate_transient_time( $global_settings['rate_auto_update_time_type'], $global_settings['rate_auto_update_time'] );
            set_transient( 'x-currency-currency-update', $transient_time, $transient_time );
        } catch ( Exception $exception ) {
        }
    }
}