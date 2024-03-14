<?php 
namespace Enteraddons\Widgets\Google_Map\Traits;
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

    // Social Icon
    public static function mapMarkup() {

        $settings = self::getSettings();
        
        $options = [
            "marker"    => !empty( $settings['marker_image']['url'] ) ? $settings['marker_image']['url'] : '',
            "latitude"  => !empty( $settings['map_latitude'] ) ? $settings['map_latitude'] : '',
            "longitude" => !empty( $settings['map_longitude'] ) ? $settings['map_longitude'] : '',
            "zoom"      => !empty( $settings['map_zoom']['size'] ) ? $settings['map_zoom']['size'] : ''
        ];

        $jsonOptions = json_encode( $options );

        echo '<div class="ea-google-map-wrap"><div class="ea-google-map" data-options="'.esc_attr( $jsonOptions ).'"  data-trigger="ea-g-map"></div></div>';
    }

}