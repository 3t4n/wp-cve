<?php
/* wppa-widget-functions.php
/* Package: wp-photo-album-plus
/*
/* Version 8.4.05.001
/*
*/

/*
This file contans functions to get the photo of the day selection pool and to get THE photo of the day.
This fila also contains functions for the use in the widget activation screens for all widgets.
*/

// This function returns an array of photos that meet the current photo of the day selection criteria
function wppa_get_widgetphotos( $alb, $option = '' ) {
global $wpdb;

	if ( ! $alb ) return false;

	$photos = false;
	$query = '';
	if ( $option == 'count' ) {
		$option = '';
		$count_only = true;
	}
	else {
		$count_only = false;
	}

	// Compile status clause
	switch( wppa_opt( 'potd_status_filter' ) ) {
		case 'publish':
			$statusclause = " status = 'publish' ";
			break;
		case 'featured':
			$statusclause = " status = 'featured' ";
			break;
		case 'gold':
			$statusclause = " status = 'gold' ";
			break;
		case 'silver':
			$statusclause = " status = 'silver' ";
			break;
		case 'bronze':
			$statusclause = " status = 'bronze' ";
			break;
		case 'anymedal':
			$statusclause = " status IN ( 'gold', 'silver', 'bronze' ) ";
			break;
		default:
			$statusclause = " status <> 'scheduled' ";
			if ( ! is_user_logged_in() ) {
				$statusclause .= " AND status <> 'private' ";
			}
	}

	// If physical album(s) and include sub albums is active, make it an enumeration(with ',' as seperator)
	if ( wppa_opt( 'potd_album_type' ) == 'physical' && wppa_switch( 'potd_include_subs' ) ) {
		$alb = str_replace( ',', '.', $alb );
		$alb = wppa_expand_enum( wppa_alb_to_enum_children( $alb ) );
		$alb = str_replace( '.', ',', $alb );
	}

	// If physical albums and inverse selection is active, invert selection
	if ( wppa_opt( 'potd_album_type' ) == 'physical' && wppa_switch( 'potd_inverse' ) ) {
		$albs = explode( ',', $alb );
		$all  = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums " );
		$alb  = implode( ',', array_diff( $all, $albs ) );
	}

	/* Now find out the final query */

	/* Physical albums */

	// Is it a single album?
	if ( wppa_is_int( $alb ) ) {
		$query = $wpdb->prepare(	"SELECT id, p_order " .
									"FROM $wpdb->wppa_photos " .
									"WHERE album = %s " .
									"AND " . $statusclause . $option,
									$alb );
	}

	// Is it an enumeration of album ids?
	elseif ( strchr( $alb, ',' ) ) {
		$alb = trim( $alb, ',' );

		$query = 	"SELECT id, p_order " .
					"FROM $wpdb->wppa_photos " .
					"WHERE album IN ( " . $alb . " ) " .
					"AND " . $statusclause . $option;
	}

	/* Virtual albums */
	// Is it ALL?
	elseif ( $alb == 'all' ) {
		$query = 	"SELECT id, p_order " .
					"FROM $wpdb->wppa_photos " .
					"WHERE " . $statusclause . $option;
	}

	// Is it SEP?
	elseif ( $alb == 'sep' ) {
		$albs = $wpdb->get_results( "SELECT id, a_parent FROM $wpdb->wppa_albums", ARRAY_A );
		$query = "SELECT id, p_order FROM $wpdb->wppa_photos WHERE ( album = '0' ";
		$first = true;
		foreach ( $albs as $a ) {
			if ( $a['a_parent'] == '-1' ) {
				$query .= "OR album = '" . $a['id'] . "' ";
			}
		}
		$query .= ") AND " . $statusclause . $option;
	}

	// Is it ALL-SEP?
	elseif ( $alb == 'all-sep' ) {
		$albs = $wpdb->get_results( "SELECT id, a_parent FROM $wpdb->wppa_albums", ARRAY_A );
		$query = "SELECT id, p_order FROM $wpdb->wppa_photos WHERE ( album IN ('0'";
		foreach ( $albs as $a ) {
			if ( $a['a_parent'] != '-1' ) {
				$query .= ",'" . $a['id'] . "'";
			}
		}
		$query .= ") ) AND " . $statusclause . $option;
	}

	// Is it Topten?
	elseif ( $alb == 'topten' ) {

		// Find the 'top' policy
		switch ( wppa_opt( 'topten_sortby' ) ) {
			case 'mean_rating':
				$sortby = 'mean_rating DESC, rating_count DESC, views DESC';
				break;
			case 'rating_count':
				$sortby = 'rating_count DESC, mean_rating DESC, views DESC';
				break;
			case 'views':
				$sortby = 'views DESC, mean_rating DESC, rating_count DESC';
				break;
			default:
				wppa_error_message( 'Unimplemented sorting method' );
				$sortby = '';
				break;
		}

		// It is assumed that status is ok for top rated photos
		$query = "SELECT id, p_order FROM $wpdb->wppa_photos ORDER BY " . $sortby . " LIMIT " . wppa_opt( 'topten_count' );
	}

	// Do the query
	if ( $query ) {

		// First get the count
		if ( $count_only ) {
			$tquery = str_replace( 'id, p_order', 'COUNT(*)', $query);
			$total = $wpdb->get_var( $tquery );
			return $total;
		}

		if ( strpos( $query, 'LIMIT' ) === false ) {
			$query .= ' LIMIT 100';
		}
		$photos = $wpdb->get_results( $query, ARRAY_A );

		// Strip void photos
		$photos = wppa_strip_void_photos( $photos );
	}
	else {
		$photos = array();
	}

	// Ready
	return $photos;
}

