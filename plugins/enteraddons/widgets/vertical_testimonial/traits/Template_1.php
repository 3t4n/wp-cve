<?php 
namespace Enteraddons\Widgets\Vertical_Testimonial\Traits;
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
        $verticalSliderSettings = self::carouselSettings();
        $id = self::getDisplayID();
        $slider_id = "ea--feedback-slider".$id;

        ?> 
        <div class="ea--feedback-slider-wrapper">
            <div id ="<?php echo esc_attr( $slider_id ) ?>"  class="ea--feedback-slider swiper-container" data-slider-id ="<?php echo esc_html( $slider_id ); ?>" data-vertical-slider-settings="<?php echo htmlspecialchars( $verticalSliderSettings, ENT_QUOTES, 'UTF-8' ); ?>">
                <div class="swiper-wrapper">
                    <?php 
                    if( ! empty( $settings['slider_list'] ) ) : foreach( $settings['slider_list'] as $item ) : 
                    ?>
                    <!-- single item -->
                    <div class="swiper-slide ea--single-feedback-slider">
                        <div class="single-feedback-inner">
                            <?php self::thumbnail( $item ); ?>
                            <div class="customer-feedback">
                                <?php
                                if( !empty( $item['show_review'] ) ) {
                                    self::star( $item );
                                }
                                self::clientName( $item ); 
                                self::clientReview( $item );
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--End of single item -->
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <?php 
            if( !empty( $settings['slide_nav'] ) ):
            ?>
            <div class="ea-swiper-pagi-nav">
                <div class="ea--swiper-button-prev ea--swiper-nav-button">
                    <?php 
                    echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon_up'] );
                    ?>
                </div>
                <div class="ea--swiper-button-next ea--swiper-nav-button">
                    <?php 
                    echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['icon_down'] );
                    ?>
                </div>
            </div>
            <?php 
            endif;
            ?>

        </div>
        <?php
    }

}