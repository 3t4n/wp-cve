<?php 
if( !defined('ABSPATH') || !defined('WP_UNINSTALL_PLUGIN') )
	exit;
delete_option('conditional_captcha_count');
delete_option('conditional_captcha_options');
