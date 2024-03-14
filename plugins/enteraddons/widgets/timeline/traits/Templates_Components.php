<?php 
namespace Enteraddons\Widgets\Timeline\Traits;
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
    protected static function title() {
        $settings = self::getSettings();
        if( !empty( $settings['title'] ) ) {
            echo '<h6>'.esc_html( $settings['title'] ).'</h6>';
        }
    }
    protected static function date() {
        $settings = self::getSettings();
        if( !empty( $settings['date'] ) ) {
            echo '<div class="timeline-time"><span>'.esc_html( $settings['date'] ).'</span></div>';
        }
        
    }
    protected static function descriptions() {
        $settings = self::getSettings();
        if( !empty( $settings['descriptions'] ) ) {
            echo '<p>'.esc_html( $settings['descriptions'] ).'</p>';
        }
    }
    protected static function icon() {

        $settings = self::getSettings();

        $iconType    = $settings['icon_type'];
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['image'] );

        echo '<div class="timeline-icon '.esc_attr( $iconType ).'">';

            if( $settings['icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] );
            }else {
                echo '<img src="'.esc_url( $settings['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
            }
            
        echo '</div>';
    }

    
}