<?php 
namespace Enteraddons\Widgets\Image_Compare\Traits;
/**
 * Enteraddons team template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
	public static function markup_style_1() {

        $settings = self::getSettings();
        
        $orientation  = !empty( $settings['orientation'] ) ? $settings['orientation'] : '';
        $originalText = !empty( $settings['img_compare_original_title'] ) ? $settings['img_compare_original_title'] : '';
        $originalImg  = !empty( $settings['original_image']['url'] ) ? $settings['original_image']['url'] : '';
        $originalImgAltText = \Elementor\Control_Media::get_image_alt( $settings['original_image'] );

        $modifiedText = !empty( $settings['img_compare_modified_title'] ) ? $settings['img_compare_modified_title'] : '';
        $modifiedImg  = !empty( $settings['modified_image']['url'] ) ? $settings['modified_image']['url'] : '';
        $modifiedImgAltText = \Elementor\Control_Media::get_image_alt( $settings['modified_image'] );

        echo '<div class="cd-image-container" data-orientation="'.esc_attr( $orientation ).'" data-original-text="'.esc_html( $originalText ).'" data-modified-text="'.esc_html( $modifiedText ).'">
                <img src="'.esc_url( $originalImg ).'" alt="'.esc_attr( $originalImgAltText ).'">
                <img src="'.esc_url( $modifiedImg ).'" alt="'.esc_attr( $modifiedImgAltText ).'">
            </div>';
	}

}