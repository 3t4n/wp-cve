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
 * VikAppointments e-mail custom text model.
 *
 * @since 1.7
 */
class VikAppointmentsModelMailtext extends JModelVAP
{
	/**
	 * A list of attachments fetched while parsing the compatible mail texts.
	 * 
	 * @var string[]
	 * @since 1.7.4
	 */
	protected $attachments = [];

	/**
	 * Parses the e-mail custom texts.
	 *
	 * @param 	string 	$tmpl     The e-mail template (HTML).
	 * @param 	mixed   $order    An object containing the order details.
	 * @param 	array 	$options  An array of options:
	 *                            - lang     string  The language tag to use.
	 *                            - file     string  The template file to search for.
	 *                            - id       mixed   Either an ID or a list of custom texts.
	 *                            - default  bool    True to load the default custom fields,
	 *                                               false to use only the specified ID. 
	 *
	 * @return 	string 	The parsed HTML template.
	 */
	public function parseTemplate($tmpl, $order, array $options = [])
	{
		$dbo = JFactory::getDbo();

		if (empty($options['lang']))
		{
			// use order lang tag in case it was not specified
			$options['lang'] = $order->langtag;

			if (!$options['lang'])
			{
				// the order is not assigned to any lang tag, use the current one
				$options['lang'] = JFactory::getLanguage()->getTag();
			}
		}

		$services  = array();
		$employees = array();
		$payments  = array();

		// extract all booked services and employees
		foreach ($order->appointments as $app)
		{
			if (!in_array($app->service->id, $services))
			{
				$services[] = $app->service->id;
			}

			if (!in_array($app->employee->id, $employees))
			{
				$employees[] = $app->employee->id;
			}
		}

		if ($order->payment)
		{
			$payments[] = $order->payment->id;
		}
	
		// push 0 to catch all the records that do not specify a service, an employee or a payment
		array_unshift($services, 0);
		array_unshift($employees, 0);
		array_unshift($payments, 0);

		$rows = array();

		// find custom e-mail custom texts
		$q = $dbo->getQuery(true);
		$q->select('m.*');
		$q->from($dbo->qn('#__vikappointments_cust_mail', 'm'));

		/**
		 * Take only published custom texts.
		 *
		 * @since 1.6.5
		 */
		$q->where($dbo->qn('m.published') . ' = 1');

		// filter by tag
		$q->where($dbo->qn('m.tag') . ' = ' . $dbo->q($options['lang']));

		// filter by services
		$q->where($dbo->qn('m.id_service') . ' IN (' . implode(', ', array_map('intval', $services)) . ')');
		
		// filter by employees
		$q->where($dbo->qn('m.id_employee') . ' IN (' . implode(', ', array_map('intval', $employees)) . ')');

		/**
		 * Filter by payment method.
		 * 
		 * @since 1.7.4
		 */
		$q->where($dbo->qn('m.id_payment') . ' IN (' . implode(', ', array_map('intval', $payments)) . ')');

		// filter by file
		if (!empty($options['file']))
		{
			/**
			 * Added support to empty files.
			 *
			 * @since 1.6.5
			 */
			$q->andWhere(array(
				$dbo->qn('m.file') . ' = ' . $dbo->q(''),
				$dbo->qn('m.file') . ' = ' . $dbo->q(basename($options['file'])),
			), 'OR');
		}

		/**
		 * Added support to empty statuses.
		 *
		 * @since 1.6.5
		 */
		$q->andWhere(array(
			$dbo->qn('status') . ' = ' . $dbo->q(''),
			$dbo->qn('status') . ' = ' . $dbo->q($order->status),
		), 'OR');

		/**
		 * Trigger event to allow the plugins to manipulate the query used to retrieve
		 * the available mail custom texts.
		 *
		 * @param 	mixed  &$query   The query string or a query builder object.
		 * @param 	mixed  $order    An object containing the order details.
	 	 * @param 	array  $options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onFetchCompatibleMailTexts', array(&$q, $order, $options));

		/**
		 * In case a specific e-mail text was specified (or more than one),
		 * always take it even if the arguments don't match.
		 *
		 * Do it after executing any plugin hook so that the following code
		 * is applied at the end.
		 *
		 * @since 1.6.5
		 */
		if (!empty($options['id']))
		{
			$default = isset($options['default']) ? (bool) $options['default'] : true;

			// cast to array to support multiple values
			$options['id'] = (array) $options['id'];

			// check if we should preserve the default conditions
			if ($default)
			{
				// load together with default custom texts
				$q->orWhere($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $options['id'])) . ')');	
			}
			else
			{
				// load only the custom text with the specified ID
				$q->clear('where');
				$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $options['id'])) . ')');
			}
		}

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		// lookup of available positions
		$positions = array(
			'{custom_position_top}'    => '',
			'{custom_position_middle}' => '',
			'{custom_position_bottom}' => '',
			'{custom_position_footer}' => '',
		);

		// always reset the attachments whenever this method is invoked
		$this->attachments = [];
		
		// Iterate the records to attach the mail contents.
		// It is required to replace the placeholders after the iteration
		// because 2 or more records may share the same position.
		foreach ($rows as $r)
		{
			/**
			 * Render HTML description to interpret attached plugins.
			 * 
			 * @since 1.6.3
			 */
			$r->content = VikAppointments::renderHtmlDescription($r->content, 'custmail');

			// append the content to the existing position
			$positions[$r->position] .= $r->content;

			/**
			 * Register the configured attachments within a list for later use.
			 * 
			 * @since 1.7.4
			 */
			if ($r->attachments)
			{
				$this->attachments = array_merge($this->attachments, (array) json_decode($r->attachments, true));
			}
		}
		
		// replace any existing placeholder
		foreach ($positions as $k => $v)
		{
			$tmpl = str_replace($k, $v, $tmpl);
		}
		
		return $tmpl;	
	}

	/**
	 * Returns the compatible e-mail attachments.
	 * 
	 * @return  string[]
	 * 
	 * @since   1.7.4
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}
}
