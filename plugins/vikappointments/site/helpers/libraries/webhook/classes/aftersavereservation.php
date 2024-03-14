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
 * Web hook handler for appointments creation/update.
 *
 * @since 1.7
 */
class VAPWebHookAfterSaveReservation extends VAPWebHook
{
	/**
	 * Flag used to check whether the selected record has been inserted or updated.
	 *
	 * @var boolean
	 */
	protected $isNew = false;

	/**
	 * @override
	 * Class constructor.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 */
	public function __construct($hook, $payload)
	{
		if (is_array($payload) && count($payload) > 1)
		{
			// The payload is an array containing the properties of the saved
			// reservation, a flag to check whether the record has been inserted
			// or updated and a JTable instance. We need to use only the first
			// argument of the list.
			$this->isNew = (bool) $payload[1];
			$payload = array_shift($payload);
		}

		// construct through parent
		parent::__construct('onAfterSaveReservation', $payload);
	}

	/**
	 * @override
	 * Returns a readable name of the hook.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAP_WEBHOOK_SAVE_RESERVATION');
	}

	/**
	 * @override
	 * Returns an associative array of supported parameters.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		return array(
			/**
			 * Choose whether the webhook should observe the created
			 * records, the updated records or both.
			 *
			 * @var list
			 */
			'type' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_WEBHOOK_SAVETYPE_PARAM'),
				'help'    => JText::translate('VAP_WEBHOOK_SAVETYPE_PARAM_HELP'),
				'default' => 'insert',
				'options' => array(
					'insert' => JText::translate('VAP_WEBHOOK_SAVETYPE_PARAM_INSERT'),
					'update' => JText::translate('VAP_WEBHOOK_SAVETYPE_PARAM_UPDATE'),
					'both'   => JText::translate('VAP_WEBHOOK_SAVETYPE_PARAM_BOTH'),
				),
			),

			/**
			 * A list of statuses to observe. The web hook will be dispatched
			 * only in case the status of the appointment is contained within
			 * this list. Leave empty to accept all the statuses.
			 *
			 * @var list
			 */
			'statuses' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAP_WEBHOOK_STATUSES_PARAM'),
				'help'     => JText::translate('VAP_WEBHOOK_STATUSES_PARAM_HELP'),
				'default'  => array(),
				'multiple' => true,
				'options'  => JHtml::fetch('vaphtml.admin.statuscodes', 'appointments'),
			),

			/**
			 * Choose the loading mode of the records:
			 * - basic     send only the specified data;
			 * - full      send the whole table row;
			 * - extended  send an extended version of the row.
			 *
			 * @var list
			 */
			'load' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_WEBHOOK_LOAD_PARAM'),
				'help'    => JText::translate('VAP_WEBHOOK_LOAD_PARAM_HELP'),
				'default' => 'basic',
				'options' => array(
					'basic'    => JText::translate('VAP_WEBHOOK_LOAD_PARAM_BASIC'),
					'full'     => JText::translate('VAP_WEBHOOK_LOAD_PARAM_FULL'),
					'extended' => JText::translate('VAP_WEBHOOK_LOAD_PARAM_EXTENDED'),
				),
			),
		);
	}

	/**
	 * @override
	 * Returns the registered payload.
	 *
	 * @param 	mixed  $options  Either an array or an object, which should contain
	 *                           the value of the specified parameters.
	 *
	 * @return 	mixed
	 */
	public function getPayload($options = array())
	{
		$options = new JRegistry($options);
		// fetch loading mode
		$load = $options->get('load');
		// fetch type setting
		$type = $options->get('type');
		// get statuses
		$statuses = (array) $options->get('statuses');

		if (($type == 'insert' && !$this->isNew) || ($type == 'update' && $this->isNew))
		{
			// do not dispatch the web hook in case of mismatch type
			return false;
		}

		$payload = (array) $this->payload;

		if (!empty($payload['id_parent']) && $payload['id_parent'] <= 0)
		{
			// handling a multi-order parent, ignore it
			return false;
		}

		$status = !empty($payload['status']) ? $payload['status'] : null;

		if (!empty($payload['id']))
		{
			if ($load == 'full')
			{
				// get all details of the reservation table
				$payload = JModelVAP::getInstance('reservation')->getItem($payload['id']);
				// fetch correct status
				$status = $payload ? $payload->status : null;
			}
			else if ($load == 'extended')
			{
				// load the extended version of the order details
				VAPLoader::import('libraries.order.factory');
				$payload = VAPOrderFactory::getAppointments($payload['id'], VikAppointments::getDefaultLanguage());
				// fetch correct status
				$status = $payload->status;
			}
			else
			{
				// strip unset properties
				$payload = array_filter($payload, function($value)
				{
					return $value !== null;
				});
			}
		}

		// check whether this webhook observes the status of the appointment
		if ($statuses && !in_array($status, $statuses))
		{
			// this status is not observed
			return false;
		}

		return $payload;
	}

	/**
	 * Returns the appointment ID.
	 *
	 * @return 	integer
	 */
	public function getOrderID()
	{
		$payload = (array) $this->payload;

		return !empty($payload['id']) ? (int) $payload['id'] : 0;
	}

	/**
	 * @override
	 * Comparator to check whether 2 instances share the same payload parent.
	 *
	 * @return 	boolean
	 */
	public function equalsTo($webhook)
	{
		if (!$webhook instanceof VAPWebHookAfterSaveReservation)
		{
			// invalid instance
			return false;
		}

		// the order ID
		$id = $this->getOrderID();

		if (!$id)
		{
			// invalid ID
			return false;
		}

		// compare both the IDs
		return $id == $webhook->getOrderID();
	}
}
