<?php
/* wppa-date-time.php
* Package: wp-photo-album-plus
*
* date and time related functions
* Version 8.2.05.002
*
*/

function wppa_get_timestamp( $key = false ) {

	$timnow = time();
	$format = 'Y:z:n:j:W:w:G:i:s';
	//         0 1 2 3 4 5 6 7 8
	// Year(2014):dayofyear(0-365):month(1-12):dayofmonth(1-31):Weeknumber(1-53):dayofweek(0-6):hour(0-23):min(0-59):sec(0-59)
	$local_date_time = wppa_local_date( $format, $timnow );

	$data = explode( ':', $local_date_time );
	$data[4] = ltrim( '0', $data[4] );

	$today_start = $timnow - $data[8] - 60 * $data[7] - 3600 * $data[6];
	if ( $key == 'todaystart' ) return $today_start;

	$daysec = 24 * 3600;

	if ( ! $data[5] ) $data[5] = 7;	// Sunday
	$thisweek_start = $today_start - $daysec * ( $data[5] - 1 );	// Week starts on monday
	if ( $key == 'thisweekstart' ) return $thisweek_start;
	if ( $key == 'lastweekend' ) return $thisweek_start;

	$thisweek_end = $thisweek_start + 7 * $daysec;
	if ( $key == 'thisweekend' ) return $thisweek_end;

	$lastweek_start = $thisweek_start - 7 * $daysec;
	if ( $key == 'lastweekstart' ) return $lastweek_start;

	$thismonth_start = $today_start - ( $data[3] - 1 ) * $daysec;
	if ( $key == 'thismonthstart' ) return $thismonth_start;
	if ( $key == 'lastmonthend' ) return $thismonth_start;

	$monthdays = array ( '0', '31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31' );
	$monthdays[2] += wppa_local_date('L', $timnow );	// Leap year correction

	$thismonth_end = $thismonth_start + $monthdays[$data[2]] * $daysec;
	if ( $key == 'thismonthend' ) return $thismonth_end;

	$lm = $data[2] > 1 ? $data[2] - 1 : 12;
	$lastmonth_start = $thismonth_start - $monthdays[$lm] * $daysec;
	if ( $key == 'lastmonthstart' ) return $lastmonth_start;

	$thisyear_start = $thismonth_start;
	$idx = $data[2];
	while ( $idx > 1 ) {
		$idx--;
		$thisyear_start -= $monthdays[$idx] * $daysec;
	}
	if ( $key == 'thisyearstart' ) return $thisyear_start;
	if ( $key == 'lastyearend' ) return $thisyear_start;

	$thisyear_end = $thisyear_start;
	foreach ( $monthdays as $month ) $thisyear_end += $month * $daysec;
	if ( $key == 'thisyearend' ) return $thisyear_end;

	$lastyear_start = $thisyear_start - 365 * $daysec;
	if ( wppa_local_date('L', $thisyear_start - $daysec) ) $lastyear_start -= $daysec;	// Last year was a leap year
	if ( $key == 'lastyearstart' ) return $lastyear_start;

	return $timnow;
}

