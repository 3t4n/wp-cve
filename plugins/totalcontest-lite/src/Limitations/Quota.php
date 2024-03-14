<?php

namespace TotalContest\Limitations;

use TotalContestVendors\TotalCore\Limitations\Limitation;

/**
 * Class Quota
 * @package TotalContest\Limitations
 */
class Quota extends Limitation {
	/**
	 * @return bool|\WP_Error
	 */
	public function check() {
		$quota        = isset( $this->args['value'] ) ? (int) $this->args['value'] : false;
		$currentValue = isset( $this->args['currentValue'] ) ? (int) $this->args['currentValue'] : false;
		$message      = empty( $this->args['message'] ) ? esc_html__( 'The quota has been exceeded.', 'totalcontest' ) : $this->args['message'];

		if ( $quota && $quota > 0 && $quota <= $currentValue ):
			return new \WP_Error( 'quota', $message );
		endif;

		return true;
	}
}
