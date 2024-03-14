<?php

namespace FSPoster\App\Providers;

use FSPoster\App\Pages\Share\Controllers\Popup as SharePopup;
use FSPoster\App\Pages\Accounts\Controllers\Popup as AccountsPopup;

class Popups
{
	use AccountsPopup, SharePopup;

	public function __construct ()
	{
		$methods = get_class_methods( $this );

		foreach ( $methods as $method )
		{
			if ( $method === '__construct' )
			{
				continue;
			}

			add_action( 'wp_ajax_popup_' . $method, function () use ( $method ) {
				define( 'FSPL_MODAL', TRUE );
				$this->$method();
				exit();
			} );
		}
	}
}
