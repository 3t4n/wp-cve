<?php
//global namespace
namespace {
// legacy function used in templates
// don't add to this add to this all deprecated

if ( ! function_exists( 'eventbrite_event_eb_url' ) ) :
/**
* Give the URL to an event's public viewing page on eventbrite.com.
*
* @return string URL on eventbrite.com
* @deprecated removed from all template 4.5.3
*/
function eventbrite_event_eb_url( $ext = null ) {
return get_post()->url . $ext;
}
endif;

if ( ! function_exists( 'eventbrite_event_start' ) ) :
/**
* Give access to the current event's start time: timezone, local, utc
* @return object Start time properties
* @deprecated removed from all templates 4.5.3
*/
function eventbrite_event_start() {
return apply_filters( 'wfea_eventbrite_event_start', get_post()->start );
}
endif;

if ( ! function_exists( 'eventbrite_event_end' ) ) :
/**
* Give access to the current event's end time: timezone, local, utc
* @return object End time properties
* @deprecated removed from all templates 4.5.3
*/
function eventbrite_event_end() {
return apply_filters( 'wfea_eventbrite_event_end', get_post()->end );
}
endif;

if ( ! function_exists( 'eventbrite_event_time' ) ) :
/**
* Gets event time
* @return bool True if start and end date are the same, false otherwise.
* @deprecated not used in templates as of 4.5.3
*/
function eventbrite_event_time() {
return apply_filters( 'wfea_eventbrite_event_time', wfea_event_time() );
}
endif;


if ( ! function_exists( 'wfea_door_time' ) ) {
/**
* Returns attribute for door time
* @return bool True if start and end date are the same, false otherwise.
* @deprecated not used as of 4.5.3
*/
function wfea_door_time( $args = false ) {
return 'door';
}
}

if ( ! function_exists( 'wfea_event_time' ) ) :
/**
* Return an event's time.
* @return string Event time.
* @deprecated removed from all templates as of 4.5.3
*/
function wfea_event_time( $args = false ) {
// Collect our formats from the admin.
$date_format     = apply_filters( 'wfea_combined_date_time_date_format', get_option( 'date_format' ) . ', ' );
$time_format     = apply_filters( 'wfea_combined_date_time_time_format', get_option( 'time_format' ) );
$combined_format = $date_format . $time_format;

if ( false == $args || $args['show_end_time'] ) {
// Determine if the end time needs the date included (in the case of multi-day events).
$end_time = ( eventbrite_is_multiday_event() )
? mysql2date( $combined_format, eventbrite_event_end()->local )
: mysql2date( $time_format, eventbrite_event_end()->local );
} else {
$end_time = '';
}

// Assemble the full event time string.
$event_time = sprintf(
_x( '%1$s %3$s %2$s', 'Event date and time. %1$s = start time, %2$s = end time %3$s is a separator', 'eventbrite_api' ),
esc_html( mysql2date( $combined_format, eventbrite_event_start()->local ) ),
esc_html( $end_time ),
( empty( $end_time ) ) ? '' : '-'
);

return apply_filters( 'wfea_event_time', $event_time, eventbrite_event_start(), eventbrite_event_end() );
}
endif;

if ( ! function_exists( 'eventbrite_is_multiday_event' ) ) :
/**
* Determine if an event spans multiple calendar days.
* @return bool True if start and end date are the same, false otherwise.
* @deprecated
*/
function eventbrite_is_multiday_event() {
// Set date variables for comparison.
$start_date = mysql2date( 'Ymd', eventbrite_event_start()->local );
$end_date   = mysql2date( 'Ymd', eventbrite_event_end()->local );

// Return true if they're different, false otherwise.
return ( $start_date !== $end_date );
}
endif;

if ( ! function_exists( 'wfea_get_ticket_form_widget_height' ) ) {
/**
* Gets a height
* @return bool True if start and end date are the same, false otherwise.
* @deprecated
*/
function wfea_get_ticket_form_widget_height() {
return 400; // backward compatibility for custom templates
}

}
}