// get the photo of the day
function wppa_get_potd( $details = false ) {
global $wpdb;

	$id = 0;
	$seqno = 0;
	$offset = 0;

	switch ( wppa_opt( 'potd_method' ) ) {

		// Random
		case '2':
			$album = wppa_opt( 'potd_album' );
			if ( $album == 'topten' ) {
				$images = wppa_get_widgetphotos( $album );
				if ( count( $images ) > 1 ) {	// Select a random first from the current selection
					$idx = rand( 0, count( $images ) - 1 );
					$id = $images[$idx]['id'];
				}
			}
			elseif ( $album != '' ) {
				$images = wppa_get_widgetphotos( $album, "ORDER BY RAND() LIMIT 0,1" );
				$id = $images[0]['id'];
			}
			break;

		// Last upload
		case '3':
			$album = wppa_opt( 'potd_album' );
			if ( $album == 'topten' ) {
				$images = wppa_get_widgetphotos( $album );
				if ( $images ) {

					// find last uploaded image in the $images pool
					$temp = 0;
					foreach( $images as $img ) {
						if ( $img['timestamp'] > $temp ) {
							$temp = $img['timestamp'];
							$image = $img;
						}
					}
					$id = $image['id'];
				}
			}
			elseif ( $album != '' ) {
				$images = wppa_get_widgetphotos( $album, "ORDER BY timestamp DESC LIMIT 0,1" );
				$id = $images[0]['id'];
			}
			break;

		// Change every
		case '4':
			$album = wppa_opt( 'potd_album' );
			if ( $album != '' ) {
				$per = wppa_opt( 'potd_period' );
				$photos = wppa_get_widgetphotos( $album, " LIMIT 366" );
				if ( $per == '0' ) {
					if ( $photos ) {
						$id = $photos[rand( 0, count( $photos )-1 )]['id'];
					}
				}
				elseif ( $per == 'day-of-week' ) {
					$offset = strval( intval( wppa_get_option( 'wppa_potd_offset', '0' ) ) % 7 );
					wppa_update_option( 'wppa_potd_offset', $offset );
					if ( $photos ) {
						$d = date_i18n( "w" );
						$d -= wppa_get_option( 'wppa_potd_offset', '0' );
						while ( $d < '1' ) $d += '7';
						$seqno = $d;
						foreach ( $photos as $img ) {
							if ( $img['p_order'] == $d ) $id = $img['id'];
						}
					}
				}
				elseif ( $per == 'day-of-month' ) {
					$offset = strval( intval( wppa_get_option( 'wppa_potd_offset', '0' ) ) % 31 );
					wppa_update_option( 'wppa_potd_offset', $offset );
					if ( $photos ) {
						$d = strval(intval(date_i18n( "d" )));
						$d -= wppa_get_option( 'wppa_potd_offset', '0' );
						while ( $d < '1' ) $d += '31';
						$seqno = $d;
						foreach ( $photos as $img ) {
							if ( $img['p_order'] == $d ) $id = $img['id'];
						}
					}
				}
				elseif ( $per == 'day-of-year' ) {
					$offset = strval( intval( wppa_get_option( 'wppa_potd_offset', '0' ) ) % 366 );
					wppa_update_option( 'wppa_potd_offset', $offset );
					if ( $photos ) {
						$d = strval(intval(date_i18n( "z" )));
						$d -= wppa_get_option( 'wppa_potd_offset', '0' );
						while ( $d < '0' ) $d += '366';
						$seqno = $d;
						foreach ( $photos as $img ) {
							if ( $img['p_order'] == $d ) $id = $img['id'];
						}
					}
				}
				elseif ( $per == 'week' ) {
					$offset = strval( intval( wppa_get_option( 'wppa_potd_offset', '0' ) ) % 53 );
					wppa_update_option( 'wppa_potd_offset', $offset );
					if ( $photos ) {
						$w = strval(intval(date_i18n( "W" )));
						$seqno = $w;
						foreach ( $photos as $img ) {
							if ( $img['p_order'] == $w ) $id = $img['id'];
						}
					}
				}
				else {
					$u = wppa_local_date( "U" ); // Seconds since 1-1-1970, local
					$u /= 3600;		//  hours since
					$u = floor( $u );
					$u /= $per;
					$u = floor( $u );

					// Cached value?
					$cache = wppa_get_option( 'wppa_potd_id_cache', false );
					if ( $cache ) {
						if ( isset( $cache[$u] ) ) {
							$id = $cache[$u];
							if ( ! wppa_photo_exists( $id ) ) {
								$id = 0;
							}
						}
					}

					// Not found in cache
					if ( ! $id ) {
						// Find the right photo out of the photos found by wppa_get_widgetphotos(),
						// based on the Change every { any timeperiod } algorithm.
						if ( $photos ) {
							$p = count( $photos );
							$idn = fmod( $u, $p );

							// If from topten,...
							if ( $album == 'topten' ) {

								// Do a re-read of the same to order by rand, reproduceable
								// This can not be done by wppa_get_widgetphotos(),
								// it does already ORDER BY for the top selection criterium.
								// So we save the ids, and do a SELECT WHERE id IN ( array of found ids ) ORDER BY RAND( seed )
								$ids = array();
								foreach( $photos as $photo ) {
									$ids[] = $photo['id'];
								}
								$photos = $wpdb->get_results( 	"SELECT id, p_order " .
																"FROM $wpdb->wppa_photos " .
																"WHERE id IN (" . implode( ',', $ids ) . ") " .
																"ORDER BY RAND(".$idn.")",
																ARRAY_A );
							}

							// Not from topten, use wppa_get_widgetphotos() to get a reproduceable random sequence
							else {
								$photos = wppa_get_widgetphotos( $album, " ORDER BY RAND($idn) LIMIT 366" );
							}

							// Image found
							$id = $photos[$idn]['id'];
						}

						wppa_update_option( 'wppa_potd_id_cache', array( $u => $id ) );
					}
				}
			}
			break;

		// Fixed photo
		default:
			$id = wppa_opt( 'potd_photo' );
			break;
	}

	if ( $id ) {
		$photo_data = wppa_cache_photo( $id );
		wppa_log_potd( $id );
	}
	else {
		$photo_data = false;
	}


	if ( $details ) {
		$result = ['id' => $id, 'potddata' => $photo_data, 'seqno' => $seqno, 'offset' => $offset];
	}
	else {
		$result = $photo_data;
	}
	return $result;
}

