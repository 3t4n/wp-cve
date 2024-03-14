<?php
/**
 *	Form & Action
 */

class ATTMGR_Form {
	/**
	 *	Initialize
	 */
	public static function init(){
		add_action( 'template_redirect', array( 'ATTMGR_Form', 'access_control' ), 99 );
		add_action( 'template_redirect', array( 'ATTMGR_Form', 'action' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_Form', 'update_by_staff' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_Form', 'update_by_admin' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_staff_scheduler', array( 'ATTMGR_Form', 'staff_scheduler' ), 99, 3 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_admin_scheduler', array( 'ATTMGR_Form', 'admin_scheduler' ), 99, 3 );
	}

	/**
	 *	フォームアクション: form action
	 */
	public static function action() {
		global $attmgr, $wpdb;
		
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$result = false;
			$result = apply_filters( ATTMGR::PLUGIN_ID.'_action', $result );
		}
	}

	/**
	 *	Scheduler for staff
	 */
	public static function update_by_staff( $result ) {
		global $attmgr, $wpdb;
		
		if ( ATTMGR::PLUGIN_ID.'_update_by_staff' != $_POST['action'] ) {
			return $result;
		}

		$error = '';
		if ( empty( $_POST['onetimetoken'] ) || ! wp_verify_nonce( $_POST['onetimetoken'], ATTMGR::PLUGIN_ID ) ) {
			$error = 'NONCE_ERROR';
		} else {
			$staff_id = $attmgr->user['operator']->data['ID'];
			$values = array();
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_post'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_post'] as $date => $value ) {
					$starttime = $value['starttime'];
					$endtime = $value['endtime'];
					if ( empty( $starttime ) && empty( $endtime ) ) {
						$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL )", array( $staff_id, $date ) );
					} elseif ( empty( $starttime ) ) {
						$values[] = $wpdb->prepare( "( %d, %s, NULL, %s )", array( $staff_id, $date, $endtime ) );
					} elseif ( empty( $endtime ) ) {
						$values[] = $wpdb->prepare( "( %d, %s, %s, NULL )", array( $staff_id, $date, $starttime ) );
					} else {
						$values[] = $wpdb->prepare( "( %d, %s, %s, %s )", array( $staff_id, $date, $starttime, $endtime ) );
					}
				}
			}
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "INSERT INTO $table "
					."( `staff_id`, `date`, `starttime`, `endtime` ) "
					."VALUES "
					.implode( ',', $values )." "
					."ON DUPLICATE KEY UPDATE "
					."starttime = VALUES( starttime ), endtime = VALUES( endtime ) ";
			$ret = $wpdb->query( $query );
			$ret = $wpdb->query( "DELETE FROM $table WHERE starttime IS NULL AND endtime IS NULL " );

			// OFF
			$del_where = array();
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_off'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_off'] as $date => $value ) {
					$del_where[] = sprintf( "'%s'", $date );
				}
				$ret = $wpdb->query( "DELETE FROM $table WHERE `staff_id`=$staff_id AND `date` IN (".implode( ',', $del_where )." )" );
			}
		}
		$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['staff_scheduler'] )->ID );
		// エラーあり
		if ( $error ) {
			$query_string = ( strstr( $url, '?' ) ) ? '&' : '?';
			$query_string .= sprintf( 'error=%s', $error );
			header( 'Location:'.$url.$query_string );
			exit;
		}
		if ( empty( $_POST['returnurl'] ) ) {
			header( 'Location:'.$url );
		} else {
			header( 'Location:'.$_POST['returnurl'] );
		}
		exit;
	}
	
	/**
	 *	Scheduler for admin
	 */
	public static function update_by_admin( $result ) {
		global $attmgr, $wpdb;
		
		if ( ATTMGR::PLUGIN_ID.'_update_by_admin' != $_POST['action'] ) {
			return $result;
		}
		$error = '';
		if ( empty( $_POST['onetimetoken'] ) || ! wp_verify_nonce( $_POST['onetimetoken'], ATTMGR::PLUGIN_ID ) ) {
			$error = 'NONCE_ERROR';
		} else {
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "INSERT INTO $table "
					."( `staff_id`, `date`, `starttime`, `endtime` ) "
					."VALUES "
					."%VALUES% "
					."ON DUPLICATE KEY UPDATE "
					."starttime = VALUES( starttime ), endtime = VALUES( endtime ) ";
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_post'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_post'] as $staff_id => $data ) {
					$values = array();
					// Update
					foreach ( $data as $date => $value ) {
						$starttime = $value['starttime'];
						$endtime = $value['endtime'];
						if ( empty( $starttime ) && empty( $endtime ) ) {
							$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL )", array( $staff_id, $date ) );
						} elseif ( empty( $starttime ) ) {
							$values[] = $wpdb->prepare( "( %d, %s, NULL, %s )", array( $staff_id, $date, $endtime ) );
						} elseif ( empty( $endtime ) ) {
							$values[] = $wpdb->prepare( "( %d, %s, %s, NULL )", array( $staff_id, $date, $starttime ) );
						} else {
							$values[] = $wpdb->prepare( "( %d, %s, %s, %s )", array( $staff_id, $date, $starttime, $endtime ) );
						}
					}
					$sql = str_replace( '%VALUES%', implode( ',', $values ), $query ); 
					$ret = $wpdb->query( $sql );
				}
				$ret = $wpdb->query( "DELETE FROM $table WHERE starttime IS NULL AND endtime IS NULL " );
			}
			// OFF
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_off'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_off'] as $staff_id => $data ) {
					$del_where = array();
					foreach ( $data as $date => $value ) {
						$del_where[] = sprintf( "'%s'", $date );
					}
					$ret = $wpdb->query( "DELETE FROM $table WHERE `staff_id`=$staff_id AND `date` IN (".implode( ',', $del_where )." )" );
				}
			}
		}

		$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['admin_scheduler'] )->ID );
		// エラーあり
		if ( $error ) {
			$query_string = ( strstr( $url, '?' ) ) ? '&' : '?';
			$query_string .= sprintf( 'error=%s', $error );
			header( 'Location:'.$url.$query_string );
			exit;
		}
		
		if ( empty( $_POST['returnurl'] ) ) {
			header( 'Location:'.$url );
		} else {
			header( 'Location:'.$_POST['returnurl'] );
		}
		exit;
	}

	/** 
	 *	各ページへのアクセス制限: Control for access to  special page
	 */
	public static function access_control() {
		global $attmgr, $wpdb;

		$ancestor = ( ! empty( $attmgr->page['ancestor']['ID'] ) ) ? get_post( $attmgr->page['ancestor']['ID'] ) : $attmgr->page['post'];
		if ( empty( $ancestor ) ) {
			return;
		}
		
		// Scheduler for staff
		if ( $ancestor->post_name == $attmgr->option['specialpages']['staff_scheduler'] ) {
			// not logged in
			if ( ! $attmgr->user['operator']->is_loggedin() ) {
				if ( empty( $attmgr->option['specialpages']['login_page'] ) ) {
					$url = wp_login_url();
				} else {
					$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['login_page'] )->ID );
				}

				if ( ! empty( $attmgr->page['redirect_to'] ) ) {
					$url .= ( strstr( $url, '?' ) ) ? '&' : '?';
					$url = $url.implode( '&', $attmgr->page['redirect_to'] );
				}
				header( 'Location: '.$url );
				exit;
			}
		}

		// Scheduler for admin
		if ( $ancestor->post_name == $attmgr->option['specialpages']['admin_scheduler'] ) {
			// not logged in
			if ( ! $attmgr->user['operator']->is_loggedin() ) {
				if ( empty( $attmgr->option['specialpages']['login_page'] ) ) {
					$url = wp_login_url();
				} else {
					$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['login_page'] )->ID );
				}

				if ( ! empty( $attmgr->page['redirect_to'] ) ) {
					$url .= ( strstr( $url, '?' ) ) ? '&' : '?';
					$url = $url.implode( '&', $attmgr->page['redirect_to'] );
				}
				header( 'Location: '.$url );
				exit;
			}
		}
	}

	/**
	 *	Scheduler for staff
	 */
	public static function staff_scheduler( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
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
			if ( $attmgr->user['operator']->is_staff() ) {
				$staff_id = $attmgr->user['operator']->data['ID'];
				$startdate = $attmgr->page['startdate'];
				list( $y, $m, $d ) = array_pad( explode( '-', $startdate ), 3, 0 );
				$m = intval( $m );
				$d = intval( $d );
				$y = intval( $y );
				$starttime = mktime( 0, 0, 0, $m, $d, $y );

				$term = $attmgr->option['general']['editable_term'];
				$endtime = mktime( 0, 0, 0, $m, $d + $term, $y );
				$enddate = date( 'Y-m-d', $endtime );

				$table = '';
				$table = apply_filters( 'attmgr_schedule_table_name', $table );
				$query = "SELECT * FROM $table "
						."WHERE staff_id = %d "
						."AND ( date>=%s AND date<= %s ) ";
				$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
				$schedule = array();
				if ( !empty( $records ) ) {
					foreach ( $records as $r ) {
						$schedule[ $r['date'] ] = $r;
						$schedule[ $r['date'] ]['starttime'] = substr( $schedule[ $r['date'] ]['starttime'], 0, 5 );
						$schedule[ $r['date'] ]['endtime'] = substr( $schedule[ $r['date'] ]['endtime'], 0, 5 );
					}
				}
				// Portrait
				$portrait = null;
				$portrait = ATTMGR_Function::get_portrait( $portrait, $attmgr->user['operator'] );
				$name = $attmgr->user['operator']->data[ $name_key ];

				// Profile
				$profile = sprintf( '<h3 class="name">%s</h3>', $name );

				// Return url
				$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['staff_scheduler'] )->ID );
				$query_string = ( strstr( $url, '?' ) ) ? '&' : '?';
				$url .= ( empty( $attmgr->page['qs']['week'] ) ) ? '' : $query_string.'week='.$startdate;

				$format = <<<EOD
