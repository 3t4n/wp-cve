<?php
/*

Plugin Name:	Feedback Company
Plugin URI:	https://feedbackcompany.com/
Description:	Integrates Feedback Company widgets, order registrations and product reviews in Wordpress/WooCommerce
Version:	3.3.2
Author:		Feedback Company
Author URI:	https://www.feedbackcompany.com/
License:	GPL2

WC requires at least: 6.0
WC tested up to: 8.0

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin. If not, see:
https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.

*/


/**
 * This is the main plugin file, which is always loaded by Wordpress
 * as long as the plugin is active
 */

// security - stop if this file isn't accessed via WP
if (!defined('ABSPATH'))
	exit;



/**
 * Class that holds all our static/public functions
 */
class feedbackcompany_wp
{
	const version = '3.3.2';
	const pluginfile = __FILE__;

	/**
	 * Array that holds all our current plugin options that are locale dependant
	 */
	const options_locale = array(
		'oauth_client_id',
		'oauth_client_secret',
		'invitation_enabled',
		'invitation_orderstatus',
		'invitation_delay',
		'invitation_delay_unit',
		'invitation_reminder_enabled',
		'invitation_reminder',
		'invitation_reminder_unit',
		'mainwidget_size',
		'mainwidget_amount',
		'stickywidget_enabled',
		'productreviewsextendedwidget_displaytype',
		'productreviewsextendedwidget_toggle_element',
	);

	/**
	 * Array that holds all our current plugin options that are client ID/key dependant
	 */
	const options_client = array(
		'access_token',
		'widget_uuid_main',
		'widget_id_main',
		'productreviews_enabled',
		'widget_uuid_bar',
		'widget_id_bar',
		'widget_uuid_sticky',
		'widget_id_sticky',
		'widget_uuid_product-summary',
		'widget_id_product-summary',
		'widget_uuid_product-extended',
		'widget_id_product-extended'
	);

	/**
	 * Function to check if a certain 3rd party plugin is enabled
	 */
	static function plugin_enabled($plugin)
	{
		static $active_plugins;
		static $network_plugins;

		if (is_multisite())
		{
			if ($network_plugins === null)
			{
				$network_plugins = get_site_option('active_sitewide_plugins');
			}

			if (isset($network_plugins[$plugin]))
			{
				return true;
			}
		}

		if ($active_plugins === null)
		{
			$active_plugins = get_option('active_plugins');
		}

		if (in_array($plugin, $active_plugins))
		{
			return true;
		}

		return false;
	}

	/**
	 * Function to check if WooCommerce is enabled
	 *
	 * this function additionally checks if the stone-age Feedback Company Connector plugin is enabled
	 * and instructs users to delete it
	 */
	static function woocommerce_enabled()
	{
		$ret = false;

		// if WooCommerce plugin is already loaded, this class exists and we don't need to look further
		if (class_exists('WooCommerce'))
		{
			$ret = true;
		}
		// if WooCommerce isn't loaded yet, see if it is in active plugins
		else
		{
			$ret = self::plugin_enabled('woocommerce/woocommerce.php');
		}

		// this check will have to stay active until the old connector plugin no longer works
		// in order to prevent double order registrations
		//
		// the original feedback-company-connector plugin is very outdated.
		// if it is still active in Wordpress, tell user to trash & burn it
		// unfortunately we cannot continue with WooCommerce integration as long as the old plugin is active
		if (self::plugin_enabled('the-feedback-company-connector/feedback.php')
			|| class_exists('WPfeedback'))
		{
			add_action('admin_notices', 'feedbackcompany_wp::admin_error_legacyplugin');
			$ret = false;
		}

		return $ret;
	}

