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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments coupon controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerCoupon extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$data     = array();
		$id_group = $app->input->getInt('id_group', 0);

		if ($id_group > 0)
		{
			$data['id_group'] = $id_group;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.coupon.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.coupons', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managecoupon');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		// unset user state for being recovered again
		$app->setUserState('vap.coupon.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.coupons', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managecoupon&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the creation
	 * page of a new record.
	 *
	 * @return 	void
	 */
	public function savenew()
	{
		if ($this->save())
		{
			$this->setRedirect('index.php?option=com_vikappointments&task=coupon.add');
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @param 	boolean  $copy  True to save the record as a copy.
	 *
	 * @return 	boolean
	 */
	public function save($copy = false)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();
		
		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$args = array();
		$args['code']          = $input->getString('code', '');
		$args['type']          = $input->getUint('type', 1);
		$args['max_quantity']  = $input->getUint('max_quantity');
		$args['used_quantity'] = $input->getUint('used_quantity');
		$args['maxperuser']    = $input->getUint('maxperuser', 0);
		$args['applicable']    = $input->getString('applicable', '');
		$args['remove_gift']   = $input->getUint('remove_gift', 0);
		$args['percentot']     = $input->getUint('percentot', 1);
		$args['value']         = $input->getFloat('value', 0);
		$args['mincost']       = $input->getFloat('mincost', 0);
		$args['pubmode']       = $input->getUint('pubmode', 1);
		$args['dstart']        = $input->getString('dstart', '');
		$args['dend']          = $input->getString('dend', '');
		$args['lastminute']    = $input->getUint('lastminute', 0);
		$args['notes']         = $input->getString('notes', '');
		$args['services']      = $input->getUint('services', array());
		$args['employees']     = $input->getUint('employees', array());
		$args['id_group']      = $input->getUint('id_group', 0);
		$args['id']            = $input->getUint('id', 0);

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.coupons', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		/**
		 * Try to auto-create a new group before saving the coupon.
		 *
		 * @since 1.7
		 */
		if ($args['id_group'] == 0 && ($group_name = $input->getString('group_name')))
		{
			// make sure the user is authorised
			if ($user->authorise('core.create', 'com_vikappointments'))
			{
				$group = $this->getModel('coupongroup');

				// attempt to save group
				$id_group = $group->save(array('name' => $group_name));
				
				if ($id_group)
				{
					// overwrite the group ID
					$args['id_group'] = $id_group;
				}
			}
		}

		/**
		 * Convert timestamp from local timezone to UTC.
		 *
		 * @since 1.7
		 */
		$args['dstart'] = VAPDateHelper::getSqlDateLocale($args['dstart']);
		$args['dend']   = VAPDateHelper::getSqlDateLocale($args['dend']);

		// get coupon model
		$coupon = $this->getModel();

		// try to save arguments
		$id = $coupon->save($args);

		if (!$id)
		{
			// get string error
			$error = $coupon->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managecoupon';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=coupon.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function delete()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->get('cid', array(), 'uint');

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.coupons', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel()->delete($cid);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_vikappointments&view=coupons');
	}
}
