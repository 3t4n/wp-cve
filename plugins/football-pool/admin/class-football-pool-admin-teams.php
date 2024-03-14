<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Admin_Teams extends Football_Pool_Admin {
	public function __construct() {}
	
	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can add, change or delete teams.</p><p>Only <em>\'Active\'</em> teams are shown on the teams page in the blog. The <em>\'real\'</em> column indicates if the team is a real team in the tournament or a temporary placeholder for a match that is not yet set (e.g. Winner Group A).</p>', 'football-pool' )
					),
					array(
						'id' => 'details',
						'title' => __( 'Team details', 'football-pool' ),
						'content' => __( '<ul><li><em>photo</em> is used on the team page.</li><li><em>flag</em> is used in the standing table and the match overview.</li><li>If <em>link</em> is filled the team name on the team\'s page will link to this address.</li><li>The <em>comments</em> field can be used to add some extra info about the team. The info is shown on the team\'s page.</li></ul>', 'football-pool' )
					),
				);
		/** @noinspection HtmlUnknownAnchorTarget */
		$help_sidebar = '<a href="?page=footballpool-help#teams-groups-and-matches">Help section about teams</a>';
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}

	/** @noinspection PhpMissingBreakStatementInspection */
	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( __( 'Teams', 'football-pool' ), $subtitle, 'add new' );
		self::intro( __( 'Add, change or delete teams.', 'football-pool' ) );
		self::intro( __( 'If you delete a team all matches for the team and predictions for those matches are also deleted. After a delete action the scores in the pool are recalculated.', 'football-pool' ) );
		
		$item_id = Football_Pool_Utils::request_int( 'item_id', 0 );
		$bulk_ids = Football_Pool_Utils::post_int_array( 'itemcheck' );
		$action = Football_Pool_Utils::request_string( 'action', 'list' );

		$notice = $nr = '';
		
		if ( count( $bulk_ids ) > 0 && $action == '-1' )
			$action = Football_Pool_Utils::request_string( 'action2', 'list' );

		switch ( $action ) {
			case 'activate':
			case 'deactivate':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $item_id > 0 ) {
					self::activate( $item_id, $action );
					if ( $action === 'activate' )
						$notice = __( 'Team %d activated.', 'football-pool' );
					else
						$notice = __( 'Team %d deactivated.', 'football-pool' );
					
					$nr = $item_id;
				}
				if ( count( $bulk_ids) > 0 ) {
					self::activate( $bulk_ids, $action );
					if ( $action == 'activate' )
						$notice = __( '%d teams activated.', 'football-pool' );
					else
						$notice = __( '%d teams deactivated.', 'football-pool' );
					
					$nr = count( $bulk_ids );
				}
				
				if ( $notice !== '' ) self::notice( sprintf( $notice, $nr ) );
				self::view();
				break;
			case 'save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				// new or updated team
				$item_id = self::update( $item_id );
				self::notice( __( 'Team saved.', 'football-pool' ) );
				if ( Football_Pool_Utils::post_str( 'submit' ) === __( 'Save & Close', 'football-pool' ) ) {
					self::view();
					break;
				}
			case 'edit':
				self::edit( $item_id );
				break;
			case 'delete':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $item_id > 0 ) {
					self::delete( $item_id );
					self::notice( sprintf( __( 'Team id:%s deleted.', 'football-pool' ), $item_id ) );
				}
				if ( count( $bulk_ids) > 0 ) {
					self::delete( $bulk_ids );
					self::notice( sprintf( __( '%s teams deleted.', 'football-pool' ), count( $bulk_ids ) ) );
				}
			default:
				self::view();
		}
		
		self::admin_footer();
	}
	
	private static function edit( $id ) {
		$values = [
			'name' => '',
			'photo' => '',
			'flag' => '',
			'link' => '',
			'group_id' => 0,
			'group_order' => 0,
			'is_real' => 1,
			'is_active' => 1,
			'is_favorite' => 0,
			'comments' => '',
		];
		
		$teams = new Football_Pool_Teams;
		$team = $teams->get_team_by_id( $id );
		if ( $id > 0 && is_object( $team ) && $team->id != 0 ) {
			$values = (array) $team;
		}
		
		$groups = Football_Pool_groups::get_groups();
		$options = [];
		foreach ( $groups as $group ) {
			$options[] = ['value' => $group->id, 'text' => $group->name];
		}
		$groups = $options;
		
		$cols = [
			['text', __( 'name', 'football-pool' ), 'name', $values['name'], ''],
			['image', __( 'photo', 'football-pool' ), 'photo', $values['photo'], sprintf( __( 'Image path must be a full URL to the image. Or a path relative to the football pool upload directory (%s)', 'football-pool' ), trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'teams' ) )],
			['image', __( 'flag', 'football-pool' ), 'flag', $values['flag'], sprintf( __( 'Image path must be a full URL to the image. Or a path relative to the football pool upload directory (%s)', 'football-pool' ), trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'flags' ) )],
			['text', __( 'link', 'football-pool' ), 'link', $values['link'], __( 'A link to a website with information about the team. Used on the team page in de plugin.', 'football-pool' )],
			['multiline', __( 'comments', 'football-pool' ), 'comments', $values['comments'], __( 'An optional text with extra information about the team that is displayed on the team\'s page.', 'football-pool' )],
			['dropdown', __( 'group', 'football-pool' ), 'group_id', $values['group_id'], $groups, ''],
			['integer', __( 'group order', 'football-pool' ), 'group_order', $values['group_order'], __( 'If teams are placed in a group and the default ordering does not work (when teams have the same points) you can fix the ordering with this number.', 'football-pool' )],
			['checkbox', __( 'team is not a temporary team name', 'football-pool' ), 'is_real', $values['is_real'], __( 'Uncheck this box if the team is not a real team, e.g. "Winner match 30".', 'football-pool' )],
			['checkbox', __( 'team is active', 'football-pool' ), 'is_active', $values['is_active'], ''],
			['checkbox', __( 'favorite team', 'football-pool' ), 'is_favorite', $values['is_favorite'], __( 'Favorite teams get an extra CSS class that you can style in your theme.', 'football-pool' )],
			['hidden', '', 'item_id', $id],
			['hidden', '', 'action', 'save']
		];
		self::value_form( $cols );
		echo '<p class="submit">';
		submit_button( __( 'Save & Close', 'football-pool' ), 'primary', 'submit', false );
		submit_button( null, 'secondary', 'save', false );
		self::cancel_button();
		echo '</p>';
	}
	
	private static function view() {
		$items = self::get_teams();

		// search in name
		$search = Football_Pool_Utils::request_string( 's' );
		if ( $search !== '' ) {
			$items = array_filter( $items, function( $v ) use ( $search ) {
				return stripos( $v['name'], $search ) !== false;
			} );
		}

		$cols = [
			['text', __( 'team', 'football-pool' ), 'team', ''],
			['boolean', __( 'active', 'football-pool' ), 'is_active', ''],
			['boolean', __( 'real team', 'football-pool' ), 'is_real', ''],
			['boolean', __( 'favorite team', 'football-pool' ), 'is_favorite', ''],
		];
		
		$rows = [];
		foreach( $items as $item ) {
			$rows[] = [
				$item['name'],
				$item['is_active'],
				$item['is_real'],
				$item['is_favorite'],
				$item['id'],
			];
		}

		$search_box = [
			'text' => __( 'Search', 'football-pool' ),
			'value' => $search,
		];
		$bulkactions[] = ['activate', __( 'Activate team(s)', 'football-pool' ), __( 'You are about to activate one or more teams.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to continue, `Cancel` to stop.', 'football-pool' )];
		$bulkactions[] = ['deactivate', __( 'Deactivate team(s)', 'football-pool' ), __( 'You are about to deactivate one or more teams.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to continue, `Cancel` to stop.', 'football-pool' )];
		$bulkactions[] = ['delete', __( 'Delete' ), __( 'You are about to delete one or more teams.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to delete, `Cancel` to stop.', 'football-pool' )];
		self::list_table( $cols, $rows, $bulkactions, null, false, $search_box );
	}
	
	private static function update( $item_id ) {
		$item = array(
			$item_id,
			Football_Pool_Utils::post_string( 'name' ),
			Football_Pool_Utils::post_string( 'photo' ),
			Football_Pool_Utils::post_string( 'flag' ),
			Football_Pool_Utils::post_string( 'link' ),
			Football_Pool_Utils::post_int( 'group_id' ),
			Football_Pool_Utils::post_int( 'group_order' ),
			Football_Pool_Utils::post_int( 'is_real' ),
			Football_Pool_Utils::post_int( 'is_active' ),
			Football_Pool_Utils::post_int( 'is_favorite' ),
			Football_Pool_Utils::post_string( 'comments' ),
		);

		return self::update_item( $item );
	}
	
	private static function delete( $item_id ) {
		if ( is_array( $item_id ) ) {
			foreach ( $item_id as $id ) self::delete_item( $id );
		} else {
			self::delete_item( $item_id );
		}
		// recalculate scorehistory
		self::update_score_history();
	}
	
	private static function delete_item( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		do_action( 'footballpool_admin_team_delete', $id );
		
		// delete all teams, matches for that team and predictions made for those matches
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}predictions 
								WHERE match_id IN 
									( SELECT id FROM {$prefix}matches WHERE home_team_id = %d OR away_team_id = %d )"
								, $id, $id );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}matches WHERE home_team_id = %d OR away_team_id = %d", $id, $id );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}teams WHERE id = %d", $id );
		$wpdb->query( $sql );
		
		wp_cache_delete( FOOTBALLPOOL_CACHE_TEAMS, FOOTBALLPOOL_WPCACHE_PERSISTENT );
	}
	
	private static function update_item( $input ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		list( $id, $name, $photo, $flag, $link, $group_id, $group_order, $is_real, $is_active, $is_favorite, $comments )
			= $input;
		
		if ( $id == 0 ) {
			$sql = $wpdb->prepare(
				"INSERT INTO {$prefix}teams 
					( name, photo, flag, link, group_id, group_order, is_real, is_active, is_favorite, comments )
				VALUES 
					( %s, %s, %s, %s, %d, %d, %d, %d, %d, %s )",
				$name, $photo, $flag, $link, $group_id, $group_order, $is_real, $is_active, $is_favorite, $comments
			);
		} else {
			$sql = $wpdb->prepare(
				"UPDATE {$prefix}teams SET
					name = %s, photo = %s, flag = %s, link = %s, group_id = %d, group_order = %d, 
					is_real = %d, is_active = %d, is_favorite = %d,
					comments = %s
				WHERE id = %d",
				$name, $photo, $flag, $link, $group_id, $group_order,
				$is_real, $is_active, $is_favorite, $comments,
				$id
			);
		}
		
		$wpdb->query( $sql );
		
		wp_cache_delete( FOOTBALLPOOL_CACHE_TEAMS, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		return ( $id == 0 ) ? $wpdb->insert_id : $id;
	}

	private static function get_teams() {
		$teams = new Football_Pool_Teams;
		$teams = $teams->get_teams();
		$output = [];
		foreach ( $teams as $team ) {
			$output[] = [
				'id' => $team->id,
				'name' => $team->name,
				'is_active' => $team->is_active,
				'is_real' => $team->is_real,
				'is_favorite' => $team->is_favorite,
			];
		}
		return $output;
	}
	
	private static function activate( $team_id, $active = 'activate' ) {
		if ( is_array( $team_id ) ) {
			foreach ( $team_id as $id ) self::activate_team( $id, $active );
		} else {
			self::activate_team( $team_id, $active );
		}
	}

	private static function activate_team( $id, $active = 'activate' ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$active = ( $active === 'activate' ) ? 1 : 0;
		$sql = $wpdb->prepare( "UPDATE {$prefix}teams SET is_active = %d WHERE id = %d"
								, $active, $id );
		$wpdb->query( $sql );
		wp_cache_delete( FOOTBALLPOOL_CACHE_TEAMS, FOOTBALLPOOL_WPCACHE_PERSISTENT );
	}
}
