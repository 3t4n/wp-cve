<?php


namespace MABEL_BHI_LITE\Services
{

	use MABEL_BHI_LITE\Models\Location;
	use MABEL_BHI_LITE\Models\Opening_Hours;
	use MABEL_BHI_LITE\Models\Opening_Hours_Set;
	use MABEL_BHI_LITE\Models\Special_Date;
	use MABEL_BHI_LITE\Models\Vacation;

	if(!defined('ABSPATH')){die;}

	class Conversion_Service
	{

		private static function raw_hour_to_opening_hours($hour, $dayname)
		{
			$start_time = DateTime_Service::getInstance()->convertToDateInWeek($hour->From.' '.$hour->FromIndication,$dayname);
			$end_time = DateTime_Service::getInstance()->convertToDateInWeek($hour->To .' '.$hour->ToIndication,$dayname);
			$after_midnight = false;

			if($end_time < $start_time){
				$end_time = DateTime_Service::getInstance()->addDays($end_time,1);
				$after_midnight = true;
			}

		    $hours = new Opening_Hours($start_time,$end_time);
			$hours->after_midnight = $after_midnight;

			return $hours;
		}

		public static function convert_to_location($raw,$location_name = null)
		{
			if( empty( $raw ) ) return null;

			$raw_json = json_decode($raw);
			if( empty( $raw_json ) ) return null;

			if($location_name === null && sizeof($raw_json) > 0){
				return self::create_location($raw_json[0]);
			}

			foreach($raw_json as $raw_location){
				if($raw_location->Name != $location_name) continue;

				return self::create_location($raw_location);
			}

			return null;
		}

		private static function create_location($raw_location)
		{
			$location = new Location($raw_location->Name);

			if(is_array($raw_location->Days))
			{
				foreach($raw_location->Days as $day)
				{
					$dayname = $day->Day;
					$short = DateTime_Service::getInstance()->getShortDOWFromDay($dayname);

					$week_day_int = DateTime_Service::getInstance()->getDayOfWeekAsInt($dayname);

					$set = new Opening_Hours_Set($week_day_int,$dayname, $short);

					$set->is_today =DateTime_Service::getInstance()->getToday() === $week_day_int;

					if(is_array($day->Hours))
					{
						foreach($day->Hours as $hour)
						{
							if($hour->From == 0 || $hour->To == 0) continue;
							$set->opening_hours[] = Conversion_Service::raw_hour_to_opening_hours( $hour, $dayname );
						}
					}
					$location->opening_hours[] = $set;
				}
			}

			if(is_array($raw_location->Holidays))
			{
				foreach($raw_location->Holidays as $raw_holiday)
				{
					$date = DateTime_Service::getInstance()->dayMonthToDate($raw_holiday->Day, $raw_holiday->Month);
					$holiday = new Special_Date($date);
					$now = DateTime_Service::getInstance()->getNow();
					$holiday->is_today = $now->format('j') === $date->format('j') && $now->format('n') === $date->format('n');

					if(is_array($raw_holiday->Hours))
					{
						foreach($raw_holiday->Hours as $hour)
						{
							if($hour->From == 0 || $hour->To == 0) continue;
							$start_time = DateTime_Service::getInstance()->toDateTime( $raw_holiday->Day, $raw_holiday->Month, $hour->From, $hour->FromIndication );
							$end_time = DateTime_Service::getInstance()->toDateTime( $raw_holiday->Day, $raw_holiday->Month, $hour->To, $hour->ToIndication );
							$after_midnight = false;
							if($end_time <= $start_time) {
								$after_midnight = true;
								$end_time = DateTime_Service::getInstance()->addDays($end_time,1);
							}
							$hrs = new Opening_Hours($start_time,$end_time);
							$hrs->after_midnight = $after_midnight;
							$holiday->opening_hours[] = $hrs;
						}
					}
					$location->specials[] = $holiday;
				}
			}

			if(is_array($raw_location->Vacations))
			{
				foreach($raw_location->Vacations as $raw_vacation)
				{
					$from_date = DateTime_Service::getInstance()->dayMonthToDate($raw_vacation->FromDay, $raw_vacation->From);
					$to_date = DateTime_Service::getInstance()->dayMonthToDate($raw_vacation->ToDay, $raw_vacation->To);

					if($to_date < $from_date)
						$to_date->modify('+1 year');

					$vacation = new Vacation($from_date,$to_date);
					$vacation->spans_today = DateTime_Service::getInstance()->now_between_dates($from_date,$to_date);
					$location->vacations[] = $vacation;
				}
			}
			return $location;
		}

		public static function convert_to_locations($raw)
		{
			if( empty( $raw ) ) return [];

			$locations = [];
			$raw_json = json_decode($raw);

			foreach( $raw_json as $raw_location )
			{
				$locations[] = self::create_location($raw_location);
			}

			return $locations;
		}

	}

}