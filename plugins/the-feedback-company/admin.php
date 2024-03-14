<?php
/**
 * This file is only loaded if we're on the admin dashboard
 */


// security - stop if file isn't accessed via WP
if (!defined('ABSPATH'))
	exit;


/**
 * Class to interface with the Wordpress Settings API
 *
 * https://codex.wordpress.org/Settings_API
 */
class feedbackcompany_admin
{
	// holds our settings
	private $options;

	// holds locales
	private $locales;

	// holds admin configuration tabs
	private $pages = array();

	/**
	 * these variables can be set to true in the object to perform certain actions
	 * during destruction time (on Wordpress shutdown)
	 */
	private $updated_oauth = false;
	private $updated_widgetmain = false;
	private $updated_widgetbar = false;
	private $updated_widgetsticky = false;
	private $updated_widgetproductsummary = false;
	private $updated_widgetproductextended = false;

	/**
	 * Constructor initializes everything we need for the admin dashboard
	 */
	public function __construct()
	{
		// add custom link to the plugin page
		add_filter('plugin_action_links_the-feedback-company/the-feedback-company.php', array($this, 'add_plugin_links'));

		// add configuration page to admin menu
		add_action('admin_menu', array($this, 'add_plugin_page'));

		// the next actions only need to happen on the admin settings page
		if ( !(isset($_GET['page']) && $_GET['page'] == 'feedbackcompany')
			&& !(isset($_POST['option_page']) && $_POST['option_page'] == 'feedbackcompany_option_group') )
			return;

		// fetch locales
		$this->locales = array(0 => null);
		if (feedbackcompany_wp::multilanguage_plugin())
		{
			$this->locales = feedbackcompany_wp::multilanguage_list();
		}

		// load admin resources on initialization of the options page
		add_action('admin_enqueue_scripts', array($this, 'init_assets'));

		// initialize configuration options
		add_action('admin_init', array($this, 'init_settings'));

		// register actions to request new widget uuids on changing of settings
		foreach ($this->locales as $code => $language)
		{
			if ($code)
				feedbackcompany_api_wp()->ext->locale_override = $code;

			// initialize the API once for each locale, so an API call is made
			// this call is also necessary for succesful upgrade, as this object converts old-style options to new
			feedbackcompany_api_wp()->oauth_refreshtoken();

			// set update actions for each locale
			$prefix = '';
			if ($code)
				$prefix = $code.'_';
			add_action('update_option_feedbackcompany_'.$prefix.'oauth_client_id', array($this, 'updated_oauth'), 10, 2);
			add_action('update_option_feedbackcompany_'.$prefix.'oauth_client_secret', array($this, 'updated_oauth'), 10, 2);
			add_action('update_option_feedbackcompany_'.$prefix.'mainwidget_size', array($this, 'updated_widgetmain'), 10, 2);
			add_action('update_option_feedbackcompany_'.$prefix.'mainwidget_amount', array($this, 'updated_widgetmain'), 10, 2);
			add_action('update_option_feedbackcompany_'.$prefix.'productreviewsextendedwidget_displaytype', array($this, 'updated_widgetproductextended'), 10, 2);

			feedbackcompany_api_wp()->ext->locale_override = null;
		}

		// register action to update widget settings on shutdown
		add_action('shutdown', array($this, 'updatedsettings'), 10, 2);

		// special action for handling TXT file download of error log
		if (isset($_GET['downloaderrorlog']))
			add_action('admin_init', array($this, 'downloaderrorlog'));

		// special action for handling deletion of the error log
		if (isset($_GET['deleteerrorlog']))
			add_action('admin_init', array($this, 'deleteerrorlog'));

		// special action for enabling debug mode
		if (isset($_GET['debugmode_disable']))
			add_action('admin_init', array($this, 'debugmode_disable'));

		// special action for enabling debug mode
		if (isset($_GET['debugmode_enable']))
			add_action('admin_init', array($this, 'debugmode_enable'));
	}

