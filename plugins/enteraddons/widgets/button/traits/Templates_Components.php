<?php 
namespace Enteraddons\Widgets\Button\Traits;
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

    protected static function text1() {
        $text = self::getSettings();

        if( !empty( $text['btn_text'] ) ) {
            echo '<span class="btn-min-text">'.esc_html( $text['btn_text'] ).'</span>';
        }
    }

    protected static function smallText() {
        $smltext = self::getSettings();
        if( !empty( $smltext['small_text'] ) ) {
            echo '<small class="btn-small-text">'.esc_html( $smltext['small_text'] ).'</small>';
        }
        
    }

    protected static function button_icon() {
        $settings = self::getSettings();

        if( empty( $settings['show_icon'] ) ) {
            return;
        }

        $normalIcon = Helper::getElementorIcon( $settings['button_icon'] );
        $hoverIcon = Helper::getElementorIcon( $settings['button_hover_icon'] );

        $getHoverIcon = $normalClass = '';
        if( !empty( $hoverIcon ) ) {            
            $getHoverIcon = '<span class="btn--hover-icon">'.Helper::allowFormattingTagHtml($hoverIcon).'</span>';
            $normalClass = 'btn--normal-icon';
        }

        return '<span class="btn-icons"><span class="'.esc_attr( $normalClass ).'">'.Helper::allowFormattingTagHtml($normalIcon).'</span>'.Helper::allowFormattingTagHtml($getHoverIcon).'</span>';
    }
    protected static function button_icon_text_before() {
        $settings = self::getSettings();

        if( empty( $settings['show_icon'] ) ) {
            return;
        }

        $normalIcon = Helper::getElementorIcon( $settings['button_icon'] );

        return '<span class="dual-icon-left">'.Helper::allowFormattingTagHtml($normalIcon).'</span>';
    }
    protected static function button_icon_text_after() {
        $settings = self::getSettings();

        if( empty( $settings['show_icon'] ) ) {
            return;
        }

        $hoverIcon = Helper::getElementorIcon( $settings['button_hover_icon'] );

        return '<span class="dual-icon-right">'.Helper::allowFormattingTagHtml($hoverIcon).'</span>';
    }
    protected static function linkOpen() {
        $settings = self::getSettings();
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        echo '<a href="'.esc_url( $settings['link']['url'] ).'" class="enteraddons-anchor-link '.esc_attr( $settings['btn_hover_effect'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        echo '</a>';
    }
    
}