<?php 
namespace Enteraddons\Widgets\Photo_Hanger\Traits;
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

    public static function title( $title = '' ) {
        if( !empty( $title ) ) {
            echo'<span>'.esc_html( $title ).'</span>';
        }
    }

    public static function thumbnail( $img, $class = '' ) {

        if( !empty( $img['url'] ) ) {
            $altText = \Elementor\Control_Media::get_image_alt( $img );
            echo '<img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'">';
        }

    }
    
    
}