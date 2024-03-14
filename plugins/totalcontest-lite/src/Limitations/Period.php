<?php

namespace TotalContest\Limitations;

use TotalContestVendors\TotalCore\Limitations\Limitation;

/**
 * Class Period
 * @package TotalContest\Limitations
 */
class Period extends Limitation {
	/**
	 * @return bool|\WP_Error
	 */
	public function check() {
		$startDate = empty( $this->args['start'] ) ? false : TotalContest( 'datetime', [ $this->args['start'], wp_timezone() ] );
		$endDate   = empty( $this->args['end'] ) ? false : TotalContest( 'datetime', [ $this->args['end'], wp_timezone() ] );
		$now       = TotalContest( 'datetime', [ 'now', wp_timezone() ] );


		if ( $startDate && $startDate->getTimestamp() > $now->getTimestamp() ):
			$interval = $startDate->diff( $now, true );
			$message  = empty( $this->args['startMessage'] ) ? esc_html__( 'Not started yet, %s left.', 'totalcontest' ) : $this->args['startMessage'];
			$message  = str_replace( [ '%s', '{{time}}' ], [ '{{time}}', $interval->format( esc_html__( '%a days, %h hours and %i minutes', 'totalcontest' ) ) ], $message );

			return new \WP_Error( 'start_date', $message );
		endif;

		if ( $endDate && $endDate->getTimestamp() < $now->getTimestamp() ):
			$interval = $endDate->diff( $now, true );
			$message  = empty( $this->args['endMessage'] ) ? esc_html__( 'Finished since %s.', 'totalcontest' ) : $this->args['endMessage'];
			$message  = str_replace( [ '%s', '{{time}}' ], [ '{{time}}', $interval->format( esc_html__( '%a days, %h hours and %i minutes', 'totalcontest' ) ) ], $message );

			return new \WP_Error( 'finish_date', $message );
		endif;

		return true;
	}
}
