<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class CookieCode_Plugin_Options
{
	/**
	 * Function to get language selected
	 */
    public function get_language()
    {
        return get_option('cookiecode-lang');
    }

	/**
	 * Function to get blocking mode selected
	 */
    public function get_blocking_mode()
    {
        return get_option('cookiecode-blocking-mode');
    }

	/**
	 * Function to get disable option selected
	 */
	public function get_script_disable_option()
	{
		return get_option('cookiecode-disable');
	}

	/**
	 * Function to get script priority selected
	 */
	public function get_script_priority_option()
	{
		return get_option('cookiecode-priority', 6);
	}

	public function get_script_legacy_after_option()
	{
		return get_option('cookiecode-legacy-after', '1');
	}

	/**
	 * Function to get blocking modes
	 */
	public function get_supported_blocking_modes()
	{
		$o = array();
		$o[''] = __('Automatic', 'cookiecode');
		$o['manual'] = __('Manual', 'cookiecode');

		return $o;
	}

	/**
	 * Function to get support lanaguage 
	 */
	public function get_supported_languages()
	{
		$o = array();
		$o[''] = __('Automatic', 'cookiecode');
		$o['nl'] = __('Dutch', 'cookiecode');
		$o['nlinf'] = __('Dutch (informal)', 'cookiecode');
		$o['en'] = __('English', 'cookiecode');
		$o['de'] = __('German', 'cookiecode');
		$o['es'] = __('Spanish', 'cookiecode');
		$o['pt'] = __('Portuguese', 'cookiecode');
		$o['pl'] = __('Polish', 'cookiecode');
		$o['it'] = __('Italian', 'cookiecode');
		$o['vl'] = __('Flemish', 'cookiecode');
		$o['wa'] = __('Walloon', 'cookiecode');

		return $o;
    }
    
	/**
	 * Function to get script disable options
	 */
	function get_supported_script_disable_options()
	{
		$o = array();
		$o[''] = __('Logged in administrators', 'cookiecode');
		$o['users'] = __('All logged in users', 'cookiecode');
		$o['never'] = __('Never disable script', 'cookiecode');

		return $o;
	}
}