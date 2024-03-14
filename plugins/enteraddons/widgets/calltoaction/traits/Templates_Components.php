<?php 
namespace Enteraddons\Widgets\Calltoaction\Traits;
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
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function title() {
        $settings = self::getSettings();
        //
        if( !empty( $settings['title'] ) ) {
            echo '<'.esc_attr( $settings['tag'] ).' class="cta-title">'.esc_html( $settings['title'] ).'</'.esc_attr( $settings['tag'] ).'>';
        }
    }

    protected static function descriptions() {
        $settings = self::getSettings();
        //
        if( !empty( $settings['descriptions'] ) ) {
            echo '<p class="cta-desc">'.wp_kses( $settings['descriptions'], 'post' ).'</p>';
        }
    }

    protected static function button() {

        $settings = self::getSettings();
        $btnClass = 'enteraddons-btn'.' '.$settings['btn_hover_effect'];

        $beforeIcon = !empty( $settings['before_icon'] ) ? Helper::getElementorIcon( $settings['before_icon'] ) : '';
        $afterIcon  = !empty( $settings['after_icon'] ) ? Helper::getElementorIcon( $settings['after_icon']) : '';

        $btnContent = '<span class="dual-icon-left">'.Helper::allowFormattingTagHtml($beforeIcon).'</span>'.esc_html($settings['link_label']).'<span class="dual-icon-right">'.Helper::allowFormattingTagHtml($afterIcon).'</span>';
        //
        echo '<div class="cta-button-wrapper">';
        echo Helper::getElementorLinkHandler( $settings['link'], $btnContent, $btnClass );
        echo '</div>';
    }

}