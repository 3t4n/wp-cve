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
 * Widget used to fetch the most booked employees by each selected customer.
 *
 * @since 1.7
 */
class VAPStatisticsWidgetCustomersPreferredEmployees extends VAPStatisticsWidget
{
	/**
	 * Internal flag used to check whether the current user
	 * owns enough permissions to access the financial data.
	 *
	 * Use true by default in case the widget is displayed
	 * without checking the permissions.
	 *
	 * @var boolean
	 */
	protected $hasFinanceAccess = true;

	/**
	 * Checks whether the specified group is supported by the widget.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		return empty($group) || $group == '*' || $group == 'customers';
	}

	/**
	 * Checks whether the specified user is capable to access this widget.
	 *
	 * @param 	JUser    $user  The user instance.
	 *
	 * @return 	boolean  True if capable, false otherwise.
	 */
	public function checkPermissions($user)
	{
		// detected finance visibility
		$this->hasFinanceAccess = $user->authorise('core.access.analytics.finance', 'com_vikappointments');

		return $user->authorise('core.access.analytics.customers', 'com_vikappointments');
	}

	/**
	 * Returns to the caller the financial rights of the user.
	 *
	 * @return 	boolean  True if capable, false otherwise.
	 */
	public function hasFinanceAccess()
	{
		return $this->hasFinanceAccess;
	}

	/**
	 * Override this method to return a configuration form of the widget.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		return array(
			/**
			 * The initial date of the range.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'datefrom' => array(
				'type'     => 'date',
				'label'    => JText::translate('VAPEXPORTRES3'),
				'volatile' => true,
			),

			/**
			 * The ending date of the range.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'dateto' => array(
				'type'     => 'date',
				'label'    => JText::translate('VAPEXPORTRES4'),
				'volatile' => true,
			),

			/**
			 * The ordering mode of the table rows.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var date
			 */
			'ordering' => array(
				'type'     => 'hidden',
				'label'    => JText::translate('VAPMANAGECUSTOMF6'),
				'default'  => 'count.desc',
				'volatile' => true,
			),

