<?php

namespace WPDesk\ShopMagic\Modules\Mulitilingual\Customer;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerProvider;
use WPDesk\ShopMagic\Customer\Guest\Guest;
use WPDesk\ShopMagic\Customer\Guest\GuestDataAccess;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\Modules\Mulitilingual\Language;
use WPDesk\ShopMagic\Modules\Mulitilingual\LanguageHandler;

/**
 * Stores metadata about Customer language preferences.
 */
final class CustomerLanguagePersistence implements HookProvider, Conditional {
	use HookTrait;

	/** @var LanguageHandler */
	private $language;

	/** @var GuestDataAccess */
	private $guest_manager;

	/** @var CustomerProvider */
	private $customer_provider;

	public function __construct(
		CustomerProvider $customer_provider,
		LanguageHandler $language,
		GuestDataAccess $guest_manager
	) {
		$this->customer_provider = $customer_provider;
		$this->language          = $language;
		$this->guest_manager     = $guest_manager;
	}

	public function hooks(): void {
		$this->add_action( 'wp', [ $this, 'persist_language' ], 100 );
	}

	public function persist_language(): void {
		try {
			$customer = $this->customer_provider->get_customer();
		} catch ( CannotProvideCustomerException $e ) {
			return;
		}

		if ( $customer->is_guest() && $customer instanceof Guest ) {
			$customer->add_meta( Customer::USER_LANGUAGE_META, $this->get_preferred_language() );
			$this->guest_manager->save( $customer );
		} else {
			update_user_meta(
				(int) $customer->get_id(),
				Customer::USER_LANGUAGE_META,
				$this->get_preferred_language()
			);
		}
	}

	/**
	 * @return string First matched language processed from request header. If none matched, use
	 *                default site language.
	 */
	private function get_preferred_language(): string {
		$preferred_languages = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) : '';
		$preferred_languages = $this->parse_preferred_browser_languages( $preferred_languages );

		$matching_languages = $this->get_matching_languages( $preferred_languages );

		return (string) ( $matching_languages->get( 0 ) ?? $this->language->default_language() );
	}

	/**
	 * Parses the HTTP_ACCEPT_LANGUAGE header and returns an array of Language objects.
	 * Expected form of header e.g.: en-US,en;q=0.9,pl;q=0.8,de;q=0.7,fr;q=0.6
	 *
	 * @return Collection<int, Language>
	 */
	private function parse_preferred_browser_languages( string $accepted_languages ): Collection {
		return ( new ArrayCollection( explode( ',', $accepted_languages ) ) )
			->map( static function ( string $language ): Language {
				// We are only interested about the language code, not value qualifier
				return new Language( explode( ';', $language )[0] );
			} );
	}

	/**
	 * @param Collection<int, Language> $preferred_languages
	 *
	 * @return Collection<int, Language>
	 */
	private function get_matching_languages( Collection $preferred_languages ): Collection {
		return $preferred_languages
			->filter(
				function ( Language $lang ) {
					foreach ( $this->language->supported_languages() as $supported_language ) {
						if ( $lang->equals( $supported_language ) ) {
							return true;
						}
					}

					return false;
				}
			)
			// We may have duplicates due to different country codes, which we currently ignore.
			->unique();
	}

	public static function is_needed(): bool {
		return ! is_admin();
	}
}
