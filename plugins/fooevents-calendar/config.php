<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

class FooEvents_Calendar_Config {

	public $scriptsPath;
	public $stylesPath;
	public $templatePath;
	public $templatePathTheme;
	public $pluginDirectory;
	public $classPath;
	public $path;
	public $pluginFile;
    public $plugin_data;

	/**
	 * Initialize configuration variables to be used as object.
	 */
	public function __construct() {

		$this->pluginDirectory   = 'fooevents-calendar';
		$this->scriptsPath       = plugin_dir_url( __FILE__ ) . 'js/';
		$this->stylesPath        = plugin_dir_url( __FILE__ ) . 'css/';
		$this->templatePath      = plugin_dir_path( __FILE__ ) . 'templates/';
		$this->templatePathTheme = get_stylesheet_directory() . '/' . $this->pluginDirectory . '/templates/';
		$this->classPath         = plugin_dir_path( __FILE__ ) . 'classes/';
		$this->path              = plugin_dir_path( __FILE__ );
		$this->pluginFile        = $this->path . 'fooevents-calendar.php';

	}

}
