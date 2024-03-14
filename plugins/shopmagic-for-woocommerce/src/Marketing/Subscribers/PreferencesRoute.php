<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers;

use WPDesk\ShopMagic\Components\UrlGenerator\UrlGenerator;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Marketing\Util\EmailHasher;
use WPDesk\ShopMagic\Marketing\Util\ShouldUseWooCommercePreferencesPage;

final class PreferencesRoute {

	/** @var ShouldUseWooCommercePreferencesPage */
	private $display_strategy;

	/** @var EmailHasher */
	private $email_hasher;

	/** @var UrlGenerator */
	private $url_generator;

	public function __construct(
		ShouldUseWooCommercePreferencesPage $display_strategy,
		EmailHasher $email_hasher,
		UrlGenerator $url_generator
	) {
		$this->display_strategy = $display_strategy;
		$this->email_hasher     = $email_hasher;
		$this->url_generator    = $url_generator;
	}

	public static function get_slug(): string {
		return sanitize_title_with_dashes(
			apply_filters(
				'shopmagic/core/communication_type/account_page_slug',
				'communication-preferences'
			)
		);
	}

	public function create_preferences_url( Customer $customer ): string {
		if ( ! $customer->is_guest() && $this->display_strategy->should_use() ) {
			return wc_get_account_endpoint_url( self::get_slug() );
		}

		return $this->url_generator->generate(
			self::get_slug(),
			[
				'hash' => $this->email_hasher->hash( $customer->get_email() ),
				'id'   => $customer->get_id(),
			]
		);
	}

}
