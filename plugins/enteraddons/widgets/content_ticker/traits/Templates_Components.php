<?php 
namespace Enteraddons\Widgets\Content_Ticker\Traits;
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

    public static function ticketSettings(){

        $settings = self::getSettings();

        $tickerSettings = [

            'type'         => !empty( $settings['ticker_type'] ) ? $settings['ticker_type'] : 'typewriter',
            'direction'    => !empty( $settings['ticker_direction'] ) ? $settings['ticker_direction'] : 'right',
            'speed'        => !empty( $settings['ticker_speed'] ) ? $settings['ticker_speed'] : 0.05,
            'autoplay'        => !empty( $settings['ticker_autoplay'] ) ? $settings['ticker_autoplay'] : 2000,
            'focus'        => !empty( $settings['ticker_focus'] ) && $settings['ticker_focus'] == 'yes' ? true : false,
            'hover'        => !empty( $settings['ticker_hover'] ) && $settings['ticker_hover'] == 'yes' ? true : false,

        ];
        return json_encode( $tickerSettings );  
    }
    //Title
    public static function title(){
        $settings = self::getSettings();

        if(!empty($settings['title'])){
            echo '<div class="enteraddons-news-ticker-label">'.esc_html($settings['title']).'</div>';
        }

    }
    //Link
    protected static function linkOpen( $item ) {

        $target = '_self';
        if( !empty( $item['link']['url'] ) ) {
            if( !empty( $item['link']['is_external'] ) && $item['link']['is_external'] == 'on' ) {
                $target = '_blank';
            }
        }
        
        return '<a href="'.esc_url( !empty( $item['link']['url'] ) ? $item['link']['url'] : '#' ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        return '</a>';
    }
    //button
    protected static function button() {
        $settings = self::getSettings();
        if ( 'yes' === $settings['icon_control_show'] ){ 
        ?>
        <button class="enteraddons-news-ticker-arrow enteraddons-news-ticker-prev"></button>
        <button class="enteraddons-news-ticker-pause"></button>
        <button class="enteraddons-news-ticker-arrow enteraddons-news-ticker-next"></button>
        <?php
        }
    }
}