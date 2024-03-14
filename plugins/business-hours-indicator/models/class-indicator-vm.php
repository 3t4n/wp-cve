<?php

namespace MABEL_BHI_LITE\Models {

	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

	class Indicator_VM
	{
		public $slug;

		public $show_location_error;

		public $include_time;

		public $include_day;

		public $time;

		public $indicator_text;

		public $open;

		public $today;

		public function __construct()
		{
			$this->show_location_error = false;
		}
	}
}