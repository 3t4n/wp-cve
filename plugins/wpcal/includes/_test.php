<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

//include('../../../../wp-load.php');

// include( '../lib/Valitron/Validator.php');

// include('common_func.php');

// include('app_func.php');

// include('class_date_time_helper.php');
// include('class_service.php');
// include('class_availability_date.php');
// include('class_service_availability_details.php');
// include('class_service_availability_slots.php');
// include('class_bookings_query.php');
//include('class_service.php');

// $start = new DateTime('2019-11-21 9:00:00');
// $interval = new DateInterval('PT30M');
// $end = new DateTime('2019-11-21 16:55:00');
// $recurrences = 4;
// $iso = 'R4/2012-07-01T00:00:00Z/P7D';

// // All of these periods are equivalent.
// $period = new DatePeriod($start, $interval, $recurrences);
// //$period = new DatePeriod($start, $interval, $end);
// // $period = new DatePeriod($iso);

// // By iterating over the DatePeriod object, all of the
// // recurring dates within that period are printed.
// foreach ($period as $date) {
// 	echo '<br>'.$date->format('Y-m-d H:i:s')."\n";
// }

// $start->add($interval);
// echo '---<br>'.$start->format('Y-m-d H:i:s');

// //Mon to Fri, 9am to 5pm
// $time_period = array(
// 	'is_available' => 1,
// 	'period_type' => 'days',
// 	'period_list' => array('Mon', 'Tue', 'Wed', 'Thu', 'Fri'),
// 	'time_intervals' => array(
// 		array(
// 			'from' => '09:00:00',
// 			'to' => '17:00:00'
// 		)
// 	)
// );

// //20 Nov 2019 to 30 Nov 2019, 9am to 1pm
// $time_period = array(
// 	'is_available' => 1,
// 	'period_type' => 'date_range',
// 	'period_range' => array('from' => '20 Nov 2019', 'to' => '30 Nov 2019'),
// 	'time_intervals' => array(
// 		array(
// 			'from' => '09:00:00',
// 			'to' => '13:00:00'
// 		)
// 	)
// );

// //22 Nov 2019, 24 Nov 2019, 2pm to 5pm
// $time_period = array(
// 	'is_available' => 1,
// 	'period_type' => 'dates',
// 	'period_list' => array(
// 		'20 Nov 2019',
// 		'30 Nov 2019'
// 	),
// 	'time_intervals' => array(
// 		array(
// 			'from' => '14:00:00',
// 			'to' => '17:00:00'
// 		)
// 	)
// );

// //23 Nov 2019, 26 Nov 2019, unavailable
// $time_period = array(
// 	'is_available' => 0,
// 	'period_type' => 'dates',
// 	'period_list' => array(
// 		'20 Nov 2019',
// 		'30 Nov 2019'
// 	)
// );
// $hi;
// $re = '/^\+(\d+)d$/m';
// $str = '+60d';

// preg_match($re, $str, $matches);

// // Print the entire match result
// var_dump($matches);

