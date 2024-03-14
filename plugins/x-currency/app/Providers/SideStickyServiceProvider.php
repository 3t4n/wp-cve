<?php

namespace XCurrency\App\Providers;

use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\App\Repositories\SwitcherRepository;
use XCurrency\WpMVC\App;
use XCurrency\WpMVC\Contracts\Provider;
use XCurrency\WpMVC\View\View;

class SideStickyServiceProvider implements Provider {
    public function boot() {
        add_action( 'init', [$this, 'init'] );
    }

    public function init() {
        if ( ! is_admin() ) {
            add_action( 'wp_footer', [$this, 'stick_sidebar'] );
        }
    }

    public function stick_sidebar() {
        $current_page_id     = apply_filters( 'x_currency_current_page_id', 'all' );
        $switcher_repository = x_currency_singleton( SwitcherRepository::class );
        $switcher_list       = $switcher_repository->get_side_sticky();

        if ( is_array( $switcher_list ) ) {
            /**
             * @var CurrencyRepository $currency_repository
             */
            $currency_repository = x_currency_singleton( CurrencyRepository::class );
            $currencies          = $currency_repository->get_geo();

            foreach ( $switcher_list as $template_id => $switcher ) {
                if ( $switcher['page'] == 'all' || $switcher['page'] == $current_page_id ) {
                    $rand = rand( 10, 9999 );
                    x_currency_render( '<div id="' . $rand . '" style="position:relative; z-index:999; display: inline-block"></div>' );
                    View::render( 'switcher', compact( 'currencies', 'rand', 'template_id' ) );
                }
            }
        }
    }
}