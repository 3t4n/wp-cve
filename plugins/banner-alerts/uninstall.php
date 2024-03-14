<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	die();
}

$option_name = 'options-general_banner-alerts_display';
delete_option($option_name);
