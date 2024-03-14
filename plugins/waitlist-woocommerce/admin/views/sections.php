<?php

$sections = array(

	/* General TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'gl_main',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'Texts',
		'id' 	=> 'gl_texts',
		'tab' 	=> 'general',
		'desc' 	=> 'Leave text empty to remove element'
	),


	/* Email TAB Sections */
	array(
		'title' => 'Sender Options',
		'id' 	=> 'em_sender',
		'tab' 	=> 'email',
	),


	array(
		'title' => 'General',
		'id' 	=> 'em_general',
		'tab' 	=> 'email',
	),


	array(
		'title' => 'Back In Stock Email',
		'id' 	=> 'em_bis',
		'tab' 	=> 'email',
	),

	array(
		'title' => 'Admin Notification Email',
		'id' 	=> 'em_an',
		'tab' 	=> 'email',
		'pro' 	=> 'yes',
	),


	array(
		'title' => 'Confirmation Email to user',
		'id' 	=> 'em_un',
		'tab' 	=> 'email',
		'pro' 	=> 'yes',
	),


	/* Email Style TAB Sections */
	array(
		'title' => 'Container',
		'id' 	=> 'emsy_container',
		'tab' 	=> 'email-style',
	),


	array(
		'title' => 'Button',
		'id' 	=> 'emsy_button',
		'tab' 	=> 'email-style',
	),


	array(
		'title' => 'Footer Container',
		'id' 	=> 'emsy_footer',
		'tab' 	=> 'email-style',
	),


	array(
		'title' => 'Back In Stock Email',
		'id' 	=> 'emsy_bis',
		'tab' 	=> 'email-style',
	),


	array(
		'title' => 'User Notification Email',
		'id' 	=> 'emsy_un',
		'tab' 	=> 'email-style',
	),


	array(
		'title' => 'Admin Notification Email',
		'id' 	=> 'emsy_an',
		'tab' 	=> 'email-style',
	),


	/* Style Sections*/
	array(
		'title' => 'Popup',
		'id' 	=> 'sy_popup',
		'tab' 	=> 'style',
	),

	array(
		'title' => 'Button',
		'id' 	=> 'sy_button',
		'tab' 	=> 'style',
	),

	/* Custom CSS TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'av_main',
		'tab' 	=> 'advanced',
	),
);

return apply_filters( 'xoo_el_admin_settings_sections', $sections );