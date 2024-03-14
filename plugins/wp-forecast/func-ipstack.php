<?php
/**
 * This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2018-2022  Hans Matzen  (email : webmaster at tuxlog dot de)
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

if ( ! function_exists( 'ipstack_get_data' ) ) {
	/**
	 * Function to get data from ipstack
	 *
	 * @param string $apikey The ipstack apikey.
	 * @param string $ip The ip adress to get the coordinates for.
	 */
	function ipstack_get_data( $apikey, $ip ) {
		// check parms.
		if ( trim( $apikey ) == '' || trim( $ip ) == '' ) {
			return array();
		}

		$url = "http://api.ipstack.com/$ip?access_key=$apikey";

		// get the location data for the ip.
		// $file = file_get_contents($url, false);.
		$file = wp_remote_get( $url );
		$data = json_decode( $file['body'], true );

		return $data;
	}
}

if ( ! function_exists( 'ipstack_get_visitor_ip' ) ) {
	/**
	 * Function to determine the ip of the visotor of the site.
	 */
	function ipstack_get_visitor_ip() {
		$ip = '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		} else {
			$ip = 'none';
		}

		return $ip;
	}
}



if ( ! function_exists( 'distance_calculation' ) ) {
	/**
	 * Description: Distance calculation from the latitude/longitude of 2 points
	 * Author: Michaël Niessen (2014)
	 * Website: http://AssemblySys.com
	 *
	 * If you find this script useful, you can show your
	 * appreciation by getting Michaël a cup of coffee ;)
	 * PayPal: MichaelNiessen
	 *
	 * As long as this notice (including author name and details) is included and
	 * UNALTERED, this code can be freely used and distributed.
	 *
	 * @param string $point1_lat    the latitude of the first point.
	 * @param string $point1_long   the longitude of the first point.
	 * @param string $point2_lat    the latitude of the second point.
	 * @param string $point2_long   the longitude of the second point.
	 * @param string $unit          the unit.
	 * @param int    $decimals      the number of decimals.
	 */
	function distance_calculation( $point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2 ) {
		// Calculate the distance in degrees.
		$degrees = rad2deg( acos( ( sin( deg2rad( $point1_lat ) ) * sin( deg2rad( $point2_lat ) ) ) + ( cos( deg2rad( $point1_lat ) ) * cos( deg2rad( $point2_lat ) ) * cos( deg2rad( $point1_long - $point2_long ) ) ) ) );

		// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles).
		switch ( $unit ) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km).
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles).
				break;
			case 'nmi':
				$distance = $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles).
		}

		return round( $distance, $decimals );
	}
}


if ( ! function_exists( 'everything_in_tags' ) ) {
	/**
	 * Returns the content of a tag in an xml text string.
	 *
	 * @param string $string String to convert.
	 * @param string $tagname The tagname to use.
	 */
	function everything_in_tags( $string, $tagname ) {
		$pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
		preg_match( $pattern, $string, $matches );
		return $matches[1];
	}
}
