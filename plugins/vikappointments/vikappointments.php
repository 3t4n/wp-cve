<?php
/*
Plugin Name:  VikAppointments
Plugin URI:   https://vikwp.com/plugin/vikappointments
Description:  A professional tool for managing any kind of appointments.
Version:      1.2.11
Author:       E4J s.r.l.
Author URI:   https://vikwp.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  vikappointments
Domain Path:  /languages
*/

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// autoload dependencies
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';

// handle install/uninstall
register_activation_hook(__FILE__, array('VikAppointmentsInstaller', 'activate'));
register_deactivation_hook(__FILE__, array('VikAppointmentsInstaller', 'deactivate'));
register_uninstall_hook(__FILE__, array('VikAppointmentsInstaller', 'delete'));

// init Installer
add_action('init', array('VikAppointmentsInstaller', 'update'));
add_action('init', array('VikAppointmentsInstaller', 'onInit'));

/**
 * Fires after all automatic updates have run.
 * Completes the update scheduled in background.
 *
 * @param  array  $results  The results of all attempted updates.
 *
 * @since  1.1.11
 */
add_action('automatic_updates_complete', array('VikAppointmentsInstaller', 'automaticUpdate'));

/**
 * Filters whether to automatically update core, a plugin, a theme, or a language.
 * Used to automatically turn off the update in case a PRO version expired.
 *
 * @param  bool|null  $update  Whether to update. The value of null is internally used
 *                             to detect whether nothing has hooked into this filter.
 * @param  object     $item    The update offer.
 *
 * @since  1.1.11
 */
add_filter('auto_update_plugin', array('VikAppointmentsInstaller', 'useAutoUpdate'), 10, 2);

/**
 * Fires at the end of the update message container in each
 * row of the plugins list table.
 *
 * The dynamic portion of the hook name, `$file`, refers to the path
 * of the plugin's primary file relative to the plugins directory.
 *
 * @link   https://developer.wordpress.org/reference/hooks/in_plugin_update_message-file/
 *
 * @param  array  $data      An array of plugin metadata.
 * @param  array  $response  An array of metadata about the available plugin update.
 *
 * @since  1.1.11
 */
add_action('in_plugin_update_message-vikappointments/vikappointments.php', array('VikAppointmentsInstaller', 'getUpdateMessage'), 10, 2);

/**
 * Load plugin language only once all the plugins
 * have been loaded, so that they are able to use
 * the filters to extend the language functionalities.
 *
 * @since 1.1.6
 */
add_action('plugins_loaded', array('VikAppointmentsBuilder', 'loadLanguage'));

// init html helpers
VikAppointmentsBuilder::setupHtmlHelpers();
// init payment framework
VikAppointmentsBuilder::configurePaymentFramework();
// init sms framework
VikAppointmentsBuilder::configureSmsFramework();
// init mirroring functions for extendable files
VikAppointmentsBuilder::setupMirroring();
// setup hooks to extend the backup functionalities
VikAppointmentsBuilder::setupBackupSystem();
// setup hooks to be used for the configuration of the wizard
VikAppointmentsBuilder::setupWizard();
// fix scripts loading via AJAX
VikAppointmentsAssets::fixAjax();

// setup lite system
add_action('plugins_loaded', array('VikAppointmentsLiteManager', 'setup'));

// add support for Help tabs
add_action('current_screen', array('VikAppointmentsScreen', 'help'));
// add support for screen options
add_action('current_screen', array('VikAppointmentsScreen', 'options'));
// always attempt to save screen options
add_filter('set-screen-option', array('VikAppointmentsScreen', 'saveOption'), 10, 3);
/**
 * Due to WordPress 5.4.2 changes, we need to attach
 * VikAppointments to a dedicated hook in order to 
 * allow the update of the list limit.
 *
 * @since 1.1.7
 */
add_filter('set_screen_option_vikappointments_list_limit', array('VikAppointmentsScreen', 'saveOption'), 10, 3);

// init Session
add_action('init', array('JSessionHandler', 'start'), 1);
add_action('wp_logout', array('JSessionHandler', 'destroy'));

