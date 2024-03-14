<?php
/**
 *	Shortcodes
 */

class ATTMGR_Shortcode {
	/**
	 *	Init
	 */
	public static function init() {
		add_shortcode( ATTMGR::PLUGIN_ID.'_staff_scheduler', array( 'ATTMGR_Shortcode', 'staff_scheduler' ) );
		add_shortcode( ATTMGR::PLUGIN_ID.'_admin_scheduler', array( 'ATTMGR_Shortcode', 'admin_scheduler' ) );

		add_shortcode( ATTMGR::PLUGIN_ID.'_daily', array( 'ATTMGR_Shortcode', 'daily' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily', array( 'ATTMGR_Shortcode', 'daily_schedule' ), 99, 3 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily_format', array( 'ATTMGR_Shortcode', 'daily_format' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily_values', array( 'ATTMGR_Shortcode', 'daily_values' ), 99, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily_guide', array( 'ATTMGR_Shortcode', 'daily_guide' ), 99 );

		add_shortcode( ATTMGR::PLUGIN_ID.'_weekly', array( 'ATTMGR_Shortcode', 'weekly' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly', array( 'ATTMGR_Shortcode', 'weekly_schedule' ), 99, 3 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_attendance', array( 'ATTMGR_Shortcode', 'weekly_attendance' ), 99, 2 );

		add_shortcode( ATTMGR::PLUGIN_ID.'_weekly_all', array( 'ATTMGR_Shortcode', 'weekly_all' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all', array( 'ATTMGR_Shortcode', 'weekly_all_schedule' ), 99, 3 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all_info', array( 'ATTMGR_Shortcode', 'weekly_all_info' ), 99, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all_attendance', array( 'ATTMGR_Shortcode', 'weekly_all_attendance' ), 99, 2 );

		add_shortcode( ATTMGR::PLUGIN_ID.'_monthly_all', array( 'ATTMGR_Shortcode', 'monthly_all' ) );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_monthly_all', array( 'ATTMGR_Shortcode', 'monthly_all_schedule' ), 99, 3 );

		add_shortcode( ATTMGR::PLUGIN_ID.'_calendar', array( 'ATTMGR_Calendar', 'show_calendar' ) );
		add_shortcode( ATTMGR::PLUGIN_ID.'_today_work', array( 'ATTMGR_Shortcode', 'today_work' ) );
	}

	/**
	 *	Scheduler for staff
	 */
	public static function staff_scheduler( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
				),
				$atts
			)
		);
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_staff_scheduler', $html, $atts, $content );
		return $html;
	}

	/**
	 *	Scheduler for Admin
	 */
	public static function admin_scheduler( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
				),
				$atts
			)
		);
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_admin_scheduler', $html, $atts, $content );
		return $html;
	}

	/**
	 *	The staff who works today
	 */
	public static function daily( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
				),
				$atts
			)
		);
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_daily', $html, $atts, $content );
		return $html;
	}

	/**
	 *	Schedule for a week of staff
	 */
	public static function weekly( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'id'   => null,
					'hide' => false,
				),
				$atts
			)
		);
		$html = '';
		if ( ! $hide ) {
			$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_weekly', $html, $atts, $content );
		}
		return $html;
	}

	/**
	 *	Schedule for a week of all staff
	 */
	public static function weekly_all( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
				),
				$atts
			)
		);
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all', $html, $atts, $content );
		return $html;
	}

	/**
	 *	Schedule for a month of all staff
	 */
	public static function monthly_all( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
				),
				$atts
			)
		);
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_shortcode_monthly_all', $html, $atts, $content );
		return $html;
	}

	/**
	 *	Monthly all schedule
	 */
	public static function monthly_all_schedule( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'start' => 0,			// 0:Sun, 1:Mon, ... 6:Sut 
					'past'  => true,
					'name_key'  => 'display_name',
				),
				$atts
			)
		);
		ob_start();
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'There are no staff.', ATTMGR::TEXTDOMAIN ) );
		} else {
			$staff_ids = array();
			foreach ( $staff as $s ) {
				$staff_ids[] = $s->data['ID'];
			}

			// month
			if ( ! empty( $attmgr->page['qs']['month'] ) ) {
				$args['month'] = $attmgr->page['qs']['month'];
			}
			else {
				$args['month'] = date( 'Y-m', current_time('timestamp') );
			}
			list( $y, $m ) = explode( '-', $args['month'] );

			// calnedar data
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "SELECT * FROM $table "
					."WHERE date LIKE %s AND staff_id IN (".implode( ',', $staff_ids ).") "
					."ORDER BY date ASC ";
			$records = $wpdb->get_results( $wpdb->prepare( $query, array( $args['month'].'%' ) ), ARRAY_A );
			$extends = array();
			if ( ! empty( $records ) ) {
				foreach( $records as $r ) {
					$d = intval( substr( $r['date'], 8, 2 ) );
					if ( ! empty( $r['starttime'] ) && ! empty( $r['endtime'] ) ) {
						$extends[ $d ]['staff'][] = $r['staff_id'];
						$extends[ $d ]['name_key'] = $name_key;
					}
				}
				for( $i = 1; $i <= 31; $i++ ) {
					if ( ! isset( $extends[ $i ] ) ) {
						$extends[ $i ] = array();
					}
				}
				ksort( $extends );
			}
			$data = ATTMGR_Calendar::set( $y, $m, $start, $extends );
			// html
			$subject = ATTMGR_Calendar::show_navi( $y, $m, $past );
			if ( $attmgr->page['midnight'] ) {
				$atts['today'] = $attmgr->page['begin_date'];
			}
			$subject .= ATTMGR_Calendar::html( $atts, $data, array( 'ATTMGR_Shortcode', 'set_monthly_data' ) );
			$search = sprintf( '<table class="%s_calendar', ATTMGR::PLUGIN_ID );
			$replace = sprintf( '<table class="%1$s_calendar %1$s_monthly_schedule', ATTMGR::PLUGIN_ID );
			echo str_replace( $search, $replace, $subject );
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	Set deta for monthly schedule
	 */
	public static function set_monthly_data( $args ) {
		global $attmgr;

		extract( $args['data'] );
		/*
			[d] => 1
			[status] => 1
			[y] => 2014
			[m] => 04
			[staff] => array(
					...
				)
			[name_key] -> 'display_name'
        */
		$list = array();
		if ( ! empty( $staff ) ) {
			sort( $staff );
			foreach ( $staff as $staff_id ) {
				$s = new ATTMGR_User( $staff_id );
				$name = $s->data[ $name_key ];
				if ( ! empty( $s->data['user_url'] ) ) {
					$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
				}
				$list[] = sprintf( '<div class="staff">%s</div>', $name );
			}
		}
		$format = <<<EOD
<div>
<div class="day">%DAY%</div>
<div class="info">%STAFF%</div>
</div>
EOD;
		$search = array(
			'%DAY%',
			'%STAFF%',
		);
		$replace = array(
			$args['data']['d'],
			implode( "\n", $list ),
		);
		$html = str_replace( $search, $replace, $format );
		return $html;
	}

	/**
	 *	Weekly all schedule
	 */
	public static function weekly_all_schedule( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'past'  => true,
					'name_key' => 'display_name',
				),
				$atts
			)
		);
		ob_start();
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'There are no staff.', ATTMGR::TEXTDOMAIN ) );
		} else {
			$startdate = $attmgr->page['startdate'];
			list( $y, $m, $d ) = array_pad( explode( '-', $startdate ), 3, 0 );
			$m = intval( $m );
			$d = intval( $d );
			$y = intval( $y );
			$starttime = mktime( 0, 0, 0, $m, $d, $y );

			$term = 7;
			$endtime = mktime( 0, 0, 0, $m, $d + $term, $y );
			$enddate = date( 'Y-m-d', $endtime );

			// Head
			$head = '';
			for ( $i = 0; $i < $term; $i++ ) {
				$t = $starttime + 60*60*24*$i;
				$w = date( 'w', $t );
				$date = '';
				$date = sprintf( '<span class="date">%s</span><span class="dow">(%s)</span>', 
					apply_filters( 'attmgr_date_format', $date, $t ),
					ATTMGR_Calendar::dow( $w )
				);
				$head .= sprintf( '<th class="%s">%s</th>'."\n", ATTMGR_Calendar::dow_lower( $w ), $date );
			}
			$head = sprintf( '<tr><th>&nbsp;</th>'."\n".'%s</tr>', $head );

			// body
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "SELECT * FROM $table "
					."WHERE staff_id = %d "
					."AND ( date>=%s AND date<= %s ) ";

			$body = '';
			foreach ( $staff as $s ) {
				$staff_id = $s->data['ID'];
				$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
				$schedule = array();
				if ( !empty( $records ) ) {
					foreach ( $records as $r ) {
						if ( ! empty( $r['starttime'] ) || ! empty( $r['endtime'] ) ) {
							$schedule[ $r['date'] ] = $r;
							$schedule[ $r['date'] ]['starttime'] = apply_filters( 'attmgr_time_format', substr( $r['starttime'], 0, 5 ) );
							$schedule[ $r['date'] ]['endtime'] = apply_filters( 'attmgr_time_format', substr( $r['endtime'], 0, 5 ) );
						}
					}
				}
				$line = '';
				for ( $i = 0; $i < 7; $i++ ) {
					$d = date( 'Y-m-d', $starttime + 60*60*24*$i );
					$w = date( 'w', $starttime + 60*60*24*$i );
					$dow = ATTMGR_Calendar::dow( $w );
					$class = array( ATTMGR_Calendar::dow_lower( $w ) );
					if ( isset( $schedule[ $d ] ) ) {
						$class[] = 'working';
						$time = sprintf( '%s ~ %s', $schedule[ $d ]['starttime'], $schedule[ $d ]['endtime'] );
					}
					else {
						$class[] = 'not_working';
						$time = '-';
					}
					$attendance = $time;
					$args = array(
						'date' => $d,
						'schedule' => ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ] : '',
						'current_staff' => $s
					);
					$attendance = apply_filters( 'attmgr_shortcode_weekly_all_attendance', $attendance, $args );
					$line .= sprintf( '<td class="%s">%s</td>'."\n", implode( ' ', $class ), $attendance );
				}
				$portrait = null;
				$portrait = ATTMGR_Function::get_portrait( $portrait, $s );
				$name = $s->data[ $name_key ];
				if ( ! empty( $s->data['user_url'] ) ) {
					$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
				}
				$info = sprintf( '%s%s', $portrait, $name );
				$args = array(
					'name' => $name,
					'portrait' => $portrait,
					'current_staff' => $s
				);
				$info = apply_filters( 'attmgr_shortcode_weekly_all_info', $info, $args );
				$body .= sprintf( '<tr><td class="portrait">%s</td>%s</tr>'."\n", $info, $line );
			}

			$format = <<<EOD
