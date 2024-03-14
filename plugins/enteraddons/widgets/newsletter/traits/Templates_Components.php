<?php 
namespace Enteraddons\Widgets\Newsletter\Traits;
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

    protected static function input() {
        $settings = self::getSettings();
        echo '<input type="text" class="enteraddons-newsletter-input newsletter-email-input" placeholder="'.esc_html( $settings['input_place_holder'] ).'">';
    }
    protected static function searchButton() {
        $settings = self::getSettings();
        echo '<button class="enteraddons-newsletter-btn">'.esc_html( $settings['search_btn_text'] ).self::icon().'</button>';
    }
    protected static function icon() {
        $settings = self::getSettings();
        return \Enteraddons\Classes\Helper::getElementorIcon( $settings['search_btn_icon'] );
    }

    
}