<?php 
namespace Enteraddons\Widgets\Profile_Card\Traits;
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

    public static function text( $text = '' ) {
            echo esc_html( $text );
    }

    public static function thumbnail( $img, $class = '' ) {
        $altText = \Elementor\Control_Media::get_image_alt( $img );
        echo '<img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'">';
    }

    protected static function linkOpen() {
        $settings = self::getSettings();
        //
        $target = '_self';
        if( !empty( $settings['profile_link']['is_external'] ) && $settings['profile_link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a class="btn-link" href="'.esc_url( $settings['profile_link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }

    protected static function linkOpen2( $item ) {
        //
        $target = '_self';
        if( !empty( $item['social_icon_link']['is_external'] ) && $item['social_icon_link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $item['social_icon_link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }

    protected static function linkClose() {
        return '</a>';
    }

    protected static function socialIcon( $item ) {
        if( ! empty( $item['social_icon'] ) ) {
            echo self::linkOpen2( $item );
            echo \Enteraddons\Classes\Helper::getElementorIcon( $item['social_icon'] );
            echo self::linkClose();
        }
    }

    protected static function star() {
        $settings = self::getSettings();
        if( !empty( $settings['ratings'] ) ) {
            echo '<div class="rating">'.\Enteraddons\Classes\Helper::ratingStar( $settings['ratings'], false ).'</div>';
        } 
    }
    
    protected static function ratings() {
        $settings = self::getSettings();

        if( !empty( $settings['ratings'] ) ) {
            echo '<h3>'.esc_html( $settings['ratings'] ).'</h3>';
        }
        
    }
    
    
}