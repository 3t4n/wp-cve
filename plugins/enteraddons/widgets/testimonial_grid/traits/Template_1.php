<?php 
namespace Enteraddons\Widgets\Testimonial_Grid\Traits;
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
        $settings   = self::getSettings();
        
        ?>
        <div class="ea-testimonial-slider">
        
            <div class="ea-single-testimonial text-center ea-testimonial-style--default">
                <?php
                // Top Thumb
                if( !empty( $settings['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'top' ) {
                    self::thumbnail( $settings['testimonial_img'] );
                }

                // Author Info
                if( $settings['author_info_alignment'] == 'top' ) {
                    // Author Name
                    if( !empty( $settings['testimonial_name'] ) ) {
                        self::authorName( $settings['testimonial_name'] );
                    }
                    // Author Designation
                    if( !empty( $settings['testimonial_designation'] ) ) {
                        self::authorDesignation( $settings['testimonial_designation'] );
                    }
                    // Author Location
                    if( !empty( $settings['testimonial_location'] ) ) {
                        self::authorLocation( $settings['testimonial_location'] );
                    }

                }

                // Rating
                if( !empty( $settings['testimonial_rating'] ) && $settings['rating_alignment'] == 'top' ) {
                    self::rating( $settings['testimonial_rating'] );
                }
                
                // Testimonial Text
                if( !empty( $settings['testimonial_desc'] ) ) {
                    self::descriptions( $settings['testimonial_desc'], $settings['left_quote_icon'], $settings['right_quote_icon'] );
                }
                
                ?>
                <div class="ea-testimonial-author">
                    <?php
                    // Top Thumb
                    if( !empty( $settings['testimonial_img'] ) && $settings['thumbnail_alignment'] == 'bottom' ) {
                        self::thumbnail( $settings['testimonial_img'] );
                    }

                    // Author Info
                    if( $settings['author_info_alignment'] == 'bottom' ) {
                        // Author Name
                        if( !empty( $settings['testimonial_name'] ) ) {
                            self::authorName( $settings['testimonial_name'] );
                        }
                        // Author Designation
                        if( !empty( $settings['testimonial_designation'] ) ) {
                            self::authorDesignation( $settings['testimonial_designation'] );
                        }
                        // Author Location
                        if( !empty( $settings['testimonial_location'] ) ) {
                            self::authorLocation( $settings['testimonial_location'] );
                        }
                    }
                    // Rating
                    if( !empty( $settings['testimonial_rating'] ) && $settings['rating_alignment'] == 'bottom' ) {
                        self::rating( $settings['testimonial_rating'] );
                    }

                    ?>
                </div>
            </div>
        </div>
        
        <?php
    }

}