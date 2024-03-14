<?php

$tabs = array(
	'general' => array(
		'title'			=> 'General',
		'id' 			=> 'general',
		'option_key' 	=> 'xoo-wl-general-options'
	),

	'email' => array(
		'title'			=> 'Email',
		'id' 			=> 'email',
		'option_key' 	=> 'xoo-wl-email-options'
	),


	'email-style' => array(
		'title'			=> 'Email Style',
		'id' 			=> 'email-style',
		'option_key' 	=> 'xoo-wl-emStyle-options',
		'pro' 			=> 'yes'
	),

	'style' => array(
		'title'			=> 'Style',
		'id' 			=> 'style',
		'option_key' 	=> 'xoo-wl-style-options'
	),
);

return apply_filters( 'xoo_wl_admin_settings_tabs', $tabs );