// Get widget checkbox html
function wppa_widget_checkbox( $class, $item, $value, $label, $subtext = '', $disabled = false, $onchange = '' ) {

	$result = '
	<p style="clear:both">
		<input
			id="' . $class->get_field_id( $item ) . '"
			name="' . $class->get_field_name( $item ) . '"
			type="checkbox"' .
			wppa_checked( $value ) .
			( $disabled ? ' disabled' : '' ) .
			( $onchange ? ' onchange="' . esc_attr( $onchange ) . '"' : '' ) .
		' />&nbsp;
		<label
			for="' . $class->get_field_id( $item ) . '"
			>' .
			$label . '
		</label>';
		if ( $subtext ) {
			$result .= '<small>' . strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] ) . '</small>';
		}
	$result .= '
	</p>';

	wppa_echo( $result );
}

// Widget input html
//
// Typical usage:
//
// wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );
//
function wppa_widget_input( $class, $item, $value, $label, $subtext = '' ) {

	$result =
	'<p style="clear:both">' .
		'<label' .
			' for="' . $class->get_field_id( $item ) . '"' .
			' >' .
			$label . ':' .
		'</label>' .
		'<input' .
			' class="widefat"' .
			' id="' . $class->get_field_id( $item ) . '"' .
			' name="' . $class->get_field_name( $item ) . '"' .
			' type="text"' .
			' value="' . esc_attr( $value ) . '"' .
		'/>';
		if ( $subtext ) {
			$result .= '<small>' . strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] ) . '</small>';
		}
	$result .= '
	</p>';

	wppa_echo( $result );
}

