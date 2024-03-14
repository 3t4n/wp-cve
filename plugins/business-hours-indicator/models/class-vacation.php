<?php

namespace MABEL_BHI_LITE\Models
{
	if(!defined('ABSPATH')){die;}

	use DateTime;

	class Vacation
	{

		public $from;

		public $to;

		public $spans_today;

		public function __construct(DateTime $from, DateTime $to) {
			$this->from = $from;
			$this->to = $to;
		}

		public function to_string($separator = '&mdash;')
		{
			return sprintf(
				'%s %s %s',
				$this->from->format('j'). ' '.$this->from->format('M'),
				$separator,
				$this->to->format('j').' '. $this->to->format('M')
			);
		}
	}

}