%NAVI%
<table class="%CLASS%">
%HEAD%
%BODY%
</table>
EOD;
			$search = array(
				'%NAVI%',
				'%CLASS%',
				'%HEAD%',
				'%BODY%',
			);
			$replace = array(
				ATTMGR_Calendar::show_navi_weekly( $startdate, $past ),
				ATTMGR::PLUGIN_ID.'_weekly_all',
				$head,
				$body,
			);
			$subject = str_replace( $search, $replace, $format );
			echo $subject;
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	'attmgr_shortcode_weekly_all_info'
	 */
	public static function weekly_all_info( $info, $args ) {
		extract( $args );	// $portrait, $current_staff
		return $info;
	}

	/**
	 *	'attmgr_shortcode_weekly_all_attendance'
	 */
	public static function weekly_all_attendance( $attendance, $args ) {
		extract( $args );	// $date, $schedule, $current_staff
		return $attendance;
	}

	/**
	 *	Weekly personal schedule
	 */
	public static function weekly_schedule( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'id' => null,
				),
				$atts
			)
		);
		$staff_id = $id;
		$staff = new ATTMGR_User( $staff_id );
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'There are no staff.', ATTMGR::TEXTDOMAIN ) );
		} else {
			$startdate = $attmgr->page['startdate'];
			list( $y, $m, $d ) = array_pad( explode( '-', $startdate ), 3, 0 );
			$m = intval( $m );
			$d = intval( $d );
			$y = intval( $y );
			$starttime = mktime( 0, 0, 0, $m, $d, $y );

			$term = 7;
			$endtime = mktime( 0, 0, 0, $m, $d + $term, $y );
			$enddate = date( 'Y-m-d', $endtime );

			// Head
			$head = '';
			for ( $i = 0; $i < $term; $i++ ) {
				$t = $starttime + 60*60*24*$i;
				$w = date( 'w', $t );
				$date = '';
				$date = sprintf( '<span class="date">%s</span><span class="dow">(%s)</span>', 
					apply_filters( 'attmgr_date_format', $date, $t ),
					ATTMGR_Calendar::dow( $w )
				);
				$head .= sprintf( '<th class="%s">%s</th>'."\n", ATTMGR_Calendar::dow_lower( $w ), $date );
			}
			$head = sprintf( '<tr>%s</tr>', $head );

			// body
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "SELECT * FROM $table "
					."WHERE staff_id = %d "
					."AND ( date>=%s AND date<= %s ) ";

			$body = '';
			$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
			$schedule = array();
			if ( !empty( $records ) ) {
				foreach ( $records as $r ) {
					if ( ! empty( $r['starttime'] ) || ! empty( $r['endtime'] ) ) {
						$schedule[ $r['date'] ] = $r;
						$schedule[ $r['date'] ]['starttime'] = apply_filters( 'attmgr_time_format', substr( $r['starttime'], 0, 5 ) );
						$schedule[ $r['date'] ]['endtime'] = apply_filters( 'attmgr_time_format', substr( $r['endtime'], 0, 5 ) );
					}
				}
			}
			$line = '';
			for ( $i = 0; $i < 7; $i++ ) {
				$d = date( 'Y-m-d', $starttime + 60*60*24*$i );
				$w = date( 'w', $starttime + 60*60*24*$i );
				$dow = ATTMGR_Calendar::dow( $w );
				$class = array( ATTMGR_Calendar::dow_lower( $w ) );
				if ( isset( $schedule[ $d ] ) ) {
					$class[] = 'working';
					$time = sprintf( '%s ~ %s', $schedule[ $d ]['starttime'], $schedule[ $d ]['endtime'] );
				}
				else {
					$class[] = 'not_working';
					$time = '-';
				}
				$attendance = $time;
				$args = array(
					'date' => $d,
					'schedule' => ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ] : '',
					'current_staff' => $staff
				);
				$attendance = apply_filters( 'attmgr_shortcode_weekly_attendance', $attendance, $args );
				$line .= sprintf( '<td class="%s">%s</td>'."\n", implode( ' ', $class ), $attendance );
			}
			$body .= sprintf( '<tr>%s</tr>'."\n", $line );

			ob_start();
			$format = <<<EOD
