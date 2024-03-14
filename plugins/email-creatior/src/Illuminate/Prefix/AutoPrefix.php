<?php

namespace WilokeEmailCreator\Illuminate\Prefix;

class AutoPrefix {
	public static function namePrefix( $name ) {
		return strpos( $name, WILOKE_EMAIL_CREATOR_PREFIX ) === 0 ? $name : WILOKE_EMAIL_CREATOR_PREFIX . $name;
	}

	public static function removePrefix( string $name ): string {
		if ( strpos( $name, WILOKE_EMAIL_CREATOR_PREFIX ) === 0 ) {
			$name = str_replace( WILOKE_EMAIL_CREATOR_PREFIX, '', $name );
		}

		return $name;
	}
}
