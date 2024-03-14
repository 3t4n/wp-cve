<?php

// ==========================================================
// UTILITY FUNCTIONS
// ==========================================================

/**
 * Checks if the current version of WP against supplied version.
 * @param string $version The version to compare against.
 * @param string $comparison Optional. The comparison operator.
 * @return bool True if the comparison between supplied version and current version is valid.
 */
function autoship_supported_wp_version( $version, $comparison = '>=' ){

  global $wp_version;
  return version_compare( $wp_version, $version , '>=' );

}

/* Array Utilities
================================ */

/**
 * Utility function for converting a stdClass object to array
 * @param stdClass $object a stdClass object.
 * @return array   The converted version.
 */
function autoship_convert_object_to_array($object) {
  if (is_object($object)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $object = get_object_vars($object);
  }

  if (is_array($object)) {
      /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
      return array_map(__FUNCTION__, $object);
  }
  else {
      // Return array
      return $object;
  }
}

/**
 * Inserts a new key/value before the key in the array.
 *
 * @param $key The key to insert before.
 * @param $array An array to insert in to.
 * @param $new_key The key to insert.
 * @param $new_value An value to insert.
 *
 * @return array|bool The new array if the key exists, FALSE otherwise.
 */
function autoship_array_insert_before($key, array &$array, $new_key, $new_value) {
  if (array_key_exists($key, $array)) {
    $new = array();
    foreach ($array as $k => $value) {
      if ($k === $key) {
        $new[$new_key] = $new_value;
      }
      $new[$k] = $value;
    }
    return $new;
  }
  return FALSE;
}

/**
 * Inserts a new key/value after the key in the array.
 *
 * @param $key The key to insert after.
 * @param $array An array to insert in to.
 * @param $new_key The key to insert.
 * @param $new_value An value to insert.
 *
 * @return array|bool The new array if the key exists, FALSE otherwise.
 */
function autoship_array_insert_after($key, array &$array, $new_key, $new_value) {
  if (array_key_exists($key, $array)) {
    $new = array();
    foreach ($array as $k => $value) {
      $new[$k] = $value;
      if ($k === $key) {
        $new[$new_key] = $new_value;
      }
    }
    return $new;
  }
  return FALSE;
}

/**
 * Recursively merges two arrays - mimics wp_parse_args for
 * multi-dimensional arrays
 *
 * @param $key The key to insert after.
 * @param $array An array to insert in to.
 * @param $new_key The key to insert.
 * @param $new_value An value to insert.
 *
 * @return array|bool The new array if the key exists, FALSE otherwise.
 */
function autoship_arrays_wp_parse_args( &$a, $b ) {

  $a = (array) $a;
  $b = (array) $b;
  $result = $b;

  foreach ( $a as $k => &$v ) {

    if ( is_array( $v ) && isset( $result[ $k ] ) ) {

      $result[ $k ] = autoship_arrays_wp_parse_args( $v, $result[ $k ] );

    } else {

      $result[ $k ] = $v;

    }

  }

  return $result;

}

/* Date Time Utilities
================================ */

/**
 * Returns the next occurrence format used throughout the cart
 *
 * @return string The format
 */
function autoship_get_cart_next_occurrence_format(){
  return apply_filters( 'autoship_cart_next_occurrence_format', "Y-m-d" );
}

/**
 * Returns the next occurrence format required by the API
 *
 * @return string The format
 */
function autoship_get_api_next_occurrence_format(){
  return "Y-m-d\TH:i:s.v\Z";
}

/**
 * Returns the current merchants timezone offset
 *
 * @return int The timezone offset
 */
function autoship_get_local_timezone_offset(){

  $offset = get_option( 'gmt_offset' );
  return empty( $offset ) ? 0 : $offset;

}

/**
 * Returns the current merchants timezone
 *
 * @return DateTimeZone A DateTimeZone object
 */
function autoship_get_local_timezone(){

  if ( autoship_supported_wp_version( '5.3' ) && function_exists( 'wp_timezone' ) )
  return wp_timezone();

  $timezone = get_option( 'timezone_string' );
  return ! empty( $timezone ) ? new DateTimeZone( $timezone ) : new DateTimeZone( autoship_get_local_timezone_offset() );

}

