<?php
declare(strict_types=1);


namespace WPDesk\ShopMagic\Helper;

final class WooCommerceStatusHelper {
	/**
	 * Check if status is allowed.
	 *
	 * @param $allowed_status string[]|string Can also be a set of allowed statuses.
	 * @param $status_to_check string
	 */
	public static function validate_status_field( $allowed_status, $status_to_check ): bool {
		if ( ! \is_array( $allowed_status ) ) {
			$allowed_status = [ $allowed_status ];
		}

		$with_prefix_match = \in_array( 'wc-' . $status_to_check, $allowed_status, true );
		$no_prefix_match   = \in_array( $status_to_check, $allowed_status, true );

		return $with_prefix_match || $no_prefix_match;
	}
}
