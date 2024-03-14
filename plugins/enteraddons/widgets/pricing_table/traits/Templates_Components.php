<?php 
namespace Enteraddons\Widgets\Pricing_Table\Traits;
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

    public static function title() {
        $settings = self::getSettings();
        if( !empty( $settings['title'] ) ) {
            echo '<h2 class="enteraddons-pt-title">'.esc_html( $settings['title'] ).'</h2>';
        }
    }
    public static function subTitle() {
        $settings = self::getSettings();
        if( !empty( $settings['sub_title'] ) ) {
            echo '<p class="enteraddons-pt-info">'.esc_html( $settings['sub_title'] ).'</p>';
        }
    }
    public static function price() {
        $settings = self::getSettings();
        $currency = '';
        if( !empty( $settings['currency'] ) ) {
            if( $settings['currency'] == 'custom' && !empty( $settings['custom_currency'] )  ) {
                $currency = $settings['custom_currency'];
            } else {
                $currency = \Enteraddons\Classes\Helper::getCurrencySymbol( $settings['currency'] );
            }
        }

        echo '<div class="enteraddons-pt-price">';
            echo '<span class="enteraddons-pt-inner-price">';
            echo '<span class="price-group">';
            // Regular price
            if( !empty( $settings['regular_price'] ) ) {
            echo '<span class="ea-pt-regular-price"><del>'.esc_html( $currency.$settings['regular_price'] ).'</del></span>';
            }
            // Sale price
            echo '<span class="ea-pt-sale-price">';
            //Currency
            if( !empty( $currency ) ) {
                echo '<sub class="currency">'.esc_html( $currency ).'</sub>';
            }
            // Price
            if( !empty( $settings['price'] ) ) {
                echo '<span class="price">'.esc_html( $settings['price'] ).'</span>';
            }
            echo '</span></span>';

            // Duration
            if( !empty( $settings['duration'] ) ) {
                echo '<span class="duration-wrapper"><sub class="duration">'.esc_html( $settings['duration'] ).'</sub></span>';
            }
            
            echo '</span>';
        echo '</div>';
    }
    public static function badge() {
        $settings = self::getSettings();
        if( !empty( $settings['show_badge'] ) ) {
            $text = !empty( $settings['badge_text'] ) ? $settings['badge_text'] : '';
            echo '<span class="enteraddons-price-badge '.esc_html( $settings['badge_style'] ).'">'.esc_html( $text ).'</span>';
        }
    }
    public static function features() {
        $settings = self::getSettings();
        if( !empty( $settings['pricing_features'] ) ) {
            echo '<div class="enteraddons-pt-body text-center">';
                echo '<ul>';
                    foreach( $settings['pricing_features'] as $features ) {
                        $title = !empty( $features['name'] ) ? $features['name'] : '';
                        echo '<li class="elementor-repeater-item-' . $features['_id'] . '">'.\Enteraddons\Classes\Helper::getElementorIcon( $features['icon'] ).' '.esc_html( $title ).'</li>';
                    }
                echo '</ul>';
            echo '</div>';
        }
    }
    public static function button() {
        $settings = self::getSettings();
        
        if( !empty( $settings['show_btn'] ) ) {
            $btnText = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
            $btnIcon = \Enteraddons\Classes\Helper::getElementorIcon( $settings['button_icon'] );
            $btnData = $btnText.$btnIcon;
            
            echo '<div class="enteraddons-pt-footer">'.\Enteraddons\Classes\Helper::getElementorLinkHandler( $settings['link'], $btnData, 'enteraddons-btn' ).'</div>';
        }
        
    }
}