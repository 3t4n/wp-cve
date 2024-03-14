<?php 
namespace Enteraddons\Widgets\Social_Icon\Traits;
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

    protected static function heading() {
        $settings = self::getSettings();
        if( !empty( $settings['social_heading'] ) ) {
            $divider = !empty( $settings['active_heading_divider'] ) && $settings['active_heading_divider'] == 'yes' ? 'heading-divider': '';

            echo '<div class="social-heading title-style--five '.esc_attr( $divider ).'">
                    <h2>'.esc_html( $settings['social_heading'] ).'</h2>
                </div>';
        }
    }
    protected static function icon( $data ) {
        $settings = self::getSettings();
        if( $data['icon_type'] != 'img' && !empty( $data['icon'] ) ) {
            echo \Enteraddons\Classes\Helper::getElementorIcon( $data['icon'] );
        } else {
            $url = !empty( $data['image']['url'] ) ? $data['image']['url'] : '';
            $altText = \Elementor\Control_Media::get_image_alt( $data['image'] );
            echo '<img src="'.esc_url( $url ).'" class="svg" alt="'.esc_attr( $altText ).'">';
        }
        //
        if( !empty( $data['social_icon_title'] ) && $settings['icon_style'] == '2' ) {
            echo '<span class="soci-media-title"><span class="soci-media-border"></span>'.esc_html( $data['social_icon_title'] ).'</span>';
        }
        
    }
    protected static function linkOpen( $data ) {
        //
        $target = '_self';
        if( !empty( $data['link']['is_external'] ) && $data['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        echo '<a href="'.esc_url( $data['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        echo '</a>';
    }

    
}