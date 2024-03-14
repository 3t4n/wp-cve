<?php 
namespace Enteraddons\Widgets\Advanced_Animation_Title\Traits;
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

    //Animation Style
    public static function TitleAnimationSettings() {

        $settings = self::getDisplaySettings();
        $animationSettings = [ 

            'animation' => !empty( $settings['animation_type'] ) ? $settings['animation_type'] : '2',  
        ];
        return json_encode( $animationSettings );
    }

    // Before Text
    public static function before_text() {

        $settings = self::getSettings();
        if( !empty( $settings['first_text'] ) ) {
            echo esc_html( $settings['first_text'] );
        }
    }

    // Animation  Text
    public static function animation_text() {
        $id = self::getDisplayID();
        
        $settings = self::getSettings();
        if( !empty( $settings['animation_text'] ) ) {
            echo '<span class="ea-aat-text ea-aat-'.esc_attr( $settings['animation_type'] ).'">'.esc_html( $settings['animation_text'] ).'</span>';
        }
    }

    // After Text
    public static function after_text() {

        $settings = self::getSettings();
        if( !empty( $settings['second_text'] ) ) {
            echo esc_html( $settings['second_text'] );
        }
    }
    
}