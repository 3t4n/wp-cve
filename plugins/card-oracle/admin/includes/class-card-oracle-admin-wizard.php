<?php
/**
 * Debug/Status page
 *
 * @package Card_Oracle/Admin/Wizard
 * @version 1.1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CardOracleAdminWizard Class.
 *
 * @since 1.1.1
 */
class CardOracleAdminWizard {
	/**
	 * Handles output of the reports page in admin.
	 */
	public static function create_reading() {
		global $co_logs, $co_notices;

		$co_logs->add( 'Wizard', 'Inserting data.', null, 'event' );

		$co_wizard_settings = get_option( 'co_wizard', array() );

		$cards_array     = array();
		$positions_array = array();
		$positions_count = 1;

		foreach ( $co_wizard_settings['positions'] as $position ) {
			$position_item['name']  = $position;
			$position_item['order'] = $positions_count++;

			array_push( $positions_array, $position_item );
		}

		foreach ( $co_wizard_settings['cards'] as $card ) {
			$card_item['name'] = $card;

			array_push( $cards_array, $card_item );
		}

		$data = array(
			'reading'   => array(
				'name' => $co_wizard_settings['reading'],
			),
			'positions' => $positions_array,
			'cards'     => $cards_array,
		);

		( new CardOracleDemoData() )->insert_data( $data );
	}

	/**
	 * Handles output of report.
	 */
	public static function wizard_validate() {
		global $co_notices;
		$message = '';

		$co_wizard_settings = get_option( 'co_wizard', array() );

		if ( empty( $co_wizard_settings['reading'] ) ) {
			$message .= esc_html__( 'Please add a title for your Reading.', 'card-oracle' );
		}

		if ( empty( $co_wizard_settings['positions'] ) ) {
			if ( ! empty( $message ) ) {
				$message .= '<br />';
			}
			$message .= esc_html__( 'Please add Positions to your Reading.', 'card-oracle' );
		}

		if ( empty( $co_wizard_settings['cards'] ) ) {
			if ( ! empty( $message ) ) {
				$message .= '<br />';
			}
			$message .= esc_html__( 'Please add Cards to your Reading.', 'card-oracle' );
		}

		if ( ! empty( $message ) ) {
			$co_notices->add( 'wizard-create', $message, 'error' );
			$co_notices->display();
			return false;
		}

		return true;
	}
}
