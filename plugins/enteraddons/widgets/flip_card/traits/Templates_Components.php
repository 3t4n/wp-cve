<?php 
namespace Enteraddons\Widgets\Flip_Card\Traits;
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
use \Enteraddons\Classes\Helper;
trait Templates_Components {
    
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function front_icon() {

        $settings = self::getSettings();

        $iconType    = !empty( $settings['front_icon_type'] ) && $settings['front_icon_type'] != 'img' ? ' flip-icon' : ' flip-img';
        
        echo '<div class="enteraddons-flip-card-icon'.esc_attr( $iconType ).'">';

            if( $settings['front_icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['front_icon'] );
            }else {
                $altText = \Elementor\Control_Media::get_image_alt( $settings['front_image'] );
                // Image
                echo '<img src="'.esc_url( $settings['front_image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
                
            }
            
        echo '</div>';
    }

    protected static function back_icon() {

        $settings = self::getSettings();

        $iconType = !empty( $settings['back_icon_type'] ) && $settings['back_icon_type'] != 'img' ? ' flip-icon' : ' flip-img';
        
        echo '<div class="enteraddons-flip-card-icon'.esc_attr( $iconType ).'">';

            if( $settings['back_icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['back_icon'] );
            } else {
                $altText = \Elementor\Control_Media::get_image_alt( $settings['back_image'] );
                // Image
                echo '<img src="'.esc_url( $settings['back_image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
            }
            
        echo '</div>';
    }

    protected static function front_title() {
        $title = self::getSettings();
        if( !empty( $title['front_title'] ) ) {
            echo '<h5>'.esc_html( $title['front_title'] ).'</h5>';
        }
    }

    protected static function back_title() {
        $title = self::getSettings();
        if( !empty( $title['back_title'] ) ) {
            echo '<h5>'.esc_html( $title['back_title'] ).'</h5>';
        }
    }

    protected static function front_descriptions() {
        $descriptions = self::getSettings();
        if( !empty( $descriptions['front_desc'] ) ) {
        echo '<div class="flip-desc">'.wpautop( $descriptions['front_desc'] ).'</div>';
        }
    }

    protected static function back_descriptions() {
        $descriptions = self::getSettings();
        if( !empty( $descriptions['back_desc'] ) ) {
        echo '<div class="flip-desc">'.wpautop( $descriptions['back_desc'] ).'</div>';
        }
    }

    protected static function back_button() {

        $settings = self::getSettings();

        if( !empty( $settings['btn_link']['url'] ) ) {
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        echo '<a href="'.esc_url( $settings['btn_link']['url'] ).'" target="'.esc_attr( $target ).'" class="ea-flip-card-btn">'.esc_html( $settings['btn_text'] ).'</a>';

        }

    }

}