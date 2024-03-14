<?php 
namespace Enteraddons\Widgets\Photo_Stack\Traits;
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

    public static function image($item) {
        $settings   = self::getSettings();

        if( !empty( $item['image']['url'] ) ) {
            echo '<img src="'.esc_url( $item['image']['url'] ).'" class="'.esc_attr($settings['hover_animation']).' ea-photo-stack-img" />';

        }
    }

    //Link
    protected static function linkOpen( $item ) {
        $target = '_self';
        if( !empty( $item['is_external'] ) && $item['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $item['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        return '</a>';
    }

}