// filter page link to rewrite URI
add_action('plugins_loaded', function()
{
	global $pagenow;

	$app   = JFactory::getApplication(); 
	$input = $app->input;

	// check if the URI contains option=com_vikappointments
	if ($input->get('option') == 'com_vikappointments')
	{
		// make sure we are not contacting the AJAX and POST end-points
		if (!wp_doing_ajax() && $pagenow != 'admin-post.php')
		{
			/**
			 * Include page in query string only if we are in the back-end,
			 * because WordPress 5.5 seems to break the page loading in case
			 * that argument has been included in query string.
			 *
			 * It is not needed to include this argument in the front-end
			 * as the page should lean on the reached shortcode only.
			 *
			 * @since 1.1.7
			 */
			if ($app->isAdmin())
			{
				// inject page=vikappointments in GET superglobal
				$input->get->set('page', 'vikappointments');
			}
		}
		else
		{
			// inject action=vikappointments in GET superglobal for AJAX and POST requests
			$input->get->set('action', 'vikappointments');
		}
	}
	else if ($input->get('page') == 'vikappointments' || $input->get('action') == 'vikappointments')
	{
		// inject option=com_vikappointments in GET superglobal for internal component detection
		$input->get->set('option', 'com_vikappointments');
	}
});

// process the request and obtain the response
add_action('init', function()
{
	$app 	= JFactory::getApplication();
	$input 	= $app->input;

	/**
	 * Hook used to fetch the site pre-processing flag.
	 * When this flag is enabled, the plugin will try to dispatch the
	 * site controller within the "init" action. This is made by 
	 * fetching the shortcode assigned to the current URI.
	 *
	 * By disabling this flag, the site controller will be dispatched 
	 * with the headers already sent.
	 *
	 * @param 	boolean  $preprocess  The default preprocess flag.
	 *
	 * @since 	1.1.10
	 */
	$preprocess = apply_filters('vikappointments_site_preprocess', VIKAPPOINTMENTS_SITE_PREPROCESS);

	// if we are in the front-end, try to parse the URL to inject
	// option, view and args in the input request
	if ($app->isSite() && $preprocess)
	{
		// get post ID from current URL
		$id = url_to_postid(JUri::current());

		if ($id)
		{
			// get shortcode admin model
			$model = JModel::getInstance('vikappointments', 'shortcode', 'admin');
			// get shortcode searching by post ID (false to avoid returning a new item)
			$shortcode = $model->getItem(array('post_id' => $id), false);

			if ($shortcode)
			{
				// build args array using the shortcode attributes
				$args = (array) json_decode($shortcode->json, true);
				$args['view'] 	= $shortcode->type;
				$args['option'] = 'com_vikappointments';

				// inject the shortcode args into the input request
				foreach ($args as $k => $v)
				{
					// inject only if not defined
					$input->def($k, $v);
				}
			}
		}
	}

	/**
	 * Process VikAppointments only if it has been requested via GET or POST.
	 *
	 * The pre-process should occur only if we are in the back-end or in case
	 * the related flag is turned on, otherwise the pre-processing technique
	 * would still take effect for those URLs that own "com_vikappointments"
	 * set in request (@since 1.2).
	 */
	if (($app->isAdmin() || $preprocess) && ($input->get('option') == 'com_vikappointments' || $input->get('page') == 'vikappointments'))
	{
		VikAppointmentsBody::process();
	}
});

// handle AJAX requests for both logged and guest users
add_action('wp_ajax_vikappointments', 'handle_vikappointments_ajax');
add_action('wp_ajax_nopriv_vikappointments', 'handle_vikappointments_ajax');

/**
 * Callback used to handle AJAX requests coming
 * from both the front-end and back-end sections.
 *
 * @since 1.1.9
 */
function handle_vikappointments_ajax()
{
	// process controller request
	VikAppointmentsBody::getHtml();

	// die to get a valid response
	wp_die();
}

// setup admin menu
add_action('admin_menu', array('VikAppointmentsBuilder', 'setupAdminMenu'));

// register widgets
add_action('widgets_init', array('VikAppointmentsBuilder', 'setupWidgets'));

