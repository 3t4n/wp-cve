<?php 
namespace Enteraddons\Widgets\Flip_Card\Traits;
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
        <div class="ea-flip-card-wrap">
            <div class="ea-flip-card">
                <div class="ea-flip-card-inner">
                    <div class="ea-flip-card-front">
                        <?php 
                        self::front_icon();
                        //
                        self::front_title();
                        //
                        self::front_descriptions();
                        ?>
                    </div>
                    <div class="ea-flip-card-back">
                        <?php
                        self::back_icon();
                        //
                        self::back_title();
                        //
                        self::back_descriptions();
                        //
                        self::back_button();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}