<?php 
namespace Enteraddons\Widgets\Image_Gallery\Traits;
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

        $linkType = $settings['link_type'];
        $popIcon = $settings['icon'];
        $is_overlay = $settings['overly_active'] ? ' overlay-active' : '';
        $title_show_on = $settings['title_show_on'] ? ' '.$settings['title_show_on'] : '';
        $icon_show_on = $settings['icon_show_on'] ? ' '.$settings['icon_show_on'] : '';
        $is_title = $settings['title_show'];
        ?>
        <div class="<?php echo esc_attr( 'enteraddons-image-gallery'.$is_overlay.$title_show_on.$icon_show_on ); ?>">
            <div class="grid--wrap enteraddons-grid-col-<?php echo esc_attr($settings['column']); ?>">
                <?php 
                if( !empty( $settings['image_gallery'] ) ):
                    foreach( $settings['image_gallery'] as $gimage ):

                    $columnSpace = !empty( $gimage['column_space'] ) ? $gimage['column_space'] : '1';
                ?>
                <div class="grid-item-before-inner-top enteraddons-grid-col-space-<?php echo esc_attr( $columnSpace ); ?>">
                    <?php
                    if( $linkType == 'wrap_link' ) {
                        echo self::linkOpen( $gimage['link'] );
                    }
                    ?>
                    <div class="grid-item-inner-top">
                        <div class="grid-item-inner">
                            <?php
                            if( !empty( $linkType == 'prettyphoto' ) ) {
                                self::imagePopup( $gimage, $popIcon );
                            }
                            // Image
                            self::image( $gimage );
                            // Overlay
                            self::overlay( $gimage, $is_title );
                                                               
                            ?>
                        </div>
                    </div>
                    <?php 
                    if( $linkType == 'wrap_link' ) {
                        echo self::linkClose();
                    }
                    ?>
                </div>
                <?php
                endforeach;
                endif;
                ?>

            </div>
        </div>
        <?php
    }

}