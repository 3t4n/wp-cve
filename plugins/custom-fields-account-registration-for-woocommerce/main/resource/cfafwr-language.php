<?php 
	add_action('plugins_loaded', 'CFAFWR_load_textdomain');
	function CFAFWR_load_textdomain()
	{
	    load_plugin_textdomain('custom-fields-account-for-woocommerce-registration', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	add_filter('load_textdomain_mofile', 'CFAFWR_load_my_own_textdomain', 10, 2);
	function CFAFWR_load_my_own_textdomain($mofile, $domain)
	{
	    if ('custom-fields-account-for-woocommerce-registration' === $domain && false !== strpos($mofile, WP_LANG_DIR . '/plugins/')) {
	        $locale = apply_filters('plugin_locale', determine_locale(), $domain);
	        $mofile = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages/' . $domain . '-' . $locale . '.mo';
	    }
	    return $mofile;
	}