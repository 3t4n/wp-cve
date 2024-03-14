<?php
// Builds the ICS files
function qem_ics_button( $id, $label ) {
	$cal_ics_svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             x="0px" y="0px" viewBox="0 0 512 512"
             width="24px" height="24px"
             xml:space="preserve">
<path d="M160,240v32c0,8.844-7.156,16-16,16h-32c-8.844,0-16-7.156-16-16v-32c0-8.844,7.156-16,16-16h32  C152.844,224,160,231.156,160,240z M144,352h-32c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h32c8.844,0,16-7.156,16-16v-32  C160,359.156,152.844,352,144,352z M272,224h-32c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h32c8.844,0,16-7.156,16-16v-32  C288,231.156,280.844,224,272,224z M272,352h-32c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h32c8.844,0,16-7.156,16-16v-32  C288,359.156,280.844,352,272,352z M400,224h-32c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h32c8.844,0,16-7.156,16-16v-32  C416,231.156,408.844,224,400,224z M400,352h-32c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h32c8.844,0,16-7.156,16-16v-32  C416,359.156,408.844,352,400,352z M112,96h32c8.844,0,16-7.156,16-16V16c0-8.844-7.156-16-16-16h-32c-8.844,0-16,7.156-16,16v64  C96,88.844,103.156,96,112,96z M512,128v320c0,35.344-28.656,64-64,64H64c-35.344,0-64-28.656-64-64V128c0-35.344,28.656-64,64-64  h16v16c0,17.625,14.359,32,32,32h32c17.641,0,32-14.375,32-32V64h160v16c0,17.625,14.375,32,32,32h32c17.625,0,32-14.375,32-32V64  h16C483.344,64,512,92.656,512,128z M480,192c0-17.625-14.344-32-32-32H64c-17.641,0-32,14.375-32,32v256c0,17.656,14.359,32,32,32  h384c17.656,0,32-14.344,32-32V192z M368,96h32c8.844,0,16-7.156,16-16V16c0-8.844-7.156-16-16-16h-32c-8.844,0-16,7.156-16,16v64  C352,88.844,359.156,96,368,96z"/>
            ';
    $url = wp_nonce_url(admin_url( 'admin-ajax.php?action=qem_download_ics&id=' . $id ), 'qem_download_ics_button' );
	return '<h4><a  style="display: inline-flex;align-items: center; margin-right: 10px;" href="' .
	       esc_url($url) . '" target="_blank">' . $cal_ics_svg
	       . '<span style="margin-left: 3px;">' . esc_html( $label ) . '</span>' . '</a></h4>';

}

function qem_download_ics() {
	if ( isset( $_GET['id'] ) ) {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'qem_download_ics_button' ) ) {
			wp_die( esc_html__( 'Invalid Nonce, sorry something went wrong', 'quick-event-manager' ) );
		}
		$post = get_post( (int) $_GET['id'] );

		header( 'Content-Type: text/calendar' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: filename="' . $post->post_title . '.ics' . '"' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp strip all tags is an escaping function
		echo wp_strip_all_tags( qem_ics( $post ) );
		exit;

	}

	return false;
}

function qem_ics( $post ) {
	// @TODO this will use local time but if your calandar is different to site it will be wrong
	// add Z to dates and convert to UTC
	$display    = event_get_stored_display();
	$summary    = $post->post_title;
	$eventstart = get_post_meta( $post->ID, 'event_date', true );
	if ( ! $eventstart ) {
		$eventstart = time();
	}
	$start       = get_post_meta( $post->ID, 'event_start', true );
	$date        = date( 'Ymd\T', $eventstart );
	$time        = qem_time( $start );
	$time        = date( 'His', $time );
	$datestart   = $date . $time;
	$dateend     = get_post_meta( $post->ID, 'event_end_date', true );
	$address     = get_post_meta( $post->ID, 'event_address', true );
	$url         = get_permalink();
	$description = get_post_meta( $post->ID, 'event_desc', true );
	$filename    = $post->post_title . '.ics';
	if ( ! $dateend ) {
		$dateend = $eventstart;
		$finish  = get_post_meta( $post->ID, 'event_finish', true );
		$date    = date( 'Ymd\T', $eventstart );
		$time    = qem_time( $finish );
		$time    = date( 'His', $time );
		$dateend = $date . $time;
	} else {
		$finish  = get_post_meta( $post->ID, 'event_finish', true );
		$date    = date( 'Ymd\T', $dateend );
		$time    = qem_time( $finish );
		$time    = date( 'His', $time );
		$dateend = $date . $time;
	}

	$ics = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
UID:' . uniqid() . '
DTSTAMP:' . dateToCal( time() ) . '
DTSTART:' . $datestart . '
DTEND:' . $dateend . '
LOCATION:' . $address . '
DESCRIPTION:' . $description . '
URL;VALUE=URI:' . $url . '
SUMMARY:' . $summary . '
END:VEVENT
END:VCALENDAR';

	return $ics;
}