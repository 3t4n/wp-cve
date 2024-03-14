<?php

abstract class ICWP_APP_Processor_BaseApp extends ICWP_APP_Processor_Base {

	protected function getRequestParams() :\FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\RequestParameters {
		return $this->getFeatureOptions()->getRequestParams();
	}
}