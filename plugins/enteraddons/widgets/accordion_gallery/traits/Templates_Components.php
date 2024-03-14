<?php 
namespace Enteraddons\Widgets\Accordion_Gallery\Traits;
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

     // Title
     public static function gallery_title( $item ) {
        if( !empty( $item['gallery_title'] ) ) {
            echo '<h2 class="ea-vg-title">'.esc_html( $item['gallery_title'] ).'</h2>';    
        }
    }

    // Sub Title
    public static function gallery_subtitle( $item ) {

        if( !empty( $item['gallery_subtitle'] ) ) {
            echo '<p class="ea-vg-subtitle">'.esc_html( $item['gallery_subtitle'] ).'</p>';     
        }
    }

    // Descriptions
    public static function gallery_description( $item ) {

        if( !empty( $item['description'] ) ) {
            echo '<div class="ea-vg-description">';
                echo '<p>'.esc_html( $item['description'] ).'</p>';
            echo '</div>';
        }
    }

    // Button
    public static function trigger_button( $item ) {

        if( !empty( $item['button_label'] ) ) {
            echo esc_html( $item['button_label'] );
        }
         
    }

    // Image
    public static function gallery_image( $item ) {

        $altText = \Elementor\Control_Media::get_image_alt( $item['image'] );
        if( !empty( $item['image']['url'] ) ) {
           echo  '<img src="'.esc_url( $item['image']['url'] ).'" alt="'.esc_attr( $altText ).'" >';
        }
    }

    // Link
    public static function gallery_button( $item  ) {
        $label     = !empty( $item ['link_label'] ) ?  $item['link_label'] : esc_html__( 'VIEW MORE', 'enteraddons' );
        echo \Enteraddons\Classes\Helper::getElementorLinkHandler( $item['more_link'], $label, 'ea-gallery-button' );
    }
}