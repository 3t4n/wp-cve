<?php
/**
 * Usage for handling review requests.
 *
 * @package NS_Cloner
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cloner review class.
 * Handles reviews requests and dismiss actions.
 */
class NS_Cloner_Reviews {

	/**
	 * The obtion name used to check the review status per user.
	 *
	 * @var string
	 */
	private $option_name = 'ns_cloner_reviewed';

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Get the instance of the class.
	 *
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add admin notices as needed for reviews.
	 *
	 * @return bool
	 */
	public function review_request() {
		$user_id = get_current_user_id();
		$review  = $this->get_user_meta( $user_id );

		if ( ! $review || 'yes' !== $review ) {
			return true;
		}
		return false;
	}

	/**
	 * Save the request to hide the review.
	 */
	public function process_review() {
		$user_id = get_current_user_id();
		$this->update_user_meta( $user_id, 'yes' );
	}

	/**
	 * Update user meta.
	 *
	 * @param int   $user_id The user id.
	 * @param array $review The review.
	 */
	private function update_user_meta( $user_id, $review ) {
		update_user_meta( $user_id, $this->option_name, $review );
	}

	/**
	 * Get user meta.
	 *
	 * @param int $user_id The user id.
	 *
	 * @return bool|array
	 */
	private function get_user_meta( $user_id ) {
		return get_user_meta( $user_id, $this->option_name, true );
	}
}
