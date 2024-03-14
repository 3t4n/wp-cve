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

class Editor_Widgets_Assets extends Editor_Widgets_Assets_Base {

	public function getPrefix(){
		return 'enteraddons-';
	}

	public function getDirPath() {
		return ENTERADDONS_DIR_PATH;
	}

	public function getDirUrl() {
		return ENTERADDONS_DIR_URL;
	}

	public function getPackageVersion() {
		return ENTERADDONS_VERSION;
	}
	
	public function getPluginMode() {
		return ENTERADDONS_PLUGIN_MODE;
	}

}