// handle shortcodes (SITE controller dispatcher)
add_shortcode('vikappointments', function($atts, $content = null)
{
	$app = JFactory::getApplication();

	/**
	 * Force the application client to "site" every time a shortcode is executed.
	 * 
	 * @since 1.2.6
	 */
	$app->setClient('site');

	// wrap attributes in a registry
	$args = new JObject($atts);

	// get the VIEW (empty if not set)
	$view = $args->get('view', '');

	// load the FORM of the view
	JLoader::import('adapter.form.form');
	$path = implode(DIRECTORY_SEPARATOR, array(VAPBASE, 'views', $view, 'tmpl', 'default.xml'));
	// raises an exception if the VIEW is not set
	$form = JForm::getInstance($view, $path);
	
	// get all the XML form fields
	$fields = $form->getFields();

	// filter the fields to get a list of allowed names
	$fields = array_map(function($f)
	{
		return (string) $f->attributes()->name;
	}, $fields);

	// inject query vars
	$input = $app->input;
	
	// since we are going to render the controller manually,
	// we need to push the option into $_REQUEST pool only
	// whether it hasn't been added yet
	$input->def('option', 'com_vikappointments');
	
	// Inject shortcode vars only if they are not set 
	// in the request. This is used to allow the navigation
	// between the pages.
	$input->def('view', $view);
	
	foreach ($fields as $k)
	{
		$input->def($k, $args->get($k));
	}

	/**
	 * When saving a shortcode block through Gutenberg,
	 * WordPress tries to reach the page to check what happens.
	 * The "confirmapp" view of VikAppointments, in case of
	 * no selected appointments, immediately redirects the
	 * users to the "serviceslist" page.
	 * This redirect breaks the request made by WordPress for the 
	 * validation of the page, which follows the new location.
	 * The code below prevents the execution of the controller
	 * in case the URI matches the REST API end-point.
	 *
	 * @since 1.1.5
	 * 
	 * The rest_get_url_prefix(), which should be equals to /wp-json is
	 * available only on websites with pretty permalinks enabled.
	 * On sites without pretty permalinks, the route is instead added
	 * to the URL as the rest_route parameter.
	 * 
	 * @since 1.2.9
	 */
	$rest_prefix = trailingslashit(rest_get_url_prefix());
	$is_rest_api = strpos($input->server->getString('REQUEST_URI', ''), $rest_prefix) !== false
		|| JUri::getInstance($input->server->getString('REQUEST_URI', ''))->hasVar('rest_route')
		|| strpos($input->server->getString('REQUEST_URI', ''), '/wp-admin/') !== false;

	if ($is_rest_api && in_array($input->get('view'), ['confirmapp']))
	{
		// return an empty string to prevent any redirects
		return '';
	}

	// dispatch the controller
	return VikAppointmentsBody::getHtml(true);
});

// the callback is fired before the VAP controller is dispatched
add_action('vikappointments_before_dispatch', function()
{
	$app 	= JFactory::getApplication();
	$user 	= Jfactory::getUser();

	// initialize timezone handler
	JDate::getDefaultTimezone();
	// date_default_timezone_set($app->get('offset', 'UTC'));

	// check if the user is authorised to access the back-end (only if the client is 'admin')
	if ($app->isAdmin() && !$user->authorise('core.manage', 'com_vikappointments'))
	{
		if ($user->guest)
		{
			// if the user is not logged, redirect to login page
			$app->redirect('index.php');
			exit;
		}
		else
		{
			// otherwise raise an exception
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}
	}

	if ($app->isAdmin())
	{
		// require helper files
		require_once implode(DIRECTORY_SEPARATOR, [VAPADMIN, 'helpers', 'vikappointments.php']);

		// remove expired credit cards
		AppointmentsHelper::removeExpiredCreditCards();
	}
});

// the callback is fired before displaying DASHBOARD view
add_action('vikappointments_before_display_vikappointments', function()
{
	$app  = JFactory::getApplication();
	$user = JFactory::getUser();

	// make sure we are not doing AJAX, we are in the back-end and the user is an administrator
	if (!wp_doing_ajax() && $app->isClient('administrator') && $user->authorise('core.admin', 'com_vikappointments'))
	{
		// push shortcodes button within the toolbar
		JToolbarHelper::shortcodes('com_vikappointments');
	}
});

/**
 * Before displaying the Calendar view, check if we should
 * introduce the toolbar button used to manage the ACL and
 * the shortcode of the program.
 *
 * @since 1.1.5
 */
