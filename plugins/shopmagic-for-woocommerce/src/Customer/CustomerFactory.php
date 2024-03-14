<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

final class CustomerFactory {
	/** @var string */
	public const GUEST_ID_PREFIX = 'g_';

	/** @param string|int $id */
	public static function id_to_guest_id( $id ): string {
		if ( is_string( $id ) && str_starts_with( self::GUEST_ID_PREFIX, $id ) ) {
			return $id;
		}
		return self::GUEST_ID_PREFIX . $id;
	}

	/** @param string|int $id */
	public static function is_customer_guest_id( $id ): bool {
		return ! is_numeric( $id );
	}

	/** @param string|int $id */
	public static function convert_customer_guest_id_to_number( $id ): int {
		return (int) str_replace( self::GUEST_ID_PREFIX, '', (string) $id );
	}

	public function create_from_user_and_order( \WP_User $user, \WC_Order $order ): \WPDesk\ShopMagic\Customer\UserInOrderContextAsCustomer {
		return new UserInOrderContextAsCustomer( $user, $order );
	}

}
