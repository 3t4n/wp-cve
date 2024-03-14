<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Autoupdate;

class Plugin extends Base {

	protected function getItem() :string {
		return $this->getActionParam( 'plugin' );
	}

	protected function getContext() :string {
		return 'plugins';
	}
}
