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
 * VikAppointments statistics class handler.
 *
 * @since 1.6
 */
class VAPStatistics
{
	/**
	 * An array containing the statistics fetched.
	 *
	 * @var array
	 */
	private $statistics = array();
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $year 	  The year to fetch.
	 * @param 	integer  $id_emp  The employee to check for.
	 * @param 	integer  $id_ser  The service to check for (optional).
	 *
	 * @uses 	getStatistics()
	 */
	public function __construct($year, $id_emp, $id_ser = null)
	{
		$this->statistics = self::getStatistics($year, $id_emp, $id_ser);
	}
	
	/**
	 * Returns the total amount earned within the specified year.
	 * 
	 * @return 	float
	 */
	public function getYearTotalEarning()
	{
		return $this->statistics[self::YEAR_TOTAL_EARNING]['total'];
	}
	
	/**
	 * Returns the total number of reservations received within the
	 * specified year.
	 *
	 * @return 	integer
	 */
	public function getYearTotalReservations()
	{
		return $this->statistics[self::YEAR_TOTAL_EARNING]['numres'];
	}
	
	/**
	 * Returns the total amount earned for the specified month
	 * of the given year.
	 *
	 * @param  	integer  $month  From 1 to 12.
	 *
	 * @return 	float
	 */
	public function getMonthTotalEarning($month)
	{
		return $this->statistics[self::MONTHS_TOTAL_EARNING][$month]['total'];
	}
	
	/**
	 * Returns the total number of reservations received for the
	 * specified month of the given year.
	 *
	 * @param  	integer  $month  From 1 ro 12.
	 *
	 * @return 	integer
	 */
	public function getMonthTotalReservations($month)
	{
		return $this->statistics[self::MONTHS_TOTAL_EARNING][$month]['numres'];
	}
	
	/**
	 * Returns the total amount earned by the employee for the 
	 * specified month of the given year.
	 *
	 * @param  	integer  $month  From 1 to 12.
	 *
	 * @return 	float
	 */
	public function getEmployeeMonthTotalEarning($month)
	{
		return $this->statistics[self::EMPLOYEE_MONTHS_TOTAL_EARNING][$month]['total'];
	}
	
	/**
	 * Returns the total number of reservations received for the
	 * specified month of the given year, that belong to the
	 * current employee.
	 *
	 * @param  	integer  $month  From 1 ro 12.
	 *
	 * @return 	integer
	 */
	public function getEmployeeMonthTotalReservations($month)
	{
		return $this->statistics[self::EMPLOYEE_MONTHS_TOTAL_EARNING][$month]['numres'];
	}
	
	/**
	 * Returns an array containing the statistics details
	 * for each service within the specified month of the given year.
	 * For example:
	 * [ 
	 * 		0 => [sname, numres, total],
	 * 		1 => [sname, numres, total],
	 * ]
	 *
	 * @param  	integer  $month  From 1 ro 12.
	 *
	 * @return  array
	 */
	public function getEmployeeMonthServiceArray($month)
	{
		return $this->statistics[self::EMPLOYEE_MONTHS_SERVICE_TOTAL_EARNING][$month];
	}
	
	/**
	 * Calculates the total amount earned within the given year.
	 *
	 * @param 	integer  $year
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	array 	 An array containing the following attributes:
	 * 					 - numres  integer 	the total number of appointments;
	 * 					 - total   float 	the total amount earned.
	 *
	 * @uses 	getDateArray()
	 * @uses 	getBaseQuery()
	 */
	protected function calculateYearTotalEarning($year, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$syear = self::getDateArray($year, 1);
		$eyear = self::getDateArray($year + 1, 1);

		$q = self::getBaseQuery($syear[0], $eyear[0], $dbo);
		
		$dbo->setQuery($q);
		return $dbo->loadAssoc();
	}
	
	/**
	 * Calculates the monthly total amount earned within the given year.
	 *
	 * @param 	integer  $year
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	array 	 An array containing the statistics for each month [1-12].
	 *
	 * @uses 	getDateArray()
	 * @uses 	getBaseQuery()
	 */
	protected function calculateMonthsTotalEarning($year, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$mon_arr = array();
		
		for ($i = 1; $i <= 12; $i++)
		{	
			$smon = self::getDateArray($year, $i);

			if ($i < 12)
			{
				$emon = self::getDateArray($year, $i + 1);
			}
			else
			{
				$emon = self::getDateArray($year + 1, 1);
			}

			$q = self::getBaseQuery($smon[0], $emon[0], $dbo);
			
			$dbo->setQuery($q);
			$mon_arr[$i] = $dbo->loadAssoc();
		}
		
		return $mon_arr;
	}
	
	/**
	 * Calculates the monthly total amount earned within the given year
	 * by the specified employee.
	 *
	 * @param 	integer  $year
	 * @param 	integer  $id_emp
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	array 	 An array containing the following attributes:
	 * 					 - numres  integer 	the total number of appointments;
	 * 					 - total   float 	the total amount earned.
	 *
	 * @uses 	getDateArray()
	 * @uses 	getBaseQuery()
	 */
	protected function calculateEmployeeMonthsTotalEarning($year, $id_emp, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$mon_arr = array();
		
		for ($i = 1; $i <= 12; $i++)
		{	
			$smon = self::getDateArray($year, $i);
			
			if ($i < 12)
			{
				$emon = self::getDateArray($year, $i + 1);
			}
			else
			{
				$emon = self::getDateArray($year + 1, 1);
			}

			$q = self::getBaseQuery($smon[0], $emon[0], $dbo);

			// extend query to filter the reservations by employee ID
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);
			
			$dbo->setQuery($q);
			$mon_arr[$i] = $dbo->loadAssoc();
		}
		
