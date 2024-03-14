<?php
declare(strict_types=1);

namespace WPDesk\FlexibleWishlist\Service;

use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;

/**
 * Generate URL pointing to wishlist with regard to permalink structure.
 */
class UrlGenerator {

	/** @var SettingsRepository */
	private $settings_repository;

	public function __construct( SettingsRepository $settings_repository ) {
		$this->settings_repository = $settings_repository;
	}

	public function generate( string $wishlist_token = '' ): string {
		$endpoint_value = $this->settings_repository->get_value( TextArchiveUrlOption::FIELD_NAME );
		if ( $this->has_slug_permalinks() ) {
			return get_home_url( null, trailingslashit( $endpoint_value ) . $wishlist_token );
		} else {
			return add_query_arg( $endpoint_value, $wishlist_token, get_home_url() );
		}
	}

	/**
	 * WordPress may work in two different modes -- plain permalink and
	 * slug permalinks. It's defined by the permalink_structure option,
	 * where unset value means we are using plain permalinks.
	 */
	private function has_slug_permalinks(): bool {
		return (bool) get_option( 'permalink_structure', false );
	}
}
