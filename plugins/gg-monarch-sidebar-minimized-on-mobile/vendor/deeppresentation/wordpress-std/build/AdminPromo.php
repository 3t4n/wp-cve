<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Arr;



class AdminPromo {


	public static function reset_actived_state( string $optionsGroup, string $optionId = 'activated_time' ) {
		$now = new \DateTime( 'now' );
		Settings::update_setting_array( $optionsGroup, $optionId, $now->getTimestamp(), true );
	}

	public static function set_a4r_state( string $cookieName, bool $reset = false, int $days = 30 ) {
		setcookie( $cookieName, $reset ? '0' : '1', time() + ( 86400 * $days ), '/' ); // 86400 = 1 day
	}

	public static function reset_promo_states( string $optionsGroup, string $timeOptionId = 'activated_time' ) {
		delete_option( $optionsGroup );
		self::reset_actived_state( $optionsGroup, $timeOptionId );
	}

	public static function is_activated_more_then_days( string $optionsGroup, float $days, bool $def = false, string $optionId = 'activated_time' ) {
		$diffDays = self::get_activated_days( $optionsGroup, null );
		if ( $diffDays ) {
			return $diffDays > $days;
		} else {
			return $def;
		}
	}

	public static function get_activated_days( string $optionsGroup, $def = 0, string $optionId = 'activated_time' ) {
		$activated_time = (int) Settings::get_setting_array_field( $optionsGroup, $optionId, null );
		if ( $activated_time ) {
			$now      = ( new \DateTime( 'now' ) )->getTimestamp();
			$diffSec  = $now - $activated_time;
			$diffDays = $diffSec / 60 / 60 / 24;
			return $diffDays;
		}
		return $def;
	}



	public static function is_right_time_for_ask_4_rating( float $minDaysActivated, string $a4rCookieName, string $optionsGroup, string $timeOptionId = 'activated_time' ) {
		//$options = get_option($optionsGroup, []);
		$a4r_already = Arr::get( $_COOKIE, $a4rCookieName, '0' );
		if ( $a4r_already != '1' ) {
			return self::is_activated_more_then_days( $optionsGroup, $minDaysActivated, false, $timeOptionId );
		}
		return false;
	}

	public static function is_right_time_for_random( float $minDaysActivated, int $randomChance, string $optionsGroup, string $pressedFlagCookieName = '', string $timeOptionId = 'activated_time' ) {
		if ( empty( $pressedFlagCookieName ) || Arr::get( $_COOKIE, $pressedFlagCookieName, '0' ) ) {
			if ( self::is_activated_more_then_days( $optionsGroup, $minDaysActivated, false, $timeOptionId ) ) {
				$randNum = rand( 0, 100 );
				if ( $randNum <= $randomChance ) {
					return true;
				}
			}
		}
		return false;
	}

	public static function backward_comp_add_activated_options( string $optionsGroup, string $timeOptionId = 'activated_time' ) {
		$options = get_option( $optionsGroup, null );
		if ( ! $options ) {
			self::reset_promo_states( $optionsGroup, $timeOptionId );
		}
	}


}
