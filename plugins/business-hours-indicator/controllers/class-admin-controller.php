<?php

namespace MABEL_BHI_LITE\Controllers
{

	use MABEL_BHI_LITE\Core\Admin;
	use DateTimeZone;
	use MABEL_BHI_LITE\Core\Config_Manager;
	use MABEL_BHI_LITE\Core\Models\Option_Dependency;
	use MABEL_BHI_LITE\Core\Options_Manager;
	use MABEL_BHI_LITE\Core\Settings_Manager;

	if(!defined('ABSPATH')){die;}

	class Admin_Controller extends Admin
	{
		private $slug;
		public function __construct()
		{
			parent::__construct(new Options_Manager());
			$this->slug = Config_Manager::$slug;

			$this->add_script_dependencies( ['underscore'] );

			$this->add_script_variable('clamp', 3);

			$this->init_admin_page();

			$this->add_ajax_function('mb-bhi-update-indicator',$this,'update_indicator',false,true);
			$this->add_ajax_function('mb-bhi-update-list',$this,'update_list',false, true);
		}

		public function update_list()
		{
			echo do_shortcode('[mbhi_hours]');
			wp_die();
		}

		public function update_indicator()
		{
			echo do_shortcode('[mbhi]');
			wp_die();
		}

		private function init_admin_page()
		{
			$this->options_manager->add_section('general', __('General','business-hours-indicator'), 'admin-tools', true);
			$this->options_manager->add_section('hours', __('Hours','business-hours-indicator'), 'clock');
			$this->options_manager->add_section('indicator', __('Indicator','business-hours-indicator'), 'arrow-down-alt');
			$this->options_manager->add_section('table', __('Table','business-hours-indicator'), 'editor-table');
			$this->options_manager->add_section('codes', __('Codes','business-hours-indicator'),'editor-code');

			$timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
			$locations = Settings_Manager::get_setting('locations');

			$this->options_manager->add_dropdown_option(
				'general',
				'timezone',
				__('Time zone','business-hours-indicator'),
				array_combine($timezones,$timezones),
				Settings_Manager::get_setting('timezone')
			);

			$this->options_manager->add_dropdown_option(
				'general',
				'format',
				__('Time format','business-hours-indicator'),
				[ 12 => __('12-hour format','business-hours-indicator'), 24 => __('24-hour format','business-hours-indicator') ],
				Settings_Manager::get_setting('format')
			);

			$this->options_manager->add_custom_option(
				'hours',
				__('Locations','business-hours-indicator'),
				'admin/views/locations.php',
				[ 'locations' => empty( $locations ) ? [] : json_decode( $locations ) ]
			);

			$this->options_manager->add_text_option(
				'indicator',
				'openline',
				__('Now open message','business-hours-indicator'),
				Settings_Manager::get_translated_setting('openline'),
				null,
				__('Some HTML is allowed.','business-hours-indicator')
			);

			$this->options_manager->add_text_option(
				'indicator',
				'closedline',
				__('Now closed message', 'business-hours-indicator'),
				Settings_Manager::get_translated_setting('closedline'),
				null,
				__('Some HTML is allowed.','business-hours-indicator')
			);

			$this->options_manager->add_checkbox_option(
				'indicator',
				'includetime',
				__('Include time', 'business-hours-indicator'),
				__('Include the current time in the output.', 'business-hours-indicator'),
				Settings_Manager::get_setting('includetime')
			);

			$this->options_manager->add_checkbox_option(
				'indicator',
				'includeday',
				__('Include day', 'business-hours-indicator'),
				__('Include the current day in the output.','business-hours-indicator'),
				Settings_Manager::get_setting('includeday')
			);

			$this->options_manager->add_checkbox_option(
				'indicator',
				'approximation',
				__('Opening/closing soon warning', 'business-hours-indicator'),
				__('When it\'s near opening or closing time, show a different message.', 'business-hours-indicator'),
				Settings_Manager::get_setting('approximation')
			);

			$this->options_manager->add_text_option(
				'indicator',
				'opensoonline',
				__('Opening soon message','business-hours-indicator'),
				Settings_Manager::get_translated_setting('opensoonline'),
				null,
				__('Some HTML is allowed. Use {x} to denote minutes.','business-hours-indicator'),
				new Option_Dependency('approximation','true')
			);

			$this->options_manager->add_text_option(
				'indicator',
				'closedsoonline',
				__('Closing soon message', 'business-hours-indicator'),
				Settings_Manager::get_translated_setting('closedsoonline'),
				null,
				__('Some HTML is allowed. Use {x} to denote minutes.','business-hours-indicator'),
				new Option_Dependency('approximation','true')
			);

			$this->options_manager->add_dropdown_option(
				'indicator',
				'warning',
				__('Opening soon warning', 'business-hours-indicator'),
				[15 => 15, 30 => 30, 45 => 45],
				Settings_Manager::get_setting('warning'),
				null,
				new Option_Dependency('approximation', 'true'),
				__("Show 'opening soon' warning ", 'business-hours-indicator'),
				__('minutes in advance.', 'business-hours-indicator')
			);

			$this->options_manager->add_dropdown_option(
				'indicator',
				'warningclosing',
				__('Closing soon warning', 'business-hours-indicator'),
				[ 15 => 15, 30 => 30, 45 => 45 ],
				Settings_Manager::get_setting('warningclosing'),
				null,
				new Option_Dependency('approximation', 'true'),
				__("Show 'closing soon' warning ", 'business-hours-indicator'),
				__('minutes in advance.', 'business-hours-indicator')
			);

			$this->options_manager->add_dropdown_option(
				'table',
				'tabledisplaymode',
				__('Display mode', 'business-hours-indicator'),
				[ 0 => __('Normal', 'business-hours-indicator') , 1 => __('Consolidated', 'business-hours-indicator') ],
				Settings_Manager::get_setting('tabledisplaymode')
			);

			$this->options_manager->add_dropdown_option(
				'table',
				'output',
				__('Output', 'business-hours-indicator'),
				[ 1 => __('Table','business-hours-indicator') , 2 => __('Inline','business-hours-indicator') ],
				Settings_Manager::get_setting('output')
			);

			$this->options_manager->add_checkbox_option(
				'table',
				'includespecialdates',
				__('Include holidays', 'business-hours-indicator'),
				__('Add holidays to the hours table.', 'business-hours-indicator'),
				Settings_Manager::get_setting('includespecialdates')
			);

			$this->options_manager->add_checkbox_option(
				'table',
				'includevacations',
				__('Include vacations', 'business-hours-indicator'),
				__('Add vacations to the hours table.', 'business-hours-indicator'),
				Settings_Manager::get_setting('includevacations')
			);

			$this->loader->add_action(Config_Manager::$slug . '-add-section-content-codes',$this,'codes_content');

			$this->loader->add_action(Config_Manager::$slug . '-add-content', $this , 'underscore_templates');

			$this->loader->add_action(Config_Manager::$slug . '-render-sidebar', $this,'render_main_sidebar');

			$this->loader->add_action(Config_Manager::$slug . '-render-sidebar-indicator', $this,'render_indicator_sidebar');

			$this->loader->add_action(Config_Manager::$slug . '-render-sidebar-table', $this,'render_list_sidebar');

		}

		public function render_list_sidebar()
		{
			include Config_Manager::$dir . 'admin/views/sidebar-list.php';
		}

		public function render_indicator_sidebar()
		{
			include Config_Manager::$dir . 'admin/views/sidebar-indicator.php';
		}

		public function render_main_sidebar()
		{
			include Config_Manager::$dir . 'admin/views/sidebar-main.php';
		}

		public function indicator_section()
		{
			include Config_Manager::$dir . 'admin/views/indicator-section.php';
		}

		public function underscore_templates()
		{
			include Config_Manager::$dir . 'admin/views/underscore-templates.php';
		}

		public function codes_content()
		{
			include Config_Manager::$dir . 'admin/views/codes.php';
		}

	}
}