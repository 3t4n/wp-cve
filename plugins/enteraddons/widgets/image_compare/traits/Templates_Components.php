<?php 
namespace Enteraddons\Widgets\Image_Compare\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function arrowNav() {

        $settings = self::getSettings();
        $arrowNav = ENTERADDONS_DIR_ASSETS_URL.'img/cd-arrows.svg';

        return '<span class="cd-handle" style="background:#dc717d url('.$arrowNav.') no-repeat center center;"></span>';
    }


}