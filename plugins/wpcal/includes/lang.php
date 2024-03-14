<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$wpcal_lang = [];

// Error messages
$wpcal_lang['validation_errors'] = '';
$wpcal_lang['db_error'] = 'DB Error.';
$wpcal_lang['db_error_insert_id_missing'] = 'DB insert ID missing.';
$wpcal_lang['invalid_input_service_page'] = 'Invalid input.';
$wpcal_lang['service_page_insert_error'] = 'New page for Event Type insert error.';
$wpcal_lang['invalid_input_default_availability'] = 'Invalid input.';
$wpcal_lang['service_default_availability_data_missing'] = 'Default availability data missing.';
$wpcal_lang['invalid_input_default_availability'] = 'Invalid input.';
$wpcal_lang['invalid_input_service_status'] = 'Invalid Event Type status.';
$wpcal_lang['service_admin_user_id_missing'] = 'Event Type admin ID missing.';
$wpcal_lang['service_new_booking_not_allowed'] = 'New Booking not allowed for this Event Type';
$wpcal_lang['service_reschedule_booking_not_allowed'] = 'Reschedule Booking not allowed for this Event Type';
$wpcal_lang['service_max_booking_per_day_reached'] = 'No more booking allowed for this day.';
$wpcal_lang['service_booking_slot_not_avaialble'] = 'Oops, Booking slot no more available. Please try again.';
$wpcal_lang['invalid_booking_id'] = 'Invalid Booking ID';
$wpcal_lang['booking_unable_to_find_unique_link'] = 'Booking unable to find unique link.';
$wpcal_lang['booking_unique_link_missing'] = 'Booking unique link is missing.';
$wpcal_lang['booking_unable_to_get_booking_id'] = 'Unble to get Booking ID.';
$wpcal_lang['invalid_input'] = 'Invalid input.';
$wpcal_lang['invalid_action'] = 'Invalid action.';
$wpcal_lang['booking_is_not_active'] = 'Booking is not active.';
$wpcal_lang['booking_cancellation_not_allowed'] = 'Booking cancellation not allowed.';
$wpcal_lang['booking_not_a_rescheduled'] = 'Not a reschedule booking to update.';
$wpcal_lang['booking_old_not_active'] = 'Old booking is not active.';
$wpcal_lang['booking_not_cancelled'] = 'Booking not cancelled.';
$wpcal_lang['booking_admin_user_id_missing'] = 'Booking admin ID missing.';
$wpcal_lang['invalid_tp_calendar_provider'] = 'Invalid calendar provider.';
$wpcal_lang['tp_calendar_auth_url_data_missing'] = 'Calendar API auth URL data missing.';
$wpcal_lang['current_admin_id_missing_or_doesnt_have_enough_privilege'] = 'Admin ID missing or doesn\'t have enough privelege.';
$wpcal_lang['availability_date_id_not_exists'] = 'Availability ID not exists.';
$wpcal_lang['availability_periods_not_exists_availability_date_id'] = 'Availability periods not exists.';
$wpcal_lang['invalid_date_misc_format'] = 'Invalid date additional data format.';
$wpcal_lang['unexpected_date_range_type'] = 'Unexpected date range type.';
$wpcal_lang['availability_period_id_not_exists'] = 'Availability periods ID not exists.';
$wpcal_lang['invalid_service_details'] = 'Invalid Event Type details.';
$wpcal_lang['invalid_response'] = 'Invalid response.';
$wpcal_lang['service_default_availability_data_missing'] = 'Event Type default availability data missing.';
$wpcal_lang['invaild_slot_details'] = 'Invalid slot details.';
$wpcal_lang['slot_unexpected_to_time_value'] = 'Slot unexpected "to time" value.';
$wpcal_lang['service_id_not_exists'] = 'Event Type ID not exists.';
$wpcal_lang['invalid_sanitize_rule'] = 'Invalid santize rule.';
$wpcal_lang['invalid_response_json_failed'] = 'Invalid JSON response.';
$wpcal_lang['invalid_response_empty'] = 'Invalid response(empty).';
$wpcal_lang['invalid_response_format'] = 'Invalid response format.';
$wpcal_lang['calendar_account_id_not_exists'] = 'Calendar account ID not exists.';
$wpcal_lang['unknown_error'] = 'Unknown error.';
$wpcal_lang['unknown_task'] = 'Unknown task has been called.';
$wpcal_lang['unknown_mail_task'] = 'Unknown mail task has been called.';
$wpcal_lang['invalid_service_id'] = 'Invalid Event Type ID.';
$wpcal_lang['invalid_license_info'] = 'Invalid license info.';
$wpcal_lang['event_id_missing'] = 'Mailing event ID missing.';
$wpcal_lang['cron_server_invalid_response'] = 'Invalid response from mail server.';
$wpcal_lang['mail_template_output_error'] = 'Mail template output error.';
$wpcal_lang['invalid_tp_provider'] = 'Invalid integration provider.';
$wpcal_lang['tp_auth_url_data_missing'] = 'Integration API auth URL data missing.';
$wpcal_lang['tp_account_id_not_exists'] = 'Integration account ID not exists.';
$wpcal_lang['user_doesnt_have_admin_rights'] = 'User doesn\'t have admin rights.';
$wpcal_lang['invalid_admin_user_id'] = 'Invalid admin ID.';
$wpcal_lang['booking_location_doesnt_need_online_meeting'] = 'Booking location doesn\'t need an online meeting.';
$wpcal_lang['booking_location_type_mismatch'] = 'Booking location type mismatch.';
$wpcal_lang['location_details_not_available'] = 'Booking location details not available.';
$wpcal_lang['tp_account_missing'] = 'Integration account is missing.';
$wpcal_lang['access_denied'] = 'Access denied.';
$wpcal_lang['invalid_resource_type'] = 'Invalid resource type.';
$wpcal_lang['invalid_resource_id'] = 'Invalid resource ID.';
$wpcal_lang['resource_id_not_exists'] = 'Resource ID doesn\'t exists.';
$wpcal_lang['unexpected_status_background_task_query'] = 'Unexpected status in background task.';
$wpcal_lang['invalid_tp_provider_details'] = 'Invalid integration provider details.';
$wpcal_lang['invalid_meeting_tp_resource_id'] = 'Invalid booking meeting resource ID.';
$wpcal_lang['invalid_tp_meeting_id'] = 'Invalid integration meeting ID.';
$wpcal_lang['booking_id_not_exists'] = 'Booking ID not exists.';
$wpcal_lang['tp_resource_id_not_exists'] = 'Third party resource ID not exists.';
$wpcal_lang['only_one_active_event_allowed_as_per_plan'] = 'Only one active Event type is permitted according to your plan. Please deactivate other Event types if you want to add/enable this one';
$wpcal_lang['only_one_active_admin_allowed_as_per_plan'] = 'Only one active admin is permitted according to your plan. Please deactivate other Event types if you want to add/enable this one.';
$wpcal_lang['service_no_longer_editable'] = 'Event type no longer editable(it could have been deleted).';
$wpcal_lang['host_admin_not_active_wpcal_or_wp'] = 'Host admin is not active in WPCal or WordPress.';
$wpcal_lang['unable_get_data_from_calendar_event_template'] = 'Unable to get data from calendar event template.';
$wpcal_lang['invalid_status'] = 'Invalid status';
$wpcal_lang['admin_not_exists'] = 'Admin not exists.';
$wpcal_lang['invalid_admin_status'] = 'Invalid admin status.';
$wpcal_lang['current_admin_id_missing_or_doesnt_have_enough_privilege_or_not_a_wpcal_admin'] = 'Current admin id missing or doesn\'t have enough privilege or not a WPCal admin.';
$wpcal_lang['admin_not_found_not_active_or_disabled'] = 'Admin not found or disabled.';
$wpcal_lang['some_admin_details_missing'] = 'Some admin details missing.';
$wpcal_lang['admin_have_active_event_types_or_bookings'] = 'Admin is having active event types or bookings.';
$wpcal_lang['cannot_disable_or_delete_the_last_active_admin'] = 'Cannot disable/delete the last active admin.';
$wpcal_lang['you_cannot_disable_or_delete_yourself'] = 'You cannot disable/delete yourself.';
$wpcal_lang['mail_task_args_missing'] = 'Mail task args missing.';
$wpcal_lang['task_args_important_arg_missing'] = 'Important task args are missing.';
$wpcal_lang['notice_id_not_exists'] = 'Notice ID not exists.';
$wpcal_lang['calendar_account_details_not_loaded'] = 'Calendar account details not loaded.';
$wpcal_lang['calendar_account_status_invalid'] = 'Calendar account status invalid.';
$wpcal_lang['max_calendar_account_limit_reached'] = 'Max calendar account limit reached.';
$wpcal_lang['invalid_stop_webhook_input'] = 'Invalid stop webhook input.';

// license auth error code from auth server respons
$wpcal_lang['license__invalid_response'] = 'Auth server - Invalid response.';
$wpcal_lang['license__invalid_token'] = 'Auth server - Invalid token.';
$wpcal_lang['license__invalid_user'] = 'Auth server - Invalid user.';
$wpcal_lang['license__not_valid'] = 'Auth server - Not valid.';
$wpcal_lang['license__expired'] = 'Auth server - Expired.';
$wpcal_lang['license__sites_limit_reached'] = 'Auth server - Site limit reached.';
$wpcal_lang['license__invalid_email'] = 'Auth server - Invalid Email.';
$wpcal_lang['license__invalid_request'] = 'Auth server - Invalid request.';
$wpcal_lang['license__email_exits'] = 'Auth server - Email already exists.';
$wpcal_lang['license__signup_error'] = 'Auth server - Signup error.';
$wpcal_lang['license__login_error'] = 'Auth server - The email or password you entered is incorrect.';
$wpcal_lang['license__unexpected_error'] = 'Auth server - Unexpected error.';
$wpcal_lang['license__invalid_args'] = 'Auth server - Invalid args.';
