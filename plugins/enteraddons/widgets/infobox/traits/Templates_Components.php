<?php 
namespace Enteraddons\Widgets\Infobox\Traits;
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

    protected static function icon() {

        $settings = self::getSettings();

        $iconDivider = !empty( $settings['icon_divider'] ) && $settings['icon_divider'] == 'yes' ? ' icon-divider' : '';
        $iconType    = !empty( $settings['icon_type'] ) && $settings['icon_type'] != 'img' ? ' info-icon' : ' info-img';
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['image'] );

        echo '<div class="enteraddons-info-box-icon'.esc_attr( $iconType.$iconDivider ).'">';

            if( $settings['icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] );
            }else {
                $normalImgClass = '';
                // Hover image icon
                if( !empty( $settings['hover_image']['url'] ) ) {
                    $altText     = \Elementor\Control_Media::get_image_alt( $settings['hover_image'] );
                    echo '<img class="entera-infobox-hover-image" src="'.esc_url( $settings['hover_image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';

                    $normalImgClass = 'entera-infobox-normal-image';
                }
                // Normal Image
                echo '<img class="'.esc_attr($normalImgClass).'" src="'.esc_url( $settings['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
                
            }
            
        echo '</div>';
    }

    protected static function title() {
        $title = self::getSettings();
        if( !empty( $title['title'] ) ) {
            echo '<h5>'.esc_html( $title['title'] ).'</h5>';
        }
    }

    protected static function descriptions() {
        $descriptions = self::getSettings();
        if( !empty( $descriptions['description'] ) ) {
        echo wpautop( $descriptions['description'] );
        }
    }

    protected static function button() {

        $settings = self::getSettings();
        if( !empty( $settings['wrapper_link'] ) || empty( $settings['link']['url'] ) ) {
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

        echo '<div class="enteraddons-button-wrapper">'.self::linkOpen().$iconLeft.'<span class="ic-btn--text">'.esc_html( $settings['btn_text'] ).'</span>'.$iconRight.self::linkClose().'</a></div>';
    }

    protected static function button_icon() {
        $settings = self::getSettings();
        $normalIcon = Helper::getElementorIcon( $settings['button_icon'] );
        $hoverIcon = Helper::getElementorIcon( $settings['button_hover_icon'] );

        $getHoverIcon = $normalClass = '';
        if( !empty( $hoverIcon ) ) {            
            $getHoverIcon = '<span class="infocard-btn-hover-icon">'.Helper::allowFormattingTagHtml($hoverIcon).'</span>';
            $normalClass = 'infocard-btn-normal-icon';
        }

        return '<span class="btn-icons"><span class="'.esc_attr( $normalClass ).'">'.Helper::allowFormattingTagHtml($normalIcon).'</span>'.$getHoverIcon.'</span>';
    }
    protected static function linkOpen() {
        $settings = self::getSettings();
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $settings['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        return '</a>';
    }
    
}