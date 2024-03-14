<?php 
namespace Enteraddons\Widgets\Travel_Gallery\Traits;
/**
 * Enteraddons Travel Gallery template class
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
	

      echo '<div class="ea-tg-wrapper">';
        if( !empty( $settings['gallery_image_list'] ) ) {
          foreach( $settings['gallery_image_list'] as $item ) {
            echo '<div class="ea-tg-slide ea-tg-anim-in">';
              self::image( $item ); 
              echo '<div class="ea-tg-overlay"></div>';
              echo '<div class="ea-tg-content">';
                self::place_name( $item );
                self::country_logo( $item );
                echo '<ul class="ea-tg-city-info">';
                  self::county( $item ); 
                  self::founded( $item ); 
                  self::population( $item ); 
                echo '</ul>';
              echo '</div>';
              echo '<div class="ea-tg-btn-close"></div>';
            echo '</div>';
          }
        }
      echo '</div>';
	
	}

}