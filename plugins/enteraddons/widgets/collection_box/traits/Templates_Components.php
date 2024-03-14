<?php 
namespace Enteraddons\Widgets\Collection_Box\Traits;
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

use \Enteraddons\Classes\Helper;
trait Templates_Components {
    
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function image() {
        $settings = self::getSettings();

        $iconType    = !empty( $settings['icon_type'] ) && $settings['icon_type'] != 'img' ? ' collection-icon' : ' collection-img';
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['image'] );

        echo '<div class="collection-img">';

            if( $settings['icon_type'] != 'img' ) {
                //Logo
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] );
            }else {
                //  Image
                echo '<img src="'.esc_url( $settings['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
                
            }
            
        echo '</div>';
    }
    protected static function title() {
        $title = self::getSettings();

        if( !empty( $title['collection_box_title'] ) ) {
           echo '<h5>'.esc_html($title['collection_box_title']).'</h5>';
        }
    }
    protected static function ammount() {
        $ammount = self::getSettings();

        if( !empty( $ammount['collection_box_ammount'] ) ) {
           echo '<span>'.esc_html($ammount['collection_box_ammount']).'</span>';
        }
    }
    protected static function linkOpen() {
        $Link = self::getSettings();
        //
        $target = '_self';
        if( !empty( $Link['link']['is_external'] ) && $Link['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $Link['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        echo '</a>';
    }
    
}