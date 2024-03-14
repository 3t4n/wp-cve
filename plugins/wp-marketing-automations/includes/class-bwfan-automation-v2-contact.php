<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * V2 Automation
 */
final class BWFAN_Automation_V2_Contact {
	private static $ins = null;

	/**
	 * Get instance
	 *
	 * @return BWFAN_Automation_V2_Contact
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'bwfan_execute_automation_contact', array( $this, 'bwfan_ac_execute_action' ) );
	}

	/**
	 * v2 automation contact action callback
	 *
	 * @param $action_id
	 *
	 * @return void|null
	 * @throws Exception
	 */
	public function bwfan_ac_execute_action( $action_id ) {
		try {
			/** Check if Autonami is in sandbox mode */
			if ( true === BWFAN_Common::is_sandbox_mode_active() ) {
				return;
			}

			/** Get the automation contact details */
			$result = BWFAN_Model_Automation_Contact::get_data( $action_id );

			if ( ! is_array( $result ) || 0 === count( $result ) ) {
				$msg = 'No data found in the database. Contact Action ID - ' . $action_id;
				$this->log( $msg );

				return;
			}

			/** Run the controller */
			$ins = new BWFAN_Automation_Controller();
			$ins->set_automation_data( $result );
			$ins->start();

		} catch ( Error $e ) {
			$msg = "Error occurred with message {$e->getMessage()} for action id {$action_id}";
			BWFAN_Common::log_test_data( $msg, 'automation_contact_execution_fail', true );
			throw new Exception( $msg, 1 );
		}
	}

	public function log( $msg ) {
		if ( empty( $msg ) ) {
			return;
		}
		if ( false === apply_filters( 'bwfan_allow_automation_contact_logging', true ) ) {
			return;
		}

		$msg = is_array( $msg ) ? print_r( $msg, true ) : $msg;
		BWFAN_Common::log_test_data( $msg, 'automation_contact_execution', true );
	}
}

BWFAN_Core::register( 'automations_v2_contact', 'BWFAN_Automation_V2_Contact' );