function test() {

	// $service_obj = new WPCal_Service(1);
	// var_export($service_obj->get_locations());

	if (!empty($_GET['show_mail_preview'])) {
		wpcal_dev_preview_all_emails();
	}

	// $url = 'http://username:password@hostname:9090/path/';
	// $new_url = wpcal_modify_url($url, $modify_parsed_url=['query_params' =>['a'=> '/sdkjfsae785q3wyrsuiahedfi/']]);
	// var_dump($url, $new_url);

	//var_dump(get_user_meta(1000000, 'wpcal_admin_profile_settings', true));

	// var_dump(wp_get_attachment_image_src( 179, 'wpcal-admin-avatar' ));

	// $result = WPCal_Service_Availability_Details::get_service_available_from_and_to_dates(new DateTime('2020-08-10', wp_timezone()), new DateTime('2020-08-30', wp_timezone()));
	// var_dump($result);

	// include_once( WPCAL_PATH . '/includes/tp/class_zoom_meeting.php');
	// $zoom = new WPCal_TP_Zoom_Meeting(42);
	// $result = $zoom->check_auth_ok();
	// var_dump($result);

	// $booking_obj = wpcal_get_booking(34);
	// wpcal_booking_may_run_add_or_update_online_meeting_task($booking_obj);

	// //if($_GET['action'] === 'auth'){
	// 	$zoom = new WPCal_TP_Zoom_Meeting(0);
	// 	$authUrl = $zoom->get_add_account_url();
	// 	header('Location: '.$authUrl);
	// 	echo '<a href="'.$authUrl.'">'.$authUrl.'</a>';
	// 	//exit;
	// //}
	// if($_GET['code']){
	// 	$zoom = new WPCal_TP_Zoom_Meeting(0);
	// 	$zoom->add_account_after_auth();
	// }

	// $zoom = new WPCal_TP_Zoom_Meeting(9);
	// $zoom->__print_resource_owner_details();

	// $_d = [
	// 	'type' => 2,
	// 	'start_time' => '2020-05-30T15:30:00Z',
	// ];
	// $zoom->create_meeting($_d);

	//$zoom->__revoke();

	// $resource_type = 'booking';
	// $resource_id = 100;
	// $is_owns = wpcal_is_admin_owns_resource($resource_type, $resource_id, 2);
	// var_dump('$is_owns', $is_owns);

	//var_dump(wp_get_theme()->get_page_templates());
	//$d = WPCal_DateTime_Helper::now_DateTime_obj();
	//var_dump($d->format('e'),$d->format('c') );

	// $logo_image_64 = file_get_contents(WPCAL_PATH.'/templates/emails/images/wpcal_img64.txt');

	// echo '<img src="'.$logo_image_64.'" />';

	// $img = file_get_contents(ABSPATH.'/wpcal.png');
	// $img64 = base64_encode($img);
	// file_put_contents(WPCAL_PATH.'/templates/emails/images/wpcal_img64.txt', $img64);

	//WPCal_Background_Tasks::run_tasks_by_main_args('booking_id', 11);

	// $booking_obj = wpcal_get_booking_by_unique_link('5716dea3271a15964d0e084b5f34968442298b76');
	// WPCal_Mail::send_invitee_booking_confirmation($booking_obj);

	// $link = WPCal_TP_Calendars_Add_Event::get_google_calendar_add_event_link(1);
	// echo '<br><a href="'.$link.'">'.$link.'</a>';

	//wpcal_may_add_sample_services_on_plugin_activation();

	// $_rr = wpcal_get_wpcal_admin_users_details_for_admin_client();
	// var_dump($_rr);

	//var_dump(wpcal_get_count_of_calendar_accounts_of_current_admin());

	//var_dump(WPCal_License::check_validity());

	// $wpcal_cron_obj = new WPCal_Cron();
	// $wpcal_cron_obj->sync_tp_calendars_and_events();
	// $wpcal_cron_obj->delete_old_tp_calendar_events();
	// $wpcal_cron_obj->delete_old_booking_slots_cache();
	//$wpcal_cron_obj->delete_old_service_custom_availability();

	//var_dump(wpcal_get_add_bookings_to_calendar_by_admin(2));

	//var_dump(wpcal_get_conflict_calendar_ids_by_admin(2));

	// $_data = [
	// 	'name' => ' Helon\'s mom',
	// 	'descr' => 'quicky
	// 	bucky
	// 	no scripty',
	// 	'default_avail' => [
	// 		'periods' => [
	// 			['from_descr' => ' bigil
	// 			suber
	// 			no alottuy ', 'to_descr' => ' bigil
	// 			suber
	// 			no alottuy '],
	// 			['from_descr' => ' bigil2
	// 			suber2
	// 			no alottuy2 ', 'to_descr' => ' bigil2
	// 			suber2
	// 			no alottuy2 ', 'normal' => ' natual\'s sand', 'glip' => [ '*' => ['from_descr' => ' bigil3
	// 			suber3
	// 			no alottuy3 '] ] ],

	// 		]
	// 	]
	// ];

	// $_rule = [
	// 	'descr' => 'sanitize_textarea_field',
	// 	'default_avail' => [
	// 		'periods' => [ '*' =>
	// 			['from_descr' => 'sanitize_textarea_field', 'to_descr' => 'sanitize_textarea_field', 'glip' => [ '*' => ['from_descr' =>'sanitize_textarea_field']]],
	// 		]
	// 	]
	// ];
	// $_s_data = wpcal_sanitize_all($_data, $_rule);
	// echo '<pre>'.var_export($_s_data, 1).'</pre>';

	// $post = get_post(49);
	// var_dump($post);

	// $title = 'Tech Consultation';

	// $page_id = wp_insert_post(
	//  array(
	//   'comment_status' => 'close',
	//   'ping_status' => 'close',
	//   'post_author' => 1,
	//   'post_title' => $title,
	//   'post_name' => $title, //strtolower(str_replace(' ', '-', trim('title_of_the_page'))),
	//   'post_status' => 'publish',
	//   'post_content' => '[wpcal id=1]',
	//   'post_type' => 'page',
	//   'post_parent' => 'id_of_the_parent_page_if_it_available',
	//  )
	// );

	// wpcal_include_and_get_tp_calendar_class('google_calendar');
	// // var_dump($page_id);
	// $google_cal = new WPCal_TP_Google_Calendar(5);
	// $google_cal->api_refresh_calendars();
	// $google_cal->refresh_events_for_all_conflict_calendars();

	//wpcal_check_and_add_default_calendars_for_current_admin(10);

	// $booking_obj = wpcal_get_booking(33);
	// wpcal_may_add_booking_to_tp_calendar($booking_obj);

	// echo 'ho';
	// $__profile_start_time = microtime(1);
	// $service_obj = new WPCal_Service(1);
	// $service_availability_obj = new WPCal_Service_Availability_Details( $service_obj );
	// $_availability_details = $service_availability_obj->get_availability_by_date_range_for_admin_client();

	// echo '<pre>';
	// var_export($_availability_details);
	// echo '</pre>';

	// echo '<br><br>TimeTaken: '. (microtime(1) - $__profile_start_time) ;

	//	echo PHP_INT_MAX;

	// $data = array(
	//    'date_range_type' => 'from_to1',
	//     'from_date' => '',
	// 	'is_available' => '1',
	// 	'periods' => [
	// 		['from_time' => '10:00:00', 'to_time' => '10:00:00'],
	// 		['from_time' => '10:00:00', 'to_time' => '10:00:00'],
	// 	]
	// );

	// $validate_obj = new WPCal_Validate($data);

	// $validate_obj->rules([
	// 	// 'requiredWith' => [
	//     //     ['from_date', ['date_range_type']],
	//     //     //['periods', ['is_available' => '11']],
	//     // ],
	//     // 'required' =>[
	//     //     //'date_range_type',
	//     //     'is_available'
	// 	// ],

	// 	// 'requiredWithIf' => [
	//     //     ['from_date', ['date_range_type' => 'from_to']],
	//     //     ['periods', ['is_available' => '11']],
	//     // ]
	// 	// ,
	//     // 'dateFormat' => [
	//     //    ['from_date', 'Y-m-d'],
	// 	// ],
	//     // 'optional' => [
	//     //     //'from_date'
	// 	// ]
	// 	'equals' => [
	// 		['periods.*', 'periods.*']
	// 	]
	// ]);

	// if( $validate_obj->validate() ){
	//     echo 'success validate';
	// }
	// else{
	// 	var_dump($validate_obj->errors());
	// 	exit;
	// }

	// $service_obj = wpcal_get_service(1);
	// $service_availability_slots_obj = new WPCal_Service_Availability_Slots($service_obj);
	// $from_date = new DateTime('2020-02-04');
	// $to_date = new DateTime('2020-03-31');
	// $all_slots = $service_availability_slots_obj->get_slots($from_date, $to_date);
	// var_dump($all_slots);

	// $booking_obj = new WPCal_Booking(10);
	// var_dump(is_callable(array($booking_obj, 'get_unique_link')));
	// var_dump($booking_obj->get_unique_link());
	// $booking_obj->randowdfrgdfgdfg();

	// $d = new DateTime('now', wp_timezone());
	// var_dump($d->format('c'));

	// $c = new DateTime('@'.time());
	// var_dump($c->format('c'));

	// $a = new DateTime('@'.time(), wp_timezone());
	// $a->setTimezone(wp_timezone());
	// var_dump($a->format('c'));

	// $tz = wp_timezone();
	// var_dump($tz);
	// var_dump(wp_timezone()->getName());

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 16:45:00'), new DateTime('2020-02-25 17:15:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 16:45:00'), new DateTime('2020-02-25 17:15:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 16:00:00'), new DateTime('2020-02-25 18:01:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 16:00:00'), new DateTime('2020-02-25 18:01:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:15:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:15:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 18:00:00') );
	// var_dump($is_two_slots_collide === false);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 18:00:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === false);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:40:00'), new DateTime('2020-02-25 18:00:00') );
	// var_dump($is_two_slots_collide === false);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:40:00'), new DateTime('2020-02-25 18:00:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === false);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:15:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:15:00'), new DateTime('2020-02-25 17:30:00') , new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') , new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:15:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:15:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') , new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:40:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:40:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') , new DateTime('2020-02-25 16:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	// $is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-25 16:00:00'), new DateTime('2020-02-25 17:30:00'), new DateTime('2020-02-25 17:00:00'), new DateTime('2020-02-25 17:30:00') );
	// var_dump($is_two_slots_collide === true);

	//var_dump(WPCal_DateTime_Helper::is_two_slots_collide(new DateTime('2020-02-29 17:00:00'), new DateTime('2020-02-29 17:30:00'), new DateTime('2020-02-25 16:00:00'), new DateTime('2020-02-25 18:01:00') ));

	//var_dump(WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time(new DateTime('@1687464753'), new DateTime('@1688464753')));

	// $booking_obj = wpcal_get_booking(41);
	// $reason_string = $booking_obj->get_reschedule_cancel_reason();
	// $reason_by = $booking_obj->get_reschedule_cancel_user_id();
	// var_dump($reason_string, $reason_by );

	//========= multi admin access queries ============>
	/*

	CREATE TABLE `wp_wpcal_admins` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`admin_user_id` bigint(20) unsigned NOT NULL,
	`admin_type` enum('administrator') COLLATE utf8mb4_unicode_520_ci NOT NULL,
	`status` tinyint(4) NOT NULL DEFAULT '1',
	`added_ts` int(10) unsigned NOT NULL,
	`updated_ts` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `admin_user_id` (`admin_user_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci

	ALTER TABLE `wp_wpcal_services`
	ADD `is_manage_private` tinyint unsigned NOT NULL DEFAULT '0' AFTER `event_buffer_after`;

	 */
	//========= multi admin access queries ============>

	// feature option to choose invitee_notify_by: calendar_invitation or email
	/*

	ALTER TABLE `wp_wpcal_services`
	ADD `invitee_notify_by` enum('calendar_invitation','email') NOT NULL AFTER `invitee_questions`;

	 */

	//================= Google Calendar webhook queries ==============>
	/*

	ALTER TABLE `wp_wpcal_calendars`
	ADD `events_webhook_channel_id` varchar(256) COLLATE 'utf8mb4_unicode_520_ci' NULL AFTER `list_events_sync_last_update_ts`,
	ADD `events_webhook_resource_id` varchar(256) COLLATE 'utf8mb4_unicode_520_ci' NULL AFTER `events_webhook_channel_id`,
	ADD  `events_webhook_expiry_ts` int(10) unsigned NULL AFTER `events_webhook_resource_id`,
	ADD `events_webhook_not_supported` tinyint(1) unsigned NULL AFTER `events_webhook_expiry_ts`,
	ADD `events_webhook_updated_ts` int(10) unsigned NULL AFTER `events_webhook_not_supported`;

	 */

	//

	//wpcal_on_plugin_activation_user_if_not_wpcal_admin_add_notice();

	//wpcal_calendars_required_reauth_add_notice($admin_user_id = '1');

	// $attachment_id = 22;

	// wpcal_may_generate_avatar_attachment($attachment_id);

	// // $meta = wp_get_attachment_metadata($attachment_id);

	// // $file = get_attached_file($attachment_id);
	// // $new_meta = [];
	// // $new_meta = wp_generate_attachment_metadata($attachment_id, $file);

	// // wp_update_attachment_metadata($attachment_id, $image_meta = $new_meta);
	// // var_dump($meta, '<br>==============<br>', $new_meta);

	// // $post_attachment = get_post($attachment_id);
	// // wp_maybe_generate_attachment_metadata($post_attachment);
	// echo '<br>I think it is done';

}