add_action('vikappointments_before_display_calendar', function()
{
	$app    = JFactory::getApplication();
	$user   = JFactory::getUser();
	$config = VAPFactory::getConfig();

	// if we are not doing AJAX, push shortcodes button within the toolbar
	if (!wp_doing_ajax() && $config->get('deftask') == 'calendar' && $app->isClient('administrator') && $user->authorise('core.admin', 'com_vikappointments'))
	{
		// add support for shortcodes management
		JToolbarHelper::shortcodes('com_vikappointments');
		// add support for ACL management
		JToolBarHelper::preferences('com_vikappointments');
	}
});

// instead using the default server timezone, try to use the one
// specified within the WordPress configuration
add_filter('vik_date_default_timezone', function($timezone)
{
	// return JFactory::getApplication()->get('offset', $timezone);
	return $timezone;
});

// the callback is fired once the VAP controller has been dispatched
add_action('vikappointments_after_dispatch', function()
{	
	// load assets after dispatching the controller to avoid
	// including JS and CSS when an AJAX function exits or dies
	VikAppointmentsAssets::load();

	// load javascript core
	JHtml::fetch('behavior.core');

	// restore standard timezone
	// date_default_timezone_set(JDate::getDefaultTimezone());

	/**
	 * WordPress has some reserved values that shouldn't be used
	 * in query string or within the forms, otherwise they could
	 * be used to rewrite the URLs of the website.
	 *
	 * In example, by using the 'day' argument in query string, 
	 * WordPress will start searching for POSTS that were created
	 * on the specified day (of the month), completely ignoring
	 * whether the current URL is used by a shortcode.
	 *
	 * For this reason, within the site section of VikAppointments,
	 * we should unset all the reserved arguments from the superglobals
	 * once the plugin finished using them.
	 *
	 * @link https://codex.wordpress.org/WordPress_Query_Vars
	 */

	$app = JFactory::getApplication();

	if ($app->isSite())
	{
		// define here the list of all the reserved arguments
		// that are used by VikAppointments
		$reserved_args_for_date_query = array(
			// reserved by WordPress
			'year',
			'day',
			'hour',
			// reserved by third-party plugins
			'people',
		);

		foreach ($reserved_args_for_date_query as $arg)
		{
			// unset argument from REQUEST
			$app->input->delete($arg);
			// unset argument from GET
			$app->input->get->delete($arg);
			// unset argument from POST
			$app->input->post->delete($arg);
		}
	}

	/**
	 * When the headers have been sent or when the request is AJAX
	 * the assets (CSS and JS) are appended into the document after
	 * the response is dispatched by the controller.
	 * Obviously only in case the controller doesn't manually exit.
	 */
});

// End-point for front-end post actions.
// The end-point URL must be built as .../wp-admin/admin-post.php
// and requires $_POST['action'] == 'vikappointments' to be submitted through a form or GET.
add_action('admin_post_vikappointments', 'handle_vikappointments_endpoint'); 			// if the user is logged in
add_action('admin_post_nopriv_vikappointments', 'handle_vikappointments_endpoint'); 	// if the user in not logged in

// handle POST end-point
function handle_vikappointments_endpoint()
{
	// get PLAIN response
	echo VikAppointmentsBody::getResponse();
}

// Hook used to access the PAGE details when a user is 
// creating or updating it. This is helpful to make a relation
// between the page and the injected shortcode.
add_action('save_post', function($post_id)
{
	// get model to access all the existing shortcodes
	$model = JModel::getInstance('vikappointments', 'shortcodes', 'admin');
	$shortcodes = $model->all(array('id', 'shortcode', 'post_id'));

	// get post data
	$post = get_post($post_id);

	/**
	 * Added support for private posts and future schedules.
	 *
	 * @since 1.1.2
	 */
	$accepted = array(
		'publish',
		'private',
		'future',
	);

	/**
	 * Check if we are editing a child post as Gutenberg 
	 * seems to use always the inherit status, which 
	 * refers to a post parent.
	 */
	if (!in_array($post->post_status, $accepted) && !empty($post->post_parent) && $post->post_parent != $post_id)
	{
		// fallback to obtain parent post data
		$post = get_post($post->post_parent);

		/**
		 * Use new post ID.
		 *
		 * @since 	1.1  Fixed post ID property name.
		 */
		$post_id = $post->ID;
	}

	if (!in_array($post->post_status, $accepted))
	{
		// ignore drafts auto-save
		return;
	}

	// get shortcode model
	$shortcodeModel = JModel::getInstance('vikappointments', 'shortcode', 'admin');

	/**
	 * Since we need unique post IDs, all the shortcodes
	 * that are assigned to the specified $post_id should
	 * be detached.
	 */
	foreach ($shortcodes as $data)
	{
		if ($data->post_id == $post_id)
		{
			// The post is already assigned to a shortcode.
			// Unset it to avoid duplicated.
			$data->post_id = 0;
			$shortcodeModel->save($data);
		}
	}
	
	// iterate the shortcodes
	foreach ($shortcodes as $data)
	{
		// check if the content of the post contains the shortcode
		if (strpos($post->post_content, html_entity_decode($data->shortcode)) !== false)
		{
			// inject the POST ID
			$data->post_id = $post_id;

			// update shortcode
			$shortcodeModel->save($data);

			// stop iterating
			return;
		}
	}
});