		return $mon_arr;
	}
	
	/**
	 * Calculates the monthly total amount earned within the given year
	 * by the specified employee.
	 *
	 * @param 	integer  $year
	 * @param 	integer  $id_emp
	 * @param 	integer  $id_ser
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	array 	 An array containing the statistics for each month [1-12].
	 *
	 * @uses 	getDateArray()
	 * @uses 	getBaseQuery()
	 */
	protected function calculateEmployeeMonthsServiceTotalEarning($year, $id_emp, $id_ser = null, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$mon_arr = array();
		
		for ($i = 1; $i <= 12; $i++)
		{	
			$smon = self::getDateArray($year, $i);

			if ($i < 12)
			{
				$emon = self::getDateArray($year, $i + 1);
			}
			else
			{
				$emon = self::getDateArray($year + 1, 1);
			}

			$q = self::getBaseQuery($smon[0], $emon[0], $dbo);

			// extend query to split the reservations by service
			$q->select($dbo->qn('s.name', 'sname'));
			$q->from($dbo->qn('#__vikappointments_service', 's'));
			$q->where($dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

			// extend query to filter the reservations by employee ID
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);

			/**
			 * In case the service was passed, retrieve only the information for that service.
			 * Otherwise group by service name in order to retrieve all the services properly.
			 *
			 * @since 1.6.3
			 */
			if ($id_ser)
			{
				$q->where($dbo->qn('r.id_service') . ' = ' . (int) $id_ser);
			}
			else
			{
				$q->group($dbo->qn('s.name'));
			}
			
			$dbo->setQuery($q);
			$mon_arr[$i] = $dbo->loadAssocList();
		}
		
		return $mon_arr;
	}
	
	/**
	 * Returns an array containing the statistics fetched.
	 * Here's the elements list:
	 * - [ytl] 		Year total earning;
	 * - [mtl] 		Monthly total earning;
	 * - [emtl] 	Employee monthly total earning;
	 * - [emstl] 	Employee monthly total earning for each service.
	 *
	 * @param 	integer  $year
	 * @param 	integer  $id_emp
	 * @param 	integer  $id_ser
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	array
	 *
	 * @uses 	calculateYearTotalEarning()
	 * @uses 	calculateMonthsTotalEarning()
	 * @uses 	calculateEmployeeMonthsTotalEarning()
	 * @uses 	calculateEmployeeMonthsServiceTotalEarning()
	 */
	public function getStatistics($year, $id_emp, $id_ser = null, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$stat = array(
			self::YEAR_TOTAL_EARNING 					=> self::calculateYearTotalEarning($year, $dbo),
			self::MONTHS_TOTAL_EARNING 					=> self::calculateMonthsTotalEarning($year, $dbo),
			self::EMPLOYEE_MONTHS_TOTAL_EARNING 		=> self::calculateEmployeeMonthsTotalEarning($year, $id_emp, $dbo),
			self::EMPLOYEE_MONTHS_SERVICE_TOTAL_EARNING => self::calculateEmployeeMonthsServiceTotalEarning($year, $id_emp, $id_ser, $dbo),
		);
		
		return $stat;
	}
	
	/**
	 * Returns a date array for the specified year and month.
	 *
	 * @param 	integer  $year   The year. If null, the current year will be used.
	 * @param 	integer  $month  The month. If null, the current month will be used.
	 *
	 * @return 	array 	 The date array.
	 *
	 * @see 	getdate()
	 */
	private function getDateArray($year = null, $month = null)
	{
		$arr = getdate();

		if (!$month)
		{
			$month = $arr['mon'];
		}

		if (!$year)
		{
			$year = $arr['year'];
		}
		
		return getdate(mktime(0, 0, 0, $month, 1, $year));
	}

	/**
	 * Returns the base query to use to fetch the statistics.
	 *
	 * @param 	integer  $start  The initial delimiter timestamp (included).
	 * @param 	integer  $end 	 The ending delimiter timestamp (excluded).
	 * @param 	mixed 	 $dbo
	 *
	 * @return 	mixed 	 The query builder instance.
	 */
	private function getBaseQuery($start, $end, $dbo)
	{
		$q = $dbo->getQuery(true);

		$q->select('COUNT(' . $dbo->qn('r.id') . ') AS ' . $dbo->qn('numres'));
		$q->select('SUM(' . $dbo->qn('r.total_cost') . ') AS ' . $dbo->qn('total'));

		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		$q->where(array(
			$dbo->qn('r.status') . ' = ' . $dbo->q('CONFIRMED'),
			$dbo->qn('r.checkin_ts') . ' BETWEEN ' . $start . ' AND ' . ($end - 1),
			$dbo->qn('r.closure') . ' = 0',
		));

		return $q;
	}
	
	/**
	 * Identifier for year total earning.
	 *
	 * @var string
	 */
	const YEAR_TOTAL_EARNING = 'ytl';

	/**
	 * Identifier for months total earning.
	 *
	 * @var string
	 */
	const MONTHS_TOTAL_EARNING = 'mtl';

	/**
	 * Identifier for employee months total earning.
	 *
	 * @var string
	 */
	const EMPLOYEE_MONTHS_TOTAL_EARNING = 'emtl';

	/**
	 * Identifier for employee months service total earning.
	 *
	 * @var string
	 */
	const EMPLOYEE_MONTHS_SERVICE_TOTAL_EARNING = 'emstl';	
}
