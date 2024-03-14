<?php

namespace MABEL_BHI_LITE\Models {

	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}

	class List_VM_Entry
	{
		public $range;

		public $hours;

		public $current;

		public function is_equal(List_VM_Entry $other_entry)
		{
			return $this->range === $other_entry->range;
		}
	}

	class List_VM
	{
		public $normal_entries;

		public $vacation_entries;

		public $special_entries;

		public $slug;

		public $show_as_table;

		public $show_location_error;

		public $show_specials;

		public $show_vacations;

		public $consolidate;

		public $entries;

		public $has_current;

		public function __construct()
		{
			$this->show_location_error = false;
			$this->entries = [];
			$this->has_current = 1;
		}

	}
}