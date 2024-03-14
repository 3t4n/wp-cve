<?php 
namespace Enteraddons\Widgets\Photo_Reveal_Animation\Traits;
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
        $settings = self::getDisplaySettings();
		?>
          <div class="eaam-has-animation <?php echo esc_attr( $settings['image_animation_style'] ); ?>" data-delay="<?php  echo esc_html( $settings['image_animation_data_delay'] );?>">
           <?php 
               self::image();
           ?>
          </div>
          <?php
        
	}

}