function wppa_get_date_time_select_html( $type, $id, $selectable = true ) {

	$type = ucfirst( strtolower( $type ) );

	if ( $type == 'Photo' || $type == 'Delphoto' ) {
		$thumb = wppa_cache_photo( $id );
	}
	elseif ( $type == 'Album' || $type = 'Delalbum' ) {
		$album = wppa_cache_album( $id );
	}
	else {
		wppa_error_message('Uniplemented type: '.$type.' in wppa_get_date_time_select_html()');
	}

	$opt_months = array( '1' => __('Jan', 'wp-photo-album-plus' ), '2' => __('Feb', 'wp-photo-album-plus' ), '3' => __('Mar', 'wp-photo-album-plus' ), '4' => __('Apr', 'wp-photo-album-plus' ), '5' => __('May', 'wp-photo-album-plus' ), '6' => __('Jun', 'wp-photo-album-plus' ), '7' => __('Jul', 'wp-photo-album-plus' ), '8' =>__('Aug', 'wp-photo-album-plus' ), '9' => __('Sep', 'wp-photo-album-plus' ), '10' => __('Oct', 'wp-photo-album-plus' ), '11' => __('Nov', 'wp-photo-album-plus' ), '12' => __('Dec', 'wp-photo-album-plus' ) );
	$val_months = array( '1' => '01', '2' => '02', '3' => '03', '4' => '04', '5' => '05', '6' => '06', '7' => '07', '8' => '08', '9' => '09', '10' => '10', '11' => '11', '12' =>'12' );
	$Y = date( 'Y' );
	$opt_years 	= array( $Y, $Y+1, $Y+2, $Y+3, $Y+4, $Y+5, $Y+6, $Y+7, $Y+8, $Y+9, $Y+10 );
	$val_years 	= $opt_years;
	$opt_days 	= array( '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' );
	$val_days 	= $opt_days;
	$opt_hours 	= array( '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23' );
	$val_hours 	= $opt_hours;
	$opt_mins 	= array( '00', '01', '02', '03', '04', '05', '06', '07', '08', '09',
						 '10', '11', '12', '13', '14', '15', '16', '17', '18', '19',
						 '20', '21', '22', '23', '24', '25', '26', '27', '28', '29',
						 '30', '31', '32', '33', '34', '35', '36', '37', '38', '39',
						 '40', '41', '42', '43', '44', '45', '46', '47', '48', '49',
						 '50', '51', '52', '53', '54', '55', '56', '57', '58', '59' );
	$val_mins 	= $opt_mins;

	switch ( $type ) {
		case 'Photo':
			$curval = $thumb['scheduledtm'];
			$class = 'wppa-datetime-' . $id;
			break;
		case 'Album':
			$curval = $album['scheduledtm'];
			$class = 'wppa-datetime-' . $id;
			break;
		case 'Delphoto':
			$curval = $thumb['scheduledel'];
			$class = 'wppa-del-datetime-' . $id;
			break;
		case 'Delalbum':
			$curval = $album['scheduledel'];
			$class = 'wppa-del-datetime-' . $id;
			break;
		default:
			$curval = '';
			$class = '';
			break;
	}

	if ( ! $curval ) $curval = wppa_get_default_scheduledtm();

	$temp = explode( ',', $curval );
	$cur_day 	= $temp[2];
	$cur_month 	= $temp[1];
	$cur_year 	= $temp[0];
	$cur_hour 	= $temp[3];
	$cur_min 	= $temp[4];

	$result = '';

	if ( $selectable ) {

		// Day
		if ( $type == 'Photo' ) {
			$result .= 	'<select name="wppa-day" id="wppa-day-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'day\', this.value)">';
		}
		elseif ( $type == 'Delphoto' ) {
			$result .= 	'<select name="wppa-day" id="wppa-day-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'delday\', this.value)">';
		}
		elseif ( $type == 'Album' ) {
			$result .= 	'<select name="wppa-day" id="wppa-day-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'day\', this)">';
		}
		else {
			$result .= 	'<select name="wppa-day" id="wppa-day-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'delday\', this)">';
		}

		foreach ( array_keys( $opt_days ) as $key ) {
			$sel =  $val_days[$key] == $cur_day ? 'selected' : '';
			$result .= '<option value="'.$val_days[$key].'" '.$sel.' >'.$opt_days[$key].'</option>';
		}
		$result .= 	'</select>';

		// Month
		if ( $type == 'Photo' ) {
			$result .= 	'<select name="wppa-month" id="wppa-month-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'month\', this.value)">';
		}
		elseif ( $type == 'Delphoto' ) {
			$result .= 	'<select name="wppa-month" id="wppa-month-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'delmonth\', this.value)">';
		}
		elseif ( $type == 'Album' ) {
			$result .= 	'<select name="wppa-month" id="wppa-month-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'month\', this)">';
		}
		else {
			$result .= 	'<select name="wppa-month" id="wppa-month-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'delmonth\', this)">';
		}

		foreach ( array_keys( $opt_months ) as $key ) {
			$sel =  $val_months[$key] == $cur_month ? 'selected' : '';
			$result .= '<option value="'.$val_months[$key].'" '.$sel.' >'.$opt_months[$key].'</option>';
		}
		$result .= 	'</select>';

		// Year
		if ( $type == 'Photo' ) {
			$result .= 	'<select name="wppa-year" id="wppa-year-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'year\', this.value)">';
		}
		elseif ( $type == 'Delphoto' ) {
			$result .= 	'<select name="wppa-year" id="wppa-year-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'delyear\', this.value)">';
		}
		elseif ( $type == 'Album' ) {
			$result .= 	'<select name="wppa-year" id="wppa-year-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'year\', this)">';
		}
		else {
			$result .= 	'<select name="wppa-year" id="wppa-year-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'delyear\', this)">';
		}

		foreach ( array_keys( $opt_years ) as $key ) {
			$sel =  $val_years[$key] == $cur_year ? 'selected' : '';
			$result .= '<option value="'.$val_years[$key].'" '.$sel.' >'.$opt_years[$key].'</option>';
		}
		$result .= 	'</select>';
		$result .= '<span class="'.$class.'" >@</span>';

		// Hour
		if ( $type == 'Photo' ) {
			$result .= 	'<select name="wppa-hour" id="wppa-hour-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'hour\', this.value)">';
		}
		elseif ( $type == 'Delphoto' ) {
			$result .= 	'<select name="wppa-hour" id="wppa-hour-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'delhour\', this.value)">';
		}
		elseif ( $type == 'Album' ) {
			$result .= 	'<select name="wppa-hour" id="wppa-hour-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'hour\', this)">';
		}
		else {
			$result .= 	'<select name="wppa-hour" id="wppa-hour-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'delhour\', this)">';
		}

		foreach ( array_keys( $opt_hours ) as $key ) {
			$sel =  $val_hours[$key] == $cur_hour ? 'selected' : '';
			$result .= '<option value="'.$val_hours[$key].'" '.$sel.' >'.$opt_hours[$key].'</option>';
		}
		$result .= 	'</select>';
		$result .= '<span class="'.$class.'" >:</span>';

		// Min
		if ( $type == 'Photo' ) {
			$result .= 	'<select name="wppa-min" id="wppa-min-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'min\', this.value)">';
		}
		elseif ( $type == 'Delphoto' ) {
			$result .= 	'<select name="wppa-min" id="wppa-min-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdatePhoto('.$id.', \'delmin\', this.value)">';
		}
		elseif ( $type == 'Album' ) {
			$result .= 	'<select name="wppa-min" id="wppa-min-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'min\', this);">';
		}
		else {
			$result .= 	'<select name="wppa-min" id="wppa-min-'.$id.'" class="'.$class.'" onchange="wppaAjaxUpdateAlbum('.$id.', \'delmin\', this);">';
		}

		foreach ( array_keys( $opt_mins ) as $key ) {
			$sel =  $val_mins[$key] == $cur_min ? 'selected' : '';
			$result .= '<option value="'.$val_mins[$key].'" '.$sel.' >'.$opt_mins[$key].'</option>';
		}
		$result .= 	'</select>';

	}
	else {
		$result .= '<span class="'.$class.'" >'.$cur_day.' '.$opt_months[strval(intval($cur_month))].' '.$cur_year.'@'.$cur_hour.':'.$cur_min.'</span>';
	}

	return $result;
}

