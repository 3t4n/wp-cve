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

VAPLoader::import('libraries.employee.area.controller');

/**
 * Employee area edit coupon controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditcoupon extends VAPEmployeeAreaController
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.coupon.data', array());

		// check user permissions
		if (!$auth->manageCoupons())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditcoupon');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.coupon.data', array());

		$cid = $app->input->getUint('cid', array(0));

		// check user permissions
		if (!$auth->manageCoupons($cid[0]))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditcoupon&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 *
	 * @since   1.7
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Save employee coupon.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

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

		$id_coupon = $input->getUint('id', 0);

		// check user permissions
		if (!$auth->manageCoupons($id_coupon))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get args
		$args = array();
		$args['code']          = $input->getString('code');
		$args['type']          = $input->getUint('type', 1);
		$args['percentot']     = $input->getUint('percentot', 1);
		$args['value']         = $input->getFloat('value', 0);
		$args['mincost']       = $input->getFloat('mincost', 0);
		$args['max_quantity']  = $input->getUint('max_quantity', 1);
		$args['used_quantity'] = $input->getUint('used_quantity', 0);
		$args['maxperuser']    = $input->getUint('maxperuser', 0);
		$args['remove_gift']   = $input->getUint('remove_gift', 0);
		$args['pubmode']       = $input->getUint('pubmode', 0);
		$args['dstart']        = $input->getString('dstart', '');
		$args['dend']          = $input->getString('dend', '');
		$args['lastminute']    = $input->getUint('lastminute', 0);
		$args['notes']         = $input->getString('notes', '');
		$args['services']      = $input->getUint('services', array());
		$args['id']            = $id_coupon;

		/**
		 * Convert timestamp from local timezone to UTC.
		 *
		 * @since 1.7
		 */
		$args['dstart'] = VAPDateHelper::getSqlDateLocale($args['dstart']);
		$args['dend']   = VAPDateHelper::getSqlDateLocale($args['dend']);

		// get coupon model
		$model = $this->getModel();

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=empeditcoupon';

			if ($id_coupon)
			{
				$url .= '&cid[]=' . $id_coupon;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditcoupon.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Removes the coupon.
	 *
	 * @return 	void
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		if ($id = $app->input->getUint('id'))
		{
			$cid[] = $id;
		}

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

		try
		{
			// delete selected records
			if ($this->getModel()->delete($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPCOUPONREMOVED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list (reset list limit)
		$this->cancel(['listlimit' => 0]);

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @param 	array  $query  An array of query arguments.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel(array $query = array())
	{
		$url = 'index.php?option=com_vikappointments&view=empcoupons';

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}
}
