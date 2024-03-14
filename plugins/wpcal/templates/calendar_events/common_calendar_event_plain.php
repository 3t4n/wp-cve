<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$eol = "\n";

// summary - Calendar Event Title
$summary = $service_obj->get_name();
$invitee_name = $booking_obj->get_invitee_name();

if (!empty($admin_details['display_name']) && !empty($invitee_name)) {
	$summary = sprintf(
		/* translators: 1: person1 name 2: person2 name */
		__('%1$s and %2$s', 'wpcal'), $admin_details['display_name'], $invitee_name);
}

//==========================================================>

$descr_txt = '';

$descr_txt = __('Event:', 'wpcal') . ' ' . $service_obj->get_name() . $eol;
if ($location_descr) {
	$descr_txt .= $eol . $location_descr . $eol;
}

if ($whos_view == 'neutral' || $whos_view == 'user') {

	$descr_txt .= $eol . __('View, Reschedule or Cancel this event', 'wpcal') . ' - ' . $booking_obj->get_redirect_view_url() . $eol;

} elseif ($whos_view == 'admin') {

	$descr_txt .= $eol . __('To cancel or reshedule the event please visit - ', 'wpcal') . $booking_obj->get_admin_view_booking_url() . $eol;
}

$descr_txt .= $eol . __('Powered by', 'wpcal') . ' ' . WPCAL_SITE_URL;

$template_result = ['summary' => $summary, 'descr' => $descr_txt];

return $template_result;