	/**
	 * Function to check if a WordPress Multilanguage plugin is active
	 */
	static function multilanguage_plugin()
	{
		// WPML
		if (defined('ICL_SITEPRESS_VERSION')
			|| self::plugin_enabled('sitepress-multilingual-cms/sitepress.php'))
			return 'wpml';
		// Polylang
		if (defined('POLYLANG_BASENAME')
			|| self::plugin_enabled('polylang/polylang.php')
			|| self::plugin_enabled('polylang-pro/polylang.php'))
			return 'polylang';

		return false;
	}

	/**
	 * Function to retrieve a list of available languages
	 */
	static function multilanguage_list()
	{
		// WPML
		if (self::multilanguage_plugin() == 'wpml')
		{
			$ret = array();
			foreach (apply_filters('wpml_active_languages', NULL) as $language)
				$ret[$language['code']] = $language['translated_name'];

			return $ret;
		}
		// Polylang
		if (self::multilanguage_plugin() == 'polylang')
		{
			foreach (get_terms('term_language', array('hide_empty' => false)) as $term)
				$ret[substr($term->slug, 4)] = $term->name;

			return $ret;
		}

		return false;
	}

	/**
	 * Function to get current Wordpress language
	 */
	static function multilanguage_current()
	{
		// WPML
		if (self::multilanguage_plugin() == 'wpml' && defined('ICL_LANGUAGE_CODE'))
			return ICL_LANGUAGE_CODE;

		// Polylang
		if (self::multilanguage_plugin() == 'polylang' && function_exists('pll_current_language'))
			return pll_current_language();

		return null;
	}

	/**
	 * Function to determine the language of an order
	 */
	static function multilanguage_orderlanguage($order_id)
	{
		$lang = null;

		// WPML
		if (self::multilanguage_plugin() == 'wpml')
			$lang = get_post_meta($order_id, 'wpml_language', true);
		// Polylang
		if (self::multilanguage_plugin() == 'polylang')
			$lang = pll_get_post_language($order_id);

		// return $lang only if it is a value that not false, 0, etc. else return null
		return $lang ? $lang : null;
	}

	/**
	 * register the widget classes for the appropriate widgets with Wordpress
	 */
	static function widgets_init()
	{
		register_widget('feedbackcompany_widget_main');
		register_widget('feedbackcompany_widget_bar');
	}

	/**
	 * initialize our shortcodes and widgets
	 */
	static function init()
	{
		// register our shortcodes
		add_shortcode('feedbackcompany_badge', 'feedbackcompany_wp::callback_shortcode_widget_main');
		add_shortcode('feedbackcompany_bar', 'feedbackcompany_wp::callback_shortcode_widget_bar');
		// legacy (2.1, 2.2, 2.3) shortcode
		add_shortcode('feedback_company_merchant_reviews_widget', 'feedbackcompany_wp::callback_shortcode_widget_main');
		// legacy (1.x shortcodes)
		add_shortcode('feedbackcompany_summary', 'feedbackcompany_wp::callback_shortcode_widget_bar');
		add_shortcode('feedbackcompany_score', 'feedbackcompany_wp::callback_shortcode_widget_main');
		add_shortcode('feedbackcompany_reviews', 'feedbackcompany_wp::callback_shortcode_widget_main');
		add_shortcode('feedbackcompany_testimonial', 'feedbackcompany_wp::callback_shortcode_widget_main');

		// hook the function that outputs the sticky widget to the footer
		add_action('wp_footer', 'feedbackcompany_wp::callback_output_widget_sticky');
	}

	/**
	 * Callback functions for shortcode output
	 */
	static function callback_shortcode_widget_main($atts, $content = "")
	{
		return feedbackcompany_api_wp()->get_widget_main();
	}
	static function callback_shortcode_widget_bar($atts, $content = "")
	{
		return feedbackcompany_api_wp()->get_widget_bar();
	}

	/**
	 * Function to output the sticky widget
	 *
	 * called by filter: wp_footer
	 */
	static function callback_output_widget_sticky()
	{
		// only output the widget if enabled in the settings
		if (feedbackcompany_api_wp()->ext->get_locale_option('stickywidget_enabled'))
			feedbackcompany_api_wp()->output_widget_sticky();
	}

