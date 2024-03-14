<?php 
namespace Enteraddons\Widgets\Icon_Card\Traits;
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
        
        ?>
        <div class="position-relative single-extension-wrap">
            <div class="extension-shape"></div>
            <div class="single-extension">
                <div class="extension-top">
                    <?php
                    //Icon
                    self::icon();
                    self::pricing();
                    ?>
                </div>
                <div class="extension-content">
                    <?php
                    self::title();
                    self::descriptions();
                    self::button();
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

}