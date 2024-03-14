<?php
add_filter( 'automatewoo/triggers', 'trigger_delivered' );

if ( ! function_exists( 'trigger_delivered' ) ) {
	function trigger_delivered( $includes ) {
		include_once 'class-automatewoo-delivered-trigger.php';
		// set a unique name for the trigger and then the class name
		$includes['order_delivered'] = 'AutomateWoo\Trigger_Order_Delivered';
		return $includes;
	}
}
