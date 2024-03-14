<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  system
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Helper class to setup the plugin.
 *
 * @since 1.0
 */
class VikAppointmentsBuilder
{
	/**
	 * Loads the .mo language related to the current locale.
	 *
	 * @return 	void
	 */
	public static function loadLanguage()
	{
		$app = JFactory::getApplication();

		// the language file is located in /languages folder
		$path 	 = VIKAPPOINTMENTS_LANG;

		$handler = VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;
		$domain  = 'vikappointments';

		// init language
		$lang = JFactory::getLanguage();
		
		$lang->attachHandler($handler . 'system.php', $domain);
		
		if ($app->isAdmin())
		{
			$lang->attachHandler($handler . 'adminsys.php', $domain);
			$lang->attachHandler($handler . 'admin.php', $domain);
		}

		// always load site language handler as fallback
		$lang->attachHandler($handler . 'site.php', $domain);

		$lang->load($domain, $path);
	}

	/**
	 * Pushes the plugin pages into the WP admin menu.
	 *
	 * @return 	void
	 *
	 * @link 	https://developer.wordpress.org/resource/dashicons/#star-filled
	 */
	public static function setupAdminMenu()
	{
		JLoader::import('adapter.acl.access');
		$capability = JAccess::adjustCapability('core.manage', 'com_vikappointments');

		// set default plugin menu title
		$default_title = JText::translate('COM_VIKAPPOINTMENTS_MENU');
		// let other plugins can filter the menu title
		$title = apply_filters('vikappointments_menu_title', $default_title);

		add_menu_page(
			JText::translate('COM_VIKAPPOINTMENTS'),         // page title
			$title ? $title : $default_title,        // menu title
			$capability,                             // capability
			'vikappointments',                       // slug
			array('VikAppointmentsBody', 'getHtml'), // callback
			'dashicons-calendar-alt',				 // icon
			71									     // ordering
		);
	}

