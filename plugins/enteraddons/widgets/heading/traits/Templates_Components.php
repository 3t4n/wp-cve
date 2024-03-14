<?php 
namespace Enteraddons\Widgets\Heading\Traits;
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
    protected static function title() {
        $settings = self::getSettings();

        if( !empty( $settings['title'] ) ) {

            $allowedHtml = wp_kses_allowed_html('post');

            $ba = '';
            $ba .= !empty( $settings['active_title_ba'] ) && 'yes' == $settings['active_title_ba'] ? 'entera-heading-title-ba' : '';

            $ba .= !empty( $settings['title_ba_hide_tab'] ) && 'yes' == $settings['title_ba_hide_tab'] ? ' entera-hide-tab' : '';
            $ba .= !empty( $settings['title_ba_hide_mob'] ) && 'yes' == $settings['title_ba_hide_mob'] ? ' entera-hide-mob' : '';
            
            echo '<'.esc_attr( $settings['tag'] ).' class="heading-title '.esc_attr( $ba ).'">'.wp_kses( $settings['title'], $allowedHtml ).'</'.esc_attr($settings['tag']).'>';
        }
        
    }
    //
    protected static function subTitle() {
        $settings = self::getSettings();

        if( !empty( $settings['subtitle'] ) ) {
            echo '<'.esc_attr( $settings['subtitle_tag'] ).' class="heading-short-title">'.esc_html( $settings['subtitle'] ).'</'.esc_attr( $settings['subtitle_tag'] ).'>';
        }
        
    }
    //
    protected static function descriptions() {
        $settings = self::getSettings();

        if( !empty( $settings['description'] ) ) {
            echo '<div class="heading-short-desc">'.wp_kses( $settings['description'], 'post' ).'</div>';
        }
        
    }
    protected static function divider() {
        $settings = self::getSettings();
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['divider_image'] );
        echo '<span class="enteraddons-seperator seperator seperator-'.esc_attr( $settings['divider_style'] ).'">';
        if( $settings['divider_style'] == 'custom' && !empty( $settings['divider_image']['url'] ) ) {
            echo '<img src="'.esc_url( $settings['divider_image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
        }
        echo '</span>';
    }
   
}