/**
 * Returns the DateTme object in local TimeZone from the Autoship formatted date.
 *
 * @param string|DateTime $date The date string to convert or DateTime object to use.
 * @return DateTime|bool  The DateTime object in local timezone or false for invalid.
 */
function autoship_get_local_date( $date ){

  // Get the Date Time object if not passed through
  if ( !( $date instanceof DateTime ) )
  $date = DateTime::createFromFormat('U', strtotime( $date ) );

  // Set to the local timezone
  if ( $date )
  $date->setTimezone( autoship_get_local_timezone() );

  return $date;

}

/**
 * Returns the local timezone formatted date from the Autoship UTC formatted date.
 *
 * @param string|DateTime $date The date string to convert or DateTime object to use.
 * @return string formatted a date based on the offset timestamp
 */
function autoship_get_formatted_local_date( $date, $format = "" ){

  if ( empty( $format ) )
  $format = wc_date_format();

  // Set to the local timezone
  $date = autoship_get_local_date( $date );

  // Now return the value based on if it's for a form input or display
  return $date->format( $format );

}

/**
 * Returns the datetime object from the supplied params.
 *
 * @param string $date  The date string to convert.
 *                      If empty current date time returned.
 * @return DateTime object.
 */
function autoship_get_datetime ( $date = '', $from_format = '', $timezone = NULL ){

  if ( empty( $from_format ) )
  $from_format = autoship_get_api_next_occurrence_format();

  if ( empty( $timezone ) ) {
    $timezone = new DateTimeZone( "UTC" );
  } else if ( !( $timezone instanceof DateTimeZone ) ){
    $timezone = new DateTimeZone( $timezone );
  }

  if ( empty( $date ) ) {
    $dateobject = new DateTime( 'now' , $timezone );
  } else {
    $dateobject = DateTime::createFromFormat( $from_format, $date, $timezone );
  }

  return $dateobject;

}

/**
 * Returns the required api formatted date for Autoship.
 * DateTime in UTC timezone
 *
 * @param string $date        The date string to convert.
 * @param string $from_format The format the date string supplied is in. Default NULL //Y-m-d'
 * @param string $to_format   Optional. The format to return
 *
 * @return DateTime|string|bool    The DateTime object in UTC else a string in UTC
 */
function autoship_get_utc_datetime ( $date = NULL, $from_format = NULL, $to_format = '' ){

  if ( !$date || empty( $date ) ){

    $date = new DateTime( "now", new DateTimeZone('UTC') );

  } else {

    // Get the Date Time object if not passed through
    if ( !( $date instanceof DateTime ) )
    $date = isset( $from_format ) ?
    DateTime::createFromFormat( $from_format, $date, autoship_get_local_timezone() ) :
    DateTime::createFromFormat( 'U', strtotime( $date ), autoship_get_local_timezone() );

    if ( !$date )
    return false;

    $date->setTimezone( new DateTimeZone('UTC') );

  }

  return empty( $to_format ) ? $date : $date->format( $to_format );

}

/**
 * Adjusts the supplied DateTime object to fit within the site processing window.
 *
 * @param DateTime $date        The DateTime object in UTC zone to adjust.
 * @param string   $format      Optional. The format to return.
 *
 * @return string|DateTime      The formatted string if format supplied else object.
 */
function autoship_force_datetime_into_processing_window ( $date, $format = NULL ){

  // Get the processing window
  if ( $window = autoship_get_api_processing_window() ){

    // Processing window only cares about hours
    $time         = $date->format('H:i:s');
    $time_string  = strtotime( $time );
    $time         = explode( ':', $time );

    // If the Processing Window startTime isn't set then 00:00:00 start
    $min          = isset( $window['startTime'] ) ? $window['startTime'] : '00:00:00';
    $min_string   = strtotime( $min );

    // If the Processing Window endTime isn't set then 23:59:59 end
    $max          = isset( $window['endTime'] ) ? $window['endTime'] : '24:00:00';
    $max_string   = strtotime( $max );

    // If Next Occurrence time is earlier than start time or later then end time
    // we need to adjust it so we set it to the start time.
    if ( $time_string < $min_string || $time_string > $max_string )
    $time = explode( ':', $min );

    // Allow Devs to change the time processing times are adjusted to.
    $time = apply_filters( 'autoship_new_adjusted_processing_window_time', $time, $date, $window );
    $date->setTime( $time[0], $time[1], $time[2] );

  }

  return isset( $format ) ? $date->format( $format ) : $date;

}

