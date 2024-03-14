<?php

use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\WpMVC\App;

defined( 'ABSPATH' ) || exit;

function x_currency():App {
    return App::$instance;
}

/**
 * Returns an entry of the container by its name.
 *
 * @template T
 * @param string|class-string<T> $class Entry name or a class name.
 * @return mixed|T
 */
function x_currency_singleton( string $class ) {
    return x_currency()::$container->get( $class );
}

function x_currency_config() {
    return x_currency()::$config;
}

function x_currency_dir( string $dir ) {
    return x_currency()::get_dir( $dir );
}

function x_currency_url( string $dir ) {
    return x_currency()::get_url( $dir );
}

function x_currency_version() {
    return x_currency_config()->get( 'app.version' );
}

function x_currency_base() {
    $currency_repository = x_currency_singleton( CurrencyRepository::class );
    return $currency_repository->get_base_currency();
}

function x_currency_base_id() {
    return x_currency_base()->id;
}

function x_currency_base_code() {
    return x_currency_base()->code;
}

function x_currency_is_base_currency() {
    return x_currency_selected()->code === x_currency_base_code();
}

function x_currency_selected() {
    global $x_currency;
    return $x_currency['selected_currency'];
}

function x_currency_render( string $content ) {
    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $content;
}

if ( ! function_exists( 'x_currency_get_json_file_content' ) ) {
    /**
     * @param $file_url
     */
    function x_currency_get_json_file_content( $file_url ) {
        $fonts_string = file_get_contents( $file_url );
        return json_decode( $fonts_string, true );
    }
}


if ( ! function_exists( 'x_currency_switcher_list' ) ) {
    function x_currency_switcher_list( string $type = 'all', string $post_status = 'publish, draft' ) {
        $args = [
            'post_type'   => x_currency_config()->get( 'app.switcher_post_type' ),
            'post_status' => $post_status,
            'numberposts' => -1,
            'order'       => 'ASC'
        ];

        if ( $type !== 'all' ) {
            $args['meta_query'] = [
                [
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '='
                ]
            ];
        }

        return get_posts( $args );
    }
}

/**
 * @return array
 */
function x_currency_global_settings() {
    global $x_currency;
    return $x_currency['global_settings'];
}

if ( ! function_exists( 'x_currency_exchange' ) ) {
    /**
     * @param $price
     * @return mixed
     */
    function x_currency_exchange( $price ) {
        if ( ! empty( $price ) ) {
            $price = $price * x_currency_selected()->rate;
        }
        return apply_filters( 'x_currency_exchange', $price );
    }
}

if ( ! function_exists( 'x_currency_price_format' ) ) {
    /**
     * @param $symbol_position
     * @return mixed
     */
    function x_currency_price_format( $symbol_position ) {
        switch ( $symbol_position ) {
            case 'right':
                $format = '%2$s%1$s';
                break;
            case 'left_space':
                $format = '%1$s&nbsp;%2$s';
                break;
            case 'right_space':
                $format = '%2$s&nbsp;%1$s';
                break;
            default:
                $format = '%1$s%2$s';
        }
        return $format;
    }
}

function x_currency_user_country_code():string {
    global $x_currency;
    return $x_currency['user_country_code'];
}

function x_currency_get_price_html( $price, \WC_Product $product, $variation_prices ) {
    if ( $product instanceof \WC_Product_Variable ) {
        if ( empty( $variation_prices['price'][$product->get_id()] ) ) {
            return $price;
        }

        $regular_price = $variation_prices['regular_price'][$product->get_id()];
        $sale_price    = $variation_prices['price'][$product->get_id()];
    } elseif ( $product instanceof \WC_Product_Simple ) {
        $regular_price = $product->get_regular_price();
        $sale_price    = $product->get_price();
    }

    if ( ! isset( $regular_price ) ) {
        return $price;
    }

    $global_settings = x_currency_global_settings();

    if ( isset( $global_settings['prices_without_cents'] ) && $global_settings['prices_without_cents'] == true ) {
        $decimal = 0;
    } else {
        $decimal = x_currency_selected()->max_decimal;
    }

    /**
     * Using number_format for fixing 0 decimal creating issue.
     */
    if ( number_format( (float) $sale_price, $decimal ) === number_format( (float) $regular_price, $decimal ) ) {
        return wc_price( $sale_price ) . $product->get_price_suffix();
    }

    return wc_format_sale_price( wc_price( $regular_price ), wc_price( $sale_price ) ) . $product->get_price_suffix();     
}

