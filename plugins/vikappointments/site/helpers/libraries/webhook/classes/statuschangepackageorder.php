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
 * Web hook handler for packages status change.
 *
 * @since 1.7
 */
class VAPWebHookStatusChangePackageorder extends VAPWebHook
{
	/**
	 * @override
	 * Class constructor.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 */
	public function __construct($hook, $payload)
	{
		if (is_array($payload))
		{
			// The payload is an array containing the properties of the saved
			// order status, and an array of options. We need to use only the
			// first argument of the list.
			$payload = array_shift($payload);
		}

		// construct through parent
		parent::__construct('onStatusChangePackageorder', $payload);
	}

	/**
	 * @override
	 * Returns a readable name of the hook.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAP_WEBHOOK_STATUSCHANGE_PACKORDER');
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
			 * A list of statuses to observe. The web hook will be
			 * dispatched only in case the status changed is contained
			 * within this list. Leave empty to accept all the statuses.
			 *
			 * @var list
			 */
			'statuses' => array(
				'type'     => 'select',
				'label'    => JText::translate('VAP_WEBHOOK_STATUSES_PARAM'),
				'help'     => JText::translate('VAP_WEBHOOK_STATUSES_PARAM_HELP'),
				'default'  => array(),
				'multiple' => true,
				'options'  => JHtml::fetch('vaphtml.admin.statuscodes', 'packages'),
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
		// get statuses
		$statuses = (array) $options->get('statuses');

		$payload = (array) $this->payload;

		if ($statuses && !in_array($payload['status'], $statuses))
		{
			// this status is not observed
			return false;
		}

		if (!empty($payload['id_order']))
		{
			if ($load == 'full')
			{
				// get all details of the package order table
				$payload = JModelVAP::getInstance('packorder')->getItem($payload['id_order']);
			}
			else if ($load == 'extended')
			{
				// load the extended version of the order details
				VAPLoader::import('libraries.order.factory');
				$payload = VAPOrderFactory::getPackages($payload['id_order'], VikAppointments::getDefaultLanguage());
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

		return $payload;
	}
}
