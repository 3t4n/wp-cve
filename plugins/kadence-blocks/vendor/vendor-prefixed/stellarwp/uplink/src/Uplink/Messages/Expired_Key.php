<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace KadenceWP\KadenceBlocks\StellarWP\Uplink\Messages;

class Expired_Key extends Message_Abstract {
	/**
	 * @inheritDoc
	 */
	public function get(): string {
        $message  = '<div class="notice notice-warning"><p>';
        $message  .= __( 'Your license is expired', '%TEXTDOMAIN%' );
		$message .= '<a href="https://evnt.is/195y" target="_blank" class="button button-primary">' .
			__( 'Renew Your License Now', '%TEXTDOMAIN%' ) .
			'<span class="screen-reader-text">' .
			__( ' (opens in a new window)', '%TEXTDOMAIN%' ) .
			'</span></a>';
        $message .= '</p>    </div>';

		return $message;
	}
}
