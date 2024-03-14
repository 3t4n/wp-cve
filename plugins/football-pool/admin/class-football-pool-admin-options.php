<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Admin_Options extends Football_Pool_Admin {
	public function __construct() {}
	
	private static function merge_with_defaults( &$val, $key, $defaults ) {
		$val = array_merge(
			$defaults,
			array_intersect_key( $val, $defaults )
		);
	}
	
	public static function help() {
		$help_tabs = array(
			array(
				'id' => 'overview',
				'title' => __( 'Overview', 'football-pool' ),
				'content' => __( '<p>The fields on this page set different options for the plugin.</p><p>Some settings have effect on the ranking (e.g. points), when changing such a setting you can recalculate the ranking on this page with the <em>\'Recalculate scores\'</em> button.</p><p>You have to click <em>Save Changes</em> for the new settings to take effect.</p>', 'football-pool' )
			),
		);
		$help_sidebar = sprintf( '<a href="?page=footballpool-help#rankings">%s</a>', __( 'Help section about rankings', 'football-pool' ) );
		
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}
	
	public static function admin() {
		global $pool;

		$action = Football_Pool_Utils::post_string( 'action' );
		$date = date_i18n( 'Y-m-d H:i' );
		
		$match_time_offsets = array();
		// based on WordPress's functions.php
		$offset_range = array( 
							-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, 
							-6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, 
							-0.5, 0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 
							6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 
							12, 12.75, 13, 13.75, 14
						);
		foreach ( $offset_range as $offset ) {
			if ( 0 <= $offset ) {
				$offset_text = '+' . $offset;
			} else {
				$offset_text = (string)$offset;
			}

			$offset_text = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $offset_text );
			$offset_text = 'UTC' . $offset_text;

			$match_time_offsets[] = array( 'value' => $offset, 'text' => $offset_text );
		}
		
		$user_defined_rankings = array();
		$rankings = $pool->get_rankings( 'user defined' );
		foreach ( $rankings as $ranking ) {
			$user_defined_rankings[] = array( 
											'value' => $ranking['id'], 
											'text' => Football_Pool_Utils::xssafe( $ranking['name'] )
											);
		}
		
		if ( $action === 'update' ) {
			// in case of a save action
			check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
			// show extra warning when calculation method is changed
			$changed_calculation_method = ( Football_Pool_Utils::post_int( 'simple_calculation_method' ) 
												!== Football_Pool_Utils::get_fp_option( 'simple_calculation_method' ) );
			if ( $changed_calculation_method ) {
				self::notice( __( 'Recalculation needed!' ), 'warning' );
			}
			
			$offset_switch = ( Football_Pool_Utils::post_int( 'match_time_display' ) !== 2 );
			$ranking_switch = ( Football_Pool_Utils::post_int( 'ranking_display' ) !== 2 );
			$league_switch = ( Football_Pool_Utils::post_int( 'use_leagues' ) !== 1 );
			$joker_switch = ( Football_Pool_Utils::post_int( 'jokers_enabled' ) !== 1 );
			$ajax_saves = ( Football_Pool_Utils::post_int( 'frontend_ajax_saves' ) !== 1 );
		} else {
			// normal situation
			$offset_switch = ( Football_Pool_Utils::get_fp_option( 'match_time_display', 0, 'int' ) !== 2 );
			$ranking_switch = ( Football_Pool_Utils::get_fp_option( 'ranking_display', 0, 'int' ) !== 2 );
			$league_switch = ( Football_Pool_Utils::get_fp_option( 'use_leagues', 0, 'int' ) !== 1 );
			$joker_switch = ( Football_Pool_Utils::get_fp_option( 'jokers_enabled', 0, 'int' ) !== 1 );
			$ajax_saves = ( Football_Pool_Utils::get_fp_option( 'frontend_ajax_saves', 0, 'int' ) !== 1 );
		}
		
		// get the match types for the groups page
		$match_types = Football_Pool_Matches::get_match_types();
		$options = array();
		foreach ( $match_types as $type ) {
			$options[] = array(
								'value' => $type->id,
								'text' => Football_Pool_Utils::xssafe( $type->name )
								);
		}
		$match_types = $options;
		
		// get the leagues
		$user_defined_leagues = $pool->get_leagues( true );
		$options = array();
		$options[] = array( 'value' => 0, 'text' => '' );
		foreach ( $user_defined_leagues as $league ) {
			$options[] = array(
							'value' => $league['league_id'],
							'text' => Football_Pool_Utils::xssafe( $league['league_name'] )
						);
		}
		$user_defined_leagues = $options;
		
		// get the pages for the redirect option & the plugin pages
		$redirect_pages = $plugin_pages = array();
		$plugin_pages[] = array(
								'value' => '',
								'text' => ''
							);
		$redirect_pages[] = array(
								'value' => '',
								'text' => ''
							);
		$redirect_pages[] = array(
								'value' => home_url(),
								'text' => __( 'homepage', 'football-pool' )
							);
		$redirect_pages[] = array(
								'value' => admin_url( 'profile.php' ),
								'text' => __( 'edit profile', 'football-pool' )
							);
		
		$args = array(
					'sort_order' => 'ASC',
					'sort_column' => 'post_title',
					'hierarchical' => 0,
					'exclude' => '',
					'include' => '',
					'meta_key' => '',
					'meta_value' => '',
					'authors' => '',
					'child_of' => 0,
					'parent' => -1,
					'exclude_tree' => '',
					'number' => '',
					'offset' => 0,
					'post_type' => 'page',
					'post_status' => 'publish'
				);
		$pages = get_pages( $args );
		foreach( $pages as $page ) {
			$redirect_pages[] = array( 'value' => $page->guid, 'text' => $page->post_title ); // uses the URL
			$plugin_pages[] = array( 'value' => $page->ID, 'text' => $page->post_title ); // uses the post ID
		}
		
		/* Definition of all configurable options
		 *
		 * array(
		 *   'option_name' =>
		 *       array(
		 *           'id'         => ID and name of the input and the option key.
		 *           'label'      => The text that is displayed before the input.
		 *           'type'       => The form input type. Defaults to text.
		 *           'value_type' => The value type to use. Defaults to string.
		 *           'desc'       => The explanation that is shown next to the input.
		 *           'options'    => In case of a radiolist or dropdown the options are supplied as an array.
		 *           'depends_on' => ID (and value) of the option if there is a dependency. Option will only 
		 *                           be shown when other option is selected or has the given value.
		 *           'extra_attr' => Extra parameters to be added in the tag. Can be an array (one attribute
		 *                           for every option) or single value.
		 *           'class'      => Extra CSS class that should be passed to the input.
		 *       ),
		 *   etc...
		 * )
		 */
		$options_defaults =
			array(
				'id'         => '',
				'label'      => '',
				'type'       => 'text',
				'value_type' => 'string',
				'desc'       => '',
				'options'    => null,
				'depends_on' => '',
				'extra_attr' => '',
				'class'      => '',
			);

		$options =
			array(
				'page_id_tournament' =>
					array(
						'id'         => 'page_id_tournament',
						'label'      => __( 'Matches page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_teams' =>
					array(
						'id'         => 'page_id_teams',
						'label'      => __( 'Team(s) page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_groups' =>
					array(
						'id'         => 'page_id_groups',
						'label'      => __( 'Group(s) page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_stadiums' =>
					array(
						'id'         => 'page_id_stadiums',
						'label'      => __( 'Venue(s) page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_rules' =>
					array(
						'id'         => 'page_id_rules',
						'label'      => __( 'Rules page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_pool' =>
					array(
						'id'         => 'page_id_pool',
						'label'      => __( 'Submit predictions page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_ranking' =>
					array(
						'id'         => 'page_id_ranking',
						'label'      => __( 'Ranking page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_statistics' =>
					array(
						'id'         => 'page_id_statistics',
						'label'      => __( 'Statistics page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'page_id_user' =>
					array(
						'id'         => 'page_id_user',
						'label'      => __( 'See a user\'s predictions page', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $plugin_pages,
					),
				'redirect_url_after_login' =>
					array(
						'id'         => 'redirect_url_after_login',
						'label'      => __( 'Page after login', 'football-pool' ),
						'type'       => 'select',
						'options'    => $redirect_pages,
						'desc'       => sprintf( '%s %s %s'
											, __( 'You can set the page where users must be redirected to after login.', 'football-pool' )
											, __( 'Leave empty to use default behavior of WordPress.', 'football-pool' )
											, __( 'This setting only applies to non-admins.', 'football-pool' )
										),
						'class'      => 'allow-single-deselect'
					),
				'redirect_url_after_registration' =>
					array(
						'id'         => 'redirect_url_after_registration',
						'label'      => __( 'Page after registration', 'football-pool' ),
						'type'       => 'select',
						'options'    => $redirect_pages,
						'desc'       => sprintf( '%s %s %s'
											, __( 'You can set the page where users must be redirected to after registration.', 'football-pool' )
											, __( 'Leave empty to use default behavior of WordPress.', 'football-pool' )
											, __( 'This setting only applies to non-admins.', 'football-pool' )
										),
						'class'      => 'allow-single-deselect'
					),
				'keep_data_on_uninstall' =>
					array(
						'id'         => 'keep_data_on_uninstall',
						'label'      => __( 'Keep data on uninstall', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'If checked the options and pool data (teams, matches, predictions, etc.) are not removed when deactivating the plugin.', 'football-pool' )
					),
				'fullpoints' =>
					array(
						'id'         => 'fullpoints',
						'label'      => __( 'Full score', 'football-pool' ) . ' *',
						'desc'       => __( 'The points a user gets for getting the exact outcome of a match. The shortcode [fp-fullpoints] adds this value in the content. This value is also used for the calculations in the pool.', 'football-pool' )
					),
				'totopoints' =>
					array(
						'id'         => 'totopoints',
						'label'      => __( 'Toto score', 'football-pool' ) . ' *',
						'desc'       => __( 'The points a user gets for guessing the outcome of a match (win, loss or draw) without also getting the exact amount of goals. The shortcode [fp-totopoints] adds this value in the content. This value is also used in the calculations in the pool.', 'football-pool' )
					),
				'goalpoints' =>
					array(
						'id'         => 'goalpoints',
						'label'      => __( 'Goal bonus', 'football-pool' ) . ' *',
						'desc'       => __( 'Extra points a user gets for guessing the goals correct for one of the teams. These points are added to the toto points or full points. The shortcode [fp-goalpoints] adds this value in the content. This value is also used in the calculations in the pool.', 'football-pool' )
					),
				'diffpoints' =>
					array(
						'id'         => 'diffpoints',
						'label'      => __( 'Goal difference bonus', 'football-pool' ) . ' *',
						'desc'       => __( 'Extra points a user gets for guessing the goal difference correct for a match. Only awarded in matches with a winning team and only on top of toto points. See the help page for more information. The shortcode [fp-diffpoints] adds this value in the content. This value is also used in the calculations in the pool.', 'football-pool' )
					),
				'stop_time_method_matches' =>
					array(
						'id'         => 'stop_time_method_matches',
						'label'      => __( 'Prediction stop method for matches', 'football-pool' ),
						'type'       => 'radiolist',
						'value_type' => 'integer',
						'options'    => array(
											array( 'value' => 0, 'text' => __( 'Dynamic time', 'football-pool' ) ),
											array( 'value' => 1, 'text' => __( 'One stop date', 'football-pool' ) ),
										),
						'desc'       => __( 'Select which method to use for the prediction stop.', 'football-pool' ),
						'extra_attr' => array(
											'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-maxperiod\', [ \'#r-matches_locktime\' ] )"',
											'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-matches_locktime\', [ \'#r-maxperiod\' ] )"',
										),
					),
				'maxperiod' =>
					array(
						'id'         => 'maxperiod',
						'label'      => __( 'Dynamic stop threshold (in seconds) for matches', 'football-pool' ) . ' *',
						'type'       => 'text',
						'desc'       => __( 'A user may change his/her predictions untill this amount of time before game kickoff. The time is in seconds, e.g. 15 minutes is 900 seconds.', 'football-pool' ),
						'depends_on' => array( 'stop_time_method_matches' => 1 )
					),
				'matches_locktime' =>
					array(
						'id'         => 'matches_locktime',
						'label'      => __( 'Prediction stop date for matches', 'football-pool' ) . ' *',
						'type'       => 'datetime',
						'value_type' => 'datetime',
						'desc'       => __( 'If a valid date and time [Y-m-d H:i] is given here, then this date/time will be used as a single value before all predictions for the matches have to be entered by users. (your local time is:', 'football-pool' ) . ' <a href="options-general.php">' . $date . '</a>)',
						'depends_on' => array( 'stop_time_method_matches' => 0 )
					),
				'stop_time_method_questions' =>
					array(
						'type'       => 'radiolist',
						'value_type' => 'integer',
						'label'      => __( 'Use one prediction stop date for questions?', 'football-pool' ),
						'id'         => 'stop_time_method_questions',
						'options'    => array(
											array( 'value' => 0, 'text' => __( 'No', 'football-pool' ) ),
											array( 'value' => 1, 'text' => __( 'Yes', 'football-pool' ) ),
										),
						'desc'       => __( 'Select which method to use for the prediction stop.', 'football-pool' ),
						'extra_attr' => array(
											'onclick="FootballPoolAdmin.toggle_linked_options( \'\', [ \'#r-bonus_question_locktime\' ] )"',
											'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-bonus_question_locktime\', null )"',
										),
					),
				'bonus_question_locktime' =>
					array(
						'id'         => 'bonus_question_locktime',
						'label'      => __( 'Prediction stop date for questions', 'football-pool' ) . ' *',
						'type'       => 'datetime',
						'value_type' => 'datetime',
						'desc'       => __( 'If a valid date and time [Y-m-d H:i] is given here, then this date/time will be used as a single value before all predictions for the bonus questions have to be entered by users. (your local time is:', 'football-pool' ) . ' <a href="options-general.php">' . $date . '</a>)',
						'depends_on' => array( 'stop_time_method_questions' => 0 )
					),
				'shoutbox_max_chars' =>
					array(
						'id'         => 'shoutbox_max_chars',
						'label'      => __( 'Maximum length for a shoutbox message', 'football-pool' ) . ' *',
						'desc'       => __( 'Maximum length (number of characters) a message in the shoutbox may have.', 'football-pool' )
					),
				'use_leagues' =>
					array(
						'id'         => 'use_leagues',
						'label'      => __( 'Use leagues', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Set this if you want to use leagues in your pool. You can use this (e.g.) for paying and non-paying users, or different departments. Important: if you change this value when there are already points given, then the scoretable will not be automatically recalculated. Use the recalculate button on this page for that.', 'football-pool' ),
						'extra_attr' => 'onclick="jQuery(\'#r-default_league_new_user\').toggle()"'
					),
				'default_league_new_user' =>
					array(
						'id'         => 'default_league_new_user',
						'label'      => __( 'Standard league for new users', 'football-pool' ),
						'type'       => 'select',
						'value_type' => 'integer',
						'options'    => $user_defined_leagues,
						'desc'       => __( 'The standard league a new user will be placed after registration.', 'football-pool' ),
						'depends_on' => $league_switch,
						'class'      => 'league-select allow-single-deselect',
					),
				'dashboard_image' =>
					array(
						'id'         => 'dashboard_image',
						'label'      => __( 'Image for Dashboard Widget', 'football-pool' ),
						'desc'       => '<a href="' . get_admin_url() . '">' . __( 'Dashboard' ) . '</a>'
					),
				'hide_admin_bar' =>
					array(
						'id'         => 'hide_admin_bar',
						'label'      => __( 'Hide Admin Bar for subscribers', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'After logging in a subscriber may see an Admin Bar on top of your blog (a user option). With this plugin option you can ignore the user configuration and never show the Admin Bar.', 'football-pool' )
					),
				'use_favicon' =>
					array(
						'id'         => 'use_favicon',
						'label'      => __( 'Use favicon', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( "Switch off if you don't want to use the icons in the plugin.", 'football-pool' )
					),
				'show_team_link' =>
					array(
						'id'         => 'show_team_link',
						'label'      => __( 'Show team names as links', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( "Switch off if you don't want to link the team names to a team info page.", 'football-pool' )
					),
				// 'show_team_link' =>
					// array( 'checkbox', __( 'Show team names as links', 'football-pool' ), 'show_team_link', __( "Switch off if you don't want to link the team names to a team info page.", 'football-pool' ), 'onclick="jQuery(\'#r-show_team_link_use_external\').toggle()"' ),
				// 'show_team_link_use_external' =>
					// array( 'checkbox', __( 'Use the external link for the link team names', 'football-pool' ), 'show_team_link_use_external', __( ".", 'football-pool' ) ),
				'show_venues_on_team_page' =>
					array(
						'id'         => 'show_venues_on_team_page',
						'label'      => __( 'Show venues on team page', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( "Switch off if you don't want to show all venues a team plays in during a season or tournament (in national competitions the venue list is a bit useless).", 'football-pool' )
					),
				'use_charts' =>
					array(
						'id' => 'use_charts',
						'label' => __( 'Enable charts', 'football-pool' ),
						'type' => 'checkbox',
						'value_type' => 'integer',
						'desc' => sprintf(
							__( 'The Highcharts API is needed for this feature. See the <%s>Help page<%s> for information on installing this library.', 'football-pool' ),
							'a href="?page=footballpool-help#charts"', '/a'
						)
					),
				'export_format' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Format for the csv export (match schedule)', 'football-pool' ),
						'id' => 'export_format',
						'options' => array(
										array( 'value' => 0, 'text' => __( 'Full data', 'football-pool' ) ),
										array( 'value' => 1, 'text' => __( 'Minimal data', 'football-pool' ) ),
									),
						'desc' => sprintf(
							__( 'Select the format of the csv export. See the <%s>Help page<%s> for more information.', 'football-pool' ),
							'a href="?page=footballpool-help#teams-groups-and-matches"', '/a'
						),
					),
				'match_time_display' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Match time setting', 'football-pool' ),
						'id' => 'match_time_display',
						'options' => array(
										array(
											'value' => 0,
											'text' => __( 'Use WordPress Timezone setting', 'football-pool' )
										),
										array( 'value' => 1, 'text' => __( 'Use UTC time', 'football-pool' ) ),
										array( 'value' => 2, 'text' => __( 'Custom setting', 'football-pool' ) ),
									),
						'desc' => __( 'Select which method to use for the display of match times.', 'football-pool' ),
						'extra_attr' =>
							array(
								'onclick="FootballPoolAdmin.toggle_linked_options( null, \'#r-match_time_offset\' )"',
								'onclick="FootballPoolAdmin.toggle_linked_options( null, \'#r-match_time_offset\' )"',
								'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-match_time_offset\', null )"',
							),
					),
				'match_time_offset' =>
					array(
						'type' => 'dropdown',
						'value_type' => 'string',
						'label' => __( 'Match time offset', 'football-pool' ),
						'id' => 'match_time_offset',
						'options' => $match_time_offsets,
						'desc' => __( 'The offset in hours to add to (or extract from) the UTC start time of a match. Only used for display of the time.', 'football-pool' ),
						'depends_on' => $offset_switch,
						'class' => 'match-time-offset'
					),
				'add_tinymce_button' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Use shortcode button in visual editor', 'football-pool' ),
						'id' => 'add_tinymce_button',
						'desc' => __( 'The plugin can add a button to the visual editor of WordPress. With this option disabled this button will not be included (uncheck if the button is causing problems).', 'football-pool' )
					),
				'always_show_predictions' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Always show predictions', 'football-pool' ),
						'id' => 'always_show_predictions',
						'desc' => __( 'Normally match predictions are only shown to other players after a prediction can\'t be changed anymore. With this option enabled the predictions are visible to anyone, anytime. Works only for matches, not bonus questions.', 'football-pool' )
					),
				'use_spin_controls' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Use HTML5 number inputs', 'football-pool' ),
						'id' => 'use_spin_controls',
						'desc' => __( 'Make use of HTML5 number inputs for the prediction form. Some browsers display these as spin controls.', 'football-pool' )
					),
				'groups_page_match_types' =>
					array(
						'type' => 'multi-select',
						'value_type' => 'integer array',
						'label' => __( 'Groups page matches', 'football-pool' ),
						'id' => 'groups_page_match_types',
						'options' => $match_types,
						'desc' => sprintf( __( 'The Groups page shows standings for the matches in these match types. Defaults to match type id: %d.', 'football-pool' ), FOOTBALLPOOL_GROUPS_PAGE_DEFAULT_MATCHTYPE ) . ' ' . __( 'Use CTRL+click to select multiple values.', 'football-pool' ),
					),
				'match_sort_method' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Match sorting', 'football-pool' ),
						'id' => 'match_sort_method',
						'options' =>
							array(
								array( 'value' => 0, 'text' => __( 'Date ascending', 'football-pool' ) ),
								array( 'value' => 1, 'text' => __( 'Date descending', 'football-pool' ) ),
								array( 'value' => 2, 'text' => __( 'Match types descending, matches date ascending', 'football-pool' ) ),
								array( 'value' => 3, 'text' => __( 'Match types ascending, matches date descending', 'football-pool' ) ),
							),
						'desc' => __( 'Select the order in which matches must be displayed on the matches page and the prediction page.', 'football-pool' ),
					),
				'question_sort_method' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Question sorting', 'football-pool' ),
						'id' => 'question_sort_method',
						'options' =>
							array(
								array( 'value' => 0, 'text' => __( 'Question order value ascending, answer before date ascending', 'football-pool' ) ),
								array( 'value' => 1, 'text' => __( 'Question order value descending, answer before date ascending', 'football-pool' ) ),
								array( 'value' => 2, 'text' => __( 'Answer before date ascending, question order value ascending', 'football-pool' ) ),
								array( 'value' => 3, 'text' => __( 'Answer before date descending, question order value descending', 'football-pool' ) ),
							),
						'desc' => __( 'Select the order in which bonus questions must be displayed on the prediction page.', 'football-pool' ),
					),
				'auto_calculation' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Automatic calculation', 'football-pool' ),
						'id' => 'auto_calculation',
						'desc' => __( 'By default the rankings are automatically (re)calculated in the admin. Change this setting if you want to (temporarily) disable this behaviour.', 'football-pool' )
					),
				'simple_calculation_method' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Simple calculation method', 'football-pool' ),
						'id' => 'simple_calculation_method',
						'desc' => __( 'The plugin calculates the scores and ranking for every point in time. This may take a long time to complete, especially in installs with a large user base. With this setting you can switch to a much quicker calculation, but without the historic data. With this setting enabled the use of charts is not possible and supplying a date to the ranking or score shortcodes will have no effect, these will then always return the latest ranking or score.', 'football-pool' )
					),
				'ranking_display' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Ranking to show', 'football-pool' ),
						'id' => 'ranking_display',
						'options' => array(
										array( 'value' => 0, 'text' => __( 'Default ranking', 'football-pool' ) ),
										array( 'value' => 1, 'text' => __( 'Let the user decide', 'football-pool' ) ),
										array( 'value' => 2, 'text' => __( 'Custom setting', 'football-pool' ) ),
									),
						'desc' => __( 'The ranking page and charts page can show different rankings. Use this setting to decide which ranking to show.', 'football-pool' ),
						'extra_attr' =>
							array(
								'onclick="FootballPoolAdmin.toggle_linked_options( null, \'#r-show_ranking\' )"',
								'onclick="FootballPoolAdmin.toggle_linked_options( null, \'#r-show_ranking\' )"',
								'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-show_ranking\', null )"',
							),
					),
				'show_ranking' =>
					array(
						'type' => 'dropdown',
						'label' => __( 'Choose ranking', 'football-pool' ),
						'id' => 'show_ranking',
						'options' => $user_defined_rankings,
						'desc' => __( 'Choose the ranking you want to use on the ranking page and the charts page.', 'football-pool' ),
						'depends_on' => $ranking_switch,
						'class' => 'ranking-select'
					),
				'team_points_win' =>
					array(
						'value_type' => 'integer',
						'label' => __( 'Points for win', 'football-pool' ) . ' *',
						'id' => 'team_points_win',
						'desc' => __( 'The points a team gets for a win.', 'football-pool' )
					),
				'team_points_draw' =>
					array(
						'value_type' => 'integer',
						'label' => __( 'Points for draw', 'football-pool' ) . ' *',
						'id' => 'team_points_draw',
						'desc' => __( 'The points a team gets for a draw.', 'football-pool' )
					),
				'listing_show_team_thumb' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show photo in team listing', 'football-pool' ),
						'id' => 'listing_show_team_thumb',
						'desc' => __( 'Show the team\'s photo on the team listing page (if available).', 'football-pool' )
					),
				'listing_show_venue_thumb' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show photo in venue listing', 'football-pool' ),
						'id' => 'listing_show_venue_thumb',
						'desc' => __( 'Show the venue\'s photo on the team listing page (if available).', 'football-pool' )
					),
				'listing_show_team_comments' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show comments in team listing', 'football-pool' ),
						'id' => 'listing_show_team_comments',
						'desc' => __( 'Show the team\'s comments on the team listing page (if available).', 'football-pool' )
					),
				'listing_show_venue_comments' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show comments in venue listing', 'football-pool' ),
						'id' => 'listing_show_venue_comments',
						'desc' => __( 'Show the venue\'s comments on the team listing page (if available).', 'football-pool' )
					),
				'user_page_show_correct_question_answer' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show question answer', 'football-pool' ),
						'id' => 'user_page_show_correct_question_answer',
						'desc' => __( 'When checked the page will also show the answer for the question that was entered by the admin.', 'football-pool' )
					),
				'user_page_show_actual_result' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show actual result', 'football-pool' ),
						'id' => 'user_page_show_actual_result',
						'desc' => __( 'When checked the page will also show the actual result of the match.', 'football-pool' )
					),
				'user_page_show_predictions_only' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Only show matches/questions with predictions?', 'football-pool' ),
						'id' => 'user_page_show_predictions_only',
						'desc' => __( 'When checked the page will only show matches and questions for which a user entered a prediction or answer.', 'football-pool' )
					),
				'user_page_show_finished_matches_only' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Only show finished matches/questions?', 'football-pool' ),
						'id' => 'user_page_show_finished_matches_only',
						'desc' => __( 'When checked the page will only show matches where the admin entered an end result and questions where the answer date is set.', 'football-pool' )
					),
				'match_table_layout' =>
					array(
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => __( 'Match list layout', 'football-pool' ),
						'id' => 'match_table_layout',
						'options' => array(
							array( 'value' => 0, 'text' => __( 'Classic layout', 'football-pool' ) ),
							array( 'value' => 1, 'text' => __( 'Mobile-friendly layout', 'football-pool' ) ),
						),
						'desc' => __( 'Choose the layout for the matches list and prediction form.', 'football-pool' ),
					),
				'jokers_enabled' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Enable score multipliers?', 'football-pool' ),
						'id' => 'jokers_enabled',
						'desc' => __( 'When checked users can select one or more predictions to multiply their score for that match.', 'football-pool' ),
						'extra_attr' => 'onclick="FootballPoolAdmin.toggle_linked_options( [ \'#r-joker_multiplier\', \'#r-number_of_jokers\', \'#r-jokers_per\' ], null, true )"',
					),
				'joker_multiplier' =>
					array(
						'id'         => 'joker_multiplier',
						'value_type' => 'integer',
						'label'      => __( 'Multiplier', 'football-pool' ),
						'desc'       => __( 'Scores are multiplied with this number when the multiplier is set. The shortcode [fp-jokermultiplier] adds this value in the content. This value is also used for the calculations in the pool.', 'football-pool' ),
						'depends_on' => $joker_switch,
					),
				'number_of_jokers' =>
					array(
						'id' => 'number_of_jokers',
						'value_type' => 'integer',
						'label' => __( 'Amount', 'football-pool' ),
						'desc' => __( 'The number of multipliers a user can use.', 'football-pool' ),
						'depends_on' => $joker_switch,
					),
				'jokers_per' =>
					array(
						'id' => 'jokers_per',
						'type' => 'radiolist',
						'value_type' => 'integer',
						'label' => _x( 'Scope', 'Used in the context "Amount of score multipliers is per pool or match type"', 'football-pool' ),
						'options' => array(
							array( 'value' => 1, 'text' => __( 'For the entire pool', 'football-pool' ) ),
							array( 'value' => 2, 'text' => __( 'Per match type', 'football-pool' ) ),
						),
						'depends_on' => $joker_switch,
					),
				'hide_save_button' =>
					array(
						'id'         => 'hide_save_button',
						'label'      => __( 'Hide save button', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'You can choose to hide the save button on the prediction form and completely rely on the asynchronous saves.', 'football-pool' )
					),
				'frontend_ajax_saves' =>
					array(
						'id'         => 'frontend_ajax_saves',
						'label'      => __( 'Asynchronous saves' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Use asynchronous (AJAX) saves for the predictions and bonus questions.', 'football-pool' ),
						'extra_attr' => 'onclick="FootballPoolAdmin.toggle_linked_options( [ \'#r-enable_onunload_check\', \'#r-show_save_messages\' ], null, true )"',
					),
				'enable_onunload_check' =>
					array(
						'id'         => 'enable_onunload_check',
						'label'      => __( 'Check unsaved changes' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Checks if there are any unsaved changes on the prediction form when leaving the page.', 'football-pool' ),
						'depends_on' => $ajax_saves,
					),
				'show_save_messages' =>
					array(
						'id'         => 'show_save_messages',
						'label'      => __( 'Show save messages', 'football-pool' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Show a messsage on success on the prediction form when using asynchronous saves.', 'football-pool' ),
						'depends_on' => $ajax_saves,
					),
				'erase_personal_data' =>
					array(
						'id'         => 'erase_personal_data',
						'label'      => __( 'Erase Personal Data' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Also erase predictions and question answers for a user when using the Erase Personal Data tool.', 'football-pool' )
					),
				'prediction_page_show_actual_result' =>
					array(
						'type' => 'checkbox',
						'value_type' => 'integer',
						'label' => __( 'Show actual result', 'football-pool' ),
						'id' => 'prediction_page_show_actual_result',
						'desc' => __( 'When checked the page will also show the actual result of the match.', 'football-pool' )
					),
				'auto_select_current_user_for_stats' =>
					array(
						'id'         => 'auto_select_current_user_for_stats',
						'label'      => __( 'Charts include current user' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'Always include the currently logged on user in the charts comparison (if applicable).', 'football-pool' )
					),
				'default_prediction_0' =>
					array(
						'id'         => 'default_prediction_0',
						'label'      => __( 'Fix incomplete predictions' ),
						'type'       => 'checkbox',
						'value_type' => 'integer',
						'desc'       => __( 'When a player has an incomplete prediction (e.g. only the home score was entered), set missing score to 0 to make sure the prediction is included in the ranking.', 'football-pool' )
					),
// todo: add option for frontend ajax saves (as a replacement for the wp-config value)
		);
		
		// merge $options with default values to fill the empty spots
		array_walk( $options, [__CLASS__, 'merge_with_defaults'], $options_defaults );

		$donate = sprintf( '<div class="donate">%s%s</div>'
			, __( 'If you want to support this plugin, you can buy me an espresso (doppio please ;))', 'football-pool' )
			, self::donate_button( 'return' )
		);
		$option_category_placeholder = '<p id="fp-options-menu"></p>';
		$menu = sprintf(
			'<span title="%s" class="fp-icon-navicon" onclick="jQuery( \'#fp-options-menu\' ).slideToggle( \'slow\' )"></span>',
			__( 'Option categories menu', 'football-pool' )
		);
		self::admin_header( __( 'Plugin Options', 'football-pool' ) . $menu
							, null, null, $option_category_placeholder . $donate );
		
		echo '<script type="text/javascript">
				jQuery( document ).ready( function() {
					let i = 0;
					const menu = jQuery( "#fp-options-menu" );
					jQuery( "h3", ".fp-admin" ).each( function() {
						$this = jQuery( this );
						$this.attr( "id", "option-section-" + i );
						menu.append( 
							"<span class=\'fp-icon-arrow-right\'></span> <a href=\'#option-section-" + i + "\'>" + 
							$this.text() + "</a><br>" 
						);
						i++;
					} );
				} );
			</script>';
		
		$recalculate =
			( Football_Pool_Utils::post_string( 'recalculate' ) === __( 'Recalculate Scores', 'football-pool' ) )
			|| ( Football_Pool_Utils::get_string( 'recalculate' ) === 'yes' );
		if ( $recalculate === true ) {
			check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
			self::update_score_history( 'force' );
		} elseif ( $action === 'update' ) {
			check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
			
			foreach ( $options as $option ) {
				if ( $option['value_type'] === 'text' || $option['value_type'] === 'string' ) {
					$value = Football_Pool_Utils::post_string( $option['id'] );
				} elseif ( $option['value_type'] === 'date' || $option['value_type'] === 'datetime' ) {
					$value = Football_Pool_Utils::gmt_from_date( self::make_date_from_input( $option['id'], $option['value_type'] ) );
				} elseif ( $option['value_type'] === 'integer array' ) {
					$value = Football_Pool_Utils::post_integer_array( $option['id'] );
				} elseif ( $option['value_type'] === 'string array' ) {
					$value = Football_Pool_Utils::post_string_array( $option['id'] );
				} else {
					$value = Football_Pool_Utils::post_integer( $option['id'] );
				}
				
				self::set_value( $option['id'], $value );
			}
			
			self::notice( __( 'Changes saved.', 'football-pool' ) );
		}
		
		self::intro( __( 'If values in the fields marked with an asterisk are left empty, then the plugin will default to the initial values.', 'football-pool' ) );
		
		do_action( 'footballpool_admin_options_screen_pre', $action );
		
		self::admin_sectiontitle( __( 'Scoring Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['fullpoints'],
				$options['totopoints'],
				$options['goalpoints'],
				$options['diffpoints'],
			)
		);
		echo '<p class="submit">';
		submit_button( null, 'primary', null, false );
		self::recalculate_button();
		echo '</p>';

		self::admin_sectiontitle( __( 'Ranking Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['auto_calculation'],
				$options['simple_calculation_method'],
				$options['ranking_display'],
				$options['show_ranking'],
			)
		);
		echo '<p class="submit">';
		submit_button( null, 'primary', null, false );
		self::recalculate_button();
		echo '</p>';

		self::admin_sectiontitle( __( 'Prediction Options', 'football-pool' ) );
		self::options_form( array(
				// $options['prediction_type'],
				// $options['prediction_type_draw'],
				$options['stop_time_method_matches'],
				$options['maxperiod'],
				$options['matches_locktime'],
				$options['stop_time_method_questions'],
				$options['bonus_question_locktime'],
				$options['always_show_predictions'],
				$options['default_prediction_0'],
			)
		);
		echo '<p class="submit">';
		submit_button( null, 'primary', null, false );
		self::recalculate_button();
		echo '</p>';

		self::admin_sectiontitle( __( 'Multiplier Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['jokers_enabled'],
				$options['number_of_jokers'],
				$options['jokers_per'],
				$options['joker_multiplier'],
			)
		);
		echo '<p class="submit">';
		submit_button( null, 'primary', null, false );
		self::recalculate_button();
		echo '</p>';

		self::admin_sectiontitle( __( 'League Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['use_leagues'],
				$options['default_league_new_user'],
			)
		);
		echo '<p class="submit">';
		submit_button( null, 'primary', null, false );
		self::recalculate_button();
		echo '</p>';

		self::admin_sectiontitle( __( 'Pool Layout Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['match_table_layout'],
				$options['match_sort_method'],
				$options['question_sort_method'],
				$options['match_time_display'],
				$options['match_time_offset'],
				$options['show_team_link'],
				// $options['show_team_link_use_external'],
				$options['show_venues_on_team_page'],
				$options['listing_show_team_thumb'],
				$options['listing_show_team_comments'],
				$options['listing_show_venue_thumb'],
				$options['listing_show_venue_comments'],
			)
		);
		submit_button( null, 'primary', null, true );

		self::admin_sectiontitle( __( 'Prediction Page Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['prediction_page_show_actual_result'],
				$options['use_spin_controls'],
				$options['hide_save_button'],
				$options['frontend_ajax_saves'],
				$options['show_save_messages'],
				$options['enable_onunload_check'],
			)
		);
		submit_button( null, 'primary', null, true );

		self::admin_sectiontitle( __( 'User Page Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['user_page_show_actual_result'],
				$options['user_page_show_correct_question_answer'],
				$options['user_page_show_predictions_only'],
				$options['user_page_show_finished_matches_only'],
			)
		);
		submit_button( null, 'primary', null, true );

		self::admin_sectiontitle( __( 'Groups Page Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['team_points_win'],
				$options['team_points_draw'],
				$options['groups_page_match_types'],
			)
		);
		submit_button( null, 'primary', null, true );

		self::admin_sectiontitle( __( 'Other Options', 'football-pool' ) );
		self::options_form(
			array(
				$options['keep_data_on_uninstall'],
				$options['erase_personal_data'],
				$options['use_charts'],
				$options['auto_select_current_user_for_stats'],
				$options['redirect_url_after_login'],
				$options['redirect_url_after_registration'],
				$options['export_format'],
				$options['shoutbox_max_chars'],
				$options['dashboard_image'],
				$options['use_favicon'],
				$options['hide_admin_bar'],
				$options['add_tinymce_button'],
			)
		);
		submit_button( null, 'primary', null, true );

		self::admin_sectiontitle( __( 'Plugin pages', 'football-pool' ) );
		echo '<p>',
			__( 'The plugin uses normal WordPress pages to display the content in the plugin (e.g. the ranking). In this section you can define which pages the plugin should append the content to. The plugin creates pages on first install for every page needed in the plugin.', 'football-pool' ),
			'</p>';
		self::options_form(
			array(
				$options['page_id_tournament'],
				$options['page_id_teams'],
				$options['page_id_groups'],
				$options['page_id_stadiums'],
				$options['page_id_rules'],
				$options['page_id_pool'],
				$options['page_id_ranking'],
				$options['page_id_statistics'],
				$options['page_id_user'],
				// $options['redirect_url_after_login'],
			)
		);
		submit_button( null, 'primary', null, true );

		do_action( 'footballpool_admin_options_screen_post', $action );
		
		self::admin_footer();
	}
}
