<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// validate name field
$value_name = stripslashes($post_data['form_name']);
if ( !empty($banned_words_list) ) {
	$words = explode(',', $banned_words_list);
	foreach ($words as $word) {
		if ( mb_ereg("\b".$word."\b", $value_name) || preg_match("/\b".$word."\b/i", $value_name) ) {
			$banned_words = true;
			$banned_words_name_field = true;
		}
	}
}
if ( isset($banned_words_name_field) && ($ignore_submission != 'yes') ) {
	$error_class['form_name_banned_words'] = true;
	$error = true;
} elseif ( mb_strlen($value_name)<2 ) {
	$error_class['form_name'] = true;
	$error = true;
}
$form_data['form_name'] = $value_name;

// validate email field
$value_email = $post_data['form_email'];
if ( !empty($banned_words_list) ) {
	$words = explode(',', $banned_words_list);
	foreach ($words as $word) {
		if ( mb_ereg("\b".$word."\b", $value_email) || preg_match("/\b".$word."\b/i", $value_email) ) {
			$banned_words = true;
			$banned_words_email_field = true;
		}
	}
}
if ( isset($banned_words_email_field) && ($ignore_submission != 'yes') ) {
	$error_class['form_email_banned_words'] = true;
	$error = true;
} elseif ( empty($value_email) ) {
	$error_class['form_email'] = true;
	$error = true;
}
$form_data['form_email'] = $value_email;

// validate subject field
if ($disable_subject != 'yes') {
	$value_subject = stripslashes($post_data['form_subject']);
	if ( !empty($banned_words_list) ) {
		$words = explode(',', $banned_words_list);
		foreach ($words as $word) {
			if ( mb_ereg("\b".$word."\b", $value_subject) || preg_match("/\b".$word."\b/i", $value_subject) ) {
				$banned_words = true;
				$banned_words_subject_field = true;
			}
		}
	}
	if ( isset($banned_words_subject_field) && ($ignore_submission != 'yes') ) {
		$error_class['form_subject_banned_words'] = true;
		$error = true;
	} elseif ( mb_strlen($value_subject)<2 ) {
		$error_class['form_subject'] = true;
		$error = true;
	}
	$form_data['form_subject'] = $value_subject;
}

// validate sum field
if(get_transient($transient_name) === false) {
	$error_class['form_transient'] = true;
	$error = true;
} else {
	if ($disable_sum == 'yes') {
		$value_sum = $post_data['form_sum'];
		$value_transient = wp_hash($vscf_rand_one + $vscf_rand_two);
		if ( $value_sum != $value_transient ) {
			$error_class['form_sum_hidden'] = true;
			$error = true;
		}
	} else {
		$value_sum = $post_data['form_sum'];
		$value_transient = $vscf_rand_one + $vscf_rand_two;
		if ( $value_sum != $value_transient ) {
			$error_class['form_sum'] = true;
			$error = true;
		}
		$form_data['form_sum'] = $value_sum;
	}
}

// validate message field
$value_message = stripslashes($post_data['form_message']);
$message_clean = preg_replace('/\s+|\r\n|\r|\n/', ' ', $value_message);
$message_array = explode(' ', $message_clean);
if ( !empty($banned_words_list) ) {
	$words = explode(',', $banned_words_list);
	foreach ($words as $word) {
		if ( mb_ereg("\b".$word."\b", $value_message) || preg_match("/\b".$word."\b/i", $value_message) ) {
			$banned_words = true;
			$banned_words_message_field = true;
		}
	}
}
if ($allow_links == 'disallow') {
	 $allowed_links = 0;
} elseif ($allow_links == 'one') {
	$allowed_links = 1;
} else {
	$allowed_links = 100;
}
$count_links = 0;
foreach ( $message_array as $message_array_value ) {
	if ( preg_match("/[A-Za-z0-9-]+\.[A-Za-z]/", $message_array_value) && !is_email($message_array_value) ) {
		$count_links++;
	}
}
if ($count_links > $allowed_links) {
	$message_has_links = true;
}
if ($allow_email == 'disallow') {
	foreach ( $message_array as $message_array_value ) {
		if ( is_email( $message_array_value ) ) {
			$message_has_email = true;
		}
	}
}
if ( isset($banned_words_message_field) && ($ignore_submission != 'yes') ) {
	$error_class['form_message_banned_words'] = true;
	$error = true;
} elseif ( mb_strlen($value_message)<10 ) {
	$error_class['form_message'] = true;
	$error = true;
} elseif ( isset($message_has_links) && ($ignore_submission != 'yes') ) {
	$error_class['form_message_has_links'] = true;
	$error = true;
} elseif ( isset($message_has_email) && ($ignore_submission != 'yes') ) {
	$error_class['form_message_has_email'] = true;
	$error = true;
}
$form_data['form_message'] = $value_message;

// validate first honeypot field
$value_first_random = $post_data['form_first_random'];
if ( mb_strlen($value_first_random)>0 ) {
	$error_class['form_first_random'] = true;
	$error = true;
}
$form_data['form_first_random'] = $value_first_random;

// validate second honeypot field
$value_second_random = $post_data['form_second_random'];
if ( mb_strlen($value_second_random)>0 ) {
	$error_class['form_second_random'] = true;
	$error = true;
}
$form_data['form_second_random'] = $value_second_random;

// validate time field
$value_time = $post_data['form_time'];
$form_seconds = time() - $value_time;
$minimal_seconds = 3;
if ( $form_seconds < $minimal_seconds ) {
	$error_class['form_time'] = true;
	$error = true;
}

// validate privacy field
if ($disable_privacy != 'yes') {
	$value_privacy = $post_data['form_privacy'];
	if ( $value_privacy !=  'yes' ) {
		$error_class['form_privacy'] = true;
		$error = true;
	}
	$form_data['form_privacy'] = $value_privacy;
}
