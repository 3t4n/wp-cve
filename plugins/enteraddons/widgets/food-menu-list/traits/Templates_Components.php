<?php 
namespace Enteraddons\Widgets\Food_Menu_List\Traits;
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

    protected static function linkOpen( $data ) {
        //
        $target = '_self';
        if( !empty( $data['link']['is_external'] ) && $data['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }
        echo '<a href="'.esc_url( $data['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    
    protected static function linkClose() {
        echo '</a>';
    }

    
}