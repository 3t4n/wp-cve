<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Autoupdate;

class Theme extends Base {

	protected function getItem() :string {
		return $this->getActionParam( 'theme' );
	}

	protected function getContext() :string {
		return 'themes';
	}
}
