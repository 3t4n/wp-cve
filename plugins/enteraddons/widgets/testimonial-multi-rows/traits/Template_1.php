<?php 
namespace Enteraddons\Widgets\Testimonial_Multi_Rows\Traits;
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

        $perRowItem = !empty( $settings['per_row_item_number'] ) ? $settings['per_row_item_number'] : '';

        ?>
        <div class="enteraddons-review-multi-row-container">
            <div class="review-wrap">
            <?php
            if( !empty( $settings['testimonial'] ) ):

                $i = 0;

                $totalItems = count( $settings['testimonial'] );

                foreach( $settings['testimonial'] as $testimonial ):
                    $i++;
            ?>
            <div class="review-single">
                <div class="review-info">
                    <?php self::thumbnail( $testimonial['testimonial_img'] ); ?>
                    <div class="content">
                        <?php
                        // Author Name
                        if( !empty( $testimonial['testimonial_name'] ) ) {
                            self::authorName( $testimonial['testimonial_name'] );
                        }
                        // Rating
                        if( !empty( $testimonial['testimonial_rating'] ) ) {
                            self::rating( $testimonial['testimonial_rating'] );
                        }
                        ?>
                    </div>
                </div>
                <?php 
                // Testimonial Text
                if( !empty( $testimonial['testimonial_desc'] ) ) {
                    self::descriptions( $testimonial['testimonial_desc'], $settings['left_quote_icon'], $settings['right_quote_icon'] );
                }
                ?>
            </div>
            <?php
            // Break review wrap
            if ( $i % $perRowItem == 0 && $totalItems > $i ) {
                // If the post is the last post, and it is odd
                echo '</div> <div class="review-wrap">';
            }
            
                endforeach;
            endif;
            ?>

            </div>
        </div>

        <?php
    }

}