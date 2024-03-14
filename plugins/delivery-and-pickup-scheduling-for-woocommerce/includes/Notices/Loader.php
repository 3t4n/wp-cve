<?php
/**
 * Load Notices to admin notices hook.
 *
 * Author:         Uriahs Victor
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Notices
 */

namespace Lpac_DPS\Notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Notices\Upsells_Notices;
use Lpac_DPS\Notices\Review_Notices;

/**
 * The Loader class.
 */
class Loader {

	/**
	 * Load our notices.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_notices() {
		if ( get_current_user_id() !== 1 ) { // Show only to main admin.
			return;
		}
		( new Upsells_Notices() );
		( new Review_Notices() );
	}
}
