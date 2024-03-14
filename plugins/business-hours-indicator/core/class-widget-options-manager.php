<?php

namespace MABEL_BHI_LITE\Core
{

	use MABEL_BHI_LITE\Core\Models\Dropdown_Option;
	use MABEL_BHI_LITE\Core\Models\Option;
	use MABEL_BHI_LITE\Core\Models\Option_Dependency;
	use MABEL_BHI_LITE\Core\Models\Text_Option;

	class Widget_Options_Manager extends Abstract_Options_Manager
	{
		public $options;

		public function __construct()
		{
			$this->options = [];
		}

		public function add_text_option($field_id,$field_title, $value, $placeholder = null, $extra_info = null,Option_Dependency $dependency = null)
		{
			$option = new Text_Option($field_id, $field_title, $value,$placeholder,$extra_info,$dependency);
			$this->options[] = $option;
		}

		public function add_textarea_option($field_id,$field_title, $value, $placeholder = null, $extra_info = null,Option_Dependency $dependency = null)
		{
			$option = new Text_Option($field_id, $field_title, $value,$placeholder,$extra_info,$dependency);
			$option->is_textarea = true;
			$this->options[] = $option;
		}

		public function add_dropdown_option($field_id, $field_title, array $options,
			$selected_value = null, $extra_info = null, Option_Dependency $dependency = null,$pre_text = null, $post_text = null)
		{
			$option = new Dropdown_Option($field_id, $field_title, $options,
				$selected_value,$extra_info, $dependency, $pre_text, $post_text);
			$this->options[] = $option;
		}
	}
}