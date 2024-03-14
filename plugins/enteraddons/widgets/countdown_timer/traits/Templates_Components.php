<?php 
namespace Enteraddons\Widgets\Countdown_Timer\Traits;
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
    //
    protected static function countDown_Html() {
        $settings = self::getSettings();

        $dateTime = !empty( $settings['countdown_date'] ) ? $settings['countdown_date'] : '';
        $text_days = !empty( $settings['text_days'] ) ? $settings['text_days'] : esc_html__( 'Days', 'enteraddons' );
        $text_hour  = !empty( $settings['text_hour'] ) ? $settings['text_hour'] : esc_html__( 'Hour', 'enteraddons' );
        $text_min  = !empty( $settings['text_min'] ) ? $settings['text_min'] : esc_html__( 'Min', 'enteraddons' );
        $text_sec  = !empty( $settings['text_sec'] ) ? $settings['text_sec'] : esc_html__( 'Sec', 'enteraddons' );
        $isDivider  = !empty( $settings['active_divider'] ) ? ' divider' : '';
        
        // Jan 5, 2024 15:37:25
        echo '<div class="star-countdown-timer'.esc_attr($isDivider).'" data-days="'.esc_attr( $text_days ).'" data-hour="'.esc_attr( $text_hour ).'" data-min="'.esc_attr( $text_min ).'" data-sec="'.esc_attr( $text_sec ).'" data-date-time="'.esc_attr( $dateTime ).'"></div>';

    }
    
   
   
}