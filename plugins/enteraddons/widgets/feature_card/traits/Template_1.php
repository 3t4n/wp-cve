<?php 
namespace Enteraddons\Widgets\Feature_Card\Traits;
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
        $vertical_direction = !empty( $settings['icon_grid_vertical_direction'] ) ? ' '.$settings['icon_grid_vertical_direction'] : '';
        ?>
        <div class="<?php echo esc_attr( 'enteraddons-single-feature '.$settings['icon_layout'].' '.$settings['icon_overlap_alignment'].$vertical_direction.' '.$settings['item_hover_effect'] ); ?>">
            <?php 
            // Icon
            self::icon();
            ?>
            <!-- Content -->
            <div class="enteraddons-single-feature-content">
                <?php 
                self::title();
                //
                self::descriptions();
                ?>
            </div>
            <!-- End Content -->
        </div>
        <?php
    }

}