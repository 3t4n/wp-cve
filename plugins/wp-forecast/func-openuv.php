<?php
/**
 *  This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2018  Hans Matzen  (email : webmaster at tuxlog dot de)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package wp-forecast
 */

if ( ! function_exists( 'openuv_get_data' ) ) {
	/**
	 * Function to get data from openuv.
	 *
	 * @param string $apikey The OpenUV API Key.
	 * @param string $lat The latitutde.
	 * @param string $lon The longitude.
	 */
	function openuv_get_data( $apikey, $lat, $lon ) {
		// check parms.
		if ( trim( $apikey ) == '' || trim( $lat ) == '' || trim( $lon ) == '' ) {
			return array();
		}

		$url1 = 'https://api.openuv.io/api/v1/uv?lat=' . $lat . '&lng=' . $lon; // '&alt=' + alt + '&ozone=' + ozone +
		$url2 = 'https://api.openuv.io/api/v1/forecast?lat=' . $lat . '&lng=' . $lon;

		// Create a stream.
		$opts = array(
			'http' => array(
				'method' => 'GET',
				'header' => "x-access-token: $apikey\r\n",
			),
		);

		$context = stream_context_create( $opts );

		$eheaders = array( 'headers' => array( 'x-access-token' => $apikey ) );

		// Open the file using the HTTP headers set above.
		$file1 = wp_remote_get( $url1, $eheaders );
		if ( is_wp_error( $file1 ) ) {
			return $file1;
		}
		$data = json_decode( $file1['body'], true );

		$file2 = wp_remote_get( $url2, $eheaders );
		if ( is_wp_error( $file2 ) ) {
			return $file2;
		}
		$data2 = json_decode( $file2['body'], true );

		// add forecast data to array.
		$data['result']['forecast'] = $data2['result'];

		// add copyright notice.
		$data['result']['copyright'] = 'UV Data is delivered by openuv.io';

		return $data['result'];
	}
}
