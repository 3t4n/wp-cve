<?php
/**
*
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
*
*/
class CJTInstallerAccessPoint extends CJTAccessPoint {

	/**
	* put your comment there...
	*
	* @var mixed
	*/
	protected $stopNotices = false;

	/**
	* put your comment there...
	*
	*/
	public function __construct() {
		// Initialize parent.
		parent::__construct();
		// Set name!
		$this->name = 'installer';
	}
	/**
	* put your comment there...
	*
	*/
	protected function doListen() {
		// If not installed and not in manage page display admin notice!
		if (!CJTPlugin::getInstance()->isInstalled() && $this->hasAccess()) {
			add_action('admin_notices', array(&$this, 'notInstalledAdminNotice'));
		}

		//if ( class_exists( 'CJTPlus' ) && $this->isOldCJTPlus()) add_action( 'admin_notices', [ $this, '_oldCJTPlusDetected' ] );
	}

	protected function isOldCJTPlus()
	{
		if ( ! function_exists( 'get_plugin_data' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$reflector = new \ReflectionClass( 'CJTPlus' );
		$CJTPlusVersion = get_plugin_data( str_replace( 'plus.class', 'plus', $reflector->getFileName() ) )['Version'];

		if ( version_compare( $CJTPlusVersion, '8.4', '<=' ) ) return true;
		return false;
	}

	public function _oldCJTPlusDetected()
	{
		$class = 'notice notice-error';
		$message = __( 'You are currently using CJT PLUS version 8.4 or under. Unfortunately, this version is not optimized for the new Gutenberg Editor.
		<br>
		<br>
		Please make sure you have added your license key and update CJT PLUS with the latest version. If your license has expired, please click to: <a target="_blank" href="https://css-javascript-toolbox.com/pricing">purchase a new subscription</a>.
		<br>
		If you wish to continue using your current version, it may be best to roll back to: <a target="_blank" href="https://downloads.wordpress.org/plugin/css-javascript-toolbox.8.4.2.zip">CJT Free - version 8.4.2</a>' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_html( $class ), $message );
	}

	/**
	* put your comment there...
	*
	*/
	public function installationPage() {
		if ($this->hasAccess()) {
			// Set as connected object!
			$this->connected();
			// Set controller internal parameters.
			return $this->route(null, array('view' => 'installer/install'))
			// Set Action
			->setAction('install');
		}
	}

	/**
	* put your comment there...
	*
	*/
	public function notInstalledAdminNotice() {
		// Show Not installed admin notice only
		// if there is no access point processed/connected the request
		if (!$this->stopNotices)	{
			// Set MVC request parameters.
			$this->route(null , array('view' => 'installer/notice'))
			// Set action name.
			->setAction('notInstalledNotice')
			// Fire action!
			->_doAction();
		}
	}

	/**
	* put your comment there...
	*
	*/
	public function stopNotices()	{
		// Do not show admin notcies!
		$this->stopNotices = true;
	}

} // End class.

// Hookable!
CJTInstallerAccessPoint::define('CJTInstallerAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));
