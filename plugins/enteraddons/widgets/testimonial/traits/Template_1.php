<?php 
namespace Enteraddons\Widgets\Testimonial\Traits;
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
        <div class="enteraddons-testimonial-slider slider-style--default owl-carousel" data-slidersettings="<?php echo htmlspecialchars( $sliderSettings, ENT_QUOTES, 'UTF-8'); ?>">
            <?php
            if( !empty( $settings['testimonial'] ) ):
                $i = 0;
                foreach( $settings['testimonial'] as $testimonial ):
                    $i++;
            ?>
            <div class="enteraddons-single-testimonial text-center testimonial-style--default">
                <?php
                // Top Thumb
                if( !empty( $testimonial['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'top' ) {
                    self::thumbnail( $testimonial['testimonial_img'] );
                }

                // Author Info
                if( $settings['author_info_alignment'] == 'top' ) {
                    // Author Name
                    if( !empty( $testimonial['testimonial_name'] ) ) {
                        self::authorName( $testimonial['testimonial_name'] );
                    }
                    // Author Designation
                    if( !empty( $testimonial['testimonial_designation'] ) ) {
                        self::authorDesignation( $testimonial['testimonial_designation'] );
                    }
                    // Author Location
                    if( !empty( $testimonial['testimonial_location'] ) ) {
                        self::authorLocation( $testimonial['testimonial_location'] );
                    }

                }

                // Rating
                if( !empty( $testimonial['testimonial_rating'] ) && $settings['rating_alignment'] == 'top' ) {
                    self::rating( $testimonial['testimonial_rating'] );
                }
                
                // Testimonial Text
                if( !empty( $testimonial['testimonial_desc'] ) ) {
                    self::descriptions( $testimonial['testimonial_desc'], $settings['left_quote_icon'], $settings['right_quote_icon'] );
                }
                
                ?>
                <div class="enteraddons-testimonial-author">
                    <?php
                    // Top Thumb
                    if( !empty( $testimonial['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'bottom' ) {
                        self::thumbnail( $testimonial['testimonial_img'] );
                    }

                    // Author Info
                    if( $settings['author_info_alignment'] == 'bottom' ) {
                        // Author Name
                        if( !empty( $testimonial['testimonial_name'] ) ) {
                            self::authorName( $testimonial['testimonial_name'] );
                        }
                        // Author Designation
                        if( !empty( $testimonial['testimonial_designation'] ) ) {
                            self::authorDesignation( $testimonial['testimonial_designation'] );
                        }
                        // Author Location
                        if( !empty( $testimonial['testimonial_location'] ) ) {
                            self::authorLocation( $testimonial['testimonial_location'] );
                        }
                    }
                    // Rating
                    if( !empty( $testimonial['testimonial_rating'] ) && $settings['rating_alignment'] == 'bottom' ) {
                        self::rating( $testimonial['testimonial_rating'] );
                    }

                    ?>
                </div>
            </div>
            <?php 
                endforeach;
            endif;
            ?>
        </div>

        <?php
    }

}