// Widget input text area
function wppa_widget_textarea( $class, $item, $value, $label ) {

	$result =
	'<p>' .
		'<label' .
			' for="' . $class->get_field_id( $item ) . '"' .
			' >' .
			$label . ':' .
		'</label>' .
		'<textarea' .
			' class="widefat"' .
			' rows="16"' .
			' id="' . $class->get_field_id( 'text' ) . '"' .
			' name="' . $class->get_field_name( 'text' ) . '"' .
			' >' .
			$value .
		'</textarea>' .
	'</p>';

	wppa_echo( $result );
}

// Widget input number_format
function wppa_widget_number( $class, $item, $value, $label, $min, $max, $subtext = '', $float = false ) {

	$_50 = wppa_is_ie() ? '60px;': '60%';

	$result = '
	<p' . ( $float ? ' style="width:50%;float:left"' : '' ) . '>
		<label
			for="' . $class->get_field_id( $item ) . '">' .
			$label . ':
		</label>
		<br>
		<input
			id="' . $class->get_field_id( $item ) . '"
			name="' . $class->get_field_name( $item ) . '"
			style="' . ( $float ? 'width:' . $_50 . ';' : '' ) . '"
			type="number"
			min="' . $min . '"
			max="' . $max . '"
			value="' . esc_attr( $value ) . '"
			onchange="' . esc_attr(
				'if(jQuery(this).val()<' . $min . '||jQuery(this).val()>' . $max . '){
					alert(\'' . esc_js( sprintf( __( 'Please enter a number >= %1s and <= %2s', 'wp-photo-album-plus' ),$min, $max ) ) . '\');
					jQuery(this).val(\'' . $max . '\');return false;}').'"
		/>';
		if ( $subtext ) {
			$result .= '<small>' . ( $cls ? '' : '<br>' ) . strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] ) . '</small>';
		}
	$result .= '
	</p>';

	wppa_echo( $result );
}

