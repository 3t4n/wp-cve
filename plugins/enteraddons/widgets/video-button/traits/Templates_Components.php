<?php 
namespace Enteraddons\Widgets\Video_Button\Traits;
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
    //
    protected static function button() {
        $settings = self::getSettings();

        if( !empty( $settings['btn_icon']['library'] ) && $settings['btn_icon']['library'] == 'svg' ) {
            $icon = !empty( $settings['btn_icon']['value']['url'] ) ? '<img src="'.esc_url( $settings['btn_icon']['value']['url'] ).'" />' : '';
        } else {
            $icon = !empty( $settings['btn_icon']['value'] ) ? '<i class="'.esc_attr( $settings['btn_icon']['value'] ).'"></i>' : '';
        }
        //
        if( !empty( $settings['video_url'] ) ) {
            $is_animation = !empty( $settings['circle_animation_active'] ) ? ' circle-ripple' : '';
            echo '<a href="'.esc_url( $settings['video_url'] ).'" rel="magnificPopup" class="vdo_btn popup-video'.esc_attr( $is_animation ).'">'.$icon.'</a>';
        }
        
    }
    //
    protected static function image() {
        $settings = self::getSettings();

        if( !empty( $settings['thumb_image']['url'] ) ) {
            echo '<img class="video-thumb-img" src="'.esc_url( $settings['thumb_image']['url'] ).'">';
        }
        
    }
    //
    protected static function animation() {
        $settings = self::getSettings();

        if( empty( $settings['circle_animation_active'] ) ) {
            return;
        }

        //
        $color1 = 'rgba(351, 148, 38, 0.3)';
        $color2 = 'rgba(351, 148, 38, 0)';
        if( !empty( $settings['play_btn_animation_color_one'] ) && !empty( $settings['play_btn_animation_color_two'] ) ) {
            $color1 = $settings['play_btn_animation_color_one'];
            $color2 = $settings['play_btn_animation_color_two'];
        }

        
        echo '<style>
            @-webkit-keyframes enteraddons-ripple {
                0% {
                  box-shadow: 0 0 0 0 '.esc_attr( $color1 ).', 0 0 0 10px '.esc_attr( $color1 ).', 0 0 0 20px '.esc_attr( $color1 ).', 0 0 0 30px '.esc_attr( $color1 ).';
                }
                100% {
                  box-shadow: 0 0 0 10px '.esc_attr( $color1 ).', 0 0 0 20px '.esc_attr( $color1 ).', 0 0 0 30px '.esc_attr( $color1 ).', 0 0 0 40px '.esc_attr($color2).';
                }
            }
            @keyframes enteraddons-ripple {
                0% {
                  box-shadow: 0 0 0 0 '.esc_attr( $color1 ).', 0 0 0 10px '.esc_attr( $color1 ).', 0 0 0 20px '.esc_attr( $color1 ).', 0 0 0 30px '.esc_attr( $color1 ).';
                }
                100% {
                  box-shadow: 0 0 0 10px '.esc_attr( $color1 ).', 0 0 0 20px '.esc_attr( $color1 ).', 0 0 0 30px '.esc_attr( $color1 ).', 0 0 0 40px '.esc_attr($color2).';
                }
            }
        </style>';



        
    }



   
}