	/**
	 * Function adds a configuration link to the plugin page
	 */
	function add_plugin_links($links)
	{
		// build the url
		$url = esc_url( add_query_arg(
			'page',
			'feedbackcompany',
			get_admin_url() . 'admin.php'
		));
		$url = '<a href="'.$url.'">'.__('Settings').'</a>';

		// add the url to the links
		array_unshift($links, $url);

		return $links;
	}

	/**
	 * Function adds our settings page to the Wordpress menu
	 */
	public function add_plugin_page()
	{
		// this page will be under "Settings"
		add_options_page(
			'Feedback Company',
			'Feedback Company',
			'manage_options',
			'feedbackcompany',
			array($this, 'output_adminpage')
		);
	}

	/**
	 * Function loads the css/javascript assets for our settings page
	 */
	function init_assets($hook)
	{
		// init is only necessary if we're on the right page
		if ($hook != 'settings_page_feedbackcompany')
			return false;

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-tooltip');

		wp_enqueue_script('feedbackcompany_admin_javascript', plugins_url('js/admin.js?pluginver='.feedbackcompany_wp::version, __FILE__));
		wp_localize_script('feedbackcompany_admin_javascript', 'feedbackcompany_admin_javascript',
			array('plugins_url' => plugins_url('images/widgets-preview/en_US/', __FILE__))
		);

		wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('feedbackcompany_admin_stylesheet', plugins_url('css/admin.css?pluginver='.feedbackcompany_wp::version, __FILE__));
	}

	/**
	 * Helper function for setting default configuration options
	 *
	 * if $key has no value, then $value is set
	 *
	 * @param string $key - option key
	 * @param string $value - option default value
	 */
	public function option_set_default($key, $value)
	{
		if (get_option($key) === false)
			update_option($key, $value);
	}

