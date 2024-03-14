<?php 
namespace Enteraddons\Widgets\Progressbar\Traits;
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

    // Name
    public static function name() {
        $settings = self::getSettings();
        echo '<span class="process-name">'.esc_html( $settings['title'] ).'</span>';
    }

    // Process
    public static function progress() {
        $settings = self::getSettings();
        echo '<span class="process-width">'.esc_html( $settings['progress']['size'].$settings['progress']['unit'] ).'</span>';
    }

}