/**
 * Returns the required api formatted date for Autoship.
 * DateTime in UTC timezone
 *
 * @param string $localdate     The local timezone date string to convert.
 * @param string $date_format   The format the date string supplied is in. Default NULL
 * @param bool   $force_ptimee  When set to True it validates and adjusts the
 *                              time component to be in processing window
 *
 * @return string|bool false if the supplied date is invalid else the formatted version of the date.
 */
function autoship_get_api_formatted_date ( $localdate = NULL, $date_format = NULL, $force_ptime = true ){

  // Get the DateTime object in UTC from the supplied date
  $order_date = autoship_get_utc_datetime( $localdate, $date_format );

  if ( !$order_date )
  return false;

  if( $force_ptime )
  $order_date = autoship_force_datetime_into_processing_window ( $order_date );

  return $order_date->format( autoship_get_api_next_occurrence_format() );

}


/**
 * Returns the name of the day of the week based off index.
 * @param int The index for the day ( 1 - 7 )
 * @return string|false The name of the day otherwise false
 */
function autoship_get_day_of_week ( $index ){
  $days = apply_filters( 'autoship_day_of_week_index', array(
    'sunday',
    'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday',
    'saturday',
  ) );

  return isset( $days[$index-1] ) ? $days[$index-1] : false;
}

/**
 * Returns the valid Relative Next Occurrence Types
 *
 * @return array The valid types and labels
 */
function autoship_valid_relative_next_occurrence_types (){
  return apply_filters( 'autoship_relative_next_occurrence_types', array(
    'Days'          => __('Days', 'autoship'),
    'Weeks'         => __('Weeks', 'autoship'),
    'Months'        => __('Months', 'autoship'),
    'DayOfTheWeek'  => __('Day of the week', 'autoship'),
    'DayOfTheMonth' => __('Day of the month', 'autoship')
  ) );
}

/**
 * Checks if the supplied Relative Next Occurrence Type is valid
 * @param string $type The type to check
 * @return bool True if valid else false.
 */
function autoship_is_valid_relative_next_occurrence_type( $type ){

  // Get valid Relative Occurrence Types
  $types = autoship_valid_relative_next_occurrence_types();

  // Normalize both the look up and the array
  $types = array_change_key_case( $types );
  $type = strtolower( $type );

  return isset( $types[$type] );

}

