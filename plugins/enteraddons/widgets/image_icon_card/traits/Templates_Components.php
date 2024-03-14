<?php 
namespace Enteraddons\Widgets\Image_Icon_Card\Traits;
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

    // Image
    public static function image() {
        $settings = self::getSettings(); 

        if( !empty( $settings['image']['url'] ) ) {
            echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
        }
    }

    // Title
    public static function title() {
        $settings = self::getSettings();

        if( !empty( $settings['image_icon_card_title'] ) ) {
            echo '<div class="eaapp-icon-title">'.esc_html( $settings['image_icon_card_title'] ).'</div>';
        }
    }


    protected static function linkOpen() {
        $Link = self::getSettings();
        //
        $target = '_blank';
        if( !empty( $Link['link']['is_external'] ) && $Link['icon_link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $Link['icon_link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }

    protected static function icon() {
        $settings = self::getSettings();        
        echo '<div class="eaicon ea-'.esc_attr( $settings['image_icon_card_icon_position']).'">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['image_icon_card_icon'] ).'</div>';
    }

    protected static function linkClose() {
        echo '</a>';
    }

}

