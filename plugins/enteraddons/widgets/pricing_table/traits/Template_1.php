<?php 
namespace Enteraddons\Widgets\Pricing_Table\Traits;
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
        $tblHoverActive = !empty( $settings['table_hover_effect'] ) ? ' active-table-hover-effect' : '';
        ?>
        <div class="enteraddons-wid-con">
            <div class="enteraddons-pricing-table text-center<?php echo esc_attr( $tblHoverActive ); ?>">
                <?php
                // Badge
                self::badge();
                ?>
                <div class="enteraddons-pt-head">
                    <?php
                    // Title
                    self::title();
                    // Price
                    self::price();
                    // Sub title
                    self::subTitle();
                    ?>
                </div>
                <?php
                // Features
                self::features();
                // Footer button
                self::button();
                ?>
            </div>
        </div>
        <?php
    }

}