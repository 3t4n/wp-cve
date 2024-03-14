<?php

namespace XCurrency\App\Providers;

use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\App;
use XCurrency\WpMVC\Contracts\Provider;
use XCurrency\WpMVC\View\View;

class ShortCodeServiceProvider implements Provider {
    public function boot() {
        add_shortcode( 'x-currency-switcher', [$this, 'view'] );
    }

    /**
     * @param $attr
     */
    public function view( $attr ) {
        if ( isset( $attr['id'] ) ) {
            return self::render( intval( $attr['id'] ) );
        }
    }

    public static function render( $template_id ) {
        $query = new \WP_Query(
            [
                'p'           => $template_id,
                'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
                'post_status' => 'publish'
            ] 
        );

        if ( empty( $query->post ) ) {
            return '';
        }

        /**
         * @var CurrencyRepository $currency_repository
         */
        $currency_repository = x_currency_singleton( CurrencyRepository::class );
        $currencies          = $currency_repository->get_geo();
        $rand                = rand( 10, 9999 );
        ob_start();
        View::render( 'switcher', compact( 'template_id', 'currencies', 'rand' ) );
        return ob_get_clean();
    }
}