function x_currency_symbols() {
    return [
        'AED' => '&#x62f;.&#x625;',
        'AFN' => '&#x60b;',
        'ALL' => 'L',
        'AMD' => 'AMD',
        'ANG' => '&fnof;',
        'AOA' => 'Kz',
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => 'Afl.',
        'AZN' => 'AZN',
        'BAM' => 'KM',
        'BBD' => '&#36;',
        'BDT' => '&#2547;&nbsp;',
        'BGN' => '&#1083;&#1074;.',
        'BHD' => '.&#x62f;.&#x628;',
        'BIF' => 'Fr',
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => 'Bs.',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTC' => '&#3647;',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYR' => 'Br',
        'BYN' => 'Br',
        'BZD' => '&#36;',
        'CAD' => '&#36;',
        'CDF' => 'Fr',
        'CHF' => '&#67;&#72;&#70;',
        'CLP' => '&#36;',
        'CNY' => '&yen;',
        'COP' => '&#36;',
        'CRC' => '&#x20a1;',
        'CUC' => '&#36;',
        'CUP' => '&#36;',
        'CVE' => '&#36;',
        'CZK' => '&#75;&#269;',
        'DJF' => 'Fr',
        'DKK' => 'DKK',
        'DOP' => 'RD&#36;',
        'DZD' => '&#x62f;.&#x62c;',
        'EGP' => 'EGP',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '&euro;',
        'FJD' => '&#36;',
        'FKP' => '&pound;',
        'GBP' => '&pound;',
        'GEL' => '&#x20be;',
        'GGP' => '&pound;',
        'GHS' => '&#x20b5;',
        'GIP' => '&pound;',
        'GMD' => 'D',
        'GNF' => 'Fr',
        'GTQ' => 'Q',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => '&#70;&#116;',
        'IDR' => 'Rp',
        'ILS' => '&#8362;',
        'IMP' => '&pound;',
        'INR' => '&#8377;',
        'IQD' => '&#x62f;.&#x639;',
        'IRR' => '&#xfdfc;',
        'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
        'ISK' => 'kr.',
        'JEP' => '&pound;',
        'JMD' => '&#36;',
        'JOD' => '&#x62f;.&#x627;',
        'JPY' => '&yen;',
        'KES' => 'KSh',
        'KGS' => '&#x441;&#x43e;&#x43c;',
        'KHR' => '&#x17db;',
        'KMF' => 'Fr',
        'KPW' => '&#x20a9;',
        'KRW' => '&#8361;',
        'KWD' => '&#x62f;.&#x643;',
        'KYD' => '&#36;',
        'KZT' => '&#8376;',
        'LAK' => '&#8365;',
        'LBP' => '&#x644;.&#x644;',
        'LKR' => '&#xdbb;&#xdd4;',
        'LRD' => '&#36;',
        'LSL' => 'L',
        'LYD' => '&#x644;.&#x62f;',
        'MAD' => '&#x62f;.&#x645;.',
        'MDL' => 'MDL',
        'MGA' => 'Ar',
        'MKD' => '&#x434;&#x435;&#x43d;',
        'MMK' => 'Ks',
        'MNT' => '&#x20ae;',
        'MOP' => 'P',
        'MRU' => 'UM',
        'MUR' => '&#x20a8;',
        'MVR' => '.&#x783;',
        'MWK' => 'MK',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => 'MT',
        'NAD' => 'N&#36;',
        'NGN' => '&#8358;',
        'NIO' => 'C&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#x631;.&#x639;.',
        'PAB' => 'B/.',
        'PEN' => 'S/',
        'PGK' => 'K',
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PRB' => '&#x440;.',
        'PYG' => '&#8370;',
        'QAR' => '&#x631;.&#x642;',
        'RMB' => '&yen;',
        'RON' => 'lei',
        'RSD' => '&#1088;&#1089;&#1076;',
        'RUB' => '&#8381;',
        'RWF' => 'Fr',
        'SAR' => '&#x631;.&#x633;',
        'SBD' => '&#36;',
        'SCR' => '&#x20a8;',
        'SDG' => '&#x62c;.&#x633;.',
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&pound;',
        'SLL' => 'Le',
        'SOS' => 'Sh',
        'SRD' => '&#36;',
        'SSP' => '&pound;',
        'STN' => 'Db',
        'SYP' => '&#x644;.&#x633;',
        'SZL' => 'E',
        'THB' => '&#3647;',
        'TJS' => '&#x405;&#x41c;',
        'TMT' => 'm',
        'TND' => '&#x62f;.&#x62a;',
        'TOP' => 'T&#36;',
        'TRY' => '&#8378;',
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => 'Sh',
        'UAH' => '&#8372;',
        'UGX' => 'UGX',
        'USD' => '&#36;',
        'UYU' => '&#36;',
        'UZS' => 'UZS',
        'VEF' => 'Bs F',
        'VES' => 'Bs.S',
        'VND' => '&#8363;',
        'VUV' => 'Vt',
        'WST' => 'T',
        'XAF' => 'CFA',
        'XCD' => '&#36;',
        'XOF' => 'CFA',
        'XPF' => 'Fr',
        'YER' => '&#xfdfc;',
        'ZAR' => '&#82;',
        'ZMW' => 'ZK'
    ];
}
