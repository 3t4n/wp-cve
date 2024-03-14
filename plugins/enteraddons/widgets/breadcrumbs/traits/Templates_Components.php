<?php 
namespace Enteraddons\Widgets\Breadcrumbs\Traits;
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
    
    protected static function customBreadcrumbs() {
        $settings = self::getSettings();
        if( !empty( $settings['custom_breadcrumbs'] ) ) {
            echo '<ul class="ea-page-title-wrap">';
                foreach( $settings['custom_breadcrumbs'] as $val ) {
                    //title 
                    if( !empty( $val['link']['url'] ) ) {
                        echo '<li>'.self::linkOpen( $val ).esc_html( $val['title'] ).self::linkClose().'</li>
                        <span class="ea-breadcrumb-delimiter">'.esc_html( $settings['delimiter'] ).'</span>';
                    } else {
                        echo '<li class="active">'.esc_html( $val['title'] ).'</li>';
                    }
                }
            echo '</ul>';
        }
    }

    protected static function linkOpen( $val ) {
        $settings = $val;
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a href="'.esc_url( $settings['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }

    protected static function linkClose() {
        return '</a>';
    }
    
}