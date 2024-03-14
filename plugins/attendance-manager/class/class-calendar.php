<?php
/** 
 *	Calendar 
 */

class ATTMGR_Calendar {
	/**
	 *	Initialize
	 */
	public static function init(){
		add_shortcode( ATTMGR::PLUGIN_ID.'_calendar', array( 'ATTMGR_Calendar', 'show_calendar' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_date_format', array( 'ATTMGR_Calendar', 'date_format' ), 10, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_month_format', array( 'ATTMGR_Calendar', 'month_format' ), 10, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_time_format', array( 'ATTMGR_Calendar', 'time_format' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_time_format_editor', array( 'ATTMGR_Calendar', 'time_format_editor' ) );
	}

	/**
	 *	Get DOW
	 */
	public static function dow( $weekday ) {
		$dow = array(
			__( 'Sun', ATTMGR::TEXTDOMAIN ),
			__( 'Mon', ATTMGR::TEXTDOMAIN ),
			__( 'Tue', ATTMGR::TEXTDOMAIN ),
			__( 'Wed', ATTMGR::TEXTDOMAIN ),
			__( 'Thu', ATTMGR::TEXTDOMAIN ),
			__( 'Fri', ATTMGR::TEXTDOMAIN ),
			__( 'Sat', ATTMGR::TEXTDOMAIN )
		);
		if ( $weekday >= 0 && $weekday <= 6 ) {
			return $dow[ $weekday ];
		}
		return false;
	}

	/**
	 *	Get DOW name
	 */
	public static function dow_lower( $weekday ) {
		return strtolower( date( 'l', mktime( 0, 0, 0, 6, 1 + $weekday, 2014 ) ) );
	}
	/**
	 *	Set calendar data
	 */
	public static function set( $y, $m, $start, $extend = array() ) {

		$dow_1st = date( 'w', mktime( 0, 0, 0, $m, 1, $y ) );	// 当月初日の曜日を取得
		$eom = date( 'd', mktime( 0, 0, 0, $m + 1, 0, $y ) );	// 当月末日を取得
		$week = 0;		// 0=第1週


		// End of previous month
		if ( $dow_1st != $start ) {
			for ( $w = $start; $w < $dow_1st; $w++ ) {
				$cal[ $week ][ $w ] = array(
					'd' => "",
					'status' => 0
				);
			}
		}
		else {
			$w = $dow_1st;
		}

		// This month
		for ( $d = 1; $d <= $eom; $d++ ) {	
			$cal[ $week ][ $w ] = array(
				'd' => $d,
				'status' => 1,
				'y' => $y,
				'm' => $m,
			);
			if ( ! empty( $extend ) )
				$cal[ $week ][ $w ] = array_merge( $cal[ $week ][ $w ], $extend[ $d ] );
				
			$w = ( 6 == $w ) ? 0 : $w + 1;			// Next DOW
			if ( $w == $start ) {
				$week++;							// Next week
			}
		}

		// Start of next month
		while ( 1 ) {
			$cal[ $week ][ $w ] = array(
				'd' => '',
				'status' => 0,
			);
			$w = ( 6 == $w ) ? 0 : $w + 1;
			if ( $w == $start ) {
				break;
			}
		}

		// Last line
		/*
		if ( $week < 5 ) {
			$week++;
			for ( $w = 0; 7 > $w; $w++ ) {
				$cal[ $week ][ $w ] = array(
					'd'   => '',
					'status' => 0,
				);
			}
		}
		*/
		return $cal;
	}

	/**
	 *	Set calendar data (weekly)
	 */
	public static function set_weekly( $date, $extend = array() ) {

		list( $year, $month, $day ) = explode( '-', $date );

		$w = date( 'w', mktime( 0, 0, 0, $month, $day, $year ) );	// 開始日の曜日を取得
		$cal = array();
		for ( $j = 0; 7 > $j; $j++ ) {
			list( $y, $m, $d ) = explode( '-', date( 'Y-m-d', mktime( 0, 0, 0, $month, $day + $j, $year ) ) );

			$cal[ $j ] = array(
				'd' => $d,
				'status' => 1,
				'y' => $y,
				'm' => $m,
				'w' => $w,
			);
			if ( ! empty( $extend ) ) {
				$cal[ $j ] = array_merge( $cal[ $j ], $extend[ $d ] );
			}
			$w = ( 6 == $w ) ? 0 : $w + 1;
		}
		return $cal;
	}

	/**
	 *	HTML code of calendar
	 */
	public static function html( $atts, $calendar, $function = "" ) {
		extract(
			shortcode_atts(
				array(
					'start' => 0,
					'today' => '',
				),
				$atts
			)
		);
		if ( empty( $today ) ) {
			$today = date( 'Y-m-d', current_time( 'timestamp' ) );
		}
		ob_start();
		printf( '<table class="%s_calendar">', ATTMGR::PLUGIN_ID );
		// DOW
		echo "<thead><tr>\n";
		$j = $start;
		while ( 1 ) {
			printf( '<th class="%s">%s</th>'."\n", self::dow_lower( $j ), self::dow( $j ) );
			$j = ( $j == 6 ) ? 0 : $j + 1;
			if ( $j == $start ) {
				break;
			}
		}
		echo "</tr></thead>\n";
		// DATE
		echo "<tbody>\n";
		for ( $i = 0; isset( $calendar[ $i ] ); $i++ ) {
			echo "<tr>\n";
			$j = $start;
			while ( 1 ) {
				extract( $calendar[ $i ][ $j ] );
				/*
				[d] => 1
				[status] => 1
				[y] => 2014
				[m] => 04
				*/

				$dval = "";
				$tdclass = array( self::dow_lower( $j ) );
				if ( $status  ) {
					$tdclass[] = 'inactive';
					$date = sprintf( '%s-%s-%02d', $y, $m, $d );
					if ( 0 == strcmp( $date, $today ) ) {
						$tdclass[] = 'today';
					}
					$fret = "";
					if ( ! empty( $function ) ) {
						$args['data'] = $calendar[ $i ][ $j ]; 
						$fret = call_user_func( $function, $args );
					} else {
						$fret = $d;
					}
					$dval = $fret;
				}
				printf( '<td class="%s">%s</td>'."\n", implode( ' ', $tdclass ), $dval );
				$j = ( 6 == $j ) ? 0 : $j + 1;
				if ( $j == $start ) {
					break;
				}
			}
			echo "</tr>\n";
		}
		echo "</tbody>\n";
		echo ( '</table>' );
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	HTML code of calendar (weekly)
	 */
	public static function html_weekly( $atts, $calendar, $function = "" ) {
		extract(
			shortcode_atts(
				array(
					'today' => '',
				),
				$atts
			)
		);
		if ( empty( $today ) ) {
			$today = date( 'Y-m-d', current_time( 'timestamp' ) );
		}
		ob_start();
		printf( '<table class="%1$s_calendar %1$_calendar_weekly">', ATTMGR::PLUGIN_ID );
		// DOW
		echo "<thead><tr>\n";
		$j = $start = $calendar[0]['w'];
		while ( 1 ) {
			printf( '<th class="%s">%s</th>'."\n",  self::dow_lower( $j ), self::dow( $j ) );
			$j = ( 6 == $j ) ? 0 : $j + 1;
			if ( $j == $start ) {
				break;
			}
		}
		echo "</tr></thead>\n";
		// DATE
		echo "<tbody>\n";
		echo "<tr>\n";
		foreach ( $calendar as $cal ) {
			extract( $cal );
			/*
			[d] => 1
			[status] => 1
			[y] => 2014
			[m] => 04
			[w] => 0
			*/

			$tdclass = array( self::dow_lower( $w ) );
			$dval = "";

			$date = sprintf( '%s-%s-%02d', $y, $m, $d );
			if ( 0 == strcmp( $date, $today ) ) {
				$tdclass[] = 'today';
			}
			$fret = "";
			if ( ! empty( $function ) ) {
				$args['data'] = $cal; 
				$fret = call_user_func( $function, $args );
			} else {
				$fret = $d;
				$fret .= sprintf( '<div class="wmonth">%s-%s</div>', $y, $m );
			}
			$dval = $fret;

			printf( '<td class="%s">%s</td>'."\n", implode( ' ', $tdclass ), $dval );
		}
		echo "</tr>\n";
		echo "</tbody>\n";
		echo ( '</table>' );
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	HTML code of weekly guide
	 */
	public static function guide_weekly( $atts, $calendar ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'current' => '',
				),
				$atts
			)
		);
		$currenttime = current_time( 'timestamp' );
		$now_time = date( 'H:i', $currenttime );
		$starttime = $attmgr->option['general']['starttime'];
		$endtime = ATTMGR_Form::time_calc( $attmgr->option['general']['endtime'], 0, false );
		$midnight = ( $starttime > $endtime && $now_time < $endtime ) ? true : false ;

		$today = date( 'Y-m-d', current_time( 'timestamp' ) );
		$format = <<<EOD
<table class="%CLASS%">
<tr>
%BODY%
</tr>
</table>
EOD;
		ob_start();
		$body = '';
		foreach ( $calendar as $cal ) {
			extract( $cal );
			/*
			[d] => 1
			[status] => 1
			[y] => 2014
			[m] => 04
			[w] => 0
			*/
			$date = '';
			$date = sprintf( '<span class="date">%s</span><span class="dow">(%s)</span>', 
				apply_filters( 'attmgr_date_format', $date, mktime( 0, 0, 0, $m, $d, $y ) ),
				ATTMGR_Calendar::dow( $w )
			);
			$qs = array();
			if ( ! empty( $attmgr->page['qs'] ) ) {
				$qs = $attmgr->page['qs'];
			}
			$qs['date'] = sprintf( '%s-%s-%02d', $y, $m, $d );
			if ( $midnight && $attmgr->page['begin_date'] == $qs['date'] ) {
				unset( $qs['date'] );
				$query_string = './';
			} else {
				$query_string = add_query_arg( $qs, '' );
			}
			$link = sprintf( '<a href="%s">%s</a>', $query_string, $date );
			$class = array( self::dow_lower( $w ) );
			if ( $current == sprintf( '%d-%s-%02d', $y, $m, $d ) ) {
				$class[] = 'current';
			}
			$body .= sprintf( '<td class="%s">%s</td>'."\n", implode( ' ', $class ), $link );
		}
		$search = array(
			'%CLASS%',
			'%BODY%',
		);
		$replace = array(
			sprintf( '%1$s_guide_weekly', ATTMGR::PLUGIN_ID ),
			$body,
		);
		echo str_replace( $search, $replace, $format );
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	Get next month
	 */
	public static function next_month( $y, $m, $add = 1 ) {
		$new = mktime( 0, 0, 0, $m + $add, 1, $y );
		return array( date( 'Y', $new ), date( 'm', $new ) );
	}

	/**
	 *	Get previous month
	 */
	public static function prev_month( $y, $m ) {
		return self::next_month( $y, $m, -1 );
	}

	/**
	 *	Link to next month
	 */
	public static function next_month_link( $y, $m, $add = 1 ) {
		global $attmgr;

		$ym = self::next_month( $y, $m, $add );
		if ( $add > 0 ) {
			$title= '&raquo;';
		} elseif ( $add < 0 ) {
			$title = '&laquo;';
		}

		if ( 0 == $add ) {
			$link = '';
			$link = apply_filters( 'attmgr_month_format', $link, mktime( 0, 0, 0, $m, 1, $y ) );
		} else {
			$qs = array();
			if ( ! empty( $attmgr->page['qs'] ) ) {
				$qs = $attmgr->page['qs'];
			}
			$qs['month'] = vsprintf( "%s-%s", $ym );
			$query_string = add_query_arg( $qs, '' );
			$link = sprintf( '<a href="%s">%s</a>', $query_string, $title );
		}
		return $link;
	}

	/**
	 *	Link to previous month
	 */
	public static function prev_month_link( $y, $m ) {
		return self::next_month_link( $y, $m, -1 );
	}

	/**
	 *	Link to this month
	 */
	public static function this_month_link( ) {
		global $attmgr;

		$thismonth = date( 'Y-m', current_time( 'timestamp' ) );
		$qs = array();
		if ( ! empty( $attmgr->page['qs'] ) ) {
			$qs = $attmgr->page['qs'];
		}
		$qs['month'] = $thismonth;
		$query_string = add_query_arg( $qs, '' );
		$link = sprintf( '<span class="back_today"><a href="%s">&raquo; %s</a></span>', $query_string, __( 'Today', ATTMGR::TEXTDOMAIN ) );
		return $link;
	}

	/**
	 *	Get currently displayed month
	 */
	public static function current_month( $y, $m ) {
		return self::next_month_link( $y, $m, 0 );
	}

	/**
	 *	Get next week
	 */
	public static function next_week( $date, $add = 7 ) {
		list( $y, $m, $d ) = array_pad( explode( '-', $date ), 3, 0 );
		$m = intval( $m );
		$d = intval( $d );
		$y = intval( $y );
		return date( 'Y-m-d', mktime( 0, 0, 0, $m, $d + $add, $y ) );
	}

	/**
	 *	Get previous week
	 */
	public static function prev_week( $date ) {
		return self::next_week( $date, -7 );
	}

	/**
	 *	Get currently displayed week
	 */
	public static function current_week( $date ) {
		return self::next_week_link( $date, 0 );
	}

	/**
	 *	Link to next week
	 */
	public static function next_week_link( $date, $add = 7 ) {
		global $attmgr;

		$next = self::next_week( $date, $add );
		if ( $add > 0 ) {
			$title= '&raquo;';
		} elseif ( $add < 0 ) {
			$title = '&laquo;';
		}
		if ( 0 == $add ) {
			list( $y, $m, $d ) = array_pad( explode( '-', $date ), 3, 0 );
			$m = intval( $m );
			$d = intval( $d );
			$y = intval( $y );
			//$link = $date.' ~ '.$end;
			$start_date = $end_date = '';
			$start_date = apply_filters( 'attmgr_date_format', $date, mktime( 0, 0, 0, $m, $d, $y ) );
			$end_date = apply_filters( 'attmgr_date_format', $date, mktime( 0, 0, 0, $m, $d+6, $y ) );
			$link = sprintf( '%s ~ %s', $start_date, $end_date );
		} else {
			$qs = array();
			if ( ! empty( $attmgr->page['qs'] ) ) {
				$qs = $attmgr->page['qs'];
			}
			$qs['week'] = $next;
			unset( $qs['date'] );
			$query_string = add_query_arg( $qs, '' );
			$link = sprintf( '<a href="%s">%s</a>', $query_string, $title );
		}
		return $link;
	}

	/**
	 *	Link to previous week 
	 */
	public static function prev_week_link( $date ) {
		return self::next_week_link( $date, -7 );
	}

	/**
	 *	Link to this week 
	 */
	public static function this_week_link( ) {
		global $attmgr;

		$thisweek = date( 'Y-m-d', current_time( 'timestamp' ) );
		$qs = array();
		if ( ! empty( $attmgr->page['qs'] ) ) {
			$qs = $attmgr->page['qs'];
		}
		$qs['week'] = $thisweek;
		unset( $qs['date'] );
		$query_string = './';	// add_query_arg( $qs, '' );
		$link = sprintf( '<span class="back_today"><a href="%s">&raquo; %s</a></span>', $query_string, __( 'Today', ATTMGR::TEXTDOMAIN ) );
		return $link;
	}

	/**
	 *	Navigation 
	 */
	public static function show_navi( $y, $m, $past = true ) {
		$thismonth = date( 'Y-m', current_time( 'timestamp' ) );
		$prev_month = self::prev_month_link( $y, $m );
		if ( ! $past ) {
			$month = ATTMGR_Calendar::prev_month( $y, $m );
			if ( implode( '-', $month ) < $thismonth ) {
				$prev_month = '&nbsp;';
			}
		}
		$format = <<<EOD
<div id="list_pagenavi" class="list_pagenavi">
<div id="prev_page" class="prev_page">&nbsp;</div>
<div id="list_datenavi" class="list_datenavi">
%PREV_MONTH%
%CURRENT_MONTH%
%NEXT_MONTH%
</div>
<div id="next_page" class="next_page">%THIS_MONTH%</div>
</div>
EOD;
		$search = array(
				'%PREV_MONTH%',
				'%CURRENT_MONTH%',
				'%NEXT_MONTH%',
				'%THIS_MONTH%'
			);
		$replace = array(
				$prev_month,
				self::current_month( $y, $m ),
				self::next_month_link( $y, $m ),
				( 0 == strcmp( $y.'-'.$m, $thismonth ) ) ? '' : self::this_month_link()
			);
		return str_replace( $search, $replace, $format );
	}

	/**
	 *	Navigation (weekly)
	 */
	public static function show_navi_weekly( $date, $past = true ) {
		$thisweek = date( 'Y-m-d', current_time( 'timestamp' ) );
		$prev_week = self::prev_week_link( $date );
		if ( ! $past && ATTMGR_Calendar::prev_week( $date ) < $thisweek ) {
			$prev_week = '&nbsp;';
		}

		$format = <<<EOD
<div id="list_pagenavi" class="list_pagenavi">
<div id="prev_page" class="prev_page">&nbsp;</div>
<div id="list_datenavi" class="list_datenavi">
%PREV_WEEK%
%CURRENT_WEEK%
%NEXT_WEEK%
</div>
<div id="next_page" class="next_page">%THIS_WEEK%</div>
</div>
EOD;
		$search = array(
				'%PREV_WEEK%',
				'%CURRENT_WEEK%',
				'%NEXT_WEEK%',
				'%THIS_WEEK%'
			);
		$replace = array(
				$prev_week,
				self::current_week( $date ),
				self::next_week_link( $date ),
				( 0 == strcmp( $date, $thisweek ) ) ? '' : self::this_week_link()
			);
		return str_replace( $search, $replace, $format );
	}

	/**
	 *	Link to day
	 */
	public static function date_link( $args ) {
		global $attmgr;

		extract( $args['data'] );
		/*
			[d] => 1
			[status] => 1
			[y] => 2014
			[m] => 04
        */
		$qs = array();
		if ( ! empty( $attmgr->page['qs'] ) ) {
			$qs = $attmgr->page['qs'];
		}
		$qs['date'] = sprintf( '%s-%s-%02d', $y, $m, $d );
		$query_string = add_query_arg( $qs, '' );
		$link = sprintf( '<a href="%s">%d</a>', $query_string, $d );
		return $link;
	}

	/**
	 *	Link to day (weekly)
	 */
	public static function date_link_weekly( $args ) {
		$link = self::date_link( $args );
		$link .= sprintf( '<div class="wmonth">%s-%s</div>', $args['data']['y'], $args['data']['m'] );
		return $link;
	}

	/**
	 *	[Shortcode] Show calendar
	 */
	public static function show_calendar( $atts, $content = null ){
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'type' => 'monthly',	// or 'weekly'
					'start' => 0,			// 0:Sun, 1:Mon, ... 6:Sut 
					'next' => 0,			// Next month
				),
				$atts
			)
		);

		if ( $attmgr->page['midnight'] ) {
			$atts['today'] = $attmgr->page['begin_date'];
		}
		switch ( $type ) {
		case 'weekly':
			if ( isset( $attmgr->page['qs']['week'] ) ) {
				$week = $attmgr->page['qs']['week'];
			} else {
				if ( $attmgr->page['midnight'] ) {
					$week = $attmgr->page['begin_date'];
				} else {
					$week = date( 'Y-m-d', current_time( 'timestamp' ) );
				}
			}

			$data = self::set_weekly( $week );
			$html = self::show_navi_weekly( $week );
			$html .= self::html_weekly( $atts, $data, array( 'ATTMGR_Calendar', 'date_link_weekly' ) );
			break;

		case 'monthry':
		default: 
			if ( isset( $attmgr->page['qs']['month'] ) ) {
				$month = $attmgr->page['qs']['month'];
			} else {
				if ( $attmgr->page['midnight'] ) {
					$month = substr( $attmgr->page['begin_date'], 0, 7 );
				} else {
					$month = date( 'Y-m', current_time( 'timestamp' ) );
				}
			}
			list( $y, $m ) = explode( '-', $month );

			if ( $next ) {
				$nextmonth = date( 'Y-m-d', mktime( 0, 0, 0, $m + $next, 1, $y ) );
				list( $y, $m ) = explode( '-', $nextmonth );
			}
			$data = self::set( $y, $m, $start );
			$html = self::show_navi( $y, $m );
			$html .= self::html( $atts, $data, array( 'ATTMGR_Calendar', 'date_link' ) );
			break;
		}
		return $html;
	}

	/**
	 *	Get week beginning
	 */
	public static function get_week_beginning( $current_date, $today = '' ) {
		global $attmgr;
		// Current date
		list( $cy, $cm, $cd ) = array_pad( explode( '-', $current_date ), 3, 0 );
		$cm = intval( $cm );
		$cd = intval( $cd );
		$cy = intval( $cy );
		$current_time = mktime( 0, 0, 0, $cm, $cd, $cy );

		if ( empty( $today ) ) {
			$today = $attmgr->page['startdate'];
		}
		list( $ty, $tm, $td ) = array_pad( explode( '-', $today ), 3, 0 );
		$tm = intval( $tm );
		$td = intval( $td );
		$ty = intval( $ty );
		$today_time = mktime( 0, 0, 0, $tm, $td, $ty );

		// Calc
		$c = array();
		$c['diff'] = $current_time - $today_time;
		$c['day'] = intval( $c['diff'] / ( 60*60*24 ) );
		$c['week'] = intval( $c['day'] / 7 );

		$ret = array(
			'day1' => date( 'Y-m-d', $today_time + 60*60*24 * 7*$c['week'] ),
			'day7' => date( 'Y-m-d', ( $today_time + 60*60*24 * 7*$c['week'] ) + 60*60*24 * 6 )
		);
		return $ret;
	}

	/**
	 *	Date format
	 */
	public static function date_format( $date, $time ) {
		$option = ATTMGR::get_option('general');
		$date = date( $option['format_month_day'], $time );
		return $date;
	}

	/**
	 *	Date format
	 */
	public static function month_format( $date, $time ) {
		$option = ATTMGR::get_option('general');
		$date = date( $option['format_year_month'], $time );
		return $date;
	}

	/**
	 *	Time format
	 */
	public static function time_format( $timestamp ) {
		$option = ATTMGR::get_option('general');
		$timestamp = ATTMGR_Form::time_calc( $timestamp, 0, false );
		$time = date( $option['format_time'], strtotime( $timestamp ) );
		return $time;
	}

	/**
	 *	Time format on scheduler
	 */
	public static function time_format_editor( $timestamp ) {
		$option = ATTMGR::get_option('general');
		$timestamp = ATTMGR_Form::time_calc( $timestamp, 0, false );
		$time = date( $option['format_time_editor'], strtotime( $timestamp ) );
		return $time;
	}
}
?>
