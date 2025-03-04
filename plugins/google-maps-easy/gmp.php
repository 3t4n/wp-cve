<?php
/**
 * Plugin Name: Easy Google Maps
 * Plugin URI: https://supsystic.com/plugins/google-maps-plugin/
 * Description: The easiest way to create Google Map with markers or locations. Display any data on the map: text, images, videos. Custom map marker icons
 * Version: 1.11.12
 * Author: supsystic.com
 * Author URI: https://supsystic.com
 * Text Domain: google-maps-easy
 * Domain Path: /languages
 **/

	/**
	 * Base config constants and functions
	 */
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'config.php');
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'functions.php');
	/**
	 * Connect all required core classes
	 */
    importClassGmp('dbGmp');
    importClassGmp('installerGmp');
    importClassGmp('baseObjectGmp');
    importClassGmp('moduleGmp');
    importClassGmp('modelGmp');
    importClassGmp('viewGmp');
    importClassGmp('controllerGmp');
    importClassGmp('helperGmp');
    importClassGmp('dispatcherGmp');
    importClassGmp('fieldGmp');
    importClassGmp('tableGmp');
    importClassGmp('frameGmp');
	/**
	 * @deprecated since version 1.0.1
	 */
    importClassGmp('langGmp');
    importClassGmp('reqGmp');
    importClassGmp('uriGmp');
    importClassGmp('htmlGmp');
    importClassGmp('responseGmp');
    importClassGmp('fieldAdapterGmp');
    importClassGmp('validatorGmp');
    importClassGmp('errorsGmp');
    importClassGmp('utilsGmp');
    importClassGmp('modInstallerGmp');
  	importClassGmp('installerDbUpdaterGmp');
  	importClassGmp('dateGmp');
	/**
	 * Check plugin version - maybe we need to update database, and check global errors in request
	 */
    installerGmp::update();
    errorsGmp::init();
    /**
	 * Start application
	 */
    frameGmp::_()->parseRoute();
    frameGmp::_()->init();
    frameGmp::_()->exec();
    installerGmp::updateIcon();

	//var_dump(frameGmp::_()->getActivationErrors()); exit();
