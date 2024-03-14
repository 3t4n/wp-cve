<?php

namespace MABEL_BHI_LITE\Core
{

	use MABEL_BHI_LITE\Core\Models\Checkbox_Option;
	use MABEL_BHI_LITE\Core\Models\Custom_Option;
	use MABEL_BHI_LITE\Core\Models\Dropdown_Option;
	use MABEL_BHI_LITE\Core\Models\Option;
	use MABEL_BHI_LITE\Core\Models\Option_Dependency;
	use MABEL_BHI_LITE\Core\Models\Option_Section;
	use Exception;
	use MABEL_BHI_LITE\Core\Models\Text_Option;

	class Options_Manager extends Abstract_Options_Manager
	{
		private $sections;

		public function __construct()
		{
			$this->sections = [];
		}

		#region Option Adding
		public function add_checkbox_option($section_id, $field_id, $field_title, $label, $checked, $extra_info = null, Option_Dependency $dependency = null)
		{
			$section = $this->get_section($section_id);

			if($section == null)
				throw new Exception("Couldn't add option to section. Section doesn't exist");

			$option = new Checkbox_Option($field_id, $field_title,$label,$checked, $extra_info, $dependency);
			$option->name = $this->set_option_name($option);
			$section->add_option($option);
		}

		public function add_text_option($section_id, $field_id,$field_title, $value, $placeholder = null, $extra_info = null,Option_Dependency $dependency = null)
		{
			$section = $this->get_section($section_id);

			if($section == null)
				throw new Exception("Couldn't add option to section. Section doesn't exist");

			$option = new Text_Option($field_id, $field_title, $value,$placeholder,$extra_info,$dependency);
			$option->name = $this->set_option_name($option);
			$section->add_option($option);
		}

		public function add_dropdown_option($section_id, $field_id, $field_title, array $options,
			$selected_value = null, $extra_info = null, Option_Dependency $dependency = null,$pre_text = null, $post_text = null)
		{
			$section = $this->get_section($section_id);

			if($section == null)
				throw new Exception("Couldn't add option to section. Section doesn't exist");

			$option = new Dropdown_Option($field_id, $field_title, $options,
				$selected_value,$extra_info, $dependency, $pre_text, $post_text);
			$option->name = $this->set_option_name($option);
			$section->add_option($option);
		}

		public function add_custom_option($section_id, $field_title, $template, array $data)
		{
			$section = $this->get_section($section_id);

			if($section == null)
				throw new Exception("Couldn't add option to section. Section doesn't exist");

			$option = new Custom_Option($field_title,$template,$data);
			$section->add_option($option);
		}
		#endregion

		#region Section Stuff
		public function add_section($id, $title, $icon, $active = false)
		{
			$this->sections[] = new Option_Section( $id, $title, $icon, $active );
		}

		private function get_section($section_id)
		{
			foreach($this->sections as $section)
			{
				if($section_id === $section->id)
					return $section;
			}

			return null;
		}

		public function get_sections()
		{
			return $this->sections;
		}
		#endregion

		private function set_option_name(Option $o)
		{
			return Config_Manager::$settings_key . '[' . $o->id . ']';
		}
	}
}