			/**
			 * Flag used to filter the appointments by customer.
			 *
			 * The parameter is VOLATILE because, every time the session
			 * ends, we need to restore the field to an empty value, just
			 * to obtain the current date.
			 *
			 * @var hidden
			 */
			'customers' => array(
				'type'     => 'hidden',
				'default'  => null,
				'volatile' => true,
			),
		);
	}

	/**
	 * Checks whether the widget is able to export the fetched data.
	 *
	 * @return 	array  A list of supported exportable functions.
	 */
	public function isExportable()
	{
		return ['export', 'print'];
	}

	/**
	 * Loads the dataset(s) that will be recovered asynchronously
	 * for being displayed within the widget.
	 *
	 * It is possible to return an array of records to be passed
	 * to a chart or directly the HTML to replace.
	 *
	 * @return 	mixed
	 */
	public function getData()
	{
		// fetch data and display
		return $this->display(['data' => $this->fetchData()]);
	}

	/**
	 * Create adapter for export method.
	 * This widget only supports "print" export function.
	 *
	 * @param 	mixed  $rule  The requested export type.
	 *
	 * @return 	void
	 */
	public function export($rule = null)
	{
		// auto-fetch the widget data
		$data = $this->fetchData();

		if ($rule == 'print')
		{
			// auto-print the document
			JHtml::fetch('vaphtml.sitescripts.winprint', 256);

			// display widget with fetched data
			echo $this->display(['data' => $data]);
		}
		else
		{
			$app = JFactory::getApplication();

			// send headers for CSV download
			$app->setHeader('Cache-Control', 'no-store, no-cache');
			$app->setHeader('Content-Type', 'text/csv; charset=UTF-8');
			$app->setHeader('Content-Disposition', 'attachment; filename="' . htmlspecialchars($this->getTitle()) . '.csv"');
			$app->sendHeaders();
			
			$handle = fopen('php://output', 'w');

			// create table heading
			$head = array(
				// customer
				JText::translate('VAPMANAGERESERVATION38'),
				// employee
				JText::translate('VAPMANAGERESERVATION3'),
				// count
				JText::translate('VAPMENUTITLEHEADER2'),
			);
			
			// include totals only in case of financial rights
			if ($this->hasFinanceAccess)
			{
				// total gross
				$head[] = JText::translate('VAPTOTALGROSS');
				// total tax
				$head[] = JText::translate('VAPTOTALTAX');
				// total net
				$head[] = JText::translate('VAPTOTALNET');
				// total discount
				$head[] = JText::translate('VAPMANAGEPACKAGE13');
			}

			// include table heading
			fputcsv($handle, $head, ',', '"');

			// make sure we have an array to export
			if ($data)
			{
				$currency = VAPFactory::getCurrency();

				foreach ($data as $customer => $table)
				{
					$includeCustomerName = true;

					// iterate all records
					foreach ($table['body'] as $columns)
					{
						$row = array();

						if ($includeCustomerName)
						{
							// include customer name only on the first row
							$row[] = $customer;
						}
						else
						{
							// include empty slot
							$row[] = '';
						}

						// employee
						$row[] = $columns['employee'];
						// count
						$row[] = $columns['count'];

						// include totals only in case of financial rights
						if ($this->hasFinanceAccess)
						{
							// total gross
							$row[] = $currency->format($columns['total']);
							// total tax
							$row[] = $currency->format($columns['tax']);
							// total net
							$row[] = $currency->format($columns['net']);
							// total discount
							$row[] = $currency->format($columns['discount']);
						}

						// include table row
						fputcsv($handle, $row, ',', '"');

						// stop including customer name
						$includeCustomerName = false;
					}

					$row = array(
						// customer
						$includeCustomerName ? $customer : '',
						// employee
						'',
						// count
						isset($table['footer']['count']) ? $table['footer']['count'] : 0,
					);

					// include totals only in case of financial rights
					if ($this->hasFinanceAccess)
					{
						// total gross
						$row[] = $currency->format(isset($table['footer']['total']) ? $table['footer']['total'] : 0);
						// total tax
						$row[] = $currency->format(isset($table['footer']['tax']) ? $table['footer']['tax'] : 0);
						// total net
						$row[] = $currency->format(isset($table['footer']['net']) ? $table['footer']['net'] : 0);
						// total discount
						$row[] = $currency->format(isset($table['footer']['discount']) ? $table['footer']['discount'] : 0);
					}

					// include customer footer
					fputcsv($handle, $row, ',', '"');
				}
			}

			fclose($handle);

			// terminate session
			$app->close();
		}
	}

	/**
	 * Helper getter used to access the ordering data of the widget.
	 *
	 * @return 	array  An associative array containing the ordering column and direction.
	 */
	public function getOrdering()
	{
		// extract ordering mode from widget settings, built as [COLUMN].[DIRECTION]
		$ordering = strtolower($this->getOption('ordering', 'count.desc'));

		if (strpos($ordering, '.') !== false)
		{
			// split ordering to obtain column and direction
			list($order, $orderDir) = explode('.', $ordering);
		}
		else
		{
			// use default ordering ascending direction
			$order    = $ordering;
			$orderDir = 'asc';
		}

		return array(
			'column'    => $order,
			'direction' => $orderDir,
		);
	}

	/**
	 * Loads the dataset(s) that will be recovered asynchronously
	 * for being displayed within the widget.
	 *
	 * It is possible to return an array of records to be passed
	 * to a chart or directly the HTML to replace.
	 *
	 * @return 	mixed
	 */
	protected function fetchData()
	{
		$filters = array();
		$filters['datefrom']  = $this->getOption('datefrom');
		$filters['dateto']    = $this->getOption('dateto');
		$filters['customers'] = $this->getOption('customers', null);

		$tz = JFactory::getUser()->getTimezone();

		if (!VAPDateHelper::isNull($filters['datefrom']))
		{
			// convert specified date to SQL format
			$filters['datefrom'] = new JDate(VAPDateHelper::getDate($filters['datefrom'], 0, 0, 0), $tz);	
		}

		if (!VAPDateHelper::isNull($filters['dateto']))
		{
			// convert specified date to SQL format
			$filters['dateto'] = new JDate(VAPDateHelper::getDate($filters['dateto'], 23, 59, 59), $tz);
		}

		// import customers helper
		VAPLoader::import('libraries.statistics.helpers.customers');
		// fetch preferred employees, grouped by customer
		$table = VAPStatisticsHelperCustomers::getPreferredEmployees($filters['customers'], $filters['datefrom'], $filters['dateto']);

		if (!$table)
		{
			// nothing to fetch
			return false;
		}

		$data = array();

		foreach ($table as $id_user => $customer)
		{
			$body = $footer = array();

			foreach ($customer['employees'] as $employee => $row)
			{
				// add row to table body
				$body[] = $row;

				// iterate row columns
				foreach ($row as $k => $v)
				{
					if (is_numeric($v))
					{
						if (!isset($footer[$k]))
						{
							$footer[$k] = 0;
						}

						// sum column to footer
						$footer[$k] += $v;
					}
				}
			}

			$data[$customer['name']] = array(
				'body'   => $body,
				'footer' => $footer,
			);
		}

		// extract widget ordering
		$ordering = $this->getOrdering();

		foreach ($data as $customer => $table)
		{
			// order the items according to the specified column and direction
			uasort($data[$customer]['body'], function($a, $b) use ($ordering)
			{
				if (!isset($a[$ordering['column']]) || !isset($b[$ordering['column']]))
				{
					// the specified ordering column is not supported
					throw new DomainException(sprintf('Ordering [%s] not supported', $ordering['column']), 400);
				}

				if (is_numeric($a[$ordering['column']]))
				{
					// compare numbers
					$diff = $a[$ordering['column']] - $b[$ordering['column']];
				}
				else
				{
					// compare texts
					$diff = strcasecmp($a[$ordering['column']], $b[$ordering['column']]);
				}

				if ($ordering['direction'] == 'desc')
				{
					// reverse direction in case of descending ordering
					$diff *= -1;
				}

				return $diff;
			});
		}

		return $data;
	}
}
