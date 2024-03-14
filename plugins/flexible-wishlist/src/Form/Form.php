<?php
/**
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist\Form;

/**
 * Performs the operation initiated by submitting the form.
 */
interface Form {

	/**
	 * @return string
	 */
	public function get_action_name(): string;

	/**
	 * @param mixed[] $form_data .
	 *
	 * @return void
	 */
	public function process_request( array $form_data );
}
