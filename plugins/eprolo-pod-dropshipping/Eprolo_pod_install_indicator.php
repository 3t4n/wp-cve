<?php


require_once 'Eprolo_pod_options_manager.php';

class Eprolo_Pod_Install_Indicator extends Eprolo_Pod_Options_Manager {


	const  OPTIONINSTALLED = 'eprolo_pod__installed';
	const  OPTIONVERSION   = 'eprolo_pod__version';

	public function isInstalled() {
		return $this->getOption( self::OPTIONINSTALLED ) == true;
	}

	/**
	 * Note in DB that the plugin is installed
	 *
	 * @return null
	 */
	protected function markAsInstalled() {
		return $this->updateOption( self::OPTIONINSTALLED, true );
	}

	/**
	 * Note in DB that the plugin is uninstalled
	 *
	 * @return bool returned form delete_option.
	 * true implies the plugin was installed at the time of this call,
	 * false implies it was not.
	 */
	protected function markAsUnInstalled() {
		return $this->deleteOption( self::OPTIONINSTALLED );
	}


	/**
	 * Set a version string in the options.
	 * need to check if
	 *
	 * @param  $version string best practice: use a dot-delimited string like '1.2.3' so version strings can be easily
	 *                  compared using version_compare (http://php.net/manual/en/function.version-compare.php)
	 * @return null
	 */
	protected function setVersionSaved( $version ) {
		return $this->updateOption( self::OPTIONVERSION, $version );
	}

	protected function getMainPluginFileName() {
		return basename( dirname( __FILE__ ) ) . 'php';
	}

	/**
	 * Get a value for input key in the header section of main plugin file.
	 * E.g. "Plugin Name", "Version", "Description", "Text Domain", etc.
	 *
	 * @param  $key string plugin header key
	 * @return string if found, otherwise null
	 */
	public function getPluginHeaderValue( $key ) {
		$data  = file_get_contents( $this->getPluginDir() . DIRECTORY_SEPARATOR . $this->getMainPluginFileName() );
		$match = array();
		preg_match( '/' . $key . ':\s*(\S+)/', $data, $match );
		if ( count( $match ) >= 1 ) {
			return $match[1];
		}
		return null;
	}

	/**
	 * If your subclass of this class lives in a different directory,
	 * override this method with the exact same code. Since __FILE__ will
	 * be different, you will then get the right dir returned.
	 *
	 * @return string
	 */
	protected function getPluginDir() {
		return dirname( __FILE__ );
	}

	/**
	 * Version of this code.
	 * Best practice: define version strings to be easily compared using version_compare()
	 * (http://php.net/manual/en/function.version-compare.php)
	 * NOTE: You should manually make this match the SVN tag for your main plugin file 'Version' release and 'Stable tag' in readme.txt
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->getPluginHeaderValue( 'Version' );
	}



	/**
	 * This helps track was version is installed so when an upgrade is installed, it should call this when finished
	 * upgrading to record the new current version
	 *
	 * @return void
	 */
	protected function saveInstalledVersion() {
		$this->setVersionSaved( $this->getVersion() );
	}



}