	/**
	 * This function defines all settings for our plugin, and loads the defaults and registers callbacks for output
	 */
	public function init_settings()
	{
		foreach ($this->locales as $code => $language)
		{
			// set locale override
			feedbackcompany_api_wp()->ext->locale_override = $code;

			// array that holds all our settings fields
			$fields = array();

			// set prefix for all our options
			$prefix = 'feedbackcompany_';
			if ($code !== 0)
			{
				$prefix .= $code.'_';
			}

			// name of our page
			$page = $prefix.'settings';
			$this->pages[$page] = $code;

			// set default options if not defined
			$this->option_set_default($prefix.'oauth_client_id', '');
			$this->option_set_default($prefix.'oauth_client_secret', '');
			$this->option_set_default($prefix.'invitation_enabled', 1);
			$this->option_set_default($prefix.'invitation_orderstatus', 'wc-completed');
			$this->option_set_default($prefix.'invitation_delay', '7');
			$this->option_set_default($prefix.'invitation_delay_unit', 'days');
			$this->option_set_default($prefix.'invitation_reminder_enabled', 1);
			$this->option_set_default($prefix.'invitation_reminder', '14');
			$this->option_set_default($prefix.'invitation_reminder_unit', 'days');
			$this->option_set_default($prefix.'mainwidget_size', 'small');
			$this->option_set_default($prefix.'mainwidget_amount', 0);
			$this->option_set_default($prefix.'stickywidget_enabled', 0);
			$this->option_set_default($prefix.'productreviewsextendedwidget_displaytype', 'sidebar');
			$this->option_set_default($prefix.'productreviewsextendedwidget_toggle_element', '');
			$this->option_set_default($prefix.'productreviews_enabled', false);

			// oauth setting fields
			$fields[$prefix.'section_oauth'] = array('section', 'Authentication Settings '.$language);
			$fields[$prefix.'oauth_client_id'] = array('text', 'Client ID');
			$fields[$prefix.'oauth_client_secret'] = array('text', 'Client secret');

			// woocommerce settings only if woocommerce is enabled
			if (feedbackcompany_wp::woocommerce_enabled())
			{
				$fields[$prefix.'section_woocommerce'] = array('section', 'WooCommerce Settings');
				$fields[$prefix.'invitation_enabled'] = array('boolean', 'Send review invitation');
				$fields[$prefix.'invitation_orderstatus'] = array('orderstatus', 'Order status');
				$fields[$prefix.'invitation_delay'] = array('timeframe', 'Invitation delay');
				$fields[$prefix.'invitation_reminder_enabled'] = array('boolean', 'Send review reminder');
				$fields[$prefix.'invitation_reminder'] = array('timeframe', 'Reminder delay');
				if (feedbackcompany_api_wp()->ext->get_client_option('productreviews_enabled'))
				{
					$fields[$prefix.'section_productreviewswidget'] = array('section', 'Product reviews widget');
					$fields[$prefix.'productreviewsextendedwidget_displaytype'] = array('displaytype', 'Product page widget');
					$fields[$prefix.'productreviewsextendedwidget_toggle_element'] = array('text', 'Widget toggle element');
				}
				else
				{
					$fields[$prefix.'section_productreviewsdisabled'] = array('section', 'Product reviews disabled');
				}
			}
			$fields[$prefix.'section_mainwidget'] = array('section', 'Badge widget');
			$fields[$prefix.'mainwidget_size'] = array('size', 'Size');
			$fields[$prefix.'mainwidget_amount'] = array('amount', 'Amount of reviews');
			$fields[$prefix.'mainwidget_shortcode'] = array('shortcode', 'Shortcode', 'feedbackcompany_badge');
			$fields[$prefix.'preview_mainwidget'] = array('widgetpreview', 'Preview');

			$fields[$prefix.'section_barwidget'] = array('section', 'Bar widget');
			$fields[$prefix.'barwidget_shortcode'] = array('shortcode', 'Shortcode', 'feedbackcompany_bar');
			$fields[$prefix.'preview_barwidget'] = array('widgetpreview', 'Preview');

			$fields[$prefix.'section_stickywidget'] = array('section', 'Floating widget');
			$fields[$prefix.'stickywidget_enabled'] = array('boolean', 'Enabled');
			$fields[$prefix.'preview_stickywidget'] = array('widgetpreview', 'Preview');

			// holds current section id while we loop over settings
			$setting_section_id = null;

			// loop over all form fields and sections
			foreach ($fields as $name => $field)
			{
				// if this entry is a section, add it with the appropriate callback
				if ($field[0] == 'section')
				{
					add_settings_section(
						$name,
						$field[1],
						array($this, 'section_output'),
						$page
					);
					$setting_section_id = $name;
				}
				// if this entry is a form field, register the setting and add the field with the appropriate callback
				else
				{
					// for timeframe fields, also register the _unit setting
					if ($field[0] == 'timeframe')
						register_setting('feedbackcompany_option_group', $name.'_unit');

					register_setting(
						'feedbackcompany_option_group',
						$name,
						array($this, 'sanitize')
					);

					add_settings_field(
						$name,
						$field[1],
						array($this, 'field_output_'.$field[0]),
						$page,
						$setting_section_id,
						array('name' => $name, 'desc' => isset($field[2]) ? $field[2] : '')
					);
				}
			}
		}

		// remove locale override
		feedbackcompany_api_wp()->ext->locale_override = null;
	}

	/**
	 * This function is called on form save to sanitize data
	 *
	 * we're not using this, so return the original data
	 */
	public function sanitize($input)
	{
		return $input;
	}

	/**
	 * Function to return all API errors
	 *
	 * This is a helper function for below, but is also called later to find out
	 * if we need to display a download button or not
	 */
	public function errorlog()
	{
		global $wpdb;
		$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
			return array();

		return $wpdb->get_results("SELECT * FROM `$table_name`");
	}

