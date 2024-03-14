<?php
/**
 * WcGetnet Api.
 *
 * @package Wc Getnet
 */

declare(strict_types=1);

namespace WcGetnet\Services;

use WcGetnet\Entities\WcGetnet_Settings as Settings_Entity;
use WcGetnet\Entities\WcGetnet_Logs as Logs_Entity;
use WcGetnet\Services\WcGetnetApi as Service_Api;
use stdClass;
use WC_Order;

class WcGetnetPayment {
	public static function set_customer( WC_Order $order, $method = "credit" ) : stdClass {
		$persontype = $order->get_meta('_billing_cpf') ? 'cpf' : 'cnpj';
		$doc        = Settings_Entity::getDigits( $order->get_meta( "_billing_{$persontype}" ) );

		$customer = [
            'document_type'    => strtoupper( $persontype ),
            'document_number'  => (string) $doc,
            'email'            => (string) $order->get_billing_email(),
            'name'             => (string) $order->get_formatted_billing_full_name(),
            'phone_number'     => (string) Settings_Entity::getDigits( $order->get_billing_phone() ),
            'first_name'       => (string) $order->get_billing_first_name(),
            'last_name'        => (string) $order->get_billing_last_name(),
            'billing_address'  => (object) self::get_checkout_address( $order, 'billing', $method )
        ];

		if ($method == "credit"){
			$customer['shippings'] = (object) self::set_shipping( $order, $method );
		}

        return (object) $customer;
    }

	public static function set_shipping( WC_Order $order, $method ): stdClass {
		return (object) [
			'address' => (object) self::get_checkout_address( $order, 'shipping', $method )
		];
	}

    public static function get_auth_token( $client_ID, $seller_ID, $client_secret ): string {
        $url      = Service_Api::get_environment_url().'/auth/oauth/v2/token';
        $response = wp_remote_post(
            $url,
            [
                'body'    => 'scope=oob&grant_type=client_credentials',
                'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $client_ID . ':' . $client_secret ),
                'seller_id'     => $seller_ID,
            ],
        ]);

        $response_body = wp_remote_retrieve_body( $response );
        $response      = json_decode( $response_body, true );

        if ( isset( $response['error'] ) ) {
            Logs_Entity::token_generate_error( 'GETNET: não foi possível gerar o token de autorização', $response['error_description'] );
        }

        return $response['access_token'];
    }

	public static function get_checkout_address( $order, $address_type = 'billing', $method ) {
		$another_type = 'billing' === $address_type ? 'shipping' : 'billing';

		$street      = $order->{"get_{$address_type}_address_1"}();
		$number      = $order->get_meta( "_${address_type}_number" );
		$complement  = $order->{"get_{$address_type}_address_2"}();
		$district    = $order->get_meta( "_${address_type}_neighborhood" );
		$city        = $order->{"get_{$address_type}_city"}();
		$state       = $order->{"get_${address_type}_state"}();
		$country     = $order->{"get_${address_type}_country"}();
		$postal_code = Settings_Entity::getDigits( $order->{"get_${address_type}_postcode"}() );

		$address = [
			'street'      => $street ? $street : $order->{"get_{$another_type}_address_1"}(),
			'number'      => $number ? $number : $order->get_meta( "_${another_type}_number" ),
			'city'        => $city ? $city : $order->{"get_{$another_type}_city"}(),
			'state'       => $state ? $state : $order->{"get_${another_type}_state"}(),
			'country'     => $country ? $country : $order->{"get_${another_type}_country"}(),
			'postal_code' => $postal_code ? $postal_code : Settings_Entity::getDigits( $order->{"get_${another_type}_postcode"}() )
		];

		if ($method == "credit"){
			$address['type'] = 'RESIDENCIAL';
		}

		$address_dist = $district ? $district : $order->get_meta( "_${another_type}_neighborhood" );

		if ( $address_dist ) {
			$address['district'] = $address_dist;
		} else {
			$address['district'] = "-";
		}

		$address_comp = $complement ? $complement : $order->{"get_{$another_type}_address_2"}();

		if ( $address_comp ) {
			$address['complement'] = $address_comp;
		}

		return (object) $address;
	}

	/**
	 * Render installments options.
	 *
	 * @param int|String $total Checkout amout total.
	 * @param int|String $installments Max installments;
	 * @param int|String $interest Initial installments
	 * @param int|String $interest_increase Increase interest.
	 * @param int|String $no_interest Installments initial interest.
	 * @return string
	 */
	public static function render_installments_options( $total, $installments, $initial_interest, $interest_increase, $no_interest ) {
		$output = sprintf(
			'<option value="1">%1$s</option>',
			__( 'À vista', 'wc_getnet' ) . ' (' . wc_price( $total ) . ')'
		);

		$initial_interest  = self::str_to_float( $initial_interest );
		$interest_increase = self::str_to_float( $interest_increase );
		$no_interest       = self::str_to_float( $no_interest );

		for ( $times = 2; $times <= $installments; $times++ ) {
			$interest = $initial_interest;
			$amount   = $total;

			if ( $interest ) {

				if ( $interest_increase && $times >= ( $no_interest + 1 ) ) {
					$interest += ( $interest_increase * ( $times - $no_interest ) );
				}

				$amount += self::calc_percentage( $interest, $total );
			}

			$value = $amount;

			if ( $times < $no_interest ) {
				$value = $total;
			}

			$price = ceil( $value / $times * 100 ) / 100;
			$text  = sprintf( __( '%dx de %s (%s)', 'wc_getnet' ),
				$times,
				wc_price( $price ),
				wc_price( $value )
			);

			$amount = $total;

			if ( $times >= $no_interest && $interest ) {
				$interest = self::format_currency( $interest );

				$text .= " c/juros de {$interest}%";
			}

			$output .= sprintf( '<option value="%1$s" data-total=%2$s>%3$s</option>', $times, $value, $text );
		}

		return $output;
	}

	/**
	 * Calc percentage
	 *
	 * @param string|float $percentage Percentage.
	 * @param string|float|int $total Total.
	 * @return mixed
	 */
	protected static function calc_percentage( $percentage, $total ) {
		if ( ! $percentage ) {
			return 0;
		}

		$percentage = self::str_to_float( $percentage );

		return ( $percentage / 100 ) * $total;
	}

	protected static function format_currency( $value ) {
		return number_format( $value, 2, ',', '.' );
	}

	/**
	 * Convert str to float.
	 *
	 * @param string $string String.
	 * @return float
	 */
	protected static function str_to_float( $value ) {
		if( is_float($value) || is_int($value) ) {
			return self::format_currency($value);
		}

		return floatval( str_replace( ',', '.', $value ) );
	}
}
