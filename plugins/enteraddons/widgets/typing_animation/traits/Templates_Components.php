<?php 
namespace Enteraddons\Widgets\Typing_Animation\Traits;
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
    
    public static function typingSettings() {

        $settings = self::getDisplaySettings();

        $typingSettings = [
            'animationText'     => !empty( $settings['animation_text_list'] ) ? array_column( $settings['animation_text_list'], 'animation_title' ) : '',
            'TypeSpeed'         => !empty( $settings['type_speed'] ) ? $settings['type_speed'] : 100,
            'StartDelay'        => !empty( $settings['start_delay'] ) ? $settings['start_delay'] : 0,
            'BackSpeed'         => !empty( $settings['back_speed'] ) ? $settings['back_speed'] : 60,
            'BackDelay'         => !empty( $settings['back_delay'] ) ? $settings['back_delay'] : 100,
            'Loop'              => !empty( $settings['data_loop'] ) && $settings['data_loop'] == 'yes' ? true : false,
            
        ];

        return json_encode( $typingSettings );

    }

      // First Text
     public static function first_text() {
        $settings = self::getSettings();

        if( !empty( $settings['first_title'] ) ) {
            echo esc_html( $settings['first_title'] );
        }
    }

     // Second Text
     public static function second_text() {
        $settings = self::getSettings();

        if( !empty( $settings['second_title'] ) ) {
            echo esc_html( $settings['second_title'] );
        }
    }

}