	/**
	 * admin notice function for legacy woocommerce connector check
	 *
	 * this displays an error if the user has the legacy FBC plugin enabled
	 * because the plugin is outdated and should be dropped
	 *
	 * can be dropped as soon as the old connector interface is no longer supported
	 */
	function admin_error_legacyplugin()
	{
		echo '<div id="message" class="error">'
			. '<p><img align="right" width="180" src="'.plugins_url('/images/feedbackcompany-logo.svg', __FILE__).'">'
			. '<strong>WARNING:</strong>  The "Feedback Company WooCommerce Connector" plugin is active in this Wordpress '
			. 'installation.  This plugin is outdated and will soon no longer work.  The functionality of this plugin has been '
			. 'integrated into the regular Feedback Company plugin, which is also active. '
			. 'In order to use the new plugin, all you have to do is deactivate & delete the old one.'
			. '<br><br>'
			. '<strong>Please <a href="plugins.php">deactivate and delete</a> the "Feedback Company WooCommerce Connector" plugin. '
			. '</strong></p></div>';
	}
}

/**
 * initialize our plugin during Wordpress init
 */
add_action('init', 'feedbackcompany_wp::init');
add_action('widgets_init', 'feedbackcompany_wp::widgets_init');



/**
 * Class which interfaces our Feedback Company API library with Wordpress
 */
class feedbackcompany_api_ext_wp
{
	// variable to override the Wordpress locale (used on admin dashboard)
	public $locale_override = null;

	/**
	 * Constructor checks if automatic migration is possible from previous 2.x versions
	 */
	function __construct()
	{
		// backwards compatibility - migrate oauth settings from 1.x to 2.x
		if ($this->get_option('options'))
		{
			$tmp = $this->get_option('options');
			if (isset($tmp['oauth_client_id']) && isset($tmp['oauth_client_secret']))
			{
				$this->update_option('oauth_client_id', $tmp['oauth_client_id']);
				$this->update_option('oauth_client_secret', $tmp['oauth_client_secret']);
			}

			$this->delete_option('options');
		}

		// backwards compatibility - merchant widget renamed between version 2.3.2 and 2.4
		if ($this->get_option('merchantreviewswidget_size'))
		{
			error_log('Feedback Company migrated config to 2.4');

			$this->update_option('mainwidget_size', $this->get_option('merchantreviewswidget_size'));
			$this->delete_option('merchantreviewswidget_size');
			$this->update_option('mainwidget_amount', $this->get_option('merchantreviewswidget_amount'));
			$this->delete_option('merchantreviewswidget_amount');
		}

		// backwards compatibility - all client_id specific options are prefixed with client_id as of 3.0
		if ($this->get_option('access_token'))
		{
			error_log('Feedback Company migrated config to 3.0');

			foreach (feedbackcompany_wp::options_client as $client_option)
			{
				$this->update_client_option($client_option, $this->get_option($client_option));
				$this->delete_option($client_option);
			}
		}

		// backwards compatibility - migrate previous WPML settings to 3.0 multilanguage settings
		if ($this->get_option('wordpressmultilanguage')
			|| (feedbackcompany_wp::multilanguage_plugin() && $this->get_option('oauth_client_id')))
		{
			error_log('Feedback Company migrated multilanguage config to 3.0');

			$migrate = $this->get_option('wordpressmultilanguage');

			// if there is a compatible multilanguage plugin, copy settings to all appropriate languages
			if (feedbackcompany_wp::multilanguage_plugin())
			{
				// if there is a matching locale for deprecated 'wordpressmultilanguage' setting, we can properly migrate
				// if there isn't, something is wrong and we'll migrate the base config to all languages
				if (!isset(feedbackcompany_wp::multilanguage_list()[$migrate]))
					$migrate = 'all';

				foreach (feedbackcompany_wp::multilanguage_list() as $code => $language)
				{
					if ($migrate != 'all' && $migrate != $code)
						continue;

					foreach (feedbackcompany_wp::options_locale as $locale_option)
					{
						$this->update_locale_option($locale_option, $this->get_option($locale_option), $code);
					}
				}

				// delete the non-locale specific options
				foreach (feedbackcompany_wp::options_locale as $locale_option)
				{
					$this->delete_option($locale_option);
				}
			}

			// delete the legacy option multilanguage option
			$this->delete_option('wordpressmultilanguage');
		}
	}

