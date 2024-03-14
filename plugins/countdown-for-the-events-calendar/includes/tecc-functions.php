<?php
function tecc_get_output( $event, $settings, $event_ID, $autostart ) {
	 $ret           = '';
	 $hourformat    = tecc_generate_countdown_html( $event, $settings, $event_ID );
	 $eventend_msz  = '';
	 $eventstart_msz = '';
	 $autostart_msz = '';
	 $image = array_key_exists('show-image', $settings) ? $settings['show-image'] : "no";
	 $main_title    = isset( $settings['main-title'] ) && ! empty( $settings['main-title'] ) ? $settings['main-title'] : __( 'Next Upcoming Event', 'tecc' );
	 $autostart_msz = isset( $settings['autostart-text'] ) && ! empty( $settings['autostart-text'] ) ? $settings['autostart-text'] : __( 'Event Starts refresh page to see next upcoming event', 'tecc' );

	if ( isset( $settings['event-end'] ) ) {
		$eventend_msz = $settings['event-end'];
	}

	if ( $autostart == 'yes' ) {
		   $eventstart_msz = $autostart_msz;
	} else {
		if ( isset( $settings['event-start'] ) ) {
			$eventstart_msz = $settings['event-start'];
		}
	}
		  // Get the event start date and end date.
		  $startdate           = tribe_get_start_date( $event, false, Tribe__Date_Utils::DBDATETIMEFORMAT );
		  $enddate             = tribe_get_end_date( $event, false, Tribe__Date_Utils::DBDATETIMEFORMAT );
		  $start_date_formated = tribe_get_start_date( $event_ID, false, 'd F Y' );

		  // Get the number of seconds remaining
		  $seconds     = strtotime( $startdate ) - current_time( 'timestamp' );
		  $endseconds  = current_time( 'timestamp' ) - strtotime( $enddate );
		  $link        = tribe_get_event_link( $event );
		  $event_venue = tribe_get_venue_details( $event_ID );

		  $ret .= '
		<div class="tecc-wrapper" id="tecc-' . $event_ID . '">
			<div class="tecc-event-info">';
	if ( $seconds > 0 ) {
		   $ret .= '<h2 class="tecc-up-event">' . $main_title . '</h2>';
	}

	if ( $image === 'yes' && tribe_event_featured_image($event_ID)) {
		$image = tribe_event_featured_image( $event_ID, 'full', false );
		$ret .= '<div class="tecc-image-wrapper">';
		$ret .= $image;	
		$ret .= '</div>';
	}
			 $ret .= '<a href="' . esc_url( $link ) . '"><h3 class="tecc-title">' . esc_attr( $event->post_title ) . '</h3></a>
				<div class="event-date-location">
				<span class="tecc-date">' . $start_date_formated . '</span>';
	if ( is_array( $event_venue ) ) {
		$strip_addr = preg_replace( '/\s+/', '', $event_venue['address'] );
		$trim_addr  = trim( preg_replace( '/\s+/', '', $strip_addr ) );
		$address    = strip_tags( $trim_addr );
		if ( $address != '' ) {
			$ret .= '<span class="tecc-location"> -</span>' . $event_venue['address'] . '';
		}
	}

			 $ret .= '
				</div>
			</div>
			<div class="tecc-timer-wrapper">
				<div class="tecc-date-timer">';
	if ( $seconds > 0 ) {
		$ret .= tecc_generate_countdown_output( $seconds, $hourformat, $event, $eventstart_msz );
	} elseif ( $endseconds >= 0 ) {
		$ret .= '<div class="eventend_msz">' . $eventend_msz . '</div>';
	} elseif ( $seconds <= 0 ) {
		$ret .= '<div class="eventstart_msz">' . $eventstart_msz . '</div>';
	}
			 $ret .= '
				</div>
			</div>
			<div class="tecc-event-detail">
				<a class="tecc-event-button" href="' . esc_url( $link ) . '">' . __( 'Find out more', 'tecc' ) . '</a>
			</div>
		</div>';
			 return $ret;
}

function tecc_generate_countdown_output( $seconds, $hourformat, $event, $eventstart_msz ) {
		 $output = '';

	if ( $event ) {
		$output .= '<div class="tec-countdown-timer-html">
				<span class="tecc-seconds-section">' . $seconds . '</span>
				<span class="tecc-countdown-format">' . $hourformat . '</span>
				<span class="tecc-countdown-complete">' . $eventstart_msz . '</h3>
			</div>';

	}
		return $output;
}

function tecc_generate_countdown_html( $event, $settings, $event_ID ) {
	$tec_html = '';

	$bg_color     = ! empty( $settings['backgroundcolor'] ) ? $settings['backgroundcolor'] : '#4395cb';
	$font_color   = ! empty( $settings['font-color'] ) ? $settings['font-color'] : '#ffffff';
	$show_seconds = ! empty( $settings['show-seconds'] ) ? $settings['show-seconds'] : 'yes';
	$box_size     = ! empty( $settings['size'] ) ? $settings['size'] : 'large';

	wp_enqueue_style(
		'custom-style',
		TECC_CSS_URL . '/countdown.css',
		array(),
		TECC_VERSION_CURRENT,
		null,
		'all'
	);

		$custom_css = "
				.tecc-wrapper#tecc-$event_ID .tec-countdown-timer .tecc-section{
					color: {$font_color};
					background: {$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .tecc-event-detail a.tecc-event-button{
					color: {$font_color};
					background: {$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .tecc-event-info h3.tecc-title{
					color:{$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .tecc-event-info h2.tecc-up-event{
					color:{$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .event-date-location {
					color:{$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .eventstart_msz,.tecc-wrapper#tecc-$event_ID .eventend_msz{
					color:{$bg_color};
				}
				.tecc-wrapper#tecc-$event_ID .tecc-countdown-complete{
					color:{$bg_color};
				}	
				";

				wp_add_inline_style( 'custom-style', $custom_css );
	$tec_html .= '				
			<div class="tec-countdown-timer tec-' . $box_size . '-box">
			
				<div class="tecc-section tecc-days-section">
					<span class="tecc-amount">DD</span>
					<span class="tecc-word">' . __( 'days', 'tecc' ) . '</span>
				</div>
				<div class="tecc-section tecc-hours-section">
					<span class="tecc-amount">HH</span>
					<span class="tecc-word">' . __( 'hours', 'tecc' ) . '</span>
				</div>
				<div class="tecc-section tecc-minutes-section">
					<span class="tecc-amount">MM</span>
					<span class="tecc-word">' . __( 'min', 'tecc' ) . '</span>
				</div>';

	if ( $show_seconds == 'yes' ) {

		$tec_html .= '
				<div class="tecc-section tecc-seconds-section">
					<span class="tecc-amount">SS</span>
					<span class="tecc-word">' . __( 'sec', 'tecc' ) . '</span>
				</div>';

	}
		$tec_html .= '</div>';
	return $tec_html;
}

