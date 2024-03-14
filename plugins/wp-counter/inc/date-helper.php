<?php
/*
	Name    : Date Helper
	Author  : Md.Harun-Ur-Rashid
	Country : Bangladesh
	Created : 09-01-2015
	Website : https://learn24bd.com
	PHP require: 5.3+
*/

function wp_get_timezone_string() {

	// if site timezone string exists, return it
	if ( $timezone = get_option( 'timezone_string' ) ) {
		return $timezone;
	}

	// get UTC offset, if it isn't set then return UTC
	if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
		return 'UTC';
	}

	// adjust UTC offset from hours to seconds
	$utc_offset *= 3600;

	// attempt to guess the timezone string from the UTC offset
	if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
		return $timezone;
	}

	// last try, guess timezone string manually
	$is_dst = date( 'I' );

	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
				return $city['timezone_id'];
			}
		}
	}

	// fallback to UTC
	return 'UTC';
}

date_default_timezone_set( wp_get_timezone_string() );

define( 'DF', 'Y-m-d' ); // your can change your date format default is 2015-05-30

function getToday() {
	return date( DF );
}

function getYesterday() {
	return date( DF, strtotime( '-1 days' ) );
}

function getCurrent( $type, $date ) {
	switch ( $type ) {
		case 'week':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'saturday this week' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'friday this week' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		case 'month':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'first day of this month' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'last day of this month' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		case 'year':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'first day of January this month' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'last day of December this month' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		default:
			return 'unknown type';
	}
}

function getLast( $type, $date ) {
	switch ( $type ) {
		case 'week':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'saturday last week' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'friday last week' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		case 'month':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'first day of this month' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'last day of this month' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		case 'year':
			switch ( $date ) {
				case 'first':
					return date( DF, strtotime( 'first day of January this month' ) );
					break;
				case 'last':
					return date( DF, strtotime( 'last day of December this month' ) );
				  break;
				default:
					return 'unknown';
			}
			break;
		default:
			return 'unknown type';
	}

}
