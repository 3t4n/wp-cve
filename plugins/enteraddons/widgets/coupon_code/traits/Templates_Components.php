<?php 
namespace Enteraddons\Widgets\Coupon_Code\Traits;
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

    protected static function icon() {
        $settings = self::getSettings();
        return '<span class="ea-ccb-icon">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] ).'</span>';
    }
    
    protected static function couponcode() {
        $settings = self::getSettings();
        return  $settings['coupon_code'];
    }

    protected static function copyBtnText() {
        $settings = self::getSettings();
        return  esc_html( $settings['btn_text'] );
    }
    
    protected static function copiedText() {
        $settings = self::getSettings();
        return  $settings['copied_text'];
    }

    
    
}