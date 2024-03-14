<?php

require_once CANVAS_DIR . 'core/push/canvas-notifications-db.class.php';

$offset = isset( $_GET['offset'] ) ? absint( $_GET['offset'] ) : 0;
$count  = isset( $_GET['count'] )
? absint( $_GET['count'] )
:
/**
* Filter to allow overriding default count of notification items.
*
* @since 3.2
*
* @param int Default value to return if no parameters provided.
*/
apply_filters( 'canvas_list_defailt_count', 20 );
$json = ! empty( $_GET['json'] ); // what to return, json or html page.

$result = array( 'history' => CanvasNotificationsDb::get_notifications( $offset, $count ) );
echo wp_json_encode( $result );
