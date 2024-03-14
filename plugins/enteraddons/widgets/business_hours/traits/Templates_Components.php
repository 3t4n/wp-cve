<?php 
namespace Enteraddons\Widgets\Business_Hours\Traits;
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

      // Heading 
      public static function header() {
        $settings = self::getSettings();

        if( !empty( $settings['business_hours_heading'] ) ) {
            echo '<h4>'.esc_html( $settings['business_hours_heading'] ).'</h4>';
        }
    }

    // Day
    public static function day($item) {

        if( !empty( $item['business_hours_day'] ) ) {
            echo '<span class="ea-day">'.esc_html( $item['business_hours_day'] ).'</span>';
        }
    }
    // Time
    public static function time($item) {
        
        if( !empty( $item['business_hours_time'] ) ) {
            echo '<span class="ea-time">'.esc_html( $item['business_hours_time'] ).'</span>';
        }
    }

 
}