	/**
	 * Function is called when the user has chosen to download the error log
	 *
	 * This function completely overrides default Wordpress output
	 * and instead serves a TXT file with our API errors
	 */
	public function downloaderrorlog()
	{
		// send error log as TXT file to browser
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=feedbackcompany-apilog.txt");
		header("Pragma: no-cache");
		header("Expires: 0");
		$errorlog = $this->errorlog();
		foreach ($errorlog as $error)
		{
			$eol = "\r\n";
			echo $error->timestamp.$eol
				. $error->url.$eol
				. 'Call: '.$error->call.$eol
				. 'Response: '.$error->response.$eol.$eol;
		}

		// exit here, to suppress normal Wordpress output
		exit();
	}

	/**
	 * Function is called when the user has chosen to delete the error log
	 * Deletes content from the errorlog table
	 */
	public function deleteerrorlog()
	{
		global $wpdb;
		$table_name = $wpdb->prefix.'feedbackcompany_errorlog';
		$wpdb->query("TRUNCATE TABLE $table_name");
		wp_redirect(admin_url('options-general.php?page=feedbackcompany'));
		exit();
	}

	/**
	 * Function is called when user has chosen to enable debug mode
	 */
	public function debugmode_enable()
	{
		feedbackcompany_api_wp()->ext->update_option('debug', true);
		wp_redirect(admin_url('options-general.php?page=feedbackcompany'));
		exit();
	}

	/**
	 * Function is called when user has chosen to disable debug mode
	 */
	public function debugmode_disable()
	{
		feedbackcompany_api_wp()->ext->update_option('debug', false);
		wp_redirect(admin_url('options-general.php?page=feedbackcompany'));
		exit();
	}

