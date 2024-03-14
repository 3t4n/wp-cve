<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual;

class Language {

	/** @var string */
	private $language_tag;

	/** @var string */
	private $code;

	/** @var string|null */
	private $country;

	public function __construct( string $language_tag ) {
		$this->language_tag = trim( $language_tag );
		$tags               = preg_split( '/[-_]/', $this->language_tag, 2 );

		if ( $tags === false ) {
			throw new \InvalidArgumentException( sprintf( 'Language tag could not be parsed. Format needs to adhere to IETF language tag format. Given tag: %s', $language_tag ) );
		}

		if ( isset( $tags[0] ) ) {
			$this->code = strtolower( $tags[0] );
		}

		if ( isset( $tags[1] ) ) {
			$this->country = strtolower( $tags[1] );
		}
	}

	/**
	 * Nondeterministic form, loosely related to IETF language tag. It may be either ISO 639 or
	 * language tag with ISO 3166 country code.
	 *
	 * @see https://en.wikipedia.org/wiki/IETF_language_tag
	 */
	public function get_tag(): string {
		return $this->language_tag;
	}

	/**
	 * ISO 639-1 language code
	 *
	 * @see https://en.wikipedia.org/wiki/ISO_639-1
	 */
	public function get_code(): string {
		return $this->code;
	}

	/**
	 * ISO 3166 country code if found.
	 */
	public function get_country(): ?string {
		return $this->country;
	}

	/**
	 * Loosely compare two languages based on language code. At the moment we don't need strict
	 * comparison, thus leaving country part (or whole tag).
	 */
	public function equals( Language $language ): bool {
		return $this->get_code() === $language->get_code();
	}

	public function __toString(): string {
		return $this->get_code();
	}

}
