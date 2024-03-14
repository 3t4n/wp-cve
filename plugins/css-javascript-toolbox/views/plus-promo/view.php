<?php
/**
*
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
*
*/
class CJTPlusPromoView extends CJTView {
	/**
	* put your comment there...
	*
	* @var mixed
	*/
	protected $component;

	/**
	* put your comment there...
	*
	* @var mixed
	*/
	protected $securityToken;

	/**
	* put your comment there...
	*
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Set vars!
		$this->securityToken = cssJSToolbox::getSecurityToken();
		$this->component = $this->getRequestParameter('component');

		// Display view.
		echo $this->getTemplate($tpl);
	}

	/**
	* put your comment there...
	*
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery',
			'thickbox',
			'framework:js:ajax:{CJT-}cjt-server',
		);
	}

	/**
	* Output CSS files required to Add-New-Block view.
	*
	* @return void
	*/
	public static function enququeStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'thickbox',
		);
	}

} // End class.