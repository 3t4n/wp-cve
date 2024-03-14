<?php
/**
 * Clean up the option table after uninstalling the plugin
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$options_to_remove = [
	'wbvp_better_variation',
	'wbvp_lowest_price',
	'wbvp_hide_reset'
];

foreach ( $options_to_remove as $option ) {
	delete_option($option);
	delete_site_option($option);
}
