<?php

/**
 * @package 
 */
// Compatibility Check
function catcbll_check_woocommerce_plugin()
{
	if (!class_exists('WooCommerce')) {
		return __('Please install and activate WooCommerce plugin first.', 'catcbll');
	}

	if (version_compare(wc()->version, CATCBLL_MINIMUM_WOOCOMMERCE_VERSION, '<=')) {
		return sprintf(__("Please update your WooCommerce plugin. It's outdated. %s or latest required", 'catcbll'), CATCBLL_MINIMUM_WOOCOMMERCE_VERSION);
	}

	return false;
}

// Plugin Die Message
function catcbll_plugin_die_message($message)
{
	return '<p>' .
		$message
		. '</p> <a href="' . admin_url('plugins.php') . '">' . __('Go back', 'catcbll') . '</a>';
}

