<?php

namespace InspireLabs\WoocommerceInpost;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Base plugin class for Inspire Labs plugins
 *
 * @author Krzysiek
 *
 */
abstract class inspire_Plugin4 {

	const VERSION = '4.0';

	protected $_pluginNamespace = "";
	protected $_pluginPath;
	protected $_templatePath;
	protected $_pluginFilePath;
	protected $_pluginUrl;

    /**
     * @var array
     */
    protected $_defaultViewArgs;

	/**
	 * @var string
	 */
	private $_pluginFullPath;

	/**
	 * @var string
	 */
	private $_templatesFullPath;

	/**
	 * @var string
	 */
	private $_pluginJs;

	/**
	 * @var string
	 */
	private $_pluginCss;

	/**
	 * @var string
	 */
	private $_pluginImages;

	public function __construct() {
		$this->initBaseVariables();
	}


	/**
	 *
	 */
	public function initBaseVariables() {

		// Set Plugin Path
		$this->_pluginPath     = dirname( WOOCOMMERCE_INPOST_PLUGIN_FILE );
		$this->_pluginFullPath = plugin_dir_path( WOOCOMMERCE_INPOST_PLUGIN_FILE );

		// Set Plugin URL
		$this->_pluginUrl    = plugin_dir_url( WOOCOMMERCE_INPOST_PLUGIN_FILE );
		$assets              = $this->_pluginUrl . 'resources/assets/';
		$this->_pluginJs     = $assets . 'js/';
		$this->_pluginCss    = $assets . 'css/';
		$this->_pluginImages = $assets . 'images/';

		$this->_pluginFilePath = basename( WOOCOMMERCE_INPOST_PLUGIN_FILE );

		$this->_templatePath      = '/resources/templates';
		$this->_templatesFullPath =
			$this->_pluginFullPath .
			DIRECTORY_SEPARATOR .
			'resources' .
			DIRECTORY_SEPARATOR .
			'templates'
			. DIRECTORY_SEPARATOR;

		$this->_defaultViewArgs = [
			'pluginUrl' => $this->getPluginUrl(),
		];

	}


	/**
	 *
	 * @return string
	 */
	public function getPluginUrl() {
		return esc_url( trailingslashit( $this->_pluginUrl ) );
	}

	/**
	 * @return string
	 */
	public function getTemplatePath() {
		return trailingslashit( $this->_templatePath );
	}

	public function getPluginFilePath() {
		return $this->_pluginFilePath;
	}

	/**
	 * @return string
	 */
	public function getPluginFullPath() {
		return $this->_pluginFullPath;
	}

	/**
	 * @return string
	 */
	public function getTemplatesFullPath() {
		return $this->_templatesFullPath;
	}

	/**
	 * @return string
	 */
	public function getPluginJs() {
		return $this->_pluginJs;
	}

	/**
	 * @return string
	 */
	public function getPluginCss() {
		return $this->_pluginCss;
	}

	/**
	 * @return string
	 */
	public function getPluginImages() {
		return $this->_pluginImages;
	}

}

