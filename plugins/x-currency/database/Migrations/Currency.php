<?php

namespace XCurrency\Database\Migrations;

use XCurrency\App\Models\Currency as ModelsCurrency;
use XCurrency\App\Repositories\CurrencyRepository;
use XCurrency\App\Repositories\SettingRepository;
use XCurrency\WpMVC\Contracts\Migration;

class Currency implements Migration {
    public SettingRepository $setting_repository;

    public CurrencyRepository $currency_repository;

    public function __construct( SettingRepository $setting_repository, CurrencyRepository $currency_repository ) {
        $this->setting_repository  = $setting_repository;
        $this->currency_repository = $currency_repository;
    }

    public function more_than_version() {
        return '1.3.0';
    }

    public function execute(): bool {

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}x_currency(
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
			active BOOLEAN,
			name VARCHAR(100) NOT NULL,
			code VARCHAR(50) NOT NULL,
			symbol VARCHAR(5) NOT NULL,
			flag INT,
			rate FLOAT(24) NOT NULL,
			rate_type VARCHAR(100) NOT NULL,
			extra_fee FLOAT(24) NOT NULL,
			extra_fee_type VARCHAR(100) NOT NULL,
			thousand_separator VARCHAR(50) NOT NULL,
			rounding VARCHAR(50) DEFAULT 'disabled',
			max_decimal INT NOT NULL,
			decimal_separator VARCHAR(50) NOT NULL,
			symbol_position VARCHAR(50) NOT NULL,
			disable_payment_gateways LONGTEXT NOT NULL,
			geo_countries_status VARCHAR(50) DEFAULT 'disable',
			disable_countries LONGTEXT,
			welcome_country VARCHAR(50)
		) {$charset_collate}";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $sql );

        $sort_ids = get_option( 'x-currency-currency-sort-keys' );

        if ( empty( $sort_ids ) ) {
            return true;
        }

        $items    = [];
        $sort_ids = unserialize( $sort_ids );

        foreach ( $sort_ids as $id ) {
            $currency = get_post( $id );
            if ( $currency->post_type === 'x-currency' ) {
                $meta           = $this->meta( $id );
                $meta['name']   = $currency->post_title;
                $meta['active'] = $currency->post_status == 'publish' ? true : false;
                $items[]        = $meta;
            }
        }

        ModelsCurrency::query()->insert( $items );

        $base_currency_id   = get_option( 'x-base-currency' );
        $base_currency_code = get_post_meta( $base_currency_id , "code", true );
        $base_currency      = ModelsCurrency::query()->where( 'code', $base_currency_code )->first();

        $this->currency_repository->update_base_currency( $base_currency->id );

        $settings = $this->setting_repository->db_settings();

        if ( ! empty( $settings['user_welcome_currency'] ) ) {

            $welcome_currency_code = get_post_meta( $settings['user_welcome_currency'] , "code", true );
            $welcome_currency      = ModelsCurrency::query()->where( 'code', $welcome_currency_code )->first();

            if ( $welcome_currency ) {
                $settings['user_welcome_currency'] = $welcome_currency->id;
            }

            $this->setting_repository->update_settings( $settings );
        }

        return true;
    }

    public function meta( $currency_id ) {
        $country_list = get_post_meta( $currency_id, 'disable_countries', true );

        if ( false == $country_list ) {
            $country_list = serialize( [] );
        }

        $payment_gateways = get_post_meta( $currency_id, 'payment_gateways', true );

        if ( false == $payment_gateways ) {
            $payment_gateways = serialize( [] );
        } elseif ( is_array( $payment_gateways ) ) {
            $payment_gateways = serialize( $payment_gateways );
        }

        $geo_countries_type = get_post_meta( $currency_id, 'geo_countries_type', true );

        return [
            "code"                     => get_post_meta( $currency_id, 'code', true ),
            "symbol"                   => get_post_meta( $currency_id, 'symbol', true ),
            'flag'                     => (int) get_post_meta( $currency_id, 'flag', true ),
            "rate"                     => get_post_meta( $currency_id, 'rate', true ),
            "rate_type"                => get_post_meta( $currency_id, 'rate_type', true ), // fixed || auto
            "extra_fee"                => get_post_meta( $currency_id, 'extra_fee', true ),
            "extra_fee_type"           => get_post_meta( $currency_id, 'extra_fee_type', true ), // fixed || percent
            "thousand_separator"       => get_post_meta( $currency_id, 'thousand_separator', true ),
            'max_decimal'              => (int) get_post_meta( $currency_id, 'decimal', true ),
            "rounding"                 => 'disabled',
            'decimal_separator'        => get_post_meta( $currency_id, 'decimal_separator', true ),
            'symbol_position'          => get_post_meta( $currency_id, 'position', true ),
            "disable_payment_gateways" => $payment_gateways,
            'geo_countries_status'     => empty( $geo_countries_type ) ? 'disable' : $geo_countries_type,
            'disable_countries'        => $country_list,
            'welcome_country'          => get_post_meta( $currency_id, 'welcome_country', true ),
        ];
    }
} 
