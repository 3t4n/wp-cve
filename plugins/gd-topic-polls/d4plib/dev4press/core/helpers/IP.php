<?php
/**
 * Name:    Dev4Press\v43\Core\Helpers\IP
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v43\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IP {
	protected static $private_ipv4 = array(
		'10.0.0.0/8',
		'127.0.0.0/8',
		'172.16.0.0/12',
		'192.168.0.0/16',
	);

	protected static $private_ipv6 = array(
		'::1/128',
		'fd00::/8',
	);

	protected static $cloudflare_ipv4 = array(
		'173.245.48.0/20',
		'103.21.244.0/22',
		'103.22.200.0/22',
		'103.31.4.0/22',
		'141.101.64.0/18',
		'108.162.192.0/18',
		'190.93.240.0/20',
		'188.114.96.0/20',
		'197.234.240.0/22',
		'198.41.128.0/17',
		'162.158.0.0/15',
		'104.16.0.0/13',
		'104.24.0.0/14',
		'172.64.0.0/13',
		'131.0.72.0/22',
	);

	protected static $cloudflare_ipv6 = array(
		'2400:cb00::/32',
		'2606:4700::/32',
		'2803:f800::/32',
		'2405:b500::/32',
		'2405:8100::/32',
		'2a06:98c0::/29',
		'2c0f:f248::/32',
	);

	public static function is_v4( $ip ) : bool {
		return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
	}

	public static function is_v6( $ip ) : bool {
		return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 );
	}

	public static function is_in_range( $ip, $range ) : bool {
		return self::is_v6( $ip ) ? self::is_ipv6_in_range( $ip, $range ) : self::is_ipv4_in_range( $ip, $range );
	}

	public static function is_ipv4_in_range( $ip, $range ) : bool {
		if ( strpos( $range, '/' ) !== false ) {
			list( $subnet, $mask ) = explode( '/', $range, 2 );

			if ( $mask <= 0 ) {
				return false;
			}

			$ip_binary  = sprintf( '%032b', ip2long( $ip ) );
			$net_binary = sprintf( '%032b', ip2long( $subnet ) );

			return ( substr_compare( $ip_binary, $net_binary, 0, $mask ) === 0 );
		} else {
			if ( strpos( $range, '*' ) !== false ) {
				$lower = str_replace( '*', '0', $range );
				$upper = str_replace( '*', '255', $range );
				$range = "$lower-$upper";
			}

			if ( strpos( $range, '-' ) !== false ) {
				list( $lower, $upper ) = explode( '-', $range, 2 );

				$lower_dec = (float) sprintf( '%u', ip2long( $lower ) );
				$upper_dec = (float) sprintf( '%u', ip2long( $upper ) );
				$ip_dec    = (float) sprintf( '%u', ip2long( $ip ) );

				return ( ( $ip_dec >= $lower_dec ) && ( $ip_dec <= $upper_dec ) );
			}

			return false;
		}
	}

	public static function is_ipv6_in_range( $ip, $range ) : bool {
		list( $subnet, $mask ) = explode( '/', $range, 2 );

		$subnet = inet_pton( $subnet );
		$ip     = inet_pton( $ip );

		$mask_binary = str_repeat( 'f', $mask / 4 );
		switch ( $mask % 4 ) {
			case 0:
				break;
			case 1:
				$mask_binary .= '8';
				break;
			case 2:
				$mask_binary .= 'c';
				break;
			case 3:
				$mask_binary .= 'e';
				break;
		}
		$mask_binary = str_pad( $mask_binary, 32, '0' );
		$mask_binary = pack( 'H*', $mask_binary );

		return ( $ip & $mask_binary ) == $subnet;
	}

	public static function full_ip( $ip ) : string {
		if ( self::is_v4( $ip ) ) {
			return $ip;
		} else if ( self::is_v6( $ip ) ) {
			$hex = bin2hex( inet_pton( $ip ) );

			if ( substr( $hex, 0, 24 ) == '00000000000000000000ffff' ) {
				return long2ip( hexdec( substr( $hex, - 8 ) ) );
			}

			return implode( ':', str_split( $hex, 4 ) );
		}

		return '';
	}

	public static function is_private( $ip = null ) : bool {
		if ( is_null( $ip ) ) {
			$ip = self::visitor();
		}

		if ( strpos( $ip, ':' ) === false ) {
			foreach ( self::$private_ipv4 as $cf ) {
				if ( self::is_ipv4_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		} else {
			foreach ( self::$private_ipv6 as $cf ) {
				if ( self::is_ipv6_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function is_private_regex( $ip = null ) : bool {
		if ( preg_match( '/^((127\.)|(192\.168\.)|(10\.)|(172\.1[6-9]\.)|(172\.2[0-9]\.)|(172\.3[0-1]\.)|(::1)|(fe80::))/', $ip ) ) {
			return true;
		}

		return false;
	}

	public static function is_cloudflare( $ip = null ) : bool {
		if ( is_null( $ip ) ) {
			if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
				$ip = $_SERVER['HTTP_X_REAL_IP'] ?? ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			} else {
				return false;
			}
		}

		if ( empty( $ip ) ) {
			return false;
		}

		if ( strpos( $ip, ':' ) === false ) {
			foreach ( self::$cloudflare_ipv4 as $cf ) {
				if ( self::is_ipv4_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		} else {
			foreach ( self::$cloudflare_ipv6 as $cf ) {
				if ( self::is_ipv6_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function server() : string {
		if ( ! isset( $_SERVER['SERVER_ADDR'] ) ) {
			return '';
		}

		$ip = self::validate( $_SERVER['SERVER_ADDR'] );  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( $ip == '::1' ) {
			$ip = '127.0.0.1';
		}

		return (string) $ip;
	}

	public static function all() : array {
		$keys = array(
			'HTTP_CF_CONNECTING_IP',
			'HTTP_CLIENT_IP',
			'HTTP_X_REAL_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
			'SERVER_ADDR',
		);

		$ips = array();

		foreach ( $keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) ) {
				$ips[ $key ] = sanitize_text_field( $_SERVER[ $key ] );  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			}
		}

		return $ips;
	}

	public static function visitor( $no_local_or_protected = false ) {
		if ( self::is_cloudflare() ) {
			if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
				return self::validate( $_SERVER['HTTP_CF_CONNECTING_IP'], true );  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			}

			return '';
		}

		$keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_REAL_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		$ip = '';

		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $_SERVER ) === true ) {
				$ip = sanitize_text_field( $_SERVER[ $key ] );  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				break;
			}
		}

		if ( $no_local_or_protected ) {
			$ip = self::validate( $ip, true );
		} else {
			if ( $ip == '::1' ) {
				$ip = '127.0.0.1';
			} else if ( $ip != '' ) {
				$ip = self::cleanup( $ip );
			}
		}

		return $ip;
	}

	public static function validate( $ip, $no_local_or_protected = false ) {
		$ips = explode( ',', $ip );

		foreach ( $ips as $_ip ) {
			$_ip = trim( $_ip );

			if ( $no_local_or_protected ) {
				if ( filter_var( $_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
					return $_ip;
				}
			} else {
				if ( filter_var( $_ip, FILTER_VALIDATE_IP ) !== false ) {
					return $_ip;
				}
			}
		}

		return false;
	}

	public static function cleanup( $ip ) {
		if ( preg_replace( '/[^0-9a-fA-F:., ]/', '', $ip ) ) {
			$ips = explode( ',', $ip );

			return trim( $ips[ count( $ips ) - 1 ] );
		} else {
			return false;
		}
	}

	public static function random_ipv4() : string {
		return wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 );
	}
}
