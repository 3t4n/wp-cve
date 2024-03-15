<?php
/**
 * check if key/s exists in an array
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'ndc_array_key_exists' ) ) :
    function ndc_array_key_exists( $keys, $array ) {
        foreach( $keys as $key )
            if(!array_key_exists( $key,$array ) ){
                return false;
            }
        return true;
    }
endif;

/**
 * convert english date to nepali
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'ndc_eng_to_nep_date' ) ) :
    function ndc_eng_to_nep_date( $input_date = array(), $date_format = 'nep_char', $format = 'D, F j, Y' ) {
        if( false == ndc_array_key_exists ( array('year','month','day' ),$input_date ) ){
            return sprintf( __( 'Invalid array provided, please pass array in this format: %s', 'nepali-date-converter' ), 'array( "year"=>"2015","month"=>"09","day"=>"11" )' );
        }
        $date_format	= trim($date_format);
        $coder_nepali_calendar = ndc_nepali_calendar();
        return $coder_nepali_calendar->eng_to_nep( $input_date, $date_format, $format );
    }
endif;

/**
 * convert english date to nepali with allow string input
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'ndc_convert_eng_to_nep' ) ) :
    function ndc_convert_eng_to_nep( $input_date_str, $date_format = 'nep_char', $format = 'D, F j, Y' ) {
        $input_date_str	= trim( $input_date_str );
        $date_format	= trim( $date_format );
        $input_date_temp= explode( '-',$input_date_str );

        $input_date_array['year']		= (int) $input_date_temp [0];
        $input_date_array['month']		= (int) $input_date_temp [1];
        $input_date_array['day']		= (int) $input_date_temp [2];

        $coder_nepali_calendar = ndc_nepali_calendar();
        return $coder_nepali_calendar->eng_to_nep( $input_date_array, $date_format, $format );
    }
endif;

/**
 * convert nepali date to english
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'ndc_nep_to_eng_date' ) ) :
    function ndc_nep_to_eng_date( $input_date = array(), $format = 'D, F j, Y' ) {
        if( false == ndc_array_key_exists ( array('year','month','day' ),$input_date ) ){
            return sprintf( __( 'Invalid array provided, please pass array in this format: %s', 'nepali-date-converter' ), 'array( "year"=>"2015","month"=>"09","day"=>"11" )' );
        }
        $coder_nepali_calendar = ndc_nepali_calendar();
        return $coder_nepali_calendar->nep_to_eng( $input_date, $format );
    }
endif;

/**
 * convert english date to nepali with allow string input
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'ndc_convert_nep_to_eng' ) ) :
    function ndc_convert_nep_to_eng( $input_date_str, $format = 'D, F j, Y' ) {
        $input_date_str	= trim( $input_date_str );
        $input_date_temp= explode( '-',$input_date_str );

        $input_date_array['year']		= (int) $input_date_temp [0];
        $input_date_array['month']		= (int) $input_date_temp [1];
        $input_date_array['day']		= (int) $input_date_temp [2];

        $coder_nepali_calendar = ndc_nepali_calendar();
        return $coder_nepali_calendar->nep_to_eng( $input_date_array, $format );
    }
endif;

/**
 * eng_to_nep_date
 * @since NDC 1.1.0
 * @deprecated 2.0.0 Use ndc_eng_to_nep_date()
 *
 */
if ( ! function_exists( 'eng_to_nep_date' ) ) :
	function eng_to_nep_date( $input_date = array(), $date_format = 'nep_char', $format = 'D, F j, Y' ) {
		_deprecated_function( __FUNCTION__, '2.0.0', 'eng_to_nep_date ()' );

		return ndc_eng_to_nep_date( $input_date, $date_format, $format );

	}
endif;

/**
 * convert_eng_to_nep
 * @since NDC 1.1.0
 * @deprecated 2.0.0 Use ndc_convert_eng_to_nep()
 *
 */
if ( ! function_exists( 'convert_eng_to_nep ' ) ) :
	function convert_eng_to_nep ( $input_date_str, $date_format = 'nep_char', $format = 'D, F j, Y'  ) {
		_deprecated_function( __FUNCTION__, '2.0.0', 'convert_eng_to_nep  ()' );

		return ndc_convert_eng_to_nep( $input_date_str, $date_format, $format );

	}