// Hook used to unset temporarily the relationship
// between the trashed post and the shortcode.
add_action('trashed_post', function($post_id)
{
	// get shortcode model
	$model = JModel::getInstance('vikappointments', 'shortcode', 'admin');

	// get the shortcode attached to the trashed post ID
	$item = $model->getItem(array('post_id' => $post_id), false);

	// if the item exists, temporarily detach the relationship
	if ($item)
	{
		$item->post_id 		= 0;
		$item->tmp_post_id 	= $post_id;

		$model->save($item);
	}
});

// Hook used to restore permanently the relationship
// between the untrashed post and the shortcode.
add_action('untrashed_post', function($post_id)
{
	// get shortcode model
	$model = JModel::getInstance('vikappointments', 'shortcode', 'admin');

	// get the shortcode attached to the untrashed post ID
	$item = $model->getItem(array('tmp_post_id' => $post_id), false);

	// if the item exists, re-attach the relationship
	if ($item)
	{
		$item->post_id 		= $post_id;
		$item->tmp_post_id 	= 0;

		$model->save($item);
	}
});

// Hook used to temporarily detach the relationship
// between the deleted post and the shortcode.
add_action('deleted_post', function($post_id)
{
	// get shortcode model
	$model = JModel::getInstance('vikappointments', 'shortcode', 'admin');

	// get the shortcode attached to the trashed post ID
	$item = $model->getItem(array('tmp_post_id' => $post_id), false);

	// If no item found, the "trash" feature is probably disabled.
	// Try to take a look for a shortcode with an active relationship.
	if (!$item)
	{
		$item = $model->getItem(array('post_id' => $post_id), false);
	}

	// if the item exists, permanently detach the relationship
	if ($item)
	{
		$item->post_id 		= 0;
		$item->tmp_post_id 	= 0;

		$model->save($item);
	}
});

if (JFactory::getApplication()->isAdmin() && !wp_doing_ajax())
{
	/**
	 * @todo should we restrict these filters to the post management pages only?
	 */

	VikAppointmentsLoader::import('system.mce');
	VikAppointmentsLoader::import('system.gutenberg');

	// add new buttons
	add_filter('mce_buttons', array('VikAppointmentsTinyMCE', 'addShortcodesButton'));

	// load the button handlers
	add_filter('mce_external_plugins', array('VikAppointmentsTinyMCE', 'registerShortcodesScript'));

	// add support for Gutenberg shortcode block
	add_action('init', array('VikAppointmentsGutenberg', 'registerShortcodesScript'));
}

/**
 * Dispatch the uninstallation of VikAppointments
 * every time a new blog (multisite) is deleted.
 *
 * Fires after the site is deleted from the network (WP 4.8.0 or higher).
 *
 * @param 	integer  $blog_id 	The site ID.
 * @param 	boolean  $drop 		True if site's tables should be dropped. Default is false.
 */
add_action('deleted_blog', function($blog_id, $drop)
{
	VikAppointmentsInstaller::uninstall($drop);
}, 10, 2);

/**
 * Suppress WP Date Query warnings when visiting the views of VikAppointments as
 * they might use reserved arguments in query string with wrong values, such
 * as 'day' with UNIX timestamps.
 *
 * @param 	boolean  $trigger   Whether to trigger the error for _doing_it_wrong() calls. Default true.
 * @param 	string   $function  The function that was called.
 * @param 	string   $message   A message explaining what has been done incorrectly.
 * @param 	string   $version   The version of WordPress where the message was added.
 */
