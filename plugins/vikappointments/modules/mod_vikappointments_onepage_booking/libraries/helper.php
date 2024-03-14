<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.helpers.module');

/**
 * Helper class used by the Onepage Booking module.
 *
 * @since 1.0
 */
class VikAppointmentsOnepageBookingHelper
{
	/**
	 * Use methods defined by modules trait for a better reusability.
	 *
	 * @see VAPModuleHelper
	 */
	use VAPModuleHelper;

	/**
	 * Returns the current cart instance.
	 *
	 * @return VAPCart
	 */
	public static function getCart()
	{
		// get cart model
		JModelLegacy::addIncludePath(VAPBASE . DIRECTORY_SEPARATOR . 'models');
		$model = JModelVAP::getInstance('cart');

		// access cart instance
		return $model->getCart();
	}

	/**
	 * Returns the login form.
	 * 
	 * @param 	string  $step  An optional step to immediately reach after logging in.
	 * 
	 * @return 	string  The login HTML.
	 */
	public static function getLoginForm($step = null, $id = null)
	{
		$uri = JUri::getInstance();

		if ($step)
		{
			// append step to the return URL
			$uri->setVar('opb_step', $step);
		}

		// render login block
		$html = JLayoutHelper::render('blocks.login', [
			'register' => JComponentHelper::getParams('com_users')->get('allowUserRegistration'),
			'return'   => (string) $uri,
			'remember' => false,
			'footer'   => true,
			'form'     => $id ? $id : null,
		], $basePath = null, [
			'component' => 'com_vikappointments',
		]);

		// wrap login/register form in a parent div
		return '<div class="login-block-wrapper">' . $html . '</div>';
	}

	/**
	 * Fetches the HTML block displaying the cart totals.
	 *
	 * @param 	VAPCart  $cart
	 *
	 * @return 	string
	 */
	public static function getCartTotalsHtml($cart = null)
	{
		if (!$cart)
		{
			// cart missing, create it
			$cart = self::getCart();
		}

		// render cart totals
		return JLayoutHelper::render('blocks.carttotals', [
			'cart' => $cart,
		], $basePath = null, [
			'component' => 'com_vikappointments',
		]);
	}

	/**
	 * Fetches the HTML block used to display the payment methods selection.
	 * 
	 * @param 	integer  $id_employee  The employee ID.
	 * 
	 * @return 	string
	 */
	public static function getPaymentMethodsHtml($id_employee)
	{
		// load payments and translate them
		$payments = VikAppointments::getAllEmployeePayments($id_employee);
		VikAppointments::translatePayments($payments);

		if (!$payments)
		{
			// nothing to display
			return;
		}

		// render cart totals
		$html = JLayoutHelper::render('blocks.paymentmethods', [
			'payments' => $payments,
			'showdesc' => false,
		], $basePath = null, [
			'component' => 'com_vikappointments',
		]);

		// wrap payment methods in a parent div
		return '<div class="opb-payments-list">' . $html . '</div>';
	}

