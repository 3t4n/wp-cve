<?php

namespace MABEL_BHI_LITE\API
{
	if(!defined('ABSPATH')) exit;

	use Exception;
	use MABEL_BHI_LITE\Core\Linq\Enumerable;
	use MABEL_BHI_LITE\Core\Settings_Manager;
	use MABEL_BHI_LITE\Models\Opening_Hours_Set;
	use MABEL_BHI_LITE\Models\Special_Date;
	use MABEL_BHI_LITE\Models\Vacation;
	use MABEL_BHI_LITE\Services\Conversion_Service;
	use MABEL_BHI_LITE\Services\Opening_Hours_Service;

	/**
	 * Class Api Easily accessible functions to be used by theme/plugin developers.
	 */
	class API
	{
		/**
		 * @var array location_name => Location. Location cache.
		 */
		private $location_cache = [];

		private static $instance = null;

		public static function instance()
		{
			if (self::$instance === null)
				self::$instance = new API();
			return self::$instance;
		}

		public function get_specials($location_name)
		{
			if(empty($location_name) ||!is_string($location_name))
				throw new Exception("Invalid location name.");

			$location = $this->get_location($location_name);

			if($location === null)
				throw new Exception("No location found with name '".$location_name."'");

			return Enumerable::from($location->specials)->select(function($x){
				/** @var Special_Date $x */
				$hours = Enumerable::from($x->opening_hours)->select(function($y){
					return (object) [
						'start' => $y->start,
						'end' => $y->end
					];
				})->toArray();

				return (object) [
					'date' => $x->date,
					'hours' => $hours,
					'is_closed' => $x->is_closed()
				];
			})->toArray();
		}

		public function get_vacations($location_name)
		{
			if(empty($location_name) ||!is_string($location_name))
				throw new Exception("Invalid location name.");

			$location = $this->get_location($location_name);

			if($location === null)
				throw new Exception("No location found with name '".$location_name."'");

			return Enumerable::from($location->vacations)->select(function($x){
				/** @var Vacation $x */
				return (object) [
					'from' => $x->from,
					'to' => $x->to
				];
			})->toArray();
		}

		/**
		 * @param $location_name string location name
		 * @return array
		 */
		public function get_opening_hours($location_name)
		{
			if(empty($location_name) ||!is_string($location_name))
				throw new Exception("Invalid location name.");

			$location = $this->get_location($location_name);

			if($location === null)
				throw new Exception("No location found with name '".$location_name."'");

			return Enumerable::from($location->opening_hours)->select(function($x){
				/** @var Opening_Hours_Set $x */
				$hours = Enumerable::from($x->opening_hours)->select(function($y){
					return (object) [
						'start' => $y->start,
						'end' => $y->end
					];
				})->toArray();
				return (object) [
					'day' => $x->day_name,
					'hours' => $hours,
					'is_closed' => $x->is_closed()
				];
			})->toArray();
		}

		/**
		 * @param $location_name string location name
		 * @return bool
		 */
		public function is_open($location_name)
		{
			if(empty($location_name) ||!is_string($location_name))
				throw new Exception("Invalid location name.");

			$location = $this->get_location($location_name);

			if($location === null)
				throw new Exception("No location found with name '".$location_name."'");

			return Opening_Hours_Service::instance()->is_open($location);
		}

		public function is_closed($location_name)
		{
			$is_open = $this->is_open($location_name);
			return !$is_open;
		}

		#region Private helpers
		private function get_location($name)
		{
			if(array_key_exists($name,$this->location_cache))
				return $this->location_cache[$name];

			$location = Conversion_Service::convert_to_location(
				Settings_Manager::get_setting('locations'),
				$name
			);

			$this->location_cache[$name] = $location;

			return $location;
		}
		#endregion
	}
}