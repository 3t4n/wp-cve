<?php 
namespace Enteraddons\Widgets\Pricing_Table_Tab\Traits;
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
        ?>
        <div class="price-wrap enteraddons-pricing-table-tab-wrapper">
            <?php 
            self::tab_switcher();
            ?>
            <div class="price-content">
                <div class="monthly-price show">
                    <?php self::tabTwoContent(); ?>
                    
                </div>
                <div class="yearly-price">
                    <?php self::tabOneContent(); ?>               
                </div>
            </div>
        </div>
        <?php
    }

}