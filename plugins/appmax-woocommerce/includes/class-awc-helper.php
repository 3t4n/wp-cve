<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Helper
{
    public static function awc_validate_cpf( $cpf ) {

        $cpf = str_pad( preg_replace( '/[^0-9]/', '', $cpf ), 11, '0', STR_PAD_LEFT );

        if ( strlen( $cpf ) != 11 ) {
            return false;
        }

        if ( preg_match( '/^(\d)\1{10}$/', $cpf ) ) {
            return false;
        }

        for ( $char = 9; $char < 11; $char++ ) {

            for ( $digit = 0, $column = 0; $column < $char; $column++ ) {
                $digit += $cpf{$column} * ( ( $char + 1 ) - $column );
            }

            $digit = ( ( 10 * $digit ) % 11 ) % 10;

            if ( $cpf{$column} != $digit ) {
                return false;
            }
        }

        return true;
    }

    public static function awc_validate_card_number( $card_number ) {

        $card_number = preg_replace( '/[^0-9]/', '', $card_number );

        if ( strlen( $card_number ) != 16 ) {
            return false;
        }

        if ( preg_match( '/^(\d)\1{15}$/', $card_number ) ) {
            return false;
        }

        if (! AWC_Helper::awc_is_digit( $card_number ) ) {
            return false;
        }

        return true;
    }

    public static function awc_is_digit( $number ) {
        return ctype_digit( $number );
    }

    public static function awc_validate_ccv_credit_card( $ccv ) {

        if ( strlen( $ccv ) < 3 || strlen( $ccv ) > 4 ) {
            return false;
        }

        if (! AWC_Helper::awc_is_digit( $ccv ) ) {
            return false;
        }

        return true;
    }

    public static function awc_cpf_unformatted( $value ) {
        return str_replace( ["-", "."], "", $value );
    }

    public static function awc_card_number_unformatted( $value ) {
        return str_replace( " ", "", $value );
    }

    public static function awc_get_currency_symbol()
    {
        return get_woocommerce_currency_symbol();
    }

    public static function awc_get_price_decimals()
    {
        return wc_get_price_decimals();
    }

    public static function awc_get_thousand_separator()
    {
        return wc_get_price_thousand_separator();
    }

    public static function awc_get_decimal_separator()
    {
        return wc_get_price_decimal_separator();
    }

    public static function awc_monetary_format( $value )
    {
        return sprintf("%s %s", AWC_Helper::awc_get_currency_symbol(), AWC_Helper::awc_number_format( $value ) );
    }

    public static function awc_number_format( $value )
    {
        return number_format(
            $value,
            AWC_Helper::awc_get_price_decimals(),
            AWC_Helper::awc_get_decimal_separator(),
            AWC_Helper::awc_get_thousand_separator()
        );
    }

    public static function awc_get_subtotal_cart()
    {
        return WC()->cart->get_subtotal();
    }

    public static function awc_get_shipping_total_cart()
    {
        return WC()->cart->get_shipping_total();
    }

    public static function awc_get_total_cart()
    {
        return WC()->cart->total;
    }

    public static function awc_get_fee_total()
    {
        return WC()->cart->get_fee_total();
    }

    public static function awc_get_discount_total()
    {
        return WC()->cart->get_discount_total();
    }

    public static function awc_first_character_in_upper_case( $string )
    {
        return ucfirst( $string );
    }

    public static function awc_get_translate_status( $status )
    {
        return wc_get_order_status_name( $status );
    }

    public static function awc_encode_object( $object )
    {
        return json_encode( $object );
    }

    public static function awc_decode_object( $object )
    {
        return json_decode( $object );
    }

    public static function awc_clear_input( $string )
    {
        return sanitize_text_field( $string );
    }

    public static function awc_cpf_formatted( $value ) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $value);
    }

    public static function awc_phone_formatted( $value ) {
        return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $value);
    }

    public static function awc_cep_formatted( $value ) {
        return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $value);
    }

    public static function awc_date_time_formatted( $date, $format = 'd/m/Y H:i:s' )
    {
        return date( $format, strtotime( $date ) );
    }

    public static function awc_get_ip()
    {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
        }

        if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
        }

        if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }

        return '';
    }

    public static function awc_get_day_week_textual( $due_date )
    {
        return date('l', strtotime( $due_date ) );
    }

    public static function awc_trim_event( $event )
    {
        $event = explode( "|", $event );
        return trim( $event[0] );
    }
}