add_filter('doing_it_wrong_trigger_error', function($show, $function, $message, $version)
{
	$input = JFactory::getApplication()->input;

	// suppress any WP_Date_Query error messages in VikAppointments
	if ($function == 'WP_Date_Query' && $input->get('option') == 'com_vikappointments')
	{
		// suppress the error
		return false;
	}

	// keep the current value otherwise
	return $show;
}, 10, 4);

/**
 * Once the plugins have been loaded, evaluates to execute the
 * scheduled cron jobs.
 *
 * Scheduling is processed in case a cron job is hitting wp-cron file
 * or in case a user is visiting the website.
 * 
 * @since 	1.2.3   Schedules a different hook for each cron.
 * @since   1.2.11  Extremely decreased the priority to make sure any other plugin ended
 *                  the registration of custom schedule intervals.
 */
add_action('plugins_loaded', array('VikAppointmentsCron', 'setup'), PHP_INT_MAX);

/**
 * Filters the action links displayed for each plugin in the Plugins list table.
 * Hook used to filter the "deactivation" link and ask a feedback every time that
 * button is clicked.
 *
 * @param 	array   $actions      An array of plugin action links. By default this can include 'activate',
 *                                'deactivate', and 'delete'. With Multisite active this can also include
 *                                'network_active' and 'network_only' items.
 * @param 	string  $plugin_file  Path to the plugin file relative to the plugins directory.
 * @param 	array   $plugin_data  An array of plugin data. See `get_plugin_data()`.
 * @param 	string  $context      The plugin context. By default this can include 'all', 'active', 'inactive',
 *                                'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
 *
 * @since 	1.1.2
 */
add_filter('plugin_action_links', array('VikAppointmentsFeedback', 'deactivate'), 10, 4);

/**
 * Adjusts the timezone of the website before dispatching
 * a widget as we are currently outside of the main plugin and
 * the timezone have probably been restored to the default one.
 *
 * @param 	string 	 $id       The widget ID (path name).
 * @param 	JObject  &$params  The widget configuration registry.
 *
 * @since 	1.1.3
 */
add_action('vik_widget_before_dispatch_site', function($id, &$params)
{
	// initialize timezone handler
	JDate::getDefaultTimezone();
	// date_default_timezone_set(JFactory::getApplication()->get('offset', 'UTC'));
}, 10, 2);

/**
 * Restores the timezone of the website after dispatching
 * a widget in order to avoid strange behaviors with other plugins.
 *
 * @param 	string 	$id     The widget ID (path name).
 * @param 	string  &$html  The HTML of the widget to display.
 *
 * @since 	1.1.3
 */
add_action('vik_widget_after_dispatch_site', function($id, &$html)
{
	// restore standard timezone
	// date_default_timezone_set(JDate::getDefaultTimezone());	
}, 10, 2);

/**
 * Action triggered before loading the text domain.
 * Loads the language handlers when needed from a 
 * different application client.
 *
 * @param 	string 	$domain    The plugin text domain to look for.
 * @param 	string 	$basePath  The base path containing the languages.
 * @param 	mixed   $langtag   An optional language tag to use.
 *
 * @since 	1.2
 */
add_action('vik_plugin_before_load_language', function($domain, $basePath, $langtag)
{
	if ($domain != 'vikappointments')
	{
		// do not go ahead
		return;
	}

	$app  = JFactory::getApplication();
	$lang = JFactory::getLanguage();

	$handler = VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;

	// check if we are in the site client and the system
	// needs to load the language used in the back-end
	if ($app->isSite() && $basePath == JPATH_ADMINISTRATOR)
	{
		// load back-end language handler
		$lang->attachHandler($handler . 'admin.php', $domain);
	}
	// check if we are in the admin client and the system
	// needs to load the language used in the front-end
	else if ($app->isAdmin() && $basePath == JPATH_SITE)
	{
		// load front-end language handler
		$lang->attachHandler($handler . 'site.php', $domain);
	}
}, 10, 3);

