<?php 
namespace Enteraddons\Widgets\Advanced_List\Traits;
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

    protected static function number( $options = '' ) {
        if( !empty( $options['number'] ) ) {
            echo '<span class="ea-list-style ea-list-number">'.esc_html( $options['number'] ).'</span>';
        }
    }

    protected static function icon( $options = '' ) {
        if( !empty( $options['icon'] ) ) {
            echo '<span class="ea-list-style ea-list-icon">'.Helper::getElementorIcon( $options['icon'] ).'</span>';
        }
    }

    protected static function title($options) {
        if( !empty( $options['title'] ) ) {
            echo '<span class="ea-title">'.esc_html( $options['title'] ).'</span>';
        }
    }
    
    protected static function linkOpen( $link ) {
        //
        $target = '_self';
        if( !empty( $link['is_external'] ) && $link['is_external'] == 'on' ) {
            $target = '_blank';
        }
        return '<a href="'.esc_url( $link['url'] ).'" target="'.esc_attr( $target ).'">';
    }

    protected static function linkClose() {
        return '</a>';
    }

}