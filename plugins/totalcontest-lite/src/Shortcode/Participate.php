<?php

namespace TotalContest\Shortcode;

/**
 * Participate shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Participate extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		$contest = $this->getContest()->setMenuVisibility( false );

		if ( $contest->getScreen() !== 'contest.thankyou' ):
			$contest->setScreen( 'contest.participate' );
		endif;

		return $contest->render();
	}

}