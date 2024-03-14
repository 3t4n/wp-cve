<?php

namespace MABEL_BHI_LITE\Services
{
	if(!defined('ABSPATH')){die;}

	use DateTime;
	use DateTimeZone;
	use DateInterval;
	use MABEL_BHI_LITE\Core\Settings_Manager;
	use MABEL_BHI_LITE\Models\Opening_Hours;
	use MABEL_BHI_LITE\Models\Opening_Hours_Set;
	use Exception;

	class DateTime_Service
	{

		private $timeZone;

		private $now;

		private $today;

		private $daysOfWeek;

		private $daysOfWeekShort;

		private static $instance;

		private function __construct($timeZone)
		{
			$this->timeZone = new DateTimeZone($timeZone);
			$this->now = new DateTime('now', $this->timeZone);
			$this->daysOfWeek = [ 'Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday' ];
			$this->daysOfWeekShort = [ 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su' ];
			$this->today = (int)$this->now->format('N'); 
		}

		public static function getInstance()
		{
			if ( is_null( self::$instance ) )
			{
				self::$instance = new self(Settings_Manager::get_setting('timezone'));
			}

			return self::$instance;
		}

		public function getDOWFromDate(DateTime $dt)
		{
			return $dt->format('l');
		}

		public function getDayOfWeekAsInt($dt)
		{
			if($dt instanceof DateTime)
				return $dt->format('N');
			else 
				return array_search($dt, $this->daysOfWeek) + 1;
		}

		public function getShortDOWFromDay($day)
		{
			$idx = array_search($day,$this->daysOfWeek);

			if($idx === false) return false;

			return $this->daysOfWeekShort[$idx];
		}

		public function convertToDateInWeek($time, $dayOfWeek, $offset_in_weeks = 0)
		{

			$dayAsNumber = array_search($dayOfWeek, $this->daysOfWeek) + 1;
			$offset = ($dayAsNumber - $this->today) + ($offset_in_weeks * 7);
			$dateTime = new DateTime($time, $this->timeZone);

			if($offset === 0)
				return $dateTime;

			$interval = new DateInterval('P'.abs($offset).'D');

			return $offset > 0 ? $dateTime->add($interval) : $dateTime->sub($interval);

		}

		public function copy_sets_to_future(array $sets,$offset_in_weeks = 1)
		{
			if($offset_in_weeks === 0)
				return $sets;

			$new_sets = [];
			foreach($sets as $set)
			{
				$new_set = new Opening_Hours_Set($set->day_of_week,$set->day_name,$set->day_name_short);

				foreach($set->opening_hours as $hour)
				{
					$start_time = DateTime_Service::getInstance()->convertToDateInWeek($hour->start_time,$set->day_name,$offset_in_weeks);
					$end_time = DateTime_Service::getInstance()->convertToDateInWeek($hour->end_time,$set->day_name,$offset_in_weeks);
					$after_midnight = false;

					if($end_time < $start_time){
						$end_time = DateTime_Service::getInstance()->addDays($end_time,1);
						$after_midnight = true;
					}
					$new_hour = new Opening_Hours($start_time,$end_time);
					$new_hour->after_midnight = $after_midnight;
					$new_set->opening_hours[] = $new_hour;
				}
				$new_sets[] = $new_set;
			}
			return $new_sets;
		}

		public function addDays(DateTime $date,$number_of_days){
			if(!is_int($number_of_days)) throw new Exception('No valid number of days given.');
			$new = clone $date;
			return $new->add(new DateInterval('P' . $number_of_days . 'D'));
		}

		public function dayMonthToDate($day, $month)
		{
			return new DateTime(sprintf('%s %s',$day,$month),$this->timeZone);
		}

		public function toDateTime($day, $month, $time, $time_indication = 'AM')
		{
			return new DateTime(
				sprintf('%s %s, %s%s',
					$day,
					$month,
					$time,
					$time_indication
				), $this->timeZone);
		}

		public function getNow()
		{
			return $this->now;
		}

		public function getToday()
		{
			return $this->today;
		}

		public function get_difference(DateTime $date, DateTime $date2, $type = 'seconds')
		{
			$interval = $date->diff($date2);

			switch ($type)
			{
				case 'seconds': return $interval->days*86400 + $interval->h*3600 + $interval->i*60 + $interval->s;
				case 'minutes': return $interval->days*1440 + $interval->h*60 + $interval->i;
				case 'hours': return $interval->days*24 + $interval->h;
			}

			return $interval->days; 
		}

		public function now_between_dates(DateTime $start, DateTime $end)
		{
			return $start <= $this->getNow() && $end >= $this->getNow();
		}

	}
}