	/**
	 * wrapper function for Wordpress get_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 *
	 * @param string $key - the specific key to fetch
	 */
	function get_option($key)
	{
		$key = 'feedbackcompany_'.$key;

		$var = get_option($key);
		if ($var)
			return $var;

		return false;
	}

	/**
	 * wrapper function for Wordpress update_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 */
	function update_option($key, $value)
	{
		$key = 'feedbackcompany_'.$key;

		update_option($key, $value);
	}

	/**
	 * wrapper function for Wordpress delete_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 */
	function delete_option($key)
	{
		$key = 'feedbackcompany_'.$key;

		delete_option($key);
	}

	/**
	 * wrapper function for Wordpress get_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current locale is also prefixed, so these are locale-specific
	 *
	 * @param string $key - the specific key to fetch
	 */
	function get_locale_option($key, $locale = null)
	{
		if ($locale === null && $this->locale_override != null)
			$locale = $this->locale_override;

		if ($locale === null)
			$locale = feedbackcompany_wp::multilanguage_current();

		if ($locale)
			$key = $locale.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		$var = get_option($key);
		if ($var)
			return $var;

		return false;
	}

	/**
	 * wrapper function for Wordpress update_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current locale is also prefixed, so these are locale-specific
	 */
	function update_locale_option($key, $value, $locale = null)
	{
		if ($locale === null && $this->locale_override != null)
			$locale = $this->locale_override;

		if ($locale === null)
			$locale = feedbackcompany_wp::multilanguage_current();

		if ($locale)
			$key = $locale.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		update_option($key, $value);
	}

	/**
	 * wrapper function for Wordpress delete_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current locale is also prefixed, so these are locale-specific
	 */
	function delete_locale_option($key, $locale = null)
	{
		if ($locale === null && $this->locale_override != null)
			$locale = $this->locale_override;

		if ($locale === null)
			$locale = feedbackcompany_wp::multilanguage_current();

		if ($locale)
			$key = $locale.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		delete_option($key);
	}

	/**
	 * wrapper function for Wordpress get_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current client ID is also prefixed, so these are client-specific
	 *
	 * @param string $key - the specific key to fetch
	 */
	function get_client_option($key, $client_id = null)
	{
		if ($client_id === null)
			$client_id = $this->get_locale_option('oauth_client_id');

		if ($client_id)
			$key = $client_id.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		$var = get_option($key);
		if ($var)
			return $var;

		return false;
	}

	/**
	 * wrapper function for Wordpress update_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current client ID is also prefixed, so these are client-specific
	 */
	function update_client_option($key, $value, $client_id = null)
	{
		if ($client_id === null)
			$client_id = $this->get_locale_option('oauth_client_id');

		if ($client_id)
			$key = $client_id.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		update_option($key, $value);
	}

	/**
	 * wrapper function for Wordpress delete_option
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every option
	 * the current client ID is also prefixed, so these are client-specific
	 */
	function delete_client_option($key, $client_id = null)
	{
		if ($client_id === null)
			$client_id = $this->get_locale_option('oauth_client_id');

		if ($client_id)
			$key = $client_id.'_'.$key;

		$key = 'feedbackcompany_'.$key;

		delete_option($key);
	}

	/**
	 * wrapper function for Wordpress get_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every transient
	 */
	function get_cache($key)
	{
		return get_transient('feedbackcompany_'.$key);
	}

