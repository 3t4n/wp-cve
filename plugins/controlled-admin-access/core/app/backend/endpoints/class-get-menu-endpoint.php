<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;

use WPRuby_CAA\Core\Constants;

class Get_Menu_Endpoint extends Abstract_Endpoint {

	public function callback( $data ) {
		$main_menu    = get_option( Constants::CAA_ALL_MENU_SLUGS );

		$this->output( [
			'main_menu'    => $main_menu,
		] );
	}

	public function action() {
		return 'caa_get_menu';
	}
}
