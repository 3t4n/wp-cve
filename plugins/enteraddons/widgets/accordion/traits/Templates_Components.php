<?php 
namespace Enteraddons\Widgets\Accordion\Traits;
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
    protected static function getAttribute() {
        return self::getAttributeString();
    }
    protected static function leftIcon() {

        $settings   = self::getSettings();
        if( empty( $settings['show_icon'] ) ) {
            return;
        }
        //
        $left_active_icon = Helper::getElementorIcon( $settings['left_active_icon'] );
        $left_close_icon = Helper::getElementorIcon( $settings['left_close_icon'] );
        //
        echo '<div class="faq-left-icons">';
        if( !empty( $left_active_icon ) ) {
            echo '<div class="active-icon"><div class="ea-faq-title-icon">'.Helper::allowFormattingTagHtml($left_active_icon).'</div></div>';
        }
        //
        if( !empty( $left_close_icon ) ) {
            echo '<div class="close-icon"><div class="ea-faq-title-icon">'.Helper::allowFormattingTagHtml($left_close_icon).'</div></div>';
        }
        echo '</div>';
    }
    protected static function rightIcon() {
        $settings   = self::getSettings();
        if( empty( $settings['show_icon'] ) ) {
            return;
        }

        $iconPosition = !empty( $settings['right_icon_position'] ) && $settings['right_icon_position'] != 'near-text' ? 'faq-ml-auto' : '';
        //
        $right_active_icon = Helper::getElementorIcon( $settings['right_active_icon'] );
        $right_close_icon = Helper::getElementorIcon( $settings['right_close_icon'] );
        //
        echo '<div class="faq-right-icons '.esc_attr($iconPosition).'">';
            if( !empty( $right_active_icon ) ) {
                echo '<div class="active-icon"><div class="ea-faq-title-icon">'.Helper::allowFormattingTagHtml($right_active_icon).'</div></div>';
            }
            //
            if( !empty( $right_close_icon ) ) {
                echo '<div class="close-icon"><div class="ea-faq-title-icon">'.Helper::allowFormattingTagHtml($right_close_icon).'</div></div>';
            }
        echo '</div>';
        
    }
    protected static function numberCount() {
        $settings   = self::getSettings();
        //
        if( !empty( $settings['number_count_show'] ) && $settings['number_count_show'] == 'yes'   ) {
            echo '<span class="faq-count faq-number-count"></span>';
        }
    }
    
}