	/**
	 * Output function for the settings page of the current version of this plugin
	 */
	public function output_adminpage()
	{
		echo '<div class="wrap">';
		echo '<img align="right" style="width: 30vw; max-width: 300px; max-height: 28px; margin-top: 10px; margin-bottom: -100%;" src="'.plugins_url('/images/feedbackcompany-logo.svg', __FILE__).'">';
		echo '<h1>Feedback Company</h1>';

		// admin notice about multiple
		if (feedbackcompany_wp::multilanguage_plugin())
		{
			echo '<h2>Multilanguage support</h2>';
			echo '<p>Because you\'re using a supported multilanguage plugin, you can configure different API keys and settings per language.</p>';
		}

		echo '<form onsubmit="return feedbackcompany_validateform();" method="post" action="options.php">';

		// This prints out all settings fields and calls the appropriate callbacks
		settings_fields('feedbackcompany_option_group');
		foreach ($this->pages as $page => $code)
		{
			if ($code)
				echo '<h2 data-tab="'.$page.'" class="feedbackcompany_tab" data-prefix="'.$code.'">'.$this->locales[$code].'</h2>';
		}
		foreach ($this->pages as $page => $code)
		{
			if ($code)
			{
				// set locale as override
				feedbackcompany_api_wp()->ext->locale_override = $code;

				echo '<div data-tabcontent="'.$page.'" class="feedbackcompany_tabcontent">';
			}

			// admin notice if there's no oauth details entered
			if (!feedbackcompany_api_wp()->ext->get_locale_option('oauth_client_id', $code)
				|| !feedbackcompany_api_wp()->ext->get_locale_option('oauth_client_secret', $code))
			{
				echo '<div class="feedbackcompany-notice-ok"><p>';
				echo 'This plugin requires a Feedback Company account. Please visit <a target="_blank" href="http://www.feedbackcompany.com/">www.feedbackcompany.com</a> to register and enter the OAuth details you receive below.';
				echo '</p></div>';
			}
			// admin error if there is oauth details entered, but we weren't able to get an access token
			elseif (!feedbackcompany_api_wp()->ext->get_client_option('access_token', feedbackcompany_api_wp()->ext->get_locale_option('oauth_client_id', $code)))
			{
				echo '<div class="feedbackcompany-notice-error"><p>';
				echo 'Wordpress was unable to connect to Feedback Company using the OAuth client & secret below. Please verify your settings or contact us if you think this is in error.';
				echo '</p></div>';
			}

			do_settings_sections($page);

			if ($code)
			{
				feedbackcompany_api_wp()->ext->locale_override = null;
				echo '</div>';
			}
		}

		// buttons for submit and downloading error logs
		echo '<p class="submit">';
		submit_button(null, 'primary', 'submit', false);
		echo '<a class="button-secondary right" onclick="jQuery(\'#feedbackcompany_debug\').show();">'.__('Customer support settings').'</a>';
		echo '</p>';

		echo '</form>';
		echo '</div>';

		// debug options are hidden by default, only to be used by tech support or webdevelopers
		echo '<div id="feedbackcompany_debug"';
		if (!feedbackcompany_api_wp()->ext->debug())
			echo ' class="hidden"';
		echo '>';
		echo '<h2>Feedback Company Customer support settings</h2>';
		echo '<p><strong>Warning:</strong> This section should only be used by Feedback Company customer support or experienced webdevelopers.</p>';

		// enable/disable debug mode
		echo '<p><strong>Debug mode</strong><br>';
		echo 'Debug mode logs all calls and responses to the Feedback Company API. ';
		if (feedbackcompany_api_wp()->ext->debug())
		{
			echo 'Debug mode is currently <strong>enabled</strong></p>';
			echo '<p><a class="button-secondary" href="?page=feedbackcompany&debugmode_disable">Disable debug mode</a> ';
		}
		else
		{
			echo 'Debug mode is currently <strong>disabled</strong></p>';
			echo '<p><a class="button-secondary" href="?page=feedbackcompany&debugmode_enable">Enable debug mode</a> ';
		}

		// download/delete the API log
		echo '<p><strong>API log</strong><br>';
		$num_errors = count($this->errorlog());
		echo 'There are <strong>'.$num_errors.'</strong> API calls in the debug log</p>';
		if (count($this->errorlog()))
		{
			echo '<p>';
			echo '<a class="button-secondary" href="?page=feedbackcompany&downloaderrorlog">Download API log file</a>';
			echo '<a class="button-secondary" href="?page=feedbackcompany&deleteerrorlog">Delete API log file</a>';
			echo '</p>';
		}
		echo '</div>';
	}

	/**
	 * Callback function for outputting a section
	 */
	public function section_output($section)
	{
		$section_id = substr($section['id'], strpos($section['id'], 'section_'));

		$tooltips = array(
			'section_oauth' => 'To get your client ID and client secret send an email to helpdesk@feedbackcompany.com',
			'section_woocommerce' => 'Configure which order status should trigger a review invitation and the delay before sending the review invitation and reminder. Also configure how to display the product reviews widget on the product page.',
			'section_productreviewswidget' => 'Configure how to display your product reviews widget. Visit https://www.feedbackcompany.com/knowledgebase/ for more information.',
			'section_mainwidget' => "Configure how to display your badge widget. You can see a preview below. To show the widget, go to the 'Appearance - Widget' or 'Appearance - Customize' page and drag-and-drop the widget into the desired section, or use the shortcode in the text editor of a post or page",
			'section_barwidget' => "Configure how to display your bar widget. You can see a preview below. To show the widget, go to the 'Appearance - Widget' or 'Appearance - Customize' page and drag-and-drop the widget into the desired section, or use the shortcode in the text editor of a post or page",
			'section_stickywidget' => "Configure how to display your floating widget. You can see a preview below. To show the widget, set 'Enabled' to 'yes'. It will automatically be added to your webpage",
			'section_productreviewsdisabled' => 'Here you can find the state and information of product review widgets',
		);

		if (isset($tooltips[$section_id]))
			echo '<span class="dashicons dashicons-editor-help" title="'.$tooltips[$section_id].'"></span>';
		echo '<br style="clear: both;">';

		// oauth has notice added if succesfully connected
		if ($section_id == 'section_oauth' && feedbackcompany_api_wp()->ext->get_locale_option('oauth_client_id'))
		{
			if (feedbackcompany_api_wp()->ext->get_client_option('access_token'))
				echo '<span class="feedbackcompany-notice-ok">&#10004; Successfully connected to Feedback Company.</span>';
			else
				echo '<span class="feedbackcompany-notice-error">&#10060; There was an error connecting to Feedback Company. Please check your credentials.</span>';
		}
		// product review has notice added if not enabled
		if ($section_id == 'section_productreviewsdisabled')
		{
			echo '<div style="float: left; margin-right: 20px;">';
			echo 'To enable your product reviews, please contact us at our email address <b>sales@feedbackcompany.com</b> or our phone number <b>+31 8 5273 6320</b>.';
			echo '<br>';
			echo 'You can check our page <a href="www.feedbackcompany.com/product-reviews">www.feedbackcompany.com/product-reviews</a> for more information about product reviews.';
			echo '</div>';
			echo '<br style="clear: both;">';
		}
	}

