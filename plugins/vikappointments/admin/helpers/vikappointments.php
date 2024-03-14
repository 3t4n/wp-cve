<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Vikappointments back-end component helper.
 * 
 * IMPORTANT: this class is automatically loaded by Joomla to invoke the prepareUpdate method
 * while launching an update for this component. For this reason, since the autoloader hasn't
 * been required, we MUST NOT autoload external files outside this class.
 *
 * @since 1.0
 * @since 1.7.3  Renamed from AppointmentsHelper.
 */
abstract class VikAppointmentsHelper
{
	/**
	 * Displays the main menu of the component.
	 *
	 * @return 	void
	 *
	 * @see 	printFooter() it is needed to invoke also this method when the menu is displayed.
	 */
	public static function printMenu()
	{
		$app = JFactory::getApplication();
		$vik = VAPApplication::getInstance();

		// load font awesome framework
		JHtml::fetch('vaphtml.assets.fontawesome');

		$task = self::getActiveView();
		$auth = self::getAuthorisations();

		$base_href = 'index.php?option=com_vikappointments';

		// load menu factory
		VAPLoader::import('libraries.menu.factory');

		$board = MenuFactory::createMenu();

		///// DASHBOARD /////

		if ($auth['dashboard']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUDASHBOARD'), $base_href, $task == 'vikappointments');

			$board->push($parent->setCustom('tachometer-alt'));
		}

		///// MANAGEMENT /////

		if ($auth['management']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUTITLEHEADER1'));

			if ($auth['management']['actions']['groups'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUGROUPS'), $base_href . '&view=groups', $task == 'groups');
				$parent->addChild($item->setCustom('list'));
			}

			if ($auth['management']['actions']['employees'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUEMPLOYEES'), $base_href . '&view=employees', $task == 'employees');
				$parent->addChild($item->setCustom('user-tie'));
			}

			if ($auth['management']['actions']['services'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUSERVICES'), $base_href . '&view=services', $task == 'services');
				$parent->addChild($item->setCustom('flask'));
			}

			if ($auth['management']['actions']['options'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUOPTIONS'), $base_href . '&view=options', $task == 'options');
				$parent->addChild($item->setCustom('tags'));
			}

			if ($auth['management']['actions']['locations'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENULOCATIONS'), $base_href . '&view=locations', $task == 'locations');
				$parent->addChild($item->setCustom('map-marker-alt'));
			}

			if ($auth['management']['actions']['packages'] && VikAppointments::isPackagesEnabled())
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUPACKAGES'), $base_href . '&view=packorders', $task == 'packorders');
				$parent->addChild($item->setCustom('gift'));
			}

			$board->push($parent->setCustom('briefcase'));
		}

		///// APPOINTMENTS /////

		if ($auth['appointments']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUTITLEHEADER2'));

			if ($auth['appointments']['actions']['reservations'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENURESERVATIONS'), $base_href . '&view=reservations', $task == 'reservations');
				$parent->addChild($item->setCustom('shopping-basket'));
			}

			if ($auth['appointments']['actions']['waitinglist'] && VikAppointments::isWaitingList())
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGGLOBTITLE14'), $base_href . '&view=waitinglist', $task == 'waitinglist');
				$parent->addChild($item->setCustom('hourglass-start'));
			}

			if ($auth['appointments']['actions']['customers'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUCUSTOMERS'), $base_href . '&view=customers', $task == 'customers');
				$parent->addChild($item->setCustom('user'));
			}

			if ($auth['appointments']['actions']['coupons'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUCOUPONS'), $base_href . '&view=coupons', $task == 'coupons');
				$parent->addChild($item->setCustom('gift'));
			}

			if ($auth['appointments']['actions']['calendar'])
			{
				// recover calendar layout from user state
				$layout = $app->getUserState('vap.calendar.layout');

				if (!$layout)
				{
					// recover calendar layout from database
					$layout = VAPFactory::getConfig()->get('calendarlayout');
				}

				$item = MenuFactory::createItem(JText::translate('VAPMENUCALENDAR'), $base_href . '&view=' . $layout, $task == 'calendar');
				$parent->addChild($item->setCustom('calendar-alt'));
			}

			$board->push($parent->setCustom('calendar'));
		}

		///// PORTAL /////

		if ($auth['portal']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUTITLEHEADER4'));

			if ($auth['portal']['actions']['countries'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUCOUNTRIES'), $base_href . '&view=countries', $task == 'countries');
				$parent->addChild($item->setCustom('globe-americas'));
			}

			if ($auth['portal']['actions']['reviews'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUREVIEWS'), $base_href . '&view=reviews', $task == 'reviews');
				$parent->addChild($item->setCustom('star'));
			}

			if ($auth['portal']['actions']['subscriptions'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUSUBSCRIPTIONS'), $base_href . '&view=subscriptions', $task == 'subscriptions');
				$parent->addChild($item->setCustom('ticket-alt'));

				$item = MenuFactory::createItem(JText::translate('VAPMENUSUBSCRIPTIONORDERS'), $base_href . '&view=subscrorders', $task == 'subscrorders');
				$parent->addChild($item->setCustom('shopping-basket'));
			}

			$board->push($parent->setCustom('certificate'));
		}

		///// ANALYTICS /////

		if ($auth['analytics']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUTITLEANALYTICS'));

			if ($auth['analytics']['actions']['analytics.finance'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUFINANCE'), $base_href . '&view=analytics&location=finance', $task == 'analytics.finance');
				$parent->addChild($item->setCustom('wallet'));
			}

			if ($auth['analytics']['actions']['analytics.appointments'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUTITLEHEADER2'), $base_href . '&view=analytics&location=appointments', $task == 'analytics.appointments');
				$parent->addChild($item->setCustom('calendar-check'));
			}

			if ($auth['analytics']['actions']['analytics.services'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUSERVICES'), $base_href . '&view=analytics&location=services', $task == 'analytics.services');
				$parent->addChild($item->setCustom('flask'));
			}

			if ($auth['analytics']['actions']['analytics.employees'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUEMPLOYEES'), $base_href . '&view=analytics&location=employees', $task == 'analytics.employees');
				$parent->addChild($item->setCustom('user-tie'));
			}

			if ($auth['analytics']['actions']['analytics.customers'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUCUSTOMERS'), $base_href . '&view=analytics&location=customers', $task == 'analytics.customers');
				$parent->addChild($item->setCustom('users'));
			}

			if ($auth['analytics']['actions']['analytics.packages'] && VikAppointments::isPackagesEnabled())
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUPACKAGES'), $base_href . '&view=analytics&location=packages', $task == 'analytics.packages');
				$parent->addChild($item->setCustom('gift'));
			}

			// check whether the subscriptions are supported, otherwise avoid to display this section
			VAPLoader::import('libraries.models.subscriptions');

			if ($auth['analytics']['actions']['analytics.subscriptions'] && (VAPSubscriptions::has(0) || VAPSubscriptions::has(1)))
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUSUBSCRIPTIONS'), $base_href . '&view=analytics&location=subscriptions', $task == 'analytics.subscriptions');
				$parent->addChild($item->setCustom('ticket-alt'));
			}

			$board->push($parent->setCustom('search-dollar'));
		}

		///// GLOBAL /////

		if ($auth['global']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUTITLEHEADER3'));

			if ($auth['global']['actions']['custfields'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUCUSTOMF'), $base_href . '&view=customf', $task == 'customf');
				$parent->addChild($item->setCustom('font'));
			}

			if ($auth['global']['actions']['payments'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUPAYMENTS'), $base_href . '&view=payments', $task == 'payments');
				$parent->addChild($item->setCustom('credit-card'));
			}

			if ($auth['global']['actions']['statuscodes'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUSTATUSCODES'), $base_href . '&view=statuscodes', $task == 'statuscodes');
				$parent->addChild($item->setCustom('tags'));
			}

			if ($auth['global']['actions']['taxes'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUTAXES'), $base_href . '&view=taxes', $task == 'taxes');
				$parent->addChild($item->setCustom('calculator'));
			}

			if ($auth['global']['actions']['archive'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUARCHIVE'), $base_href . '&view=invoices', $task == 'invoices');
				$parent->addChild($item->setCustom('file-pdf'));
			}

			if ($auth['global']['actions']['media'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUMEDIA'), $base_href . '&view=media', $task == 'media');
				$parent->addChild($item->setCustom('camera-retro'));
			}

			$board->push($parent->setCustom('layer-group'));
		}

		///// CONFIGURATION /////

		if ($auth['configuration']['numactives'] > 0)
		{
			$parent = MenuFactory::createSeparator(JText::translate('VAPMENUCONFIG'));

			if ($auth['configuration']['actions']['config'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPMENUTITLEHEADER3'), $base_href . '&view=editconfig', $task == 'editconfig');
				$parent->addChild($item->setCustom('tools'));
			}

			if ($auth['configuration']['actions']['config'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGTABNAME2'), $base_href . '&view=editconfigemp', $task == 'editconfigemp');
				$parent->addChild($item->setCustom('users'));
			}

			if ($auth['configuration']['actions']['config'] || $auth['configuration']['actions']['config.closures'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGTABNAME3'), $base_href . '&view=editconfigcldays', $task == 'editconfigcldays');
				$parent->addChild($item->setCustom('calendar-times'));
			}

			if ($auth['configuration']['actions']['config'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGTABNAME4'), $base_href . '&view=editconfigsmsapi', $task == 'editconfigsmsapi');
				$parent->addChild($item->setCustom('sms'));
			}

			if ($auth['configuration']['actions']['config'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGTABNAME5'), $base_href . '&view=editconfigcron', $task == 'editconfigcron');
				$parent->addChild($item->setCustom('stopwatch'));
			}

			if ($auth['configuration']['actions']['config'])
			{
				$item = MenuFactory::createItem(JText::translate('VAPCONFIGTABNAME6'), $base_href . '&view=editconfigapp', $task == 'editconfigapp');
				$parent->addChild($item->setCustom('plug'));
			}

			$board->push($parent->setCustom('cog'));
		}

		// CUSTOM
		$line_separator = MenuFactory::createCustomItem('line');

		// split
		$board->push($line_separator);
		$board->push(MenuFactory::createCustomItem('split'));

		// check version
		if ($auth['configuration']['numactives'] > 0)
		{
			/**
			 * Detect current platform and use the correct version button:
			 * - VikUpdater for Joomla
			 * - Go To PRO for WordPress
			 *
			 * @since 1.6.3
			 */
			if (VersionListener::getPlatform() == 'joomla')
			{
				if ($task == 'vikappointments' || $task == 'editconfig')
				{
					$board->push($line_separator);
					$board->push(MenuFactory::createCustomItem('version', self::getCheckVersionParams()));
				}
			}
			else if (VersionListener::getPlatform() == 'wordpress')
			{
				// always display license button
				$board->push(MenuFactory::createCustomItem('license'));
			}
		}
		
		///// BUILD MENU /////

		/**
		 * Trigger event to allow the plugins to manipulate the back-end menu of VikAppointments.
		 *
		 * @param 	MenuShape  &$menu 	The menu to build.
		 *
		 * @return 	void
		 *
		 * @since 	1.6.3
		 */
		VAPFactory::getEventDispatcher()->trigger('onBeforeBuildVikAppointmentsMenu', array(&$board));

		echo $board->build();

		/**
		 * Open body by using the specific menu handler.
		 *
		 * @since 1.6.3
		 */
		echo $board->openBody();
	}

	/**
	 * Displays the footer of the component.
	 *
	 * @return 	void
	 *
	 * @see 	printMenu() it is needed to invoke also this method when the footer is displayed.
	 */
	public static function printFooter()
	{
		/**
		 * Close body by using the specific menu handler.
		 *
		 * @since 1.6.3
		 */
		echo MenuFactory::createMenu()->closeBody();
		
		if (VAPFactory::getConfig()->getBool('showfooter'))
		{
			/**
			 * Find manufacturer name according to the platform in use.
			 * Display a link in the format [SHORT] - [LONG].
			 *
			 * @since 1.6.3
			 */
			$manufacturer = VAPApplication::getInstance()
				->getManufacturer(array('link' => true, 'short' => true, 'long' => true));

			?>
			<p id="vapfooter">
				<?php echo JText::sprintf('VAPFOOTER', VIKAPPOINTMENTS_SOFTWARE_VERSION) . ' ' . $manufacturer; ?>
			</p>
			<?php
		}
	}

	/**
	 * In case of missing view, fetches the first 
	 * available one.
	 *
	 * @return 	string  The name of the default view.
	 *
	 * @since 	1.7
	 */
	public static function getDefaultView()
	{
		// scan ACL table to find the very first allowed page
		$acl = static::getAuthorisations();

		// iterate sections
		foreach ($acl as $section)
		{
			// iterates actions
			foreach ($section['actions'] as $action => $status)
			{
				// make sure the view is accessible
				if ($status)
				{
					// look for a specific view name
					return isset($section['views'][$action]) ? $section['views'][$action] : $action;
				}
			}
		}

		// the user seems to be unable to access any views
		return null;
	}

	/**
	 * Returns the parent to which the task belongs.
	 * For example, if we are visiting the states list,
	 * the parent will be "countries", as the states don't
	 * have a specific menu item.
	 *
	 * @param 	string 	$task 	The task to check.
	 * 							If empty, it will be taken from the request.
	 *
	 * @return 	void
	 */
	public static function getParentTask($task = null)
	{
		$input = JFactory::getApplication()->input;

		if (empty($task))
		{
			$task = 'vikappointments';
		}

		switch ($task)
		{
			case 'serworkdays':
			case 'rates':
			case 'restrictions':
				$task = 'services';
				break;
			
			case 'emprates':
			case 'emplocations':
				$task = 'employees';
				break;

			case 'optiongroups':
				$task = 'options';
				break;
			
			case 'packages':
			case 'packgroups':
				$task = 'packorders';
				break;

			case 'usernotes':
				$task = 'customers';
				break;
			
			case 'states':
			case 'cities':
				$task = 'countries';
				break;

			case 'coupongroups':
				$task = 'coupons';
				break;

			case 'caldays':
				$task = 'calendar';
				break;

			case 'payments':
				$task = $input->getBool('id_employee') ? 'employees' : 'payments';
				break;

			case 'analytics':
				// append macro-group to analytics task
				$task .= '.' . $input->get('location', 'unknown');
				break;

			case 'import':
			case 'export':
				// recursive search to make sure we select the correct parent
				$task = $input->getString('import_type', '');
				$task = self::getParentTask($task);
				break;

			case 'tags':
				// recursive search to make sure we select the correct parent
				$task = $input->getString('group', 'editconfig');
				$task = self::getParentTask($task);
				break;
		}

		return $task;
	}

	/**
	 * Returns the current active view.
	 * For example, if we are visiting the rooms closures,
	 * the active view will be "rooms", as the closures don't
	 * have a specific menu item.
	 *
	 * @return 	string  The current active view.
	 *
	 * @since 	1.7
	 */
	public static function getActiveView()
	{
		$input = JFactory::getApplication()->input;

		// get view/task from request
		$view = $input->get('view', $input->get('task'));

		if (empty($view))
		{
			// get default view
			$view = static::getDefaultView();
		}

		return self::getParentTask($view);
	}

	/**
	 * Returns the arguments used to display a link to check the version.
	 *
	 * @return 	array
	 */
	protected static function getCheckVersionParams()
	{
		$data = array(
			'hn'  => getenv('HTTP_HOST'),
			'sn'  => getenv('SERVER_NAME'),
			'app' => 'com_vikappointments',
			'ver' => VIKAPPOINTMENTS_SOFTWARE_VERSION,
		);

		return array(
			'url' 	=> 'https://extensionsforjoomla.com/vikcheck/vikupdater.php?' . http_build_query($data),
			'label' => 'Check Updates',
		);
	}
	
	/**
	 * Loads the base CSS and JS resources.
	 *
	 * @return 	void
	 */
	public static function load_css_js()
	{
		$vik = VAPApplication::getInstance();
		
		/**
		 * Load only jQuery framework provided by the CMS.
		 *
		 * @since 1.6.2
		 */
		$vik->loadFramework('jquery.framework');

		/**
		 * Do not load jQuery UI on Joomla 4.
		 * 
		 * Restore jQuery UI loading on Joomla 4.1 and higher because the
		 * CMS definitively removed its support.
		 *
		 * @since 1.7
		 */
		if (VersionListener::isJoomla40() === false)
		{
			$vik->addScript(VAPASSETS_URI . 'js/jquery-ui.min.js');
			$vik->addStyleSheet(VAPASSETS_URI . 'css/jquery-ui.min.css');
		}
		
		$vik->addScript(VAPASSETS_URI . 'js/jquery-ui.sortable.min.js');
		
		$vik->addScript(VAPASSETS_ADMIN_URI . 'js/vikappointments.js');
		
		$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/vikappointments.css');

		// always include CSS for confirm dialog in back-end
		$vik->addStyleSheet(VAPASSETS_URI . 'css/confirmdialog.css');

		if (VersionListener::isJoomla3x())
		{
			$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/adapter/J30.css');
		}
		else if (VersionListener::isJoomla4x())
		{
			$vik->addScript(VAPASSETS_ADMIN_URI . 'js/adapter/J40.js');
			$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/adapter/J40.css');

			// register adapter scripts on DOM loaded
			$app = JFactory::getApplication();
			$app->getDocument()->addScriptDeclaration('jQuery(function($) { $(\'body\').addClass(\'com_vikappointments\'); __vikappointments_j40_adapter(); });');

			if (VersionListener::isJoomla50())
			{
				$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/adapter/J50.css');
			}
		}

		/**
		 * Loads utils.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.assets.utils');

		/**
		 * Always instantiate the currency object.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.assets.currency');

		/**
		 * Auto set CSRF token to ajaxSetup so all jQuery ajax call will contain CSRF token.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.sitescripts.ajaxcsrf');
	}
	
	/**
	 * Returns a list containing all the media files.
	 *
	 * @param 	mixed  $order   The type of ordering to use.
	 * @param 	mixed  $thumbs  True to obtain the thumbnails.
	 *
	 * @return 	array
	 *
	 * @uses 	getMediaFromPath()
	 */
	public static function getAllMedia($order = false, $thumbs = false)
	{
		if ($thumbs)
		{
			$path = VAPMEDIA_SMALL;
		}
		else
		{
			$path = VAPMEDIA;
		}

		/**
		 * Ordering boolean flag is deprecated.
		 * Use the correct type instead.
		 * 
		 * @deprecated 1.8
		 */
		if ($order === true)
		{
			$order = 'creation';
		}

		return self::getMediaFromPath($path, $order);
	}

	/**
	 * Returns a list of images stored in the following folder.
	 *
	 * @param 	string  $path   The path from which the images should be loaded.
	 * @param 	mixed   $order  The type of ordering to use:
	 *                          - "name"      natural ordering;
	 *                          - "date"      creation date ordering;
	 *                          - "size"      image size ordering;
	 *                          - "filesize"  file size ordering;
	 *                          It is possible to pass an array with the first 
	 *                          argument to indicate the ordering type and the 
	 *                          second one for the ordering direction.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	public static function getMediaFromPath($path, $order = null)
	{
		// since certain server configurations may not support GLOB_BRACE mask,
		// we need to filter the list manually
		$arr = glob($path . DIRECTORY_SEPARATOR . '*');

		// use media model to check what are the supported files
		$model = JModelVAP::getInstance('media');

		$arr = array_filter($arr, function($path) use ($model)
		{
			return $model->isFileAllowed($path);
		});

		if ($order)
		{
			// check whether the user requested an ordering direction
			if (is_array($order))
			{
				list($order, $direction) = $order;
			}
			else
			{
				// fallback to default one
				$direction = 'asc';
			}

			// fetch the type of ordering
			usort($arr, function($a, $b) use ($order, $direction)
			{
				switch ($order)
				{
					case 'date':
						// sort by creation date
						$factor = filemtime($a) - filemtime($b);
						break;

					case 'filesize':
						// sort by file size
						$factor = filesize($a) - filesize($b);
						break;

					case 'size':
						// sort by image size
						$a_size = getimagesize($a);
						$b_size = getimagesize($b);

						$factor = $a_size[0] * $a_size[1] - $b_size[0] * $b_size[1];
						break;

					default:
						// fallback to file name comparison
						$factor = 0;
				}

				// in case of same creation date, sort files by name
				if ($factor == 0)
				{
					$factor = strcmp($a, $b);

					if ($order != 'name')
					{
						// always force ascending direction when
						// using this ordering as fallback
						$direction = 'asc';
					}
				}

				// in case of descending direction, reverse the ordering factor
				if (preg_match("/desc/i", $direction))
				{
					$factor *= -1;
				}

				return $factor;
			});
		}

		return $arr;
	}
	
	/**
	 * Returns the list of the invoices related to the specified group.
	 *
	 * @param 	string 	 $group  The invoices group.	 
	 * @param 	boolean  $sort 	 True to sort the invoices by name.
	 *
	 * @return 	array 	 The invoices list.
	 */
	public static function getAllInvoices($group = '', $sort = false)
	{
		$parts = array(
			VAPINVOICE,
		);

		if ($group)
		{
			$parts[] = $group;
		}

		$parts[] = '*.pdf';

		$arr = glob(implode(DIRECTORY_SEPARATOR, $parts));
		
		if ($sort)
		{
			sort($arr);
		}

		return $arr;
	}
	
	/**
	 * Returns an associative array containing the details of
	 * the specified file.
	 *
	 * @param 	string 	$file  The file path.
	 * @param 	array 	$attr  An array of options.
	 *
	 * @return 	mixed 	An array in case the file exists, null otherwise.
	 *
	 * @since 	1.2
	 */
	public static function getFileProperties($file, $attr = array())
	{
		if (!is_file($file))
		{
			return null;
		}

		// fill the options with the attributes needed in
		// case they were not specified
		$attr = self::getDefaultFileAttributes($attr);
		
		$prop = array();
		$prop['file'] 	     = $file;
		$prop['path'] 	     = dirname($file);
		$prop['name'] 	     = basename($file);
		$prop['file_ext']    = substr($file, strrpos($file, '.'));
		$prop['size'] 	     = JHtml::fetch('number.bytes', filesize($file));
		$prop['timestamp']   = filemtime($file);
		$prop['creation']    = JHtml::fetch('date', $prop['timestamp'], $attr['dateformat'], date_default_timezone_get());
		$prop['name_no_ext'] = substr($prop['name'], 0, strrpos($prop['name'], '.'));

		// fetch URI
		if ($prop['path'] == VAPMEDIA)
		{
			// use media URI
			$prop['uri'] = VAPMEDIA_URI;
		}
		else if ($prop['path'] == VAPMEDIA_SMALL)
		{
			// use media@small URI
			$prop['uri'] = VAPMEDIA_SMALL_URI;
		}
		else if (strpos($prop['path'], VAPCUSTOMERS_DOCUMENTS) !== false)
		{
			// get rid of the path to preserve any additional folder
			$folder = str_replace(VAPCUSTOMERS_DOCUMENTS, '', $prop['path']);
			$folder = trim($folder, DIRECTORY_SEPARATOR);
			$folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);

			/**
			 * Use customers documents folder.
			 *
			 * @since 1.7
			 */
			$prop['uri'] = VAPCUSTOMERS_DOCUMENTS_URI . $folder . '/';
		}
		else if (strpos($prop['path'], VAPCUSTOMERS_AVATAR) !== false)
		{
			/**
			 * Use customers avatar folder.
			 *
			 * @since 1.7
			 */
			$prop['uri'] = VAPCUSTOMERS_AVATAR_URI;
		}
		else if (strpos($prop['path'], VAPBASE) !== false)
		{
			/**
			 * Fetch URI based on given (internal) site path.
			 *
			 * @since 1.7
			 */
			$folder = str_replace(VAPBASE, '', $prop['path']);
			$folder = trim($folder, DIRECTORY_SEPARATOR);
			$folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);

			$prop['uri'] = VAPBASE_URI . $folder . '/';
		}
		else if (strpos($prop['path'], VAPADMIN) !== false)
		{
			/**
			 * Fetch URI based on given (internal) admin path.
			 *
			 * @since 1.7
			 */
			$folder = str_replace(VAPADMIN, '', $prop['path']);
			$folder = trim($folder, DIRECTORY_SEPARATOR);
			$folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder);

			$prop['uri'] = VAPADMIN_URI . $folder . '/';
		}
		else
		{
			throw new Exception('Unable to read files out of VikAppointments', 500);
		}

		// complete file URI
		$prop['uri'] .= $prop['name'];

		if (preg_match('/\.(jpe?g|a?png|bmp|gif|svg)$/i', $prop['file_ext']))
		{
			$img_size = getimagesize($file);

			$prop['width']  = $img_size[0];
			$prop['height'] = $img_size[1];
		}

		return $prop;
	}

	/**
	 * Returns an array of options to be used while fetching the
	 * details of a file. The default values will be used only
	 * if the specified attributes array doesn't contain them.
	 *
	 * @param 	array 	$attr  An array of attributes.
	 *
	 * @return 	array 	The resulting array.
	 */
	public static function getDefaultFileAttributes($attr = array())
	{
		if (empty($attr['dateformat']))
		{
			$config = VAPFactory::getConfig();

			/**
			 * The date format is now localised.
			 *
			 * @since 1.7
			 */
			$attr['dateformat'] = JText::translate('DATE_FORMAT_LC3') . ' ' . preg_replace("/:i/", ':i:s', $config->get('timeformat'));
		}

		return $attr;
	}
	
	/**
	 * Creates a dropdown containing all the media files.
	 *
	 * @param 	mixed 	 $value 		The selected value.
	 * @param 	boolean  $first_null 	True to insert an empty value as first option.
	 * @param 	array 	 $prop 			An associative array of input properties.
	 *
	 * @return 	string 	 The HTML of the dropdown.
	 *
	 * @uses 	getAllMedia()
	 */
	public static function composeMediaSelect($value = null, $first_null = true, array $prop = array())
	{
		if (!isset($prop['name']))
		{
			$prop['name'] = 'media';
		}

		$prop_str = '';
		foreach ($prop as $k => $v)
		{
			$prop_str .= ' ' . $k . (!empty($v) ? '="' . htmlspecialchars($v) . '"' : '');
		}

		$options = array();
		
		if ($first_null)
		{
			$options[] = JHtml::fetch('select.option', '', '');
		}

		foreach (self::getAllMedia() as $img)
		{
			$img = basename($img);
			
			$options[] = JHtml::fetch('select.option', $img, $img);
		}

		$selector = isset($prop['id']) ? '#' . $prop['id'] : 'input[name="' . $prop['name'] . '"]';

		JFactory::getDocument()->addScriptDeclaration("jQuery(document).ready(function() {
			jQuery('$selector').select2({
				placeholder: '" . (!$first_null ? addslashes(JText::translate('VAPFIRSTIMAGENULL')) : '--') . "',
				allowClear: true,
				width: 300
			});
		});");
		
		return '<select' . $prop_str . '>' . JHtml::fetch('select.options', $options, 'value', 'text', $value) . '</select>';
	}
	
	/**
	 * Returns an associative array containing the authorisations used
	 * to check which views can be visited by the logged-in user.
	 *
	 * @return 	array
	 */
	public static function getAuthorisations()
	{
		static $rules = null;

		if ($rules)
		{
			// return cached array
			return $rules;
		}

		$rules = array(
			'dashboard' => array(
				'actions'    => array('dashboard' => 0),
				'views'      => array('dashboard' => 'vikappointments'),
				'numactives' => 0,
			),
			'management' => array( 
				'actions'    => array('employees' => 0, 'groups' => 0, 'services' => 0, 'options' => 0, 'locations' => 0, 'packages' => 0),
				'views'      => array(),
				'numactives' => 0,
			),
			'appointments' => array( 
				'actions'    => array('reservations' => 0, 'waitinglist' => 0, 'customers' => 0, 'coupons' => 0, 'calendar' => 0),
				'views'      => array(),
				'numactives' => 0,
			),
			'portal' => array( 
				'actions'    => array('countries' => 0, 'reviews' => 0, 'subscriptions' => 0),
				'views'      => array(),
				'numactives' => 0,
			), 
			'analytics' => array( 
				'actions' => array(
					'analytics.finance'       => 0,
					'analytics.appointments'  => 0,
					'analytics.services'      => 0,
					'analytics.employees'     => 0,
					'analytics.customers'     => 0,
					'analytics.packages'      => 0,
					'analytics.subscriptions' => 0,
				),
				'numactives' => 0,
			), 
			'global' => array( 
				'actions'    => array('custfields' => 0, 'payments' => 0, 'statuscodes' => 0, 'taxes' => 0, 'archive' => 0, 'media' => 0),
				'views'      => array(
					'custfields' => 'customf',
					'archive'    => 'invoices',
				),
				'numactives' => 0,
			),
			'configuration' => array(
				'actions' 		=> array('config' => 0, 'config.closures' => 0),
				'views'         => array('config' => 'editconfig', 'config.closures' => 'editconfigcldays'),
				'numactives'	=> 0,
			),
		);
		
		$user = JFactory::getUser();
		
		foreach ($rules as $group => $rule)
		{
			foreach ($rule['actions'] as $action => $val)
			{
				$rules[$group]['actions'][$action] = $user->authorise("core.access.$action", "com_vikappointments");
				
				if ($rules[$group]['actions'][$action])
				{
					$rules[$group]['numactives']++;
				}
			}
		}
		
		return $rules;
	}

	/**
	 * Register a new Joomla user with the details
	 * specified in the given $args array.
	 *
	 * All the restrictions specified in com_users
	 * component are always bypassed.
	 *
	 * @param 	array 	$args 	The user details.
	 *
	 * @return 	mixed 	The ID of the user on success, otherwise false.
	 *
	 * @throws  RuntimeException
	 */
	public static function createNewJoomlaUser($args)
	{
		jimport('joomla.application.component.helper');
		$params = JComponentHelper::getParams('com_users');

		$vik = VAPApplication::getInstance();

		$user = new JUser;
		$data = array();

		if (empty($args['usertype']))
		{
			$groups = array($params->get('new_usertype', 2));
		}
		else
		{
			if (is_array($args['usertype']))
			{
				$groups = $args['usertype'];
			}
			else
			{
				$groups = array((string) $args['usertype']);
			}
		}

		if (empty($args['user_username']))
		{
			// empty username, use the specified name
			$args['user_username'] = $args['user_name'];
		}

		// get the default new user group, Registered if not specified
		$data['groups'] 	= $groups;
		$data['name'] 		= $args['user_name'];
		$data['username'] 	= $args['user_username'];
		$data['email'] 		= $vik->emailToPunycode($args['user_mail']);
		$data['password'] 	= $args['user_pwd1'];
		$data['password2']	= $args['user_pwd2'];
		$data['sendEmail'] 	= 0;
		
		/**
		 * Instead of returning 'false', this method 
		 * throws exceptions in case of errors.
		 *
		 * @since 1.7
		 */
		
		// bind user data
		if (!$user->bind($data))
		{
			// get error from user table
			$error = $user->getError(null, true);

			// throw exception
			throw new RuntimeException($error ? $error : JText::translate('VAP_USER_SAVE_BIND_ERR'));
		}

		if (!$user->save())
		{
			// get error from user table
			$error = $user->getError(null, true);

			// throw exception
			throw new RuntimeException($error ? $error : JText::translate('VAP_USER_SAVE_CHECK_ERR'));
		}

		return $user->id;
	}

	/**
	 * Removes the credit card details for the order that have been CONFIRMED
	 * and don't need them anymore.
	 *
	 * @param 	boolean  $force  Flag used to skip the waiting check.
	 * @param 	integer  $wait 	 The number of minutes to wait between each check.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	public static function removeExpiredCreditCards($force = false, $wait = 30)
	{
		$dbo 	 = JFactory::getDbo();
		$session = JFactory::getSession();

		$now = time();

		// if doesn't exist, get a time in the past
		$check = (int) $session->get('cc-flush-check', $now - 3600, 'vikappointments');

		if ($force || $check < $now)
		{
			$threshold = JFactory::getDate('-1 day');

			// the reservations must have a checkin date previous than yesterday
			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_reservation'))
				->set($dbo->qn('cc_data') . ' = ' . $dbo->q(''))
				->where($dbo->qn('checkin_ts') . ' < ' . $dbo->q($threshold->toSql()));

			$dbo->setQuery($q);
			$dbo->execute();

			$threshold  = JFactory::getDate('-1 week');
			$threshold2 = JFactory::getDate('-1 month');

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1));

			if ($approved)
			{
				// The packages must be confirmed and the creation date 
				// should be at least one week old. Otherwise remove them
				// after one month since the creation date.
				$q = $dbo->getQuery(true)
					->update($dbo->qn('#__vikappointments_package_order'))
					->set($dbo->qn('cc_data') . ' = ' . $dbo->q(''))
					->where(array(
						$dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')',
						$dbo->qn('createdon') . ' < ' . $dbo->q($threshold->toSql()),
					))
					->orWhere($dbo->qn('createdon') . ' < ' . $dbo->q($threshold2->toSql()));

				$dbo->setQuery($q);
				$dbo->execute();
			}

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1));

			if ($approved)
			{
				// The subscriptions must be confirmed and the creation date 
				// should be at least one week old. Otherwise remove them
				// after one month since the creation date.
				$q = $dbo->getQuery(true)
					->update($dbo->qn('#__vikappointments_subscr_order'))
					->set($dbo->qn('cc_data') . ' = ' . $dbo->q(''))
					->where(array(
						$dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')',
						$dbo->qn('createdon') . ' < ' . $dbo->q($threshold->toSql()),
					))
					->orWhere($dbo->qn('createdon') . ' < ' . $dbo->q($threshold2->toSql()));

				$dbo->setQuery($q);
				$dbo->execute();
			}

			// update time for next check
			$session->set('cc-flush-check', time() + $wait * 60, 'vikappointments');
		}
	}

	/**
	 * Updates the extra fields of VikAppointments to let them
	 * be sent to our servers during Joomla! updates.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	public static function registerUpdaterFields()
	{
		// make sure the Joomla version is 3.2.0 or higher
		// otherwise the extra_fields wouldn't be available
		$jv = new JVersion();

		if (version_compare($jv->getShortVersion(), '3.2.0', '<'))
		{
			// stop to avoid fatal errors
			return;
		}

		/**
		 * Do not register extra fields in case of Joomla 3.7+, because they are
		 * injected every time the update is going to be launched.
		 * 
		 * @since 1.7.3
		 */
		if (version_compare($jv->getShortVersion(), '3.7.0', '>='))
		{
			return;
		}

		$config = VAPFactory::getConfig();
		$extra_fields = $config->getInt('update_extra_fields', 0);	

		if ($extra_fields > time())
		{
			// not needed to rewrite extra fields
			return;
		}

		// get current domain
		$server = JFactory::getApplication()->input->server;
		$domain = base64_encode($server->getString('HTTP_HOST'));
		$ip 	= $server->getString('REMOTE_ADDR');

		// import url update handler
		VAPLoader::import('libraries.update.urihandler');

		$update = new UriUpdateHandler('com_vikappointments');

		$update->addExtraField('domain', $domain)
			->addExtraField('ip', $ip)
			->register();

		// validate schema version
		$update->checkSchema($config->get('version'));

		// rewrite extra fields next week
		$config->set('update_extra_fields', time() + 7 * 86400);
	}

	/**
	 * Method to add parameters to the update extra query.
	 *
	 * @param   Update  &$update  An update definition
	 * @param   JTable  &$table   The update instance from the database
	 *
	 * @return  void
	 *
	 * @since 	1.7.3
	 */
	public static function prepareUpdate(&$update, &$table)
	{
		// require autoloader
		require_once implode(DIRECTORY_SEPARATOR, array(JPATH_SITE, 'components', 'com_vikappointments', 'helpers', 'libraries', 'autoload.php'));

		// get current domain
		$server = JFactory::getApplication()->input->server;

		// build query array
		$query = [
			'domain' => base64_encode($server->getString('HTTP_HOST')),
			'ip' 	 => $server->getString('REMOTE_ADDR'),
		];

		// check if we have already validated the license
		$licenseKey = VAPFactory::getConfig()->get('licensekey');

		if ($licenseKey)
		{
			// inject license key within the request
			$query['key'] = $licenseKey;
		}

		// always refresh the extra query before an update
		$update->set('extra_query', http_build_query($query, '', '&amp;'));
	}

	/**
	 * Get the actions.
	 *
	 * @param 	integer  $id
	 *
	 * @return 	object
	 */
	public static function getActions($id = 0)
	{
		jimport('joomla.access.access');

		$user 	= JFactory::getUser();
		$result = new stdClass;

		if (empty($id))
		{
			$assetName = 'com_vikappointments';
		}
		else
		{
			$assetName = 'com_vikappointments.message.' . (int) $id;
		}

		$actions = JAccess::getActions('com_vikappointments', 'component');

		foreach ($actions as $action)
		{
			$result->{$action->name} = $user->authorise($action->name, $assetName);
		};

		return $result;
	}
}

if (!class_exists('OrderingManager'))
{
	/**
	 * Helper class used to handle lists ordering.
	 *
	 * @since 1.0
	 * @deprecated 1.8 Use VAPHtmlAdmin::sort() instead.
	 */
	class OrderingManager
	{
		/**
		 * The component name.
		 *
		 * @var string
		 */
		protected static $option = 'com_vikappointments';

		/**
		 * The value in query string that will be used to 
		 * recover the selected ordering column.
		 *
		 * @var string
		 */
		protected static $columnKey = 'vapordcolumn';

		/**
		 * The value in query string that will be used to 
		 * recover the selected ordering direction.
		 *
		 * @var string
		 */
		protected static $typeKey = 'vapordtype';
		
		/**
		 * Class constructor.
		 */
		protected function __construct()
		{
			// not accessible
		}

		/**
		 * Prepares the class with custom configuration.
		 *
		 * @param 	string 	$option
		 * @param 	string 	$column
		 * @param 	string 	$type
		 *
		 * @return 	void
		 */
		public static function getInstance($option = '', $column = '', $type = '')
		{
			if (!empty($option))
			{
				self::$option = $option;
			}

			if (!empty($column))
			{
				self::$columnKey = $column;
			}

			if (!empty($type))
			{
				self::$typeKey = $type;
			}
		}
		
		/**
		 * Returns the link that will be used to sort the column.
		 *
		 * @param 	string 	$task 			The task to reach after clicking the link.
		 * @param 	string 	$text 			The link text.
		 * @param 	string 	$col 			The column to sort.
		 * @param 	string 	$type 			The new direction value (1 ASC, 2 DESC).
		 * @param 	string 	$def_type 		The default direction if $type is empty.
		 * @param 	array 	$params 		An associative array with addition params to include in the URL-
		 * @param 	string 	$active_class 	The class used in case of active link.
		 *
		 * @return 	string 	The HTML of the link.
		 */
		public static function getLinkColumnOrder($task, $text, $col, $type = '', $def_type = '', $params = array(), $active_class = '')
		{
			if (empty($type))
			{
				$type 			= $def_type;
				$active_class 	= '';
			}

			if (!is_array($params))
			{
				if (empty($params))
				{
					$params = array();
				}
				else
				{
					$params = array($params);
				}
			}

			// inject URL vars in $params array
			$params['option'] 			= self::$option;
			$params['task']				= $task;
			$params[self::$columnKey] 	= $col;
			$params[self::$typeKey] 	= $type;

			$href = 'index.php?' . http_build_query($params);
			
			return '<a class="' . $active_class . '" href="' . $href . '">' . $text . '</a>';
		}
		
		/**
		 * Returns the ordering details for the specified values.
		 *
		 * @param 	string 	$task 		The task where we are.
		 * @param 	string 	$def_col 	The default column to sort.
		 * @param 	string 	$def_type 	The default ordering direction.
		 *
		 * @return 	array 	An associative array containing the ordering column and direction.
		 */
		public static function getColumnToOrder($task, $def_col = 'id', $def_type = 1)
		{
			$app = JFactory::getApplication();

			$col 	= $app->getUserStateFromRequest(self::$columnKey . "[$task]", self::$columnKey, $def_col, 'string');
			$type 	= $app->getUserStateFromRequest(self::$typeKey . "[$task]", self::$typeKey, $def_type, 'uint');
			
			return array('column' => $col, 'type' => $type);
		}
		
		/**
		 * Returns the ordering direction, based on the current one.
		 *
		 * @param 	string 	$task 		The task where we are.
		 * @param 	string 	$col 		The column we need to alter.
		 * @param 	string 	$curr_type 	The current direction.
		 *
		 * @return 	string  The new direction value.
		 */
		public static function getSwitchColumnType($task, $col, $curr_type)
		{
			$stored = JFactory::getApplication()->getUserStateFromRequest(self::$columnKey . "[$task]", self::$columnKey, '', 'string');
			
			$types = array(1, 2);

			if ($stored == $col)
			{
				$index = array_search($curr_type, $types);

				if ($index >= 0)
				{
					return $types[($index + 1) % 2];
				}
			} 
			
			return end($types);
		}
	}
}

/**
 * Vikappointments back-end component helper.
 *
 * @since 1.0
 * @deprecated 1.8  Use VikAppointmentsHelper instead.
 */
class_alias('VikAppointmentsHelper', 'AppointmentsHelper');
