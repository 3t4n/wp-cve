<?php

namespace CBChangeMailSender\Helpers;

/**
 * Skin for on-the-fly addon installations.
 *
 * @since 1.3.0
 */
class CB_Change_Mail_Sender_Install_Skin extends PluginSilentUpgraderSkin {

	/**
	 * Instead of outputting HTML for errors, json_encode the errors and send them
	 * back to the Ajax script for processing.
	 *
	 * @since 1.3.0
	 *
	 * @param array $errors Array of errors with the install process.
	 */
	public function error( $errors ) {

		if ( ! empty( $errors ) ) {
			wp_send_json_error( $errors );
		}
	}
}