// Exactly like php's date(), but corrected for wp's timezone
function wppa_local_date( $format, $timestamp = false ) {

	// Fill in default format if not supplied
	if ( ! $format ) {
		$format = wppa_get_option( 'date_format' ) . ' ' . wppa_get_option( 'time_format' );
	}

	// Fill in default timestamp if not suplied
	if ( $timestamp ) {
		$time = $timestamp;
	}
	else {
		$time = time();
	}

	return wp_date( $format, $time );
}

// Return unix timestamp computed from readable date/time, corrected for timezone.
function wppa_local_strtotime( $str ) {

	// Unix timestamp
	$result = strtotime( $str );

	// Find timezonestring
	$tzstring = wppa_get_option( 'timezone_string' );

	// Correct $time according to gmt_offset
	$current_offset = intval( wppa_get_option( 'gmt_offset', 0 ) ) * 3600;
	$result -= $current_offset;

	return $result;
}

function wppa_get_default_scheduledtm() {

	$result = wppa_local_date( 'Y,m,d,H,i' );

	return $result;
}

function wppa_format_scheduledtm( $sdtm ) {

	$opt_months = array( '0' => '', '1' => __('Jan', 'wp-photo-album-plus' ), '2' => __('Feb', 'wp-photo-album-plus' ), '3' => __('Mar', 'wp-photo-album-plus' ), '4' => __('Apr', 'wp-photo-album-plus' ), '5' => __('May', 'wp-photo-album-plus' ), '6' => __('Jun', 'wp-photo-album-plus' ), '7' => __('Jul', 'wp-photo-album-plus' ), '8' =>__('Aug', 'wp-photo-album-plus' ), '9' => __('Sep', 'wp-photo-album-plus' ), '10' => __('Oct', 'wp-photo-album-plus' ), '11' => __('Nov', 'wp-photo-album-plus' ), '12' => __('Dec', 'wp-photo-album-plus' ) );

	$temp = explode( ',', $sdtm );
	$cur_day 	= $temp[2];
	$cur_month 	= $temp[1];
	$cur_year 	= $temp[0];
	$cur_hour 	= $temp[3];
	$cur_min 	= $temp[4];

	$result = $cur_day.' '.$opt_months[strval(intval($cur_month))].' '.$cur_year.'@'.$cur_hour.':'.$cur_min;

	return $result;
}

function wppa_exif_date_to_wp_date( $exif_date ) {

	$date = date_create_from_format( 'Y:m:d', $exif_date );
	if ( $date ) {
		$result = wppa_get_option( 'date_format' );
		$result = str_replace( 'Y', $date->format( 'Y' ), $result );
		$result = str_replace( 'm', $date->format( 'm' ), $result );
		$result = str_replace( 'd', $date->format( 'd' ), $result );
		$result = str_replace( 'M', $date->format( 'M' ), $result );
		$result = str_replace( 'D', $date->format( 'D' ), $result );
		$result = str_replace( 'j', $date->format( 'j' ), $result );
		$result = str_replace( 'F', $date->format( 'F' ), $result );
	}
	else {
		$result = '';
	}

	return $result;
}
