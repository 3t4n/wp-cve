<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

abstract class Editor_Widgets_Assets_Base {

	protected  $dirUrl;
	protected  $dirPath;
	protected  $packageVersion;
	protected  $pluginMode;
	protected  $prefix;

	function __construct() {
		$this->prefix = $this->getPrefix();
		$this->dirUrl = $this->getDirUrl();
		$this->dirPath = $this->getDirPath();
		$this->packageVersion = $this->getPackageVersion();
		$this->pluginMode = $this->getPluginMode();

		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'editor_widgets_scripts' ], 10, 1 );

	}

	public function getPrefix(){}

	public function getDirPath(){}

	public function getDirUrl(){}

	public function getPackageVersion(){}

	public function getPluginMode(){}

	public  function editor_widgets_scripts() {

		if( $this->pluginMode == 'DEV' ) {
			// This is work on DEV mode
			$this->getEditModeWidgetsCSSFiles();
		} else {
			// This is work on PRODUCTION mode
			$this->getEditModeWidgetsCSS();
		}
	}

	public  function getEditModeWidgetsCSSFiles() {

		if( \Enteraddons\Classes\Helper::is_elementor_edit_mode() ) {
			$dir = $this->dirPath."assets".DIRECTORY_SEPARATOR."widgets-css".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR;
			$files = array_diff( scandir( $dir ), array( '.', '..' ) );
			if( is_array( $files ) && !empty( $files ) )  {
				foreach( $files as $file ) {
					if( file_exists($dir.$file) ) {
						wp_enqueue_style( 'widget-'.esc_attr(uniqid()), $this->dirUrl.'assets/widgets-css/css/'.$file, array(), $this->packageVersion, false );
					}
				}
			}
		}
	}

	public  function getEditModeWidgetsCSS() {
		if( \Enteraddons\Classes\Helper::is_elementor_edit_mode() ) {
			wp_enqueue_style( $this->prefix.'edit-mode-widget', $this->dirUrl.'assets/css/'.$this->prefix.'edit-mode-widgets.css', array(), $this->packageVersion, false );
		}
	}

}
