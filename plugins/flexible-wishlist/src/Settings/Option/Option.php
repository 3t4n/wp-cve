<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * Stores information about a plugin settings field.
 */
interface Option {

	/**
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * @return string
	 */
	public function get_label(): string;

	/**
	 * @return string|null
	 */
	public function get_description();

	/**
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * @return string[]
	 */
	public function get_options(): array;

	/**
	 * @return string[]
	 */
	public function get_disabled_options(): array;

	/**
	 * @return string|string[]
	 */
	public function get_default_value();

	/**
	 * @param string|string[] $raw_value .
	 *
	 * @return string|string[]
	 */
	public function parse_value( $raw_value );

	/**
	 * @param string|string[] $raw_value .
	 *
	 * @return void
	 */
	public function init_translation( $raw_value );
}