test();

// $_t = new DateTime('0000-1-1');
// var_dump($_t->modify('+1 day'));
// var_dump(new DateTime('13:30:58'));

// $v = new WPCal_Validate(array('name' => ''));
// $v->rule('required', 'name');
// if($v->validate()) {
//     echo "Yay! We're all good!";
// } else {
//     // Errors
//     print_r($v->errors());
// }

// $data = array(
// 	'name' => 'Ahi',
// 	'status' => '1',
// 	'location' => '',
// 	'descr' => '',
// 	'post_id' => '',
// 	'color' => '',
// 	'relationship_type' => '1to1',
// 	'duration' => '30',
// 	'display_start_time_every' => '15',
// 	'max_booking_per_day' => '0',
// 	'min_schedule_notice' => '0',
// 	'event_buffer_before' => '0',
// 	'event_buffer_after' => '0',
// 	'added_ts' => '',
// 	'updated_ts' => '',
// 	'owner_admin_id' => null
// );

//wpcal_add_service($data);

// $data = array(
// 	'name' => 'Ahi',
// 	'status' => '1',
// 	'location' => '',
// 	'descr' => '',
// 	'post_id' => '',
// 	'color' => '',
// 	'relationship_type' => '1to1',
// 	'duration' => '30',
// 	'display_start_time_every' => '15',
// 	'max_booking_per_day' => '0',
// 	'min_schedule_notice' => '0',
// 	'event_buffer_before' => '15',
// 	'event_buffer_after' => '0',
// 	'added_ts' => '',
// 	'updated_ts' => '',
// 	'owner_admin_id' => null,
// );

//wpcal_update_service($data, 2);

//WPCal_Service_Availability_Slots();
