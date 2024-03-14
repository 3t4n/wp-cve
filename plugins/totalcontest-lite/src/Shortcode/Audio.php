<?php

namespace TotalContest\Shortcode;

/**
 * Audio shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Audio extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		$source = $this->getAttribute( 'src' );

		return do_shortcode( sprintf( '[audio src="%s"]', esc_attr( $source ) ) );
	}

}
