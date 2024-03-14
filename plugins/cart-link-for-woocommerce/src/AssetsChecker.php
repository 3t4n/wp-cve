<?php

namespace IC\Plugin\CartLinkWooCommerce;

use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;

class AssetsChecker {
	/**
	 * @return bool
	 */
	public function should_register_assets(): bool {
		$current_screen = get_current_screen();

		if ( ! $current_screen ) {
			return false;
		}

		return in_array(
			$current_screen->id,
			[
				RegisterPostType::POST_TYPE,
				'edit-' . RegisterPostType::POST_TYPE,
			],
			true
		);
	}

	/**
	 * @param int    $type     .
	 * @param string $var_name .
	 *
	 * @return mixed
	 * @codeCoverageIgnore
	 */
	protected function filter_input( int $type, string $var_name ) {
		return filter_input( $type, $var_name );
	}
}
