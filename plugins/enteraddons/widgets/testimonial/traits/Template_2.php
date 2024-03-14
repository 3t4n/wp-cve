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

trait Template_2 {
    
    public static function markup_style_2() {

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
                // Author Info
                if( $settings['author_info_alignment'] == 'top' ) {
                    echo '<div class="author-meta-wrap">';
                        // Thumb
                        if( !empty( $testimonial['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'top' ) {
                            echo '<div class="author-meta-thumb">';
                            self::thumbnail( $testimonial['testimonial_img'] );
                            echo '</div>';
                        }
                        // Info
                        echo '<div class="author-meta-info">';
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
                        echo '</div>';
                    echo '</div>';
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
                    // Author Info
                    if( $settings['author_info_alignment'] == 'bottom' ) {
                        echo '<div class="author-meta-wrap">';
                        // Thumb
                        if( !empty( $testimonial['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'top' ) {
                            echo '<div class="author-meta-thumb">';
                            self::thumbnail( $testimonial['testimonial_img'] );
                            echo '</div>';
                        }
                        // Info
                        echo '<div class="author-meta-info">';
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
                        echo '</div>';
                        echo '</div>';
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