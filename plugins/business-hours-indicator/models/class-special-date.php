<?php


namespace MABEL_BHI_LITE\Models
{
	if(!defined('ABSPATH')){die;}

	use DateTime;

	class Special_Date
	{

		public $date;

		public $opening_hours;

		public $is_today;

		public function __construct(DateTime $date)
		{
			$this->date = $date;
			$this->opening_hours = [];
		}

		public function is_closed()
		{
			return sizeof($this->opening_hours) === 0;
		}

		public function to_string()
		{
			return sprintf('%s %s',
				$this->date->format('j'),
				$this->date->format('M')
			);
		}
	}

}