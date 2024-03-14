<?php namespace MSMoMDP\Std\Core;

class Math {

	public static function wrap_around( int $v, int $delta, int $minval, int $maxval ) {
		$mod = $maxval + 1 - $minval;
		$v  += $delta - $minval;
		$v  += ( 1 - intdiv( $v, $mod ) ) * $mod;
		return $v % $mod + $minval;
	}
}
