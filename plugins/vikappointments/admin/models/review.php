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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments review model.
 *
 * @since 1.7
 */
class VikAppointmentsModelReview extends JModelVAP
{
	/**
	 * Acts as a save method but applying further validations,
	 * since it assumes that the review is left by a customer.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function leave($data)
	{
		$config = VAPFactory::getConfig();

		if (!empty($data['id_employee']))
		{
			// validate permissions for employee reviews
			if (!VikAppointments::userCanLeaveEmployeeReview($data['id_employee'])) 
			{
				// user cannot leave a review for this employee
				$this->setError(JText::translate('VAPPOSTREVIEWAUTHERR'));
				return false;
			}
		}
		else if (!empty($data['id_service']))
		{
			// validate permissions for service reviews
			if (!VikAppointments::userCanLeaveServiceReview($data['id_service']))
			{
				// user cannot leave a review for this service
				$this->setError(JText::translate('VAPPOSTREVIEWAUTHERR'));
				return false;
			}
		}
		else
		{
			// missing subject
			$this->setError(JText::translate('VAPPOSTREVIEWFILLERR'));
			return false;
		}

		if (empty($data['title']) || empty($data['rating']))
		{
			// title or rating are empty
			$this->setError(JText::translate('VAPPOSTREVIEWFILLERR'));
			return false;
		}

		if ($config->getBool('revcommentreq') && empty($data['comment']))
		{
			// comment required and empty
			$this->setError(JText::translate('VAPPOSTREVIEWFILLERR'));
			return false;
		}

		if (strlen($data['comment']) > 0 && strlen($data['comment']) < $config->getUint('revminlength'))
		{
			// comment length higher than 0 but lower than min length
			$this->setError(JText::translate('VAPPOSTREVIEWFILLERR'));
			return false;
		}

		if (!isset($data['published']))
		{
			// rely on global configuration status
			$data['published'] = $config->getUint('revautopublished');
		}

		$user = JFactory::getUser();

		// always use the details of the current logged-in user
		$data['jid']   = $user->id;
		$data['name']  = $user->username;
		$data['email'] = $user->email;

		// take only the maximum number of characters
		$data['comment'] = mb_substr($data['comment'], 0, $config->getUint('revmaxlength'), 'UTF-8');

		if (!$this->save($data))
		{
			// unable to save the review
			return false;	
		}
		
		/**
		 * @todo add support for e-mail notifications to administrator(s) and employees
		 */

		return true;
	}
}
