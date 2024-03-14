<?php

namespace WPDesk\ShopMagic\Modules\Mulitilingual;

/**
 * Language-aware support for automations.
 *
 * @internal
 */
interface LanguageHandler {

	/**
	 * Get base language set for the site if none other specified.
	 *
	 * @return Language
	 */
	public function default_language(): Language;

	/**
	 * Get list of languages currently supported by the website.
	 *
	 * @return Language[]
	 */
	public function supported_languages(): array;
}