/**
 * Added support for Loco Translate.
 * In case some translations have been edited by using this plugin,
 * we should look within the Loco Translate folder to check whether
 * the requested translation is available.
 *
 * @param 	boolean  $loaded  True if the translation has been already loaded.
 * @param 	string 	 $domain  The plugin text domain to load.
 *
 * @return 	boolean  True if a new translation is loaded.
 *
 * @since 	1.1.6
 */
add_filter('vik_plugin_load_language', function($loaded, $domain)
{
	// proceed only in case the translation hasn't been loaded
	// and Loco Translate plugin is installed
	if (!$loaded && is_dir(WP_LANG_DIR . DIRECTORY_SEPARATOR . 'loco'))
	{
		// Build LOCO path.
		// Since load_plugin_textdomain accepts only relative paths, 
		// we should go back to the /wp-contents/ folder first.
		$loco = implode(DIRECTORY_SEPARATOR, array('..', 'languages', 'loco', 'plugins'));

		// try to load the plugin translation from Loco folder
		$loaded = load_plugin_textdomain($domain, false, $loco);
	}

	return $loaded;
}, 10, 2);

/**
 * Downloads the RSS feeds after loading the dashboard of VikAppointments.
 *
 * @since 1.1.9
 */
add_action('vikappointments_after_display_vikappointments', array('VikAppointmentsRssFeeds', 'download'));

/**
 * Trigger event to allow the plugins to include custom HTML within the view. 
 * It is possible to return an associative array to group the HTML strings
 * under different fieldsets. Plain/html string will be always pushed within
 * the "custom" fieldset instead.
 *
 * Displays the RSS configuration.
 *
 * @param 	mixed   $forms  The HTML to display.
 * @param 	mixed   $view 	The current view instance.
 *
 * @return 	mixed 	The HTML to display.
 *
 * @since 	1.1.9
 */
add_filter('vikappointments_display_view_config_global', array('VikAppointmentsRssFeeds', 'config'), 10, 2);

/**
 * Trigger event to allow the plugins to make something after saving
 * a record in the database.
 *
 * @param 	mixed 	$dummy  A dummy argument for BC.
 * @param 	array 	$args   The saved record.
 *
 * @since 	1.1.9
 */
add_action('vikappointments_after_save_config', array('VikAppointmentsRssFeeds', 'save'), 10, 2);

/**
 * Hook used to manipulate the RSS channels to which the plugin is subscribed.
 *
 * @param 	array    $channels  A list of RSS permalinks.
 * @param 	boolean  $status    True to return only the published channels.
 *
 * @since 	1.1.9
 */
add_filter('vikappointments_fetch_rss_channels', array('VikAppointmentsRssFeeds', 'getChannels'), 10, 2);

/**
 * Hook used to apply some stuff before returning the RSS reader.
 *
 * @param 	JRssReader  &$rss  The RSS reader handler.
 *
 * @since 	1.1.9
 */
add_action('vikappointments_before_use_rss', array('VikAppointmentsRssFeeds', 'ready'));

/**
 * Fixed the issue with wptexturize() function, which might convert special characters contained
 * within <script> tags into an HTML-encoded version (e.g. "&" became "&#038;").
 * 
 * @since 1.2.11
 */
add_filter('the_content', function($content)
{
	// look for any script tags
	if (preg_match_all("/<script(?:.*?)>(?:.*?)<\/script>/s", $content, $matches))
	{
		// scan all the scripts
		foreach ($matches[0] as $script)
		{
			// make sure the script contains "&#038;"
			if (strpos($script, '&#038;') === false)
			{
				continue;
			}

			// fix the script by reverting the plain "&"
			$fixedScript = str_replace('&#038;', '&', $script);

			// replace the bugged script from the content with the fixed one
			$content = str_replace($script, $fixedScript, $content);
		}
	}

	return $content;
}, PHP_INT_MAX);

/**
 * Re-adds support to legacy widgets within the Full-Site-Editing tool.
 * @link https://developer.wordpress.org/block-editor/how-to-guides/widgets/legacy-widget-block/
 * 
 * @since 1.2.11
 */
add_action('enqueue_block_editor_assets', function()
{
    wp_enqueue_script('wp-widgets');
    wp_add_inline_script('wp-widgets', 'wp.widgets.registerLegacyWidgetBlock()');
});
