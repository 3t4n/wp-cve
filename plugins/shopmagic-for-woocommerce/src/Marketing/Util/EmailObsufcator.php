<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Util;

class EmailObsufcator {

	public function obfuscate( string $email ): string {
		[ $local, $domain ] = explode( '@', $email );

		$obfuscate = static function ( string $string ): string {
			$len = \strlen( $string );

			return implode(
				'',
				array_map(
					static function ( int $key, string $value ) use ( $len ): string {
						return ( $key === 0 || $key === $len - 1 ) ? $value : '*';
					},
					array_keys( str_split( $string ) ),
					array_values( str_split( $string ) )
				)
			);
		};

		return $obfuscate( $local ) . '@' . $domain;
	}
}
