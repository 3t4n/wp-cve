<?php

#namespace WilokeTest;

class AutoPrefix {
	public static function namePrefix( $name ) {
		return strpos( $name, PROJECT_PREFIX ) === 0 ? $name : PROJECT_PREFIX . $name;
	}

	public static function removePrefix( string $name ): string {
		if ( strpos( $name, PROJECT_PREFIX ) === 0 ) {
			$name = str_replace( PROJECT_PREFIX, '', $name );
		}

		return $name;
	}
}