/**
 * Returns the calculated Relative Next Occurrence Date
 *
 * @param int      $value The period amount
 * @param string   $option The type of period
 * @param DateTime $basedate The datetime object to calculate from.
 * @param string   $format The format to return
 *
 * @return bool|string formatted version of the date in Y-m-d H:i:s. False if invalid.
*/
function autoship_calculate_relative_next_occurrence_date ( $value, $option , $basedate = NULL, $format = "" ){

  if ( empty( $format ) )
  $format = autoship_get_cart_next_occurrence_format();

  if ( empty( $basedate ) )
  $basedate = new DateTime();

  if ( !autoship_is_valid_relative_next_occurrence_type( $option ) )
  return false;

  switch ( strtolower( $option ) ) {
    case 'days':
      $basedate->add(new DateInterval("P{$value}D"));
      break;
    case 'weeks':
      $basedate->add(new DateInterval("P{$value}W"));
      break;
    case 'months':
      $basedate->add(new DateInterval("P{$value}M"));
      break;
    case 'dayoftheweek':

      $dayofweek = autoship_get_day_of_week( $value );
      if ( false === $dayofweek )
      return false;

      // Check if the Day of the Week is same as basedate
      if ( $dayofweek == strtolower( $basedate->format('l') ) ){
        $basedate->add(new DateInterval("P1W"));

      // Check if the Day of the Week selected is past or future
      } else {

        $dayofweekdatetime = clone $basedate;
        $dayofweekdatetime->modify("this week {$dayofweek}");
        $time = explode(':', $basedate->format('H:i:s') );
        $dayofweekdatetime->setTime( $time[0], $time[1], $time[2] );

        if ( $dayofweekdatetime < $basedate )
        $dayofweekdatetime->add(new DateInterval("P1W"));

        $basedate = $dayofweekdatetime;

      }

      break;
    case 'dayofthemonth':

      $value--;

      if ( $value < 0 || $value > 31 )
      return false;

      $thismonth = clone $basedate;
      $nextmonth = clone $basedate;
      $thismonth->modify('first day of this month')->modify("+{$value} day");

      $time = explode(':', $basedate->format('H:i:s') );
      $thismonth->setTime( $time[0], $time[1], $time[2] );

      $nextmonth->modify('first day of next month')->modify("+{$value} day");
      $nextmonth->setTime( $time[0], $time[1], $time[2] );

      $basedate =  $basedate < $thismonth ? $thismonth : $nextmonth;
      break;

    default:
      // Custom non-standard option
      return apply_filters('autoship_calculate_non_standard_relative_next_occurrence_date', false, $value, $option , $basedate );
      break;
  }

  return $basedate->format( $format );

}

/**
 * Formats the Next Occurrence to the required Y-m-d\TH:i:s format
 *
 * @param string     $next_occurrence The next occurrence date string.
 * @param string     $next_occurrence_format The next occurrence date format.
 *
 * @return string    The next occurrence in Y-m-d\TH:i:s format
 */
function autoship_format_next_occurrence_for_save( $next_occurrence, $next_occurrence_format = "" ){

  if ( empty( $next_occurrence_format ) )
  $next_occurrence_format = autoship_get_cart_next_occurrence_format();

  $date = DateTime::createFromFormat( $next_occurrence_format, $next_occurrence );
  return autoship_force_datetime_into_processing_window ( $date, autoship_get_api_next_occurrence_format() );

}

/**
 * Formats the Next Occurrence to display
 *
 * @param string     $next_occurrence The next occurrence date string.
 * @param string     $next_occurrence_format The next occurrence date format.
 *
 * @return string    The next occurrence in local date time and format determined by WP settings
 */
function autoship_format_next_occurrence_for_display( $next_occurrence, $next_occurrence_format = '' ){

  if ( empty( $next_occurrence_format ) )
  $next_occurrence_format = autoship_get_cart_next_occurrence_format();

  $display_format = apply_filters('autoship_next_occurrence_display_format', get_option( 'date_format' ) );

  $date = DateTime::createFromFormat( $next_occurrence_format, $next_occurrence );
  $date->setTimezone( autoship_get_local_timezone() );

  return $date->format( $display_format );

}

/* Frequency Utilities
================================ */

/**
 * Returns the required api QPilot formatted frequency array for Product and Site data.
 *
 * @param array $frequencies The frequency array to convert.
 * @return array The formatted array.
 */
function autoship_get_api_formatted_frequencies_data ( $frequencies ){

  if ( empty( $frequencies ) )
  return NULL;

  $data = array();
  foreach ($frequencies as $key => $value) {
    $frequency = array();
    $frequency["type"]        = $value['frequency_type'];
    $frequency["value"]       = $value['frequency'];
    $frequency["displayName"] = $value['display_name'];
    $data[] = $frequency;
  }

  return $data;

}

/**
* Retrieves the frequency int from the name.
* @param string The frequency type
* @return int The frequency type id.
*/
function autoship_get_frequencytype_int ( $frequency_type ){

  $ids = array(
    'Days' => 0,
    'Weeks' => 1,
    'Months' => 2,
    'DayOfTheWeek' => 3,
    'DayOfTheMonth' => 4,
  );

  return isset( $ids[$frequency_type] ) ? $ids[$frequency_type] : false;

}

