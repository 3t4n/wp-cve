<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

use WPRuby_CAA\Core\Constants;
use WPRuby_CAA\Core\Dto\Settings;

class Get_Settings_Endpoint extends Abstract_Endpoint {


	public function callback( $data )
	{

		$settings = get_option(Constants::CAA_SETTINGS, []);

		$settings = wp_parse_args($settings, Settings::default_settings());

		$this->output(['settings' => $settings]);
	}

	public function action()
	{
		return 'caa_get_settings';
	}
}
