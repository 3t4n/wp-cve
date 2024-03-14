<?php 
namespace Enteraddons\Widgets\Color_Scheme\Traits;
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

    // Color Code
    public static function color_code( $color ) {

        if( !empty( $color['color_code'] ) ) {
            echo '<div class="ea-bg-color" style="background-color:'.esc_attr( $color['color_code'] ).';color:'.esc_attr( $color['color_code_color'] ).';" >';
            echo  '<span class="copied-text" style="display:none;">'.esc_html__( 'Copied!', 'enteraddons' ).'</span>';
            echo  '<span class="ea-color-value">'.esc_html( $color['color_code'] ).'</span>';
            echo '</div>';
        }
    }

    // Color Text
    public static function scheme_name() {
        $settings = self::getDisplaySettings();

        if( !empty( $settings['color_scheme_name'] ) ) {
            echo '<div class="ea-color-details">';
                echo '<div class="ea-color-name">'.esc_html( $settings['color_scheme_name'] ).'</div>';
            echo '</div>';
        }
    }
}