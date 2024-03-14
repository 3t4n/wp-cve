<?php

namespace TotalContestVendors\TotalCore\Shortcodes;

use TotalContestVendors\TotalCore\Contracts\Shortcodes\Shortcode as ShortcodeContract;

/**
 * Shortcodes base class
 * @package TotalContestVendors\TotalCore\Shortcodes
 * @since   1.0.0
 */
abstract class Shortcode implements ShortcodeContract {
	/**
	 * @var array $attributes
	 */
	protected $attributes = [];
	/**
	 * @var string $content
	 */
	protected $content = null;

	/**
	 * Setup shortcode.
	 *
	 * @param      $attributes
	 * @param null $content
	 *
	 * @since 1.0.0
	 */
	public function __construct( $attributes, $content = null ) {
		$this->attributes = (array) $attributes;
		$this->content    = $content;
	}

	/**
	 * Bind shortcode instance to tag.
	 *
	 * @param $tag
	 */
	public static function bind( $tag ) {
		add_shortcode(
			$tag,
			function ( $attributes, $content = null ) {
				return new static( $attributes, $content );
			}
		);
	}

	/**
	 * Get attribute value.
	 *
	 * @param      $name
	 * @param null $default
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	public function getAttribute( $name, $default = null ) {
		return isset( $this->attributes[ $name ] ) ? $this->attributes[ $name ] : $default;
	}

	/**
	 * Get content.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * To string.
	 *
	 * @return string
	 */
	public function __toString() {
		return (string) $this->handle();
	}
}