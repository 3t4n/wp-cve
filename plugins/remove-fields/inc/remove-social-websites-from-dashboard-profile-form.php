<?php

/*
 * Remove AIM, Yahoo IM and Jabber / Google Talk
 */
add_filter('user_contactmethods', 'remove_contactmethods');
function remove_contactmethods($contactmethods) {
	unset($contactmethods['aim']);
	unset($contactmethods['yim']);
	unset($contactmethods['jabber']);
	return $contactmethods;
}
