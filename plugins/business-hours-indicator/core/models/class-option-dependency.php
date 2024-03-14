<?php

namespace MABEL_BHI_LITE\Core\Models {

	class Option_Dependency
	{
		public $element_id;

		public $value;

		public $not_empty;

		public function __construct($element_id, $value, $not_empty = false)
		{
			$this->element_id = $element_id;
			$this->value = $value;
			$this->not_empty = $not_empty;
		}
	}
}