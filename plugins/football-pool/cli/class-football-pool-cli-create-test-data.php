<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

/** @noinspection SqlResolve */

/**
 * Executes functions in the Football Pool plugin.
 */

WP_CLI::add_command( 'football-pool test-data', 'Football_Pool_CLI_Create_Test_Data' );

class Football_Pool_CLI_Create_Test_Data {
	/**
	 * Creates test data in your Football Pool install.
	 *
	 * ## OPTIONS
	 *
	 * [--users=<number>]
	 * : Number of users to create. Defaults to 50. Maximum is 1000.
	 *
	 * [--delete]
	 * : Deletes all the football pool test users and predictions that were created with this CLI command.
	 *
	 * [--yes]
	 * : Answer yes to the confirmation message.
	 *
	 * [--calc]
	 * : Runs a calculation after the command has finished.
	 *
	 * ## EXAMPLES
	 *
	 *     # Create 100 test users and create predictions for existing matches.
	 *     $ wp football-pool test-data --users=100 --yes
	 *     Creating users  100% [===============================================================] 0:52 / 0:51
	 *     Created 100 users with random predictions for 64 matches.
	 *
	 *     # Remove the test users and predictions from the database.
	 *     $ wp football-pool test-data --delete --yes
	 *     Deleting users  100% [===============================================================] 0:08 / 0:08
	 *     Test data deleted.
	 */
	
	public function __invoke( $args, $assoc_args ) {
		$do_calc = ( isset( $assoc_args['calc'] ) && $assoc_args['calc'] === true );
		$delete = ( isset( $assoc_args['delete'] ) && $assoc_args['delete'] === true );
		$users = $assoc_args['users'] ?? 50;
		$this->verbose = ( isset( $assoc_args['verbose'] ) && $assoc_args['verbose'] === true );
		
		if ( $delete ) {
			WP_CLI::confirm( "Are you sure you want to DELETE the test data?", $assoc_args );
			$result = $this->delete();
		} else {
			WP_CLI::confirm( "Are you sure you want to CREATE test users and predictions?", $assoc_args );
			$result = $this->create( $users );
		}
		
		if ( $result && $do_calc ) {
			WP_CLI::runcommand( 'football-pool calc' );
		}
	}

	/*
	  If I put these properties above the __invoke method, then the "wp help football-pool" will not show any
	  content for this CLI command... *insert exploding head*
	*/
	private bool $verbose;
	private string $meta_key = 'footballpool__is_test_user_from_cli_cmd';

	/**
	 * Deletes the test users.
	 *
	 * @return bool
	 */
	private function delete(): bool
	{
		$args = array(
			'meta_query' => array(
				array(
					'key' => $this->meta_key,
					'value' => 'yes',
					'compare' => '='
				)
			)
		);
		$users = get_users( $args );

		if ( $users ) {
			if ( ! $this->verbose ) {
				$progress = \WP_CLI\Utils\make_progress_bar( 'Deleting users', count( $users ) );
			}

			if ( ! function_exists( 'wp_delete_user' ) ) {
				require_once trailingslashit( get_home_path() ) . 'wp-admin/includes/user.php';
			}

			foreach ( $users as $user ) {
				$success = wp_delete_user( $user->ID );
				if ( $this->verbose && $success ) WP_CLI::log( "User ID {$user->ID} deleted." );

				if ( ! $this->verbose ) $progress->tick();
			}

			if ( ! $this->verbose ) $progress->finish();

			if ( ! $this->verbose ) WP_CLI::log( 'Test data deleted.' );
		} else {
			if ( $this->verbose ) WP_CLI::log ( 'No test users found.' );
		}

		return true;
	}

