<?php
/**
 * Watchful debug enabled test.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Tests;

use Watchful\Audit\Audit;

class IsDebugLogAvailable extends Audit {

	/**
	 * @return mixed
	 */
	public function run() {
        if ( file_exists( $this->getLogPath() ) ) {
            return $this->response->send_ko();
        }

        return $this->response->send_ok();
	}

    private function getLogPath()
    {
        if ( in_array( strtolower( (string) WP_DEBUG_LOG ), array( 'true', '1' ), true ) ) {
            return WP_CONTENT_DIR . '/debug.log';
        }

        if ( is_string( WP_DEBUG_LOG ) && !in_array( strtolower( WP_DEBUG_LOG ), array( 'false', '0' ), true ) ) {
            return WP_DEBUG_LOG;
        }

        return WP_CONTENT_DIR . '/debug.log';
    }
}
