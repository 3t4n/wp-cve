<?php 
namespace Enteraddons\Widgets\Counter\Traits;
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
            
            $tag = $settings['title_tag'];
            echo '<'.esc_attr( $tag ).' class="enteraddons-counter-title">'.esc_html( $settings['title'] ).'</'.esc_attr( $tag ).'>';
        }
        
    }

    protected static function icon() {
        
        $settings = self::getSettings();

        $iconType    = $settings['icon_type'] != 'img' ? ' counter-icon' : ' counter-img';
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['image'] );

        if( !empty( $settings['icon_show'] ) && $settings['icon_show'] == 'yes' ){
            
            echo '<div class="enteraddons-counter-icon'.esc_attr( $iconType ).'">';
                if( $settings['icon_type'] != 'img' ) {
                    echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] );
                }else {
                    echo '<img src="'.esc_url( $settings['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
                }
            echo '</div>';
        }
    }

    protected static function number() {
        $settings = self::getSettings();

        if( !empty( $settings['number'] ) ) {
            $tag = $settings['number_tag'];
            echo '<'.esc_attr( $tag ).' class="enteraddons-counter-number"><span class="enteraddons-count">'.esc_html( $settings['number'] ).'</span>'.esc_html( $settings['number_unit'] ).'</'.esc_attr( $tag ).'>';
        }
        
    }

}