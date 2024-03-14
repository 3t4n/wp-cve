<?php

/**
 * NSANotification provides a structured method to deliver important messages for your plugin / theme to your users.
 *
 * NSANotification provides a structured method to deliver important messages for your plugin / theme to your users.
 *
 * @version 1.0
 * @author Night Shift Apps
 */
class NSANotification
{
	/*
	 * Notification Structure
	 *      
		{
			"id": 3,
			"message": "WP Facebook Pixel pro is finally here!  To celebrate, we wanted to  extend to you a very special offer.  Check out all the details <a href='http://nightshiftapps.com/early-adopter-special-offer' target='_blank'>here</a>!  This is a limited time offer, so act fast!",
			"class": "blue",
			"dismissible": false,
			"hide_on_pages": false
		}
	 *
	 */

	public static function getNotifications($plugin_id, $notification_url, $force, $check_is_dismissed) {
		$notifications = array();
        //REMOVED FOR WORDPRESS.ORG COMPLIANCE
        //if($force) delete_transient($plugin_id.'_Notifications');

        //if(false === ($notifications = get_transient($plugin_id.'_Notifications'))) {
        //    try {
        //        $request = wp_remote_get( $notification_url, array('timeout' => 1) );
        //        if( !is_wp_error( $request ) && wp_remote_retrieve_response_code( $request ) == 200 ) 
        //            $notifications = json_decode($request['body']);
        //    }
        //    catch (Exception $exception) { /* DO NOTHING */ }
        //    if(!is_array($notifications)) $notifications = array();

        //    set_transient($plugin_id.'_Notifications', $notifications, 1 * DAY_IN_SECONDS );
        //}

		
        //$notification_file = plugin_dir_path(__FILE__).'nsa_wpfbp_notifications.txt';
        //if(file_exists($notification_file)) {
        //    $file_notifications = json_decode(file_get_contents($notification_file));
        //    if (is_array($file_notifications)) $notifications = array_merge($notifications, $file_notifications);
        //}


		
		$notifications = apply_filters($plugin_id.'_Notifications', $notifications);

		/* Test Notifications */
		//$notification = new stdClass();
		//$notification->id = 'test4';
		//$notification->title = "Dismissible";
		//$notification->message = "Dismiss me.";
		//$notification->class = 'red';
		//$notification->dismissible = true;
		//$notifications[] = $notification;
		//$notification = new stdClass();
		//$notification->id = 'test2';
		//$notification->title = "Green baby!";
		//$notification->message = "Can't dismiss me.";
		//$notification->class = 'green';
		//$notification->dismissible = false;
		//$notifications[] = $notification;
		//$notification = new stdClass();
		//$notification->id = 'test5';
		//$notification->title = "Just a message";
		//$notification->message = "Can't dismiss me.";
		//$notification->class = 'none';
		//$notification->dismissible = false;
		//$notifications[] = $notification;
		//$notification = new stdClass();
		//$notification->id = 'test6';
		//$notification->title = "Don't eat yellow snow";
		//$notification->message = "Can't dismiss me.";
		//$notification->class = 'yellow';
		//$notification->dismissible = false;
		//$notifications[] = $notification;

		if(is_array($notifications)) {

			if($check_is_dismissed) {
				$index = 0;

				foreach ($notifications as $notice)
				{
					// Has the user dismissed this notice?
					if (get_user_option("{$plugin_id}_hide_note_{$notice->id}")) 
						unset($notifications[$index]);
					

					//Are there any requirements for this notice?
					//if(isset($notice->req)) {
					//    if(is_array($notice->req)) {
					//        foreach ($notice->req as $req)
					//        {
					//            $show = $show && $this->showNotification($this->{$req->param}, $req->op, $req->value);
					//        }
					
					//    } else {
					//        $show = $show && $this->showNotification($this->{$notice->req->param}, $notice->req->op, $notice->req->value);
					//    }
					//}
					
					$index++;
				}
				
			}
		}

		return $notifications;
	}



	//public static function showNotification($left, $op, $right) {
	//    switch ($op)
	//    {
	//        case "<":
	//            return $left < $right;
	//        case "<=":
	//            return $left <= $right;
	//        case "==":
	//            return $left == $right;
	//        case "!=":
	//            return $left != $right;
	//        case ">":
	//            return $left > $right;
	//        case ">=":
	//            return $left >= $right;
	//    }
	//    return true;
		
	//}



}