<?php
/**
 * Handles webhooks from Eventbrite
 * Is standalone, not part of the plugin for performance reasons as eb can send many webhooks at once
 * Queues webhooks into flat files ready or wp processing
 */
// Retrieve the request headers
// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- called outside WP
$event_header    = isset( $_SERVER['HTTP_X_EVENTBRITE_EVENT'] ) ? filter_var( $_SERVER['HTTP_X_EVENTBRITE_EVENT'] , FILTER_SANITIZE_STRING) : '';
// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- called outside WP
$delivery_header = isset( $_SERVER['HTTP_X_EVENTBRITE_DELIVERY'] ) ? filter_var($_SERVER['HTTP_X_EVENTBRITE_DELIVERY'], FILTER_SANITIZE_STRING )  : '';


// Check if the required headers are present and have valid values
$valid_actions = [ 'event.created', 'event.published', 'event.unpublished', 'event.updated', 'test' ];
if ( ! in_array( $event_header, $valid_actions ) || empty( $delivery_header ) ) {
	// Headers are missing or incorrect, return an error response
	$response = array( 'message' => 'Invalid headers' );
	http_response_code( 400 );
	echo json_encode( $response );
	exit;
}

// Retrieve the request body
$request_body = file_get_contents( 'php://input' );

// Specify the directory path for the process queue files
$queue_directory = 'queue/';

// Ensure the queue directory exists, create it if not
if ( ! is_dir( $queue_directory ) ) {
	mkdir( $queue_directory, 0755, true );
}
$payload = json_decode( $request_body, true );

if ( $payload ) {
	$api_url = $payload['api_url'];
}
$result = preg_match( '/events\/(\d+)/', $api_url, $matches );
if ( $result ) {
	$event_id = $matches[1];
	$filename = $event_id . '.json';
// Write the request body to a new file in the queue directory
	file_put_contents( $queue_directory . $filename, $delivery_header );
	$response = array( 'message' => 'Webhook processed' );
} elseif ( $event_header === 'test' ) {
	$response = array( 'message' => 'Test webhook received' );
} else {
	http_response_code( 400 );
	$response = array( 'message' => 'Invalid webhook' );
}

// Respond with a success message

echo json_encode( $response );