	/**
	 * Returns the list of supported services, grouped by category.
	 * 
	 * @return 	array
	 */
	public static function getServices()
	{
		$dbo = JFactory::getDbo();

		$groups = $group_ids = $service_ids = [];

		$q = $dbo->getQuery(true)
			->select('s.*')
			->select($dbo->qn('g.name', 'group_name'))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'))
			->where($dbo->qn('s.published') . ' = 1')
			->order(array(
				$dbo->qn('g.ordering') . ' ASC',
				$dbo->qn('s.ordering') . ' ASC',
				$dbo->qn('s.name') . ' ASC',
			));

		// retrieve only the services that belong to the view
		// access level of the current user
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('s.level') . ' IN (' . implode(', ', $levels) . ')');
		}
			
		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if ($rows)
		{
			foreach ($rows as $r)
			{
				if ($r->id_group < 0)
				{
					$r->id_group = 0;
				}

				if (!isset($groups[$r->id_group]))
				{
					$group = new stdClass;
					$group->id       = $r->id_group;
					$group->name     = $r->group_name;
					$group->services = [];

					$groups[$r->id_group] = $group;

					if ($r->id_group > 0)
					{
						$group_ids[] = $r->id_group;
					}
				}

				// fetch service min and max range dates
				static::fetchServiceRangeDates($r);

				$groups[$r->id_group]->services[] = $r;

				$service_ids[] = $r->id;
			}
		}

		$translator = VAPFactory::getTranslator();

		$lang = JFactory::getLanguage()->getTag();

		// preload translations
		$serLang = $translator->load('service', array_unique($service_ids), $lang);
		$grpLang = $translator->load('group', array_unique($group_ids), $lang);

		foreach ($groups as $id_group => $group)
		{
			// translate record for the given language
			$tx = $grpLang->getTranslation($id_group, $lang);

			if ($tx)
			{
				$group->name = $tx->name;
			}

			foreach ($group->services as $i => $service)
			{
				// translate record for the given language
				$tx = $serLang->getTranslation($service->id, $lang);

				if ($tx)
				{
					$service->name = $tx->name;
				}
			}
		}

		// check if we have any services without group
		if (isset($groups[0]))
		{
			// detach uncategorized groups from the list
			$tmp = $groups[0];
			unset($groups[0]);

			if (count($groups))
			{
				// there are other configured groups, use a label also
				// for the uncategorized services
				$tmp->name = JText::translate('VAP_OPB_NO_GROUP_LEGEND');
			}

			// re-append services without group at the end of the list
			$groups[0] = $tmp;
		}
		
		return $groups;
	}

	/**
	 * Returns a list of employees available for the specified service.
	 * 
	 * @param 	integer  $id_service  The selected service ID.
	 * 
	 * @return 	array
	 */
	public static function getEmployees($id_service)
	{
		if ($id_service <= 0)
		{
			return [];
		}

		/**
		 * Include the folder containing all the VikAppointments models for the site client.
		 * 
		 * @since 1.0.4
		 */
		JModelLegacy::addIncludePath(VAPBASE . DIRECTORY_SEPARATOR . 'models');

		// take only the employees that should be listed
		$employees = JModelVAP::getInstance('service')->getEmployees($id_service, $strict = true);

		if ($employees)
		{
			// translate employees
			VikAppointments::translateEmployees($employees);
		}
		
		return $employees;
	}

	/**
	 * Builds a dropdown to allow the selection of the timezone.
	 * 
	 * @param 	int     $module_id  The module ID.
	 * 
	 * @return 	string
	 */
	public static function getTimezoneDropdown($module_id)
	{
		$zones = [];

		foreach (timezone_identifiers_list() as $zone)
		{
			$parts = explode('/', $zone);

			$continent  = isset($parts[0]) ? $parts[0] : '';
			$city 		= (isset($parts[1]) ? $parts[1] : $continent) . (isset($parts[2]) ? '/' . $parts[2] : '');
			$city 		= ucwords(str_replace('_', ' ', $city));

			if (!isset($zones[$continent]))
			{
				$zones[$continent] = [];
			}

			$zones[$continent][] = JHtml::fetch('select.option', $zone, $city);
		}

		$params = [
			'id'          => 'opb-timezone-list-' . $module_id,
			'group.items' => null,
			'list.select' => VikAppointments::getUserTimezone()->getName(),
		];

		return JHtml::fetch('select.groupedList', $zones, null, $params);
	}

	/**
	 * Helper method used to check whether the module should perform a refresh
	 * before moving to the step that follows the timeline. This is useful
	 * to properly fetch payments and custom fields that might vary according
	 * to the booked items.
	 * 
	 * @return 	bool  True in case of refresh needed, false otherwise.
	 */
	public static function isRefreshNeededAfterBook()
	{
		$dbo = JFactory::getDbo();

		// check whether there is at least a custom field that has been
		// assigned to an employee or whether there's a custom field
		// that should be repeated for each participant
		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_custfields'))
			->where($dbo->qn('group') . ' = 0')
			->andWhere([
				$dbo->qn('id_employee') . ' > 0',
				$dbo->qn('repeat') . ' = 1',
			], 'OR');

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			return true;
		}

		// check whether there is at least a custom field that has been
		// assigned to a specific service
		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_cf_service_assoc'));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			return true;
		}

		// check whether there is at least a payment gateway that has been
		// assigned to a specific employee
		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where([
				$dbo->qn('id_employee') . ' > 0',
				$dbo->qn('published') . ' = 1',
				$dbo->qn('appointments') . ' = 1',
			]);

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			return true;
		}

		// there are no records that may vary according to the booked appointments
		return false;
	}

	/**
	 * Checks whether the billing box should be displayed or not.
	 * 
	 * @param 	VAPCart  $cart    The cart instance.
	 * @param 	array    $fields  The custom fields array.
	 * @param 	mixed    $user    The user instance or null.
	 * 
	 * @return  bool     True to display, false to skip.
	 */
	public static function shouldDisplayBillingBox(VAPCart $cart, array $fields, $user, $zip)
	{
		if (VAPFactory::getConfig()->getUint('loginreq') == 2 && !VikAppointments::isUserLogged())
		{
			// login mandatory at billing stage
			return true;
		}

		// checks whether there's at least an editable custom field
		if (VAPCustomFieldsRenderer::hasEditableCustomFields($fields, $user ? $user->fields : []))
		{
			// there's at least an editable field
			return true;
		}

		// count the total number of attendees and, in case it is higher than 1, 
		// display the custom fields to collect the information of the other guests
		$attendees = VAPCartUtils::getAttendees($cart->getItemsList());

		if ($attendees > 1 && VAPCustomFieldsRenderer::hasRepeatableFields($fields))
		{
			// there's at least a field for the attendees
			return true;
		}

		// check whether it is needed to validate the zip code
		if ($zip)
		{
			// zip code validation required
			return true;
		}

		// billing box can be omitted
		return false;
	}

	/**
	 * Approximatively finds the first and last available dates for the given service.
	 * 
	 * @param 	object 	$service  The service to check.
	 * 
	 * @return 	void
	 */
	protected static function fetchServiceRangeDates($service)
	{
		$tz = VikAppointments::getUserTimezone();

		if ($service->mindate == -1)
		{
			// used global setting
			$service->mindate = VAPFactory::getConfig()->getUint('mindate');
		}

		if ($service->mindate > 0)
		{
			// create minimum date from now on
			$mindate = '+' . $service->mindate . ' days';
		}
		else
		{
			// use current date
			$mindate = 'now';
		}

		// format minimum date
		$service->first_date = JHtml::fetch('date', $mindate, 'Y-m-d', $tz->getName());

		if (!VAPDateHelper::isNull($service->start_publishing))
		{
			// register start publishing
			$start_publishing = JHtml::fetch('date', $service->start_publishing, 'Y-m-d', $tz->getName());

			if ($service->first_date < $start_publishing)
			{
				// use service start publishing
				$service->first_date = $start_publishing;
			}
		}

		if ($service->maxdate == -1)
		{
			// used global setting
			$service->maxdate = VAPFactory::getConfig()->getUint('maxdate');
		}

		$service->last_date = null;

		if ($service->maxdate > 0)
		{
			// format maximum date
			$service->last_date = JHtml::fetch('date', '+' . $service->maxdate . ' days', 'Y-m-d', $tz->getName());
		}
		
		if (!VAPDateHelper::isNull($service->end_publishing))
		{
			// register end publishing
			$end_publishing = JHtml::fetch('date', $service->end_publishing, 'Y-m-d', $tz->getName());

			if (!$service->last_date || $service->last_date > $end_publishing)
			{
				// use service end publishing
				$service->last_date = $end_publishing;
			}
		}
	}
}