%NAVI%
<form id="%FORM_ID%" method="post">
<div class="portrait">%PORTRAIT%</div>
<div class="profile">%PROFILE%</div>
<table class="%CLASS%">
<tr><th class="date">%DATE_LABEL%</th><th class="time">%TIME_LABEL%</th></tr>
%SCHEDULE%
</table>
%NONCE%
<input type="hidden" name="returnurl" value="%RETURN_URL%" />
<input type="hidden" name="action" value="%ACTION%" />
<input type="submit" name="submit" value="%SUBMIT%" />
</form>
%MESSAGE%
EOD;
				$param = array(
					'start'    => $attmgr->option['general']['starttime'],
					'end'      => $attmgr->option['general']['endtime'],
					'interval' => $attmgr->option['general']['interval'],
					'class'    => array(),
				);

				$line = '';
				for ( $i = 0; $i < 7; $i++ ) {
					$t = $starttime + 60*60*24*$i;
					$d = date( 'Y-m-d', $t );
					$w = date( 'w', $t );
					$dow = ATTMGR_Calendar::dow( $w );

					$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['starttime'] : '';
					$param['name'] = ATTMGR::PLUGIN_ID.'_post['.$d.'][starttime]';
					$st = ATTMGR_Form::select_time( $param );

					$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['endtime'] : '';
					$param['name'] = ATTMGR::PLUGIN_ID.'_post['.$d.'][endtime]';
					$et = ATTMGR_Form::select_time( $param );

					$off = sprintf( '<label><input type="checkbox" name="%s_off[%s]" value="1" />%s</label>', ATTMGR::PLUGIN_ID, $d, __( 'DEL', ATTMGR::TEXTDOMAIN ) );
					$date = '';
					$date = sprintf( '%s(%s)', 
						apply_filters( 'attmgr_date_format', $date, $t ),
						ATTMGR_Calendar::dow( $w )
					);
					$line .= sprintf( '<tr><td class="date">%s</td><td>%s %s~%s</td></tr>'."\n", $date, $off, $st, $et );
				}
				$search = array(
					'%NAVI%',
					'%FORM_ID%',
					'%CLASS%',
					'%PORTRAIT%',
					'%PROFILE%',
					'%DATE_LABEL%',
					'%OFF_LABEL%',
					'%TIME_LABEL%',
					'%SCHEDULE%',
					'%NONCE%',
					'%RETURN_URL%',
					'%ACTION%',
					'%SUBMIT%',
					'%MESSAGE%',
				);
				$replace = array(
					ATTMGR_Calendar::show_navi_weekly( $startdate ),
					ATTMGR::PLUGIN_ID.'_staff_scheduler',
					ATTMGR::PLUGIN_ID.'_staff_scheduler',
					$portrait,
					$profile,
					__( 'Date', ATTMGR::TEXTDOMAIN ),
					__( 'Off', ATTMGR::TEXTDOMAIN ),
					__( 'Time', ATTMGR::TEXTDOMAIN ),
					$line,
					wp_nonce_field( ATTMGR::PLUGIN_ID, 'onetimetoken', true, false ),
					$url,
					ATTMGR::PLUGIN_ID.'_update_by_staff',
					__( 'Update', ATTMGR::TEXTDOMAIN ),
					'',
				);
				$subject = str_replace( $search, $replace, $format );
				echo $subject;
			} else {
				$error_msg = __( 'Permission denied.', ATTMGR::TEXTDOMAIN ).'<br>';
				$error_msg .= __( 'Only a "Staff" user can edit here.', ATTMGR::TEXTDOMAIN ).'<br>';
				printf( '<div class="alert alert-error">%s</div>', $error_msg );
			}
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	Scheduler for admin
	 */
	public static function admin_scheduler( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
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
			if ( $attmgr->user['operator']->can_edit_admin_scheduler() ) {
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
				$staff = ATTMGR_User::get_all_staff();
				foreach ( $staff as $s ) {
					$staff_id = $s->data['ID'];
					$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
					$schedule = array();
					if ( !empty( $records ) ) {
						foreach ( $records as $r ) {
							$schedule[ $r['date'] ] = $r;
							$schedule[ $r['date'] ]['starttime'] = substr( $schedule[ $r['date'] ]['starttime'], 0, 5 );
							$schedule[ $r['date'] ]['endtime'] = substr( $schedule[ $r['date'] ]['endtime'], 0, 5 );
						}
					}
					$param = array(
						'start'    => $attmgr->option['general']['starttime'],
						'end'      => $attmgr->option['general']['endtime'],
						'interval' => $attmgr->option['general']['interval'],
						'class'    => array(),
					);

					$line = '';
					for ( $i = 0; $i < 7; $i++ ) {
						$d = date( 'Y-m-d', $starttime + 60*60*24*$i );
						$w = date( 'w', $starttime + 60*60*24*$i );
						$dow = ATTMGR_Calendar::dow( $w );

						$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['starttime'] : '';
						$param['name'] = sprintf( '%s_post[%d][%s][starttime]', ATTMGR::PLUGIN_ID, $staff_id, $d );
						$st = ATTMGR_Form::select_time( $param );

						$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['endtime'] : '';
						$param['name'] = sprintf( '%s_post[%d][%s][endtime]', ATTMGR::PLUGIN_ID, $staff_id, $d );
						$et = ATTMGR_Form::select_time( $param );

						$off = sprintf( '<label><input type="checkbox" name="%s_off[%d][%s]" value="1" />%s</label>', ATTMGR::PLUGIN_ID, $staff_id, $d, __( 'DEL', ATTMGR::TEXTDOMAIN ) );
						$line .= sprintf( '<td>%s<br>%s<br>%s</td>'."\n", $st, $et, $off );
					}
					$portrait = null;
					$portrait = ATTMGR_Function::get_portrait( $portrait, $s );
					$name = $s->data[ $name_key ];
					if ( ! empty( $s->data['user_url'] ) ) {
						$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
					}
					$body .= sprintf( '<tr><td class="portrait">%s<br>%s</td>%s</tr>'."\n", $portrait, $name, $line );
				}

				// Return url
				$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['admin_scheduler'] )->ID );
				$query_string = ( strstr( $url, '?' ) ) ? '&' : '?';
				$url .= ( empty( $attmgr->page['qs']['week'] ) ) ? '' : $query_string.'week='.$startdate;

				$format = <<<EOD
