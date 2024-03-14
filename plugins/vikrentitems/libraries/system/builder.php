<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	system
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Helper class to setup the plugin.
 *
 * @since 1.0
 */
class VikRentItemsBuilder
{
	/**
	 * Loads the .mo language related to the current locale.
	 *
	 * @return 	void
	 */
	public static function loadLanguage()
	{
		$app = JFactory::getApplication();

		/**
		 * @since 	1.0.2 	All the language files have been merged 
		 * 					within a single file to be compliant with
		 * 					the Worpdress Translation Standards.
		 *					The language file is located in /languages folder.
		 */
		$path 	 = VIKRENTITEMS_LANG;

		$handler = VIKRENTITEMS_LIBRARIES . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;
		$domain  = 'vikrentitems';

		// init language
		$lang = JFactory::getLanguage();
		
		$lang->attachHandler($handler . 'system.php', $domain);
		
		if ($app->isAdmin())
		{
			$lang->attachHandler($handler . 'adminsys.php', $domain);
			$lang->attachHandler($handler . 'admin.php', $domain);
		}
		else
		{
			$lang->attachHandler($handler . 'site.php', $domain);
		}

		$lang->load($domain, $path);
	}

	/**
	 * Setup the pagination layout to use.
	 *
	 * @return 	void
	 */
	public static function setupPaginationLayout()
	{
		$layout = new JLayoutFile('html.system.pagination', null, array('component' => 'com_vikrentitems'));

		JLoader::import('adapter.pagination.pagination');
		JPagination::setLayout($layout);
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
		$capability = JAccess::adjustCapability('core.manage', 'com_vikrentitems');

		add_menu_page(
			JText::translate('COM_VIKRENTITEMS'), 			// page title
			JText::translate('COM_VIKRENTITEMS_MENU'), 		// menu title
			$capability,							// capability
			'vikrentitems', 						// slug
			array('VikRentItemsBody', 'getHtml'),	// callback
			'dashicons-cart',						// icon
			71										// ordering
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
			JHtml::fetch('script', VRI_SITE_URI . 'resources/jquery-ui.min.js');
			JHtml::fetch('stylesheet', VRI_SITE_URI . 'resources/jquery-ui.min.css');

			$layout = new JLayoutFile('html.plugins.calendar', null, array('component' => 'com_vikrentitems'));
			
			return $layout->render($data);
		});

		// helper method to get the plugin layout file handler
		JHtml::register('layoutfile', function($layoutId, $basePath = null, $options = array())
		{
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

				JHtml::fetch('script', VRI_ADMIN_URI . 'resources/js/system.js');
				JHtml::fetch('stylesheet', VRI_ADMIN_URI . 'resources/css/system.css');

				JHtml::fetch('script', VRI_ADMIN_URI . 'resources/js/bootstrap.min.js');
				JHtml::fetch('stylesheet', VRI_ADMIN_URI . 'resources/css/bootstrap.lite.css');
			}
		});

		// helper method to include the select2 JS file
		JHtml::register('select2', function()
		{
			/**
			 * Select2 is now loaded only when requested.
			 *
			 * @since 1.2.5
			 */
			JHtml::fetch('script', VRI_ADMIN_URI . 'resources/select2.min.js');
			JHtml::fetch('stylesheet', VRI_ADMIN_URI . 'resources/select2.min.css');
		});

		/**
		 * Register helper methods to sanitize attributes, html, JS and other elements.
		 */
		JHtml::register('esc_attr', function($str)
		{
			return esc_attr($str);
		});

		JHtml::register('esc_html', function($str)
		{
			return esc_html($str);
		});

		JHtml::register('esc_js', function($str)
		{
			return esc_js($str);
		});

		JHtml::register('esc_textarea', function($str)
		{
			return esc_textarea($str);
		});
	}

	/**
	 * This method is used to configure teh payments framework.
	 * Here should be registered all the default gateways supported
	 * by the plugin.
	 *
	 * @return 	void
	 *
	 * @since 	1.0.5
	 */
	public static function configurePaymentFramework()
	{
		// push the pre-installed gateways within the payment drivers list
		add_filter('get_supported_payments_vikrentitems', function($drivers)
		{
			$list = glob(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR . '*.php');

			return array_merge($drivers, $list);
		});

		// load payment handlers when dispatched
		add_action('load_payment_gateway_vikrentitems', function(&$drivers, $payment)
		{
			$classname = null;
			
			VikRentItemsLoader::import('admin.payments.' . $payment, VIKRENTITEMS_BASE);

			switch ($payment)
			{
				case 'paypal':
					$classname = 'VikRentItemsPayPalPayment';
					break;

				case 'offline_credit_card':
					$classname = 'VikRentItemsOfflineCreditCardPayment';
					break;

				case 'bank_transfer':
					$classname = 'VikRentItemsBankTransferPayment';
					break;
			}

			if ($classname)
			{
				$drivers[] = $classname;
			}
		}, 10, 2);

		// manipulate response to be compliant with notifypayment task
		add_action('payment_after_validate_transaction_vikrentitems', function(&$payment, &$status, &$response)
		{
			// manipulate the response to be compliant with the old payment system
			$response = array(
				'verified' => (int) $status->isVerified(),
				'tot_paid' => $status->amount,
				'log'	   => $status->log,
			);

			if ($status->skip_email)
			{
				$response['skip_email'] = $status->skip_email;
			}
		}, 10, 3);
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
		JModuleFactory::load(VIKRENTITEMS_BASE . DIRECTORY_SEPARATOR . 'modules');
	}
}
