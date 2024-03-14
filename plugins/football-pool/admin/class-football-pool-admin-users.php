<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Admin_Users extends Football_Pool_Admin {
	public static string $default_order_by = 'display_name';
	public static string $default_order = 'ASC';
	
	public function __construct() {}

	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can add or remove users from the pool.</p><p>Use the bulk actions to add or remove more players at once.</p>', 'football-pool' )
					),
					array(
						'id' => 'leagues',
						'title' => __( 'Leagues', 'football-pool' ),
						'content' => __( '<p>The plugin can use leagues (a league is a group of players in your pool) to group players together. If you are using leagues in the pool an admin has to acknowledge the league for which a player subscribed.</p><p>The <em>\'plays in league\'</em> column shows the league where the user is currently added to; you may change that value. The column <em>\'registered for league\'</em> shows the league the user wants to play in (the user chose this value upon subscribing for the pool).</p>', 'football-pool' )
					),
					array(
						'id' => 'other',
						'title' => __( 'Other options', 'football-pool' ),
						'content' => __( '<p>The <em>\'paid?\'</em> option has no function in the pool, but can be a help for the admin to remember which of the players have paid if you are using a fee for competing in the pool.</p>', 'football-pool' )
					),
				);
		/** @noinspection HtmlUnknownAnchorTarget */
		$help_sidebar = sprintf( '<a href="?page=footballpool-options">%s</a></p><p><a href="?page=footballpool-help#players">%s</a></p><p><a href="?page=footballpool-help#leagues">%s</a>'
								, __( 'Change league settings', 'football-pool' )
								, __( 'Help section about players', 'football-pool' )
								, __( 'Help section about leagues', 'football-pool' )
						);
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}
	
	public static function screen_options() {
		$screen = get_current_screen();
		$args = array(
			'label' => __( 'Users', 'football-pool' ),
			'default' => FOOTBALLPOOL_ADMIN_USERS_PER_PAGE,
			'option' => 'footballpool_users_per_page'
		);
		$screen->add_option( 'per_page', $args );
	}
	
	public static function admin() {
		global $pool;
		
		// check for league settings
		$notice = sprintf( '<strong>%s: </strong>', __( 'Football Pool', 'football-pool' ) ); 
		if ( Football_Pool_Utils::get_fp_option( 'use_leagues', 0, 'int' ) === 1 ) {
			$league_id = Football_Pool_Utils::get_fp_option( 'default_league_new_user', 0, 'int' );
			if ( ! array_key_exists( $league_id, $pool->leagues ) ) {
				$notice .= __( 'Leagues are enabled but the plugin setting for default league is not valid.', 'football-pool' );
				$notice .= ' ';
				/** @noinspection HtmlUnknownTarget */
				$notice .= sprintf( __( 'You can change this setting on the <a href="%s">options page</a>.', 'football-pool' )
									, 'admin.php?page=footballpool-options#r-use_leagues' );
				self::notice( $notice , 'warning', false, true );
			}
		}

		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( sprintf( '%s %s'
									, __( 'Football Pool', 'football-pool' )
									, __( 'Users', 'football-pool' ) 
									)
							, $subtitle );
		
		if ( $pool->has_leagues ) {
			self::intro( __( 'You are using leagues. To exclude users from the pool you have to take them out of any league.', 'football-pool' ) );
		} else {
			self::intro( __( 'To exclude users tick the appropriate column', 'football-pool' ) );
		}
		
		$user_id = Football_Pool_Utils::request_int( 'item_id', 0 );
		$bulk_ids = Football_Pool_Utils::post_int_array( 'itemcheck' );
		$action = Football_Pool_Utils::request_string( 'action', 'list' );

		if ( count( $bulk_ids ) > 0 && $action === '-1' )
			$action = Football_Pool_Utils::request_string( 'action2', 'list' );
		
		if ( Football_Pool_Utils::request_string( 'submit' ) === __( 'Save Changes', 'football-pool' ) ) {
			$action = 'save';
		}
		
		$league_id = Football_Pool_Utils::post_int( 'new_league' );
		if ( $league_id > 0 && count( $bulk_ids ) > 0 
				&& Football_Pool_Utils::request_string( 'changeit' ) === __( 'Change', 'football-pool' ) ) {
			$action = 'bulk_extra';
		}
				
		switch ( $action ) {
			case 'bulk_extra':
				self::bulk_extra( $bulk_ids, $league_id );
				self::notice( __( 'Changes saved.', 'football-pool' ) );
				break;
			case 'list_email':
				self::list_email_addresses();
				break;
			case 'save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				self::update();
				self::notice( __( 'Changes saved.', 'football-pool' ) );
				break;
			case 'remove':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $user_id > 0 ) {
					self::remove( $user_id );
					$user = get_userdata( $user_id );
					self::notice( sprintf( __( '%s deleted as a user.', 'football-pool' ), $user->display_name ) );
				}
				if ( count( $bulk_ids) > 0 ) {
					self::remove( $bulk_ids );
					self::notice( sprintf( __( '%d users removed as user.', 'football-pool' )
											, count( $bulk_ids )
										)
								);
				}
				break;
			case 'add':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $user_id > 0 ) {
					self::add( $user_id );
					$user = get_userdata( $user_id );
					self::notice( sprintf( __( '%s added as a user.', 'football-pool' ), $user->display_name ) );
				}
				if ( count( $bulk_ids) > 0 ) {
					self::add( $bulk_ids );
					self::notice( sprintf( __( '%d users added as user.', 'football-pool' )
											, count( $bulk_ids )
										)
								);
				}
				break;
		}
		
		if ( in_array( $action, array( 'add', 'remove', 'save' ) ) ) {
			check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
			self::update_score_history();
		}
		
		if ( $action !== 'list_email' ) self::view();
		self::admin_footer();
	}
	
	private static function get_users( $offset = 0, $number = 0, $search = '', $search_by = 'name', $league_id = 0 ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$output = array();
		$excluded_players = array();
		$league_users = array();
		
		$sql = "SELECT user_id, league_id FROM {$prefix}league_users";
		if ( $league_id > 0 ) $sql .= " WHERE league_id = {$league_id}";
		$users = $wpdb->get_results( $sql, ARRAY_A );
		foreach ( $users as $user ) {
			$league_users[$user['user_id']] = $user['league_id'];
			if ( $user['league_id'] == 0 )
				$excluded_players[] = $user['user_id'];
		}
		
		$args = array( 'orderby' => self::$default_order_by, 'order' => self::$default_order );
		if ( $number > 0 ) {
			$args['offset'] = $offset;
			$args['number'] = $number;
		}
		if ( $search !== '' ) {
			if ( $search_by === 'name' ) {
				// defaults to name search
				$args['search'] = "*{$search}*";
			} else {
				// but we can also search for meta key in the user meta
				$args['meta_query'] = array(
					'relation' => 'OR',
					array(
						'key' => $search_by,
						'value' => $search,
						'compare' => 'LIKE',
					)
				);
			}
		}
		if ( $league_id > 0 ) {
			$user_ids = $wpdb->get_col( $sql );
			$args['include'] = count( $user_ids ) ? $user_ids : array( 0 );
		}

		$users = get_users( $args );
		
		foreach ( $users as $user ) {
			$user_meta = get_user_meta( $user->ID );
			$league_id = Football_Pool_Utils::get_user_meta( $user_meta, 'footballpool_registeredforleague', -1 );
			if ( array_key_exists( $league_id, $pool->leagues ) ) {
				$league_name = Football_Pool_Utils::xssafe( $pool->leagues[$league_id]['league_name'] );
			} else {
				$league_name = __( 'unknown', 'football-pool' );
			}
			
			$plays_in_league = array_key_exists( $user->ID, $league_users ) ? $league_users[$user->ID] : 0;
			$is_no_player = in_array( $user->ID, $excluded_players ) ? 1 : 0;
			if ( $pool->has_leagues ) {
				$is_no_player = ( $is_no_player || $plays_in_league == 0 ) ? 1 : 0; 
			}
			
			$output[] = array(
				'id'					=> $user->ID,
				'name'					=> $user->display_name,
				//'plays_in_league'		=> Football_Pool_Utils::get_user_meta( $user_meta, 'footballpool_league' ),
				'plays_in_league'		=> $plays_in_league,
				'subscribed_for_league'	=> $league_name,
				'is_no_player'			=> $is_no_player,
				'paid_for_pool'			=> Football_Pool_Utils::get_user_meta( $user_meta, 'footballpool_payed', 0 ),
				'email_address'			=> $user->user_email,
				'all_user_meta'			=> $user_meta,
			);
		}
		
		return $output;
	}
	
	private static function view() {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$has_leagues = $pool->has_leagues;
		
		$search = Football_Pool_Utils::request_string( 's' );
		$league_id = Football_Pool_Utils::request_int( 'league_search' );
		$search_by = Football_Pool_Utils::request_string( 'search_by', 'name' );
		
		// optionally extend the search for users with meta key search
		$search_options = array(
							'name' => 'search by name',
						);
		$search_options = apply_filters( 'footballpool_admin_users_search_options', $search_options );
		
		if ( $search !== '' ) {
			if ( $search_by === 'name' ) {
				// default is search by name
				$args['search'] = "*{$search}*";
			} else {
				// but we can extend the search options and then also search the meta keys
				$args['meta_query'] = array(
					'relation' => 'OR',
					array(
						'key' => $search_by,
						'value' => $search,
						'compare' => 'LIKE',
					)
				);
			}
			// only include users from this league
			if ( $league_id > 0 ) {
				$sql = $wpdb->prepare( "SELECT user_id FROM {$prefix}league_users WHERE league_id = %d", $league_id );
				$user_ids = $wpdb->get_col( $sql );
				$args['include'] = count( $user_ids ) > 0 ? $user_ids : [0];
			}
			$users = get_users( $args );
			$num_users = count( $users );
		} else {
			$sql = "SELECT COUNT( * ) FROM {$wpdb->users} u ";
			if ( $league_id > 0 ) {
				$sql .= "JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id AND lu.league_id = %d )";
				$sql = $wpdb->prepare( $sql, $league_id );
			}
			$num_users = $wpdb->get_var( $sql );
		}
		
		$pagination = new Football_Pool_Pagination( $num_users );
		$pagination->set_page_size( self::get_screen_option( 'per_page' ) );
		$pagination->add_query_arg( 's', $search );
		$pagination->add_query_arg( 'league_search', $league_id );
		$pagination->add_query_arg( 'search_by', $search_by );
		
		$users = self::get_users( 
			( $pagination->current_page - 1 ) * $pagination->get_page_size(),
			$pagination->get_page_size(),
			$search,
			$search_by,
			$league_id
		);
		
		$cols = [];
		$cols[] = ['text', __( 'name', 'football-pool' ), 'name', ''];
		if ( $has_leagues ) {
			$cols[] = ['select', __( 'plays in league', 'football-pool' ), 'plays_in_league', ''];
			$cols[] = ['text', __( 'registered for league', 'football-pool' ), 'subscribed_for_league', ''];
		} else {
			$cols[] = ['checkbox', __( 'not a user in the pool', 'football-pool' ), 'is_no_player', ''];
		}
		$cols[] = ['checkbox', __( 'paid?', 'football-pool' ), 'paid_for_pool', ''];
		$cols = apply_filters( 'footballpool_admin_users_view_cols', $cols );
		
		$rows = [];
		$user_list = [];
		foreach( $users as $user ) {
			$user_list[] = $user['id'];
			$temp = [];
			$temp[] = $user['name'];
			if ( $has_leagues ) {
				$temp[] = $user['plays_in_league'];
				$temp[] = $user['subscribed_for_league'];
			} else {
				$temp[] = $user['is_no_player'];
			}
			$temp[] = $user['paid_for_pool'];
			$temp[] = $user['id'];
			
			$rows[] = $temp;
		}
		$rows = apply_filters( 'footballpool_admin_users_view_rows', $rows, $users );
		
		$rowactions[] = array( 'add', __( 'Add', 'football-pool' ) );
		$rowactions[] = array( 'remove', __( 'Remove', 'football-pool' ) );
		$bulkactions[] = array( 'add', __( 'Add to football pool', 'football-pool' ), __( 'You are about to add one or more users to the pool.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to continue, `Cancel` to stop.', 'football-pool' ) );
		$bulkactions[] = array( 'remove', __( 'Remove from football pool', 'football-pool' ), __( 'You are about to remove one or more users from the pool.', 'football-pool' ) . ' ' . __( 'Are you sure? `OK` to continue, `Cancel` to stop.', 'football-pool' ) );
		
		$extra = false;
		if ( $pool->has_leagues ) {
			$leagues = $pool->get_leagues( true );
			$temp = [];
			foreach ( $leagues as $league ) {
				$temp[$league['league_id']] = Football_Pool_Utils::xssafe( $league['league_name'] );
			}
			$leagues = $temp;
			$extra = [
				'id' => 'new_league',
				'text' => __( 'Change league to…', 'football-pool' ),
				'options' => $leagues,
			];
			$temp = [];
			$temp[''] = '';
			foreach ( $leagues as $key => $val ) {
				$temp[$key] = $val;
			}
			$leagues = $temp;
			
			$search = [
				'text' => __( 'Search Users' ),
				'value' => $search,
				'extra_search' => Football_Pool_Utils::select( 'league_search', $leagues, $league_id ),
				'extra_search_text' => __( 'League', 'football-pool' ),
			];
		} else {
			$search = [
				'text' => __( 'Search Users' ),
				'value' => $search,
			];
		}
		
		// add extra search_by drop down if needed
		if ( is_array( $search_options ) && count( $search_options ) > 1 ) {
			$search['search_by'] = Football_Pool_Utils::select( 'search_by', $search_options, $search_by );
		}
		
		self::list_table( $cols, $rows, $bulkactions, $rowactions, $pagination, $search, $extra );
		
		self::hidden_input( 'user_list', implode( ',', $user_list ) );
		submit_button( __( 'Save Changes', 'football-pool' ) );
		
		// self::list_email_addresses( $users );
		self::secondary_button( __( 'List player email addresses', 'football-pool' ), 'list_email', true );
	}

	private static function list_email_addresses( $users = null ) {
		if ( $users === null ) {
			$search = Football_Pool_Utils::request_string( 's' );
			$league_id = Football_Pool_Utils::request_int( 'league_search' );
			$search_by = Football_Pool_Utils::request_string( 'search_by', 'name' );
			$users = self::get_users( null, null, $search, $search_by, $league_id );
		}
		
		$players = $not_players = [];
		
		foreach ( $users as $user ) {
			if ( $user['is_no_player'] == 1 ) {
				$not_players[] = $user['email_address'];
			} else {
				$players[] = $user['email_address'];
			}
		}
		
		printf( '<h3>%s</h3>', __( 'Email addresses', 'football-pool' ) );
		printf( '<div class="email-addresses players">
					<label for="player-addresses">%s</label>
					<textarea id="player-addresses" onfocus="this.select()">%s</textarea>
					</div>'
				, __( 'Player', 'football-pool' )
				, implode( '; ', $players ) 
		);
		printf( '<div class="email-addresses not-players">
					<label for="not-player-addresses">%s</label>
					<textarea id="not-player-addresses" onfocus="this.select()">%s</textarea>
					</div>'
				, __( 'Not a player', 'football-pool' )
				, implode( '; ', $not_players ) 
		);
		
		self::primary_button( __( 'Back', 'football-pool' ), '' );
		self::secondary_button( __( 'Ignore search and list all users', 'football-pool' ), 'list_email' );
	}
	
	private static function update() {
		global $pool;
		$has_leagues = $pool->has_leagues;
		$default_league = Football_Pool_Utils::get_fp_option( 'default_league_new_user', FOOTBALLPOOL_LEAGUE_DEFAULT, 'int' );
		
		$user_list = Football_Pool_Utils::post_string( 'user_list', 0 );
		$users = get_users( "include={$user_list}" );
		foreach ( $users as $user ) {
			$paid = Football_Pool_Utils::post_integer( 'paid_for_pool_' . $user->ID );
			update_user_meta( $user->ID, 'footballpool_payed', $paid );
			
			if ( $has_leagues ) {
				$plays_in_league = Football_Pool_Utils::post_integer( 'plays_in_league_' . $user->ID, $default_league );
				update_user_meta( $user->ID, 'footballpool_league', $plays_in_league );
				$pool->update_league_for_user( $user->ID, $plays_in_league );
			} else {
				$is_no_player = Football_Pool_Utils::post_integer( 'is_no_player_' . $user->ID );
				if ( $is_no_player == 1 ) 
					self::remove_user( $user->ID );
				else
					self::add_user( $user->ID );
			}
		}
	}

	private static function remove( $user_id ) {
		if ( is_array( $user_id ) ) {
			foreach ( $user_id as $id ) self::remove_user( $id );
		} else {
			self::remove_user( $user_id );
		}
	}

	private static function remove_user( $id ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $pool->has_leagues ) {
			update_user_meta( $id, 'footballpool_league', 0 );
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}league_users WHERE user_id = %d", $id );
			$wpdb->query( $sql );
		} else {
			$pool->update_league_for_user( $id, 0 );
		}
	}

	private static function add( $user_id ) {
		if ( is_array( $user_id ) ) {
			foreach ( $user_id as $id ) self::add_user( $id );
		} else {
			self::add_user( $user_id );
		}
	}

	private static function bulk_extra( $bulk_ids, $league_id ) {
		global $pool;
		foreach( $bulk_ids as $id ) $pool->update_league_for_user( $id, $league_id, 'update league');
	}
	
	private static function add_user( $id ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$default_league = Football_Pool_Utils::get_fp_option( 'default_league_new_user', FOOTBALLPOOL_LEAGUE_DEFAULT, 'ínt' );

		if ( $pool->has_leagues ) {
			update_user_meta( $id, 'footballpool_league', $default_league );
			
			$sql = $wpdb->prepare( "SELECT COUNT( * ) FROM {$prefix}league_users lu 
									LEFT OUTER JOIN {$prefix}leagues l ON ( lu.league_id = l.id )
									WHERE lu.user_id = %d AND l.id IS NULL"
									, $id
								);
			$non_existing_league = ( $wpdb->get_var( $sql ) == 1 );
			// if user is in a non-existing league, then force the update
			if ( $non_existing_league )
				$pool->update_league_for_user( $id, $default_league, 'update league' );
			else
				$pool->update_league_for_user( $id, $default_league, 'no update' );
		} else {
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}league_users WHERE user_id = %d AND league_id = 0", $id );
			$wpdb->query( $sql );
		}
		do_action( 'footballpool_admin_add_user', $id, $default_league );
	}
	
	protected static function list_table( $cols, $rows, $bulkactions = array(), $rowactions = array()
										, $pagination = false, $search = false, $extra = false ) {
		parent::bulk_actions( $bulkactions, 'action', $pagination, $search, $extra );
		echo "<table class='wp-list-table widefat fixed user-list'>";
		parent::list_table_def( $cols, 'head' );
		parent::list_table_def( $cols, 'foot' );
		self::list_table_body( $cols, $rows, $rowactions );
		echo '</table>';
		parent::bulk_actions( $bulkactions, 'action2' );
	}
	
	protected static function list_table_field( $type, $value, $name = '', $source = null ) {
		global $pool;
		switch ( $type ) {
			case 'checkbox':
			case 'boolean':
				$checked = $value == 1 ? 'checked="checked" ' : '';
				$output = '<input type="checkbox" value="1" name="' . $name . '" ' . $checked . '/>';
				break;
			case 'select':
				$output = $pool->league_select( (int)$value, (string)$name );
				// TODO: make a generic method that can be used with different data-sources for the select
				// if ( is_array( $source ) && count( $source ) > 0 ) {
					// $output = '<select></select>';
				// } else {
					// $output = $value;
				// }
				break;
			case 'text':
			default:
				$output = $value;
		}

		return $output;
	}
	
	protected static function list_table_body( $cols, $rows, $rowactions ) {
		echo "<tbody id='the-list'>";

		$r = count( $rows );
		$c = count( $cols );
		$page = Football_Pool_Utils::get_string( 'page' );

		if ( $r == 0 ) {
			echo "<tr><td colspan='", $c+1, "'>", __( 'no data', 'football-pool' ), "</td></tr>";
		} else {
			for ( $i = 0; $i < $r; $i++ ) {
				$row_class = ( $i % 2 == 0 ) ? 'alternate' : '';
				echo "
					<tr class='{$row_class}' id='row-{$i}'>
					<th class='check-column' scope='row'>
						<input type='checkbox' value='{$rows[$i][$c]}' name='itemcheck[]'>
					</th>";
				for ( $j = 0; $j < $c; $j++ ) {
					echo "<td class='column-{$cols[$j][2]}'>";
					if ( $j == 0 ) {
						echo '<strong><a title="Edit “', esc_attr( $rows[$i][$j] ), '”" href="user-edit.php?user_id=', esc_attr( $rows[$i][$c] ), '" class="row-title">';
					}
					$name = $cols[$j][2] . '_' . $rows[$i][$c];
					echo self::list_table_field( $cols[$j][0], $rows[$i][$j], $name, $cols[$j][3] );

					if ( $j == 0 ) {
						$row_action_url = sprintf( 'user-edit.php?user_id=%s'
													, esc_attr( $rows[$i][$c] )
											);
						$row_action_url = wp_nonce_url( $row_action_url, FOOTBALLPOOL_NONCE_ADMIN );
						echo '</a></strong><br>
								<div class="row-actions">
									<span class="item-id">', __( 'id', 'football-pool' ), ': '
										, $rows[$i][$c], '</span>&nbsp;|&nbsp;
									<span class="edit">
										<a href="', $row_action_url, '">', __( 'Edit' ), '</a>
									</span>';
						foreach ( $rowactions as $action ) {
							$span_class = ( $action[0] == 'remove' ) ? 'delete' : 'edit';
							$row_action_url = sprintf( '?page=%s&amp;action=%s&amp;item_id=%s'
														, esc_attr( $page )
														, esc_attr( $action[0] )
														, esc_attr( $rows[$i][$c] )
												);
							$row_action_url = wp_nonce_url( $row_action_url, FOOTBALLPOOL_NONCE_ADMIN );
							echo '<span class="', $span_class, '">
									| <a href="', $row_action_url, '">', $action[1], '</a>
								</span>';
						}
						echo "</div>";
					}

					echo "</td>";
				}
				echo "</tr>";
			}
		}
		echo '</tbody>';
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public static function update_user_options( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		$league = Football_Pool_Utils::post_int( 'footballpool_league', FOOTBALLPOOL_LEAGUE_DEFAULT );
		update_user_meta( $user_id, 'footballpool_registeredforleague', $league );
	}
	
	public static function add_extra_profile_fields( $user ) {
		// only for admins
		if ( ! current_user_can( 'edit_users' ) ) return;

		global $pool;

		// add extra profile fields to user edit page
		if ( $pool->has_leagues ) {
			echo '<h3>', FOOTBALLPOOL_PLUGIN_NAME, '</h3>';
			echo '<table class="form-table">';

			// option to echo some extra info at the beginning of the table
			do_action( 'footballpool_show_extra_profile_fields_pre', $user );

			$league = get_the_author_meta( 'footballpool_registeredforleague', $user->ID );
			if ( is_numeric( $league ) ) {
				$league = (int) $league;
			} else {
				$league = 0;
			}
			echo'<tr><th><label for="league">', __( 'Play in league', 'football-pool' ), '</label></th>';
			echo '<td>', $pool->league_select( $league, 'footballpool_league' );
			if ( current_user_can( 'edit_users' ) ) {
				echo '<br><span class="description">', __( "<strong>Important:</strong> An administrator can change users in the plugin's admin page for", 'football-pool' ), ' <a href="admin.php?page=footballpool-users">', __( 'Users', 'football-pool' ), '</a>.</span>';
			}
			echo '</td></tr>';

			$league = $pool->get_league_for_user( $user->ID );
			if ( $league > 1 && array_key_exists( $league, $pool->leagues ) ) {
				$league = $pool->league_name( $league );
			} else {
				$league = __( 'unknown', 'football-pool' );
			}

			echo '<tr><th><label>', __( 'The webmaster put you in this league', 'football-pool' ), '</label></th>';
			echo '<td><span style="text-decoration: underline">', $league, 
				'</span><br><span class="description">',
				__( 'if this value is different from the one you entered on registration, then the webmaster did not approve it yet.', 'football-pool' ), 
				'</span></td></tr>';

			// option to echo some extra info at the end of the table
			do_action( 'footballpool_show_extra_profile_fields_post', $user );

			echo '</table>';
		}
	}
	
	public static function delete_user_from_pool( $user_id ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$scorehistory = $pool->get_score_table();

		do_action( 'footballpool_delete_user_pre', $user_id );

		// delete all references in the pool tables
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}{$scorehistory} WHERE user_id = %d", $user_id );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}league_users WHERE user_id = %d", $user_id );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}predictions WHERE user_id = %d", $user_id );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}bonusquestions_useranswers WHERE user_id = %d", $user_id );
		$wpdb->query( $sql );

		do_action( 'footballpool_delete_user_post', $user_id );
	}

}
