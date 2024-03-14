<?php

namespace MABEL_BHI_LITE\Core\Models
{
	class Option_Section
	{
		public $title;

		public $icon;

		public $id;

		public $active;

		private $options;

		public function __construct($id, $title, $icon, $active = false)
		{
			$this->options = [];
			$this->id = $id;
			$this->title = $title;
			$this->icon = $icon;
			$this->active = $active;
		}

		public function add_option(Option $option)
		{
			$this->options[] = $option;
		}

		public function get_options()
		{
			return $this->options;
		}

		public function has_options()
		{
			return count($this->options) > 0;
		}
	}
}