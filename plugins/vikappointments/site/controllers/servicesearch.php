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
 * VikAppointments service search view controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerServicesearch extends VAPControllerAdmin
{
	/**
	 * Task used to send a contact message to the specified service.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  id_service    The ID of the service.
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

		$id_service = $input->getUint('id_service', 0);
		$name       = $input->getString('sendername');
		$email      = $input->getString('sendermail');
		$content    = $input->getString('mail_content');

		$return_url = $input->getBase64('return');

		if ($return_url)
		{
			// use given URL
			$return_url = base64_decode($return_url);
		}
		else
		{
			// create new URL
			$return_url = 'index.php?option=com_vikappointments&view=servicesearch&id_service=' . $id_service;	

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

		// try to send the e-mail to the administrators
		if (!$model->askQuestion($id_service, $name, $email, $content))
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
	 * Task used to leave a review for the specified service.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	string 	 title 		 The review title.
	 * @param 	string 	 comment 	 The review comment.
	 * @param 	integer  rating 	 The review rating (1-5).
	 * @param 	integer  id_service  The ID of the service to review.
	 *
	 * @return 	boolean
	 */
	public function leavereview()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
			
		$args = array();
		$args['title']      = $input->getString('title');
		$args['comment']    = $input->getString('comment');
		$args['rating']     = $input->getUint('rating', 0);
		$args['id_service'] = $input->getUint('id_service', 0);

		$itemid = $input->getUint('Itemid');
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch&id_service=' . $args['id_service'] . ($itemid ? '&Itemid=' . $itemid : ''), false));
		
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
	 * AJAX task used to load more reviews for the specified service.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  id_service  The service ID.
	 * @param 	integer  start 	     The starting limit.
	 *
	 * @return 	void
	 */
	public function loadreviews()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$id_ser = $input->getUint('id_service', 0);
		$lim0 	= $input->getUint('start', 0);
		
		$reviews = VikAppointments::loadReviews('service', $id_ser, $lim0);
		
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
}
