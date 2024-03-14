<?php
function qem_show_calendar_esc( $atts ) {
	global $qem_calendars;
	if ( ! isset( $qem_calendars ) ) {
		$qem_calendars = 0;
	}
	$c        = ( ( isset( $_REQUEST['qemcalendar'] ) ) ? (int) $_REQUEST['qemcalendar'] : $qem_calendars ++ );
	$cal      = qem_get_stored_calendar();
	$style    = qem_get_stored_style();
	$category = '';
	$defaults = array(
		'category'         => '',
		'navigation'       => '',
		'month'            => '',
		'year'             => '',
		'links'            => 'on',
		'categorykeyabove' => '',
		'categorykeybelow' => '',
		'usecategory'      => '',
		'smallicon'        => 'trim',
		'widget'           => '',
		'header'           => 'h2',
		'fullpopup'        => '',
		'calendar'         => 'checked',
		'thisisapopup'     => false
	);
	$atts     = (array) $atts;
	$natts    = array_merge( $defaults, $atts );
	extract( shortcode_atts( $natts, $atts ) );
	global $post;
	global $_GET;
	if ( ! $widget ) {
		$header = $cal['header'];
	}
	if ( $cal['hidenavigation'] ) {
		$navigation = 'off';
	}
	$reload = ( $cal['jumpto'] ? '#qem_calreload' : '' );
	if ( isset( $_REQUEST['category'] ) ) {
		$category = sanitize_text_field( $_REQUEST['category'] );
	}
	$args = array(
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'event_date',
		'order'          => 'asc',
		'posts_per_page' => - 1,
		'category'       => '',
		'links'          => 'on'
	);

	$catarry      = explode( ",", $category );
	$leftnavicon  = '';
	$rightnavicon = '';
	if ( $cal['navicon'] == 'arrows' ) {
		$leftnavicon  = '&#9668; ';
		$rightnavicon = ' &#9658;';
	}
	if ( $cal['navicon'] == 'unicodes' ) {
		$leftnavicon  = $cal['leftunicode'] . ' ';
		$rightnavicon = ' ' . $cal['rightunicode'];
	}
	$monthnames = array();
	$monthstamp = 0;
	for ( $i = 0; $i <= 12; $i ++ ) {
		$monthnames[] = date_i18n( 'F', $monthstamp );
		$monthstamp   = strtotime( '+1 month', $monthstamp );
	}
	if ( $cal['startday'] == 'monday' ) {
		$timestamp = strtotime( 'next sunday' );
	}
	if ( $cal['startday'] == 'sunday' ) {
		$timestamp = strtotime( 'next saturday' );
	}
	$days = array();
	for ( $i = 0; $i <= 7; $i ++ ) {
		$days[]    = date_i18n( 'D', $timestamp );
		$timestamp = strtotime( '+1 day', $timestamp );
	}

	if ( isset( $_REQUEST["qemmonth"] ) ) {
		$month = sanitize_text_field($_REQUEST["qemmonth"]);
	} else {
		if ( $month ) {
			if ( ! is_numeric( $month ) ) {
				$month = strtotime( $month );
				if ( false === $month ) {
					$month = date_i18n( "n" );
				}
				$month = date( 'n', $month );
			}
		} else {
			$month = date_i18n( "n" );
		}
	}
	if ( isset( $_REQUEST["qemyear"] ) ) {
		$year = sanitize_text_field($_REQUEST["qemyear"]);
	} else {
		if ( $year ) {
			if ( ! is_numeric( $year ) ) {
				$year = strtotime( $year );
				if ( false === $year ) {
					$year = date_i18n( "Y" );
				}
				$year = date( 'Y', $year );
			}
		} else {
			$year = date_i18n( "Y" );
		}
	}
	$currentmonth = filter_var( $month, FILTER_SANITIZE_NUMBER_INT );
	$currentyear  = filter_var( $year, FILTER_SANITIZE_NUMBER_INT );

	/*
		Build attribute array into json object to use later
	*/


	$calendar_script_data = "data-qem_calendar_atts = \"" . esc_html( wp_json_encode( $atts ) ) . "\" ";
	$calendar_script_data .= "data-qem_month = \"{$currentmonth}\" ";
	$calendar_script_data .= "	data-qem_year = \"{$currentyear}\" ";
	$calendar_script_data .= "	data-qem_category = \"{$category}\" ";


	$calendar = '<div class="qem_calendar" id="qem_calendar_' . $c . '" ' . $calendar_script_data . '><a id="qem_calreload"></a>';


	$p_year  = $currentyear;
	$n_year  = $currentyear;
	$p_month = $currentmonth - 1;
	$n_month = $currentmonth + 1;
	if ( $p_month == 0 ) {
		$p_month = 12;
		$p_year  = $currentyear - 1;
	}
	if ( $n_month == 13 ) {
		$n_month = 1;
		$n_year  = $currentyear + 1;
	};

	$atts['calendar'] = true;
	if ( qem_get_element( $cal, 'fullpopup', false ) ) {
		$atts['fullpopup'] = 'checked';
	}

	$qem_dates    = array();
	$eventdate    = array();
	$eventenddate = array();
	$eventtitle   = array();
	$eventsummary = array();
	$eventlinks   = array();
	$eventslug    = array();
	$eventimage   = array();
	$eventdesc    = array();
	$eventnumbers = array();
	$eventx       = false;

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( in_category( $catarry ) || ! $category || 'undefined' === $category ) {
				$startdate = get_post_meta( $post->ID, 'event_date', true );
				$enddate   = get_post_meta( $post->ID, 'event_end_date', true );
				if ( ! $startdate || ! is_numeric( $startdate ) ) {
					$startdate = time();
				}
				$startmonth = date( "m", (int) $startdate );
				$startyear  = date( "Y", (int) $startdate );
				$match      = false;

				// Do matches for the start of an event
				if ( $startyear == $currentyear && $startmonth == $currentmonth ) {
					$match = true;
				}

				// Do matches for end of event (If it applies)
				if ( ! $match && $enddate ) {
					$endmonth = date( "m", $enddate );
					$endyear  = date( "Y", $enddate );

					if ( $endyear == $currentyear && $endmonth == $currentmonth ) {
						$match = true;
					}
				}

				if ( $match ) {
					// $startdate = strtotime(date("d M Y", $startdate));
					$enddate    = get_post_meta( $post->ID, 'event_end_date', true );
					$image      = get_post_meta( $post->ID, 'event_image', true );
					$desc       = get_post_meta( $post->ID, 'event_desc', true );
					$link       = get_permalink();
					$whoscoming = get_option( 'qem_messages_' . $post->ID );
					$attendees  = $whoscoming ? true : false;
					$cat        = get_the_category();
					if ( ! $cat ) {
						$cat = array();
					}
					$slug = ( isset( $cat[0] ) ) ? $cat[0]->slug : '';
					if ( $cal['eventlink'] == 'linkpopup' ) {
						$eventx = get_event_popup( $atts );
					}
					$title = get_the_title();
					if ( qem_get_element( $cal, 'showmultiple', false ) ) {
						do {
							array_push( $eventdate, $startdate );
							array_push( $eventtitle, $title );
							array_push( $eventslug, $slug );
							array_push( $eventsummary, $eventx );
							array_push( $eventlinks, $link );
							array_push( $eventnumbers, $attendees );
							$startdate = $startdate + ( 24 * 60 * 60 );
						} while ( $startdate <= $enddate );
					} else {
						array_push( $eventdate, $startdate );
						array_push( $eventtitle, $title );
						array_push( $eventslug, $slug );
						array_push( $eventsummary, $eventx );
						array_push( $eventlinks, $link );
						array_push( $eventnumbers, $attendees );
						array_push( $eventimage, $image );
						array_push( $eventdesc, $desc );
					}
				}
			}
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	if ( $cal['connect'] ) {
		$calendar .= '<p><a href="' . $cal['eventlist_url'] . '">' . $cal['eventlist_text'] . '</a></p>';
	}

	$actual_link = qem_actual_link();
	$parts       = explode( "&", $actual_link );
	$actual_link = $parts['0'];
	$link        = ( strpos( $actual_link, '?' ) ? '&' : '?' );
	$catkey      = qem_category_key( $cal, $style, 'calendar' );

	if ( qem_get_element( $cal, 'showkeyabove', false ) &&
	     ! $widget ||
	     qem_get_element( $atts, 'categorykeyabove', false ) == 'checked' ) {
		$calendar .= $catkey;
	}
	if ( qem_get_element( $cal, 'showmonthsabove', false ) ) {
		$calendar .= qem_calendar_months( $cal );
	}

	// Build Category Information

	$category = qem_get_element( $atts, 'category' );
	if ( isset( $_GET['category'] ) ) {
		$category = sanitize_text_field( $_GET['category'] );
	}

	$catlabel = str_replace( ',', ', ', $category );

	if ( $category && $display['showcategory'] ) {
		$calendar .= '<h2>' . $display['showcategorycaption'] . ' ' . $catlabel . '</h2>';
	}

	$calendar .= '<div id="qem-calendar">
    <table cellspacing="' . $cal['cellspacing'] . '" cellpadding="0">
    <tr class="caltop">
    <td>';
	if ( $navigation != 'off' ) {
		$calendar .= '<a class="calnav" href="' . $actual_link . $link . 'qemmonth=' . $p_month . '&amp;qemyear=' . $p_year . $reload . '">' . $leftnavicon . $cal['prevmonth'] . '</a>';
	}

	$headerorder = $cal['headerorder'] == 'ym' ? $currentyear . ' ' . $monthnames[ $currentmonth - 1 ] : $monthnames[ $currentmonth - 1 ] . ' ' . $currentyear;

	$calendar .= '</td>
    <td class="calmonth"><' . $header . '>' . $headerorder . '</' . $header . '></td>
    <td>';
	if ( $navigation != 'off' ) {
		$calendar .= '<a class="calnav" href="' . $actual_link . $link . 'qemmonth=' . $n_month . '&amp;qemyear=' . $n_year . $reload . '">' . $cal['nextmonth'] .
		             $rightnavicon . '</a>';
	}
	$calendar .= '</td>
    </tr>
    </table>
    <table>
    <tr>' . "\r\n";
	for ( $i = 1; $i <= 7; $i ++ ) {
		$calendar .= '<td class="calday">' . $days[ $i ] . '</td>';
	}
	$calendar  .= '</tr>' . "\r\n";
	$timestamp = mktime( 0, 0, 0, $currentmonth, 1, $currentyear );
	$maxday    = date_i18n( "t", $timestamp );
	$thismonth = getdate( $timestamp );
	if ( $cal['startday'] == 'monday' ) {
		$startday = $thismonth['wday'] - 1;
		if ( $startday == '-1' ) {
			$startday = '6';
		}
	} else {
		$startday = $thismonth['wday'];
	}
	$firstday = '';
	$henry    = $startday - 1;
	$startday = (int) $startday;

	for ( $i = 0; $i < ( $maxday + $startday ); $i ++ ) {
		$oldday   = '';
		$blankday = ( $i < $startday ? ' class="blankday" ' : '' );
		$firstday = ( $i == $startday - 1 ? ' class="firstday" ' : '' );
		$xxx      = mktime( 0, 0, 0, $currentmonth, $i - $startday + 1, $currentyear );
		if ( date_i18n( "d" ) > $i - $startday + 1 && $currentmonth <= date_i18n( "n" ) && $currentyear == date_i18n( "Y" ) ) {
			$oldday = 'oldday';
		}
		if ( $currentmonth < date_i18n( "n" ) && $currentyear == date_i18n( "Y" ) ) {
			$oldday = 'oldday';
		}
		if ( $currentyear < date_i18n( "Y" ) ) {
			$oldday = 'oldday';
		}
		if ( ( $cal['archive'] && $oldday ) || ! $oldday ) {
			$show = 'checked';
		} else {
			$show = '';
		}
		$tdstart   = '<td class="day ' . $oldday . ' ' . $firstday . '"><' . $header . '>' . ( $i - $startday + 1 ) . '</' . $header . '><br>';
		$tdcontent = $eventcontent = '';
		$flag      = $cal['attendeeflagcontent'] ? $cal['attendeeflagcontent'] : '&#x25cf;';
		foreach ( $eventdate as $key => $day ) {
			$m   = date( 'm', $day );
			$d   = date( 'd', $day );
			$y   = date( 'Y', $day );
			$zzz = mktime( 0, 0, 0, $m, $d, $y );
			if ( $xxx == $zzz && $show ) {
				$tdstart      = '<td class="eventday ' . $oldday . ' ' . $firstday . '"><' . $header . '>' . ( $i - $startday + 1 ) . '</' . $header . '>';
				$img          = ( qem_get_element( $eventimage, $key, false ) &&
				                  qem_get_element( $cal, 'eventimage', false ) &&
				                  ! $widget ? '<br><img src="' . qem_get_element( $eventimage, $key, '' ) . '">' : '' );
				$tooltip      = '';
				$tooltipclass = '';
				if ( qem_get_element( $cal, 'usetooltip', false ) ) {
					$desc         = ( $eventdesc[ $key ] ? ' - ' . $eventdesc[ $key ] : '' );
					$tooltip      = 'data-tooltip="' . $eventtitle[ $key ] . $desc . '"';
					$tooltipclass = ( ( $i % 7 ) == 6 ? ' tooltip-left ' : '' );
					if ( $widget ) {
						$tooltipclass = ( ( $i % 7 ) > 2 ? ' tooltip-left ' : '' );
					}
				}
				$length  = $cal['eventlength'];
				$short   = $length - 3;
				$numbers = '';
				if ( $cal['attendeeflag'] ) {
					$numbers = ( $eventnumbers[ $key ] ) ? $flag . '&nbsp;' : '';
				}
				$tagless_title = strip_tags( $eventtitle[ $key ] );
				$trim_title    = ( strlen( $tagless_title ) > $length ) ? mb_substr( $tagless_title, 0, $short, "utf-8" ) . '...' : $tagless_title;
				// put back tags - this is for translation plugins like TranslatePress
				$trim = str_replace( $tagless_title, $trim_title, $eventtitle[ $key ] );
				if ( $cal['eventlink'] == 'linkpopup' ) {
					$tdcontent .= '<a ' . $tooltip . ' class="qem_linkpopup event ' . $eventslug[ $key ] . $tooltipclass . '" data-xlightbox="' . esc_html( $eventsummary[ $key ] ) . '"><div class="qemtrim"><span>' . $numbers . $trim . '</span>' . $img . '</div></a>';
				} else {
					$eventcontent = '<a ' . $tooltip . ' class="' . $eventslug[ $key ] . $tooltipclass . '" href="' . $eventlinks[ $key ] . '"><div class="qemtrim"><span>' . $numbers . $trim . '</span>' . $img . '</div></a>';
					$tdcontent    .= preg_replace( "/\r|\n/", "", $eventcontent );
				}
			}
		}
		$tdbuilt = $tdstart . $tdcontent . '</td>';
		if ( ( $i % 7 ) == 0 ) {
			$calendar .= "<tr>\r\t";
		}
		if ( $i < $startday ) {
			$calendar .= '<td' . $firstday . $blankday . '></td>';
		} else {
			$calendar .= $tdbuilt;
		}
		if ( ( $i % 7 ) == 6 ) {
			$calendar .= "</tr>" . "\r\n";
		};
	}
	$calendar .= "</table></div>";
	if ( qem_get_element( $cal, 'showkeybelow', false ) && ! $widget || qem_get_element( $atts, 'categorykeybelow', false ) == 'checked' ) {
		$calendar .= $catkey;
	}
	if ( qem_get_element( $cal, 'showmonthsbelow', false ) ) {
		$calendar .= qem_calendar_months( $cal );
	}
	$eventdate = remove_empty( $eventdate );

	//  escaped function - always return safe for output
	return qem_wp_kses_post( $calendar ) . "</div>";
}