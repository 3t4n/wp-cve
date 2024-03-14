<?php 
namespace Enteraddons\Widgets\Title_Reveal_Animation\Traits;
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

    // Title
    public static function title() {
        $settings = self::getSettings();

        if( !empty( $settings['animation_title'] ) ) {
            echo '<p class="eaatbigger">'.\Enteraddons\Classes\Helper::allowFormattingTagHtml( $settings['animation_title'] ).'</p>';
        } 
    }

    public static function description() {
        $settings = self::getSettings();

        if( !empty( $settings['animation_description'] ) ) {
            echo '<p class="eaattext">'.esc_html( $settings['animation_description'] ).'</p>';
        } 
    }
   
}