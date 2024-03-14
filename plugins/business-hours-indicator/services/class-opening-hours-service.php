<?php

namespace MABEL_BHI_LITE\Services
{

	use MABEL_BHI_LITE\Core\Linq\Enumerable;
	use MABEL_BHI_LITE\Models\Location;
	use MABEL_BHI_LITE\Models\Opening_Hours;
	use MABEL_BHI_LITE\Models\Special_Date;
	use MABEL_BHI_LITE\Models\Vacation;
	use DateTime;

	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

	class Opening_Hours_Service
	{
		private static $instance;

		private $now;

		private function __construct()
		{
			$this->now = DateTime_Service::getInstance()->getNow();
		}

		public static function instance()
		{
			if ( is_null( self::$instance ) )
			{
				self::$instance = new self();
			}

			return self::$instance;
		}

		#region Public Methods
		public function is_today_vacation(array $vacations)
		{
			return $this->is_vacation($vacations,$this->now);
		}

		public function is_vacation(array $vacations, DateTime $d) {

			foreach($vacations as $vacation)
			{
				$from = $vacation->from;
				$to = $vacation->to;
				if($from == $to) 
					$to = DateTime_Service::getInstance()->addDays($to, 1);

				if($from  <= $d && $d < $to) return true;
			}
			return false;
		}

		public function isTodaySpecialDate(array $specials)
		{
			foreach($specials as $special)
			{
				$days_diff = $this->now->diff($special->date)->days;
				if($days_diff === 0) return true;
			}
			return false;
		}

		public function is_open(Location $location)
		{
			if(Opening_Hours_Service::instance()->is_today_vacation($location->vacations))
				return false;

			$now = DateTime_Service::getInstance()->getNow();

			$same_days = Enumerable::from($location->specials)->where( function($special) use ($now) {
				return $now->format('j') === $special->date->format('j') && $now->format('n') === $special->date->format('n');
			})->toArray();

			if(!empty($same_days)) {
				foreach ( $same_days as $day ) {
					if (  $day->is_closed() ) {
						return false;
					}
					if ( $this->now_in_between( $day->opening_hours ) ) {
						return true;
					}
				}
				return false;
			}

			foreach ($location->opening_hours as $set)
			{
				if($set->is_closed()) continue;
				if( $this->now_in_between($set->opening_hours) ){
					return true;
				}
			}
			return false;
		}

		public function get_next_opening_time(Location $location)
		{
			$smallest = null;

			if(!is_array($location->opening_hours) || sizeof($location->opening_hours) === 0)
				return null;

			foreach($location->specials as $set){
				foreach($set->opening_hours as $hour) {
					if($hour->start > $this->now && ($hour->start < $smallest || $smallest == null))
						$smallest = $hour->start;
				}
			}

			$weeks_in_future = 0;
			$tries = 20;
			while($smallest === null && $tries >= 0){
				$sets = DateTime_Service::getInstance()->copy_sets_to_future($location->opening_hours,$weeks_in_future);

				foreach($sets as $set)
				{
					foreach($set->opening_hours as $hour)
					{
						if($this->is_vacation($location->vacations,$hour->start))
							continue;

						if($hour->start > $this->now && ($hour->start < $smallest || $smallest == null))
							$smallest = $hour->start;
					}
				}
				$tries--;
				$weeks_in_future++;
			}

			return $smallest;
		}

		public function get_next_closing_time($location)
		{
			$smallest = null;

			foreach($location->opening_hours as $set)
			{
				foreach($set->opening_hours as $hour) {
					if($hour->end > $this->now && ($hour->end < $smallest || $smallest == null))
						$smallest = $hour->end;
				}
			}

			if($smallest === null)
			{
				$new_sets = DateTime_Service::getInstance()->copy_sets_to_future($location->opening_hours,1);

				foreach($new_sets as $set){
					foreach($set->opening_hours as $hour) {
						if($hour->end > $this->now && ($hour->end < $smallest || $smallest == null))
							$smallest = $hour->end;
					}
				}
			}

			foreach($location->specials as $set){
				foreach($set->opening_hours as $hour) {
					if($hour->end > $this->now && ($hour->end < $smallest || $smallest == null))
						$smallest = $hour->end;
				}
			}

			foreach($location->vacations as $vacation) {
				if($vacation->from > $this->now && ($vacation->from < $smallest || $smallest == null))
					$smallest = $vacation->from;
			}

			return $smallest;
		}

		#endregion

		#region Private Helpers

		private function is_in_past(array $opening_hours)
		{
			foreach($opening_hours as $hour){
				if($hour->start > $this->now)
					return false;
			}
			return true;
		}

		private function now_in_between(array $opening_hours)
		{
			if(!is_array($opening_hours)) return false;

			foreach($opening_hours as $opening_hour)
			{
				if($opening_hour->after_midnight && $opening_hour->day_of_week == 7)
				{
					$new_now = DateTime_Service::getInstance()->addDays($this->now,7);
					if($opening_hour->start  <= $new_now && $new_now < $opening_hour->end)
						return true;
				}
				if($opening_hour->start  <= $this->now && $this->now < $opening_hour->end)
					return true;
			}

			return false;
		}
		#endregion

	}
}

