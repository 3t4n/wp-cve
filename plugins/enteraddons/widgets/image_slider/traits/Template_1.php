<?php 
namespace Enteraddons\Widgets\Image_Slider\Traits;
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

trait Template_1 {
    
    public static function markup_style_1() {

        $settings       = self::getDisplaySettings();
        $sliderSettings = self::carouselSettings();

        ?>
        <div class="enteraddons-app-slider enteraddons-slider owl-carousel dots-style--one" data-slidersettings="<?php echo htmlspecialchars( $sliderSettings, ENT_QUOTES, 'UTF-8'); ?>">
            <?php
            // Single Slide
            if( !empty( $settings['image_slider'] ) ) {
                foreach( $settings['image_slider'] as $slider ) {
                    echo '<div class="enteraddons-single-slide">';
                        self::thumbnail( $slider['slider_img'] );
                        //
                        if( !empty( $slider['title'] ) || !empty( $slider['sub_title'] ) ) {
                            echo '<div class="image-info">';
                                self::title( $slider['title'] );
                                self::sub_title( $slider['sub_title'] );
                            echo '</div>';
                        }                      

                    echo '</div>';
                }
                
            }
            ?>            
        </div>
        <?php
    }

}