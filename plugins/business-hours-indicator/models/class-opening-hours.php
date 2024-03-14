<?php

namespace MABEL_BHI_LITE\Models
{
	if(!defined('ABSPATH')){die;}

	use DateTime;

	class Opening_Hours
	{

		public $start;

		public $end;

		public $after_midnight;

		public $start_time;

		public $end_time;

		public $day_of_week;


		public function __construct(DateTime $start = null, DateTime $end = null) {
			$this->start = $start;
			$this->end = $end;

			if($start != null & $end != null){

				$this->day_of_week = (int)$start->format('N');
				$this->start_time = $this->start->format('g:i A');
				$this->end_time = $this->end->format('g:i A');

			}
		}

		public function is_equal(Opening_Hours $opening_hours, $including_day = false)
		{

			if($including_day && $this->day_of_week != $opening_hours->day_of_week) return false;

			return $this->start_time === $opening_hours->start_time && $this->end_time === $opening_hours->end_time;

		}

		public function to_string($format = 'g:i A', $separator = ' &mdash; ')
		{

			return sprintf('%s%s%s',
				$this->start->format($format),
				$separator,
				$this->end->format($format)
			);
		}
	}

}