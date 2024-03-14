<?php

namespace XCurrency\App\Repositories;

use Exception;
use XCurrency\App\Models\Currency;

class CurrencyRepository {
    private $base_currency;

    private $currencies;

    public function get() {
        return $this->get_currencies()['active'];
    }

    public function get_all( $refresh = false ) {
        return $this->get_currencies( $refresh )['all'];
    }

    public function get_geo( $refresh = false ) {
        return $this->get_currencies( $refresh )['geo'];
    }

    public function update_geo( array $data ) {
        Currency::query()->where( 'id', $data['currency_id'] )->update(
            [
                'geo_countries_status' => $data['geo_countries_status'],
                'disable_countries'    => serialize( $data['disable_countries'] ),
                'welcome_country'      => $data['welcome_country'],
            ]
        );
    }

    public function get_currencies( $refresh = false ) {
        if ( ! $refresh && ! empty( $this->currencies ) ) {
            return $this->currencies;
        }

        $currencies        = $this->sort_currencies( Currency::query()->get() );
        $active_currencies = [];
        $geo_currencies    = [];

        $base_currency = $this->get_base_currency();

        global $x_currency;

        $country_code = x_currency_user_country_code();

        foreach ( $currencies as $key => $currency ) {

            $currency = $this->prepare_currency( $currency );

            $currencies[$key] = $currency;

            if ( $currency->id === $base_currency->id ) {
                $geo_currencies[] = $currency;
                continue;
            }

            if ( ! $currency->active ) {
                continue;
            }

            $active_currencies[] = $currency;

            if ( is_array( $currency->disable_countries ) ) {
                if ( 'enable' === $currency->geo_countries_status ) {
                    if ( in_array( $country_code, $currency->disable_countries ) ) {
                        $geo_currencies[] = $currency;
                    }
                    
                } elseif ( 'disable' === $currency->geo_countries_status ) {
                    if ( ! in_array( $country_code, $currency->disable_countries ) ) {
                        $geo_currencies[] = $currency;
                    }
                }
            }
        }

        $this->currencies = [
            'all'    => $currencies,
            'active' => $active_currencies,
            'geo'    => $geo_currencies
        ];

        return $this->currencies;
    }

    protected function prepare_currency( $currency ) {
        $currency->disable_payment_gateways = unserialize( $currency->disable_payment_gateways );
        $currency->disable_countries        = unserialize( $currency->disable_countries );
        $currency->active                   = (bool) $currency->active;
        $flag_attachment                    = wp_get_attachment_image_src( $currency->flag );

        if ( empty( $flag_attachment[0] ) ) {
            $flag_url = x_currency_url( 'media/common/dummy-flag.jpg' );
        } else {
            $flag_url = $flag_attachment[0];
        }

        $currency->flag_url = $flag_url;

        return $currency;
    }

    protected function sort_currencies( array $currencies ) {
        $sort_ids = $this->sort_ids();

        usort(
            $currencies, function( $a, $b ) use ( $sort_ids ) {
                $a_pos = array_search( $a->id, $sort_ids );
                $b_pos = array_search( $b->id, $sort_ids );
                return $a_pos - $b_pos;
            }
        );

        if ( function_exists( 'x_currency_pro' ) || count( $currencies ) <= 3 ) {
            return $currencies;
        }

        $base_idx = array_search( $this->get_base_currency_id() , array_column( $currencies, 'id' ) );

        if ( $base_idx > 1 ) {
            $base_currency = $currencies[$base_idx];
            $currencies    = array_slice( $currencies, 0, 2 );
            array_push( $currencies, $base_currency );
            return $currencies;
        }

        return array_slice( $currencies, 0, 3 );
    }

    public function get_base_currency() {
        if ( ! empty( $this->base_currency ) ) {
            return $this->base_currency;
        }
        $this->base_currency = Currency::query()->where( 'id', $this->get_base_currency_id() )->first();
        return $this->base_currency;
    }

    private function get_base_currency_id() {
        $base_currency_option_key = x_currency_config()->get( 'app.base_currency_option_key' );
        return get_option( $base_currency_option_key );
    }

    public function get_by_first( string $field, $value, $all = false, $refresh = false ) {
        if ( $all ) {
            $currencies = $this->get_all( $refresh );
        } else {
            $currencies = $this->get_geo( $refresh );
        }

        $key = array_search( $value, array_column( $currencies, $field ) );
        if ( ! is_int( $key ) ) {
            return null;
        }
        return $currencies[$key];
    }