%NAVI%
<form id="%FORM_ID%" method="post">
<table class="%CLASS%">
%HEAD%
%BODY%
</table>
%NONCE%
<input type="hidden" name="returnurl" value="%RETURN_URL%" />
<input type="hidden" name="action" value="%ACTION%" />
<input type="submit" name="submit" value="%SUBMIT%" />
</form>
%MESSAGE%
EOD;
				$search = array(
					'%NAVI%',
					'%FORM_ID%',
					'%CLASS%',
					'%HEAD%',
					'%BODY%',
					'%NONCE%',
					'%RETURN_URL%',
					'%ACTION%',
					'%SUBMIT%',
					'%MESSAGE%',
				);
				$replace = array(
					ATTMGR_Calendar::show_navi_weekly( $startdate ),
					ATTMGR::PLUGIN_ID.'_admin_scheduler',
					ATTMGR::PLUGIN_ID.'_admin_scheduler',
					$head,
					$body,
					wp_nonce_field( ATTMGR::PLUGIN_ID, 'onetimetoken', true, false ),
					$url,
					ATTMGR::PLUGIN_ID.'_update_by_admin',
					__( 'Update', ATTMGR::TEXTDOMAIN ),
					'',
				);
				$subject = str_replace( $search, $replace, $format );
				echo $subject;
			} else {
				printf( '<div class="alert alert-error">%s</div>', __( 'Permission denied.', ATTMGR::TEXTDOMAIN ) );
			}
		}
		$html = ob_get_contents();
		if (ob_get_contents()) ob_end_clean();
		return $html;
	}

	/**
	 *	(function) Make select tag 
	 */
	public static function select_time( $atts ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'start'    => null,
					'end'      => null,
					'interval' => null,
					'default'  => null,
					'current'  => null,
					'name'     => null,
					'class'    => array(),
				),
				$atts
			)
		);
		$subject = <<<EOD
