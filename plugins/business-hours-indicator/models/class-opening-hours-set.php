<?php

namespace MABEL_BHI_LITE\Models
{
	if(!defined('ABSPATH')){die;}

	class Opening_Hours_Set
	{

		public $opening_hours;

		public $day_of_week;

		public $day_name;

		public $day_name_short;

		public $is_today;

		public function __construct($day_of_week, $day_name, $day_name_short, array $opening_hours = [] )
		{

			$this->day_of_week = $day_of_week;
			$this->day_name = $day_name;
			$this->day_name_short = $day_name_short;
			$this->opening_hours = $opening_hours;

		}

		public function is_equal(Opening_Hours_Set $set)
		{
			if(sizeof($this->opening_hours) !== sizeof($set->opening_hours))
				return false;

			if($this->is_closed() &&  $set->is_closed())
				return true;

			for ($i = 0; $i < sizeof($this->opening_hours); $i++) {
				$eq = $this->opening_hours[$i]->is_equal($set->opening_hours[$i]);
				if(!$eq) return false;
			}

			return true;
		}

		public function is_closed()
		{
			return sizeof($this->opening_hours) === 0;
		}


	}
}