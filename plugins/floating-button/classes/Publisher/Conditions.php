<?php

namespace FloatingButton\Publisher;

defined( 'ABSPATH' ) || exit;

class Conditions {

	public static function init( $result ): bool {
		$param = ! empty( $result->param ) ? maybe_unserialize( $result->param ) : [];
		$check = [
			'status'   => self::status( $result->status ),
			'mode'     => self::mode( $result->mode ),
		];

		if ( in_array( false, $check, true ) ) {
			return false;
		}

		return true;

	}

	private static function status( $status ): bool {
		return empty( $status );
	}

	private static function mode( $mode ): bool {
		return empty( $mode ) || current_user_can( 'administrator' );
	}

	private static function users( $param ): bool {

		if ( empty( $param['item_user'] ) || $param['item_user'] === '1' ) {
			return true;
		}

		if ( $param['item_user'] === '3' ) {
			return ! is_user_logged_in();
		}

		if ( $param['item_user'] === '2' ) {

			$users = [];

			if ( isset( $param['user_role'] ) ) {
				if ( is_array( $param['user_role'] ) ) {
					foreach ( $param['user_role'] as $key => $value ) {
						if ( empty( $value ) ) {
							continue;
						}
						$users[] = $key;
					}
				} else {
					$users[] = $param['user_role'];
				}
			}


			if ( ! is_user_logged_in() ) {
				return false;
			}
			$current_user = wp_get_current_user();

			$i = 0;

			foreach ( $current_user->roles as $value ) {
				if ( in_array( $value, $users, true ) ) {
					$i ++;
				}
			}

			return ! empty( $i );

		}

		return true;

	}

	private static function browser( $param ): bool {

		if ( empty( $param['browsers'] ) ) {
			return true;
		}

		$browser = self::get_browser_name();
		switch ( $browser ) {
			case 'Opera':
				$check = empty( $param['browsers']['opera'] );
				break;
			case 'Edge':
				$check = empty( $param['browsers']['edge'] );
				break;
			case 'Chrome':
				$check = empty( $param['browsers']['chrome'] );
				break;
			case 'Safari':
				$check = empty( $param['browsers']['safari'] );
				break;
			case 'Firefox':
				$check = empty( $param['browsers']['firefox'] );
				break;
			case 'IE':
				$check = empty( $param['browsers']['ie'] );
				break;
			case 'Other':
				$check = empty( $param['browsers']['other'] );
				break;
			default:
				$check = true;
		}

		return $check;

	}

	private static function get_browser_name(): string {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ( strpos( $user_agent, 'Opera' ) || strpos( $user_agent, 'OPR/' ) ) {
			return 'Opera';
		} elseif ( strpos( $user_agent, 'Edg' ) ) {
			return 'Edge';
		} elseif ( strpos( $user_agent, 'Chrome' ) ) {
			return 'Chrome';
		} elseif ( strpos( $user_agent, 'Safari' ) ) {
			return 'Safari';
		} elseif ( strpos( $user_agent, 'Firefox' ) ) {
			return 'Firefox';
		} elseif ( strpos( $user_agent, 'MSIE' ) || strpos( $user_agent, 'Trident/7' ) ) {
			return 'IE';
		}

		return 'Other';
	}

	private static function language( $param ): bool {

		if ( empty( $param['depending_language'] ) || empty( $param['lang'] ) ) {
			return true;
		}

		$current_locale = get_locale();

		return $current_locale === $param['lang'];

	}

	private static function schedule( $param ): bool {

		if ( empty( $param['weekday'] ) ) {
			return true;
		}

		$count = count( $param['weekday'] );

		for ( $i = 0; $i < $count; $i ++ ) {
			if ( self::check_day( $param, $i ) === true && self::check_time( $param, $i ) === true && self::check_date( $param, $i ) ) {
				return true;
			}
		}

		return false;
	}

	private static function check_day( $param, $i ): bool {

		if ( empty( $param['weekday'][ $i ] ) ||  $param['weekday'][ $i ] === 'none' ) {
			return true;
		}

		$currentDay = date( 'N' );

		return $currentDay === $param['weekday'][ $i ] || empty( $param['weekday'][ $i ] );

	}

	private static function check_time( $param, $i ): bool {

		$start   = (float) str_replace( ':', '.', $param['time_start'][ $i ] );
		$end     = (float) str_replace( ':', '.', $param['time_end'][ $i ] );
		$current = (float) current_time( 'H.i' );

		return $start <= $current && $current <= $end;
	}

	private static function check_date( $param, $i ): bool {

		if ( empty( $param['dates'][ $i ] ) ) {
			return true;
		}

		$current = date( 'Y-m-d' );
		$start   = ! empty( $param['date_start'][ $i ] ) ? $param['date_start'][ $i ] : $current;
		$end     = ! empty( $param['date_end'][ $i ] ) ? $param['date_end'][ $i ] : $current;

		return $start <= $current && $current <= $end;

	}

}