endif;

/**
 * convert english date to nepali
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'nep_to_eng_date  ' ) ) :
	function nep_to_eng_date ( $input_date = array(), $format = 'D, F j, Y'  ) {
		_deprecated_function( __FUNCTION__, '2.0.0', 'nep_to_eng_date   ()' );

		return ndc_nep_to_eng_date( $input_date, $format );

	}
endif;


/**
 * convert english date to nepali
 *
 * @since NDC 1.1.0
 */
if ( ! function_exists( 'convert_nep_to_eng   ' ) ) :
	function convert_nep_to_eng  ( $input_date_str, $format = 'D, F j, Y'  ) {
		_deprecated_function( __FUNCTION__, '2.0.0', 'convert_nep_to_eng    ()' );

		return ndc_convert_nep_to_eng( $input_date_str, $format );

	}
endif;

/**
 * ndc_human_time_diff
 * copied from human_time_diff
 * https://developer.wordpress.org/reference/functions/human_time_diff/
 * 
 * @since NDC 2.0.1
 */
function ndc_human_time_diff( $from, $to = 0 ) {
	if ( empty( $to ) ) {
		$to = time();
	}

	$diff = (int) abs( $to - $from );

	if ( $diff < MINUTE_IN_SECONDS ) {
		$secs = $diff;
		if ( $secs <= 1 ) {
			$secs = 1;
		}
		/* translators: Time difference between two dates, in seconds. %s: Number of seconds. */
		$since = sprintf( _n( '%s सेकेन्ड ', '%s सेकेन्ड', $secs ), $secs );
	} elseif ( $diff < HOUR_IN_SECONDS && $diff >= MINUTE_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 ) {
			$mins = 1;
		}
		/* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes. */
		$since = sprintf( _n( '%s मिनेट', '%s मिनेट', $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 ) {
			$hours = 1;
		}
		/* translators: Time difference between two dates, in hours. %s: Number of hours. */
		$since = sprintf( _n( '%s घण्टा', '%s घण्टा', $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 ) {
			$days = 1;
		}
		/* translators: Time difference between two dates, in days. %s: Number of days. */
		$since = sprintf( _n( '%s दिन', '%s दिन', $days ), $days );
	} elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 ) {
			$weeks = 1;
		}
		/* translators: Time difference between two dates, in weeks. %s: Number of weeks. */
		$since = sprintf( _n( '%s हप्ता', '%s हप्ता', $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
		$months = round( $diff / MONTH_IN_SECONDS );
		if ( $months <= 1 ) {
			$months = 1;
		}
		/* translators: Time difference between two dates, in months. %s: Number of months. */
		$since = sprintf( _n( '%s महिना', '%s महिना', $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 ) {
			$years = 1;
		}
		/* translators: Time difference between two dates, in years. %s: Number of years. */
		$since = sprintf( _n( '%s वर्ष', '%s वर्ष', $years ), $years );
	}

	/*Convert num to Nepali*/
	$since = strtr( $since, ndc_nepali_calendar()->eng_nep_num );

	/**
	 * Filters the human readable difference between two timestamps.
	 *
	 * @since 4.0.0
	 *
	 * @param string $since The difference in human readable text.
	 * @param int    $diff  The difference in seconds.
	 * @param int    $from  Unix timestamp from which the difference begins.
	 * @param int    $to    Unix timestamp to end the time difference.
	 */
	return apply_filters( 'ndc_human_time_diff', $since, $diff, $from, $to );
}


/**
 * Get Post Types.
 *
 * @since 2.1.0
 */
function ndc_get_post_types( $args = array( 'public' => true ), $excludes = array(), $includes = array() ) {

	$post_types = get_post_types(
		$args,
		'objects'
	);
	$exclude_pt = array(
		'edd_wish_list',
		'elementor_library'
	);
	$exclude_pt = array_unique( array_merge( $exclude_pt, $excludes ) );
	$exclude_pt = array_diff( $exclude_pt, $includes );
	$exclude_pt = apply_filters( 'ndc_get_post_types', $exclude_pt, $excludes, $includes );

	$options = array();
	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type->name, $exclude_pt ) ) {
			continue;
		}
		$options[] = array(
			'value' => $post_type->name,
			'label' => $post_type->label,
		);
	}
	return $options;
}