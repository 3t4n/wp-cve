<?php 
namespace Enteraddons\Widgets\Nav_Search\Traits;
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
    
    protected static function searchModalForm() {
        $settings = self::getSettings();

        $placeHolder = !empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : '';

        echo '<div class="ea-search-modal" id="search">
          <div class="ea-search-close-btn" close-modal="search">X</div>
          <div class="content-body">
            <form class="search-form ea-search-form-wrap" action="'.esc_url( site_url( '/' ) ).'">
            <div class="ea-form-field-group">
            <input name="s" type="text" class="ea-search-input" placeholder="'.esc_attr( $placeHolder ).'">
            <button type="submit" class="ea-search-submit-btn">';
            if( !empty( $settings['search_btn_type'] ) && $settings['search_btn_type'] == 'icon' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['search_btn_icon'] );
            } else {
                if( !empty( $settings['search_btn_text'] ) ) {
                    echo esc_html( $settings['search_btn_text'] );
                }
            }
            echo '</button>
            </div>
            </form>
          </div>
        </div>';
    }

    protected static function searchForm() {
        $settings = self::getSettings();
        $placeHolder = !empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : '';
        echo '<div class="ea-search-form-wrap"><form class="search-form ea-search-form-wrap" action="'.esc_url( site_url( '/' ) ).'">';
            echo '<div class="ea-form-field-group">';
                echo '<input type="text" name="s" class="ea-search-input" placeholder="'.esc_attr( $placeHolder ).'">';
                echo '<button type="submit" class="submit-btn ea-search-submit-btn">';
                    if( !empty( $settings['search_btn_type'] ) && $settings['search_btn_type'] == 'icon' ) {
                        echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['search_btn_icon'] );
                    } else {
                        if( !empty( $settings['search_btn_text'] ) ) {
                            echo esc_html( $settings['search_btn_text'] );
                        }
                    }
                echo '</button>';
            echo '</div>';
        echo '</form></div>';

    }

   
}