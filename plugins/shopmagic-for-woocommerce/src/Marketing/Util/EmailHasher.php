<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Util;

/**
 * Simple helper class handling generation and validation of request hash based on user email.
 */
class EmailHasher {

	public function hash( string $email ): string {
		return md5( $email . SECURE_AUTH_SALT );
	}

	public function valid( string $email, string $hash ): bool {
		return $this->hash( $email ) === $hash;
	}
}
