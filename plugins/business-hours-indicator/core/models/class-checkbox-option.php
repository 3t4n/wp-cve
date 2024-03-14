<?php

namespace MABEL_BHI_LITE\Core\Models
{

	class Checkbox_Option extends Option
	{

		public $checked;

		public $label;

		public function __construct( $id, $title, $label, $checked = false, $extra_info = null, Option_Dependency $dependency = null )
		{
			parent::__construct($id, $checked, $title, $extra_info, $dependency );

			$this->checked = $checked;
			$this->label = $label;
		}

	}

}