<select class="%CLASS%" name="%NAME%">
%OPTIONS%</select>
EOD;
		$options = '<option value=""></options>'."\n";
		$now = $start;
		$end = ( $start > $end ) ? ATTMGR_Form::time_calc( $end, 60*24 ) : $end;
		$i = 0;
		while ( $now <= $end && $i <= 24 ) {
			$hour = intval( substr( $now, 0, 2 ) );
			$min = intval( substr( $now, -2 ) );
			$show = apply_filters( 'attmgr_time_format_editor', $now );
			$selected = ( $current == $now ) ? 'selected' : '';
			$options .= sprintf( '<option value="%s" %s >%s</options>'."\n", $now, $selected, $show );
			$min += $interval;
			if ( $min >= 60 ) {
				$i += floor( $min / 60 );
				$hour += floor( $min / 60 );
				$min = abs( $min % 60 );
			}
			$now = sprintf( '%02d:%02d', $hour, $min );
		}
		$search = array(
			'%NAME%',
			'%CLASS%',
			'%OPTIONS%'
		);
		$replace = array(
			$name,
			( !empty( $class ) ) ? implode( ' ', $class ) : '',
			$options
		);
		$html = str_replace( $search, $replace, "\n".$subject );
		return $html;
	}

	/**
	 *	time calc
	 */
	public static function time_calc( $time, $add, $over24 = true ) {
		/*
		$time = '10:00'
		$add = 120 (min)
		*/
		$ret = '';
		if ( empty( $time ) ) {
			$time = '00:00';
		}
		list( $hour, $min ) = explode( ':', $time );
		$min += $add;
		if ( 0 > $min  || 60 <= $min ) {
			$hour += floor( $min / 60 );
			$min = abs( $min % 60 );
		}
		if ( 0 > $hour ) {
			$hour = abs( $hour % 24 );
		} elseif ( ! $over24 ) {
			if ( 24 <= $hour ) {
				$hour = abs( $hour % 24 );
			}
		}
		$ret = sprintf( '%02d:%02d', $hour, $min );
		return $ret;
	}

}
?>
