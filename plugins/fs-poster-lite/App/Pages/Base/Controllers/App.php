<?php

namespace FSPoster\App\Pages\Base\Controllers;

use FSPoster\App\Providers\Pages;

class App
{
	public function install ()
	{
		wp_register_script( 'fsp-install', Pages::asset( 'Base', 'js/fsp-install.js' ), [ 'jquery', 'fsp' ], NULL );
		wp_enqueue_script( 'fsp-install' );

		Pages::view( 'Base', 'verify' );
	}
}