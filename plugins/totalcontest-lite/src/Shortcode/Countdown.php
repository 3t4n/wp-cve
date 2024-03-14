<?php

namespace TotalContest\Shortcode;

/**
 * Contest shortcode class
 *
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Countdown extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		$contest = $this->getContest();
		$type    = $this->getAttribute( 'type', 'contest' );
		$format  = $this->getAttribute( 'format', '%a days and %h hours' );
		$until   = $this->getAttribute( 'until', 'start' );

		if ( $until === 'start' ):
			$interval = $contest->getTimeLeftToStart( $type );
		elseif ( $until === 'end' ):
			$interval = $contest->getTimeLeftToEnd( $type );
		endif;

		if ( isset( $interval ) && $interval instanceof \DateInterval ):
			return $interval->format( $format );
		endif;
	}

}
