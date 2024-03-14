<?php
namespace shellpress\v1_4_0\demo\src\Components;

use shellpress\v1_4_0\src\Shared\Components\IUniversalFrontComponentEDDLicenser;

/**
 * @author jakubkuranda@gmail.com
 * Date: 16.09.2019
 * Time: 11:34
 */
class EddLicenseManagerExample2 extends IUniversalFrontComponentEDDLicenser {


	/**
	 * Called on basic set up, just before everything else.
	 *
	 * @return void
	 */
	public function onSetUpComponent() {

		$this->setApiUrl( 'https://new.themastercut.co' );
		$this->setProductId( '1344' );
		$this->enableSoftwareUpdates();

	}

}