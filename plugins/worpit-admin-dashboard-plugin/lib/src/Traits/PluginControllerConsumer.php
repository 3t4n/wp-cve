<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Traits;

use FernleafSystems\Wordpress\Plugin\iControlWP\Controller;

trait PluginControllerConsumer {

	private $con;

	/**
	 * @var \ICWP_APP_Plugin_Controller|Controller
	 */
	private $oPlugCon;

	/**
	 * @return \ICWP_APP_Plugin_Controller|Controller
	 */
	public function getCon() {
		return $this->con ?? $this->oPlugCon;
	}

	/**
	 * @param \ICWP_APP_Plugin_Controller|Controller $con
	 * @return $this
	 */
	public function setCon( $con ) {
		$this->con = $con;
		return $this;
	}
}