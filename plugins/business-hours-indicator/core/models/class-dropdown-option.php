<?php

namespace MABEL_BHI_LITE\Core\Models
{

	class Dropdown_Option extends Option
	{

		public $options;

		public $pre_text;

		public $post_text;

		public function __construct($id, $title, array $options, $selected_option = null,
			$extra_info = null,Option_Dependency $dependency = null, $pre_text = null, $post_text=null)
		{

			parent::__construct($id, $selected_option, $title, $extra_info, $dependency);

			$this->pre_text = $pre_text;
			$this->post_text = $post_text;
			$this->options = $options;

		}

	}

}