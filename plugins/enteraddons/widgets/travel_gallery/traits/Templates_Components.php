<?php 
namespace Enteraddons\Widgets\Travel_Gallery\Traits;
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
     public static function image( $image ) {

        $altText = \Elementor\Control_Media::get_image_alt( $image['image'] );
        if( !empty( $image['image']['url'] ) ) {
          echo  '<div class="ea-tg-image" style="background-image: url('.esc_url( $image['image']['url'] ).')" alt="'.esc_attr( $altText ).'">
            </div>';
        }
    }
  
    // City Name
    public static function place_name( $place_name ) {

        if( !empty( $place_name['place_name'] ) ) {
            echo '<h1 class="ea-tg-title" data-title="'.esc_attr( $place_name['place_name'] ).'">'.esc_html( $place_name['place_name'] ).'</h1>';
        }
    }

     // Country Logo
     public static function country_logo( $country_logo ) {

        $altText = \Elementor\Control_Media::get_image_alt( $country_logo['country_logo'] );
        if( !empty( $country_logo['country_logo']['url'] ) ) {
          echo  '<div class="ea-tg-emblem" style="background-image: url('.esc_url( $country_logo['country_logo']['url'] ).')" alt="'.esc_attr( $altText ).'">
            </div>';
        }
    }

     // Country Name
     public static function county( $country_name ) {

        if( !empty( $country_name['country_name'] ) ) {
            echo '<li class="ea-country">Country: '.esc_html( $country_name['country_name'] ).'</li>';
        }
    } 
    
     // Foundation Year
     public static function founded( $founded ) {

        if( !empty( $founded['founded'] ) ) {
            echo '<li class="ea-founded">Founded: '.esc_html( $founded['founded'] ).'</li>';
        }
    }
    
     // Country Name
     public static function population( $population ) {

        if( !empty( $population['population'] ) ) {
            echo '<li class="ea-population">Population: '.esc_html( $population['population'] ).'</li>';
        }
    }

}