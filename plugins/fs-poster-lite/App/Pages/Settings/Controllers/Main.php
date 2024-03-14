<?php

namespace FSPoster\App\Pages\Settings\Controllers;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Request;

class Main
{
	private function load_assets ()
	{
		wp_register_script( 'fsp-settings', Pages::asset( 'Settings', 'js/fsp-settings.js' ), [
			'jquery',
			'fsp'
		], NULL );
		wp_enqueue_script( 'fsp-settings' );

		wp_enqueue_style( 'fsp-settings', Pages::asset( 'Settings', 'css/fsp-settings.css' ), [ 'fsp-ui' ], NULL );
	}

	public function index ()
	{
		$this->load_assets();

		$settings_tab = Request::get( 'setting', 'general', 'string', [
			'general',
		] );

		Pages::view( 'Settings', 'index', [
			'active_tab' => $settings_tab
		] );
	}

	public function component_general ()
	{
		wp_register_script( 'fsp-settings-general', Pages::asset( 'Settings', 'js/fsp-settings-general.js' ), [], NULL );
		wp_enqueue_script( 'fsp-settings-general' );

		Pages::view( 'Settings', 'Components/general' );
	}
}
