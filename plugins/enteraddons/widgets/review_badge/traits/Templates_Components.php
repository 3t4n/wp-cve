<?php 
namespace Enteraddons\Widgets\Review_Badge\Traits;
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
    protected static function text() {
        $settings = self::getSettings();
        if( !empty( $settings['rating_text'] ) ) {
            echo '<p>'.esc_html( $settings['rating_text'] ).'</p>';
        }
        
    }
    //
    protected static function star() {
        $settings = self::getSettings();
        if( !empty( $settings['ratings'] ) ) {
            echo '<div class="rating">'.\Enteraddons\Classes\Helper::ratingStar( $settings['ratings'], false ).'</div>';
        }
        
    }
    //
    protected static function ratings() {
        $settings = self::getSettings();

        if( !empty( $settings['ratings'] ) ) {
            echo '<h3>'.esc_html( $settings['ratings'] ).'</h3>';
        }
        
    }

    //
    protected static function ratings_image() {
        $settings = self::getSettings();

        if( !empty( $settings['rating_img']['url'] ) ) {
            echo '<img src="'.esc_url( $settings['rating_img']['url'] ).'" alt="">';
        }
        
    }

   
}