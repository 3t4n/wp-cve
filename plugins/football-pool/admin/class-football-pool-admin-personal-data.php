<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Admin_Personal_Data {
	/**
	 * Registers all data exporters.
	 *
	 * @param array $exporters
	 *
	 * @return array
	 */
	public static function register_user_data_exporters( array $exporters ): array
	{
		// Match predictions
		$exporters['football-pool-matches'] = array(
			'exporter_friendly_name' => self::friendly_name_prefix() . __( 'Predictions', 'football-pool' ),
			'callback'               => ['Football_Pool_Admin_Personal_Data', 'export_user_data_match_predictions'],
		);
		// Bonus question answers
		$exporters['football-pool-questions'] = array(
			'exporter_friendly_name' => self::friendly_name_prefix() . __( 'Bonus questions', 'football-pool' ),
			'callback'               => ['Football_Pool_Admin_Personal_Data', 'export_user_data_question_answers'],
		);
		// League name
		$exporters['football-pool-league'] = array(
			'exporter_friendly_name' => self::friendly_name_prefix() . __( 'League', 'football-pool' ),
			'callback'               => ['Football_Pool_Admin_Personal_Data', 'export_user_data_league'],
		);

		return $exporters;
	}

	/**
	 * Registers all data erasers.
	 *
	 * @param array $erasers
	 *
	 * @return array
	 */
	public static function register_privacy_erasers( array $erasers ): array
	{
		// Match predictions
		$erasers['football-pool-predictions'] = array(
			'eraser_friendly_name' => self::friendly_name_prefix() . __( 'Predictions', 'football-pool' ),
			'callback'             => ['Football_Pool_Admin_Personal_Data', 'remove_match_predictions'],
		);
		// Bonus question answers
		$erasers['football-pool-questions'] = array(
			'eraser_friendly_name' => self::friendly_name_prefix() . __( 'Bonus questions', 'football-pool' ),
			'callback'             => ['Football_Pool_Admin_Personal_Data', 'remove_question_answers'],
		);

		return $erasers;
	}

	/**
	 * Add "Football Pool: " (translated) to the friendly names.
	 *
	 * @return string
	 */
	private static function friendly_name_prefix(): string
	{
		return __( 'Football Pool', 'football-pool' ) . ': ';
	}

	/**
	 * Removes all stored predictions for the supplied email address.
	 *
	 * @param string $email_address   email address to manipulate
	 * @param int    $page            pagination
	 *
	 * @return array
	 */
	public static function remove_match_predictions( string $email_address, int $page ): array
	{
		$user = get_user_by( 'email', $email_address );

		return self::generic_football_pool_data_remover( 'predictions', $user, $page, 500 );
	}

	/**
	 * Removes all stored user answers for the supplied email address.
	 *
	 * @param string $email_address   email address to manipulate
	 * @param int $page            pagination
	 *
	 * @return array
	 */
	public static function remove_question_answers( string $email_address, int $page ): array
	{
		$user = get_user_by( 'email', $email_address );

		return self::generic_football_pool_data_remover( 'bonusquestions_useranswers', $user, $page, 500 );
	}

	/**
	 * Generic function to delete data from the Football Pool tables.
	 *
	 * @param string $db_table          table where the data is
	 * @param WP_User|boolean $user     WP User object or false if email address could not be found
	 * @param int $page                 pagination
	 * @param int $number               limit to avoid timing out (if applicable)
	 * @return array
	 */
	private static function generic_football_pool_data_remover( string $db_table, $user, int $page, int $number = 500 ): array
	{
		if ( $user !== false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;

			$user_id = $user->ID;

			$sql = $wpdb->prepare( "DELETE FROM {$prefix}{$db_table} WHERE user_id = %d", $user_id );
			$result = $wpdb->query( $sql );

			$items_removed = ( $result !== false && $result > 0 );

			// Tell core if we have more data to work on still.
			$done = true;

			$return = [
				'items_removed'  => $items_removed,
				'items_retained' => false, // always false in this case
				'messages'       => [], // no messages in this case
				'done'           => $done,
			];
		} else {
			$return = [
				'items_removed'  => 0,
				'items_retained' => false, // always false in this case
				'messages'       => [], // no messages in this case
				'done'           => true,
			];
		}

		return $return;
	}

	/**
	 * Export league name for a user using the supplied email.
	 *
	 * @param string $email_address   email address to manipulate
	 * @param int    $page            pagination
	 *
	 * @return array
	 */
	public static function export_user_data_league( string $email_address, int $page = 1 ): array
	{
		$export_items = [];

		$user = get_user_by( 'email', $email_address );
		if ( $user !== false ) {
			// We only gather data if we get a valid user.
			$user_id = $user->ID;

			$pool = new Football_Pool_Pool();
			$league = $pool->get_league_for_user( $user->ID );
			if ( $league > 1 && array_key_exists( $league, $pool->leagues ) ) {
				$league = $pool->league_name( $league );
			} else {
				$league = __( 'unknown', 'football-pool' );
			}

			$data = array(
				array(
					'name'  => self::friendly_name_prefix() . __( 'League', 'football-pool' ),
					'value' => $league,
				),
			);

			$export_items[] = array(
				'group_id'    => 'user',
				'group_label' => __( 'User' ),
				'item_id'     => "football-pool-league-{$user_id}",
				'data'        => $data,
			);
		}

		return array(
			'data' => $export_items,
			'done' => true,
		);
	}

	/**
	 * Export question answers for a user using the supplied email.
	 *
	 * @param string $email_address email address to manipulate
	 * @param int $page pagination
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function export_user_data_question_answers( string $email_address, int $page = 1 ): array
	{
//		$number = 500; // Limit us to avoid timing out
		$done   = true;

		$export_items = [];

		$user = get_user_by( 'email', $email_address );
		if ( $user !== false ) {
			// We only gather data if we get a valid user.
			$user_id = $user->ID;

			$pool = new Football_Pool_Pool();
			$predictions = $pool->get_bonus_questions_for_user( $user_id );

			foreach ( $predictions as $prediction ) {
				// Only add prediction to the export if prediction exists for this user.
				if ( $prediction['has_answer'] === true ) {
					$data = array(
						array(
							'name'  => __( 'Question', 'football-pool' ),
							'value' => $prediction['question'],
						),
						array(
							'name'  => ucfirst( __( 'answer', 'football-pool' ) ),
							'value' => $prediction['user_answer'],
						),
					);

					$export_items[] = array(
						'group_id'          => 'questions',
						'group_label'       => self::friendly_name_prefix() . __( 'Bonus questions', 'football-pool' ),
						'group_description' => __( "User's answers to bonus questions.", 'football-pool' ),
						'item_id'           => "question-{$prediction['id']}",
						'data'              => $data,
					);
				}
			}

			// Tell core if we have more questions to work on still.
//			$done = count( $predictions ) > $number;
		}

		return array(
			'data' => $export_items,
			'done' => $done,
		);
	}

	/**
	 * Export predictions for a user using the supplied email.
	 *
	 * @param string $email_address   email address to manipulate
	 * @param int    $page            pagination
	 *
	 * @return array
	 */
	public static function export_user_data_match_predictions( string $email_address, int $page = 1 ): array
	{
//		$number = 500; // Limit us to avoid timing out
//		$page   = (int) $page;
		$done   = true;

		$export_items = [];

		$user = get_user_by( 'email', $email_address );
		if ( $user !== false ) {
			// We only gather data if we get a valid user.
			$user_id = $user->ID;

			$pool = new Football_Pool_Pool();
			$predictions = $pool->matches->get_match_info_for_user_unfiltered( $user_id );

			foreach ( $predictions as $prediction ) {
				// Only add prediction to the export if prediction exists for this user.
				if ( $prediction['has_prediction'] === true ) {
					$data = array(
						array(
							'name'  => __( 'Match', 'football-pool' ),
							'value' => $prediction['home_team'] . ' - ' . $prediction['away_team'],
						),
						array(
							'name'  => __( 'Prediction', 'football-pool' ),
							'value' => $prediction['home_score'] . ' - ' . $prediction['away_score'],
						),
						array(
							'name'  => __( 'Multiplier', 'football-pool' ),
							'value' => $prediction['has_joker'] ?
								__( 'Yes', 'football-pool' ) : __( 'No', 'football-pool' ),
						),
					);

					$export_items[] = array(
						'group_id'          => 'predictions',
						'group_label'       => self::friendly_name_prefix() . __( 'Predictions', 'football-pool' ),
						'group_description' => __( "User's match predictions.", 'football-pool' ),
						'item_id'     => "prediction-{$prediction['id']}",
						'data'        => $data,
					);
				}
			}

			// Tell core if we have more predictions to work on still.
//			$done = count( $predictions ) > $number;
		}

		return array(
			'data' => $export_items,
			'done' => $done,
		);
	}
}