	/**
	 * Creates test users and sets random predictions for each user for existing matches in the database.
	 *
	 * @param $users
	 * @return bool
	 */
	private function create( $users ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		// check if users is between 1 and 1000, if not then use the default
		if ( ! is_numeric( $users ) || $users > 1000 || $users <= 0 ) $users = 50;

		/*
		 * We've scraped some surnames and first names from the internet and put them in an array.
		 *
		 * first names:
		 * https://www.ssa.gov/oact/babynames/decades/century.html
		 *
		 * surnames:
		 * https://en.wikipedia.org/wiki/List_of_most_common_surnames_in_North_America#United_States_(American)
		 */
		$first_names = explode( ',', 'James,Mary,John,Patricia,Robert,Jennifer,Michael,Linda,William,Elizabeth,David,Barbara,Richard,Susan,Joseph,Jessica,Thomas,Sarah,Charles,Karen,Christopher,Nancy,Daniel,Lisa,Matthew,Margaret,Anthony,Betty,Donald,Sandra,Mark,Ashley,Paul,Dorothy,Steven,Kimberly,Andrew,Emily,Kenneth,Donna,Joshua,Michelle,Kevin,Carol,Brian,Amanda,George,Melissa,Edward,Deborah,Ronald,Stephanie,Timothy,Rebecca,Jason,Laura,Jeffrey,Sharon,Ryan,Cynthia,Jacob,Kathleen,Gary,Amy,Nicholas,Shirley,Eric,Angela,Jonathan,Helen,Stephen,Anna,Larry,Brenda,Justin,Pamela,Scott,Nicole,Brandon,Samantha,Benjamin,Katherine,Samuel,Emma,Frank,Ruth,Gregory,Christine,Raymond,Catherine,Alexander,Debra,Patrick,Rachel,Jack,Carolyn,Dennis,Janet,Jerry,Virginia,Tyler,Maria,Aaron,Heather,Jose,Diane,Henry,Julie,Adam,Joyce,Douglas,Victoria,Nathan,Kelly,Peter,Christina,Zachary,Lauren,Kyle,Joan,Walter,Evelyn,Harold,Olivia,Jeremy,Judith,Ethan,Megan,Carl,Cheryl,Keith,Martha,Roger,Andrea,Gerald,Frances,Christian,Hannah,Terry,Jacqueline,Sean,Ann,Arthur,Gloria,Austin,Jean,Noah,Kathryn,Lawrence,Alice,Jesse,Teresa,Joe,Sara,Bryan,Janice,Billy,Doris,Jordan,Madison,Albert,Julia,Dylan,Grace,Bruce,Judy,Willie,Abigail,Gabriel,Marie,Alan,Denise,Juan,Beverly,Logan,Amber,Wayne,Theresa,Ralph,Marilyn,Roy,Danielle,Eugene,Diana,Randy,Brittany,Vincent,Natalie,Russell,Sophia,Louis,Rose,Philip,Isabella,Bobby,Alexis,Johnny,Kayla,Bradley,Charlotte' );
		$last_names = explode( ',', 'Smith,Johnson,Williams,Brown,Jones,Miller,Davis,Garcia,Rodriguez,Wilson,Martinez,Anderson,Taylor,Thomas,Hernandez,Moore,Martin,Jackson,Thompson,White,Lopez,Lee,Gonzalez,Harris,Clark,Lewis,Robinson,Walker,Perez,Hall,Young,Allen,Sanchez,Wright,King,Scott,Green,Baker,Adams,Nelson,Hill,Ramirez,Campbell,Mitchell,Roberts,Carter,Phillips,Evans,Turner,Torres,Parker,Collins,Edwards,Stewart,Flores,Morris,Nguyen,Murphy,Rivera,Cook,Rogers,Morgan,Peterson,Cooper,Reed,Bailey,Bell,Gomez,Kelly,Howard,Ward,Cox,Diaz,Richardson,Wood,Watson,Brooks,Bennett,Gray,James,Reyes,Cruz,Hughes,Price,Myers,Long,Foster,Sanders,Ross,Morales,Powell,Sullivan,Russell,Ortiz,Jenkins,Gutierrez,Perry,Butler,Barnes,Fisher' );

		if ( ! $this->verbose ) $progress = \WP_CLI\Utils\make_progress_bar( 'Creating users', $users );

		$new_users = array();
		for ( $i = 1; $i <= $users; $i++ ) {
			// pick a random first name
			$k = array_rand( $first_names );
			$first_name = $first_names[$k];

			// pick a random surname
			$k = array_rand( $last_names );
			$last_name = $last_names[$k];

			$user_name = strtolower( $first_name . $last_name );
			$email = "{$user_name}_{$i}@footballpooltestdata.com";
			$password = wp_generate_password( 16, true, false );

			$id = wp_insert_user(
				array(
					'user_login' => "{$user_name}_{$i}",
					'first_name' => $first_name,
					'last_name' => $last_name,
					'user_pass' => $password,
					'user_email' => $email,
					'nickname' => $user_name,
					'display_name' => "{$first_name} {$last_name}",
				)
			);

			if ( is_wp_error( $id ) ) {
				if ( $this->verbose ) {
					WP_CLI::log( "User nr {$i} '{$first_name} {$last_name}' creation failed." );
					WP_CLI::log( $id->get_error_message() );
				}
			} else {
				// set a value in the user meta so we can easily distinguish between test users and real users
				update_user_meta( $id, $this->meta_key, 'yes' );
				$new_users[] = $id;
				if ( $this->verbose ) WP_CLI::log( "User ID {$id} '{$first_name} {$last_name}' created." );
			}

			if ( ! $this->verbose ) $progress->tick();
		}

		if ( ! $this->verbose ) $progress->finish();

		$num_users = count( $new_users );
		if ( $num_users > 0 ) {
			// get all match id's
			$sql = "SELECT id FROM {$prefix}matches";
			$matches = $wpdb->get_col( $sql );
			$num_matches = count( $matches );

			// delete old predictions for these users (if any)
			$user_str = implode( ',', $new_users );
			$sql = "DELETE FROM {$prefix}predictions WHERE user_id IN ( {$user_str} )";
			$wpdb->query( $sql );

			foreach ( $new_users as $user ) {
				// set predictions (random scores between 0 and 4)
				// FLOOR(RAND()*(b-a+1))+a <-- a = 0 & b = 4
				$random_score = 'FLOOR( RAND() * 5 )';
				$sql = $wpdb->prepare( "INSERT INTO {$prefix}predictions
											( user_id, match_id, home_score, away_score, has_joker )
										SELECT
										    %d, id, {$random_score}, {$random_score}, 0
										FROM {$prefix}matches"
										, $user );
				$wpdb->query( $sql );

				// set a random joker
				$k = array_rand( $matches );
				$sql = $wpdb->prepare( "UPDATE {$prefix}predictions SET has_joker = 1
										WHERE match_id = %d AND user_id = %d"
										, $matches[$k], $user );
				$wpdb->query( $sql );
			}

			WP_CLI::log( "Created {$num_users} users with random predictions for {$num_matches} matches." );
			return true;
		} else {
			WP_CLI::log( 'No users created!' );
			return false;
		}
	}
}
