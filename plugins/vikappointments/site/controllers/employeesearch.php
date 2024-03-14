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
 * VikAppointments employee search view controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerEmployeesearch extends VAPControllerAdmin
{
	/**
	 * Task used to send a contact message to the specified employee.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  id_employee   The ID of the employee.
	 * @param 	string 	 sendername    The sender name.
	 * @param 	string 	 sendermail    The sender e-mail.
	 * @param 	string 	 mail_content  The contents to send via mail.
	 * @param 	string 	 return        An optional return URL.
	 *
	 * @return 	boolean
	 */
	public function quickcontact()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$dbo   = JFactory::getDbo();

		$itemid = $input->getInt('Itemid');

		$id_employee = $input->getUint('id_employee', 0);
		$name        = $input->getString('sendername');
		$email       = $input->getString('sendermail');
		$content     = $input->getString('mail_content');

		$return_url = $input->getBase64('return');

		if ($return_url)
		{
			// use given URL
			$return_url = base64_decode($return_url);
		}
		else
		{
			// create new URL
			$return_url = 'index.php?option=com_vikappointments&view=employeeslist&id_emp=' . $id_employee;	

			if ($itemid)
			{
				$return_url .= '&Itemid=' . $itemid;
			}
		}

		// set return URL as redirect
		$this->setRedirect(JRoute::rewrite($return_url, false));

		// validate session token to avoid direct requests
		if (!JSession::checkToken())
		{
			// invalid session token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}

		$vik = VAPApplication::getInstance();

		// in case of reCAPTCHA, validate it
		if ($vik->isGlobalCaptcha() && !$vik->reCaptcha('check'))
		{
			// invalid captcha
			$app->enqueueMessage(JText::translate('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL'), 'error');
			return false;
		}

		// get view model
		$model = $this->getModel();

		// try to send the e-mail to the employee
		if (!$model->askQuestion($id_employee, $name, $email, $content))
		{
			// extract error from model
			$error = $model->getError($index = null, $string = true);
			$app->enqueueMessage($error, 'error');
			return false;
		}
		
		// e-mail sent successfully
		$app->enqueueMessage(JText::translate('VAPQUICKCONTACTMAILSENT'));
		return true;
	}

	/**
	 * Task used to leave a review for the specified employee.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	string 	 title 		  The review title.
	 * @param 	string 	 comment 	  The review comment.
	 * @param 	integer  rating 	  The review rating (1-5).
	 * @param 	integer  id_employee  The ID of the employee to review.
	 *
	 * @return 	boolean
	 */
	public function leavereview()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
			
		$args = array();
		$args['title']       = $input->getString('title');
		$args['comment']     = $input->getString('comment');
		$args['rating']      = $input->getUint('rating', 0);
		$args['id_employee'] = $input->getUint('id_employee', 0);

		$itemid = $input->getUint('Itemid');
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=employeesearch&id_employee=' . $args['id_employee'] . ($itemid ? '&Itemid=' . $itemid : ''), false));
		
		// get review model
		$model = $this->getModel('review');

		if (!$model->leave($args))
		{
			// get last error fetched
			$error = $model->getError($index = null, $string = true);
			$app->enqueueMessage($error ? $error : JText::translate('VAPPOSTREVIEWINSERTERR'), 'error');
			return false;
		}

		if ($model->getData()->published)
		{
			// review published
			$app->enqueueMessage(JText::translate('VAPPOSTREVIEWCREATEDCONF'));
		}
		else
		{
			// review waiting for approval
			$app->enqueueMessage(JText::translate('VAPPOSTREVIEWCREATEDPEND'));
		}

		return true;
	}

	/**
	 * AJAX task used to load more reviews for the specified employee.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  id_employee  The employee ID.
	 * @param 	integer  start 	      The starting limit.
	 *
	 * @return 	void
	 */
	public function loadreviews()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$id_emp = $input->getUint('id_employee', 0);
		$lim0 	= $input->getUint('start', 0);
		
		$reviews = VikAppointments::loadReviews('employee', $id_emp, $lim0);
		
		$result = new stdClass;
		$result->size    = $reviews->size;
		$result->reviews = array();
		
		foreach ($reviews->rows as $review)
		{
			$data = array(
				'review'          => $review,
				'datetime_format' => JText::translate('DATE_FORMAT_LC2'),
			);

			/**
			 * The review block is displayed from the layout below:
			 * /components/com_vikappointments/layouts/review/default.php
			 * 
			 * If you need to change something from this layout, just create
			 * an override of this layout by following the instructions below:
			 * - open the back-end of your Joomla
			 * - visit the Extensions > Templates > Templates page
			 * - edit the active template
			 * - access the "Create Overrides" tab
			 * - select Layouts > com_vikappointments > review
			 * - start editing the default.php file on your template to create your own layout
			 *
			 * @since 1.6
			 */
			$result->reviews[] = JLayoutHelper::render('review.default', $data);
		}
		
		// send reviews to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX end-point used to fetch the availability timeline.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  $id_ser     The service ID.
	 * @param 	integer  $id_emp     The employee ID.
	 * @param 	string   $day        The check-in date.
	 * @param 	integer  $people     The number of participants.
	 * @param 	array    $locations  A list of selected locations.
	 *
	 * @return 	void
	 */
	public function timelineajax()
	{
		$input = JFactory::getApplication()->input;

		$args = array();
		$args['id_emp']    = $input->getUint('id_emp', 0);
		$args['id_ser']    = $input->getUint('id_ser', 0);
		$args['date'] 	   = $input->getString('day', '');
		$args['people']    = $input->getUint('people', 1);
		$args['locations'] = $input->getUint('locations', null);
		$args['admin']     = $input->getBool('admin', false);

		if ($args['admin'])
		{
			// requested administrator right, make sure the user is an employee
			$args['admin'] = VAPEmployeeAuth::getInstance()->isEmployee();

			if ($args['admin'])
			{
				// in case of employee, check if we are editing a reservation
				$args['id_res'] = $input->getUint('id_res');
			}
		}

		// get model
		$model = $this->getModel();
		// use model to create the timeline
		$timeline = $model->getTimeline($args);

		$result = new stdClass;

		if ($timeline)
		{
			// create timeline response
			$result->html     = $timeline->display();
			$result->timeline = $timeline->getTimeline();

			// recalculate rate by specifing the selected arguments
			$result->rate = VAPSpecialRates::getRate($args['id_ser'], $args['id_emp'], $args['date'], $args['people']);

			// fetch service details
			$service = $this->getModel('service')->getItem($args['id_ser']);

			if ($service->priceperpeople)
			{
				// multiply by the number of selected participants
				$result->rate *= $args['people'];
			}
		}
		else
		{
			// raise error message
			$result->error    = $model->getError($index = null, $string = true);
			$result->timeline = array();
		}

		// send timeline to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX end-point used to refresh the price of the given service.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  $id_ser     The service ID.
	 * @param 	integer  $id_emp     The employee ID.
	 * @param 	string   $day        The check-in date.
	 * @param 	integer  $people     The number of participants.
	 *
	 * @return 	void
	 * 
	 * @since   1.7.4
	 */
	public function refreshprice()
	{
		$input = JFactory::getApplication()->input;

		$args = [];
		$args['id_emp'] = $input->getUint('id_emp', 0);
		$args['id_ser'] = $input->getUint('id_ser', 0);
		$args['date'] 	= $input->getString('day', '');
		$args['people'] = $input->getUint('people', 1);

		$result = new stdClass;
		$result->rate = 0;

		// recalculate rate by specifing the selected arguments
		$result->rate = VAPSpecialRates::getRate($args['id_ser'], $args['id_emp'], $args['date'], $args['people']);
		
		// fetch service details
		$service = $this->getModel('service')->getItem($args['id_ser']);

		if ($service->priceperpeople)
		{
			// multiply by the number of selected participants
			$result->rate *= $args['people'];
		}

		$this->sendJSON($result);
	}
}
