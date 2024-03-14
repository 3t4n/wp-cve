<?php 
namespace Enteraddons\Widgets\Nav_Logo\Traits;
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
    protected static function mainLogo() {
        $settings = self::getSettings();
        
        $url = !empty( $settings['logo_img']['url'] ) ? $settings['logo_img']['url'] : '';
        $altText = \Elementor\Control_Media::get_image_alt( $settings['logo_img'] );
        echo '<img src="'.esc_url( $url ).'" class="main-logo" alt="'.esc_attr( $altText ).'">';
    }
    //
    protected static function stickyLogo() {
        $settings = self::getSettings();

        $url = !empty( $settings['sticky_logo_img']['url'] ) ? $settings['sticky_logo_img']['url'] : '';
        $altText = \Elementor\Control_Media::get_image_alt( $settings['sticky_logo_img'] );
        echo '<img src="'.esc_url( $url ).'" class="sticky-logo" alt="'.esc_attr( $altText ).'">';
        
    }

   
}