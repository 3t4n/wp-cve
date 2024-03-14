<?php 
namespace Enteraddons\Widgets\Image_Zoom_Magnifier\Traits;
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

    // Image
    public static function image() {
        $settings = self::getSettings();

        if( !empty( $settings['image']['url'] ) ) {
            echo '<img src="'.esc_url( $settings['image']['url'] ).'" class="ea-img-zoom" />';
        }
    }
}