// Widget selection box
function wppa_widget_selection( $class, $item, $value, $label, $options, $values, $disabled = array(), $cls = 'widefat', $subtext = '' ) {

	$result = '
	<p>
		<label
			for="' . $class->get_field_id( $item ) . '">' .
			$label . ':
		</label>' .
		( $cls ? '' : '<br>' ) . '
		<select
			class="' . $cls . '"
			id="' . $class->get_field_id( $item ) . '"
			name="' . $class->get_field_name( $item ) . '">';

			foreach( array_keys( $options ) as $key ) {
				$result .= '
				<option
					value="' . $values[$key] . '"' .
					( $value == $values[$key] ? ' selected' : '' ) .
					( isset( $disabled[$key] ) && $disabled[$key] ? ' disabled' : '' ) .
					'>' .
					__( $options[$key] ) . '
				</option>';
			}

		$result .= '</select>';
		if ( $subtext ) {
			$result .= '<small>' . ( $cls ? '' : '<br>' ) . strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] ) . '</small>';
		}
	$result .= '
	</p>';

	wppa_echo( $result );
}

// Widget selection box frame
function wppa_widget_selection_frame( $class, $item, $body, $label, $multi = false, $subtext = '' ) {

	$result =
	'<p>' .
		'<label' .
			' for="' . $class->get_field_id( $item ) . '"' .
			' >' .
			$label . ':' .
		'</label>' .
		'<select' .
			' class="widefat"' .
			' id="' . $class->get_field_id( $item ) . '"' .
			' name="' . $class->get_field_name( $item ) . ( $multi ? '[]' : '' ) . '"' .
			( $multi ? ' multiple' : '' ) .
			' >' .
			$body .
		'</select>';
		if ( $subtext ) {
			$result .= '<small>' . strip_tags( wp_check_invalid_utf8( $subtext ), ["<br>", "<a>", "<i>", "<b>"] ) . '</small>';
		}
	$result .= '
	</p>';

	wppa_echo( $result );
}

// Get checked html
function wppa_checked( $arg ) {

	// Backward compat yes/no selectionbox
	if ( $arg == 'no' ) {
		$result = '';
	}

	// '0'
	elseif ( $arg == '0' ) {
		$result = '';
	}

	// 'yes' or 'on'
	elseif ( $arg ) {
		$result = ' checked';
	}

	// ''
	else {
		$result = '';
	}

	return $result;
}

// Log photo of the day
function wppa_log_potd( $id ) {

	// Feature enabled?
	if ( wppa_switch( 'potd_log' ) ) {

		// Get existig history
		$his = wppa_get_option( 'wppa_potd_log_data', array() );

		// If history exists and last one is current id, quit
		if ( ! empty( $his ) ) {
			if ( $his[0]['id'] == $id ) {
				return;
			}
		}

		// Compose current entry
		$now = array( 'id' => $id, 'tm' => time() );

		// Log current potd at the beginning of the existing array
		$cnt = array_unshift( $his, $now );

		// Truncate array if larger than max
		$max = wppa_opt( 'potd_log_max' );
		if ( $cnt > $max ) {
			$his = array_slice( $his, 0, $max );
		}

		// Save result
		wppa_update_option( 'wppa_potd_log_data', $his );
	}
}

// Timer
function wppa_widget_timer( $key = '', $title = '', $cached = false ) {
static $queries;
static $time;

	switch( $key ) {
		case 'init':
			$queries = get_num_queries();
			$time = microtime( true );
			break;

		case 'show':
			$queries = get_num_queries() - $queries;
			$time = microtime( true ) - $time;
			$result = "\n" .
				'<!-- End ' . $title . ' ' .
				sprintf( '%d queries in %3.1f ms. at %s',
					$queries,
					$time * 1000,
					wppa_local_date( wppa_get_option( 'time_format' ) ) ) .
				( $cached ? ' (cached) ' : ' ' ) .
				'-->';
				wppa_log( 'tim', trim( $result, "\n<>" ) );
			return $result;
			break;

		default:
			wppa_log( 'err', 'Unimplemented key in wppa_widget_timer (' . $key . ')' );
			break;
	}
}

// Cache this widget?
function wppa_cache_widget( $instance_cache ) {
global $wppa;

	if ( is_admin() ) return false;

	switch( wppa_opt( 'cache_overrule' ) ) {
		case 'always':
			$wppa['cache'] = true;
			return true;
			break;
		case 'never':
			return false;
			break;
		default:
			$wppa['cache'] = $instance_cache;
			return $instance_cache;
	}
}