<table class="%CLASS%">
%HEAD%
%BODY%
</table>
EOD;
			$search = array(
				'%CLASS%',
				'%HEAD%',
				'%BODY%',
			);
			$replace = array(
				ATTMGR::PLUGIN_ID.'_weekly',
				$head,
				$body,
			);
			$subject = str_replace( $search, $replace, $format );
			echo $subject;
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	'attmgr_shortcode_weekly_attendance'
	 */
	public static function weekly_attendance( $attendance, $args ) {
		extract( $args );	// $date, $schedule, $current_staff
		return $attendance;
	}

	/**
	 *	Daily schedule
	 */
	public static function daily_schedule( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'guide' => '',
					'past'  => true,
					'name_key' => 'display_name',
				),
				$atts
			)
		);
		ob_start();
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'No staff are registered yet.', ATTMGR::TEXTDOMAIN ) );
		} else {
			$date = $attmgr->page['startdate'];
			$starttime = $attmgr->option['general']['starttime'];
			$endtime = ATTMGR_Form::time_calc( $attmgr->option['general']['endtime'], 0, false );

			if ( ! empty( $guide ) ) {
				$args = array(
					'date'  => $date,
					'guide' => $guide,
					'past'  => $past,
					'html'  => '',
					'begin' => $attmgr->page['begin_date'],
				);
				$args = apply_filters( 'attmgr_shortcode_daily_guide', $args );
				echo $args['html'];
			}

			$now = current_time('timestamp');
			$now_time = date( 'H:i', $now );

			// e.g. 19:00 ~ 04:00
			$result = ATTMGR_User::get_working_staff( $date );
			if ( $attmgr->page['midnight'] ) {
				printf( '<div class="alert alert-normal">%s</div>', sprintf( __( '[Open %s~%s] Now %s ', ATTMGR::TEXTDOMAIN ), $starttime, $endtime, $now_time ) );
			}

			extract( $result );		// $staff, $attendance
			if ( empty( $staff ) ) {
				printf( '<div class="alert">%s</div>', __( 'There are no staff today.', ATTMGR::TEXTDOMAIN ) );
			} else {
				echo '<ul class="staff_block">'."\n";
				foreach ( $staff as $s ) {
					// Format
					$format = <<<EOD
<li>
	<div class="thumb">
		%PORTRAIT%
	</div>
	<div class="post-info">
		<div class="name">%NAME%</div>
		<div class="attendance">%ATTENDANCE%</div>
	</div>
	<div class="clear">&nbsp;</div>
</li>
EOD;
					$format = apply_filters( 'attmgr_shortcode_daily_format', $format );

					// Search: Key
					$search = array(
						'%PORTRAIT%',
						'%NAME%',
						'%ATTENDANCE%',
					);

					// Repelace: Value
					$portrait = null;
					$portrait = ATTMGR_Function::get_portrait( $portrait, $s );
					$name = $s->data[ $name_key ];
					if ( ! empty( $s->data['user_url'] ) ) {
						$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
					}
					$starttime = apply_filters( 'attmgr_time_format', $attendance[$s->data['ID']]['starttime'] );
					$endtime   = apply_filters( 'attmgr_time_format', $attendance[$s->data['ID']]['endtime'] );

					$replace = array(
						$portrait,
						$name,
						sprintf( '%s ~ %s', $starttime, $endtime ),
					);
					$args = array(
						'result' => $result,
						'current_staff' => $s
					);

					list( $search, $replace ) = apply_filters( 'attmgr_shortcode_daily_values', array( $search, $replace ), $args );
					$line = str_replace( $search, $replace, $format );
					echo $line;
				}
				echo "</ul>\n";
			}
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	'attmgr_shortcode_daily_format'
	 */
	public static function daily_format( $format ) {
		return $format;
	}

	/**
	 *	'attmgr_shortcode_daily_values'
	 */
	public static function daily_values( $array, $args ) {
		list( $search, $replace ) = $array;
		return array( $search, $replace );
	}

	/**
	 *	'attmgr_shortcode_daily_guide'
	 */
	public static function daily_guide( $args ) {
		/*
		$args = array(
			[date]  => '2015-11-03',
			[guide] => 'week',		// '1week', 'week'
			[past]  -> true,
			[html]  => '', 
			[begin] => '', 
		)
		*/
		extract( $args );	// $date, $guide, $html

		$begin = ( isset( $begin ) ) ? $begin : '';
		$week = ATTMGR_Calendar::get_week_beginning( $date, $begin );
		/*
		$week = array(
			[day1] => '2015-11-03',
			[day7] => '2015-11-10'
		)
		*/
		$calendar = ATTMGR_Calendar::set_weekly( $week['day1'] );
		$date_link = ATTMGR_Calendar::guide_weekly( array( 'current' => $date ), $calendar );
		if ( $guide == 'week' ) {
			$navi = ATTMGR_Calendar::show_navi_weekly( $week['day1'], $past );
			$html .= preg_replace( '/<a href=\"\?week=/s', '<a href="?date=', $navi );
		}
		if ( in_array( $args['guide'], array( 'week', '1week' ) ) ) {
			$html .= $date_link;
		}
		$args['html'] = $html;

		return $args;
	}

	/**
	 *	Show today's working time
	 */
	public static function today_work( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'id'   => null,
					'text' => '',
					'none' => '',
				),
				$atts
			)
		);
		$user = new ATTMGR_User( $id );
		$r = $user->is_work( $attmgr->page['startdate'] );

		if ( ! empty( $r ) ) {
			if ( empty( $text ) ) {
				$starttime = apply_filters( 'attmgr_time_format', substr( $r['starttime'], 0, 5 ) );
				$endtime = apply_filters( 'attmgr_time_format', substr( $r['endtime'], 0, 5 ) );
				$text = sprintf('%s - %s', $starttime, $endtime );
				if ( $attmgr->page['midnight'] ) {
					list( $y, $m, $d ) = explode('-', $attmgr->page['begin_date'] );
					$currenttime = mktime( 0, 0, 0, $m, $d, $y );
					$current_date = date('Y-m-d', $currenttime );
					$text = sprintf( '<span class="date">%s</span><span class="dow">(%s)</span> %s', 
						apply_filters( 'attmgr_date_format', $current_date, $currenttime ),
						ATTMGR_Calendar::dow( date( 'w', $currenttime ) ),
						$text
					);
				}
			}
			return $text;
		} else {
			return $none;
		}
	}
}
?>
