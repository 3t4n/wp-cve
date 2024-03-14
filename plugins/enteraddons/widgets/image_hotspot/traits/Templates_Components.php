<?php 
namespace Enteraddons\Widgets\Image_Hotspot\Traits;
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
    public static function hotspotsettings(){

        $settings = self::getSettings();

        $hotspotsettings = [

            'type' => !empty( $settings['tooltip_type'] ) ? $settings['tooltip_type'] : 'click',
        ];
        return json_encode( $hotspotsettings );  
    }
    //Background Imange
    protected static function background_image() {
        $settings = self::getSettings();
        if( !empty( $settings['background_img']['url'] ) ) {
            echo '<img src="'.esc_url( $settings['background_img']['url'] ).'" class="ea-img-responsive">';
        }
    }
    // Tooltip Image
    protected static function image($item) {
        if( !empty( $item['img']['url'] ) ) {
          echo  '<div class="ea-img-row"><img src="'.esc_url( $item['img']['url'] ).'"></div>';
            
        }
    }

    //Title
    protected static function title($item) {
        if( !empty( $item['title'] ) ) {
          echo   '<h4>'.esc_html($item['title']).'</h4>';
        }
    }

    //Description
    protected static function description($item) {
        if( !empty( $item['description'] ) ) {
          echo '<p>'.esc_html($item['description']).'</p>';
        }
    }
}