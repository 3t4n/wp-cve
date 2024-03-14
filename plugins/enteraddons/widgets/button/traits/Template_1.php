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

trait Template_1 {
    
    public static function markup_style_1() {

        $settings   = self::getSettings();
        echo '<div class="enteraddons-anchor-btn-wrap">';
            self::linkOpen();
                if( $settings['btn_hover_effect'] != 'dual-icon-btn' && $settings['icon_position'] == 'left' ) {
                    echo self::button_icon();
                }
                echo '<span class="entera-btn-text-wrap">';

                    if( $settings['btn_hover_effect'] == 'dual-icon-btn' ) {
                        echo self::button_icon_text_before();
                    }
                    self::smallText();
                    self::text1();
                    if( $settings['btn_hover_effect'] == 'dual-icon-btn' ) {
                        echo self::button_icon_text_after();
                    }
                echo '</span>';
                if( $settings['btn_hover_effect'] != 'dual-icon-btn' && $settings['icon_position'] == 'right' ) {
                    echo self::button_icon();
                }

            self::linkClose();
        echo '</div>';
    }

}