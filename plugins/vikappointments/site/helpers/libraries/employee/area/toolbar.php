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
 * Class used to manage the employees area toolbar.
 *
 * @since 1.7
 */
class VAPEmployeeAreaToolbar
{
	/**
	 * The singleton instance.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * The employee authentication handler.
	 *
	 * @var VAPEmployeeAuth
	 */
	protected $auth;

	/**
	 * An associative array holding the main menu.
	 *
	 * @var array
	 */
	protected $mainMenu = array();

	/**
	 * An associative array holding the secondary menu.
	 *
	 * @var array
	 */
	protected $sideMenu = array();

	/**
	 * Returns the employees area toolbar instance.
	 *
	 * @return self
	 */
	public static function getInstance()
	{
		if (static::$instance === null)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 * The visibility is protected to prevent the instantiation
	 * of this class from the outside.
	 */
	protected function __construct()
	{
		$this->auth = VAPEmployeeAuth::getInstance();

		// set up employees area menus
		$this->setupMainMenu();
		$this->setupSidebarMenu();
	}

	/**
	 * Cannot clone this object.
	 */
	private function __clone()
	{

	}

	/**
	 * Helper method used to set up the main menu.
	 *
	 * @return 	void
	 */
	protected function setupMainMenu()
	{
		/**
		 * Do not display the "profile" menu item in case
		 * the profile management is turned off from the
		 * employees configuration.
		 *
		 * @since 1.7.2
		 */
		if ($this->auth->manage())
		{
			// add profile item to main menu
			$this->mainMenu['profile'] = [
				'active' => true,
				'title'  => JText::translate('VAPEMPPROFILETITLE'),
				'icon'   => 'fas fa-user',
				'query'  => [
					'task' => 'empeditprofile.edit',
				],
				'selQuery' => [
					'view' => 'empeditprofile',
				]
			];
		}

		// add working days item to main menu
		$this->mainMenu['workdays'] = [
			'active' => true,
			'title'  => JText::translate('VAPEMPWORKDAYSTITLE'),
			'icon'   => 'fas fa-calendar',
			'query'  => [
				'view' => 'empwdays',
			],
		];

		// add services item to main menu
		$this->mainMenu['services'] = [
			'active' => true,
			'title'  => JText::translate('VAPEMPSERVICESTITLE'),
			'icon'   => 'fas fa-list',
			'query'  => [
				'view' => 'empserviceslist',
			],
		];

		if ($this->auth->managePayments())
		{
			// add payments item to main menu
			$this->mainMenu['payments'] = [
				'active' => true,
				'title'  => JText::translate('VAPEMPPAYMENTSTITLE'),
				'icon'   => 'fas fa-credit-card',
				'query'  => [
					'view' => 'emppaylist',
				],
			];
		}

		/**
		 * Trigger hook to allow the plugins to alter the items of the main menu
		 * displayed within the Employees Area.
		 *
		 * @param 	array  &$menu  The main menu.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onSetupEmployeesAreaMainMenu', array(&$this->mainMenu));
	}

	/**
	 * Helper method used to set up the sidebar menu.
	 *
	 * @return 	void
	 */
	protected function setupSidebarMenu()
	{
		// add account status item to sidebar menu
		$this->sideMenu['status'] = [
			'separator' => true,
			'title'     => JText::translate('VAPEMPACCOUNTSTATUSTITLE'),
			'icon'      => null,
			'query'     => [
				'view' => 'empaccountstat',
			],
		];
		
		/**
		 * Do not display the "coupons" menu item in case
		 * the coupons management is turned off from the
		 * employees configuration.
		 *
		 * @since 1.6.5
		 */
		if ($this->auth->manageCoupons())
		{
			// add coupons item to sidebar menu
			$this->sideMenu['coupons'] = [
				'separator' => false,
				'title'     => JText::translate('VAPEMPCOUPONSTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'empcoupons',
				],
			];
		}

		/**
		 * Do not display the "locations" menu item in case
		 * the locations management is turned off from the
		 * employees configuration.
		 *
		 * @since 1.7.2
		 */
		if ($this->auth->manageLocations())
		{
			// add locations item to sidebar menu
			$this->sideMenu['locations'] = [
				'separator' => $this->auth->manageWorkDays() ? false : true,
				'title'     => JText::translate('VAPEMPLOCATIONSTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'emplocations',
				],
			];
		}

		/**
		 * Do not display the "assignments" menu item in case
		 * the working days management is turned off from the
		 * employees configuration.
		 *
		 * @since 1.7.2
		 */
		if ($this->auth->manageWorkDays())
		{
			// add locations-working days item to sidebar menu
			$this->sideMenu['locwdays'] = [
				'separator' => true,
				'title'     => JText::translate('VAPEMPLOCWDTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'emplocwdays',
				],
			];
		}

		if (VikAppointments::isSubscriptions())
		{
			// add subscriptions orders item to sidebar menu
			$this->sideMenu['subscrorders'] = [
				'separator' => false,
				'title'     => JText::translate('VAPEMPSUBSCRPURCHTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'empsubscrorder',
				],
			];
			
			// add subscriptions item to sidebar menu
			$this->sideMenu['subscriptions'] = [
				'separator' => true,
				'title'     => JText::translate('VAPEMPSUBSCRTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'empsubscr',
				],
			];
		}

		if ($this->auth->manageCustomFields())
		{
			// add custom fields item to sidebar menu
			$this->sideMenu['fields'] = [
				'separator' => false,
				'title'     => JText::translate('VAPEMPCUSTOMFTITLE'),
				'icon'      => null,
				'query'     => [
					'view' => 'empcustfields',
				],
			];
		}
		
		// add settings item to sidebar menu
		$this->sideMenu['settings'] = [
			'separator' => true,
			'title'     => JText::translate('VAPEMPSETTINGSTITLE'),
			'icon'      => null,
			'query'     => [
				'view' => 'empsettings',
			],
		];

		// add log out item to sidebar menu
		$this->sideMenu['logout'] = [
			'separator' => false,
			'title'     => JText::translate('VAPLOGOUTTITLE'),
			'icon'      => null,
			'query'     => [
				'task' => 'emplogin.logout',
			],
		];

		/**
		 * Trigger hook to allow the plugins to alter the items of the sidebar menu
		 * displayed within the Employees Area.
		 *
		 * @param 	array  &$menu  The sidebar menu.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onSetupEmployeesAreaSidebarMenu', array(&$this->sideMenu));
	}

	/**
	 * Renders the employees area menus.
	 *
	 * @param 	array   $options  An associative array of options.
	 *
	 * @return 	string
	 */
	public function render(array $options = array())
	{
		$input = JFactory::getApplication()->input;

		foreach ($this->mainMenu as $key => &$mainItem)
		{
			if (empty($options['selected']))
			{
				// extract query to fetch
				$query = isset($mainItem['selQuery']) ? $mainItem['selQuery'] : @$mainItem['query'];

				// make sure all the query arguments are set in request
				$mainItem['selected'] = !array_diff_assoc((array) $query, $input->getArray());
			}
			else
			{
				// check whether the key of this item matches the specified one
				$mainItem['selected'] = $key === $options['selected'];
			}
		}

		foreach ($this->sideMenu as &$sideItem)
		{
			if (empty($options['selected']))
			{
				// extract query to fetch
				$query = isset($sideItem['selQuery']) ? $sideItem['selQuery'] : @$sideItem['query'];

				// make sure all the query arguments are set in request
				$sideItem['selected'] = !array_diff_assoc((array) $query, $input->getArray());
			}
			else
			{
				// check whether the key of this item matches the specified one
				$mainItem['selected'] = $key === $options['selected'];
			}
		}

		// prepare display data options
		$options['auth']     = $this->auth;
		$options['mainmenu'] = $this->mainMenu;
		$options['sidemenu'] = $this->sideMenu;

		/**
		 * The employees area toolbar is displayed from the layout below:
		 * /components/com_vikappointments/layouts/emparea/toolbar.php
		 * 
		 * If you need to change something from this layout, just create
		 * an override of this layout by following the instructions below:
		 * - open the back-end of your Joomla
		 * - visit the Extensions > Templates > Templates page
		 * - edit the active template
		 * - access the "Create Overrides" tab
		 * - select Layouts > com_vikappointments > emparea
		 * - start editing the toolbar.php file on your template to create your own layout
		 *
		 * @since 1.6
		 */
		return JLayoutHelper::render('emparea.toolbar', $options);
	}
}
