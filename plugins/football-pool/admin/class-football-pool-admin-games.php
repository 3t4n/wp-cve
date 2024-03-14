<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Admin_Games extends Football_Pool_Admin {
	public function __construct() {}
	
	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can quickly edit match scores and team names for final rounds (if applicable). If you wish to change all information about a match, then click the <em>\'edit\'</em> link.</p><p>After saving the match data the pool ranking is recalculated. If you have a lot of users this may take a while. You can (temporarily) disable the automatic recalculation of scores in the Plugin Options.</p>', 'football-pool' )
					),
					array(
						'id' => 'import',
						'title' => __( 'Import & Export', 'football-pool' ),
						'content' => __( '<p>Matches can be imported into the plugin using the import function (<em>\'Bulk change game schedule\'</em>). See the help page for more information about the required format.</p><p>On the import screen you can choose one of the already uploaded schedules or upload a new one (if write is enabled on the upload directory).</p><p>The import can add matches to your schedule, or completely overwrite the existing schedule. Please beware that when overwriting the schedule all existing predictions and rankings will be lost.</p><p>Existing matches can be exported using the <em>\'Download game schedule\'</em> button.</p>', 'football-pool' )
					),
					array(
						'id' => 'details',
						'title' => __( 'Match details', 'football-pool' ),
						'content' => __( '<ul><li><em>match date</em> must be in UTC format.</li></ul>', 'football-pool' )
					),
				);
		/** @noinspection HtmlUnknownAnchorTarget */
		$help_sidebar = sprintf( '<a href="?page=footballpool-help#teams-groups-and-matches">%s</a></p><p><a href="?page=footballpool-options">%s</a>'
								, __( 'Help section about matches and the import', 'football-pool' )
								, __( 'Plugin options page', 'football-pool' )
						);
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}
	
	public static function screen_options() {
		$args = array(
			'label' => __( 'Matches', 'football-pool' ),
			'default' => FOOTBALLPOOL_ADMIN_MATCHES_PER_PAGE,
			'option' => 'footballpool_matches_per_page'
		);
		add_screen_option( 'per_page', $args );
	}

	/** @noinspection PhpMissingBreakStatementInspection */
	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( __( 'Matches', 'football-pool' ), $subtitle, 'add new' );
		
		$log = $file = '';

		$action  = Football_Pool_Utils::request_string( 'action' );
		$item_id = Football_Pool_Utils::request_int( 'item_id', 0 );

		switch ( $action ) {
			case 'upload_csv':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				$uploaded_file = self::upload_csv();
				if ( Football_Pool_Utils::post_int( 'csv_import' ) === 1 ) {
					$file = $uploaded_file;
				}
			case 'import_csv':
			case 'import_csv_overwrite':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				$log = self::import_csv( $action, $file );
			case 'change-culture':
			case 'schedule':
				self::view_schedules( $log );
				break;
			case 'edit':
			case 'update':
			case 'update_single_match':
			case 'update_single_match_close':
				if ( $action !== 'edit' ) {
					check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				}
				self::edit_handler( $item_id, $action );
				break;
			case 'delete':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				$success = self::delete( $item_id );
				if ( $success )
					self::notice( sprintf( __( 'Game %d deleted.', 'football-pool' ), $item_id ) );
				else
					self::notice( __( 'Error performing the requested action.', 'football-pool' ), 'error' );
			case 'view':
			default:
				self::view();
		}
		
		self::admin_footer();
	}
	
	private static function upload_csv() {
		$err = false;
		$msg = '';
		if ( is_uploaded_file( $_FILES['csv_file']['tmp_name'] ) ) {
			$new_file = FOOTBALLPOOL_CSV_UPLOAD_DIR . $_FILES['csv_file']['name'];
			$extension = pathinfo( $new_file, PATHINFO_EXTENSION );
			if ( in_array( $extension, array( 'csv', 'txt' ) ) ) {
				if ( move_uploaded_file( $_FILES['csv_file']['tmp_name'], $new_file ) === false ) {
					$msg = __( 'Error: Upload of csv file failed.', 'football-pool' );
					$err = true;
				}
			} else {
				$msg = __( 'Error: Sorry, this file type is not permitted.', 'football-pool' );
				$err = true;
			}
		} else {
			$msg = __( 'Error: Upload of csv file failed.', 'football-pool' );
			$err = true;
		}
		
		if ( $err ) {
			self::notice( $msg, 'error' );
			return '';
		} else {
			self::notice( __( 'Upload of csv file successful.', 'football-pool' ) );
			return $_FILES['csv_file']['name'];
		}
	}
	
	private static function import_csv( $action = 'import_csv', $file = '' ) {
		global $pool;
		$msg = $err = [];
		
		if ( $action === 'upload_csv' && $file === '' ) return array( $err, $msg );
		
		if ( $file === '' ) {
			$file = Football_Pool_Utils::post_string( 'csv_file' );
		}
		
		if ( $file === '' || ( $fp = @fopen( $file, 'r' ) ) === false ) {
			if ( $file === '' ) {
				$err[] = __( 'No csv file selected.', 'football-pool' );
			} else {
				$err[] = __( 'Please check if the csv file exists and is readable.', 'football-pool' );
			}
		} else {
			// Check if metadata is set in the csv, if not it should contain the csv column definition
			$header = fgetcsv( $fp, 0, FOOTBALLPOOL_CSV_DELIMITER );
			if ( is_array( $header ) && strncmp( $header[0], '/*', 2 ) === 0 ) {
				/** @noinspection PhpStatementHasEmptyBodyInspection */
				while ( ( $header = fgetcsv( $fp, 0, FOOTBALLPOOL_CSV_DELIMITER ) ) !== false
				        && str_replace( [" ", "\t"], '', $header[0] ) !== '*/' ) {
					// keep reading
				}
				// with meta gone, next line should contain the csv column definition
				$header = fgetcsv( $fp, 0, FOOTBALLPOOL_CSV_DELIMITER );
			}

			// check the columns
			if ( $header !== false ) {
				$full_data = count( $header ) > 5;
				if ( $full_data ) {
					$column_names = explode(
						',',
						'play_date,home_team,away_team,stadium,match_type,home_team_photo,home_team_flag,home_team_link,home_team_group,home_team_group_order,home_team_is_real,away_team_photo,away_team_flag,away_team_link,away_team_group,away_team_group_order,away_team_is_real,stadium_photo'
					);
				} else {
					$column_names = explode(
						',',
						'play_date,home_team,away_team,stadium,match_type'
					);
				}

				if ( count( $header ) !== count( $column_names ) ) {
					$column_count = count( $column_names );
					$header_count = count( $header );
					$err[] = sprintf( __( 'Imported csv file should contain %d columns (header contains %d columns). See help page for the correct format.', 'football-pool' ), $column_count, $header_count );
				} else {
					for ( $i = 0; $i < count( $header ); $i++ ) {
						if ( trim( $header[$i] ) !== trim ( $column_names[$i] ) ) {
							$err[] = sprintf(
								__( 'Column %d header should be "%s" &rArr; not "%s"', 'football-pool' )
								, ( $i + 1 )
								, $column_names[$i]
								, $header[$i]
							);
						}
					}
					if ( count( $err ) === 0 ) {
						// Import the data (note: teams are always imported as active)
						$teams = new Football_Pool_Teams;
						$stadiums = new Football_Pool_Stadiums;

						// If action is 'overwrite' then first empty all tables
						if ( $action === 'import_csv_overwrite' ) {
							// Remove all match data except match types
							$scorehistory = $pool->get_score_table();
							self::empty_table( $scorehistory );
							self::empty_table( 'predictions' );
							self::empty_table( 'stadiums' );
							self::empty_table( 'rankings_matches' );
							self::empty_table( 'matches' );
							self::empty_table( 'teams' );
						}

						$row = 2; // Start at 2, because first row contains header
						while ( ( $data = fgetcsv( $fp, 0, FOOTBALLPOOL_CSV_DELIMITER ) ) !== false ) {
							// check the column count in the fetched row
							if ( count( $column_names ) !== count( $data ) ) {
								$err[] = sprintf( __( 'Invalid column count on row %d.', 'football-pool' ), $row );
								break;
							}
							// trim all values
							$data = array_map( 'trim', $data );

							// ** process all data **

							// match date
							$play_date = $data[0];
							if ( defined( 'FOOTBALLPOOL_CSV_DATE_FORMAT' ) ) {
								$date_format = FOOTBALLPOOL_CSV_DATE_FORMAT;
							} else {
								$date_format = 'Y-m-d H:i';
								if ( strlen( $play_date ) === strlen( '0000-00-00 00:00:00' ) ) {
									$date_format = 'Y-m-d H:i:s';
								}
							}
							if ( ! Football_Pool_Utils::is_valid_mysql_date( $play_date, $date_format ) ) {
								// Date is not valid, so we change it to the current date to be able to import a match.
								$csv_date = $play_date;
								$play_date = current_time( 'mysql', true );
								$err[] = sprintf(
									__( "Invalid date '%s' on row %d. Date changed to current date '%s'.", 'football-pool' )
									, $csv_date, $row, $play_date );
							} else {
								// If the play date is in a non-standard format, but valid, we translate it to ISO format
								if ( $date_format !== 'Y-m-d H:i' && $date_format !== 'Y-m-d H:i:s' ) {
									$d = DateTime::createFromFormat( $date_format, $play_date );
									$play_date = $d->format( 'Y-m-d H:i:s' );
								}
							}

							// Home team
							$extra_data = '';
							if ( $full_data ) {
								$group = Football_Pool_Groups::get_group_by_name( $data[8], 'addnew' );
								$group_id = ( is_object( $group ) ? $group->id : 0 );

								$extra_data = array(
									'photo' => $data[5],
									'flag' => $data[6],
									'link' => $data[7],
									'group_id' => $group_id,
									'group_order' => $data[9],
									'is_real' => $data[10],
									'is_active' => 1,
								);
							}
							$home_team = $teams->get_team_by_name( $data[1], 'addnew', $extra_data );
							$home_team_id = $home_team->id;
							// Check if there is a valid home team for this match, if not, break with error
							$match_row_err = self::break_with_error( $home_team_id, __( 'home team', 'football-pool' ), $row );
							if ( $match_row_err !== false ) {
								$err[] = $match_row_err;
								break;
							}
							if ( isset( $home_team->inserted ) && $home_team->inserted === true ) {
								$msg[] = sprintf(
									__( 'Team %d added: %s', 'football-pool' )
									, $home_team->id, $home_team->name
								);
							}

							// Away team
							$extra_data = '';
							if ( $full_data ) {
								$group = Football_Pool_Groups::get_group_by_name( $data[14], 'addnew' );
								$group_id = ( is_object( $group ) ? $group->id : 0 );

								$extra_data = array(
									'photo' => $data[11],
									'flag' => $data[12],
									'link' => $data[13],
									'group_id' => $group_id,
									'group_order' => $data[15],
									'is_real' => $data[16],
									'is_active' => 1,
								);
							}
							$away_team = $teams->get_team_by_name( $data[2], 'addnew', $extra_data );
							$away_team_id = $away_team->id;
							// Check if there is a valid away team for this match, if not, break with error
							$match_row_err = self::break_with_error( $away_team_id, __( 'away team', 'football-pool' ), $row );
							if ( $match_row_err !== false ) {
								$err[] = $match_row_err;
								break;
							}
							if ( isset( $away_team->inserted ) && $away_team->inserted === true ) {
								$msg[] = sprintf(
									__( 'Team %d added: %s', 'football-pool' )
									, $away_team->id, $away_team->name
								);
							}

							// Stadium
							$extra_data = '';
							if ( $full_data ) {
								$extra_data = array( 'photo' => $data[17] );
							}
							$stadium = $stadiums->get_stadium_by_name( $data[3], 'addnew', $extra_data );
							$stadium_id = ( is_object( $stadium ) ? $stadium->id : 0 );
							// Check if there is a valid stadium for this match, if not, break with error
							$match_row_err = self::break_with_error( $stadium_id, __( 'stadium', 'football-pool' ), $row );
							if ( $match_row_err !== false ) {
								$err[] = $match_row_err;
								break;
							}
							// Add message for added stadium
							if ( isset( $stadium->inserted ) && $stadium->inserted === true ) {
								$msg[] = sprintf(
									__( 'Stadium %d added: %s', 'football-pool' )
									, $stadium->id, $stadium->name
								);
							}

							// Match type
							$match_type = $pool->matches->get_match_type_by_name( $data[4], 'addnew' );
							$match_type_id = $match_type->id;
							// Check if there is a valid match type for this match, if not, break with error
							$match_row_err = self::break_with_error( $match_type_id, __( 'match type', 'football-pool' ), $row );
							if ( $match_row_err !== false ) {
								$err[] = $match_row_err;
								break;
							}
							// Add message for added match type
							if ( isset( $match_type->inserted ) && $match_type->inserted === true ) {
								$msg[] = sprintf(
									__( 'Match Type %d added: %s', 'football-pool' )
									, $match_type->id, $match_type->name
								);
							}

							// Save the match in the database
							$id = self::update_match(
								0, $home_team_id, $away_team_id, null, null, $play_date, $stadium_id, $match_type_id
							);
							$msg[] = sprintf(
								__( 'Match %d imported: %s - %s for date "%s"', 'football-pool' )
								, $id, $home_team->name, $away_team->name, $play_date
							);

							$row++;
						}
					}
				}
			}
		}
		
		if ( isset( $fp ) ) @fclose( $fp );
		
		if ( count( $msg ) > 0 ) {
			$msg[] = self::link_button( 
								__( 'Done', 'football-pool' ),
								$_SERVER["REQUEST_URI"],
								true,
								null,
								'primary'
							);
		}
		// return an array containing error messages and/or import messages
		return [$err, $msg];
	}
	
	private static function break_with_error( $val, $type, $row ) {
		if ( $val === 0 || $val === null ) {
			$result = sprintf( __( 'Invalid or missing %1$s value on row %2$d.', 'football-pool' ), $type, $row );
		} else {
			$result = false;
		}
		return $result;
	}
	
	private static function get_meta_from_csv( $file ) {
		$all_headers = array(
							'contributor'	=> 'Contributor',
							'translator'	=> 'Translator',
							'assets'		=> 'Assets URI',
						);
		return get_file_data( $file, $all_headers );
	}
	
	private static function view_schedules( $log = '' ) {
		if ( is_array( $log ) ) {
			$errors = $log[0];
			$import_log = $log[1];
			if ( count( $errors ) > 0 ) self::notice( implode( '<br>', $errors ), 'error' );
			if ( count( $import_log ) > 0 ) self::notice( implode( '<br>', $import_log ), 'info' );
		}
		
		// check if upload dir exists and is writable
		$data_is_readable = is_readable( trailingslashit( FOOTBALLPOOL_PLUGIN_DIR ) . 'data/schedules' );
		$upload_is_readable = is_readable( FOOTBALLPOOL_CSV_UPLOAD_DIR );
		$upload_is_writable = is_writable( FOOTBALLPOOL_CSV_UPLOAD_DIR );
		
		if ( ! $upload_is_readable && ! $data_is_readable ) {
			// nothing readable so exit with an error
			self::notice( __( "Please make sure that the directory 'data/schedules' exists in the plugin directory and that it is readable!", 'football-pool' ), 'error' );
			self::notice( __( "Please make sure that the directory 'football-pool/schedules' exists in the WordPress uploads directory and that it is readable!", 'football-pool' ), 'error' );
			return;
		}
		
		// show warnings when one of the dirs is not readable
		if ( ! $data_is_readable ) {
			self::notice( __( "Please make sure that the directory 'data/schedules' exists in the plugin directory and that it is readable!", 'football-pool' ), 'error' );
		}
		if ( ! $upload_is_readable ) {
			self::notice( __( "Please make sure that the directory 'football-pool/schedules' exists in the WordPress uploads directory and that it is readable!", 'football-pool' ), 'error' );
		}
		
		if ( ! $upload_is_writable ) {
			self::notice( sprintf( __( "Uploading of new csv files is not possible at the moment. Directory '%s' is not writable.", 'football-pool' ), FOOTBALLPOOL_CSV_UPLOAD_DIR ), 'warning' );
		}
		
		if ( $upload_is_readable || $data_is_readable ) {
			echo '<h3>', __( 'Choose a new game schedule', 'football-pool' ), '</h3>';
			echo '<p>', __( 'Import any of the following files. Overwrite the existing game schedule or add to the existing schedule.', 'football-pool' ), '</p>';
			
			$locale = Football_Pool::get_locale();
			$locale_filter = Football_Pool_Utils::post_string( 'culture', Football_Pool_Utils::get_fp_option( 'csv_file_filter', '*' ) );
			self::set_value( 'csv_file_filter', $locale_filter );
			
			$options = array(
							array( 'value' => '*', 'text' => __( 'all files', 'football-pool' ) ),
							array( 'value' => $locale, 'text' => sprintf( __( 'only \'%s\' files', 'football-pool' ), $locale ) ),
						);
			
			echo '<div class="import culture-select">';
			echo self::dropdown( 'culture', $locale_filter, $options );
			self::secondary_button( __( 'change', 'football-pool' ), 'change-culture' );
			echo '</div>';
			
			$i = 0;
			$files = [];
			// get the user's  files
			$handle = @opendir( FOOTBALLPOOL_CSV_UPLOAD_DIR );
			if ( $handle ) {
				while ( false !== ( $entry = readdir( $handle ) ) ) {
					$locale_check = ( $locale_filter == '*' || strpos( $entry, $locale_filter ) !== false );
					if ( $entry != '.' && $entry != '..' && $locale_check ) {
						$meta = self::get_meta_from_csv( FOOTBALLPOOL_CSV_UPLOAD_DIR . $entry );
						$files[$i]['file'] = $entry;
						$files[$i]['file_path'] = FOOTBALLPOOL_CSV_UPLOAD_DIR . $entry;
						$files[$i]['meta'] = $meta;
						$i++;
					}
				}
			}
			// get the files included in the install
			$schedule_dir = trailingslashit( FOOTBALLPOOL_PLUGIN_DIR . 'data/schedules' );
			$handle = @opendir( $schedule_dir );
			if ( $handle ) {
				while ( false !== ( $entry = readdir( $handle ) ) ) {
					$locale_check = ( $locale_filter == '*' || strpos( $entry, $locale_filter ) !== false );
					if ( $entry != '.' && $entry != '..' && $locale_check ) {
						$meta = self::get_meta_from_csv( $schedule_dir . $entry );
						$files[$i]['file'] = $entry;
						$files[$i]['file_path'] = $schedule_dir . $entry;
						$files[$i]['meta'] = $meta;
						$i++;
					}
				}
			}
			
			// write the content
			echo '<table class="fp-radio-list">';
			echo '<tr>
					<th></th>
					<th>', __( 'File', 'football-pool' ), '</th>
					<th>', __( 'Contributor', 'football-pool' ), '</th>
					<th>', __( 'Assets', 'football-pool' ), '</th>
				</tr>';
			$i = 0;
			foreach( $files as $file ) {
				$i++;
				echo '<tr class="csv-file"><td><input id="csv-', $i, '" name="csv_file" type="radio" value="', esc_attr( $file['file_path'] ), '"></td>';
				echo '<td><label for="csv-', $i, '">', $file['file'], '</label></td>';
				echo '<td>', $file['meta']['contributor'], ' ';
				if ( $file['meta']['translator'] != '' ) {
					printf( __( '(translation: %s)', 'football-pool' ), $file['meta']['translator'] );
				}
				echo '</td>';
				echo '<td>';
				if ( $file['meta']['assets'] != '' ) {
					echo '<a title="', __( 'Upload these files to the \'football-pool\' folder in the uploads folder of your WP install', 'football-pool' ), '" href="', $file['meta']['assets'], '">', __( 'download files', 'football-pool' ), '</a>';
				} else {
					echo '';
				}
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			
			if ( count( $files ) > 0 ) {
				echo '<p class="submit">';
				self::primary_button( 
					__( 'Import CSV', 'football-pool' ), 
					array(
						'import_csv',
						'return confirm(\'' . __( 'Are you sure you want to add these matches to the existing schedule?', 'football-pool' ) . '\')' 
					), 
					false 
				);
				self::secondary_button( 
					__( 'Import CSV & Overwrite', 'football-pool' ), 
					array( 
						'import_csv_overwrite', 
						sprintf( 'return ( confirm( \'%s\' ) ? confirm( \'%s\' ) : false )'
								, __( 'Are you sure you want to overwrite the game schedule with this schedule?\n\nAll predictions and scores will also be overwritten!', 'football-pool' )
								, __( 'Are you really, really, really sure?', 'football-pool' )
						)
					), 
					false 
				);
				self::cancel_button();
				echo '</p>';
			} else {
				self::notice( __( "No csv files found in 'upload' directory.", 'football-pool' ), 'warning' );
			}
		}
	
		if ( $upload_is_writable ) {
			// set the right the enctype for the upload
			echo '</form><form method="post" enctype="multipart/form-data" action="">';
			wp_nonce_field( FOOTBALLPOOL_NONCE_ADMIN );
			echo '<input type="hidden" name="action" value="upload_csv">';
			echo '<h3>', __( 'Upload new game schedule', 'football-pool' ), '</h3>';
			// link to help/data explanation and explain the extra data that is needed for teams etc (e.g. photo)
			// option to just upload, add or overwrite
			// upload file
			echo '<div>';
			echo '<input type="file" name="csv_file">';
			self::secondary_button( 
				__( 'Upload CSV', 'football-pool' ), 
				'upload_csv',
				false
			);
			echo '</div>';
		}
	}

	private static function get_match_types() {
		global $pool;

		$match_types = $pool->matches->get_match_types();
		$output = [];
		foreach ( $match_types as $match_type ) {
			$output[$match_type->id] = Football_Pool_Utils::xssafe( $match_type->name );
		}

		return $output;
	}

	private static function view() {
		global $pool;
		$rows = $pool->matches->matches;

		// TODO: update search to a date and/or match info filtering/search
		$search = Football_Pool_Utils::request_str( 's' );
		$date = Football_Pool_Utils::request_str( 'match_date_search', '' );
		$match_type_id = Football_Pool_Utils::request_int( 'match_type_search' );
		$match_types = self::get_match_types();

		$search_block = '';
		if ( count ( $match_types ) > 0 ) {
			$match_types = array( __( 'Show only:', 'football-pool' ) ) + $match_types;
			$search_block .= Football_Pool_Utils::select( 'match_type_search', $match_types, $match_type_id );
			$search_block .= Football_Pool_Admin::get_secondary_button( __( 'Filter', 'football-pool' ), 'search' );
		}

		// filter the rows by match type id
		if ( $match_type_id > 0 ) {
			$rows = array_filter( $rows, function( $v ) use ( $match_type_id ) {
						return isset( $v['match_type_id'] ) && $v['match_type_id'] == $match_type_id;
					} );
		}

		// option to alter (e.g. sorting) the matches in the admin independently from the frontend
		$rows = apply_filters( 'footballpool_admin_matches', $rows );
		
		$pagination = new Football_Pool_Pagination( count( $rows ) );
		$pagination->set_page_size( self::get_screen_option( 'per_page' ) );
		$pagination->add_query_arg( 'match_type_search', $match_type_id );
		$pagination->wrap = false;
		
		$rows = array_slice( 
							$rows
							, ( $pagination->current_page - 1 ) * $pagination->get_page_size()
							, $pagination->get_page_size()
							, true
				);
		
		$full_data = ( Football_Pool_Utils::get_fp_option( 'export_format', 0, 'int' ) == 0 );
		$download_url = wp_nonce_url( FOOTBALLPOOL_PLUGIN_URL . 'admin/csv-export-matches.php'
									, FOOTBALLPOOL_NONCE_CSV );
		if ( ! $full_data ) $download_url = esc_url( add_query_arg( array( 'format' => 'minimal' ), $download_url ) );
		
		echo '<p class="submit">';
		submit_button( null, 'primary', 'submit', false );
		echo '<span class="matches-bulk-action-buttons">';
		self::secondary_button( __( 'Import matches', 'football-pool' ), 'schedule', false );
		self::secondary_button( 
			__( 'Export matches', 'football-pool' ),
			$download_url, 
			false, 
			'link' 
		);
		echo '</span></p>';

		echo '<div class="tablenav top">';
		$pagination->wrap = false;
		$pagination->show();
		echo $search_block;
		echo '</div>';

		self::print_matches( $rows );

		// $pagination->wrap = true;
		// $pagination->show();

		submit_button();
	}
	
	private static function edit_handler( $item_id, $action ) {
		$success = false;
		switch ( $action ) {
			case 'update':
				$success = self::update();
				break;
			case 'update_single_match':
			case 'update_single_match_close':
				$success = self::update_single_match( $item_id );
				if ( $item_id == 0 ) $item_id = $success;
				if ( $success ) self::update_score_history();
				break;
			case 'edit':
				self::edit( $item_id );
				break;
		}
		
		if ( $action !== 'edit' ) {
			if ( $success ) {
				self::notice( __( 'Values updated.', 'football-pool' ) );
				// reset the matches cache
				global $pool;
				// todo: can be replaced with one call to wp_cache_delete_multiple from wp 6.0.0 onwards
				wp_cache_delete( FOOTBALLPOOL_CACHE_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
				wp_cache_delete( FOOTBALLPOOL_CACHE_ALL_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
				$pool->matches = new Football_Pool_Matches();
			}
			if ( $action === 'update_single_match' ) {
				self::edit_handler( $item_id, 'edit' );
			} else {
				self::view();
			}
		}
	}
	
	private static function delete( $item_id ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		do_action( 'footballpool_admin_match_delete', $item_id );
		
		// delete match, corresponding predictions and update linked bonus questions
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}matches WHERE id = %d", $item_id );
		$success = ( $wpdb->query( $sql ) !== false );
		if ( $success ) {
			// clear cache
			global $pool;
			// todo: can be replaced with one call to wp_cache_delete_multiple from wp 6.0.0 onwards
			wp_cache_delete( FOOTBALLPOOL_CACHE_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
			wp_cache_delete( FOOTBALLPOOL_CACHE_ALL_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
			$pool->matches = new Football_Pool_Matches();
			// remove linked info
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}predictions WHERE match_id = %d", $item_id );
			$success = ( $wpdb->query( $sql ) !== false );
			$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions SET match_id = 0 WHERE match_id = %d", $item_id );
			$success = $success && ( $wpdb->query( $sql ) !== false );
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}rankings_matches WHERE match_id = %d", $item_id );
			$success = $success && ( $wpdb->query( $sql ) !== false );
			// update score history
			$success = $success && self::update_score_history();
		}
		
		return $success;
	}
	
	private static function edit( $item_id ) {
		global $pool;

		$values = array(
						'play_date' => '',
						'home_team_id' => '',
						'away_team_id' => '',
						'home_score' => '',
						'away_score' => '',
						'stadium_id' => 0,
						'match_type_id' => 0
						);
		
		$matches = $pool->matches;
		$match = $matches->matches[$item_id] ?? false;
		if ( $match && $item_id > 0 ) {
			$values = $match;
		}
		
		$types = $matches->get_match_types();
		$options = [];
		foreach ( $types as $type ) {
			$options[] = array( 'value' => $type->id, 'text' => $type->name );
		}
		$types = $options;
		
		$venues = new Football_Pool_Stadiums;
		$venues = $venues->get_stadiums();
		$options = [];
		foreach ( $venues as $venue ) {
			$options[] = ['value' => $venue->id, 'text' => $venue->name];
		}
		$venues = $options;
		
		$teams = new Football_Pool_Teams;
		$teams = $teams->team_names;
		$options = [];
		foreach( $teams as $id => $name ) {
			$options[] = ['value' => $id, 'text' => $name];
		}
		$teams = $options;
		
		// check if there is enough information to fill a match
		if ( count( $teams ) === 0 || count( $types ) === 0 || count( $venues ) === 0 ) {
			/** @noinspection HtmlUnknownTarget */
			self::notice( sprintf( __( 'You have to enter some <a href="%s">teams</a>, <a href="%s">venues</a> and <a href="%s">match types</a> first.', 'football-pool' ), '?page=footballpool-teams', '?page=footballpool-venues', '?page=footballpool-matchtypes'), 'important' );
			self::cancel_button( __( 'Back', 'football-pool' ), true );
			return;
		}
		
		$matchdate = new DateTime( $values['play_date'] );
		$matchdate = $matchdate->format( 'Y-m-d H:i' );
		$matchdate_local = Football_Pool_Utils::date_from_gmt( $values['play_date'] );
		if ( $item_id > 0 ) {
			$desc = sprintf(
				'<span title="%s">%s</span>',
				__( 'time of the match in local time (WordPress setting)', 'football-pool' ),
				sprintf( __( 'local time is %s', 'football-pool' ), $matchdate_local )
			);
		} else {
			$desc = '';
		}
		$cols = array(
					array( 'text', __( 'match date (UTC)', 'football-pool' ), 'match_date', $matchdate, $desc ),
					array( 'dropdown', __( 'home team', 'football-pool' ), 'home_team_id', $values['home_team_id'], $teams, '' ),
					array( 'dropdown', __( 'away team', 'football-pool' ), 'away_team_id', $values['away_team_id'], $teams, '' ),
					array( 'text', __( 'home score', 'football-pool' ), 'home_score', $values['home_score'], '' ),
					array( 'text', __( 'away score', 'football-pool' ), 'away_score', $values['away_score'], '' ),
					array( 'dropdown', __( 'stadium', 'football-pool' ), 'stadium_id', $values['stadium_id'], $venues, '' ),
					array( 'dropdown', __( 'match type', 'football-pool' ), 'match_type_id', $values['match_type_id'], $types, '' ),
					array( 'hidden', '', 'item_id', $item_id )
				);
		self::value_form( $cols );
		echo '<p class="submit">';
		self::primary_button( __( 'Save & Close', 'football-pool' ), 'update_single_match_close' );
		self::secondary_button( __( 'Save', 'football-pool' ), 'update_single_match' );
		self::cancel_button();
		echo '</p>';
	}
	
	private static function update_single_match( $item_id ) {
		$home_score = Football_Pool_Utils::post_integer( 'home_score', -1 );
		$away_score = Football_Pool_Utils::post_integer( 'away_score', -1 );
		$home_team = Football_Pool_Utils::post_integer( 'home_team_id', -1 );
		$away_team = Football_Pool_Utils::post_integer( 'away_team_id', -1 );
		$match_date = Football_Pool_Utils::post_string( 'match_date', '0000-00-00 00:00' );
		$stadium_id = Football_Pool_Utils::post_integer( 'stadium_id', -1 );
		$match_type_id = Football_Pool_Utils::post_integer( 'match_type_id', -1 );
		
		$success = self::update_match( $item_id, $home_team, $away_team, $home_score, $away_score, 
										$match_date, $stadium_id, $match_type_id );
		
		return $success;
	}
	
	private static function update() {
		global $pool;
		$match_saved = false;
		
		// update scores for all matches
		foreach( $pool->matches->matches as $row ) {
			$match_id = $row['id'];
			$match_on_form = ( Football_Pool_Utils::post_integer( '_match_id_' . $match_id, 0 ) == $match_id );
			$home_score = Football_Pool_Utils::post_integer( '_home_score_' . $match_id, -1 );
			$away_score = Football_Pool_Utils::post_integer( '_away_score_' . $match_id, -1 );
			$home_team = Football_Pool_Utils::post_integer( '_home_team_' . $match_id, -1 );
			$away_team = Football_Pool_Utils::post_integer( '_away_team_' . $match_id, -1 );
			$match_date = Football_Pool_Utils::post_string( '_match_date_' . $match_id, '1900-01-01 00:00' );
			
			if ( $match_on_form ) {
				$match_saved = self::update_match( $match_id, $home_team, $away_team, 
													$home_score, $away_score, $match_date );
			}
		}
		
		if ( $match_saved ) $match_saved = $match_saved && self::update_score_history();
		
		return $match_saved;
	}
	
	private static function print_matches( $rows ) {	
		$date_title = '';
		$matchtype = '';
		
		if ( ! is_array( $rows ) || count( $rows ) === 0 ) {
			printf( '<div class="no-matches-notice"><img src="%sassets/admin/images/matches-import-here.png" alt="%s" title="%s"></div>'
				, FOOTBALLPOOL_PLUGIN_URL
				, __( 'import a new schedule here', 'football-pool' )
				, __( 'import a new schedule here', 'football-pool' )
			);
		} else {
			$tabindex = 1;
			
			echo '<table id="matchinfo" class="wp-list-table widefat matchinfo"><tbody id="the-list">';
			foreach( $rows as $row ) {
				if ( $matchtype != $row['matchtype'] ) {
					$matchtype = $row['matchtype'];
					echo '<tr class="type-row"><td class="sidebar-name" colspan="8"><h3>', Football_Pool_Utils::xssafe( $matchtype ), '</h3></td></tr>';
				}
				
				$matchdate = new DateTime( $row['play_date'] );
				$matchdate = $matchdate->format( 'Y-m-d H:i' );
				$localdate = new DateTime( Football_Pool_Utils::date_from_gmt( $matchdate ) );
				// $localdate = new DateTime( Football_Pool_Matches::format_match_time( $matchdate, 'Y-m-d H:i' ) );
				$localdate_formatted = date_i18n( __( 'M d, Y', 'football-pool' )
												, $localdate->format( 'U' ) );
				if ( $date_title != $localdate_formatted ) {
					$date_title = $localdate_formatted;
					echo '<tr class="date-row"><td class="sidebar-name"></td>',
							'<td class="sidebar-name" title="', __( 'time of the match in local time (WordPress setting)', 'football-pool' ), '">', __( 'local time', 'football-pool' ), '</td>',
							'<td class="sidebar-name"><span title="Coordinated Universal Time">', __( 'UTC', 'football-pool' ), '</span></td>',
							'<td class="sidebar-name date-title" colspan="5">', $date_title, '</td>',
							'</tr>';
				}
				
				$page = wp_nonce_url( sprintf( '?page=%s&amp;item_id=%d'
												, Football_Pool_Utils::get_string( 'page' )
												, $row['id'] )
										, FOOTBALLPOOL_NONCE_ADMIN );
				$confirm = sprintf( __( 'You are about to delete match %d.', 'football-pool' )
									, $row['id'] 
								);
				$confirm .= ' ' . __( "Are you sure? `OK` to delete, `Cancel` to stop.", 'football-pool' );
				echo '<tr class="match-row match-', $row['id'], '">',
						'<td class="time column-match-id"><span class="item-id">', __( 'id', 'football-pool' ), ': ', $row['id'], '</span>', self::hidden_input( "_match_id_{$row['id']}", $row['id'], 'return' ), '</td>',
						'<td class="time local column-localtime">', $localdate->format( 'Y-m-d H:i' ), '<br><div class="row-actions"><span class="edit"><a href="', $page, '&amp;action=edit">', __( 'Edit' ), '</a></span> | <span class="delete"><a onclick="return confirm( \'', $confirm, '\' )" href="', $page, '&amp;action=delete">', __( 'Delete' ), '</a></span></div></td>',
						'<td class="time UTC column-utctime" title="', __( 'change match time', 'football-pool' ), '">', self::show_input( '_match_date_' . $row['id'], $matchdate, 16, '' ), '</td>',
						'<td class="home column-home">', self::teamname_input( (int) $row['home_team_id'], '_home_team_'.$row['id'] ), '</td>',
						'<td class="score column-home-score">', self::show_input( '_home_score_' . $row['id'], $row['home_score'], 3, 'score', $tabindex++ ), '</td>',
						'<td>-</td>',
						'<td class="score column-away-score">', self::show_input( '_away_score_' . $row['id'], $row['away_score'], 3, 'score', $tabindex++ ), '</td>',
						'<td class="away column-away">', self::teamname_input( (int) $row['away_team_id'], '_away_team_' . $row['id'] ), '</td>',
						'</tr>';
			}
			echo '</tbody></table>';
		}
	}
	
	private static function show_input( $name, $value, $max_length = 3, $class = 'score', $tabindex = false ) {
		$tabindex = ( $tabindex !== false ) ? "tabindex=\"{$tabindex}\" " : "";
		return sprintf( '<input type="text" name="%s" value="%s" maxlength="%s" class="%s" %s/>', 
						$name, $value, $max_length, $class, $tabindex );
	}
	
	private static function teamname_input( $team, $input_name ) {
		$teams = new Football_Pool_Teams;
		if ( ! is_int( $team ) || ! isset( $teams->team_names[$team] ) ) return '';
		
		if ( ! $teams->team_types[$team] ) {
			// for matches beyond the group phase and for non-real teams a dropdown
			return self::team_select( $team, $input_name );
		} else {
			return sprintf( '%s<input type="hidden" name="%s" id="%s" value="%s">'
							, Football_Pool_Utils::xssafe( $teams->team_names[$team] )
							, esc_attr( $input_name )
							, esc_attr( $input_name )
							, esc_attr( $team )
					);
		}
	}
	
	private static function team_select( $team, $input_name ) {
		$teams = new Football_Pool_Teams;
		
		$select = '<select name="' . $input_name . '" id="' . $input_name . '">';
		foreach ( $teams->team_names as $id => $name ) {
			$select .= '<option value="' . $id . '"'
						. ( $team == $id ? ' selected="selected"' : '' ) 
						. '>' . Football_Pool_Utils::xssafe( $name ) . '</option>';
		}
		$select .= '</select>';
		return $select;
	}
	
	private static function update_match( $id, $home_team, $away_team, $home_score, $away_score, 
									$match_date, $stadium_id = null, $match_type_id = null ) {
		// if no valid team Id return false
		if ( $home_team == -1 || $away_team == -1 ) return false;

		// check if match date is valid
		$date_format = 'Y-m-d H:i';
		if ( strlen( $match_date ) === strlen( '0000-00-00 00:00:00' ) ) $date_format = 'Y-m-d H:i:s';
		if ( ! Football_Pool_Utils::is_valid_mysql_date( $match_date, $date_format ) ) $match_date = current_time( 'mysql', 1 );

		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $id == 0 ) {
			if ( $home_score < 0 || $away_score < 0 ) {
				$sql = $wpdb->prepare( "INSERT INTO {$prefix}matches 
											( home_team_id, away_team_id, home_score, away_score, 
												play_date, stadium_id, matchtype_id )
										VALUES ( %d, %d, NULL, NULL, %s, %d, %d )"
									, $home_team, $away_team, $match_date, $stadium_id, $match_type_id
								);
			} else {
				$sql = $wpdb->prepare( "INSERT INTO {$prefix}matches 
											( home_team_id, away_team_id, home_score, away_score, 
												play_date, stadium_id, matchtype_id )
										VALUES ( %d, %d, %d, %d, %s, %d, %d )"
									, $home_team, $away_team, $home_score, $away_score
									, $match_date, $stadium_id, $match_type_id
								);
			}
		} else {
			$match = $pool->matches->matches[$id];
			$old_home_score = $match['home_score'];
			$old_away_score = $match['away_score'];
			$old_date = new DateTime( $match['date'] );
			$old_date = $old_date->format( 'Y-m-d H:i' );
			$old_home_id = $match['home_team_id'];
			$old_away_id = $match['away_team_id'];
			if ( ! is_int( $stadium_id ) ) $stadium_id = $match['stadium_id'];
			if ( ! is_int( $match_type_id ) ) $match_type_id = $match['match_type_id'];
			
			if ( $home_score < 0 || $away_score < 0 ) {
				$sql = $wpdb->prepare( "UPDATE {$prefix}matches SET 
											home_team_id = %d, away_team_id = %d, 
											home_score = NULL, away_score = NULL,
											play_date = %s, stadium_id = %d, matchtype_id = %d
										WHERE id = %d",
									$home_team, $away_team, $match_date, $stadium_id, $match_type_id, $id
								);
			} else {
				$sql = $wpdb->prepare( "UPDATE {$prefix}matches SET 
											home_team_id = %d, away_team_id = %d, 
											home_score = %d, away_score = %d, 
											play_date = %s, stadium_id = %d, matchtype_id = %d
										WHERE id = %d",
									$home_team, $away_team, $home_score, $away_score, 
									$match_date, $stadium_id, $match_type_id, $id
								);
			}
		}
		
		$success = ( $wpdb->query( $sql ) !== false );
		
		if ( $id  > 0 ) {
			return $success;
		} else {
			$id = $success ? $wpdb->insert_id : 0;
			do_action( 'footballpool_admin_match_save', $id );
			return $id;
		}
	}

}
