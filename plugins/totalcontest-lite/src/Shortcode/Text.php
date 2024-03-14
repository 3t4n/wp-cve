<?php

namespace TotalContest\Shortcode;

/**
 * Text shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Text extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		return esc_attr( $this->getContent() );
	}

}