<?php 
namespace Enteraddons\Widgets\Icon_Card\Traits;
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
    
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function icon() {

        $settings = self::getSettings();

        $iconType    = $settings['icon_type'];
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['image'] );

        echo '<div class="enteraddons-icon-wrap '.esc_attr( $iconType ).'">';

            if( $settings['icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] );
            }else {
                echo '<img src="'.esc_url( $settings['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
            }
            
        echo '</div>';
    }
    protected static function priceIcon() {

        $settings = self::getSettings();

        echo '<span class="'.esc_attr( $settings['pricing_icon_type'] ).'">';

            if( $settings['pricing_icon_type'] != 'img' && !empty( $settings['pricing_icon'] ) ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['pricing_icon'] );
            } else {
                $url = !empty( $settings['pricing_image']['url'] ) ? $settings['pricing_image']['url'] : '';
                $altText = \Elementor\Control_Media::get_image_alt( $settings['pricing_image'] );
                echo '<img src="'.esc_url( $url ).'" class="svg" alt="'.esc_attr( $altText ).'">';
            }

        echo '</span>';
    }
    protected static function pricing() {

        $settings = self::getSettings();
  
        echo '<div class="ext-price">';
            //
            if( !empty( $settings['pricing_text'] ) ) {
                echo '<span>'.esc_html( $settings['pricing_text'] ).'</span>';
            }
            //
            self::priceIcon();
        echo '</div>';
    }

    protected static function title() {
        $title = self::getSettings();
        if( !empty( $title['title'] ) ) {
            echo '<h3>'.esc_html( $title['title'] ).'</h3>';
        }
    }

    protected static function descriptions() {
        $descriptions = self::getSettings();
        if( !empty( $descriptions['description'] ) ) {
        echo '<p>'.esc_html( $descriptions['description'] ).'</p>';
        }
    }

    protected static function button() {

        $settings = self::getSettings();
        
        if( $settings['btn_show'] != 'yes' ) {
            return;
        }

        // button icon position
        $iconLeft   = '';
        $iconRight  = '';

        if( $settings['icon_position'] == 'left' ) {
            $iconLeft = self::button_icon().' ';
        } else {
            $iconRight = ' '.self::button_icon();
        }

        echo self::linkOpen().$iconLeft.esc_html( $settings['btn_text'] ).$iconRight.self::linkClose();
    }

    protected static function button_icon() {
        $settings = self::getSettings();
        return \Enteraddons\Classes\Helper::getElementorIcon( $settings['button_icon'] );
    }
    protected static function linkOpen() {
        $settings = self::getSettings();
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $settings['link']['url'] ).'" target="'.esc_attr( $target ).'" class="icon-card-btn">';
    }
    protected static function linkClose() {
        return '</a>';
    }
    
}