	/**
	 * Setup HTML helper classes.
	 * This method should be used to register custom function
	 * for example to render own layouts.
	 *
	 * @return 	void
	 */
	public static function setupHtmlHelpers()
	{
		// helper method to render calendars layout
		JHtml::register('renderCalendar', function($data)
		{
			JHtml::fetch('script', VAP_SITE_URI . 'assets/js/jquery-ui.min.js');
			JHtml::fetch('stylesheet', VAP_SITE_URI . 'assets/css/jquery-ui.min.css');

			$layout = new JLayoutFile('html.plugins.calendar', null, array('option' => 'com_vikappointments'));
			
			return $layout->render($data);
		});

		// helper method to get the plugin layout file handler
		JHtml::register('layoutfile', function($layoutId, $basePath = null, $options = array())
		{
			$input = JFactory::getApplication()->input;

			if (!isset($options['component']) && !$input->getBool('option'))
			{
				// force layout file in case there is no active plugin
				$options['component'] = 'com_vikappointments';
			}

			return new JLayoutFile($layoutId, $basePath, $options);
		});

		// helper method to include the system JS file
		JHtml::register('system.js', function()
		{
			static $loaded = 0;

			if (!$loaded)
			{
				// include only once
				$loaded = 1;

				$internalFilesOptions = array('version' => VIKAPPOINTMENTS_SOFTWARE_VERSION);

				JHtml::fetch('script', VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/system.js', $internalFilesOptions, array('id' => 'vap-sys-script'));
				JHtml::fetch('stylesheet', VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/system.css', $internalFilesOptions, array('id' => 'vap-sys-style'));

				JHtml::fetch('script', VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/bootstrap.min.js', $internalFilesOptions, array('id' => 'bootstrap-script'));
				JHtml::fetch('stylesheet', VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/bootstrap.lite.css', $internalFilesOptions, array('id' => 'bootstrap-lite-style'));
			}
		});

		// helper method to register select2 plugin
		JHtml::register('select2', function()
		{
			// stop loading select2 outside of VAP
			// VikAppointments::load_complex_select();
		});
	}

	/**
	 * This method is used to configure teh payments framework.
	 * Here should be registered all the default gateways supported
	 * by the plugin.
	 *
	 * @return 	void
	 */
	public static function configurePaymentFramework()
	{
		// push the pre-installed gateways within the payment drivers list
		add_filter('get_supported_payments_vikappointments', function($drivers)
		{
			$list = glob(VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR . '*.php');

			return array_merge($drivers, $list);
		});

		// load payment handlers when dispatched
		add_action('load_payment_gateway_vikappointments', function(&$drivers, $payment)
		{
			$classname = null;
			
			VikAppointmentsLoader::import('payments.' . $payment);

			switch ($payment)
			{
				case 'paypal':
					$classname = 'VikAppointmentsPayPalPayment';
					break;

				case 'paypal_express_checkout':
					$classname = 'VikAppointmentsPayPalExpressCheckoutPayment';
					break;

				case 'offline_credit_card':
					$classname = 'VikAppointmentsOfflineCreditCardPayment';
					break;

				case 'bank_transfer':
					$classname = 'VikAppointmentsBankTransferPayment';
					break;
			}

			if ($classname)
			{
				$drivers[] = $classname;
			}
		}, 10, 2);

		// echo directly the payment HTML as showPayment() only returns it
		add_action('vikappointments_payment_after_begin_transaction', function(&$payment, &$html)
		{
			echo $html;
		}, 10, 2);

		// manipulate response to be compliant with notifypayment task
		add_action('vikappointments_payment_after_validate_transaction', function(&$payment, &$status, &$response)
		{
			// manipulate the response to be compliant with the old payment system
			$response = [
				'verified'    => (int) $status->isVerified(),
				'tot_paid'    => $status->amount,
				'log'         => $status->log,
				'transaction' => $status->transaction,
			];
		}, 10, 3);
	}

	/**
	 * This method is used to configure the sms drivers framework.
	 * Here should be registered all the default drivers supported
	 * by the plugin.
	 *
	 * @return 	void
	 *
	 * @since 	1.2
	 */
	public static function configureSmsFramework()
	{
		// push the pre-installed drivers within the sms drivers list
		add_filter('get_supported_sms_drivers_vikappointments', function($drivers)
		{
			$list = glob(VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'sms' . DIRECTORY_SEPARATOR . '*.php');

			return array_merge($drivers, $list);
		});

		// load sms handlers when dispatched
		add_action('load_sms_driver_vikappointments', function(&$drivers, $driver)
		{
			$classname = null;
			
			VikAppointmentsLoader::import('sms.' . $driver);

			switch ($driver)
			{
				case 'clickatell':
					$classname = 'VikAppointmentsSmsClickatell';
					break;

				case 'clicksend':
					$classname = 'VikAppointmentsSmsClicksend';
					break;

				case 'cmtelecom':
					$classname = 'VikAppointmentsSmsCmtelecom';
					break;

				case 'smshosting':
					$classname = 'VikAppointmentsSmsHosting';
					break;

				case 'tellustalk':
					$classname = 'VikAppointmentsSmsTellustalk';
					break;
			}

			if ($classname)
			{
				$drivers[] = $classname;
			}
		}, 10, 2);
	}

	/**
	 * Registers all the widget contained within the modules folder.
	 *
	 * @return 	void
	 */
	public static function setupWidgets()
	{
		JLoader::import('adapter.module.factory');

		// load all the modules
		JModuleFactory::load(VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . 'modules');

		/**
		 * Loads also the widgets to display within the
		 * admin dashboard of WordPress.
		 *
		 * @since 1.1.9
		 */
		add_action('wp_dashboard_setup', function()
		{
			JLoader::import('adapter.dashboard.admin');

			// set up folder containing the widget to load
			$path = VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'dashboard';
			// define the classname prefix
			$prefix = 'JDashboardWidgetVikAppointments';

			try
			{
				// load and register widgets
				JDashboardAdmin::load($path, $prefix);
			}
			catch (Exception $e)
			{
				// silently suppress exception to avoid breaking the website

				if (VIKAPPOINTMENTS_DEBUG)
				{
					// propagate error in case of debug enabled
					throw $e;
				}
			}
		});
	}

	/**
	 * Configures the RSS feeds reader.
	 *
	 * @return 	JRssReader
	 *
	 * @since 	1.1.9
	 */
	public static function setupRssReader()
	{
		// autoload RSS handler class
		JLoader::import('adapter.rss.reader');

		/**
		 * Hook used to manipulate the RSS channels to which the plugin is subscribed.
		 *
		 * @param 	array    $channels  A list of RSS permalinks.
		 * @param 	boolean  $status    True to return only the published channels.
		 *
		 * @return 	array    A list of supported channels.
		 *
		 * @since 	1.1.9
		 */
		$channels = apply_filters('vikappointments_fetch_rss_channels', array(), true);

		if (VIKAPPOINTMENTS_DEBUG)
		{
			/**
			 * Filters the transient lifetime of the feed cache.
			 *
			 * @since 2.8.0
			 *
			 * @param 	integer  $lifetime  Cache duration in seconds. Default is 43200 seconds (12 hours).
			 * @param 	string   $filename  Unique identifier for the cache object.
			 */
			add_filter('wp_feed_cache_transient_lifetime', function($time, $url) use ($channels)
			{
				// in case of debug enabled, cache the feeds only for 60 seconds
				if ($url == $channels || in_array($url, $channels))
				{
					$time = 60;
				}

				return $time;
			}, 10, 2);
		}

		// instantiate RSS reader
		$rss = JRssReader::getInstance($channels, 'vikappointments');

		/**
		 * Hook used to apply some stuff before returning the RSS reader.
		 *
		 * @param 	JRssReader  &$rss  The RSS reader handler.
		 *
		 * @return 	void
		 *
		 * @since 	1.1.9
		 */
		do_action_ref_array('vikappointments_before_use_rss', array(&$rss));

		return $rss;
	}

	/**
	 * Extends the backup framework.
	 *
	 * @return 	void
	 *
	 * @since 	1.2.3
	 */
	public static function setupBackupSystem()
	{
		/**
		 * Anonymous function used to check whether the manifest includes the shortcodes import.
		 * 
		 * @param 	object  $manifest  The backup manifest.
		 * 
		 * @return 	boolean
		 */
		$hasShortcodes = function($manifest)
		{
			// look for the uninstall directive inside the manifest, which is mainly
			// used during the sample data installation
			if (isset($manifest->uninstall))
			{
				// iterate all uninstall queries
				foreach ((array) $manifest->uninstall as $query)
				{
					// look for a table that uninstall the shortcodes
					if (preg_match("/#__vikappointments_wpshortcodes\b/", $query))
					{
						return true;
					}
				}
			}

			// look for the directive containing the installation rules
			if (isset($manifest->installers))
			{
				// iterate all install rules
				foreach ((array) $manifest->installers as $rule)
				{
					// detect SQL File role
					if ($rule->role === 'sqlfile')
					{
						// check shortcodes into the file path
						$target = [$rule->data->path];
					}
					else if ($rule->role === 'sql')
					{
						// search into all the provided queries
						$target = (array) $rule->data;
					}
					else
					{
						// nothing to check
						$target = [];
					}

					foreach ($target as $tmp)
					{
						// check whether the current target mentions the shortcodes database table
						if (preg_match("/#__vikappointments_wpshortcodes\b/", $tmp))
						{
							return true;
						}
					}
				}
			}

			return false;
		};

		/**
		 * Trigger event to allow third party plugins to extend the backup import.
		 * This hook triggers before processing the import of an existing backup.
		 * 
		 * It is possible to throw an exception to prevent the import process.
		 * 
		 * Uninstalls all the pages that have been assigned to the existing shortcodes.
		 * 
		 * @param 	mixed   $status    Internal environment variable.
		 * @param 	object  $manifest  The backup manifest.
		 * @param 	string  $path      The path of the backup archive (uncompressed).
		 * 
		 * @since 	1.7.1
		 * 
		 * @throws 	Exception
		 */
		add_action('vikappointments_before_import_backup', function($status, $manifest, $path) use ($hasShortcodes)
		{
			// check whether the manifest includes the shortcodes installation
			$manifest->shortcodes = $hasShortcodes($manifest);

			if (empty($manifest->shortcodes))
			{
				// shortcodes not included within the backup, do not uninstall
				return;
			}

			// get shortcode admin model
			$model = JModel::getInstance('vikappointments', 'shortcodes', 'admin');

			// get all existing shortcodes
			$shortcodes = $model->all(array('createdon', 'post_id'));

			// iterate all shortcodes found
			foreach ($shortcodes as $shortcode)
			{
				// make sure the shortcode has been assigned to a post
				if ($shortcode->post_id)
				{
					// get post details
					$post = get_post((int) $shortcode->post_id);

					// convert shortcode creation date
					$shortcode->createdon = new JDate($shortcode->createdon);
					// convert post creation date
					$post->post_date_gmt = new JDate($post->post_date_gmt);

					// compare ephocs and make sure the post was not created before the shortcode
					if ((int) $shortcode->createdon->format('U') <= (int) $post->post_date_gmt->format('U'))
					{
						// permanently delete post
						wp_delete_post($post->ID, $force_delete = true);
					}
				}
			}
		}, 10, 3);

		/**
		 * Trigger event to allow third party plugins to extend the backup import.
		 * This hook triggers after processing the import of an existing backup.
		 * 
		 * It is possible to throw an exception to prevent the import process.
		 * 
		 * Assigns all the newly created shortcodes to new pages.
		 * 
		 * @param 	mixed   $status    Internal environment variable.
		 * @param 	object  $manifest  The backup manifest.
		 * @param 	string  $path      The path of the backup archive (uncompressed).
		 * 
		 * @since 	1.7.1
		 * 
		 * @throws 	Exception
		 */
		add_action('vikappointments_after_import_backup', function($status, $manifest, $path)
		{
			if (empty($manifest->shortcodes))
			{
				// shortcodes not included within the backup, do not install
				return;
			}

			// get shortcodes admin model
			$listModel = JModel::getInstance('vikappointments', 'shortcodes', 'admin');

			// get all existing shortcodes
			$shortcodes = $listModel->all('id');

			// get shortcode admin model
			$model = JModel::getInstance('vikappointments', 'shortcode', 'admin');

			// iterate all shortcodes found
			foreach ($shortcodes as $shortcode)
			{
				// assign the shortcode to a new page
				$model->addPage($shortcode->id);
			}
		}, 10, 3);

		/**
		 * Trigger event to allow third party plugins to choose what are the columns to dump
		 * and whether the table should be skipped or not.
		 * 
		 * Fires while attaching a rule to dump some SQL statements.
		 * 
		 * Used to avoid dumping the post ID to which the shortcodes are attached
		 * 
		 * @param 	boolean  $include   False to avoid including the table into the backup.
		 * @param 	array    &$columns  An associative array of supported database table columns,
		 *                              where the key is the column name and the value is a nested
		 *                              array holding the column information.
		 * @param 	string   $table     The name of the database table.
		 * 
		 * @since 	1.7.1
		 */
		add_filter('vikappointments_before_backup_dump_sql', function($include, &$columns, $table)
		{
			if (is_null($include))
			{
				$include = true;
			}

			// check if we are exporting the shortcodes
			if ($table === '#__vikappointments_wpshortcodes')
			{
				// avoid dumping the post ID column
				unset($columns['post_id'], $columns['tmp_post_id']);
			}

			return $include;
		}, 10, 3);
	}

	/**
	 * Sets up the wizard.
	 *
	 * @return 	void
	 *
	 * @since 	1.2.3
	 */
	public static function setupWizard()
	{
		add_action('vikappointments_setup_wizard', function($ret, $wizard)
		{
			// include path to load additional steps
			$wizard->addIncludePath(VIKAPPOINTMENTS_LIBRARIES . DIRECTORY_SEPARATOR . 'wizard');
		}, 10, 2);

		add_action('vikappointments_after_setup_wizard', function($ret, $wizard)
		{
			// add step to manage the shortcodes
			$wizard->addStep(new VAPWizardStepShortcodes());

			// add packages and subscriptions steps as dependency
			$wizard['shortcodes']->addDependency($wizard['syspack'], $wizard['syssubscr']);

			// add step to install sample data (after taxes widget)
			$wizard->addStepAfter(new VAPWizardStepSampleData(), 'taxes');
		}, 10, 2);
	}

	/**
	 * Registers all the events used to backup the extendable files when needed.
	 *
	 * @return 	void
	 */
	public static function setupMirroring()
	{
		/**
		 * Backup all the files below before accessing the configuration page.
		 *
		 * - custom CSS
		 * - theme CSS
		 * - mail templates
		 * - mail attachments
		 * - SMS gateways
		 */
		add_action('vikappointments_before_display_editconfig', function()
		{
			// import update manager
			VikAppointmentsLoader::import('update.manager');

			try
			{
				// backup CSS themes
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPBASE . '/assets/css/themes',
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/css/themes'
				);

				// backup mail attachments
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPMAIL_ATTACHMENTS,
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/mail/attachments'
				);

				// backup mail templates
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPBASE . '/helpers/mail_tmpls',
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/mail/tmpl'
				);
			}
			catch (Exception $e)
			{
				// raise error and avoid breaking the flow
				JFactory::getApplication()->enqueueMessage("Impossible to complete back-up.\n" . $e->getMessage(), 'error');
			}
		});

		/**
		 * Backup all the files below before accessing the configuration page.
		 *
		 * - custom CSS
		 * - customizer
		 */
		add_action('vikappointments_before_display_editconfigapp', function()
		{
			// import update manager
			VikAppointmentsLoader::import('update.manager');

			try
			{
				// backup custom CSS file
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPBASE . '/assets/css/vap-custom.css',
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/css'
				);

				// backup CSS customizer
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPBASE . '/assets/css/customizer',
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/css/customizer'
				);
			}
			catch (Exception $e)
			{
				// raise error and avoid breaking the flow
				JFactory::getApplication()->enqueueMessage("Impossible to complete back-up.\n" . $e->getMessage(), 'error');
			}
		});

