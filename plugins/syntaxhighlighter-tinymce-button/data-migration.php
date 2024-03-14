<?php
$shtb_adv_setting_opt = get_option('shtb_adv_setting_opt');

// Data migration for ver. 0.6 or older
if (!$current_checkver_stamp) {
	$using_syntaxhighlighter = get_option('shtb_adv_using_syntaxhighlighter');
	if ($using_syntaxhighlighter) {
		$shtb_adv_setting_opt['using_syntaxhighlighter'] = $using_syntaxhighlighter;
	}
	$button_window_size = get_option('shtb_adv_button_window_size');
	if ($button_window_size) {
		$shtb_adv_setting_opt['button_window_size'] = $button_window_size;
	}
	$button_row = get_option('shtb_adv_button_row');
	if ($button_row) {
		$shtb_adv_setting_opt['button_row'] = $button_row;
	}
}

// Data migration for ver. 0.7 - 0.7.7
if ($current_checkver_stamp && version_compare($current_checkver_stamp, "0.7.5", "<=")) {
	foreach ($shtb_adv_setting_opt as $key => $val) {
		$newkey = str_replace('shtb_adv_', '', $key);
		$shtb_adv_setting_opt[$newkey] = $val;
		unset($shtb_adv_setting_opt[$key]);
	}
}

update_option('shtb_adv_setting_opt', $shtb_adv_setting_opt);
?>