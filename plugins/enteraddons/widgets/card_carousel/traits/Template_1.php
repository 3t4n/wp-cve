<?php 
namespace Enteraddons\Widgets\Card_Carousel\Traits;
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

        $settings = self::getDisplaySettings();
        $sliderSettings = self::carouselSettings();

        ?>        
        <div class="enteraddons-card-carousel enteraddons-slider owl-carousel enteraddons-nav-style--seventeen enteraddons-slider-nav-middle" data-slidersettings="<?php echo htmlspecialchars( $sliderSettings, ENT_QUOTES, 'UTF-8'); ?>">

            <?php
                // Single Client
                if( !empty( $settings['card_carousel'] ) ):
                    foreach( $settings['card_carousel'] as $data ):
                        echo '<div class="enteraddons-info-box box-style--one text-center">';
                                self::icon($data);
                            echo '<div class="enteraddons-info-box-content">';
                                self::title($data);
                                self::description($data);
                            echo '</div>';

                        echo '</div>';
                    endforeach;
                endif;
            ?>
            
        </div>

        <?php
    }

}