		/**
		 * Declare an anonymous function for the back-up that should be applied
		 * within the dashboard, as the default view might be different.
		 * For this reason, we should apply the back-up when visitin the
		 * dashboard, the calendar and the weekly calendar.
		 *
		 * @since 1.1.6
		 */
		$dashboard_backup = function()
		{
			// check if we are doing an AJAX request
			if (!wp_doing_ajax())
			{
				// import update manager
				VikAppointmentsLoader::import('update.manager');

				try
				{
					// backup cron jobs
					VikAppointmentsUpdateManager::doBackup(
						// target to backup
						VIKAPPOINTMENTS_BASE . '/languages',
						// destination folder
						VAP_UPLOAD_DIR_PATH . '/languages'
					);
				}
				catch (Exception $e)
				{
					// raise error and avoid breaking the flow
					JFactory::getApplication()->enqueueMessage("Impossible to complete back-up.\n" . $e->getMessage(), 'error');
				}
			}
		};

		/**
		 * Backup all the files below before accessing the dashboard page.
		 *
		 * - languages
		 */
		add_action('vikappointments_before_display_vikappointments', $dashboard_backup);
		add_action('vikappointments_before_display_calendar', $dashboard_backup);
		add_action('vikappointments_before_display_caldays', $dashboard_backup);

		/**
		 * Backup all the files below before accessing the service management page.
		 *
		 * - mail attachments
		 *
		 * @since 1.2
		 */
		add_action('vikappointments_before_display_manageservice', function()
		{
			// import update manager
			VikAppointmentsLoader::import('update.manager');

			try
			{
				// backup mail attachments
				VikAppointmentsUpdateManager::doBackup(
					// target to backup
					VAPMAIL_ATTACHMENTS,
					// destination folder
					VAP_UPLOAD_DIR_PATH . '/mail/attachments'
				);
			}
			catch (Exception $e)
			{
				// raise error and avoid breaking the flow
				JFactory::getApplication()->enqueueMessage("Impossible to complete back-up.\n" . $e->getMessage(), 'error');
			}
		});
	}
}