	/**
	 * wrapper function for Wordpress set_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every transient
	 */
	function set_cache($key, $value, $expiration)
	{
		set_transient('feedbackcompany_'.$key, $value, $expiration);
	}

	/**
	 * wrapper function for Wordpress delete_transient
	 *
	 * this function exists so 'feedbackcompany_' is automatically prefixed to every transient
	 */
	function delete_cache($key)
	{
		delete_transient('feedbackcompany_'.$key);
	}

	/**
	 * wrapper function for Wordpress plugins_url
	 *
	 * this returns the URL where the plugin directory lives
	 */
	function get_url()
	{
		return plugins_url('', __FILE__);
	}

	/**
	 * Function to check if debug mode is enabled
	 *
	 * called from the Feedback Company API library
	 * with debug mode enabled, all API calls and responses are logged
	 */
	function debug()
	{
		return $this->get_option('debug');
	}

	/**
	 * Function for logging API errors
	 *
	 * called from the Feedback Company API library
	 * this function creates the relevant database table if it does not exist yet
	 * api errors can be downloaded from the admin dashboard
	 *
	 * @param string $url - the URL called for the API request
	 * @param string $call - the data sent for the API request
	 * @param string $response - the data returned for the API request
	 */
	function log_apierror($url, $call, $response)
	{
		// create table if not exists
		// this is done here, rather than on installation/activation, because
		// API errors should never happen, thus there is no need to have this table
		// on every installation.  it is only for case of emergencies
		global $wpdb;
		$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			  `url` text NOT NULL,
			  `call` text NOT NULL,
			  `response` text NOT NULL
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$wpdb->insert($table_name, array('url' => $url, 'call' => $call, 'response' => $response));
	}
}



/**
 * Function to interface with the API library
 *
 * Calling this function returns an instance of the API library
 * This function is created to prevent the API library from being initiated multiple times
 */
function feedbackcompany_api_wp()
{
	static $fbcapi;
	if ($fbcapi !== null)
		return $fbcapi;

	// include feedback company php api
	require_once plugin_dir_path(__FILE__).'lib/feedbackcompany_api.php';
	$fbcapisettings = new feedbackcompany_api_ext_wp();
	$fbcapi = new feedbackcompany_api($fbcapisettings);

	return $fbcapi;
}


/**
 * Callback classes for Wordpress widgets. This uses the Widgets API
 *
 * Class renders the appropriate Feedback Company widget and settings form
 */
class feedbackcompany_widget_main extends WP_Widget
{
	function __construct()
	{
		parent::__construct(false, 'Feedback Company Badge Widget');
	}

	function widget($args, $instance)
	{
		echo $args['before_widget']
			. feedbackcompany_api_wp()->get_widget_main()
			. $args['after_widget'];
	}
	function form($instance)
	{
		echo '<p>Please use the menu option under \'Settings\' to change appearance of this widget.</p>';
	}
}
class feedbackcompany_widget_bar extends WP_Widget
{
	function __construct()
	{
		parent::__construct(false, 'Feedback Company Bar Widget');
	}

	function widget($args, $instance)
	{
		echo $args['before_widget']
			. feedbackcompany_api_wp()->get_widget_bar()
			. $args['after_widget'];
	}
	function form($instance)
	{
		echo '<p>This widget has no configuration.</p>';
	}
}


/**
 * dynamic loading
 *
 * following parts of the file are to dynamically load portions of this plugin
 * which aren't required per say
 */

/** load the WooCommerce portion of this plugin only if WooCommerce is active */
if (feedbackcompany_wp::woocommerce_enabled())
{
	require_once plugin_dir_path(__FILE__).'woocommerce.php';
}

/** admin settings portion of this plugin is only loaded if we're on the admin dashboard */
if (is_admin())
{
	require_once plugin_dir_path(__FILE__).'admin.php';
}

