<?php
/**
 * Description: API Class supporting apaczka.pl API enpoints.
 * Version:     0.3
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// phpcs:disable
namespace Apaczka;

class Api {
	const SIGN_ALGORITHM = 'sha256';
	const EXPIRES = '+30min';
	const API_URL = 'https://www.apaczka.pl/api/v2/';

	public static $app_id;
	public static $app_secret;

	public static function request( $route, $data = null) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, self::API_URL . $route );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);

		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( self::buildRequest($route, $data) ) );

		$result = curl_exec( $ch );

		if ( $result === false ) {
			curl_close( $ch );

			return false;
		}
		curl_close( $ch );

		return $result;
	}

	public static function buildRequest( $route, $data = [] ) {
		$data = json_encode($data);
		$expires = strtotime( self::EXPIRES );
		return [
			'app_id'    => self::$app_id,
			'request'   => $data,
			'expires'   => $expires,
			'signature' => self::getSignature( self::stringToSign( self::$app_id, $route, $data, $expires ), self::$app_secret )
		];
	}

	public static function order( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function orders ($page = 1, $limit = 10) {
		return self::request( __FUNCTION__ . '/', [
			'page' => $page,
			'limit' => $limit
		]);
	}

	public static function waybill( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function pickup_hours ($postal_code, $service_id = false) {
		return self::request( __FUNCTION__ . '/', [
			'postal_code' => $postal_code,
			'service_id' => $service_id
		]);
	}

	public static function order_valuation ($order) {
		return self::request( __FUNCTION__ . '/', [
			'order' => $order
		]);
	}

	public static function order_send ($order) {
		return self::request( __FUNCTION__ . '/', [
			'order' => $order
		]);
	}

	public static function cancel_order( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function service_structure () {
		return self::request( __FUNCTION__ . '/');
	}

	public static function points ($type = null) {
		return self::request( __FUNCTION__ . '/' . $type . '/');
	}

	public static function customer_register ($customer) {
		return self::request( __FUNCTION__ . '/', [
			'customer' => $customer
		]);
	}

	public static function turn_in( $order_ids = [] ) {
		return self::request( __FUNCTION__ . '/', [
			'order_ids' => $order_ids
		]);
	}

	/**
	 * @param $string
	 * @param $key
	 *
	 * @return string
	 */
	public static function getSignature( $string, $key ) {
		return hash_hmac( self::SIGN_ALGORITHM, $string, $key );
	}

	/**
	 * @param $appId
	 * @param $route
	 * @param $data
	 * @param $expires
	 *
	 * @return string
	 */
	public static function stringToSign( $appId, $route, $data, $expires ) {
		return sprintf( "%s:%s:%s:%s", $appId, $route, $data, $expires );
	}
}
