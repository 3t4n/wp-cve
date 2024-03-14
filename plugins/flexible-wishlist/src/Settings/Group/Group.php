<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\Option;

/**
 * Stores information about a group of plugin settings fields.
 */
interface Group {

	/**
	 * @return string
	 */
	public function get_label(): string;

	/**
	 * @return Option[]
	 */
	public function get_fields(): array;
}