	/**
	 * Callback functions for outputting specific form fields
	 *
	 * These functions and their $args are determined in this::page_init()
	 */
	public function field_output_orderstatus($args)
	{
		$options = wc_get_order_statuses();
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_color($args)
	{
		$options = array('white' => 'white', 'blue' => 'blue');
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_size($args)
	{
		$options = array('small' => 'small', 'big' => 'big');
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_amount($args)
	{
		$options = array(0 => '0', 1 => '1', 2 => '2');
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_displaytype($args)
	{
		$options = array('sidebar' => 'sidebar', 'popup' => 'popup', 'inline' => 'inline');
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_boolean($args)
	{
		$options = array(1 => 'yes', 0 => 'no');
		$this->field_output_selectbox($args, $options);
	}
	public function field_output_shortcode($args)
	{
		echo '<input size="'.strlen($args['desc']).'" data-role="feedbackcompany-shortcode" '
			. 'id="shortcode_'.$args['name'].'" value="['.$args['desc'].']">';
		echo ' <a href="#" onclick="feedbackcompany_copytoclipboard(event, \'shortcode_'.$args['name'].'\');">copy</a>';
	}
	public function field_output_widgetpreview($args)
	{
		$prefix = substr($args['name'], 0, strpos($args['name'], 'preview_'));
		$widgetname = substr($args['name'], strpos($args['name'], 'preview_'));

		echo '<img id="'.$args['name'].'" data-prefix="'.$prefix.'" class="feedbackcompany_'.$widgetname.'">';
	}
	public function field_output_selectbox($args, $options)
	{
		echo '<select class="feedbackcompany-formfield" id="'.$args['name'].'" name="'.$args['name'].'">';
		foreach ($options as $value => $label)
			echo '<option value="'.$value.'" '
				. (esc_attr(get_option($args['name'])) == $value ? 'selected' : '')
				. '>'.esc_attr($label);
		echo '</select>';

		$this->field_output_description($args);
	}
	public function field_output_timeframe($args)
	{
		$options = array('minutes', 'hours', 'days', 'weekdays');
		echo '<input class="feedbackcompany-formfield" type="number" step="1" min="1" required id="'.$args['name'].'" name="'.$args['name'].'" value="';
		echo esc_attr(get_option($args['name']));
		echo '"> ';

		echo '<select class="feedbackcompany-formfield" id="'.$args['name'].'_unit" name="'.$args['name'].'_unit">';
		foreach ($options as $option)
			echo '<option value="'.$option.'" '.(get_option($args['name'].'_unit') == $option ? 'selected' : '').'>'.$option;
		echo '</select>';

		$this->field_output_description($args);
	}
	public function field_output_text($args)
	{
		echo '<input class="feedbackcompany-formfield" type="text" id="'.$args['name'].'" name="'.$args['name'].'" size="70" value="';
		echo esc_attr(get_option($args['name']));
		echo '" />';

		$this->field_output_description($args);
	}
	/**
	 * helper function for description fields
	 *
	 * note: description fields are no longer used, except for displaying error messages
	 */
	public function field_output_description($args)
	{
		// if description is set
		if (isset($args['desc']))
			echo '<p class="description">'.esc_attr($args['desc']).'</p>';
	}

	/**
	 * Functions for handling updated settings
	 *
	 * there are actions registered at the bottom of this file, to add actions if above settings are changed
	 * these actions don't register new widgets directly, because for each individual widget setting there is a seperate action
	 * to prevent any unnecessary calls, the action-functions set variables inside this object
	 * and on shutdown the 'updatedsettings' function is called, to perform the actual actions if settings have changed
	 *
	 * note that these functions are called on 'shutdown', rather than via the destructor, to ensure PHP 5.x compatibility
	 */
	public function updated_oauth($old_value, $new_value)
	{
		$this->updated_oauth = true;
	}
	public function updated_widgetmain($old_value, $new_value)
	{
		$this->updated_widgetmain = true;
	}
	public function updated_widgetproductextended($old_value, $new_value)
	{
		$this->updated_widgetproductextended = true;
	}

	/**
	 * This function is called on shutdown, to perform updates if needed
	 * determined by if action variables are set to true
	 */
	public function updatedsettings()
	{
		// update settings for each locale
		foreach ($this->locales as $code => $language)
		{
			// set locale as override
			if ($code)
				feedbackcompany_api_wp()->ext->locale_override = $code;

			// if oauth settings are updated, force new widgets to be registered
			$force_refresh = false;

			// actions to perform if certain settings have been updated
			if ($this->updated_oauth)
			{
				// clear the cache and access token
				feedbackcompany_api_wp()->clear_cache();

				// request an access token - this also sets the feedbackcompany_productreviews_enabled option
				feedbackcompany_api_wp()->oauth_refreshtoken();

				// if access token was not successfully retrieved, stop here
				if (!feedbackcompany_api_wp()->ext->get_client_option('access_token'))
					return;

				$force_refresh = true;
				$this->updated_widgetmain = true;
				$this->updated_widgetbar = true;
				$this->updated_widgetsticky = true;
				$this->updated_widgetproductsummary = true;
				$this->updated_widgetproductextended = true;
			}

			if ($this->updated_widgetmain)
			{
				feedbackcompany_api_wp()->register_widget_main(
					feedbackcompany_api_wp()->ext->get_locale_option('mainwidget_size'),
					feedbackcompany_api_wp()->ext->get_locale_option('mainwidget_amount'),
					$force_refresh
				);
			}

			if ($this->updated_widgetbar)
			{
				feedbackcompany_api_wp()->register_widget_bar($force_refresh);
			}

			if ($this->updated_widgetsticky)
			{
				feedbackcompany_api_wp()->register_widget_sticky($force_refresh);
			}

			if ($this->updated_widgetproductsummary && feedbackcompany_api_wp()->ext->get_client_option('productreviews_enabled'))
			{
				feedbackcompany_api_wp()->register_widget_productsummary($force_refresh);
			}

			if ($this->updated_widgetproductextended && feedbackcompany_api_wp()->ext->get_client_option('productreviews_enabled'))
			{
				feedbackcompany_api_wp()->register_widget_productextended(
					feedbackcompany_api_wp()->ext->get_locale_option('productreviewsextendedwidget_displaytype'),
					$force_refresh
				);
			}

			// remove locale override
			if ($code)
				feedbackcompany_api_wp()->ext->locale_override = null;
		}
	}
}

/**
 * initialize the class above during Wordpress init
 */
add_action('init', function() {
	new feedbackcompany_admin();
});
