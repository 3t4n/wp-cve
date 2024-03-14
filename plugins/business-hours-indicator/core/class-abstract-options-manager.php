<?php

namespace MABEL_BHI_LITE\Core
{
	use MABEL_BHI_LITE\Core\Models\Checkbox_Option;
	use MABEL_BHI_LITE\Core\Models\Custom_Option;
	use MABEL_BHI_LITE\Core\Models\Dropdown_Option;
	use MABEL_BHI_LITE\Core\Models\Text_Option;

	abstract class Abstract_Options_Manager
	{
		public function display_field(array $args)
		{
			if(!isset($args) || !isset($args['option']))
				return false;

			$option = $args['option'];

			$field_dir = Config_Manager::$dir . 'core/templates/fields/';

			if($option instanceof Checkbox_Option) {
				return require($field_dir . 'checkbox.php');
			}

			if($option instanceof Dropdown_Option) {
				return require($field_dir . 'dropdown.php');
			}

			if($option instanceof Text_Option) {
				return require ($field_dir . 'textbox.php');
			}

			if($option instanceof Custom_Option){
				$data = $option->data;
				return  require(Config_Manager::$dir . $option->template);
			}

			return false;
		}
	}
}