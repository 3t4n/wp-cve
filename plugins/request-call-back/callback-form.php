<?php

function wpcallback_display_form($lightbox = true) {
	$field_email = null;
	$field_time = null;
	$field_message = null;

	$label = null;
	$script = null;
	$description = wpautop(wpcallback_get_description());

	$inline_container = null;

	$field_label_name = wpcallback_get_option('field_option_label_name');
	$field_placeholder_name = wpcallback_get_option('field_option_placeholder_name');
	$field_label_telephone = wpcallback_get_option('field_option_label_telephone');
	$field_placeholder_telephone = wpcallback_get_option('field_option_placeholder_telephone');
	$field_label_email = wpcallback_get_option('field_option_label_email');
	$field_placeholder_email = wpcallback_get_option('field_option_placeholder_email');
	$field_label_time = wpcallback_get_option('field_option_label_time');
	$field_placeholder_time = wpcallback_get_option('field_option_placeholder_time');
	$field_label_message = wpcallback_get_option('field_option_label_message');
	$field_placeholder_message = wpcallback_get_option('field_option_placeholder_message');
	$field_label_submit = wpcallback_get_option('field_option_label_submit');

	if($lightbox) {
		$label = '<h1>' . wpcallback_get_option('label') . '</h1>';
		$script = '<script type="text/javascript" src="' . plugins_url('js/request-callback.js', __FILE__) . '"></script>';
	}
	else {
		$inline_container = 'inline-container';
	}

	if(wpcallback_get_option('field_email') != 'disabled') {
		$validate = null;
		$optional = null;
		if(wpcallback_get_option('field_email') == 'required') {
			$validate = 'validate';
			$optional = '<span class="input-required">&#42;</span>';
		}

		$field_email = '<label><span class="callback-label"><span class="label-text">' . $field_label_email . '</span> ' . $optional . '</span><input class="' . $validate . '" type="text" autocomplete="off" name="callback_email" placeholder="' . $field_placeholder_email . '"></label>';
	}

	if(wpcallback_get_option('field_time') != 'disabled') {
		$time_from = wpcallback_get_option('allowable_from');
		$time_to = wpcallback_get_option('allowable_to');

		$validate = null;
		$optional = null;
		if(wpcallback_get_option('field_time') == 'required') {
			$validate = 'validate';
			$optional = '<span class="input-required">&#42;</span>';
		}

		$select_range = build_time_intervals($time_from, $time_to, 0.5);

		$select_options = '<option value=""></option><option value="anytime">' . $field_placeholder_time . '</option>';
		foreach($select_range as $item) {
			$select_options .= '<option value="' . $item['decimal'] . '">' . $item['time'] . '</option>';
		}

		$field_time = '<label><span class="callback-label"><span class="label-text">' . $field_label_time . '</span> ' . $optional . '</span><select class="' . $validate . '" name="callback_time">' . $select_options . '</select></label>';
	}

	if(wpcallback_get_option('field_message') != 'disabled') {
		$validate = null;
		$optional = null;
		if(wpcallback_get_option('field_message') == 'required') {
			$validate = 'validate';
			$optional = '<span class="input-required">&#42;</span>';
		}

		$field_message = '<label><span class="callback-label"><span class="label-text">' . $field_label_message . '</span> ' . $optional . '</span><textarea class="' . $validate . '" name="callback_message" placeholder="' . $field_placeholder_message . '"></textarea></label>';
	}

	$form_action = get_site_url() . '/?wpcallback_action=email';

	$form = <<<EOT
	<div class="callback-form {$inline_container}">{$label}{$description}<form class="clearfix callback-form-container" action="{$form_action}" method="post"><label class="hear-about-us"><span>Hear about us</span><input type="text" autocomplete="off" name="hear_about_us"></label><label><span class="callback-label"><span class="label-text">{$field_label_name}</span> <span class="input-required">&#42;</span></span><input class="validate" type="text" autocomplete="off" name="callback_name" placeholder="{$field_placeholder_name}"></label><label><span class="callback-label"><span class="label-text">{$field_label_telephone}</span> <span class="input-required">&#42;</span></span><input class="validate" type="text" autocomplete="off" name="callback_telephone" placeholder="{$field_placeholder_telephone}"></label>{$field_email}{$field_time}{$field_message}<input class="submit-button" type="submit" value="{$field_label_submit}"></form></div>{$script}
EOT;

	return $form;
}
