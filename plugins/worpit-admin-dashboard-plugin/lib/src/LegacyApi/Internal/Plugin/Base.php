<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Plugin;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

abstract class Base extends LegacyApi\Internal\Base {

	protected function getFile() :string {
		return $this->getActionParam( 'plugin_file' );
	}
}