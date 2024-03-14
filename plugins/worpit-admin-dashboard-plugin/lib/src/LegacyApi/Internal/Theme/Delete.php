<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Theme;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\ApiResponse;

class Delete extends Base {

	public function process() :ApiResponse {
		$stylesheet = $this->getFile();

		if ( empty( $stylesheet ) ) {
			return $this->fail( 'Stylesheet provided was empty.' );
		}

		$WPT = $this->loadWpFunctionsThemes();
		if ( !$WPT->getExists( $stylesheet ) ) {
			return $this->fail( sprintf( 'Theme does not exist with Stylesheet: %s', $stylesheet ) );
		}

		$toDelete = $WPT->getTheme( $stylesheet );
		if ( $toDelete->get_stylesheet_directory() == get_stylesheet_directory() ) {
			return $this->fail( sprintf( 'Cannot uninstall the currently active WordPress theme: %s', $stylesheet ) );
		}

		return $this->success( [
			'result'           => $WPT->delete( $stylesheet ),
			'wordpress-themes' => $this->getWpCollector()->collectWordpressThemes( null, true ),
			//Need to send back all themes, so we can update the one that got deleted
		] );
	}
}