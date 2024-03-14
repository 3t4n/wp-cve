<?php 
namespace Enteraddons\Widgets\Image_Gallery\Traits;
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

    protected static function image( $data ) {
        $altText     = \Elementor\Control_Media::get_image_alt( $data['img'] );
        echo '<img src="'.esc_url( $data['img']['url'] ).'" class="g-img" alt="'.esc_attr( $altText ).'">';
    }

    protected static function imagePopup( $gimage, $popIcon ) {
        echo '<a href="'.esc_url( $gimage['img']['url'] ).'">';                
            echo \Enteraddons\Classes\Helper::getElementorIcon( $popIcon );
        echo '</a>';
    }

    protected static function overlay( $gimage, $is_title = '' ) {
        echo '<div class="overlay">';                
            self::title($gimage , $is_title);
        echo '</div>';
    }

    protected static function title($gimage, $is_title = '') {

        if( empty( $is_title ) ) {
            return;
        }

        // Title
        if( !empty( $gimage['title'] ) ) {
            echo '<h5 class="gallery-title">'.esc_html( $gimage['title'] ).'</h5>';
        }
        // Tags
        if( !empty( $gimage['tags'] ) ) {
            echo '<span class="tags">'.esc_html( $gimage['tags'] ).'</span>';
        }

    }

    protected static function linkOpen( $link, $class = '' ) {
        //
        $target = '_self';
        if( !empty( $link['is_external'] ) && $link['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a class="'.esc_attr( $class ).'" href="'.esc_url( $link['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    
    protected static function linkClose() {
        return '</a>';
    }

    
}