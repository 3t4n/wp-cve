<?php

namespace MABEL_BHI_LITE\Core\Models
{
	class Option
	{
		public $id;

		public $title;

		public $value;

		public $extra_info;

		public $name;

		public $dependency;

		public function __construct($id, $value, $title, $extra_info = null, Option_Dependency $dependency = null)
		{

			$this->value = $value;
			$this->title = $title;
			$this->id = $id;
			$this->extra_info = $extra_info;
			$this->dependency = $dependency;

		}
	}

}