/**
 * Retrieves the custom Display name from the Options
 *
 * @param string $frequency_type The frequency type
 * @param int $frequency The frequency
 * @param array $options The current frequency options.
 * @return string The display name.
 */
function autoship_search_for_frequency_display_name( $frequency_type, $frequency, $options ){

  if ( isset( $options[$frequency_type.'-'.$frequency] ) )
  return $options[$frequency_type.'-'.$frequency]['display_name'];

  foreach ($options as $key => $value) {

    if ( ( $frequency_type == $value['frequency_type'] ) && ( $frequency == $value['frequency'] ) )
    return $value['display_name'];

  }

  // Not Found so get the default
  return autoship_get_frequency_display_name( $frequency_type, $frequency );

}

/**
 * Retrieves the Display name for the Frequency and Frequency Type combination
 *
 * @param string $frequency_type The frequency type
 * @param int $frequency The frequency
 * @return string The display name.
 */
function autoship_get_frequency_display_name( $frequency_type, $frequency ) {
	$display_name = '';
	switch ( $frequency_type ) {
		case 'Days': {
			$display_name = sprintf( __( "Every %d days", 'autoship' ), $frequency );
			break;
		}
		case 'Weeks': {
			$display_name = sprintf( __( "Every %d weeks", 'autoship' ), $frequency );
			break;
		}
		case 'Months': {
			$display_name = sprintf( __( "Every %d months", 'autoship' ), $frequency );
			break;
		}
		case 'DayOfTheWeek': {
			if ( $frequency < 8 ) {
				$days = array(
					__( 'Sunday', 'autoship' ),
					__( 'Monday', 'autoship' ),
					__( 'Tuesday', 'autoship' ),
					__( 'Wednesday', 'autoship' ),
					__( 'Thursday', 'autoship' ),
					__( 'Friday', 'autoship' ),
					__( 'Saturday', 'autoship' )
				);
				$day = $days[ $frequency - 1 ];
				$display_name = sprintf( __( "Every %s", 'autoship'), $day );
				break;
			}
		}
		case 'DayOfTheMonth': {
			if ( ( ( $frequency % 100 ) >= 11 ) && ( ( $frequency % 100 ) <= 13 ) ) {
				$display_name = sprintf( __( "The %dth of every month", 'autoship' ), $frequency );
				break;
			} else {
				$suffixes = array('th','st','nd','rd','th','th','th','th','th','th');
				$display_name =  sprintf( __( "The %d%s of every month", 'autoship' ), $frequency, $suffixes[$frequency % 10] );
				break;
			}
		}
		default: {
			$display_name = sprintf( __( "%s:%d", 'autoship' ), $frequency_type, $frequency );
		}
	}

	return apply_filters( 'autoship_frequency_display_name', $display_name, $frequency_type, $frequency );
}

/* Template Inclusion Utilities
================================ */

/**
 * Include a template
 * @param string $template The template file to include
 * @param array $vars An Array of variables and values to make available to the template.
 */
function autoship_include_template( $template, $vars = array() ) {
	// Default vars
	$prefix = 'autoship_';
	// Custom vars
	extract( $vars );
	// Find theme template
	$theme_template = apply_filters( 'autoship_theme_template',
		get_stylesheet_directory() . '/'.Autoship_Plugin_Folder_Name.'/templates/' . $template . '.php',
		$template,
		$vars
	);
	if ( file_exists( $theme_template ) ) {
		// Include theme template
		include ( $theme_template );
	} else {
		// Include plugin template
		$plugin_template = apply_filters( 'autoship_plugin_template',
			Autoship_Plugin_Dir . '/templates/' . $template . '.php',
			$template,
			$vars
		);
		include( $plugin_template );
	}
}

/**
 * Render a template
 * @see autoship_include_template()
 * @param string $template The template file to include
 * @param array $vars An Array of variables and values to make available to the template.
 */
function autoship_render_template( $template, $vars = array() ) {
	autoship_include_template( $template, $vars );
}
