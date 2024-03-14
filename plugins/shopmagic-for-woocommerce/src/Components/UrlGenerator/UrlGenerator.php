<?php

namespace WPDesk\ShopMagic\Components\UrlGenerator;

/**
 * Uniform interface for generating URLs.
 *
 * @since 3.0.9
 */
interface UrlGenerator {

	/**
	 * Generate absolute URL.
	 *
	 * Implementor MAY support special parameter `_fragment` which will be added to URL as fragment.
	 *
	 * @param string                $path       Path for URL.
	 * @param array<string, scalar> $parameters Query parameters.
	 *
	 * @return string
	 */
	public function generate( string $path = '', array $parameters = [] ): string;
}
