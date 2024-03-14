<?php
/**
 * GoogleAnalytics Controller Core.
 *
 * @package GoogleAnalytics
 */

/**
 * Core Controller.
 */
class Ga_Controller_Core {

	const GA_NONCE_FIELD_NAME = '_gawpnonce';
	const ACTION_PARAM_NAME   = 'ga_action';

	/**
	 * Runs particular action.
	 */
	public function handle_actions() {
		// Nonce verification happens in verify_nonce function.
		$action = false === empty( $_REQUEST[ self::ACTION_PARAM_NAME ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ self::ACTION_PARAM_NAME ] ) ) : null; // phpcs:ignore

		if ( $action ) {
			$class = get_class( $this );
			if ( is_callable(
				array(
					$class,
					$action,
				)
			) ) {
				call_user_func( $class . '::' . $action );
			}
		}
	}

	/**
	 * Verifies nonce for given action.
	 *
	 * @param string $action Action.
	 * @return bool
	 */
	public static function verify_nonce( $action ) {
		$nonce = filter_input( INPUT_POST, self::GA_NONCE_FIELD_NAME, FILTER_SANITIZE_STRING );

		return false !== wp_verify_nonce( $nonce, $action );
	}
}
