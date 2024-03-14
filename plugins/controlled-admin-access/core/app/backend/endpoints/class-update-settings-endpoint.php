<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

use WPRuby_CAA\Core\Constants;

class Update_Settings_Endpoint extends Abstract_Endpoint {


	public function callback( $data )
	{
		$settings = array_map(function($setting) {
			return sanitize_text_field($setting);
		}, $data['settings']);

		update_option(Constants::CAA_SETTINGS, $settings);

		$this->ok();
	}

	public function action()
	{
		return 'caa_update_settings';
	}
}
