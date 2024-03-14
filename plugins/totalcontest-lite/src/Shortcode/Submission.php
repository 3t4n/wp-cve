<?php

namespace TotalContest\Shortcode;

/**
 * Submission shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Submission extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		return $this->getSubmission()->render();
	}
}