    public function update_base_currency( int $currency_id ) {
        $base_currency_option_key = x_currency_config()->get( 'app.base_currency_option_key' );
        update_option( $base_currency_option_key, $currency_id );
        $setting_repository        = x_currency_singleton( SettingRepository::class );
        $settings                  = $setting_repository->db_settings();
        $settings['base_currency'] = $currency_id;
        $setting_repository->update_settings( $settings );
    }

    public function create( array $data ) {

        $currency_code = str_replace( " ", "", strtoupper( $data['code'] ) );

        $currency = Currency::query()->where( 'code', $currency_code )->first();

        if ( ! empty( $currency ) ) {
            throw new Exception( esc_html__( 'This currency code already exists', 'x-currency' ), 500 );
        }

        $currency_id = Currency::query()->insert_get_id(
            [
                'active'                   => (bool) $data['active'],
                'name'                     => $data['name'],
                'code'                     => $currency_code,
                'symbol'                   => $data['symbol'],
                'flag'                     => $data['flag'],
                'rate'                     => $this->normalize_rate( $data['rate'] ),
                'rate_type'                => $data['rate_type'],
                'extra_fee'                => (float) $data['extra_fee'],
                'extra_fee_type'           => $data['extra_fee_type'],
                'thousand_separator'       => $data['thousand_separator'],
                'max_decimal'              => $data['max_decimal'],
                'rounding'                 => $data['rounding'],
                'decimal_separator'        => $data['decimal_separator'],
                'symbol_position'          => $data['symbol_position'],
                'disable_payment_gateways' => serialize( $data['disable_payment_gateways'] ),
                'geo_countries_status'     => 'disable',
                'disable_countries'        => serialize( [] ),
                'welcome_country'          => '',
            ]
        );

        $sort_ids   = $this->sort_ids();
        $sort_ids[] = $currency_id;

        $this->update_sort_ids( $sort_ids );

        return $currency_id;
    }

    private function normalize_rate( $rate ) {
        return number_format( $rate, 12, '.', '' );
    }

    public function update( array $data ) {

        $currency_code = str_replace( " ", "", strtoupper( $data['code'] ) );
        $currency_id   = (int) $data['id'];
        $currency      = Currency::query()->where( 'code', $currency_code )->first();

        if ( $currency->id != $currency_id ) {
            throw new Exception( esc_html__( 'This currency code already exists', 'x-currency' ), 500 );
        }

        Currency::query()->where( 'id', $currency_id )->update(
            [
                'active'                   => (bool) $data['active'],
                'name'                     => $data['name'],
                'code'                     => $currency_code,
                'symbol'                   => $data['symbol'],
                'flag'                     => $data['flag'],
                'rate'                     => $this->normalize_rate( $data['rate'] ),
                'rate_type'                => $data['rate_type'],
                'extra_fee'                => (float) $data['extra_fee'],
                'extra_fee_type'           => $data['extra_fee_type'],
                'thousand_separator'       => $data['thousand_separator'],
                'max_decimal'              => $data['max_decimal'],
                'rounding'                 => $data['rounding'],
                'decimal_separator'        => $data['decimal_separator'],
                'symbol_position'          => $data['symbol_position'],
                'disable_payment_gateways' => serialize( $data['disable_payment_gateways'] )
            ]
        );
    }

    public function sort_ids():array {
        $sort_ids = unserialize( get_option( x_currency_config()->get( 'app.sort_ids_option_key' ) ) );
        return $sort_ids !== false ? $sort_ids : [];
    }

    public function update_sort_ids( array $ids ):void {
        update_option( x_currency_config()->get( 'app.sort_ids_option_key' ), serialize( $ids ) );
    }

    public function query( $ids, $type ) {
        $currency = Currency::query()->where_in( 'id', $ids );
        switch ( $type ) {
            case 'active':
                $currency->update(
                    [
                        'active' => true
                    ]
                );
                break;
            case 'deactive':
                $currency->update(
                    [
                        'active' => false
                    ]
                );
                break;
            case 'delete':
                $currency->delete();

                $sort_ids     = array_diff( $this->sort_ids(), $ids ); // remove ids form sortlist
                $new_sort_ids = [];
                foreach ( $sort_ids as $sort_id ) {
                    array_push( $new_sort_ids, $sort_id );
                }
                $this->update_sort_ids( $new_sort_ids );
                break;
            case 'sort':
                $this->update_sort_ids( $ids );
        }
    }
}