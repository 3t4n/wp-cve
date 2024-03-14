<?php

namespace MABEL_BHI_LITE
{

	if(!defined('ABSPATH')){die;}

	use MABEL_BHI_LITE\Controllers\Admin_Controller;
	use MABEL_BHI_LITE\Controllers\Shortcode_Controller;
	use MABEL_BHI_LITE\Controllers\Widgets_Controller;
	use MABEL_BHI_LITE\Core\Config_Manager;
	use MABEL_BHI_LITE\Core\Language_Manager;
	use MABEL_BHI_LITE\Core\Registry;
	use MABEL_BHI_LITE\Core\Settings_Manager;

	class Business_Hours_Indicator
	{

		/**
		 * @var Language_Manager language manager.
		 */
		protected $language_manager;

		/**
		 * Business_Hours_Indicator constructor.
		 *
		 * @param $dir string
		 * @param $url string
		 * @param $slug string
		 * @param $version string
		 */
		public function __construct($dir, $url, $plugin_base, $name, $version)
		{
			// Init meta info.
			Config_Manager::init($dir, $url, $plugin_base, $version, MABEL_BHI_LITE_SETTINGS, $name);

		}

		public function run()
		{
			// Init translations.
			$this->language_manager = new Language_Manager();

			// Init settings with defaults.
			Settings_Manager::init( [
				'openline'=> "We're open",
				'closedline'=> "Sorry, we're closed",
				'closedsoonline'=> "Closing in {x} minutes",
				'opensoonline'=> "Opening in {x} minutes",
				'includetime' => false,
				'includeday' =>false,
				'timezone'=>get_option('timezone_string'),
				'format'=>'12',
				'approximation'=>false,
				'warning'=>45,
				'warningclosing'=>45,
				'tabledisplaymode'=>'normal',
				'output'=> 'table',
				'includespecialdates' => false,
				'includevacations' => false
			] );

			// Kick off admin page.
			new Admin_Controller();

			// Register shortcodes
			new Shortcode_Controller();

			// Widgets
			new Widgets_Controller();

			// Kick off!
			Registry::get_loader()->run();
		}

	}

}