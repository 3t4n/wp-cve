<?php 
namespace Enteraddons\Widgets\Logo_Carousel\Traits;
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
        $image_width_type = $settings['logo_image_width_type'];
        ?>
        <div class="enteraddons-client-slider owl-carousel <?php echo esc_attr( 'hover--style-'.$settings['logo_hover_style'] ); ?>" data-slidersettings="<?php echo htmlspecialchars( $sliderSettings, ENT_QUOTES, 'UTF-8'); ?>">
               
               <?php
               // Single Client
               if( !empty( $settings['logo_carousel'] ) ):
                foreach( $settings['logo_carousel'] as $logo ):
                    echo '<div class="enteraddons-single-client">';
                    // anchor open
                    if( !empty( $logo['link']['url'] ) ) {
                        echo self::linkOpen( $logo['link'] );
                    }
                        echo '<img class="'.esc_attr( $image_width_type ).'" src="'.esc_url( $logo['img']['url'] ).'" alt="'.esc_attr( $logo['name'] ).'">';
                    // Anchor close
                    if( !empty( $logo['link']['url'] ) ) {
                        echo self::linkClose();
                    }
                    echo '</div>';
                endforeach;
                endif;
                ?>
            </div>

        <?php
    }

}